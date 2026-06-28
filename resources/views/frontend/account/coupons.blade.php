@extends('layouts.app')
@section('title', 'คูปองของฉัน')
@section('content')
<div class="container acc-wrap">
    <div class="acc-layout">
        @include('frontend.account._sidebar')
        <div>
            <h2 style="font-size:20px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;">
                <i class="bi bi-ticket-perforated-fill"></i> คูปองของฉัน
            </h2>

            @if($myCoupons->isEmpty())
            <div class="acc-card" style="text-align:center;padding:64px 28px;">
                <i class="bi bi-ticket-perforated" style="font-size:48px;color:#ddd;display:block;margin-bottom:16px;"></i>
                <p style="color:#aaa;margin-bottom:16px;">ยังไม่มีคูปองที่ใช้ได้</p>
                <a href="{{ route('coupons.index') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> เก็บคูปอง
                </a>
            </div>
            @else
            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(320px,1fr));gap:16px;">
                @foreach($myCoupons as $cc)
                @php $coupon = $cc->coupon; @endphp
                <div style="position:relative;display:flex;background:white;border-radius:18px;box-shadow:0 2px 16px rgba(0,0,0,0.07);min-height:140px;overflow:visible;">

                    {{-- notch top/bottom --}}
                    <div style="content:'';position:absolute;left:calc(38% - 11px);top:-11px;width:22px;height:22px;border-radius:50%;background:#f5f7fa;z-index:3;box-shadow:inset 0 0 0 1px rgba(0,0,0,0.06);"></div>
                    <div style="content:'';position:absolute;left:calc(38% - 11px);bottom:-11px;width:22px;height:22px;border-radius:50%;background:#f5f7fa;z-index:3;box-shadow:inset 0 0 0 1px rgba(0,0,0,0.06);"></div>

                    {{-- LEFT --}}
                    <div style="flex:0 0 38%;border-radius:18px 0 0 18px;display:flex;align-items:center;justify-content:center;overflow:hidden;position:relative;min-height:120px;">
                        @if($coupon->image)
                        <div style="position:absolute;inset:0;background:linear-gradient(135deg,#ffffff,#ffffff);"></div>
                        <img src="{{ media_url($coupon->image) }}" alt="{{ $coupon->name }}"
                             style="width:100%;height:100%;object-fit:contain;padding:14px;position:relative;z-index:1;">
                        @else
                        <div style="background:linear-gradient(135deg,var(--kgm-green-700),var(--kgm-green-500));position:absolute;inset:0;border-radius:18px 0 0 18px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:4px;color:white;text-align:center;">
                            @if($coupon->type === 'free_shipping')
                                <i class="bi bi-truck" style="font-size:26px;"></i>
                                <span style="font-size:11px;font-weight:700;letter-spacing:1px;opacity:.85;">ส่งฟรี</span>
                            @elseif($coupon->type === 'percent')
                                <span style="font-size:26px;font-weight:900;line-height:1;">{{ (int)$coupon->value }}%</span>
                                <span style="font-size:11px;font-weight:700;letter-spacing:1px;opacity:.85;">ส่วนลด</span>
                            @else
                                <span style="font-size:20px;font-weight:900;line-height:1;">฿{{ number_format((float)$coupon->value,0) }}</span>
                                <span style="font-size:11px;font-weight:700;letter-spacing:1px;opacity:.85;">ส่วนลด</span>
                            @endif
                        </div>
                        @endif
                    </div>

                    {{-- divider --}}
                    <div style="width:1px;background:repeating-linear-gradient(to bottom,#ddd 0,#ddd 6px,transparent 6px,transparent 12px);flex-shrink:0;align-self:stretch;margin:12px 0;"></div>

                    {{-- RIGHT --}}
                    <div style="flex:1;padding:16px 18px;display:flex;flex-direction:column;gap:5px;min-width:0;">
                        <span style="font-family:monospace;font-size:13px;font-weight:700;color:white;background:var(--kgm-green-600);display:inline-block;padding:2px 10px;border-radius:6px;letter-spacing:1.5px;align-self:flex-start;">
                            {{ $coupon->code }}
                        </span>
                        <div style="font-size:14px;font-weight:700;color:#222;margin-top:2px;">{{ $coupon->name }}</div>
                        <div style="font-size:19px;font-weight:900;color:var(--kgm-green-700);line-height:1.1;">
                            @if($coupon->type === 'free_shipping')
                                <i class="bi bi-truck"></i> จัดส่งฟรี
                            @elseif($coupon->type === 'percent')
                                ลด {{ (int)$coupon->value }}%
                                @if($coupon->maximum_discount)
                                <span style="font-size:11px;font-weight:400;color:#888;">(สูงสุด ฿{{ number_format($coupon->maximum_discount,0) }})</span>
                                @endif
                            @else
                                ลด ฿{{ number_format($coupon->value,0) }}
                            @endif
                        </div>
                        <div style="font-size:11px;color:#777;display:flex;flex-direction:column;gap:2px;">
                            @if($coupon->minimum_order > 0)
                            <span><i class="bi bi-cart-check" style="color:var(--kgm-green-500);margin-right:3px;"></i>ซื้อขั้นต่ำ ฿{{ number_format($coupon->minimum_order,0) }}</span>
                            @endif
                        </div>
                        <div style="font-size:11px;color:#bbb;margin-top:auto;padding-top:4px;">
                            @if($coupon->expires_at)
                            <i class="bi bi-clock"></i> หมดอายุ {{ $coupon->expires_at->translatedFormat('j M Y') }}
                            @else
                            <i class="bi bi-infinity"></i> ไม่มีวันหมดอายุ
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="margin-top:20px;text-align:center;">
                <a href="{{ route('coupons.index') }}" style="font-size:13px;color:var(--kgm-green-600);font-weight:600;">
                    <i class="bi bi-plus-circle"></i> เก็บคูปองเพิ่ม
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
