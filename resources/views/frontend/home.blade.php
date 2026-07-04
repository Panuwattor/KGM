@extends('layouts.app')
@section('title', 'หน้าแรก - กิจเจริญการ์เมนท์')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
@endpush

@section('navbar_secondary')
{{-- ══ NEWS TICKER ══ --}}
<div class="news-ticker-wrapper">
    <div class="news-ticker-container">
        <div class="news-ticker-label">
            <span>ข่าวสำคัญ</span>
            <i class="bi bi-megaphone-fill"></i>
        </div>
        <div class="news-ticker-content">
            <div class="news-ticker-track">
                <span class="news-ticker-item">
                    @foreach($newsTickers as $ticker)
                        @if($ticker->plain_text){{ $ticker->plain_text }}@endif
                        @if($ticker->link_text && $ticker->link_url)
                            <a href="{{ $ticker->link_url }}" class="ticker-link">{{ $ticker->link_text }}</a>
                        @endif
                        @if(!$loop->last) | @endif
                    @endforeach
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')

{{-- ══ HERO ══ --}}
@if($banners->isNotEmpty())
<div class="hero-section" id="hero-slider">
    <div class="hero-inner">
        <div class="hero-viewport">
            <div class="hero-track" id="hero-track">
                @foreach($banners as $i => $banner)
                <div class="hero-item">
                    @if($banner->link_url)
                    <a href="{{ $banner->link_url }}">
                        <img src="{{ media_url($banner->image_path) }}" alt="Banner {{ $i+1 }}" width="1200" height="600" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" {{ $i === 0 ? 'fetchpriority="high"' : '' }} onerror="this.onerror=null;this.src='/images/logo.png'">
                    </a>
                    @else
                    <img src="{{ media_url($banner->image_path) }}" alt="Banner {{ $i+1 }}" width="1200" height="600" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" {{ $i === 0 ? 'fetchpriority="high"' : '' }} onerror="this.onerror=null;this.src='/images/logo.png'">
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @if($banners->count() > 1)
        <button class="hero-nav prev" id="hero-prev" aria-label="ก่อนหน้า"><i class="bi bi-chevron-left"></i></button>
        <button class="hero-nav next" id="hero-next" aria-label="ถัดไป"><i class="bi bi-chevron-right"></i></button>
        @endif
    </div>
    @if($banners->count() > 1)
    <div class="hero-dots" id="hero-dots">
        @foreach($banners as $i => $_)
        <button class="hero-dot {{ $i===0?'active':'' }}" onclick="heroGo({{ $i }})" aria-label="สไลด์ {{ $i+1 }}"></button>
        @endforeach
    </div>
    @endif
</div>
@endif


