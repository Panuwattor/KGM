<?php

namespace App\Http\Controllers\Admin;

use App\Exports\OrdersPackingExport;
use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Order;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('customer')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('order_number', 'like', '%' . $request->search . '%')
                  ->orWhere('ship_name', 'like', '%' . $request->search . '%')
                  ->orWhere('ship_phone', 'like', '%' . $request->search . '%');
            });
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->paginate(20)->withQueryString();
        $statusCounts = Order::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status');

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function exportPacking(Request $request)
    {
        $filters = $request->only('status', 'search', 'date_from', 'date_to');
        $filename = 'orders-packing-' . now()->format('Y-m-d-Hi') . '.xlsx';

        AdminLog::record('exported', 'Export รายการออเดอร์สำหรับแพ็กสินค้า');

        return Excel::download(new OrdersPackingExport($filters), $filename);
    }

    public function show(Order $order)
    {
        $order->load('items.product', 'customer', 'pickupShowroom');
        $shippingProviders = \App\Models\ShippingProvider::active()->pluck('name');
        return view('admin.orders.show', compact('order', 'shippingProviders'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status'     => 'required|in:' . implode(',', array_keys(Order::STATUS_LABELS)),
            'admin_note' => 'nullable|string',
        ]);

        $old = $order->status;
        $new = $request->status;

        // ออเดอร์ที่ยกเลิก/คืนเงินแล้วถือเป็นสถานะสิ้นสุด — เปลี่ยนสถานะต่อไม่ได้
        // (สต๊อกถูกคืนไปแล้ว ถ้าเปลี่ยนกลับสต๊อกจะกลับไปกลับมาไม่ตรงความจริง)
        if (in_array($old, ['cancelled', 'refunded']) && $new !== $old) {
            return back()->with('error', 'ออเดอร์นี้ถูก' . ($old === 'refunded' ? 'คืนเงิน' : 'ยกเลิก') . 'แล้ว ไม่สามารถเปลี่ยนสถานะได้');
        }

        $data = ['status' => $new];

        // ฟอร์มหมายเหตุแอดมินส่ง admin_note มาด้วย -> บันทึกให้ครบ (เดิมตกหล่น)
        if ($request->has('admin_note')) {
            $data['admin_note'] = $request->admin_note;
        }

        // บันทึกเวลาเมื่อเปลี่ยนสถานะแบบ manual (เผื่อแอดมินข้ามขั้นตอน)
        if ($new === 'shipped' && ! $order->shipped_at) {
            $data['shipped_at'] = now();
        }
        if ($new === 'delivered' && ! $order->delivered_at) {
            $data['delivered_at'] = now();
            if ($order->is_pickup && ! $order->picked_up_at) {
                $data['picked_up_at'] = now();
            }
        }

        // คืนสต็อก + ลดยอดขาย เมื่อยกเลิก/คืนเงิน (เฉพาะออเดอร์ที่ยังไม่เคยถูกยกเลิกมาก่อน)
        if (in_array($new, ['cancelled', 'refunded']) && ! in_array($old, ['cancelled', 'refunded'])) {
            $this->restoreStock($order);
        }

        $order->update($data);

        if ($old !== $new) {
            AdminLog::record('updated', "เปลี่ยนสถานะออเดอร์ {$order->order_number}: {$old} → {$new}", $order);
            return back()->with('success', 'อัปเดตสถานะออเดอร์แล้ว');
        }

        AdminLog::record('updated', "บันทึกหมายเหตุออเดอร์ {$order->order_number}", $order);
        return back()->with('success', 'บันทึกหมายเหตุแล้ว');
    }

    private function restoreStock(Order $order): void
    {
        $order->loadMissing('items.product', 'items.variant');
        foreach ($order->items as $item) {
            if ($item->product && $item->product->manage_stock) {
                // คืนสต๊อกตรงกับตอนตัด: มีไซส์คืนที่ไซส์, ไม่มีคืนที่สินค้ารวม
                if ($item->variant) {
                    $item->variant->increment('stock_quantity', $item->quantity);
                } else {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
                $item->product->decrement('sale_count', $item->quantity);
            }
        }
    }

    public function verifyPayment(Order $order)
    {
        $order->update([
            'status' => 'payment_verified',
            'payment_verified_at' => now(),
            'verified_by' => auth()->id(),
            'rejection_reason' => null,
        ]);
        AdminLog::record('approved', "อนุมัติการชำระเงิน ออเดอร์ {$order->order_number}", $order);
        return back()->with('success', 'อนุมัติการชำระเงินแล้ว');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        $request->validate(['reason' => 'required|string']);
        $order->update([
            'status' => 'pending_payment',
            'payment_slip' => null,
            'rejection_reason' => $request->reason,
        ]);
        AdminLog::record('rejected', "ปฏิเสธการชำระเงิน ออเดอร์ {$order->order_number}", $order);
        return back()->with('success', 'ปฏิเสธสลิปการชำระเงินแล้ว');
    }

    public function updateTracking(Request $request, Order $order)
    {
        $request->validate([
            'tracking_number'   => 'required|string',
            'shipping_provider' => 'required|string',
        ]);
        $order->update([
            'tracking_number'   => $request->tracking_number,
            'shipping_provider' => $request->shipping_provider,
            'status'            => 'shipped',
            'shipped_at'        => now(),
        ]);
        AdminLog::record('shipped', "อัปเดต Tracking ออเดอร์ {$order->order_number}: {$request->tracking_number}", $order);
        return back()->with('success', 'อัปเดต Tracking Number แล้ว');
    }
}
