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
        $cartItems         = $this->cart->getCartItems();
        $subtotal          = $this->cart->getSubtotal();
        $couponCode        = session('coupon_code');
        $stackedCouponCode = session('stacked_coupon_code');
        $coupon            = $couponCode
            ? Coupon::where('code', $couponCode)->with(['products', 'categories'])->first()
            : null;
        $stackedCoupon     = $stackedCouponCode
            ? Coupon::where('code', $stackedCouponCode)->first()
            : null;
        $discountAmount    = $coupon ? $coupon->calculateDiscount($subtotal, $cartItems) : 0;
        $shippingFee       = $this->cart->getShippingFee($subtotal, $couponCode, $stackedCouponCode);
        $total             = $subtotal - $discountAmount + $shippingFee;
        $collectedCoupons  = $this->getCollectedCoupons($couponCode, $stackedCouponCode);

        return view('frontend.cart', compact(
            'cartItems', 'subtotal', 'coupon', 'stackedCoupon', 'discountAmount',
            'shippingFee', 'total', 'collectedCoupons'
        ));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id'      => 'required|exists:products,id',
            'quantity'        => 'integer|min:1',
            'embroidery'      => 'boolean',
            'embroidery_text' => 'nullable|string|max:1000',
        ]);
        $flashPrice = $request->filled('flash_sale_price') ? (float) $request->flash_sale_price : null;

        // งานปัก: อนุญาตเฉพาะสินค้าที่ประเภทรองรับการปัก และคิดราคาฝั่งเซิร์ฟเวอร์เสมอ
        $embroidery      = false;
        $embroideryText  = null;
        $embroideryPrice = 0.0;
        if ($request->boolean('embroidery')) {
            $product = \App\Models\Product::with('productType')->find($request->product_id);
            if ($product && $product->productType?->has_embroidery) {
                $embroidery      = true;
                $embroideryText  = $request->embroidery_text;
                $embroideryPrice = $product->free_embroidery
                    ? 0.0
                    : (float) \App\Models\SiteSetting::get('embroidery_name_price', 45);
            }
        }

        $this->cart->addItem(
            $request->product_id,
            $request->quantity ?? 1,
            $request->variant_id,
            $flashPrice,
            $embroidery,
            $embroideryText,
            $embroideryPrice,
        );

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
        $cartItems         = $this->cart->getCartItems();
        $subtotal          = $this->cart->getSubtotal();
        $couponCode        = session('coupon_code');
        $stackedCouponCode = session('stacked_coupon_code');
        $coupon            = $couponCode
            ? Coupon::where('code', $couponCode)->with(['products', 'categories'])->first()
            : null;
        $stackedCoupon     = $stackedCouponCode
            ? Coupon::where('code', $stackedCouponCode)->with(['products', 'categories'])->first()
            : null;
        $discountAmount    = ($coupon ? $coupon->calculateDiscount($subtotal, $cartItems) : 0)
                           + ($stackedCoupon ? $stackedCoupon->calculateDiscount($subtotal, $cartItems) : 0);
        $shippingFee       = $this->cart->getShippingFee($subtotal, $couponCode, $stackedCouponCode);

        return [
            'count'           => $this->cart->getCount(),
            'subtotal'        => $subtotal,
            'discount_amount' => $discountAmount,
            'shipping_fee'    => $shippingFee,
            'total'           => $subtotal - $discountAmount + $shippingFee,
            'is_empty'        => $cartItems->isEmpty(),
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

        $sessionKey = $coupon->is_stackable ? 'stacked_coupon_code' : 'coupon_code';
        session([$sessionKey => $coupon->code]);
        return back()->with('success', 'ใช้คูปอง "' . $coupon->code . '" สำเร็จ');
    }

    public function removeCoupon(Request $request)
    {
        if ($request->boolean('stacked')) {
            session()->forget('stacked_coupon_code');
        } else {
            session()->forget('coupon_code');
        }
        return back()->with('success', 'ลบคูปองออกแล้ว');
    }

    public function count()
    {
        return response()->json(['count' => $this->cart->getCount()]);
    }

    private function getCollectedCoupons(?string $activeCouponCode, ?string $activeStackedCode = null): \Illuminate\Support\Collection
    {
        if (!auth('customer')->check()) return collect();

        $activeCodes = array_filter([$activeCouponCode, $activeStackedCode]);

        return CustomerCoupon::where('customer_id', auth('customer')->id())
            ->with(['coupon.products', 'coupon.categories'])
            ->get()
            ->filter(fn($cc) =>
                $cc->coupon &&
                $cc->coupon->isValid() &&
                $cc->coupon->isValidForCustomer(auth('customer')->id()) &&
                !in_array($cc->coupon->code, $activeCodes)
            )
            ->values();
    }
}
