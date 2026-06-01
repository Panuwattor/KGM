@extends('layouts.app')
@section('title', 'ออเดอร์ '.$order->order_number)
@section('content')
<div class="container" style="padding:32px 0 64px;max-width:900px;">
    <a href="{{ route('account.orders') }}" style="display:inline-flex;align-items:center;gap:6px;color:var(--kgm-green-600);font-weight:600;margin-bottom:20px;"><i class="bi bi-arrow-left"></i> ย้อนกลับ</a>
    <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
            <div>
                <h1 style="font-size:22px;font-weight:800;color:var(--kgm-green-800);">{{ $order->order_number }}</h1>
                <div style="font-size:13px;color:#888;">{{ $order->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <span class="status-badge status-{{ $order->status_color }}" style="font-size:14px;padding:6px 16px;">{{ $order->status_label }}</span>
        </div>
        @foreach($order->items as $item)
        <div style="display:flex;gap:14px;padding:14px 0;border-bottom:1px solid #f5f7f5;">
            <img src="{{ $item->product_image ? asset('storage/'.$item->product_image) : '' }}" style="width:70px;height:70px;object-fit:cover;border-radius:12px;flex-shrink:0;" alt="">
            <div style="flex:1;">
                <div style="font-weight:700;">{{ $item->product_name }}</div>
                @if($item->variant_label)<div style="font-size:12px;color:#888;">{{ $item->variant_label }}</div>@endif
                <div style="font-size:13px;color:#888;">฿{{ number_format($item->unit_price,0) }} × {{ $item->quantity }}</div>
            </div>
            <div style="font-weight:800;color:var(--kgm-green-700);">฿{{ number_format($item->subtotal,0) }}</div>
        </div>
        @endforeach
        <div style="margin-top:16px;padding-top:12px;border-top:2px solid #f0f2f0;text-align:right;">
            <div style="font-size:14px;color:#888;margin-bottom:4px;">ค่าจัดส่ง: {{ $order->shipping_fee > 0 ? '฿'.number_format($order->shipping_fee,2) : 'ฟรี' }}</div>
            @if($order->discount_amount > 0)<div style="font-size:14px;color:#e74c3c;margin-bottom:4px;">ส่วนลด: -฿{{ number_format($order->discount_amount,2) }}</div>@endif
            <div style="font-size:20px;font-weight:800;color:var(--kgm-green-700);">ยอดรวม: ฿{{ number_format($order->total,2) }}</div>
        </div>
    </div>

    @if($order->status === 'pending_payment' || $order->status === 'payment_uploaded')
    <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;">
        <h3 style="font-weight:800;margin-bottom:16px;"><i class="bi bi-credit-card"></i> อัปโหลดสลิปการชำระเงิน</h3>
        @if($order->payment_slip)
        <div style="margin-bottom:16px;"><img src="{{ asset('storage/'.$order->payment_slip) }}" style="max-width:200px;border-radius:12px;border:2px solid #eee;" alt="สลิป">
        <div style="font-size:12px;color:#888;margin-top:6px;">อัปโหลดแล้ว รอการตรวจสอบ</div></div>
        @endif
        <form method="POST" action="{{ route('orders.upload-slip', $order) }}" enctype="multipart/form-data">
            @csrf
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <input type="file" name="slip" accept="image/*" class="form-control" style="flex:1;border-radius:12px;" required>
                <button type="submit" class="btn btn-gold"><i class="bi bi-cloud-upload"></i> อัปโหลดสลิป</button>
            </div>
        </form>
    </div>
    @endif

    @if($order->tracking_number)
    <div style="background:var(--kgm-green-100);border-radius:20px;padding:20px 24px;">
        <h3 style="font-weight:800;margin-bottom:8px;color:var(--kgm-green-800);"><i class="bi bi-truck"></i> ติดตามพัสดุ</h3>
        <div>บริษัทขนส่ง: <strong>{{ $order->shipping_provider }}</strong></div>
        <div style="font-size:18px;font-weight:800;color:var(--kgm-green-700);margin-top:4px;">{{ $order->tracking_number }}</div>
    </div>
    @endif
</div>
@endsection
