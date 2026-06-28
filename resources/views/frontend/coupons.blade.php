@extends('layouts.app')
@section('title', 'คูปองส่วนลด')

@push('styles')
<style>
/* hide layout default alerts on this page */
.container > .alert { display:none !important; }

.cp-page { background:#f5f7fa; padding:40px 0 64px; min-height:60vh; }
.cp-head { text-align:center; margin-bottom:36px; }
.cp-head h1 { font-size:clamp(22px,5vw,32px); font-weight:800; color:var(--kgm-green-800); }
.cp-head p  { color:#888; margin-top:8px; }

.cp-grid {
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(340px,1fr));
    gap:20px;
}

/* ── Ticket card ── */
.cp-ticket {
    position:relative;
    display:flex;
    background:white;
    border-radius:18px;
    box-shadow:0 2px 16px rgba(0,0,0,0.07);
    min-height:160px;
    overflow:visible;
}
.cp-ticket::before,
.cp-ticket::after {
    content:'';
    position:absolute;
    left:calc(38% - 11px);
    width:22px; height:22px;
    border-radius:50%;
    background:#f5f7fa;
    z-index:3;
    box-shadow:inset 0 0 0 1px rgba(0,0,0,0.06);
}
.cp-ticket::before { top:-11px; }
.cp-ticket::after  { bottom:-11px; }

/* ── Left: image or gradient ── */
.cp-left {
    flex:0 0 38%;
    border-radius:18px 0 0 18px;
    display:flex;
    align-items:center;
    justify-content:center;
    overflow:hidden;
    position:relative;
    min-height:140px;
}
.cp-left-img {
    width:100%; height:100%;
    object-fit:contain;
    padding:14px;
    position:relative; z-index:1;
}
.cp-left-bg {
    position:absolute; inset:0;
    background:linear-gradient(135deg,#ffffff,#ffffff);
}
.cp-left-inner {
    display:flex; flex-direction:column; align-items:center; gap:4px;
    color:white; text-align:center; position:relative; z-index:1;
}
.cp-left-num  { font-size:28px; font-weight:900; line-height:1; }
.cp-left-lbl  { font-size:11px; font-weight:700; letter-spacing:1px; opacity:.85; }

/* dashed border between left & right */
.cp-divider {
    width:1px;
    background:repeating-linear-gradient(to bottom,#ddd 0,#ddd 6px,transparent 6px,transparent 12px);
    flex-shrink:0;
    align-self:stretch;
    margin:12px 0;
}

/* ── Right: info ── */
.cp-right {
    flex:1; padding:18px 20px;
    display:flex; flex-direction:column; gap:6px;
    min-width:0;
}
.cp-code {
    font-family:monospace; font-size:13px; font-weight:700;
    color:white; background:var(--kgm-green-600);
    display:inline-block; padding:2px 10px; border-radius:6px;
    letter-spacing:1.5px; align-self:flex-start;
}
.cp-name  { font-size:15px; font-weight:700; color:#222; margin-top:2px; }
.cp-value { font-size:21px; font-weight:900; color:var(--kgm-green-700); line-height:1.1; }
.cp-cond  { font-size:12px; color:#777; display:flex; flex-direction:column; gap:3px; }
.cp-cond i { color:var(--kgm-green-500); margin-right:3px; }
.cp-expire{ font-size:11px; color:#bbb; margin-top:auto; padding-top:6px; }
.cp-expire i { margin-right:3px; }

.cp-collected {
    background:var(--kgm-green-100); color:var(--kgm-green-700);
    border-radius:10px; padding:9px; text-align:center;
    font-weight:700; font-size:13px;
}
.cp-ribbon {
    position:absolute; top:14px; right:0;
    background:#e74c3c; color:white;
    font-size:10px; font-weight:700;
    padding:3px 10px; border-radius:6px 0 0 6px;
    z-index:4;
}

@media(max-width:480px){
    .cp-grid { grid-template-columns:1fr; }
    .cp-left-num { font-size:22px; }
}
</style>
@endpush

@section('content')
<div class="cp-page">
<div class="container">

    <div class="cp-head">
        <h1><i class="bi bi-ticket-perforated"></i> คูปองส่วนลด</h1>
        <p>เก็บคูปองไว้ใช้ตอนสั่งซื้อ — เลือกใช้ได้ในหน้าตะกร้า</p>
    </div>

    @if($coupons->isEmpty())
    <div style="text-align:center;padding:80px 0;background:white;border-radius:24px;">
        <i class="bi bi-ticket-perforated" style="font-size:56px;color:#ddd;display:block;margin-bottom:16px;"></i>
        <p style="color:#aaa;font-size:18px;">ไม่มีคูปองที่ใช้งานได้ในขณะนี้</p>
    </div>
    @else
    <div class="cp-grid">
        @foreach($coupons as $coupon)
        @php
            $isCollected = in_array($coupon->id, $collectedIds);
            $expiringSoon = $coupon->expires_at && $coupon->expires_at->diffInDays(now()) <= 3;
        @endphp

        <div class="cp-ticket mb-2">
                <div class="cp-left">
                    @if($coupon->image)
                        <div class="cp-left-bg"></div>
                        <img class="cp-left-img" src="{{ media_url($coupon->image) }}" alt="{{ $coupon->name }}">
                    @else
                    <div class="cp-left-inner" style="background:linear-gradient(135deg,var(--kgm-green-700),var(--kgm-green-500));position:absolute;inset:0;border-radius:18px 0 0 18px;justify-content:center;">
                        @if($coupon->type === 'free_shipping')
                            <i class="bi bi-truck" style="font-size:26px;"></i>
                            <span class="cp-left-lbl">ส่งฟรี</span>
                        @elseif($coupon->type === 'percent')
                            <span class="cp-left-num">{{ (int)$coupon->value }}%</span>
                            <span class="cp-left-lbl">ส่วนลด</span>
                        @else
                            <span class="cp-left-num" style="font-size:20px;">฿{{ number_format((float)$coupon->value,0) }}</span>
                            <span class="cp-left-lbl">ส่วนลด</span>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="cp-divider"></div>

                {{-- RIGHT --}}
                <div class="cp-right">
                    <div class="cp-name">{{ $coupon->name }}</div>
                    @if($coupon->type === 'free_shipping' || $coupon->value > 0)
                    <div class="cp-value">
                        @if($coupon->type === 'free_shipping')
                            <i class="bi bi-truck"></i> จัดส่งฟรี
                        @elseif($coupon->type === 'percent')
                            ลด {{ (int)$coupon->value }}%
                            @if($coupon->maximum_discount)
                            <span style="font-size:12px;font-weight:400;color:#888;">(สูงสุด ฿{{ number_format($coupon->maximum_discount,0) }})</span>
                            @endif
                        @else
                            ลด ฿{{ number_format($coupon->value,0) }}
                        @endif
                    </div>
                    @endif
                    <div class="cp-cond">
                        @if($coupon->minimum_order > 0)
                        <span><i class="bi bi-cart-check"></i>ซื้อขั้นต่ำ ฿{{ number_format($coupon->minimum_order,0) }}</span>
                        @endif
                        @if($coupon->hasProductScope())
                        <span><i class="bi bi-box-seam"></i>
                            @if($coupon->categories->isNotEmpty())เฉพาะหมวด: {{ $coupon->categories->pluck('name')->join(', ') }}@endif
                            @if($coupon->products->isNotEmpty())เฉพาะ {{ $coupon->products->count() }} สินค้า@endif
                        </span>
                        @endif
                        @if($coupon->usage_limit)
                        <span><i class="bi bi-people"></i>เหลือ {{ max(0,$coupon->usage_limit - $coupon->used_count) }} สิทธิ์</span>
                        @endif
                    </div>
                    <div class="cp-expire">
                        @if($coupon->expires_at)
                        <i class="bi bi-clock"></i>หมดอายุ {{ $coupon->expires_at->translatedFormat('j M Y H:i') }}
                        @else
                        <i class="bi bi-infinity"></i>ไม่มีวันหมดอายุ
                        @endif
                    </div>
                    <div style="margin-top:10px;">
                        @if($isCollected)
                        <div class="cp-collected">
                            <i class="bi bi-check-circle-fill"></i> เก็บแล้ว — ใช้ได้ในตะกร้า
                        </div>
                        @elseif(auth('customer')->check())
                        <form method="POST" action="{{ route('coupons.collect', $coupon) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full" style="justify-content:center;font-size:13px;padding:7px 12px;">
                                <i class="bi bi-plus-circle"></i> เก็บคูปอง
                            </button>
                        </form>
                        @else
                        <a href="{{ route('login') }}" class="btn btn-outline w-full" style="justify-content:center;font-size:13px;padding:7px 12px;">
                            <i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบเพื่อเก็บ
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endif

</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'สำเร็จ!',
        text: @json(session('success')),
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#2e7d32',
        timer: 3000,
        timerProgressBar: true,
    });
    @elseif(session('info'))
    Swal.fire({
        icon: 'info',
        title: 'แจ้งเตือน',
        text: @json(session('info')),
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#2e7d32',
        timer: 3000,
        timerProgressBar: true,
    });
    @elseif(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'เกิดข้อผิดพลาด',
        text: @json(session('error')),
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#2e7d32',
    });
    @endif
});
</script>
@endpush
