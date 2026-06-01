<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $cartItems = $this->cart->getCartItems();
        $subtotal = $this->cart->getSubtotal();
        $couponCode = session('coupon_code');
        $coupon = $couponCode ? Coupon::where('code', $couponCode)->first() : null;
        $discountAmount = $coupon ? $coupon->calculateDiscount($subtotal) : 0;
        $shippingFee = $this->cart->getShippingFee($subtotal, $couponCode);
        $total = $subtotal - $discountAmount + $shippingFee;
        $freeShippingThreshold = (float) env('FREE_SHIPPING_AMOUNT', 1000);
        $amountForFreeShipping = max(0, $freeShippingThreshold - $subtotal);

        return view('frontend.cart', compact(
            'cartItems', 'subtotal', 'coupon', 'discountAmount',
            'shippingFee', 'total', 'freeShippingThreshold', 'amountForFreeShipping'
        ));
    }

    public function add(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id', 'quantity' => 'integer|min:1']);
        $this->cart->addItem($request->product_id, $request->quantity ?? 1, $request->variant_id);

        if ($request->expectsJson()) {
            return response()->json(['count' => $this->cart->getCount(), 'message' => 'เพิ่มลงตะกร้าแล้ว']);
        }
        return back()->with('success', 'เพิ่มลงตะกร้าแล้ว');
    }

    public function update(Request $request, int $id)
    {
        $request->validate(['quantity' => 'required|integer|min:0']);
        $this->cart->updateQuantity($id, $request->quantity);

        if ($request->expectsJson()) {
            $subtotal = $this->cart->getSubtotal();
            return response()->json(['subtotal' => $subtotal, 'count' => $this->cart->getCount()]);
        }
        return back();
    }

    public function remove(int $id)
    {
        $this->cart->removeItem($id);
        if (request()->expectsJson()) {
            return response()->json(['count' => $this->cart->getCount()]);
        }
        return back()->with('success', 'ลบสินค้าออกจากตะกร้าแล้ว');
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);
        $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('error', 'คูปองไม่ถูกต้องหรือหมดอายุแล้ว');
        }

        $subtotal = $this->cart->getSubtotal();
        if ($subtotal < $coupon->minimum_order) {
            return back()->with('error', 'ยอดสั่งซื้อไม่ถึงขั้นต่ำ ฿' . number_format($coupon->minimum_order, 2));
        }

        session(['coupon_code' => $coupon->code]);
        return back()->with('success', 'ใช้คูปอง "' . $coupon->code . '" สำเร็จ');
    }

    public function removeCoupon()
    {
        session()->forget('coupon_code');
        return back()->with('success', 'ลบคูปองออกแล้ว');
    }

    public function count()
    {
        return response()->json(['count' => $this->cart->getCount()]);
    }
}
