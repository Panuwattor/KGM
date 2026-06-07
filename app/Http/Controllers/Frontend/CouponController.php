<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\CustomerCoupon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::publiclyVisible()
            ->with(['products', 'categories'])
            ->latest()
            ->get();

        $collectedIds = [];
        if (auth('customer')->check()) {
            $collectedIds = CustomerCoupon::where('customer_id', auth('customer')->id())
                ->pluck('coupon_id')
                ->toArray();
        }

        return view('frontend.coupons', compact('coupons', 'collectedIds'));
    }

    public function collect(Coupon $coupon)
    {
        if (!auth('customer')->check()) {
            return redirect()->route('login')->with('error', 'กรุณาเข้าสู่ระบบก่อนเก็บคูปอง');
        }

        if (!$coupon->is_public || !$coupon->isValid()) {
            return back()->with('error', 'คูปองนี้ไม่สามารถเก็บได้');
        }

        $customerId = auth('customer')->id();

        $existing = CustomerCoupon::where('customer_id', $customerId)
            ->where('coupon_id', $coupon->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'คุณเก็บคูปองนี้ไว้แล้ว');
        }

        CustomerCoupon::create([
            'customer_id'  => $customerId,
            'coupon_id'    => $coupon->id,
            'collected_at' => now(),
        ]);

        return back()->with('success', 'เก็บคูปอง "' . $coupon->code . '" สำเร็จ');
    }
}
