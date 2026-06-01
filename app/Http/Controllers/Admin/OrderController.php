<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminLog;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('user')->latest();

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

    public function show(Order $order)
    {
        $order->load('items.product', 'user');
        return view('admin.orders.show', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $request->validate(['status' => 'required|in:' . implode(',', array_keys(Order::STATUS_LABELS))]);
        $old = $order->status;
        $order->update(['status' => $request->status]);
        AdminLog::record('updated', "เปลี่ยนสถานะออเดอร์ {$order->order_number}: {$old} → {$request->status}", $order);
        return back()->with('success', 'อัปเดตสถานะออเดอร์แล้ว');
    }

    public function verifyPayment(Order $order)
    {
        $order->update([
            'status' => 'payment_verified',
            'payment_verified_at' => now(),
            'verified_by' => auth()->id(),
        ]);
        AdminLog::record('approved', "อนุมัติการชำระเงิน ออเดอร์ {$order->order_number}", $order);
        return back()->with('success', 'อนุมัติการชำระเงินแล้ว');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        $order->update([
            'status' => 'pending_payment',
            'payment_slip' => null,
            'admin_note' => $request->reason,
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
