<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CustomerCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use App\Services\SmsService;
use App\Services\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private TelegramService $telegram,
        private SmsService $sms,
    ) {}

    public function index()
    {
        $cartItems = $this->cart->getCartItems();
        if ($cartItems->isEmpty()) return redirect()->route('cart')->with('error', 'ตะกร้าของคุณว่างเปล่า');

        $subtotal          = $this->cart->getSubtotal();
        $couponCode        = session('coupon_code');
        $stackedCouponCode = session('stacked_coupon_code');
        $coupon            = $couponCode
            ? Coupon::where('code', $couponCode)->with(['products', 'categories'])->first()
            : null;
        $stackedCoupon         = $stackedCouponCode
            ? Coupon::where('code', $stackedCouponCode)->first()
            : null;
        $discountAmount        = $coupon ? $coupon->calculateDiscount($subtotal, $cartItems) : 0;
        $shippingFee           = $this->cart->getShippingFee($subtotal, $couponCode, $stackedCouponCode);
        $subtotalAfterDiscount = $subtotal - $discountAmount;
        $vatAmount             = round($subtotalAfterDiscount * 0.07, 2);
        $total                 = $subtotalAfterDiscount + $shippingFee;

        // ลูกค้าทั่วไป (ไม่ได้ล็อกอิน) ไม่มีสมุดที่อยู่ → กรอกใหม่ทุกครั้ง
        /** @var \App\Models\Customer|null $customer */
        $customer       = auth('customer')->user();
        $addresses      = $customer ? $customer->addresses()->get() : collect();
        $defaultAddress = $addresses->where('is_default', true)->first();

        $pickupShowrooms = \App\Models\Showroom::active()
            ->orderByDesc('is_main')
            ->get();

        return view('frontend.checkout', compact(
            'cartItems', 'subtotal', 'coupon', 'stackedCoupon', 'discountAmount',
            'shippingFee', 'vatAmount', 'total', 'addresses', 'defaultAddress', 'pickupShowrooms'
        ));
    }

    public function store(Request $request)
    {
        $isPickup = $request->delivery_method === 'pickup';

        $request->validate([
            'delivery_method' => 'required|in:ship,pickup',
            // เลือกสาขา จำเป็นเฉพาะกรณีรับเองที่ร้าน
            'pickup_showroom_id' => [Rule::requiredIf($isPickup), 'nullable', 'exists:showrooms,id'],
            // ติดต่อผู้รับ จำเป็นทั้งสองแบบ
            'ship_name'       => 'required|string|max:255',
            'ship_phone'      => 'required|string|max:20',
            // ที่อยู่จัดส่ง จำเป็นเฉพาะกรณีจัดส่งถึงบ้าน
            'ship_address'    => [Rule::requiredIf(! $isPickup), 'nullable', 'string'],
            'ship_district'   => [Rule::requiredIf(! $isPickup), 'nullable', 'string'],
            'ship_amphoe'     => [Rule::requiredIf(! $isPickup), 'nullable', 'string'],
            'ship_province'   => [Rule::requiredIf(! $isPickup), 'nullable', 'string'],
            'ship_postcode'   => [Rule::requiredIf(! $isPickup), 'nullable', 'string', 'max:10'],
        ]);

        $cartItems = $this->cart->getCartItems();
        if ($cartItems->isEmpty()) return redirect()->route('cart');

        // สาขาที่ลูกค้าเลือกไว้ (กรณีรับเองที่ร้าน) — ต้องเป็นสาขาที่เปิดใช้งาน
        $pickupShowroom = $isPickup
            ? \App\Models\Showroom::active()->find($request->pickup_showroom_id)
            : null;

        $subtotal          = $this->cart->getSubtotal();
        $couponCode        = session('coupon_code');
        $stackedCouponCode = session('stacked_coupon_code');
        $coupon            = $couponCode
            ? Coupon::where('code', $couponCode)->with(['products', 'categories'])->first()
            : null;
        $stackedCoupon         = $stackedCouponCode
            ? Coupon::where('code', $stackedCouponCode)->first()
            : null;
        $discountAmount        = $coupon ? $coupon->calculateDiscount($subtotal, $cartItems) : 0;
        // รับเองที่ร้าน = ไม่มีค่าจัดส่ง
        $shippingFee           = $isPickup ? 0 : $this->cart->getShippingFee($subtotal, $couponCode, $stackedCouponCode);
        $subtotalAfterDiscount = $subtotal - $discountAmount;
        $vatAmount             = $request->boolean('needs_tax_invoice') ? round($subtotalAfterDiscount * 0.07, 2) : 0;
        $total                 = $subtotalAfterDiscount + $shippingFee;

        /** @var \App\Models\Customer|null $customer */
        $customer  = auth('customer')->user();
        $isGuest   = $customer === null;
        $order     = null;

        DB::transaction(function () use ($request, $cartItems, $subtotal, $coupon, $stackedCoupon, $discountAmount, $shippingFee, $vatAmount, $total, $customer, $isGuest, $isPickup, $pickupShowroom, &$order) {
            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'customer_id'      => $customer?->id,
                'is_guest'         => $isGuest,
                'guest_token'      => $isGuest ? Order::generateGuestToken() : null,
                'status'           => 'pending_payment',
                'delivery_method'  => $isPickup ? 'pickup' : 'ship',
                'pickup_showroom_id' => $pickupShowroom?->id,
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
                    'order_id'        => $order->id,
                    'product_id'      => $item->product_id,
                    'variant_id'      => $item->variant_id,
                    'product_name'    => $item->product->name,
                    'variant_label'   => $item->variant?->label,
                    'embroidery'      => $item->embroidery,
                    'embroidery_text' => $item->embroidery_text,
                    'embroidery_price'=> $item->embroidery ? $item->embroidery_price : 0,
                    'product_image'   => $item->product->main_image,
                    'quantity'        => $item->quantity,
                    'unit_price'      => $item->product->current_price + ($item->variant?->price_adjustment ?? 0),
                    'subtotal'        => $item->subtotal,
                ]);
                if ($item->product->manage_stock) {
                    // มีไซส์ (variant) → ตัดสต๊อกที่ไซส์, ไม่มี → ตัดสต๊อกสินค้ารวม
                    if ($item->variant) {
                        $item->variant->decrement('stock_quantity', $item->quantity);
                    } else {
                        $item->product->decrement('stock_quantity', $item->quantity);
                    }
                    $item->product->increment('sale_count', $item->quantity);
                }
            }

            // ใช้คูปองแล้ว: เพิ่มยอดใช้ และลบออกจากกระเป๋าลูกค้า (ต้องเก็บใหม่ถึงจะใช้ได้อีก)
            // ลูกค้าทั่วไปไม่มีกระเป๋าคูปอง — นับยอดใช้อย่างเดียว
            if ($coupon) {
                $coupon->increment('used_count');
                if ($customer) {
                    CustomerCoupon::where('customer_id', $customer->id)
                        ->where('coupon_id', $coupon->id)
                        ->delete();
                }
            }
            if ($stackedCoupon) {
                $stackedCoupon->increment('used_count');
                if ($customer) {
                    CustomerCoupon::where('customer_id', $customer->id)
                        ->where('coupon_id', $stackedCoupon->id)
                        ->delete();
                }
            }

            // บันทึกที่อยู่จัดส่งเข้าสมุดที่อยู่ ถ้ายังไม่มี (เฉพาะสมาชิกที่เลือกแบบจัดส่ง)
            if ($customer && ! $isPickup) {
                $exists = $customer->addresses()
                    ->where('address_line1', $request->ship_address)
                    ->where('district', $request->ship_district)
                    ->where('amphoe', $request->ship_amphoe)
                    ->where('province', $request->ship_province)
                    ->where('postcode', $request->ship_postcode)
                    ->exists();

                if (! $exists) {
                    $customer->addresses()->create([
                        'recipient_name' => $request->ship_name,
                        'phone'          => $request->ship_phone,
                        'address_line1'  => $request->ship_address,
                        'district'       => $request->ship_district,
                        'amphoe'         => $request->ship_amphoe,
                        'province'       => $request->ship_province,
                        'postcode'       => $request->ship_postcode,
                        'is_default'     => $customer->addresses()->count() === 0,
                    ]);
                }
            }

            $this->cart->clearCart();
            session()->forget(['coupon_code', 'stacked_coupon_code']);
        });

        assert($order instanceof Order);
        $this->telegram->notifyNewOrder($order);

        // จำ token ไว้ใน session ลูกค้าทั่วไปจึงเข้าหน้าออเดอร์ต่อได้ทันทีโดยไม่ต้องใส่ ?token=
        // และส่งลิงก์ทาง SMS ไว้เป็นทางกลับมาอีกทาง (SMS ล้มเหลวต้องไม่ทำให้ออเดอร์พัง)
        if ($order->is_guest) {
            session([$order->guestSessionKey() => $order->guest_token]);
            session()->flash('order_sms_sent', $this->sms->sendOrderLink($order));
        }

        return redirect()->route('checkout.success', $order);
    }

    public function success(Order $order)
    {
        $this->authorizeOrder($order);
        return view('frontend.checkout-success', compact('order'));
    }

    /**
     * ติดตามออเดอร์ด้วยลิงก์ลับ — ลูกค้าทั่วไปดูสถานะ/รายการสินค้า/เลขพัสดุ และอัปโหลดสลิปได้
     * ใช้หน้าเดียวกับออเดอร์ของสมาชิก เพื่อให้เห็นข้อมูลครบเท่ากัน
     */
    public function track(Request $request, Order $order)
    {
        $this->authorizeOrder($order, $request->query('token'));

        // สมาชิกที่เปิดลิงก์นี้ ส่งไปหน้าออเดอร์ในบัญชีตัวเองแทน
        if (! $order->is_guest && auth('customer')->check()) {
            return redirect()->route('account.orders.show', $order);
        }

        $order->load('items', 'pickupShowroom');

        return view('frontend.account.order-show', [
            'order'       => $order,
            'isGuestView' => true,
        ]);
    }

    /**
     * อนุญาตเฉพาะเจ้าของออเดอร์ หรือผู้ถือลิงก์ลับ (token จาก query หรือที่จำไว้ใน session)
     * เมื่อ token ถูกต้อง จะจำไว้ใน session ให้ใช้ต่อได้ทั้ง session
     */
    private function authorizeOrder(Order $order, ?string $queryToken = null): void
    {
        $customer = auth('customer')->user();
        $token    = $queryToken ?: session($order->guestSessionKey());

        if (! $order->isAccessibleBy($customer, $token)) abort(403);

        if ($order->is_guest && $token) {
            session([$order->guestSessionKey() => $token]);
        }
    }

    public function uploadSlip(Request $request, Order $order)
    {
        $this->authorizeOrder($order, $request->input('token'));

        $request->validate(['slip' => 'required|image|max:5120']);

        $path = $request->file('slip')->store('slips', config('filesystems.media'));
        $order->update([
            'payment_slip'        => $path,
            'status'              => 'payment_uploaded',
            'payment_uploaded_at' => now(),
            'rejection_reason'    => null,
        ]);

        $this->telegram->notifyPaymentSlip($order);

        return back()->with('success', 'อัปโหลดสลิปเรียบร้อยแล้ว รอการตรวจสอบจากทีมงาน');
    }
}
