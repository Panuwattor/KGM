@extends('layouts.app')
@section('title', $flashSale->name.' - Flash Sale')

@push('styles')
<style>
.fs-hero {
    background: linear-gradient(135deg, #b71c1c 0%, #e53935 50%, #ff7043 100%);
    padding: 40px 0 32px;
    position: relative;
    overflow: hidden;
}
.fs-hero::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 80% 50%, rgba(255,255,255,.08) 0%, transparent 70%);
}
.fs-hero-inner {
    max-width: 1100px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    align-items: center;
    gap: 32px;
    position: relative;
    z-index: 1;
}
.fs-hero-img {
    width: 280px;
    flex-shrink: 0;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,.3);
}
.fs-hero-img img { width: 100%; height: 180px; object-fit: cover; display: block; }
.fs-hero-placeholder {
    width: 280px;
    height: 180px;
    flex-shrink: 0;
    border-radius: 16px;
    background: rgba(255,255,255,.15);
    display: flex;
    align-items: center;
    justify-content: center;
}
.fs-hero-content { flex: 1; color: white; }
.fs-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,.2);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 20px;
    padding: 4px 14px;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 12px;
}
.fs-hero-title {
    font-size: 32px;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 12px;
    text-shadow: 0 2px 8px rgba(0,0,0,.2);
}
.fs-countdown {
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}
.fs-countdown-label { font-size: 14px; opacity: .85; }
.fs-countdown-box {
    display: flex;
    gap: 4px;
}
.fs-digit {
    background: rgba(255,255,255,.2);
    border: 1px solid rgba(255,255,255,.3);
    border-radius: 8px;
    padding: 4px 10px;
    font-size: 22px;
    font-weight: 800;
    min-width: 44px;
    text-align: center;
    line-height: 1.2;
}
.fs-sep { font-size: 22px; font-weight: 800; align-self: center; opacity: .7; }

.fs-products { padding: 40px 0; }
.fs-products .container { max-width: 1100px; margin: 0 auto; padding: 0 24px; }
.fs-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
@media(max-width:900px) {
    .fs-grid { grid-template-columns: repeat(2, 1fr); gap: 14px; }
    .fs-hero-inner { flex-direction: column; text-align: center; }
    .fs-hero-img, .fs-hero-placeholder { width: 100%; }
    .fs-hero-img img { height: 160px; }
    .fs-hero-placeholder { height: 100px; }
    .fs-hero-title { font-size: 24px; }
    .fs-countdown { justify-content: center; }
}

