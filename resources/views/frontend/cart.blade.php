@extends('layouts.app')
@section('title', 'ตะกร้าสินค้า')

@push('styles')
<style>
.cart-layout {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 28px;
    align-items: start;
}
.cart-summary {
    background: white;
    border-radius: 20px;
    padding: 24px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    position: sticky;
    top: 90px;
}
.cart-item-row {
    display: flex;
    gap: 16px;
    padding: 20px;
    border-bottom: 1px solid #f5f7f5;
}
.cart-item-img { width: 90px; height: 90px; object-fit: cover; border-radius: 14px; flex-shrink: 0; }
.cart-item-info { flex: 1; min-width: 0; }
.cart-item-actions { display: flex; flex-direction: column; align-items: flex-end; gap: 10px; flex-shrink: 0; }
.qty-control { display: flex; align-items: center; border: 2px solid #e8ecef; border-radius: 999px; overflow: hidden; }
.qty-btn { width: 36px; height: 36px; background: none; border: none; cursor: pointer; font-size: 16px; color: var(--kgm-green-600); }


@media (max-width: 767px) {
    .cart-layout {
        grid-template-columns: 1fr;
        gap: 16px;
    }
    .cart-summary {
        position: static;
    }
    /* มือถือ: รูป+ข้อมูลสินค้าอยู่แถวบนเต็มความกว้าง, ปุ่ม/ราคารวมย้ายลงแถวล่าง */
    .cart-item-row {
        padding: 14px;
        gap: 12px;
        flex-wrap: wrap;
    }
    .cart-item-img { width: 72px; height: 72px; border-radius: 10px; }
    .cart-item-actions {
        flex-direction: row;
        align-items: center;
        flex-basis: 100%;
        width: 100%;
        gap: 12px;
        margin-top: 6px;
    }
    .cart-item-actions .qty-control { order: 1; }
    .cart-item-actions [id^="item-subtotal-"] {
        order: 2;
        margin-left: auto;
        font-size: 16px;
    }
    .cart-item-actions > button { order: 3; font-size: 20px; }
    .qty-btn { width: 32px; height: 32px; font-size: 14px; }
}
</style>
@endpush

@section('content')
<div class="container" style="padding-top:32px;padding-bottom:64px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;gap:12px;">
        <h1 style="font-size:clamp(20px,5vw,28px);font-weight:800;color:var(--kgm-green-800);margin:0;">
            <i class="bi bi-cart3"></i> ตะกร้าสินค้า
        </h1>
        <a href="{{ route('shop') }}" class="btn btn-outline" style="white-space:nowrap;flex-shrink:0;">
            <i class="bi bi-arrow-left"></i> ช็อปปิ้งต่อ
        </a>
    </div>

    @if($cartItems->isEmpty())
    <div style="text-align:center;padding:80px 0;background:white;border-radius:24px;">
        <i class="bi bi-cart-x" style="font-size:64px;color:#ddd;display:block;margin-bottom:20px;"></i>
        <h2 style="color:#aaa;font-size:22px;">ตะกร้าของคุณว่างเปล่า</h2>
        <p style="color:#aaa;margin:8px 0 24px;">เลือกสินค้าที่ต้องการแล้วเพิ่มลงตะกร้า</p>
        <a href="{{ route('shop') }}" class="btn btn-primary btn-lg"><i class="bi bi-shop"></i> ช็อปปิ้งต่อ</a>
    </div>
    @else
    <div class="cart-layout">

        {{-- Cart Items --}}
        <div>
            <div style="background:white;border-radius:20px;box-shadow:0 2px 10px rgba(0,0,0,0.06);overflow:hidden;">
                @foreach($cartItems as $item)
                <div class="cart-item-row" id="cart-item-{{ $item->id }}">
                    <a href="{{ route('shop.show', $item->product->slug) }}" style="flex-shrink:0;">
                        <img src="{{ $item->product->main_image ? asset('storage/'.$item->product->main_image) : asset('images/no-product.png') }}"
                            class="cart-item-img" alt="{{ $item->product->name }}">
                    </a>
                    <div class="cart-item-info">
                        <a href="{{ route('shop.show', $item->product->slug) }}" style="font-weight:700;font-size:14px;color:var(--kgm-green-900);display:block;line-height:1.4;">{{ $item->product->name }}</a>
                        @if($item->variant)
                        <div style="font-size:12px;color:#888;margin-top:2px;">{{ $item->variant->label }}</div>
                        @endif
                        @if($item->embroidery)
                        <div style="font-size:12px;color:var(--kgm-green-700);margin-top:4px;font-weight:600;">
                            <i class="bi bi-pen"></i> ปักชื่อ
                            @if($item->embroidery_price > 0)
                                <span style="font-weight:400;color:#888;">(+฿{{ number_format($item->embroidery_price, 0) }}/ตัว)</span>
                            @else
                                <span style="font-weight:400;color:#16a34a;">(ฟรี)</span>
                            @endif
                        </div>
                        @if($item->embroidery_text)
                        <div style="font-size:11px;color:#999;margin-top:2px;white-space:pre-line;background:#f5f7f5;border-radius:8px;padding:6px 8px;">{{ $item->embroidery_text }}</div>
                        @endif
                        @endif
                        @php
                            $adj          = $item->variant?->price_adjustment ?? 0;
                            $unitPrice    = ($item->flash_sale_price ?? $item->product->current_price) + $adj;
                            $originalUnit = $item->product->price + $adj;
                            if ($item->flash_sale_price !== null) {
                                $itemDiscount = $item->product->price > 0
                                    ? (int) round(($item->product->price - $item->flash_sale_price) / $item->product->price * 100)
                                    : 0;
                            } else {
                                $itemDiscount = $item->product->discount_percent ?? 0;
                            }
                        @endphp
                        <div style="display:flex;align-items:center;flex-wrap:wrap;gap:8px;margin-top:6px;">
                            <span style="font-size:15px;font-weight:700;color:var(--kgm-green-600);">฿{{ number_format($unitPrice, 0) }}</span>
                            @if($itemDiscount > 0)
                            <span style="font-size:12px;color:#999;text-decoration:line-through;">฿{{ number_format($originalUnit, 0) }}</span>
                            <span style="font-size:11px;font-weight:700;color:#fff;background:#e53935;border-radius:6px;padding:1px 6px;">-{{ $itemDiscount }}%</span>
                            @endif
                        </div>
                    </div>
                    <div class="cart-item-actions">
                        <button type="button" onclick="cartRemove({{ $item->id }}, this)"
                            style="background:none;border:none;cursor:pointer;color:#bbb;font-size:17px;padding:0;" title="ลบ">
                            <i class="bi bi-trash3"></i>
                        </button>
                        <div class="qty-control">
                            <button type="button" class="qty-btn" onclick="cartQty({{ $item->id }}, -1)">-</button>
                            <span id="qty-{{ $item->id }}" style="width:36px;text-align:center;font-weight:700;font-size:14px;">{{ $item->quantity }}</span>
                            <button type="button" class="qty-btn" onclick="cartQty({{ $item->id }}, 1)">+</button>
                        </div>
                        <div id="item-subtotal-{{ $item->id }}" style="font-weight:800;font-size:14px;color:var(--kgm-green-800);white-space:nowrap;">฿{{ number_format($item->subtotal, 0) }}</div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>

        {{-- Summary --}}
        <div class="cart-summary">
            <h3 style="font-size:18px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid #f0f2f0;">
                <i class="bi bi-receipt"></i> สรุปรายการ
            </h3>

            {{-- Applied coupons --}}
            @if($coupon || $stackedCoupon)
            <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:12px;">
                @if($coupon)
                <div style="background:var(--kgm-green-100);border-radius:12px;padding:10px 12px;display:flex;align-items:center;justify-content:space-between;gap:8px;">
                    <span style="font-weight:700;color:var(--kgm-green-700);font-size:13px;">
                        <i class="bi bi-ticket-perforated"></i> {{ $coupon->code }}
                        <span style="font-weight:400;font-size:12px;"> — {{ $coupon->getTypeLabel() }}</span>
                    </span>
                    <form method="POST" action="{{ route('cart.coupon.remove') }}" style="margin:0;">
                        @csrf @method('DELETE')
                        <button type="submit" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:14px;padding:0;"><i class="bi bi-x-lg"></i></button>
                    </form>
                </div>
                @endif
                @if($stackedCoupon)
                <div style="background:#e8f4fd;border-radius:12px;padding:10px 12px;display:flex;align-items:center;justify-content:space-between;gap:8px;">
                    <span style="font-weight:700;color:#0369a1;font-size:13px;">
                        <i class="bi bi-truck"></i> {{ $stackedCoupon->code }}
                        <span style="font-weight:400;font-size:12px;"> — {{ $stackedCoupon->getTypeLabel() }}</span>
                    </span>
                    <form method="POST" action="{{ route('cart.coupon.remove') }}" style="margin:0;">
                        @csrf @method('DELETE')
                        <input type="hidden" name="stacked" value="1">
                        <button type="submit" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:14px;padding:0;"><i class="bi bi-x-lg"></i></button>
                    </form>
                </div>
                @endif
            </div>
            @endif

            {{-- Input form --}}
            <form id="coupon-form" method="POST" action="{{ route('cart.coupon') }}" style="display:flex;gap:8px;margin-bottom:8px;">
                @csrf
                <input type="text" name="coupon_code" id="coupon-input" class="form-control" placeholder="รหัสคูปอง" style="border-radius:12px;flex:1;min-width:0;" value="{{ old('coupon_code') }}" required>
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check-lg"></i></button>
            </form>

            {{-- คูปองของฉัน --}}
            @auth('customer')
            @if($collectedCoupons->isNotEmpty())
            <div style="margin-bottom:8px;">
                <button type="button" data-bs-toggle="modal" data-bs-target="#couponModal"
                    style="width:100%;display:flex;align-items:center;justify-content:space-between;background:#f0faf4;border:1.5px dashed var(--kgm-green-400);border-radius:12px;padding:10px 14px;cursor:pointer;font-size:13px;font-weight:600;color:var(--kgm-green-700);">
                    <span><i class="bi bi-ticket-perforated"></i> คูปองของฉัน</span>
                    <span style="background:var(--kgm-green-500);color:white;border-radius:999px;padding:1px 8px;font-size:11px;">{{ $collectedCoupons->count() }}</span>
                </button>
            </div>
            @endif
            @endauth

            @if($errors->has('coupon_code'))
            <div id="__swal_coupon_error" data-msg="{{ $errors->first('coupon_code') }}" style="display:none;"></div>
            @endif

            {{-- Totals --}}
            <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
                <div style="display:flex;justify-content:space-between;font-size:14px;"><span>ยอดสินค้า</span><span id="summary-subtotal">฿{{ number_format($subtotal, 2) }}</span></div>
                <div id="summary-discount-row" style="display:{{ $discountAmount > 0 ? 'flex' : 'none' }};justify-content:space-between;font-size:14px;color:#e74c3c;"><span>ส่วนลด</span><span id="summary-discount">-฿{{ number_format($discountAmount, 2) }}</span></div>
                <div style="display:flex;justify-content:space-between;font-size:14px;"><span>ค่าจัดส่ง</span><span id="summary-shipping">{{ $shippingFee > 0 ? '฿'.number_format($shippingFee,2) : 'ฟรี' }}</span></div>
                <div style="border-top:2px solid #e8ecef;padding-top:10px;display:flex;justify-content:space-between;font-size:18px;font-weight:800;color:var(--kgm-green-700);">
                    <span>ยอดรวม</span><span id="summary-total">฿{{ number_format($total, 2) }}</span>
                </div>
            </div>

            @auth('customer')
            <a href="{{ route('checkout') }}" class="btn btn-primary w-full btn-lg" style="justify-content:center;">
                <i class="bi bi-bag-check"></i> ดำเนินการสั่งซื้อ
            </a>
            @else
            <a href="{{ route('checkout') }}" class="btn btn-primary w-full btn-lg" style="justify-content:center;">
                <i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบเพื่อสั่งซื้อ
            </a>
            @endauth
        </div>

    </div>
    @endif
</div>

{{-- ── Coupon Modal ──────────────────────────────────────────────────── --}}
@auth('customer')
@if(isset($collectedCoupons) && $collectedCoupons->isNotEmpty())
<div class="modal fade" id="couponModal" tabindex="-1" aria-labelledby="couponModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width:460px;">
        <div class="modal-content" style="border-radius:20px;border:none;">

            <div class="modal-header" style="background:linear-gradient(135deg,var(--kgm-green-700),var(--kgm-green-500));border:none;padding:18px 20px;border-radius:20px 20px 0 0;">
                <div>
                    <h5 class="modal-title" id="couponModalLabel" style="color:white;font-weight:800;margin:0;font-size:16px;">
                        <i class="bi bi-ticket-perforated"></i> คูปองของฉัน
                    </h5>
                    <div style="font-size:12px;color:rgba(255,255,255,0.8);margin-top:2px;">{{ $collectedCoupons->count() }} คูปองที่ใช้ได้</div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" style="padding:12px;">
                @foreach($collectedCoupons as $cc)
                @php
                    $c        = $cc->coupon;
                    $canUse   = $subtotal >= $c->minimum_order;
                    $need     = $c->minimum_order - $subtotal;
                    $daysLeft = $c->expires_at ? now()->diffInDays($c->expires_at, false) : null;
                    $urgent   = $daysLeft !== null && $daysLeft <= 3;
                @endphp
                <div data-coupon-item data-minimum="{{ (float) $c->minimum_order }}"
                     style="border:1.5px solid {{ $canUse ? '#d4edda' : '#e0e0e0' }};border-radius:12px;overflow:hidden;margin-bottom:10px;background:{{ $canUse ? '#fff' : '#fafafa' }};">

                    {{-- Single-row header --}}
                    <div style="padding:12px 14px;display:flex;align-items:center;gap:10px;">
                        @if($c->image)
                            <img src="{{ asset('storage/'.$c->image) }}" style="width:36px;height:36px;object-fit:cover;border-radius:8px;flex-shrink:0;{{ $canUse ? '' : 'opacity:.5' }}">
                        @else
                            <div style="width:36px;height:36px;background:{{ $canUse ? 'var(--kgm-green-100)' : '#eee' }};border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-ticket-perforated" style="color:{{ $canUse ? 'var(--kgm-green-600)' : '#bbb' }};font-size:16px;"></i>
                            </div>
                        @endif
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:14px;font-weight:800;color:{{ $canUse ? 'var(--kgm-green-800)' : '#999' }};letter-spacing:.5px;">{{ $c->code }}</div>
                            @if($c->name)
                            <div style="font-size:11px;color:#888;margin-top:1px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $c->name }}</div>
                            @endif
                        </div>
                        <div style="display:flex;flex-direction:column;align-items:flex-end;gap:4px;flex-shrink:0;">
                            <span style="font-size:11px;font-weight:700;padding:3px 8px;border-radius:6px;white-space:nowrap;background:{{ $canUse ? 'var(--kgm-green-100)' : '#eee' }};color:{{ $canUse ? 'var(--kgm-green-700)' : '#999' }};">
                                {{ $c->getTypeLabel() }}
                            </span>
                            @if($c->is_stackable)
                            <span style="font-size:10px;font-weight:600;padding:2px 6px;border-radius:4px;background:#dbeafe;color:#1d4ed8;white-space:nowrap;">
                                <i class="bi bi-layers"></i> ใช้คู่ได้
                            </span>
                            @endif
                        </div>
                    </div>

                    {{-- Compact meta row --}}
                    <div style="padding:0 14px 10px;display:flex;align-items:center;gap:12px;font-size:11px;color:#888;flex-wrap:wrap;">
                        @if($c->minimum_order > 0)
                        <span><i class="bi bi-cart-check"></i> ขั้นต่ำ ฿{{ number_format($c->minimum_order, 0) }}</span>
                        @endif
                        @if($c->expires_at)
                        <span style="color:{{ $urgent ? '#e74c3c' : '#888' }};">
                            <i class="bi bi-clock"></i>
                            หมด {{ $c->expires_at->format('d/m/') }}{{ $c->expires_at->year + 543 }}
                            @if($urgent)<strong> ({{ $daysLeft }}ว.)</strong>@endif
                        </span>
                        @endif
                        @if($c->hasProductScope())
                        <span><i class="bi bi-tag"></i> เฉพาะสินค้าที่กำหนด</span>
                        @endif
                    </div>

                    {{-- Button / cannot-use state (both rendered, JS toggles) --}}
                    <div style="padding:0 12px 12px;">
                        <div class="coupon-action-can" style="display:{{ $canUse ? 'block' : 'none' }}">
                            <form method="POST" action="{{ route('cart.coupon') }}">
                                @csrf
                                <input type="hidden" name="coupon_code" value="{{ $c->code }}">
                                <button type="submit" class="btn btn-primary w-100" style="font-size:13px;" data-bs-dismiss="modal">
                                    <i class="bi bi-check-lg"></i> ใช้คูปองนี้
                                </button>
                            </form>
                        </div>
                        <div class="coupon-action-cannot" style="display:{{ $canUse ? 'none' : 'flex' }};background:#fff3cd;border-radius:8px;padding:8px 12px;font-size:12px;color:#856404;align-items:center;gap:6px;">
                            <i class="bi bi-exclamation-triangle-fill" style="flex-shrink:0;"></i>
                            ซื้อเพิ่มอีก <strong class="coupon-need">฿{{ number_format($need, 0) }}</strong> จึงจะใช้คูปองนี้ได้
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</div>
@endif
@endauth
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;

// --- Coupon form validation & server-error SweetAlert ---
document.addEventListener('DOMContentLoaded', function () {
    const form  = document.getElementById('coupon-form');
    const input = document.getElementById('coupon-input');

    if (form) {
        form.addEventListener('submit', function (e) {
            if (!input.value.trim()) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณากรอกรหัสคูปอง',
                    text: 'โปรดระบุรหัสคูปองก่อนกดยืนยัน',
                    confirmButtonText: 'ตกลง',
                    confirmButtonColor: 'var(--kgm-green-600)',
                });
                input.focus();
            }
        });
    }

    const couponErrEl = document.getElementById('__swal_coupon_error');
    if (couponErrEl) {
        Swal.fire({
            icon: 'error',
            title: 'ไม่สามารถใช้คูปองได้',
            text: couponErrEl.dataset.msg,
            confirmButtonText: 'ตกลง',
            confirmButtonColor: 'var(--kgm-green-600)',
        });
    }
});

