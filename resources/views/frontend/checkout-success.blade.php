@extends('layouts.app')
@section('title', 'สั่งซื้อสำเร็จ')
@section('content')
<div class="container" style="padding:64px 0;max-width:700px;">
    <div style="background:white;border-radius:24px;padding:48px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div style="width:80px;height:80px;background:var(--kgm-green-100);border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:36px;color:var(--kgm-green-600);margin:0 auto 20px;">
            <i class="bi bi-bag-check-fill"></i>
        </div>
        <h1 style="font-size:28px;font-weight:800;color:var(--kgm-green-800);margin-bottom:8px;">สั่งซื้อสำเร็จ!</h1>
        <p style="color:#888;margin-bottom:24px;">ขอบคุณสำหรับการสั่งซื้อ เลขที่ออเดอร์ของคุณคือ</p>
        <div style="background:var(--kgm-green-100);border-radius:14px;padding:16px 24px;display:inline-block;margin-bottom:24px;">
            <span style="font-size:22px;font-weight:900;color:var(--kgm-green-700);">{{ $order->order_number }}</span>
        </div>
        <div style="background:#fef9ec;border:2px solid var(--kgm-gold-300);border-radius:16px;padding:20px;margin-bottom:28px;text-align:left;">
            <h3 style="font-weight:800;color:var(--kgm-gold-700);margin-bottom:12px;"><i class="bi bi-credit-card"></i> วิธีชำระเงิน</h3>
            <p style="font-size:14px;color:#555;line-height:1.8;">
                1. โอนเงินจำนวน <strong style="color:var(--kgm-green-700);font-size:18px;">฿{{ number_format($order->total, 2) }}</strong> ไปยังบัญชี PromptPay / ธนาคาร<br>
                2. ถ่ายสลิปการโอนเงิน<br>
                3. อัปโหลดสลิปในหน้าออเดอร์ของคุณ
            </p>
        </div>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('account.orders.show', $order) }}" class="btn btn-primary btn-lg">
                <i class="bi bi-cloud-upload"></i> อัปโหลดสลิปชำระเงิน
            </a>
            <a href="{{ route('shop') }}" class="btn btn-outline btn-lg">
                <i class="bi bi-shop"></i> ช็อปปิ้งต่อ
            </a>
        </div>
    </div>
</div>
@endsection
