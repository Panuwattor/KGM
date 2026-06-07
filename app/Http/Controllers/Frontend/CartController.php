<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private CartService $cart) {}

    public function index()
    {
        $cartItems   = $this->cart->getCartItems();
        $subtotal    = $this->cart->getSubtotal();
        $couponCode  = session('coupon_code');
        $coupon      = $couponCode
            ? Coupon::where('code', $couponCode)->with(['products', 'categories'])->first()
            : null;
        $discountAmount          = $coupon ? $coupon->calculateDiscount($subtotal, $cartItems) : 0;
        $shippingFee             = $this->cart->getShippingFee($subtotal, $couponCode);
        $total                   = $subtotal - $discountAmount + $shippingFee;
        $freeShippingThreshold   = (float) env('FREE_SHIPPING_AMOUNT', 1000);
        $amountForFreeShipping   = ($shippingFee == 0) ? 0 : max(0, $freeShippingThreshold - $subtotal);

        $collectedCoupons = $this->getCollectedCoupons($couponCode);

        return view('frontend.cart', compact(
            'cartItems', 'subtotal', 'coupon', 'discountAmount',
            'shippingFee', 'total', 'freeShippingThreshold', 'amountForFreeShipping',
            'collectedCoupons'
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
            $data          = $this->cartSummaryJson();
            $item          = $this->cart->getCartItems()->firstWhere('id', $id);
            $data['item_quantity'] = $item?->quantity ?? 0;
            $data['item_subtotal'] = $item?->subtotal ?? 0;
            $data['item_removed']  = !$item;
            return response()->json($data);
        }
        return back();
    }

    public function remove(int $id)
    {
        $this->cart->removeItem($id);
        if (request()->expectsJson()) {
            return response()->json($this->cartSummaryJson());
        }
        return back()->with('success', 'ลบสินค้าออกจากตะกร้าแล้ว');
    }

    private function cartSummaryJson(): array
    {
        $cartItems             = $this->cart->getCartItems();
        $subtotal              = $this->cart->getSubtotal();
        $couponCode            = session('coupon_code');
        $coupon                = $couponCode
            ? Coupon::where('code', $couponCode)->with(['products', 'categories'])->first()
            : null;
        $discountAmount        = $coupon ? $coupon->calculateDiscount($subtotal, $cartItems) : 0;
        $shippingFee           = $this->cart->getShippingFee($subtotal, $couponCode);
        $freeShippingThreshold = (float) env('FREE_SHIPPING_AMOUNT', 1000);

        return [
            'count'                    => $this->cart->getCount(),
            'subtotal'                 => $subtotal,
            'discount_amount'          => $discountAmount,
            'shipping_fee'             => $shippingFee,
            'total'                    => $subtotal - $discountAmount + $shippingFee,
            'free_shipping_threshold'  => $freeShippingThreshold,
            'amount_for_free_shipping' => ($shippingFee == 0) ? 0 : max(0, $freeShippingThreshold - $subtotal),
            'is_empty'                 => $cartItems->isEmpty(),
        ];
    }

    public function applyCoupon(Request $request)
    {
        $request->validate(['coupon_code' => 'required|string']);
        $coupon = Coupon::where('code', strtoupper($request->coupon_code))
            ->with(['products', 'categories'])
            ->first();

        if (!$coupon || !$coupon->isValid()) {
            return back()->with('error', 'คูปองไม่ถูกต้องหรือหมดอายุแล้ว');
        }

        $customerId = auth('customer')->id();
        if ($customerId && !$coupon->isValidForCustomer($customerId)) {
            return back()->with('error', 'คุณใช้คูปองนี้ครบตามจำนวนที่กำหนดแล้ว');
        }

        $subtotal  = $this->cart->getSubtotal();
        $cartItems = $this->cart->getCartItems();

        if ($subtotal < $coupon->minimum_order) {
            return back()->with('error', 'ยอดสั่งซื้อไม่ถึงขั้นต่ำ ฿' . number_format($coupon->minimum_order, 0));
        }

        if ($coupon->type !== 'free_shipping') {
            $discount = $coupon->calculateDiscount($subtotal, $cartItems);
            if ($discount <= 0) {
                return back()->with('error', $coupon->hasProductScope()
                    ? 'คูปองนี้ใช้ได้เฉพาะสินค้าที่กำหนด ซึ่งไม่มีในตะกร้าของคุณ'
                    : 'ไม่สามารถใช้คูปองนี้ได้ในขณะนี้');
            }
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

    private function getCollectedCoupons(?string $activeCouponCode): \Illuminate\Support\Collection
    {
        if (!auth('customer')->check()) return collect();

        return CustomerCoupon::where('customer_id', auth('customer')->id())
            ->with(['coupon.products', 'coupon.categories'])
            ->get()
            ->filter(fn($cc) =>
                $cc->coupon &&
                $cc->coupon->isValid() &&
                $cc->coupon->isValidForCustomer(auth('customer')->id()) &&
                $cc->coupon->code !== $activeCouponCode
            )
            ->values();
    }
}