function fmtDec(n) {
    return '฿' + parseFloat(n).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
}
function fmtWhole(n) {
    return '฿' + Math.round(n).toLocaleString('en-US');
}

function updateSummary(data) {
    updateCartBadge(data.count);

    const subtotalEl = document.getElementById('summary-subtotal');
    const discRow    = document.getElementById('summary-discount-row');
    const discEl     = document.getElementById('summary-discount');
    const shippingEl = document.getElementById('summary-shipping');
    const totalEl    = document.getElementById('summary-total');

    if (subtotalEl) subtotalEl.textContent = fmtDec(data.subtotal);

    if (discRow) {
        if (data.discount_amount > 0) {
            discRow.style.display = 'flex';
            if (discEl) discEl.textContent = '-' + fmtDec(data.discount_amount);
        } else {
            discRow.style.display = 'none';
        }
    }

    if (shippingEl) shippingEl.textContent = data.shipping_fee > 0 ? fmtDec(data.shipping_fee) : 'ฟรี';
    if (totalEl)    totalEl.textContent    = fmtDec(data.total);

    // Update "คูปองของฉัน" modal coupon availability
    document.querySelectorAll('[data-coupon-item]').forEach(item => {
        const minimum = parseFloat(item.dataset.minimum) || 0;
        const canUse  = data.subtotal >= minimum;
        const need    = Math.max(0, minimum - data.subtotal);

        const canEl    = item.querySelector('.coupon-action-can');
        const cannotEl = item.querySelector('.coupon-action-cannot');
        const needEl   = item.querySelector('.coupon-need');

        if (canEl)    canEl.style.display    = canUse ? 'block' : 'none';
        if (cannotEl) cannotEl.style.display = canUse ? 'none'  : 'flex';
        if (needEl)   needEl.textContent     = '฿' + Math.round(need).toLocaleString('en-US');

        item.style.borderColor = canUse ? '#d4edda' : '#e0e0e0';
        item.style.background  = canUse ? '#fff'    : '#fafafa';
    });
}

