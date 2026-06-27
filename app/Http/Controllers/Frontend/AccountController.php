<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\CustomerCoupon;
use App\Models\Order;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    private function customer() { return auth('customer')->user(); }

    public function index()
    {
        $user = $this->customer();
        $recentOrders = $user->orders()->latest()->take(5)->get();
        $totalOrders  = $user->orders()->count();
        $wishlistCount = $user->wishlists()->count();
        return view('frontend.account.index', compact('user', 'recentOrders', 'totalOrders', 'wishlistCount'));
    }

    public function profile()
    {
        return view('frontend.account.profile', ['user' => $this->customer()]);
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
        ]);
        $this->customer()->update($request->only('name', 'phone'));
        return back()->with('success', 'อัปเดตข้อมูลสำเร็จ');
    }

    public function orders()
    {
        $orders = $this->customer()->orders()->latest()->paginate(10);
        return view('frontend.account.orders', compact('orders'));
    }

    public function orderShow(Order $order)
    {
        if ($order->customer_id !== auth('customer')->id()) abort(403);
        $order->load('items', 'pickupShowroom');
        return view('frontend.account.order-show', compact('order'));
    }

    public function reorder(Order $order, CartService $cart)
    {
        if ($order->customer_id !== auth('customer')->id()) abort(403);
        foreach ($order->items as $item) {
            $cart->addItem($item->product_id, $item->quantity, $item->variant_id);
        }
        return redirect()->route('cart')->with('success', 'เพิ่มสินค้าจากออเดอร์เดิมลงตะกร้าแล้ว');
    }

    public function confirmReceived(Order $order)
    {
        if ($order->customer_id !== auth('customer')->id()) abort(403);
        if ($order->status !== 'shipped') {
            return back()->with('error', 'ไม่สามารถยืนยันรับสินค้าในสถานะนี้ได้');
        }
        $order->update([
            'status'       => 'delivered',
            'delivered_at' => now(),
            'picked_up_at' => $order->is_pickup ? now() : $order->picked_up_at,
        ]);
        return back()->with('success', 'ขอบคุณที่ยืนยันการรับสินค้า 🎉');
    }

    public function cancelOrder(Order $order)
    {
        if ($order->customer_id !== auth('customer')->id()) abort(403);
        // ยกเลิกเองได้เฉพาะตอนยังไม่ชำระเงิน (ยังไม่แนบสลิป) เท่านั้น
        if ($order->status !== 'pending_payment') {
            return back()->with('error', 'ยกเลิกได้เฉพาะออเดอร์ที่รอชำระเงินเท่านั้น หากต้องการยกเลิกออเดอร์นี้กรุณาติดต่อทีมงาน');
        }
        DB::transaction(function () use ($order) {
            $this->restoreStock($order);
            $order->update([
                'status'           => 'cancelled',
                'rejection_reason' => 'ลูกค้ายกเลิกออเดอร์เอง',
            ]);
        });
        return back()->with('success', 'ยกเลิกออเดอร์เรียบร้อยแล้ว');
    }

    /** คืนสินค้าเข้าสต๊อก: มีไซส์คืนที่ไซส์, ไม่มีคืนที่สินค้ารวม */
    private function restoreStock(Order $order): void
    {
        $order->loadMissing('items.product', 'items.variant');
        foreach ($order->items as $item) {
            if ($item->product && $item->product->manage_stock) {
                if ($item->variant) {
                    $item->variant->increment('stock_quantity', $item->quantity);
                } else {
                    $item->product->increment('stock_quantity', $item->quantity);
                }
                $item->product->decrement('sale_count', $item->quantity);
            }
        }
    }

    public function addresses()
    {
        $addresses = $this->customer()->addresses()->get();
        return view('frontend.account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'label'          => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line1'  => 'required|string',
            'address_line2'  => 'nullable|string',
            'district'       => 'required|string',
            'amphoe'         => 'required|string',
            'province'       => 'required|string',
            'postcode'       => 'required|string|max:10',
            'is_default'     => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            $this->customer()->addresses()->update(['is_default' => false]);
        }
        $this->customer()->addresses()->create($data);
        return back()->with('success', 'เพิ่มที่อยู่เรียบร้อยแล้ว');
    }

    public function updateAddress(Request $request, Address $address)
    {
        if ($address->customer_id !== auth('customer')->id()) abort(403);
        $data = $request->validate([
            'label'          => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line1'  => 'required|string',
            'address_line2'  => 'nullable|string',
            'district'       => 'required|string',
            'amphoe'         => 'required|string',
            'province'       => 'required|string',
            'postcode'       => 'required|string|max:10',
            'is_default'     => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            $this->customer()->addresses()->update(['is_default' => false]);
        }
        $address->update($data);
        return back()->with('success', 'อัปเดตที่อยู่เรียบร้อยแล้ว');
    }

    public function deleteAddress(Address $address)
    {
        if ($address->customer_id !== auth('customer')->id()) abort(403);
        $address->delete();
        return back()->with('success', 'ลบที่อยู่แล้ว');
    }

    public function wishlist()
    {
        $wishlists = $this->customer()->wishlists()->with('product.images')->paginate(12);
        return view('frontend.account.wishlist', compact('wishlists'));
    }

    public function toggleWishlist(Product $product)
    {
        $customer = $this->customer();
        $exists = $customer->wishlists()->where('product_id', $product->id)->first();

        if ($exists) {
            $exists->delete();
            $message = 'ลบออกจากรายการโปรดแล้ว';
            $inWishlist = false;
        } else {
            $customer->wishlists()->create(['product_id' => $product->id]);
            $message = 'เพิ่มในรายการโปรดแล้ว';
            $inWishlist = true;
        }

        if (request()->expectsJson()) {
            return response()->json(['in_wishlist' => $inWishlist, 'message' => $message]);
        }
        return back()->with('success', $message);
    }

    public function coupons()
    {
        $myCoupons = CustomerCoupon::where('customer_id', auth('customer')->id())
            ->with(['coupon.products', 'coupon.categories'])
            ->latest('collected_at')
            ->get()
            ->filter(fn($cc) => $cc->coupon && $cc->coupon->isValid())
            ->values();

        return view('frontend.account.coupons', compact('myCoupons'));
    }

    public function quotes()
    {
        $quotes = $this->customer()->quoteRequests()->latest()->paginate(10);
        return view('frontend.account.quotes', compact('quotes'));
    }
}
