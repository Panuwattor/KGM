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
        order: -1;
    }
    .cart-item-row {
        padding: 14px;
        gap: 12px;
    }
    .cart-item-img { width: 72px; height: 72px; border-radius: 10px; }
    .cart-item-actions { gap: 8px; }
    .qty-btn { width: 32px; height: 32px; font-size: 14px; }
}
</style>
@endpush

@section('content')
<div class="container" style="padding-top:32px;padding-bottom:64px;">
    <h1 style="font-size:clamp(20px,5vw,28px);font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;">
        <i class="bi bi-cart3"></i> ตะกร้าสินค้า
    </h1>

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
            {{-- Free Shipping Bar --}}
            <div id="shipping-bar-wrap" data-threshold="{{ $freeShippingThreshold }}"
                style="{{ $amountForFreeShipping > 0 ? 'background:linear-gradient(135deg,var(--kgm-gold-100),white);border:2px solid var(--kgm-gold-300);border-radius:16px;padding:14px 20px;margin-bottom:16px;display:flex;align-items:center;gap:10px;' : 'background:var(--kgm-green-100);border-radius:16px;padding:12px 20px;margin-bottom:16px;display:flex;align-items:center;gap:8px;color:var(--kgm-green-700);font-weight:700;font-size:14px;' }}">
                @if($amountForFreeShipping > 0)
                <i class="bi bi-truck" style="color:var(--kgm-gold-600);font-size:20px;flex-shrink:0;"></i>
                <div style="flex:1;min-width:0;">
                    <span style="font-weight:700;color:var(--kgm-green-800);font-size:14px;">ซื้อเพิ่มอีก ฿{{ number_format($amountForFreeShipping, 0) }} จะได้ส่งฟรี!</span>
                    <div style="background:#e8ecef;border-radius:999px;height:6px;margin-top:6px;overflow:hidden;">
                        <div style="background:linear-gradient(to right,var(--kgm-gold-500),var(--kgm-gold-300));height:100%;border-radius:999px;width:{{ min(100, ($subtotal/$freeShippingThreshold)*100) }}%;transition:width 0.5s;"></div>
                    </div>
                </div>
                @else
                <i class="bi bi-truck-flatbed"></i> คุณได้รับการจัดส่งฟรี!
                @endif
            </div>

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
                        <div style="font-size:15px;font-weight:700;color:var(--kgm-green-600);margin-top:6px;">
                            ฿{{ number_format($item->product->current_price + ($item->variant?->price_adjustment ?? 0), 0) }}
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

            <div style="margin-top:16px;">
                <a href="{{ route('shop') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> ช็อปปิ้งต่อ</a>
            </div>
        </div>

        {{-- Summary --}}
        <div class="cart-summary">
            <h3 style="font-size:18px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid #f0f2f0;">
                <i class="bi bi-receipt"></i> สรุปรายการ
            </h3>

            {{-- Coupon --}}
            @if($coupon)
            <div style="background:var(--kgm-green-100);border-radius:12px;padding:12px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-weight:700;color:var(--kgm-green-700);">
                    <i class="bi bi-ticket-perforated"></i> {{ $coupon->code }}
                    <span style="font-weight:400;font-size:13px;"> — {{ $coupon->getTypeLabel() }}</span>
                </span>
                <form method="POST" action="{{ route('cart.coupon.remove') }}">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:14px;"><i class="bi bi-x-lg"></i></button>
                </form>
            </div>
            @else
            <form method="POST" action="{{ route('cart.coupon') }}" style="display:flex;gap:8px;margin-bottom:8px;">
                @csrf
                <input type="text" name="coupon_code" class="form-control" placeholder="รหัสคูปอง" style="border-radius:12px;flex:1;min-width:0;" value="{{ old('coupon_code') }}">
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-check-lg"></i></button>
            </form>
            {{-- คูปองที่เก็บไว้ --}}
            @auth('customer')
            @if($collectedCoupons->isNotEmpty())
            <div style="margin-bottom:16px;">
                <div style="font-size:12px;color:#888;font-weight:600;margin-bottom:8px;"><i class="bi bi-collection"></i> คูปองของฉัน</div>
                <div style="display:flex;flex-direction:column;gap:6px;">
                    @foreach($collectedCoupons as $cc)
                    <form method="POST" action="{{ route('cart.coupon') }}" style="display:flex;align-items:center;gap:8px;background:#f8f9fa;border-radius:12px;padding:8px 12px;">
                        @csrf
                        <input type="hidden" name="coupon_code" value="{{ $cc->coupon->code }}">
                        <div style="flex:1;min-width:0;">
                            <span style="font-weight:700;font-size:13px;color:var(--kgm-green-800);">{{ $cc->coupon->code }}</span>
                            <span style="font-size:12px;color:#888;margin-left:6px;">{{ $cc->coupon->getTypeLabel() }}</span>
                            @if($cc->coupon->expires_at)
                            <span style="font-size:11px;color:#e74c3c;margin-left:6px;">หมด {{ $cc->coupon->expires_at->format('d/m/y') }}</span>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary" style="padding:4px 12px;font-size:12px;white-space:nowrap;">ใช้</button>
                    </form>
                    @endforeach
                </div>
            </div>
            @endif
            @endauth
            @endif
            @if($errors->has('coupon_code') || session('error'))
            <div style="color:#e74c3c;font-size:13px;margin-bottom:10px;"><i class="bi bi-exclamation-circle"></i> {{ $errors->first('coupon_code') ?? session('error') }}</div>
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
@endsection