.fs-product-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,.07);
    border: 2px solid #ffcdd2;
    transition: transform .2s, box-shadow .2s;
    display: flex;
    flex-direction: column;
}
.fs-product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 24px rgba(229,57,53,.18); }
.fs-product-img-wrap { position: relative; background: #fafafa; }
.fs-product-img-wrap img {
    width: 100%;
    aspect-ratio: 1;
    object-fit: cover;
    display: block;
}
.fs-badge-flash {
    position: absolute;
    top: 10px;
    left: 10px;
    background: #e53935;
    color: white;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 12px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 4px;
}
.fs-product-info { padding: 14px; flex: 1; display: flex; flex-direction: column; gap: 6px; }
.fs-product-category { font-size: 11px; color: #aaa; text-transform: uppercase; letter-spacing: .5px; }
.fs-product-name {
    font-size: 14px;
    font-weight: 700;
    color: #222;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-decoration: none;
}
.fs-product-name:hover { color: #e53935; }
.fs-price-row { display: flex; align-items: baseline; gap: 8px; margin-top: 4px; }
.fs-price-flash { font-size: 20px; font-weight: 800; color: #e53935; }
.fs-price-original { font-size: 13px; color: #bbb; text-decoration: line-through; }
.fs-price-save {
    font-size: 11px;
    background: #fff3e0;
    color: #e65100;
    border-radius: 10px;
    padding: 2px 8px;
    font-weight: 700;
}
.fs-add-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    background: #e53935;
    color: white;
    border: none;
    border-radius: 10px;
    padding: 10px;
    font-size: 14px;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    margin-top: auto;
    transition: background .2s;
}
.fs-add-btn:hover { background: #c62828; color: white; }
.fs-add-btn:disabled { background: #f0f0f0; color: #bbb; cursor: not-allowed; }
</style>
@endpush

@section('content')

{{-- HERO --}}
<div class="fs-hero">
    <div class="fs-hero-inner">
        @if($flashSale->image)
        <div class="fs-hero-img">
            <img src="{{ asset('storage/'.$flashSale->image) }}" alt="{{ $flashSale->name }}">
        </div>
        @else
        <div class="fs-hero-placeholder">
            <i class="bi bi-lightning-charge-fill" style="font-size:56px;color:rgba(255,255,255,.6);"></i>
        </div>
        @endif
        <div class="fs-hero-content">
            <div class="fs-hero-badge"><i class="bi bi-lightning-charge-fill"></i> Flash Sale</div>
            <div class="fs-hero-title">{{ $flashSale->name }}</div>
            <div class="fs-countdown">
                <span class="fs-countdown-label">สิ้นสุดใน</span>
                <div class="fs-countdown-box" id="fs-countdown" data-ends="{{ $flashSale->ends_at->toIso8601String() }}">
                    <span class="fs-digit" id="cd-d">--</span>
                    <span class="fs-sep">วัน</span>
                    <span class="fs-digit" id="cd-h">--</span>
                    <span class="fs-sep">:</span>
                    <span class="fs-digit" id="cd-m">--</span>
                    <span class="fs-sep">:</span>
                    <span class="fs-digit" id="cd-s">--</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- PRODUCTS --}}
<section class="fs-products" style="background:#fff8f8;">
    <div class="container">
        @if($flashSale->items->isEmpty())
        <div style="text-align:center;padding:60px 0;color:#aaa;">
            <i class="bi bi-box-seam" style="font-size:48px;display:block;margin-bottom:16px;"></i>
            ยังไม่มีสินค้าใน Flash Sale นี้
        </div>
        @else
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:24px;">
            <i class="bi bi-fire" style="color:#e53935;font-size:20px;"></i>
            <span style="font-size:18px;font-weight:800;color:#c62828;">สินค้าร่วมรายการ ({{ $flashSale->items->count() }} รายการ)</span>
        </div>
        <div class="fs-grid">
            @foreach($flashSale->items as $item)
            @php
                $product = $item->product;
                $flashPrice = (float) $item->sale_price;
                $origPrice  = (float) $product->price;
                $savePercent = $origPrice > 0 ? (int) round(($origPrice - $flashPrice) / $origPrice * 100) : 0;
            @endphp
            <div class="fs-product-card">
                <div class="fs-product-img-wrap">
                    <a href="{{ route('shop.show', $product->slug) }}">
                        <img src="{{ $product->main_image ? asset('storage/'.$product->main_image) : asset('images/logo.png') }}"
                             alt="{{ $product->name }}"
                             onerror="this.onerror=null;this.src='/images/logo.png'">
                    </a>
                    @if($savePercent > 0)
                    <div class="fs-badge-flash"><i class="bi bi-lightning-charge-fill"></i> -{{ $savePercent }}%</div>
                    @endif
                </div>
                <div class="fs-product-info">
                    @if($product->category?->name)
                    <div class="fs-product-category">{{ $product->category->name }}</div>
                    @endif
                    <a href="{{ route('shop.show', $product->slug) }}" class="fs-product-name">{{ $product->name }}</a>
                    <div class="fs-price-row">
                        <span class="fs-price-flash">฿{{ number_format($flashPrice, 0) }}</span>
                        @if($origPrice > $flashPrice)
                        <span class="fs-price-original">฿{{ number_format($origPrice, 0) }}</span>
                        @if($savePercent > 0)
                        <span class="fs-price-save">ประหยัด {{ $savePercent }}%</span>
                        @endif
                        @endif
                    </div>
                    @if($product->stock_quantity > 0)
                    <button type="button" class="fs-add-btn"
                            onclick="addFlashToCart({{ $product->id }}, {{ $flashPrice }}, this)">
                        <i class="bi bi-cart-plus"></i> หยิบใส่ตะกร้า
                    </button>
                    @else
                    <button disabled class="fs-add-btn">
                        <i class="bi bi-x-circle"></i> สินค้าหมด
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>
// Countdown timer
(function () {
    const el   = document.getElementById('fs-countdown');
    if (!el) return;
    const ends = new Date(el.dataset.ends).getTime();

    function tick() {
        const diff = ends - Date.now();
        if (diff <= 0) {
            ['cd-d','cd-h','cd-m','cd-s'].forEach(id => document.getElementById(id).textContent = '00');
            return;
        }
        const d = Math.floor(diff / 86400000);
        const h = Math.floor((diff % 86400000) / 3600000);
        const m = Math.floor((diff % 3600000)  / 60000);
        const s = Math.floor((diff % 60000)    / 1000);
        document.getElementById('cd-d').textContent = String(d).padStart(2,'0');
        document.getElementById('cd-h').textContent = String(h).padStart(2,'0');
        document.getElementById('cd-m').textContent = String(m).padStart(2,'0');
        document.getElementById('cd-s').textContent = String(s).padStart(2,'0');
    }
    tick();
    setInterval(tick, 1000);
})();

// Add to cart with flash price
function addFlashToCart(productId, flashPrice, btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> กำลังเพิ่ม...';

    fetch('{{ route('cart.add') }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
        },
        body: JSON.stringify({ product_id: productId, quantity: 1, flash_sale_price: flashPrice }),
    })
    .then(r => r.json())
    .then(data => {
        btn.innerHTML = '<i class="bi bi-check-circle"></i> เพิ่มแล้ว!';
        btn.style.background = '#388e3c';
        // update cart count badge if exists
        const badge = document.querySelector('.cart-count, [data-cart-count]');
        if (badge && data.count !== undefined) badge.textContent = data.count;
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-cart-plus"></i> หยิบใส่ตะกร้า';
            btn.style.background = '';
        }, 2000);
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-cart-plus"></i> หยิบใส่ตะกร้า';
        alert('เกิดข้อผิดพลาด กรุณาลองใหม่');
    });
}
</script>
@endpush
