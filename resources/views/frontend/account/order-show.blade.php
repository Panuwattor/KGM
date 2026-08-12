@php
    // หน้านี้ใช้ร่วมกันระหว่างสมาชิก (/account/orders/{id}) และลูกค้าทั่วไปที่เปิดลิงก์ลับ (/order/{เลขออเดอร์})
    // ลูกค้าทั่วไปไม่มีบัญชี จึงไม่มีปุ่มยกเลิก/ยืนยันรับสินค้า (ต้องติดต่อทางร้านแทน)
    $isGuestView = $isGuestView ?? false;
@endphp
@extends('layouts.app')
@section('title', 'ออเดอร์ '.$order->order_number)
@section('content')
<div class="container" style="padding-top:clamp(16px,4vw,32px);padding-bottom:clamp(32px,8vw,64px);max-width:900px;">
    @if($isGuestView && $order->guest_url)
    {{-- ลิงก์ลับ: ทางเดียวที่ลูกค้าทั่วไปจะกลับมาดูออเดอร์นี้ได้ --}}
    <div style="background:#fffdf5;border:2px solid var(--kgm-gold-300);border-radius:16px;padding:clamp(14px,3vw,18px);margin-bottom:20px;">
        <div style="font-weight:800;color:var(--kgm-gold-700);font-size:15px;margin-bottom:6px;">
            <i class="bi bi-bookmark-star"></i> บันทึกลิงก์นี้ไว้ดูออเดอร์ภายหลัง
        </div>
        @if(session('order_sms_sent'))
        <div style="background:var(--kgm-green-100);border-radius:10px;padding:8px 12px;margin-bottom:10px;font-size:13px;color:var(--kgm-green-800);font-weight:600;">
            <i class="bi bi-chat-dots-fill"></i> ส่งลิงก์ทาง SMS ไปที่ {{ $order->ship_phone }} แล้ว
        </div>
        @endif
        <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
            <input type="text" id="guest-link" value="{{ $order->guest_url }}" readonly onclick="this.select()"
                style="flex:1;min-width:200px;font-size:12.5px;padding:9px 12px;border:1.5px solid #e6dfc8;border-radius:12px;background:#fff;color:#5a5344;font-family:monospace;">
            <button type="button" onclick="copyGuestLink(this)" class="btn btn-gold" style="flex-shrink:0;">
                <i class="bi bi-clipboard"></i> <span>Copy</span>
            </button>
        </div>
    </div>
    @endif

    <div style="background:white;border-radius:20px;padding:clamp(16px,4vw,28px);box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
            <div>
                <h1 style="font-size:clamp(18px,5vw,22px);font-weight:800;color:var(--kgm-green-800);">{{ $order->order_number }}</h1>
                <div style="font-size:13px;color:#888;">{{ $order->created_at->format('d/m/Y H:i') }}</div>
            </div>
            <span class="status-badge status-{{ $order->status_color }}" style="font-size:14px;padding:6px 16px;">{{ $order->status_label }}</span>
        </div>
        @foreach($order->items as $item)
        <div style="display:flex;gap:14px;padding:14px 0;border-bottom:1px solid #f5f7f5;">
            <img src="{{ $item->product_image ? media_url($item->product_image) : '' }}" style="width:70px;height:70px;object-fit:cover;border-radius:12px;flex-shrink:0;" alt="">
            <div style="flex:1;">
                <div style="font-weight:700;">{{ $item->product_name }}</div>
                @if($item->variant_label)<div style="font-size:12px;color:#888;">{{ $item->variant_label }}</div>@endif
                <div style="font-size:13px;color:#888;">฿{{ number_format($item->unit_price,0) }} × {{ $item->quantity }}</div>
                @if($item->embroidery)
                <div style="font-size:12px;color:var(--kgm-green-700);font-weight:600;margin-top:2px;">
                    <i class="bi bi-pen"></i> ปักชื่อ
                    @if($item->embroidery_price > 0)+฿{{ number_format($item->embroidery_price,0) }} × {{ $item->quantity }}@else(ฟรี)@endif
                </div>
                @if($item->embroidery_text)<div style="font-size:11px;color:#999;margin-top:2px;white-space:pre-line;background:#f5f7f5;border-radius:8px;padding:6px 8px;">{{ $item->embroidery_text }}</div>@endif
                @endif
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

    {{-- สถานะออเดอร์ --}}
    @if(in_array($order->status, ['cancelled', 'refunded']))
    <div style="background:#fdecea;border:1px solid #f5c6cb;border-radius:20px;padding:clamp(16px,4vw,24px);margin-bottom:20px;color:#c0392b;">
        <h3 style="font-weight:800;margin-bottom:4px;"><i class="bi bi-x-octagon"></i> {{ $order->status_label }}</h3>
        <div style="font-size:14px;">ออเดอร์นี้{{ $order->status === 'refunded' ? 'ได้รับการคืนเงินแล้ว' : 'ถูกยกเลิกแล้ว' }} หากมีข้อสงสัยกรุณาติดต่อทีมงาน</div>
    </div>
    @else
    @php
        $steps = $order->is_pickup ? [
            'pending_payment'  => 'รอชำระเงิน',
            'payment_uploaded' => 'ตรวจสอบสลิป',
            'payment_verified' => 'ชำระเงินแล้ว',
            'processing'       => 'กำลังเตรียมสินค้า',
            'shipped'          => 'พร้อมรับที่ร้าน',
            'delivered'        => 'รับสินค้าแล้ว',
        ] : [
            'pending_payment'  => 'รอชำระเงิน',
            'payment_uploaded' => 'ตรวจสอบสลิป',
            'payment_verified' => 'ชำระเงินแล้ว',
            'processing'       => 'กำลังเตรียมส่ง',
            'shipped'          => 'จัดส่งแล้ว',
            'delivered'        => 'ส่งถึงแล้ว',
        ];
        $order_keys = array_keys($steps);
        $currentIndex = array_search($order->status, $order_keys);
        if ($currentIndex === false) $currentIndex = 0;
    @endphp
    <div style="background:white;border-radius:20px;padding:clamp(16px,4vw,24px);box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;overflow-x:auto;">
        <div style="display:flex;min-width:560px;">
            @foreach($steps as $key => $label)
            @php $done = $loop->index <= $currentIndex; $isCurrent = $loop->index === $currentIndex; @endphp
            <div style="flex:1;text-align:center;position:relative;">
                @if(! $loop->first)
                <div style="position:absolute;top:14px;left:-50%;width:100%;height:3px;background:{{ $done ? 'var(--kgm-green-500)' : '#e5e7eb' }};z-index:0;"></div>
                @endif
                <div style="position:relative;z-index:1;width:30px;height:30px;border-radius:999px;margin:0 auto;display:flex;align-items:center;justify-content:center;font-size:14px;color:white;background:{{ $done ? 'var(--kgm-green-600)' : '#e5e7eb' }};{{ $isCurrent ? 'box-shadow:0 0 0 4px var(--kgm-green-100);' : '' }}">
                    @if($done)<i class="bi bi-check-lg"></i>@else {{ $loop->iteration }} @endif
                </div>
                <div style="font-size:11px;margin-top:6px;font-weight:{{ $isCurrent ? '800' : '500' }};color:{{ $done ? 'var(--kgm-green-700)' : '#aaa' }};">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- เหตุผลกรณีสลิปถูกปฏิเสธ --}}
    @if($order->status === 'pending_payment' && $order->rejection_reason)
    <div style="background:#fdecea;border:1px solid #f5c6cb;border-radius:16px;padding:16px 20px;margin-bottom:20px;color:#c0392b;">
        <strong><i class="bi bi-exclamation-triangle"></i> สลิปไม่ผ่านการตรวจสอบ</strong>
        <div style="font-size:14px;margin-top:4px;">เหตุผล: {{ $order->rejection_reason }} — กรุณาอัปโหลดสลิปใหม่อีกครั้ง</div>
    </div>
    @endif

    @if($order->status === 'pending_payment' || $order->status === 'payment_uploaded')
    <div style="background:white;border-radius:20px;padding:clamp(16px,4vw,24px);box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;">
        <h3 style="font-weight:800;margin-bottom:16px;"><i class="bi bi-bank"></i> ช่องทางการชำระเงิน</h3>
        <div style="margin-bottom:20px;">
            @include('frontend._payment-info', ['payAmount' => $order->total, 'payRef' => $order->order_number])
        </div>
        <h3 style="font-weight:800;margin-bottom:16px;border-top:1px solid #f0f2f0;padding-top:20px;"><i class="bi bi-cloud-upload"></i> อัปโหลดสลิปการชำระเงิน</h3>
        @if($order->payment_slip)
        <div style="margin-bottom:16px;"><img src="{{ media_url($order->payment_slip) }}" style="max-width:200px;border-radius:12px;border:2px solid #eee;" alt="สลิป">
        <div style="font-size:12px;color:#888;margin-top:6px;">อัปโหลดแล้ว รอการตรวจสอบ</div></div>
        @endif
        <form method="POST" action="{{ route('orders.upload-slip', $order) }}" enctype="multipart/form-data">
            @csrf
            @if($isGuestView)<input type="hidden" name="token" value="{{ $order->guest_token }}">@endif
            <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <input type="file" name="slip" accept="image/*" class="form-control" style="flex:2 1 180px;min-width:0;border-radius:12px;" required>
                <button type="submit" class="btn btn-gold" style="flex:1 1 140px;justify-content:center;"><i class="bi bi-cloud-upload"></i> อัปโหลดสลิป</button>
            </div>
        </form>

        @if($order->status === 'pending_payment' && ! $isGuestView)
        <div style="border-top:1px solid #f0f2f0;margin-top:20px;padding-top:16px;">
            <form method="POST" action="{{ route('account.orders.cancel', $order) }}" id="cancel-order-form" style="text-align:center;">
                @csrf
            <div style="font-size:13px;color:#888;margin-bottom:10px;">ยังไม่ต้องการสั่งซื้อแล้ว? สามารถยกเลิกออเดอร์นี้ได้ก่อนชำระเงิน</div>
                <button type="submit" class="btn btn-outline" style="background:#fff;border-color:#ddd;color:#555;"><i class="bi bi-x-circle"></i> ยกเลิกออเดอร์</button>
            </form>
        </div>
        @endif
    </div>
    @endif

    @if($order->is_pickup)
    <div style="background:var(--kgm-green-100);border-radius:20px;padding:clamp(16px,4vw,24px);">
        <h3 style="font-weight:800;margin-bottom:8px;color:var(--kgm-green-800);"><i class="bi bi-shop"></i> รับเองที่ร้าน</h3>
        @if($order->pickupShowroom)
        <div style="font-weight:700;">{{ $order->pickupShowroom->name }}</div>
        <div style="font-size:14px;color:#555;margin-top:4px;line-height:1.7;">
            <i class="bi bi-geo-alt"></i> {{ $order->pickupShowroom->address }}<br>
            @if($order->pickupShowroom->phone)<i class="bi bi-telephone"></i> {{ $order->pickupShowroom->phone }}<br>@endif
            @if($order->pickupShowroom->open_hours)<i class="bi bi-clock"></i> {{ $order->pickupShowroom->open_hours }}@endif
        </div>
        @else
        <div style="font-size:14px;color:#555;">รับสินค้าที่หน้าร้าน — ทางร้านจะแจ้งเมื่อสินค้าพร้อมรับ</div>
        @endif
        <div style="margin-top:10px;font-size:14px;color:#555;line-height:1.7;border-top:1px solid rgba(0,0,0,0.06);padding-top:10px;">
            <strong>ผู้รับสินค้า:</strong> {{ $order->ship_name }} <i class="bi bi-telephone"></i> {{ $order->ship_phone }}
        </div>
        @if($order->status === 'shipped')
        <div style="margin-top:10px;font-weight:700;color:var(--kgm-green-700);"><i class="bi bi-check-circle"></i> สินค้าพร้อมให้เข้ามารับแล้ว</div>
        @endif
    </div>
    @else
    <div style="background:var(--kgm-green-100);border-radius:20px;padding:clamp(16px,4vw,24px);">
        <h3 style="font-weight:800;margin-bottom:8px;color:var(--kgm-green-800);"><i class="bi bi-geo-alt"></i> ที่อยู่จัดส่ง</h3>
        <div style="font-weight:700;">{{ $order->ship_name }}</div>
        <div style="font-size:14px;color:#555;margin-top:4px;line-height:1.7;">
            {{ $order->ship_address }}<br>
            ต.{{ $order->ship_district }} อ.{{ $order->ship_amphoe }}<br>
            จ.{{ $order->ship_province }} {{ $order->ship_postcode }}<br>
            <i class="bi bi-telephone"></i> {{ $order->ship_phone }}
        </div>
        @if($order->tracking_number)
        <div style="margin-top:12px;padding-top:12px;border-top:1px solid rgba(0,0,0,0.06);">
            <div style="font-weight:700;color:var(--kgm-green-800);margin-bottom:4px;"><i class="bi bi-truck"></i> ติดตามพัสดุ</div>
            <div>บริษัทขนส่ง: <strong>{{ $order->shipping_provider }}</strong></div>
            <div style="font-size:18px;font-weight:800;color:var(--kgm-green-700);margin-top:2px;">{{ $order->tracking_number }}</div>
        </div>
        @endif
    </div>
    @endif

    {{-- ยืนยันรับสินค้า (ลูกค้ากดเอง — เฉพาะสมาชิก) --}}
    @if($order->status === 'shipped' && ! $isGuestView)
    <div style="background:white;border-radius:20px;padding:clamp(16px,4vw,24px);box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-top:20px;text-align:center;">
        <div style="font-weight:700;margin-bottom:4px;">{{ $order->is_pickup ? 'มารับสินค้าเรียบร้อยแล้วใช่ไหม?' : 'ได้รับสินค้าแล้วใช่ไหม?' }}</div>
        <div style="font-size:13px;color:#888;margin-bottom:14px;">กดยืนยันเพื่อปิดออเดอร์นี้</div>
        <form method="POST" action="{{ route('account.orders.confirm-received', $order) }}" onsubmit="return confirm('ยืนยันว่าได้รับสินค้าครบถ้วนแล้ว?')">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="bi bi-check2-circle"></i> ยืนยันได้รับสินค้าแล้ว</button>
        </form>
    </div>
    @endif
</div>

@push('scripts')
<script>
function copyGuestLink(btn) {
    const input = document.getElementById('guest-link');
    if (!input) return;
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
document.addEventListener('DOMContentLoaded', function () {
    const cancelForm = document.getElementById('cancel-order-form');
    if (cancelForm) {
        cancelForm.addEventListener('submit', function (e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'ยืนยันยกเลิกออเดอร์?',
                text: 'สินค้าจะถูกคืนเข้าสต๊อก และไม่สามารถกู้คืนได้',
                showCancelButton: true,
                confirmButtonText: 'ยกเลิกออเดอร์',
                cancelButtonText: 'ไม่ใช่ตอนนี้',
                confirmButtonColor: '#c0392b',
                cancelButtonColor: '#aaa',
                reverseButtons: true,
            }).then(function (result) {
                if (result.isConfirmed) cancelForm.submit();
            });
        });
    }
});
</script>
@endpush
@endsection