@push('scripts')
<script>
const CSRF = document.querySelector('meta[name=csrf-token]').content;

function fmtDec(n) {
    return '฿' + parseFloat(n).toLocaleString('en-US', {minimumFractionDigits:2, maximumFractionDigits:2});
}
function fmtWhole(n) {
    return '฿' + Math.round(n).toLocaleString('en-US');
}

function updateSummary(data) {
    updateCartBadge(data.count);
    document.getElementById('summary-subtotal').textContent = fmtDec(data.subtotal);

    const discRow = document.getElementById('summary-discount-row');
    if (data.discount_amount > 0) {
        discRow.style.display = 'flex';
        document.getElementById('summary-discount').textContent = '-' + fmtDec(data.discount_amount);
    } else {
        discRow.style.display = 'none';
    }

    document.getElementById('summary-shipping').textContent = data.shipping_fee > 0 ? fmtDec(data.shipping_fee) : 'ฟรี';
    document.getElementById('summary-total').textContent = fmtDec(data.total);
    updateShippingBar(data);
}

function updateShippingBar(data) {
    const el = document.getElementById('shipping-bar-wrap');
    if (!el) return;
    const threshold = parseFloat(el.dataset.threshold);
    if (data.amount_for_free_shipping > 0) {
        const pct = Math.min(100, (threshold - data.amount_for_free_shipping) / threshold * 100);
        el.style.cssText = 'background:linear-gradient(135deg,var(--kgm-gold-100),white);border:2px solid var(--kgm-gold-300);border-radius:16px;padding:14px 20px;margin-bottom:16px;display:flex;align-items:center;gap:10px;';
        el.innerHTML = `<i class="bi bi-truck" style="color:var(--kgm-gold-600);font-size:20px;flex-shrink:0;"></i>
            <div style="flex:1;min-width:0;">
                <span style="font-weight:700;color:var(--kgm-green-800);font-size:14px;">ซื้อเพิ่มอีก ฿${Math.round(data.amount_for_free_shipping).toLocaleString('en-US')} จะได้ส่งฟรี!</span>
                <div style="background:#e8ecef;border-radius:999px;height:6px;margin-top:6px;overflow:hidden;">
                    <div style="background:linear-gradient(to right,var(--kgm-gold-500),var(--kgm-gold-300));height:100%;border-radius:999px;width:${pct}%;transition:width 0.5s;"></div>
                </div>
            </div>`;
    } else {
        el.style.cssText = 'background:var(--kgm-green-100);border-radius:16px;padding:12px 20px;margin-bottom:16px;display:flex;align-items:center;gap:8px;color:var(--kgm-green-700);font-weight:700;font-size:14px;';
        el.innerHTML = '<i class="bi bi-truck-flatbed"></i> คุณได้รับการจัดส่งฟรี!';
    }
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
    .then(r => r.json())
    .then(data => {
        if (data.is_empty) { location.reload(); return; }
        if (data.item_removed) {
            document.getElementById('cart-item-' + id)?.remove();
        } else {
            document.getElementById('qty-' + id).textContent = data.item_quantity;
            document.getElementById('item-subtotal-' + id).textContent = fmtWhole(data.item_subtotal);
        }
        updateSummary(data);
    })
    .catch(() => {});
}

function cartRemove(id, btn) {
    btn.disabled = true;
    fetch(`/cart/remove/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.is_empty) { location.reload(); return; }
        document.getElementById('cart-item-' + id)?.remove();
        updateSummary(data);
    })
    .catch(() => { btn.disabled = false; });
}
</script>
@endpush