{{-- ══ PRODUCT TYPES ══ --}}
@if($productTypes->isNotEmpty())
<section class="type-section">
    <div class="container">
        <div class="type-grid">
            @foreach($productTypes as $pt)
            <a href="{{ route('shop') }}?type={{ $pt->slug }}" class="type-card">
                <div class="type-img-wrap">
                    @if($pt->image)
                        <img src="{{ media_url($pt->image) }}" alt="{{ $pt->name }}" width="110" height="110" loading="lazy">
                    @else
                        <div class="type-img-placeholder"><i class="bi bi-grid-3x3-gap"></i></div>
                    @endif
                </div>
                <div class="type-name">{{ $pt->name }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif


{{-- ══ COUPON CAROUSEL ══ --}}
@if($homeCoupons->isNotEmpty())
<section class="cq-sec mt-2">
    <div class="container">
        <div class="cq-wrap">
            <button class="cq-arrow cq-prev" onclick="cqSlide(-1)" aria-label="ก่อนหน้า"><i class="bi bi-chevron-left"></i></button>
            <button class="cq-arrow cq-next" onclick="cqSlide(1)"  aria-label="ถัดไป"><i class="bi bi-chevron-right"></i></button>

            <div class="cq-viewport">
                <div class="cq-track" id="cq-track">
                    @foreach($homeCoupons as $i => $coupon)
                    <div class="cq-ticket">
                        {{-- LEFT: image bg or gradient --}}
                        <div class="cq-left"
                             @if($coupon->image) style="background-image:url('{{ media_url($coupon->image) }}');" @endif>
                            @if(!$coupon->image)
                            <div class="cq-left-inner">
                                @if($coupon->type === 'free_shipping')
                                    <i class="bi bi-truck" style="font-size:20px;"></i>
                                    <span class="cq-lbl">ส่งฟรี</span>
                                @elseif($coupon->type === 'percent')
                                    <span class="cq-num">{{ (int)$coupon->value }}%</span>
                                    <span class="cq-lbl">ส่วนลด</span>
                                @else
                                    <span class="cq-num" style="font-size:17px;">฿{{ number_format((float)$coupon->value,0) }}</span>
                                    <span class="cq-lbl">ส่วนลด</span>
                                @endif
                            </div>
                            @endif
                        </div>
                        {{-- notch circles --}}
                        <span class="cq-notch cq-nt"></span>
                        <span class="cq-notch cq-nb"></span>
                        {{-- RIGHT --}}
                        <div class="cq-right">
                            <div class="cq-name">{{ $coupon->name }}</div>
                            @if($coupon->minimum_order > 0)
                            <div class="cq-cond">ซื้อขั้นต่ำ ฿{{ number_format($coupon->minimum_order,0) }}</div>
                            @endif
                            @if(in_array($coupon->id, $collectedIds ?? []))
                            <span class="cq-btn cq-got"><i class="bi bi-check-circle-fill"></i> เก็บแล้ว</span>
                            @elseif(auth('customer')->check())
                            <form method="POST" action="{{ route('coupons.collect', $coupon) }}">
                                @csrf
                                <button type="submit" class="cq-btn">เก็บคูปอง</button>
                            </form>
                            @else
                            <a href="{{ route('login') }}" class="cq-btn">เข้าสู่ระบบ</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
@endif

{{-- ══ FLASH SALE ══ --}}
@if(isset($activeFlashSales) && $activeFlashSales->isNotEmpty())
<section class="flash-sale-section">
    <div class="container">
        <div class="fs-banner" id="fsBanner" data-count="{{ $activeFlashSales->count() }}">
            @foreach($activeFlashSales as $i => $fs)
            @php
                $maxPct = 0;
                foreach($fs->items as $it) {
                    $op = (float) optional($it->product)->price;
                    $sp = (float) $it->sale_price;
                    if ($op > 0) {
                        $pct = (int) round(($op - $sp) / $op * 100);
                        if ($pct > $maxPct) $maxPct = $pct;
                    }
                }
            @endphp
            <a href="{{ route('flash-sales.show', $fs) }}" class="fs-banner-slide {{ $i === 0 ? 'is-active' : '' }}">
                <div class="fs-banner-img">
                    @if($fs->image)
                    <img src="{{ media_url($fs->image) }}" alt="{{ $fs->name }}" loading="lazy">
                    @else
                    <div class="fs-banner-img-ph"><i class="bi bi-lightning-charge-fill"></i></div>
                    @endif
                </div>
                <div class="fs-banner-body">
                    <span class="fs-banner-badge"><i class="bi bi-lightning-charge-fill"></i> Flash Sale</span>
                    <div class="fs-banner-headline">
                        @if($maxPct > 0)ลด {{ $maxPct }} %@else{{ $fs->name }}@endif
                    </div>
                    <div class="fs-banner-timer" data-ends="{{ $fs->ends_at->timestamp }}">
                        <span class="fs-timer-label">สิ้นสุดใน</span>
                        <span class="fs-timer-box" data-d>00</span><span class="fs-timer-unit">วัน</span>
                        <span class="fs-timer-box" data-h>00</span><span class="fs-timer-colon">:</span>
                        <span class="fs-timer-box" data-m>00</span><span class="fs-timer-colon">:</span>
                        <span class="fs-timer-box" data-s>00</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>

<style>
.flash-sale-section{padding:40px 0 32px;}
/* Image bleeds flush on the left (1200x400 / 3:1) + content on red right */
.fs-banner{position:relative;border-radius:22px;overflow:hidden;background:linear-gradient(120deg,#e53935 0%,#f4453f 60%,#ff6a4d 100%);box-shadow:0 10px 34px rgba(229,57,53,.28);}
/* Active slide is in-flow so banner height = the 1200x400 image height (no red bleed); others overlay for the fade */
.fs-banner-slide{position:absolute;inset:0;display:flex;align-items:stretch;text-decoration:none;opacity:0;visibility:hidden;transition:opacity .55s ease;}
.fs-banner-slide.is-active{position:relative;opacity:1;visibility:visible;}
.fs-banner-img{flex:0 0 52%;max-width:52%;aspect-ratio:3/1;overflow:hidden;}
.fs-banner-img img{width:100%;height:100%;object-fit:cover;display:block;}
.fs-banner-img-ph{width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:rgba(255,255,255,.12);}
.fs-banner-img-ph i{font-size:52px;color:#fff;opacity:.85;}
.fs-banner-body{flex:1;display:flex;flex-direction:column;justify-content:center;align-items:flex-start;color:#fff;min-width:0;padding:0 5%;}
.fs-banner-badge{display:inline-flex;align-items:center;gap:7px;background:rgba(255,255,255,.14);border:1px solid rgba(255,255,255,.28);backdrop-filter:blur(4px);color:#fff;font-weight:700;font-size:15px;padding:7px 18px;border-radius:30px;}
.fs-banner-headline{font-size:clamp(28px,3.4vw,44px);font-weight:900;line-height:1.1;margin:16px 0 18px;text-shadow:0 2px 8px rgba(0,0,0,.15);word-break:break-word;}
.fs-banner-timer{display:flex;align-items:center;gap:8px;flex-wrap:wrap;font-size:15px;font-weight:600;}
.fs-timer-label{opacity:.95;margin-right:4px;}
.fs-timer-box{display:inline-flex;align-items:center;justify-content:center;min-width:50px;padding:8px 6px;background:rgba(255,255,255,.18);border-radius:12px;font-size:24px;font-weight:800;line-height:1;}
.fs-timer-unit{margin:0 4px;font-weight:600;}
.fs-timer-colon{font-size:22px;font-weight:800;opacity:.85;}
/* Tablet & below: stack image on top, content below (side-by-side gets too cramped) */
@media(max-width:1024px){
    .flash-sale-section{padding:28px 0 22px;}
    .fs-banner{aspect-ratio:auto;}
    .fs-banner-slide{position:absolute;flex-direction:column;align-items:stretch;}
    .fs-banner-slide:not(.is-active){display:none;}
    .fs-banner-slide.is-active{position:relative;}
    .fs-banner-img{flex:none;max-width:100%;width:100%;aspect-ratio:3/1;}
    .fs-banner-body{padding:16px 18px 18px;}
    .fs-banner-badge{font-size:13px;padding:6px 14px;}
    .fs-banner-headline{font-size:24px;margin:10px 0 12px;}
    .fs-banner-timer{font-size:13px;gap:6px;}
    .fs-timer-box{min-width:36px;font-size:17px;padding:5px 4px;border-radius:10px;}
    .fs-timer-colon{font-size:17px;}
}
</style>

<script>
(function(){
    var banner = document.getElementById('fsBanner');
    if(!banner) return;
    var slides = banner.querySelectorAll('.fs-banner-slide');

    // Countdown timers
    function pad(n){ return n < 10 ? '0'+n : ''+n; }
    function tick(){
        var now = Date.now()/1000;
        banner.querySelectorAll('.fs-banner-timer').forEach(function(t){
            var diff = parseInt(t.dataset.ends,10) - now;
            if(diff < 0) diff = 0;
            var d = Math.floor(diff/86400);
            var h = Math.floor((diff%86400)/3600);
            var m = Math.floor((diff%3600)/60);
            var s = Math.floor(diff%60);
            t.querySelector('[data-d]').textContent = pad(d);
            t.querySelector('[data-h]').textContent = pad(h);
            t.querySelector('[data-m]').textContent = pad(m);
            t.querySelector('[data-s]').textContent = pad(s);
        });
    }
    tick();
    setInterval(tick, 1000);

    // Auto slideshow (only if more than one)
    if(slides.length > 1){
        var idx = 0, timer = null;
        var DELAY = 6500;

        function go(i){
            slides[idx].classList.remove('is-active');
            idx = (i + slides.length) % slides.length;
            slides[idx].classList.add('is-active');
        }
        function start(){ if(!timer) timer = setInterval(function(){ go(idx + 1); }, DELAY); }
        function stop(){ if(timer){ clearInterval(timer); timer = null; } }

        // Pause when the pointer is over the banner
        banner.addEventListener('mouseenter', stop);
        banner.addEventListener('mouseleave', start);

        // Drag / swipe to switch
        var downX = null, dragged = false;
        banner.addEventListener('pointerdown', function(e){
            downX = e.clientX; dragged = false; stop();
        });
        banner.addEventListener('pointermove', function(e){
            if(downX !== null && Math.abs(e.clientX - downX) > 8) dragged = true;
        });
        banner.addEventListener('pointerup', function(e){
            if(downX !== null){
                var dx = e.clientX - downX;
                if(Math.abs(dx) > 40) go(dx < 0 ? idx + 1 : idx - 1);
            }
            downX = null;
            start();
        });
        banner.addEventListener('pointercancel', function(){ downX = null; start(); });
        // Block link navigation when the user was dragging
        banner.addEventListener('click', function(e){
            if(dragged){ e.preventDefault(); dragged = false; }
        }, true);
        banner.addEventListener('dragstart', function(e){ e.preventDefault(); });

        start();
    }
})();
</script>
@endif

{{-- ══ CATEGORIES ══ --}}
@if($categories->isNotEmpty())
<section class="cat-section">
    <div class="container">
        <div style="text-align:center;margin-bottom:24px;">
            <div class="section-title">หมวดหมู่สินค้า</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div class="cat-grid">
            @foreach($categories as $cat)
            <a href="{{ route('shop.category', $cat->slug) }}" class="cat-card">
                <div class="cat-img-wrap">
                    <img src="{{ media_url($cat->image) }}" alt="{{ $cat->name }}" width="150" height="150" loading="lazy">
                </div>
                <div class="cat-name">{{ $cat->name }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══ PRODUCTS ══ --}}
@if($featuredProducts->isNotEmpty() || $bestsellerProducts->isNotEmpty() || $newProducts->isNotEmpty())
<section class="pt-5 pb-0" style="background:white;">
    <div class="container">
        <div style="text-align:center;margin-bottom:28px;">
            <div class="section-title">สินค้าแนะนำและยอดนิยม</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>

        <div class="product-tabs">
            @if($featuredProducts->isNotEmpty())
            <button class="product-tab active" onclick="switchTab('featured',this)"><i class="bi bi-star-fill"></i> สินค้าแนะนำ</button>
            @endif
            @if($bestsellerProducts->isNotEmpty())
            <button class="product-tab {{ $featuredProducts->isEmpty()?'active':'' }}" onclick="switchTab('bestseller',this)"><i class="bi bi-fire"></i> ขายดี</button>
            @endif
            @if($newProducts->isNotEmpty())
            <button class="product-tab" onclick="switchTab('new',this)"><i class="bi bi-lightning-charge-fill"></i> สินค้าใหม่</button>
            @endif
        </div>

        <div id="tab-featured"><div class="grid grid-5" style="gap:14px;">@foreach($featuredProducts as $product)@include('components.product-card')@endforeach</div></div>
        <div id="tab-bestseller" style="display:none;"><div class="grid grid-5" style="gap:14px;">@foreach($bestsellerProducts as $product)@include('components.product-card')@endforeach</div></div>
        <div id="tab-new"        style="display:none;"><div class="grid grid-5" style="gap:14px;">@foreach($newProducts as $product)@include('components.product-card')@endforeach</div></div>

        <div style="text-align:center;margin-top:24px;">
            <a href="{{ route('shop') }}" class="btn btn-outline"><i class="bi bi-grid-3x3-gap"></i> ดูสินค้าทั้งหมด</a>
        </div>
    </div>
</section>
@endif

{{-- ══ PRODUCT SHOWCASE SLIDER ══ --}}
@if($saleProducts->isNotEmpty())
<section class="product-showcase-section">
    <div class="container">
        <div style="text-align:center;margin-bottom:24px;">
            <div class="section-title">โปรโมชั่นประจำเดือนนี้</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div class="row justify-content-center">
            <div class="col-12 col-lg-12 col-xl-10">
        <div class="showcase-wrapper" x-data="productShowcase({{ json_encode($saleProducts->map(fn($p) => [
            'id' => $p->id,
            'name' => $p->name,
            'slug' => $p->slug,
            'price' => $p->sale_price ?? $p->price,
            'originalPrice' => $p->price,
            'discount' => $p->discount_percent,
            'image' => media_url($p->main_image),
            'sizes' => $p->variants->pluck('size')->filter()->unique()->values(),
            'stock' => $p->stock_quantity,
            'description' => $p->short_description ?? '',
            'isNew' => $p->is_new,
            'isSale' => $p->sale_price && $p->sale_price < $p->price,
            'category' => $p->category->name ?? '',
        ])) }})">

            {{-- Left: Vertical Thumbnail List (Different Products) --}}
            <div class="showcase-thumbnails">
                <template x-for="(product, index) in products" :key="product.id">
                    <div class="showcase-thumb"
                         :class="{ 'active': currentIndex === index }"
                         @click="selectProduct(index)">
                        <img :src="product.image" :alt="product.name">
                    </div>
                </template>
            </div>

            {{-- Center: Main Product Image --}}
            <div class="showcase-main-image">
                <div class="showcase-image-container">
                    <img :src="currentProduct.image"
                         :alt="currentProduct.name"
                         class="showcase-img">

                    {{-- Badges --}}
                    <div class="showcase-badges">
                        <span x-show="currentProduct.isNew" class="badge badge-new">New</span>
                        <span x-show="currentProduct.isSale && currentProduct.discount > 0"
                              class="badge badge-sale" x-text="'-' + currentProduct.discount + '%'"></span>
                    </div>
                </div>
            </div>

            {{-- Right: Product Details --}}
            <div class="showcase-details">
                <h2 class="showcase-title" x-text="currentProduct.name"></h2>

                <div class="showcase-category" x-show="currentProduct.category">
                    <span class="category-name" x-text="currentProduct.category"></span>
                </div>

                <div class="showcase-price">
                    <span class="price-current" x-text="'฿' + currentProduct.price.toLocaleString()"></span>
                    <template x-if="currentProduct.originalPrice > currentProduct.price">
                        <span class="price-old" x-text="'฿' + currentProduct.originalPrice.toLocaleString()"></span>
                    </template>
                </div>

                <p class="showcase-description" x-text="currentProduct.description"></p>

                <div class="showcase-divider"></div>

                {{-- Buy Button --}}
                <a :href="`/shop/${currentProduct.slug}`" class="btn btn-primary w-100" style="padding: 16px; font-size: 16px;">
                    <i class="bi bi-cart-plus"></i> ซื้อเลย
                </a>
            </div>
        </div>
            </div>
        </div>
        <div style="text-align:center;margin-top:24px;">
            <a href="{{ route('promotions') }}" class="btn btn-outline"><i class="bi bi-grid-3x3-gap"></i> ดูสินค้าโปรโมชั่นทั้งหมด</a>
        </div>
    </div>