function cartQty(id, delta) {
    const qtyEl = document.getElementById('qty-' + id);
    const newQty = Math.max(0, parseInt(qtyEl.textContent) + delta);
    cartUpdate(id, newQty);
}

function cartUpdate(id, qty) {
    fetch(`/cart/update/${id}`, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json', 'Content-Type': 'application/json' },
        body: JSON.stringify({ quantity: qty })
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(data => {
        if (data.is_empty) { location.reload(); return; }
        if (data.item_removed) {
            document.getElementById('cart-item-' + id)?.remove();
        } else {
            const qtyEl      = document.getElementById('qty-' + id);
            const subtotalEl = document.getElementById('item-subtotal-' + id);
            if (qtyEl)      qtyEl.textContent      = data.item_quantity;
            if (subtotalEl) subtotalEl.textContent  = fmtWhole(data.item_subtotal);
        }
        updateSummary(data);
    })
    .catch(() => {});
}

function cartRemove(id, btn) {
    Swal.fire({
        icon: 'warning',
        title: 'ลบสินค้านี้?',
        text: 'ต้องการนำสินค้านี้ออกจากตะกร้าใช่หรือไม่',
        showCancelButton: true,
        confirmButtonText: 'ลบออก',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: 'var(--kgm-green-600)',
        reverseButtons: true,
    }).then((result) => {
        if (!result.isConfirmed) return;
        doCartRemove(id, btn);
    });
}

function doCartRemove(id, btn) {
    btn.disabled = true;
    fetch(`/cart/remove/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(data => {
        if (data.is_empty) { location.reload(); return; }
        document.getElementById('cart-item-' + id)?.remove();
        updateSummary(data);
    })
    .catch(() => { btn.disabled = false; });
}
</script>
@endpush
