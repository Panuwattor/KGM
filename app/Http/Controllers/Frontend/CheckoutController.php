<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private TelegramService $telegram,
    ) {}

    public function index()
    {
        $cartItems = $this->cart->getCartItems();
        if ($cartItems->isEmpty()) return redirect()->route('cart')->with('error', 'ตะกร้าของคุณว่างเปล่า');

        $subtotal       = $this->cart->getSubtotal();
        $couponCode     = session('coupon_code');
        $coupon         = $couponCode
            ? Coupon::where('code', $couponCode)->with(['products', 'categories'])->first()
            : null;
        $discountAmount        = $coupon ? $coupon->calculateDiscount($subtotal, $cartItems) : 0;
        $shippingFee           = $this->cart->getShippingFee($subtotal, $couponCode);
        $subtotalAfterDiscount = $subtotal - $discountAmount;
        $vatAmount             = round($subtotalAfterDiscount * 0.07, 2);
        $total                 = $subtotalAfterDiscount + $shippingFee;

        /** @var \App\Models\Customer $customer */
        $customer       = auth('customer')->user();
        $addresses      = $customer->addresses()->get();
        $defaultAddress = $addresses->where('is_default', true)->first();

        return view('frontend.checkout', compact(
            'cartItems', 'subtotal', 'coupon', 'discountAmount',
            'shippingFee', 'vatAmount', 'total', 'addresses', 'defaultAddress'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ship_name'     => 'required|string|max:255',
            'ship_phone'    => 'required|string|max:20',
            'ship_address'  => 'required|string',
            'ship_district' => 'required|string',
            'ship_amphoe'   => 'required|string',
            'ship_province' => 'required|string',
            'ship_postcode' => 'required|string|max:10',
        ]);

        $cartItems = $this->cart->getCartItems();
        if ($cartItems->isEmpty()) return redirect()->route('cart');

        $subtotal       = $this->cart->getSubtotal();
        $couponCode     = session('coupon_code');
        $coupon         = $couponCode
            ? Coupon::where('code', $couponCode)->with(['products', 'categories'])->first()
            : null;
        $discountAmount        = $coupon ? $coupon->calculateDiscount($subtotal, $cartItems) : 0;
        $shippingFee           = $this->cart->getShippingFee($subtotal, $couponCode);
        $subtotalAfterDiscount = $subtotal - $discountAmount;
        $vatAmount             = $request->boolean('needs_tax_invoice') ? round($subtotalAfterDiscount * 0.07, 2) : 0;
        $total                 = $subtotalAfterDiscount + $shippingFee;

        /** @var \App\Models\Customer $customer */
        $customer = auth('customer')->user();
        $order = null;

        DB::transaction(function () use ($request, $cartItems, $subtotal, $coupon, $discountAmount, $shippingFee, $vatAmount, $total, $customer, &$order) {
            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'customer_id'      => $customer->id,
                'status'           => 'pending_payment',
                'subtotal'         => $subtotal,
                'shipping_fee'     => $shippingFee,
                'discount_amount'  => $discountAmount,
                'vat_amount'       => $vatAmount,
                'total'            => $total,
                'needs_tax_invoice'=> $request->boolean('needs_tax_invoice'),
                'tax_id'           => $request->tax_id,
                'tax_branch'       => $request->tax_branch,
                'tax_company_name' => $request->tax_company_name,
                'tax_address'      => $request->tax_address,
                'coupon_code'      => $coupon?->code,
                'coupon_id'        => $coupon?->id,
                'ship_name'        => $request->ship_name,
                'ship_phone'       => $request->ship_phone,
                'ship_address'     => $request->ship_address,
                'ship_district'    => $request->ship_district,
                'ship_amphoe'      => $request->ship_amphoe,
                'ship_province'    => $request->ship_province,
                'ship_postcode'    => $request->ship_postcode,
                'customer_note'    => $request->customer_note,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item->product_id,
                    'variant_id'    => $item->variant_id,
                    'product_name'  => $item->product->name,
                    'variant_label' => $item->variant?->label,
                    'product_image' => $item->product->main_image,
                    'quantity'      => $item->quantity,
                    'unit_price'    => $item->product->current_price + ($item->variant?->price_adjustment ?? 0),
                    'subtotal'      => $item->subtotal,
                ]);
                if ($item->product->manage_stock) {
                    $item->product->decrement('stock_quantity', $item->quantity);
                    $item->product->increment('sale_count', $item->quantity);
                }
            }

            if ($coupon) {
                $coupon->increment('used_count');
                CustomerCoupon::where('customer_id', $customer->id)
                    ->where('coupon_id', $coupon->id)
                    ->first()?->update(['updated_at' => now()]);
            }

            $this->cart->clearCart();
            session()->forget('coupon_code');
        });

        assert($order instanceof Order);
        $this->telegram->notifyNewOrder($order);

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        /** @var \App\Models\Customer $customer */
        $customer = auth('customer')->user();
        if ($order->customer_id !== $customer->id) abort(403);
        return view('frontend.checkout-success', compact('order'));
    }

    public function uploadSlip(Request $request, Order $order)
    {
        /** @var \App\Models\Customer $customer */
        $customer = auth('customer')->user();
        if ($order->customer_id !== $customer->id) abort(403);

        $request->validate(['slip' => 'required|image|max:5120']);

        $path = $request->file('slip')->store('slips', 'public');
        $order->update([
            'payment_slip'        => $path,
            'status'              => 'payment_uploaded',
            'payment_uploaded_at' => now(),
        ]);

        $this->telegram->notifyPaymentSlip($order);

        return back()->with('success', 'อัปโหลดสลิปเรียบร้อยแล้ว รอการตรวจสอบจากทีมงาน');
    }
}