</section>

<style>
.product-showcase-section { padding: 60px 0; background: #fff; }
.showcase-wrapper { display: grid; grid-template-columns: 200px 1fr 400px; gap: 24px; align-items: start; }

/* Thumbnails */
.showcase-thumbnails { display: flex; flex-direction: column; gap: 16px; max-height: 490px; overflow-y: auto; scrollbar-width: none; -ms-overflow-style: none; }
.showcase-thumbnails::-webkit-scrollbar { display: none; }
.showcase-thumb { width: 200px; height: 200px; border-radius: 16px; overflow: hidden; cursor: pointer; border: 3px solid #e5e7eb; transition: border-color 0.2s; }
.showcase-thumb:hover { border-color: var(--kgm-green-500); }
.showcase-thumb.active { border-color: var(--kgm-green-600); box-shadow: 0 0 0 4px rgba(45,106,79,0.1); }
.showcase-thumb img { width: 100%; height: 100%; object-fit: contain; background: #f9fafb; }

/* Main Image */
.showcase-main-image { position: sticky; top: 20px; }
.showcase-image-container { position: relative; aspect-ratio: 1; background: linear-gradient(135deg, #f0f9f4, #fff); border-radius: 20px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
.showcase-img { width: 100%; height: 100%; object-fit: contain; padding: 4px; }
.showcase-image-dots { position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%); display: flex; gap: 8px; }
.showcase-dot { width: 8px; height: 8px; border-radius: 50%; border: none; background: rgba(255,255,255,0.6); cursor: pointer; transition: all 0.2s; padding: 0; }
.showcase-dot:hover { background: rgba(255,255,255,0.9); }
.showcase-dot.active { background: var(--kgm-green-600); width: 24px; border-radius: 4px; }
.showcase-badges { position: absolute; top: 16px; right: 16px; display: flex; flex-direction: column; gap: 8px; }

/* Details */
.showcase-details { background: #fff; border-radius: 20px; padding: 32px; box-shadow: 0 4px 24px rgba(0,0,0,0.08); }
.showcase-title { font-size: 24px; font-weight: 700; color: #1f2937; margin: 0 0 12px; line-height: 1.3; }
.showcase-category { display: flex; align-items: center; gap: 8px; margin-bottom: 16px; }
.category-label { font-size: 14px; font-weight: 600; color: #6b7280; }
.category-name { font-size: 14px; font-weight: 600; color: var(--kgm-green-600); background: var(--kgm-green-50); padding: 4px 12px; border-radius: 6px; }
.showcase-price { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.price-current { font-size: 32px; font-weight: 800; color: var(--kgm-green-700); }
.price-old { font-size: 18px; color: #9ca3af; text-decoration: line-through; }
.showcase-description { font-size: 14px; color: #6b7280; line-height: 1.6; margin-bottom: 20px; }
.showcase-divider { height: 1px; background: #e5e7eb; margin: 20px 0; }
.showcase-label { font-size: 14px; font-weight: 600; color: #374151; margin-bottom: 12px; display: block; }
.text-danger { color: #ef4444; }

/* Sizes */
.showcase-size-section { margin-bottom: 20px; position: relative; }
.custom-select { position: relative; width: 100%; cursor: pointer; }
.select-trigger { display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 12px 16px; border: 2px solid #e5e7eb; border-radius: 8px; background: #fff; font-size: 14px; font-weight: 600; color: #374151; transition: all 0.2s; }
.select-trigger:hover { border-color: var(--kgm-green-500); }
.text-placeholder { color: #9ca3af; }
.select-arrow { width: 20px; height: 20px; transition: transform 0.2s; flex-shrink: 0; }
.rotate-180 { transform: rotate(180deg); }
.select-dropdown { position: absolute; top: calc(100% + 4px); left: 0; right: 0; background: #fff; border: 2px solid #e5e7eb; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); max-height: 250px; overflow-y: auto; z-index: 50; }
.select-option { padding: 12px 16px; font-size: 14px; font-weight: 600; color: #374151; cursor: pointer; transition: background 0.15s; }
.select-option:hover { background: #f0f9f4; }
.select-option.selected { background: var(--kgm-green-50); color: var(--kgm-green-700); }

/* Quantity */
.showcase-quantity { display: flex; align-items: center; gap: 16px; margin-bottom: 20px; flex-wrap: wrap; }
.qty-controls { display: flex; align-items: center; border: 2px solid #e5e7eb; border-radius: 8px; overflow: hidden; }
.qty-btn { width: 40px; height: 40px; border: none; background: #f9fafb; font-size: 18px; font-weight: 700; cursor: pointer; transition: background 0.2s; }
.qty-btn:hover:not(:disabled) { background: #e5e7eb; }
.qty-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.qty-input { width: 60px; height: 40px; border: none; text-align: center; font-size: 16px; font-weight: 600; }
.stock-info { font-size: 13px; color: #6b7280; }

/* Total */
.showcase-total { display: flex; justify-content: space-between; align-items: center; padding: 16px; background: #f0f9f4; border-radius: 12px; margin-bottom: 20px; }
.showcase-total span { font-size: 14px; color: #374151; }
.showcase-total strong { font-size: 24px; color: var(--kgm-green-700); }

/* Actions */
.showcase-actions { display: flex; flex-direction: column; gap: 12px; }

/* Responsive */
@media (max-width: 1024px) {
    .showcase-wrapper { grid-template-columns: 160px 1fr 350px; gap: 16px; }
    .showcase-thumb { width: 160px; height: 160px; }
    .showcase-details { padding: 24px; }
    .showcase-title { font-size: 20px; }
    .price-current { font-size: 28px; }
}

@media (max-width: 768px) {
    .product-showcase-section { padding: 40px 0; }
    .showcase-wrapper { grid-template-columns: 1fr; gap: 20px; }
    .showcase-thumbnails { flex-direction: row; max-height: none; overflow-x: scroll; overflow-y: hidden; padding-bottom: 8px; scrollbar-width: none; -ms-overflow-style: none; }
    .showcase-thumbnails::-webkit-scrollbar { display: none; }
    .showcase-thumb { width: 120px; height: 120px; flex-shrink: 0; }
    .showcase-main-image { position: static; }
    .showcase-details { padding: 20px; }
}
</style>

<script>
function productShowcase(productsData) {
    return {
        products: productsData,
        currentIndex: 0,
        selectedSize: null,
        quantity: 1,
        dropdownOpen: false,
        get currentProduct() {
            return this.products[this.currentIndex] || this.products[0];
        },
        selectProduct(index) {
            this.currentIndex = index;
            this.selectedSize = null;
            this.quantity = 1;
            this.dropdownOpen = false;
        },
        selectSize(size) {
            this.selectedSize = size;
            this.dropdownOpen = false;
        }
    };
}
</script>
@endif

{{-- ══ ABOUT + SERVICE HIGHLIGHTS ══ --}}
<img src="/images/cartoon.jpg" alt="KGM" class="w-100" loading="lazy">
<section style="background:#E1CD94;" class="pt-0 pb-3">
    <div class="container">
        <div class="grid" style="gap:24px;align-items:stretch;">
            <div class="row justify-content-center g-0">
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12  mb-3">
                            <div class="card">
                               <div class="card-body text-center">
                                     <h2 style="font-size:19px;color:#185c2e;font-weight:800;margin:0 0 12px;">บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด</h2>
                                    <p style="font-size:14px;color:#555;line-height:1.95;margin:0;text-indent:2em;">
                                        เราคือผู้ผลิตและจำหน่ายเครื่องแบบนักเรียนมากกว่า 40 ปี เรามุ่งมั่นสร้างสรรค์ชุดเครื่องแบบเพื่อคนไทยและเด็กนักเรียนทั่วประเทศ
                                        โดยคำนึงถึงคุณภาพและความคุ้มค่ามาเป็นที่หนึ่ง ใส่ใจในทุกรายละเอียด และพิถีพิถันทุกขั้นตอนการผลิตอย่างที่สุด
                                    </p>
                                    <a href="{{ route('about') }}" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px;margin-top:20px;font-size:13px;font-weight:700;text-decoration:none;">
                                        อ่านเพิ่มเติมเกี่ยวกับเรา <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ PROMO BANNERS ══ --}}
<section class="py-4">
    <div class="container">
        <div class="row g-3">
            <div class="col-12 col-sm-6">
                <a href="{{ route('careers') }}" class="promo-banner green text-decoration-none">
                    <div class="promo-banner-img-wrap">
                        <img src="{{ asset('images/job/job1.jpg') }}" alt="สมัคร PC" class="promo-banner-img" width="900" height="500" loading="lazy">
                    </div>
                    <div class="promo-banner-body">
                        <div class="promo-tag">รับสมัคร PC</div>
                        <div class="promo-title">ปิดรับสมัคร PC<br>ชุดนักเรียน</div>
                        <span class="btn btn-gold btn-sm align-self-start"><i class="bi bi-file-earmark-text"></i> ดูตำแหน่งว่าง</span>
                    </div>
                </a>
            </div>
            <div class="col-12 col-sm-6">
                <a href="{{ route('dealer') }}" class="promo-banner gold text-decoration-none">
                    <div class="promo-banner-img-wrap">
                        <img src="{{ asset('images/job/job2.jpg') }}" alt="ตัวแทนจำหน่าย" class="promo-banner-img" width="900" height="500" loading="lazy">
                    </div>
                    <div class="promo-banner-body">
                        <div class="promo-tag">ตัวแทนจำหน่าย</div>
                        <div class="promo-title">สนใจเป็นตัวแทน<br>จำหน่าย KGM</div>
                        <span class="btn btn-gold btn-sm align-self-start"><i class="bi bi-shop"></i> สมัครตัวแทน</span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══ SERVICES ══ --}}
<section class="section" style="background:#f8faf8;">
    <div class="container">
        <div style="text-align:center;margin-bottom:28px;">
            <div class="section-subtitle" style="text-align:center;">สิ่งที่เราทำ</div>
            <div class="section-title">บริการของเรา</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        @php
        $services = [
            ['service1.jpg','บริการออกแบบและสั่งผลิต','บริการงานปักและสกรีน เรามีจักรปักคุณภาพมาตรฐานสากล มีทั้งประเภท 20 หัวปัก 4 หัวปัก และ 1 หัวปัก พร้อมด้วยทีมงานออกแบบลายปัก ที่มีประสบการณ์มากว่า 40 ปี รองรับงานปักจำนวนมาก ด้วยไหมปักที่มีคุณภาพ', 'order'],
            ['service2.jpg','เครื่องแบบนักเรียนและองค์กร','ออกแบบและผลิตชุดนักเรียนและชุดยูนิฟอร์มองค์กรตามความต้องการของลูกค้า เราผลิตชุดนักเรียนครบชุดทุกระดับชั้น ชาย-หญิง พร้อมชุดพลศึกษา ชุดเครื่องแบบลูกเสือ-เนตรนารี และยูนิฟอร์มสำหรับบริษัทและองค์กรทุกประเภท', 'school-uniforms'],
            ['service3.jpg','บริการงานปักและสกรีน','บริการออกแบบและสั่งผลิต เสื้อยูนิฟอร์ม เสื้อหน่วยงาน เครื่องแบบนักเรียน เสื้อโปโล เสื้อแจ็คเก็ต เสื้อยืดคอกลม ชุดพนักงานราชการ ชุดคนไข้โรงพยาบาล ชุดกีฬา ถุงผ้า เรามีพนักงานที่มีความสามารถและมีประสบการณ์ กว่า 40 ปี', 'embroidery-screen'],
        ];
        @endphp

        {{-- Desktop (≥768px): 3 columns --}}
        <div class="grid grid-3 svc-desktop" style="gap:14px;">
            @foreach($services as [$img,$title,$desc,$route])
            <a href="{{ route($route) }}" style="text-decoration:none;display:block;">
                <div class="card" style="text-align:center;padding:0;overflow:hidden;transition:transform 0.2s,box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.12)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
                    <img src="{{ asset('images/service/'.$img) }}" alt="{{ $title }}" loading="lazy" style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:12px 12px 0 0;display:block;">
                    <div style="padding:18px 16px;">
                        <h3 style="font-size:15px;font-weight:700;color:var(--kgm-green-800);margin:0 0 8px;">{{ $title }}</h3>
                        <p style="font-size:13px;color:#666;line-height:1.7;margin:0;">{{ $desc }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        {{-- Mobile (<768px): slideshow --}}
        <div class="svc-mobile" style="position:relative;padding:0 20px;">
            <div id="svc-track-wrap" style="overflow:hidden;border-radius:16px;width:100%;">
                <div id="svc-track" style="display:flex;width:100%;transition:transform 0.35s ease;">
                    @foreach($services as $i => [$img,$title,$desc,$route])
                    <div style="flex:0 0 100%;width:100%;box-sizing:border-box;">
                        <a href="{{ route($route) }}" style="text-decoration:none;display:block;">
                            <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                                <img src="{{ asset('images/service/'.$img) }}" alt="{{ $title }}" loading="lazy" style="width:100%;aspect-ratio:4/3;object-fit:cover;display:block;">
                                <div style="padding:16px;text-align:center;">
                                    <h3 style="font-size:15px;font-weight:700;color:var(--kgm-green-800);margin:0 0 8px;">{{ $title }}</h3>
                                    <p style="font-size:13px;color:#666;line-height:1.7;margin:0;">{{ $desc }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
            <button id="svc-prev" onclick="svcGo(-1)" style="position:absolute;top:35%;left:0;transform:translateY(-50%);width:32px;height:32px;border-radius:50%;background:white;border:none;box-shadow:0 2px 8px rgba(0,0,0,0.18);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--kgm-green-700);font-size:13px;z-index:2;">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button id="svc-next" onclick="svcGo(1)" style="position:absolute;top:35%;right:0;transform:translateY(-50%);width:32px;height:32px;border-radius:50%;background:white;border:none;box-shadow:0 2px 8px rgba(0,0,0,0.18);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--kgm-green-700);font-size:13px;z-index:2;">
                <i class="bi bi-chevron-right"></i>
            </button>
            <div style="display:flex;justify-content:center;gap:6px;margin-top:14px;">
                @foreach($services as $i => [$img,$title,$desc,$route])
                <button onclick="svcSet({{ $i }})" id="svc-dot-{{ $i }}" style="width:8px;height:8px;border-radius:50%;border:none;cursor:pointer;padding:0;transition:all 0.2s;background:{{ $i===0 ? 'var(--kgm-green-600)' : '#ccc' }};{{ $i===0 ? 'transform:scale(1.3);' : '' }}"></button>
                @endforeach
            </div>
        </div>

        <style>
            @media(min-width:768px){ .svc-mobile{display:none!important;} }
            @media(max-width:767px){ .svc-desktop{display:none!important;} }
        </style>
        <script>
            var svcIdx=0,svcTotal=3;
            function svcSet(n){
                svcIdx=n;
                var tw=document.getElementById('svc-track-wrap').offsetWidth;
                var track=document.getElementById('svc-track');
                track.style.transition='transform 0.35s ease';
                track.style.transform='translateX(-'+(svcIdx*tw)+'px)';
                document.getElementById('svc-prev').style.opacity=svcIdx===0?'0.3':'1';
                document.getElementById('svc-next').style.opacity=svcIdx===svcTotal-1?'0.3':'1';
                for(var i=0;i<svcTotal;i++){
                    var d=document.getElementById('svc-dot-'+i);
                    d.style.background=i===svcIdx?'var(--kgm-green-600)':'#ccc';
                    d.style.transform=i===svcIdx?'scale(1.3)':'scale(1)';
                }
            }
            function svcGo(dir){ svcSet(Math.max(0,Math.min(svcTotal-1,svcIdx+dir))); }
            svcSet(0);

            (function(){
                var wrap=document.getElementById('svc-track-wrap');
                var track=document.getElementById('svc-track');
                var startX=0,isDragging=false,dragX=0,threshold=50;

                function onStart(x){
                    startX=x; isDragging=true;
                    track.style.transition='none';
                }
                function onMove(x){
                    if(!isDragging) return;
                    dragX=x-startX;
                    var tw=wrap.offsetWidth;
                    track.style.transform='translateX('+(-svcIdx*tw+dragX)+'px)';
                }
                function onEnd(){
                    if(!isDragging) return;
                    isDragging=false;
                    if(dragX<-threshold) svcGo(1);
                    else if(dragX>threshold) svcGo(-1);
                    else svcSet(svcIdx);
                    dragX=0;
                }

                /* touch */
                wrap.addEventListener('touchstart',function(e){ onStart(e.touches[0].clientX); },{passive:true});
                wrap.addEventListener('touchmove',function(e){ onMove(e.touches[0].clientX); },{passive:true});
                wrap.addEventListener('touchend',onEnd);

                /* mouse */
                wrap.addEventListener('mousedown',function(e){ onStart(e.clientX); e.preventDefault(); });
                window.addEventListener('mousemove',function(e){ onMove(e.clientX); });
                window.addEventListener('mouseup',onEnd);

                /* ป้องกัน link ถูก trigger เวลา drag */
                wrap.addEventListener('click',function(e){ if(Math.abs(dragX)>5) e.preventDefault(); },{capture:true});
            })();
        </script>
        <div style="text-align:center;margin-top:22px;">
            <a href="{{ route('services') }}" class="btn btn-outline"><i class="bi bi-arrow-right-circle"></i> ดูบริการทั้งหมด</a>
        </div>
    </div>
</section>

{{-- ══ TESTIMONIALS ══ --}}
@if($testimonials->isNotEmpty())
<section class="section" style="background:var(--kgm-green-800);">
    <div class="container">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="color:var(--kgm-gold-300);font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:2px;margin-bottom:6px;">ความคิดเห็น</div>
            <div style="font-size:clamp(20px,4vw,28px);font-weight:700;color:white;">เสียงจากลูกค้าของเรา</div>
        </div>
        <div class="testimonials-grid">
            @foreach($testimonials as $review)
            <div style="background:rgba(255,255,255,0.08);border-radius:16px;padding:20px;">
                <div style="color:var(--kgm-gold-300);font-size:15px;margin-bottom:10px;">
                    @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$review->rating?'-fill':'' }}"></i>@endfor
                </div>
                <p style="color:rgba(255,255,255,0.85);font-size:14px;line-height:1.8;margin:0 0 14px;">"{{ $review->body }}"</p>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:34px;height:34px;background:var(--kgm-gold-500);border-radius:999px;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--kgm-green-900);font-size:15px;flex-shrink:0;"><i class="bi bi-person-fill"></i></div>
                    <div>
                        <div style="color:white;font-weight:700;font-size:13px;">{{ $review->customer->name }}</div>
                        <div style="color:rgba(255,255,255,0.45);font-size:11px;">{{ $review->product->name }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══ VIDEOS ══ --}}
@if(isset($featuredVideos) && $featuredVideos->isNotEmpty())
@php $videosSlice = $featuredVideos->take(3); $vidsTotal = $videosSlice->count(); @endphp
<section class="section" style="background:#f8faf8;">
    <div class="container">
        <div style="text-align:center;margin-bottom:28px;">
            <div class="section-subtitle" style="text-align:center;">YouTube</div>
            <div class="section-title">เรื่องราวที่น่าสนใจ</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>

        {{-- Desktop (≥768px): 3 columns --}}
        <div class="vids-desktop" style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
            @foreach($videosSlice as $video)
            <div style="background:white;border-radius:14px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.07);cursor:pointer;transition:transform .2s,box-shadow .2s;"
                 onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 28px rgba(0,0,0,.13)'"
                 onmouseleave="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.07)'"
                 onclick="homeOpenVideo('{{ $video->youtube_id }}')">
                <div style="position:relative;padding-bottom:56.25%;background:#111;overflow:hidden;">
                    <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" width="480" height="270" loading="lazy"
                         style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;"
                         onerror="this.src='/images/logo.png'">
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.25);">
                        <div style="width:52px;height:52px;background:rgba(255,255,255,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;padding-left:4px;box-shadow:0 4px 16px rgba(0,0,0,.3);">
                            <i class="bi bi-play-fill" style="font-size:22px;color:#e30000;"></i>
                        </div>
                    </div>
                </div>
                <div style="padding:14px;">
                    <div style="font-size:14px;font-weight:700;color:#1a2e1a;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;margin-bottom:6px;line-height:1.4;">{{ $video->title }}</div>
                    @if($video->description)
                    <div style="font-size:12px;color:#888;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $video->description }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Mobile (<768px): slideshow --}}
        <div class="vids-mobile" style="position:relative;padding:0 20px;">
            <div id="vids-track-wrap" style="overflow:hidden;border-radius:16px;width:100%;">
                <div id="vids-track" style="display:flex;width:100%;transition:transform 0.35s ease;">
                    @foreach($videosSlice as $video)
                    <div style="flex:0 0 100%;width:100%;box-sizing:border-box;">
                        <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);cursor:pointer;"
                             onclick="homeOpenVideo('{{ $video->youtube_id }}')">
                            <div style="position:relative;padding-bottom:56.25%;background:#111;overflow:hidden;">
                                <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" loading="lazy"
                                     style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;"
                                     onerror="this.src='/images/logo.png'">
                                <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.25);">
                                    <div style="width:56px;height:56px;background:rgba(255,255,255,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;padding-left:4px;box-shadow:0 4px 16px rgba(0,0,0,.3);">
                                        <i class="bi bi-play-fill" style="font-size:24px;color:#e30000;"></i>
                                    </div>
                                </div>
                            </div>
                            <div style="padding:16px;text-align:center;">
                                <div style="font-size:15px;font-weight:700;color:#1a2e1a;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;margin-bottom:6px;line-height:1.4;">{{ $video->title }}</div>
                                @if($video->description)
                                <div style="font-size:13px;color:#888;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $video->description }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <button id="vids-prev" onclick="vidsGo(-1)" style="position:absolute;top:30%;left:0;transform:translateY(-50%);width:32px;height:32px;border-radius:50%;background:white;border:none;box-shadow:0 2px 8px rgba(0,0,0,0.18);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--kgm-green-700);font-size:13px;z-index:2;opacity:0.3;">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button id="vids-next" onclick="vidsGo(1)" style="position:absolute;top:30%;right:0;transform:translateY(-50%);width:32px;height:32px;border-radius:50%;background:white;border:none;box-shadow:0 2px 8px rgba(0,0,0,0.18);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--kgm-green-700);font-size:13px;z-index:2;">
                <i class="bi bi-chevron-right"></i>
            </button>
            <div style="display:flex;justify-content:center;gap:6px;margin-top:14px;">
                @foreach($videosSlice as $video)
                <button onclick="vidsSet({{ $loop->index }})" id="vids-dot-{{ $loop->index }}" style="width:8px;height:8px;border-radius:50%;border:none;cursor:pointer;padding:0;transition:all 0.2s;background:{{ $loop->first ? 'var(--kgm-green-600)' : '#ccc' }};{{ $loop->first ? 'transform:scale(1.3);' : '' }}"></button>
                @endforeach
            </div>
        </div>

        <style>
            @media(min-width:768px){ .vids-mobile{display:none!important;} }
            @media(max-width:767px){ .vids-desktop{display:none!important;} }
        </style>
        <script>
            var vidsIdx=0,vidsTotal={{ $vidsTotal }};
            function vidsSet(n){
                vidsIdx=n;
                var tw=document.getElementById('vids-track-wrap').offsetWidth;
                var track=document.getElementById('vids-track');
                track.style.transition='transform 0.35s ease';
                track.style.transform='translateX(-'+(vidsIdx*tw)+'px)';
                document.getElementById('vids-prev').style.opacity=vidsIdx===0?'0.3':'1';
                document.getElementById('vids-next').style.opacity=vidsIdx===vidsTotal-1?'0.3':'1';
                for(var i=0;i<vidsTotal;i++){
                    var d=document.getElementById('vids-dot-'+i);
                    if(!d) continue;
                    d.style.background=i===vidsIdx?'var(--kgm-green-600)':'#ccc';
                    d.style.transform=i===vidsIdx?'scale(1.3)':'scale(1)';
                }
            }
            function vidsGo(dir){ vidsSet(Math.max(0,Math.min(vidsTotal-1,vidsIdx+dir))); }
            vidsSet(0);
            (function(){
                var wrap=document.getElementById('vids-track-wrap');
                var track=document.getElementById('vids-track');
                var startX=0,isDragging=false,dragX=0,threshold=50;
                function onStart(x){ startX=x; isDragging=true; track.style.transition='none'; }
                function onMove(x){ if(!isDragging) return; dragX=x-startX; var tw=wrap.offsetWidth; track.style.transform='translateX('+(-vidsIdx*tw+dragX)+'px)'; }
                function onEnd(){ if(!isDragging) return; isDragging=false; if(dragX<-threshold) vidsGo(1); else if(dragX>threshold) vidsGo(-1); else vidsSet(vidsIdx); dragX=0; }
                wrap.addEventListener('touchstart',function(e){ onStart(e.touches[0].clientX); },{passive:true});
                wrap.addEventListener('touchmove',function(e){ onMove(e.touches[0].clientX); },{passive:true});
                wrap.addEventListener('touchend',onEnd);
                wrap.addEventListener('mousedown',function(e){ onStart(e.clientX); e.preventDefault(); });
                window.addEventListener('mousemove',function(e){ onMove(e.clientX); });
                window.addEventListener('mouseup',onEnd);
                wrap.addEventListener('click',function(e){ if(Math.abs(dragX)>5) e.preventDefault(); },{capture:true});
            })();
        </script>
        <div style="text-align:center;margin-top:22px;">
            <a href="{{ route('videos') }}" class="btn btn-outline"><i class="bi bi-play-circle"></i> ดูวิดีโอทั้งหมด</a>
        </div>
    </div>
</section>

{{-- Home Video Modal --}}
<div id="home-yt-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.85);align-items:center;justify-content:center;" onclick="homeCloseVideo(event)">
    <div style="width:90%;max-width:860px;background:#000;border-radius:12px;overflow:hidden;position:relative;">
        <button onclick="homeCloseVideo(null,true)" style="position:absolute;top:-40px;right:0;background:none;border:none;color:white;font-size:30px;cursor:pointer;line-height:1;">&times;</button>
        <div style="position:relative;padding-bottom:56.25%;height:0;">
            <iframe id="home-yt-iframe" src="" frameborder="0"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;"
                    allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    </div>
</div>
@push('scripts')
<script>
function homeOpenVideo(id) {
    document.getElementById('home-yt-iframe').src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0';
    const m = document.getElementById('home-yt-modal');
    m.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function homeCloseVideo(e, force) {
    if (force || e.target === document.getElementById('home-yt-modal')) {
        document.getElementById('home-yt-iframe').src = '';
        document.getElementById('home-yt-modal').style.display = 'none';
        document.body.style.overflow = '';
    }
}
document.addEventListener('keydown', function(e) { if(e.key==='Escape') homeCloseVideo(null,true); });
</script>
@endpush
@endif

{{-- ══ LATEST NEWS ══ --}}
@if($latestPosts->isNotEmpty())
@php $newsTotal = $latestPosts->count(); @endphp
<section class="section">
    <div class="container">
        <div style="text-align:center;margin-bottom:28px;">
            <div class="section-subtitle" style="text-align:center;">บทความและข่าวสาร</div>
            <div class="section-title">ข่าวสารล่าสุด</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>

        {{-- Desktop (≥768px): 3 columns --}}
        <div class="news-desktop">
            <div class="grid grid-3" style="gap:14px;">
                @foreach($latestPosts as $post)
                <div class="card">
                    @if($post->featured_image)
                    <div style="height:165px;overflow:hidden;">
                        <img src="{{ media_url($post->featured_image) }}" alt="{{ $post->title }}" width="600" height="165" loading="lazy" style="width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.src='/images/logo.png'">
                    </div>
                    @endif
                    <div class="card-body">
                        <div style="font-size:11px;color:var(--kgm-green-500);font-weight:700;text-transform:uppercase;margin-bottom:5px;">{{ $post->postCategory?->name }}</div>
                        <h3 style="font-size:14px;font-weight:700;margin:0 0 7px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $post->title }}</h3>
                        <p style="font-size:13px;color:#666;margin:0 0 10px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $post->excerpt }}</p>
                        <a href="{{ route('news.show', $post->slug) }}" style="font-size:13px;font-weight:700;color:var(--kgm-green-600);">อ่านต่อ <i class="bi bi-arrow-right"></i></a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Mobile (<768px): slideshow --}}
        <div class="news-mobile" style="position:relative;padding:0 20px;">
            <div id="news-track-wrap" style="overflow:hidden;border-radius:16px;width:100%;">
                <div id="news-track" style="display:flex;width:100%;transition:transform 0.35s ease;">
                    @foreach($latestPosts as $post)
                    <div style="flex:0 0 100%;width:100%;box-sizing:border-box;">
                        <div class="card" style="border-radius:16px;overflow:hidden;box-shadow:0 4px 16px rgba(0,0,0,0.08);">
                            @if($post->featured_image)
                            <div style="height:200px;overflow:hidden;">
                                <img src="{{ media_url($post->featured_image) }}" alt="{{ $post->title }}" width="600" height="200" loading="lazy" style="width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.src='/images/logo.png'">
                            </div>
                            @endif
                            <div class="card-body">
                                <div style="font-size:11px;color:var(--kgm-green-500);font-weight:700;text-transform:uppercase;margin-bottom:5px;">{{ $post->postCategory?->name }}</div>
                                <h3 style="font-size:15px;font-weight:700;margin:0 0 8px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $post->title }}</h3>
                                <p style="font-size:13px;color:#666;margin:0 0 12px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;">{{ $post->excerpt }}</p>
                                <a href="{{ route('news.show', $post->slug) }}" style="font-size:13px;font-weight:700;color:var(--kgm-green-600);">อ่านต่อ <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            <button id="news-prev" onclick="newsGo(-1)" style="position:absolute;top:40%;left:0;transform:translateY(-50%);width:32px;height:32px;border-radius:50%;background:white;border:none;box-shadow:0 2px 8px rgba(0,0,0,0.18);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--kgm-green-700);font-size:13px;z-index:2;opacity:0.3;">
                <i class="bi bi-chevron-left"></i>
            </button>
            <button id="news-next" onclick="newsGo(1)" style="position:absolute;top:40%;right:0;transform:translateY(-50%);width:32px;height:32px;border-radius:50%;background:white;border:none;box-shadow:0 2px 8px rgba(0,0,0,0.18);display:flex;align-items:center;justify-content:center;cursor:pointer;color:var(--kgm-green-700);font-size:13px;z-index:2;">
                <i class="bi bi-chevron-right"></i>
            </button>
            <div style="display:flex;justify-content:center;gap:6px;margin-top:14px;">
                @foreach($latestPosts as $post)
                <button onclick="newsSet({{ $loop->index }})" id="news-dot-{{ $loop->index }}" style="width:8px;height:8px;border-radius:50%;border:none;cursor:pointer;padding:0;transition:all 0.2s;background:{{ $loop->first ? 'var(--kgm-green-600)' : '#ccc' }};{{ $loop->first ? 'transform:scale(1.3);' : '' }}"></button>
                @endforeach
            </div>
        </div>

        <style>
            @media(min-width:768px){ .news-mobile{display:none!important;} }
            @media(max-width:767px){ .news-desktop{display:none!important;} }
        </style>
        <script>
            var newsIdx=0,newsTotal={{ $newsTotal }};
            function newsSet(n){
                newsIdx=n;
                var tw=document.getElementById('news-track-wrap').offsetWidth;
                var track=document.getElementById('news-track');
                track.style.transition='transform 0.35s ease';
                track.style.transform='translateX(-'+(newsIdx*tw)+'px)';
                document.getElementById('news-prev').style.opacity=newsIdx===0?'0.3':'1';
                document.getElementById('news-next').style.opacity=newsIdx===newsTotal-1?'0.3':'1';
                for(var i=0;i<newsTotal;i++){
                    var d=document.getElementById('news-dot-'+i);
                    if(!d) continue;
                    d.style.background=i===newsIdx?'var(--kgm-green-600)':'#ccc';
                    d.style.transform=i===newsIdx?'scale(1.3)':'scale(1)';
                }
            }
            function newsGo(dir){ newsSet(Math.max(0,Math.min(newsTotal-1,newsIdx+dir))); }
            newsSet(0);
            (function(){
                var wrap=document.getElementById('news-track-wrap');
                var track=document.getElementById('news-track');
                var startX=0,isDragging=false,dragX=0,threshold=50;
                function onStart(x){ startX=x; isDragging=true; track.style.transition='none'; }
                function onMove(x){ if(!isDragging) return; dragX=x-startX; var tw=wrap.offsetWidth; track.style.transform='translateX('+(-newsIdx*tw+dragX)+'px)'; }
                function onEnd(){ if(!isDragging) return; isDragging=false; if(dragX<-threshold) newsGo(1); else if(dragX>threshold) newsGo(-1); else newsSet(newsIdx); dragX=0; }
                wrap.addEventListener('touchstart',function(e){ onStart(e.touches[0].clientX); },{passive:true});
                wrap.addEventListener('touchmove',function(e){ onMove(e.touches[0].clientX); },{passive:true});
                wrap.addEventListener('touchend',onEnd);
                wrap.addEventListener('mousedown',function(e){ onStart(e.clientX); e.preventDefault(); });
                window.addEventListener('mousemove',function(e){ onMove(e.clientX); });
                window.addEventListener('mouseup',onEnd);
                wrap.addEventListener('click',function(e){ if(Math.abs(dragX)>5) e.preventDefault(); },{capture:true});
            })();
        </script>
        <div style="text-align:center;margin-top:22px;">
            <a href="{{ route('news') }}" class="btn btn-outline"><i class="bi bi-newspaper"></i> ดูบทความทั้งหมด</a>
        </div>
    </div>
</section>
@endif

{{-- ══ ONLINE CHANNELS ══ --}}
<section class="section online-channels-section">
    <div class="container">
        <div style="text-align:center;">
            <div class="online-channels-title">Online Shopping</div>
            <div class="online-channels-subtitle">สั่งซื้อออนไลน์</div>
        </div>
        <div class="online-channels-grid">
            @foreach([
                ['https://shopee.co.th/kgmuniform',          '/images/online/online_shopping_shopee.jpg',  'Shopee'],
                ['https://www.lazada.co.th/shop/kgmuniform', '/images/online/online_shopping_Lazada.jpg',  'Lazada'],
                ['https://www.tiktok.com/@kgmuniform',       '/images/online/online_shopping_tiktok.jpg',  'TikTok Shop'],
                ['#',       '/images/online/online_shopping_thaimart.jpg',  'TikTok Shop'],
            ] as [$url, $img, $name])
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="online-channel-card">
                <img src="{{ $img }}" alt="{{ $name }}" width="515" height="233" loading="lazy">
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA ══ --}}
<section style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:2px;margin-bottom:10px;">พร้อมเริ่มต้นแล้วหรือยัง?</div>
        <h2 style="font-size:clamp(20px,4.5vw,36px);font-weight:800;color:white;margin:0 0 12px;line-height:1.55;">สั่งผลิตเครื่องแบบคุณภาพสูง<br>ในราคาที่คุ้มค่า</h2>
        <p style="font-size:15px;color:rgba(255,255,255,0.7);margin:0 0 26px;">ติดต่อเราเพื่อรับใบเสนอราคาฟรี ไม่มีค่าใช้จ่าย</p>
        <div class="cta-btns" style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('quote') }}"   class="btn btn-gold btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี</a>
            <a href="{{ route('contact') }}" class="btn btn-lg" style="background:rgba(255,255,255,0.12);color:white;border:1.5px solid rgba(255,255,255,0.25);"><i class="bi bi-telephone"></i> ติดต่อเรา</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ── Coupon carousel (infinite clone) ──
(function(){
    const track = document.getElementById('cq-track');
    if (!track) return;
    const origCards = Array.from(track.querySelectorAll('.cq-ticket'));
    const total = origCards.length;
    if (total === 0) return;

    // Append clones so the list appears to continue forever
    const clones = origCards.map(c => { const cl = c.cloneNode(true); track.appendChild(cl); return cl; });

    const GAP = 14, CARD = 272, STEP = CARD + GAP;
    let cur = 0, tmr = null;

    function vis() {
        const w = track.parentElement.offsetWidth;
        return w < 360 ? 1 : w < 640 ? 2 : Math.min(total, Math.floor((w + GAP) / STEP));
    }
    function maxI() { return Math.max(0, total - vis()); }

    // Show/hide clones depending on whether scrolling is needed
    function updateClones() {
        const show = maxI() > 0;
        clones.forEach(c => { c.style.display = show ? '' : 'none'; });
    }

    // Silent jump — remove transition, move, restore transition
    function snapTo(i) {
        track.style.transition = 'none';
        cur = i;
        track.style.transform = `translateX(-${cur * STEP}px)`;
        requestAnimationFrame(() => { track.style.transition = ''; });
    }

    function goTo(i) {
        updateClones();
        const vw = track.parentElement.offsetWidth;
        const tw = total * CARD + Math.max(0, total - 1) * GAP;
        track.style.marginLeft = tw < vw ? `${(vw - tw) / 2}px` : '0';
        if (maxI() === 0) { snapTo(0); return; }
        cur = i;
        track.style.transform = `translateX(-${cur * STEP}px)`;
    }

    // After the CSS transition (~450ms), silently jump from clone back to original
    function afterSlide() {
        if (cur >= total) snapTo(cur - total);
        else if (cur < 0) snapTo(cur + total);
    }

    function slide(d) {
        reset();
        goTo(cur + d);
        setTimeout(afterSlide, 460);
    }

    function reset() {
        clearInterval(tmr);
        tmr = setInterval(() => { goTo(cur + 1); setTimeout(afterSlide, 460); }, 4500);
    }

    window.cqSlide = d => slide(d);
    window.cqGo    = i => { reset(); goTo(i); };
    goTo(0);
    reset();
    let resizeTmr;
    window.addEventListener('resize', () => { clearTimeout(resizeTmr); resizeTmr = setTimeout(() => { afterSlide(); goTo(cur); }, 150); });

    // drag / swipe
    let startX = 0, startY = 0, horizLock = false;
    const vp = track.parentElement;
    vp.addEventListener('mousedown',  e => { startX = e.clientX; vp.style.cursor = 'grabbing'; });
    vp.addEventListener('mousemove',  e => { if (e.buttons !== 1) return; e.preventDefault(); });
    vp.addEventListener('mouseup',    e => { vp.style.cursor = ''; const dx = startX - e.clientX; if (Math.abs(dx) > 30) slide(dx > 0 ? 1 : -1); });
    vp.addEventListener('mouseleave', () => { vp.style.cursor = ''; });
    vp.addEventListener('touchstart', e => { startX = e.touches[0].clientX; startY = e.touches[0].clientY; horizLock = false; }, { passive: true });
    vp.addEventListener('touchmove',  e => { const dx = Math.abs(startX - e.touches[0].clientX), dy = Math.abs(startY - e.touches[0].clientY); if (!horizLock && dx > dy) horizLock = true; if (horizLock) e.preventDefault(); }, { passive: false });
    vp.addEventListener('touchend',   e => { const dx = startX - e.changedTouches[0].clientX; if (Math.abs(dx) > 30) slide(dx > 0 ? 1 : -1); });
})();

// ── Hero 60/20/20 slide carousel ──
(function() {
    const track = document.getElementById('hero-track');
    if (!track) return;
    const items = Array.from(track.querySelectorAll('.hero-item'));
    const total = items.length;
    if (total === 0) return;

    let cur = 0, heroTimer = null;
    const isMob = () => window.innerWidth < 768;

    // 5-position table: far-left · hi-left · hi-center · hi-right · far-right
    const DESK = {
        fl:{ left:'-22%',            width:'20%',              top:'14px', height:'calc(100% - 28px)', opacity:0,   z:1 },
        hl:{ left:'0%',              width:'20%',              top:'14px', height:'calc(100% - 28px)', opacity:.68, z:1 },
        hc:{ left:'calc(20% + 8px)', width:'calc(60% - 16px)', top:'0',    height:'100%',              opacity:1,   z:2 },
        hr:{ left:'80%',             width:'20%',              top:'14px', height:'calc(100% - 28px)', opacity:.68, z:1 },
        fr:{ left:'102%',            width:'20%',              top:'14px', height:'calc(100% - 28px)', opacity:0,   z:1 },
    };
    const MOB = {
        fl:{ left:'-100%', width:'100%', top:'0', height:'100%', opacity:0, z:1 },
        hl:{ left:'-100%', width:'100%', top:'0', height:'100%', opacity:0, z:1 },
        hc:{ left:'0%',    width:'100%', top:'0', height:'100%', opacity:1, z:2 },
        hr:{ left:'100%',  width:'100%', top:'0', height:'100%', opacity:0, z:1 },
        fr:{ left:'100%',  width:'100%', top:'0', height:'100%', opacity:0, z:1 },
    };

    function applyPos(item, key, instant) {
        const p = (isMob() ? MOB : DESK)[key];
        if (instant) { item.style.transition = 'none'; }
        item.style.left = p.left; item.style.width  = p.width;
        item.style.top  = p.top;  item.style.height = p.height;
        item.style.opacity = p.opacity; item.style.zIndex = p.z;
        item.style.pointerEvents = p.opacity > 0 ? 'auto' : 'none';
        if (instant) { void item.offsetWidth; item.style.transition = ''; }
    }

    function keyFor(idx, c) {
        const d = ((idx - c) % total + total) % total;
        if (d === 0)         return 'hc';
        if (d === 1)         return 'hr';
        if (d === total - 1) return 'hl';
        if (d === 2)         return 'fr';
        if (d === total - 2) return 'fl';
        return 'fr';
    }

    const curKey = items.map(() => 'fr');

    function goTo(newCur, dir) {
        cur = ((newCur % total) + total) % total;
        items.forEach((item, i) => {
            const oldK = curKey[i], newK = keyFor(i, cur);
            const off = k => k === 'fr' || k === 'fl';
            // snap incoming item to correct off-screen edge before animating in
            if (off(oldK) && !off(newK)) {
                const stage = dir >= 0 ? 'fr' : 'fl';
                if (oldK !== stage) applyPos(item, stage, true);
            }
            applyPos(item, newK, false);
            curKey[i] = newK;
            item.classList.toggle('hero-item-center', newK === 'hc');
        });
        document.querySelectorAll('.hero-dot').forEach((d, j) => d.classList.toggle('active', j === cur));
    }

    function slide(d) { clearInterval(heroTimer); goTo(cur + d, d); startAuto(); }
    function startAuto() { if (total > 1) heroTimer = setInterval(() => slide(1), 5500); }

    document.getElementById('hero-prev')?.addEventListener('click', () => slide(-1));
    document.getElementById('hero-next')?.addEventListener('click', () => slide(1));
    window.heroGo = function(n) { clearInterval(heroTimer); goTo(n, n > cur ? 1 : -1); startAuto(); };

    const heroVp = document.querySelector('#hero-slider .hero-viewport');
    if (heroVp) {
        // Touch swipe
        let tx = 0;
        heroVp.addEventListener('touchstart', e => { tx = e.touches[0].clientX; }, { passive: true });
        heroVp.addEventListener('touchend',   e => { const dx = tx - e.changedTouches[0].clientX; if (Math.abs(dx) > 40) slide(dx > 0 ? 1 : -1); });

        // Mouse drag — prevent click when dragged
        let mx = 0, dragged = false;
        heroVp.addEventListener('mousedown', e => { mx = e.clientX; dragged = false; heroVp.classList.add('dragging'); });
        heroVp.addEventListener('mousemove', e => { if (e.buttons === 1 && Math.abs(e.clientX - mx) > 5) dragged = true; });
        heroVp.addEventListener('mouseup',   e => {
            heroVp.classList.remove('dragging');
            if (dragged) { const dx = mx - e.clientX; if (Math.abs(dx) > 40) slide(dx > 0 ? 1 : -1); }
        });
        heroVp.addEventListener('mouseleave', () => heroVp.classList.remove('dragging'));
        heroVp.addEventListener('click',  e => { if (dragged) { e.preventDefault(); e.stopPropagation(); } }, true);
        heroVp.addEventListener('dragstart', e => e.preventDefault());
    }

    // init all positions instantly
    items.forEach((item, i) => { const k = keyFor(i, 0); applyPos(item, k, true); curKey[i] = k; item.classList.toggle('hero-item-center', k === 'hc'); });
    startAuto();

    let resizeTmr;
    window.addEventListener('resize', () => {
        clearTimeout(resizeTmr);
        resizeTmr = setTimeout(() => { items.forEach((item, i) => { applyPos(item, curKey[i], true); item.classList.toggle('hero-item-center', curKey[i] === 'hc'); }); }, 150);
    });
})();

// ── Product tabs ──
function switchTab(name, btn) {
    document.querySelectorAll('[id^="tab-"]').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.product-tab').forEach(el => el.classList.remove('active'));
    const pane = document.getElementById('tab-' + name);
    if (pane) pane.style.display = '';
    btn.classList.add('active');
}
</script>
@endpush
