@php
    // หน้าเดียวกันนี้ใช้ทั้งตอนสั่งซื้อเสร็จ และตอนลูกค้าเปิดลิงก์ลับกลับมาดูภายหลัง
    $isTracking = request()->routeIs('order.track');
@endphp
@extends('layouts.app')
@section('title', $isTracking ? 'ติดตามออเดอร์ ' . $order->order_number : 'สั่งซื้อสำเร็จ')
@section('content')
<div class="container" style="padding:clamp(24px,6vw,64px) 0;max-width:700px;">
    <div style="background:white;border-radius:20px;padding:clamp(20px,5vw,44px);text-align:center;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div style="width:72px;height:72px;background:var(--kgm-green-100);border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:32px;color:var(--kgm-green-600);margin:0 auto 18px;">
            <i class="bi {{ $isTracking ? 'bi-receipt' : 'bi-bag-check-fill' }}"></i>
        </div>
        <h1 style="font-size:clamp(22px,5vw,28px);font-weight:800;color:var(--kgm-green-800);margin-bottom:6px;">{{ $isTracking ? 'ออเดอร์ของคุณ' : 'สั่งซื้อสำเร็จ!' }}</h1>
        <p style="color:#888;margin-bottom:18px;font-size:14px;">{{ $isTracking ? 'เลขที่ออเดอร์' : 'ขอบคุณสำหรับการสั่งซื้อ เลขที่ออเดอร์ของคุณคือ' }}</p>
        <div style="background:var(--kgm-green-100);border-radius:14px;padding:14px 20px;display:inline-block;margin-bottom:16px;">
            <span style="font-size:clamp(18px,5vw,22px);font-weight:900;color:var(--kgm-green-700);">{{ $order->order_number }}</span>
        </div>
        <div style="margin-bottom:24px;">
            <span class="status-badge status-{{ $order->status_color }}" style="font-size:14px;padding:6px 16px;">{{ $order->status_label }}</span>
        </div>

        @if(session('success'))
        <div style="background:var(--kgm-green-100);color:var(--kgm-green-800);border-radius:12px;padding:12px 16px;margin-bottom:20px;font-weight:600;font-size:14px;">
            <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
        </div>
        @endif

        {{-- ลิงก์ลับสำหรับลูกค้าที่ไม่ได้สมัครสมาชิก — ใช้กลับมาดูสถานะ/อัปโหลดสลิปภายหลัง --}}
        @if($order->is_guest && $order->guest_url)
        <div style="background:#fffdf5;border:2px solid var(--kgm-gold-300);border-radius:16px;padding:clamp(16px,4vw,20px);margin-bottom:20px;text-align:left;">
            <h3 style="font-weight:800;color:var(--kgm-gold-700);margin-bottom:8px;font-size:16px;">
                <i class="bi bi-bookmark-star"></i> บันทึกลิงก์นี้ไว้!
            </h3>
            @if(session('order_sms_sent'))
            <div style="background:var(--kgm-green-100);border-radius:10px;padding:10px 12px;margin-bottom:12px;font-size:13px;color:var(--kgm-green-800);font-weight:600;">
                <i class="bi bi-chat-dots-fill"></i> ส่งลิงก์นี้ทาง SMS ไปที่เบอร์ {{ $order->ship_phone }} แล้ว
            </div>
            @endif
            <p style="font-size:13px;color:#7a6f55;line-height:1.65;margin-bottom:12px;">
                คุณสั่งซื้อแบบไม่ได้สมัครสมาชิก <b>ลิงก์นี้คือทางเดียว</b>ที่จะกลับมาดูสถานะออเดอร์และอัปโหลดสลิปได้
                กรุณากด Copy แล้วบันทึกไว้ หรือบันทึกหน้านี้เป็นบุ๊กมาร์ก
            </p>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                <input type="text" id="guest-link" value="{{ $order->guest_url }}" readonly
                    onclick="this.select()"
                    style="flex:1;min-width:200px;font-size:12.5px;padding:10px 12px;border:1.5px solid #e6dfc8;border-radius:12px;background:#fff;color:#5a5344;font-family:monospace;">
                <button type="button" onclick="copyGuestLink(this)" class="btn btn-gold" style="flex-shrink:0;">
                    <i class="bi bi-clipboard"></i> <span>Copy</span>
                </button>
            </div>
        </div>
        @endif

        {{-- ช่องทางชำระเงิน --}}
        <div style="background:white;border:2px solid var(--kgm-gold-300);border-radius:16px;padding:clamp(16px,4vw,20px);margin-bottom:20px;text-align:left;">
            <h3 style="font-weight:800;color:var(--kgm-gold-700);margin-bottom:14px;font-size:16px;"><i class="bi bi-credit-card"></i> ช่องทางการชำระเงิน</h3>
            @include('frontend._payment-info', ['payAmount' => $order->total, 'payRef' => $order->order_number])
        </div>

        {{-- อัปโหลดสลิปได้เลยจากหน้านี้ --}}
        @if(in_array($order->status, ['pending_payment', 'payment_uploaded']))
        <div style="background:#f8faf9;border:1px solid #e3e8e5;border-radius:16px;padding:clamp(16px,4vw,20px);margin-bottom:24px;text-align:left;">
            <h3 style="font-weight:800;color:var(--kgm-green-800);margin-bottom:14px;font-size:16px;"><i class="bi bi-cloud-upload"></i> อัปโหลดสลิปการชำระเงิน</h3>

            @if($order->payment_slip)
            <div style="display:flex;align-items:center;gap:12px;background:var(--kgm-green-100);border-radius:12px;padding:12px 14px;margin-bottom:14px;">
                <img src="{{ media_url($order->payment_slip) }}" style="width:54px;height:54px;object-fit:cover;border-radius:10px;border:1px solid #fff;" alt="สลิป">
                <div style="font-size:13px;color:var(--kgm-green-800);font-weight:600;line-height:1.5;">
                    <i class="bi bi-check-circle-fill"></i> อัปโหลดสลิปแล้ว<br>
                    <span style="font-weight:400;color:#777;">รอทีมงานตรวจสอบการชำระเงิน</span>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('orders.upload-slip', $order) }}" enctype="multipart/form-data">
                @csrf
                @if($order->is_guest)<input type="hidden" name="token" value="{{ $order->guest_token }}">@endif
                <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                    <input type="file" name="slip" accept="image/*" class="form-control" style="flex:1;min-width:180px;border-radius:12px;" required>
                    <button type="submit" class="btn btn-gold" style="flex-shrink:0;">
                        <i class="bi bi-cloud-upload"></i> {{ $order->payment_slip ? 'อัปโหลดใหม่' : 'อัปโหลดสลิป' }}
                    </button>
                </div>
                @error('slip')<div style="color:#e74c3c;font-size:13px;margin-top:8px;">{{ $message }}</div>@enderror
            </form>
        </div>
        @endif

        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            @if(! $order->is_guest)
            <a href="{{ route('account.orders.show', $order) }}" class="btn btn-primary">
                <i class="bi bi-receipt"></i> ดูรายละเอียดออเดอร์
            </a>
            @endif
            <a href="{{ route('shop') }}" class="btn btn-outline">
                <i class="bi bi-shop"></i> ช็อปปิ้งต่อ
            </a>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
function copyGuestLink(btn) {
    const input = document.getElementById('guest-link');
    const label = btn.querySelector('span');
    const done  = () => {
        label.textContent = 'คัดลอกแล้ว!';
        btn.querySelector('i').className = 'bi bi-check-lg';
        setTimeout(() => {
            label.textContent = 'Copy';
            btn.querySelector('i').className = 'bi bi-clipboard';
        }, 2000);
    };
    if (navigator.clipboard) {
        navigator.clipboard.writeText(input.value).then(done).catch(() => { input.select(); document.execCommand('copy'); done(); });
    } else {
        input.select(); document.execCommand('copy'); done();
    }
}
</script>
@endpush
