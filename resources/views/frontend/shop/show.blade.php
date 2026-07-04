@extends('layouts.app')
@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('og_tags')
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:type" content="product">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
<meta property="og:description" content="{{ $product->meta_description ?? $product->short_description }}">
<meta property="og:image" content="{{ $product->main_image ? media_url($product->main_image) : '' }}">
<meta property="og:site_name" content="กิจเจริญการ์เมนท์">
@php
    $jsonImages = $product->images->map(fn($img) => media_url($img->image_path))->values()->all();
    if (empty($jsonImages) && $product->main_image) {
        $jsonImages = [media_url($product->main_image)];
    }
    $jsonLd = [
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $product->name,
        'description' => $product->meta_description ?? $product->short_description ?? '',
        'image'       => $jsonImages,
        'sku'         => $product->sku ?? '',
        'url'         => url()->current(),
        'brand'       => ['@type' => 'Brand', 'name' => 'กิจเจริญการ์เมนท์ (1993) จำกัด'],
        'offers'      => [
            '@type'         => 'Offer',
            'url'           => url()->current(),
            'priceCurrency' => 'THB',
            'price'         => number_format($product->current_price, 2, '.', ''),
            'availability'  => $product->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'itemCondition' => 'https://schema.org/NewCondition',
            'seller'        => ['@type' => 'Organization', 'name' => 'กิจเจริญการ์เมนท์ (1993) จำกัด'],
        ],
    ];
    if ($product->reviews->count()) {
        $jsonLd['aggregateRating'] = [
            '@type'       => 'AggregateRating',
            'ratingValue' => number_format($product->average_rating, 1, '.', ''),
            'reviewCount' => $product->reviews->count(),
            'bestRating'  => '5',
            'worstRating' => '1',
        ];
    }
@endphp
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}</script>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/shop-show.css') }}">
@endpush

@section('content')
<div class="container product-page">
    {{-- Breadcrumb --}}
    <div class="breadcrumb" style="margin-bottom:20px;">
        <a href="{{ route('home') }}"><i class="bi bi-house"></i></a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('shop') }}">ร้านค้า</a>
        @if($product->category)
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('shop.category', $product->category->slug) }}">{{ $product->category->name }}</a>
        @endif
        <span class="breadcrumb-sep">/</span>
        <span>{{ $product->name }}</span>
    </div>

    @php
        $allImages = $product->images->map(fn($img) => media_url($img->image_path))->values()->toArray();
        if (empty($allImages) && $product->main_image) $allImages = [media_url($product->main_image)];
        $firstImage = $allImages[0] ?? '';
    @endphp

    @php $flashPrice = isset($flashSaleItem) && $flashSaleItem ? (float)$flashSaleItem->sale_price : null; @endphp
    <div class="product-layout" x-data="productPage({{ $product->id }}, {{ json_encode($product->variants) }}, {{ $flashPrice ?? $product->current_price }}, '{{ $firstImage }}', {{ json_encode($allImages) }}, {{ $flashPrice !== null ? $flashPrice : 'null' }}, {{ $embroideryPrice }}, {{ (int) $product->stock_quantity }})">

        {{-- Gallery --}}
        <div class="product-gallery">
            <div class="main-img" @click="openLightbox(currentImage)">
                <img :src="currentImage" alt="{{ $product->name }}">
            </div>
            <div class="thumb-list">
                @foreach($product->images as $i => $img)
                <div class="thumb {{ $i===0?'active':'' }}" onclick="setImage('{{ media_url($img->image_path) }}', this)">
                    <img src="{{ media_url($img->image_path) }}" alt="">
                </div>
                @endforeach
            </div>
        </div>

        {{-- Lightbox --}}
        <div class="lightbox" x-show="lightboxOpen" x-cloak @click.self="lightboxOpen=false" @keydown.escape.window="lightboxOpen=false">
            <button class="lightbox-close" @click="lightboxOpen=false"><i class="bi bi-x-lg"></i></button>
            <button class="lightbox-nav lightbox-prev" @click="lightboxPrev()" x-show="allImages.length > 1"><i class="bi bi-chevron-left"></i></button>
            <img :src="lightboxImage" alt="">
            <button class="lightbox-nav lightbox-next" @click="lightboxNext()" x-show="allImages.length > 1"><i class="bi bi-chevron-right"></i></button>
        </div>

        {{-- Info --}}
        <div style="min-width:0;">
            <div class="product-header-meta">
                @if($product->category)
                <a href="{{ route('shop.category', $product->category->slug) }}" class="product-cat-link">{{ $product->category->name }}</a>
                @endif
                @if($product->is_new)<span class="badge badge-new">ใหม่</span>@endif
                @if($product->is_bestseller)<span class="badge badge-hot">ขายดี</span>@endif
            </div>

            <h1 class="product-title">{{ $product->name }}</h1>

            @if($product->reviews->count())
            <div class="product-rating">
                <div class="product-rating-stars">
                    @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=round($product->average_rating)?'-fill':'' }}"></i>@endfor
                </div>
                <span class="product-rating-score">{{ number_format($product->average_rating,1) }}</span>
                <span class="product-rating-count">({{ $product->reviews->count() }} รีวิว)</span>
            </div>
            @endif

            @if($flashPrice !== null)
            <div style="display:inline-flex;align-items:center;gap:8px;background:linear-gradient(135deg,#e53935,#ff7043);color:white;border-radius:20px;padding:5px 14px;font-size:13px;font-weight:700;margin-bottom:10px;">
                <i class="bi bi-lightning-charge-fill"></i>
                Flash Sale — <a href="{{ route('flash-sales.show', $flashSaleItem->flashSale) }}" style="color:white;text-decoration:underline;">{{ $flashSaleItem->flashSale->name }}</a>
                &nbsp;· สิ้นสุด {{ $flashSaleItem->flashSale->ends_at->format('d/m/Y H:i') }} น.
            </div>
            @endif
            <div class="product-price">
                <span class="product-price-current" x-text="'฿' + currentPrice.toLocaleString()"></span>
                @if($flashPrice !== null)
                <span class="product-price-old">฿{{ number_format($product->price, 0) }}</span>
                @php $flashDiscount = $product->price > 0 ? (int)round(($product->price - $flashPrice) / $product->price * 100) : 0; @endphp
                @if($flashDiscount > 0)
                <span class="badge badge-sale">-{{ $flashDiscount }}%</span>
                @endif
                @elseif($product->sale_price)
                <span class="product-price-old">฿{{ number_format($product->price, 0) }}</span>
                <span class="badge badge-sale">-{{ $product->discount_percent }}%</span>
                @endif
            </div>

            @if($product->short_description)
            <p class="product-desc">{{ $product->short_description }}</p>
            @endif

            {{-- Variants --}}
            @if($product->variants->isNotEmpty())
            @php $sizes = $product->variants->pluck('size')->filter()->unique(); @endphp
            @if($sizes->isNotEmpty())
            <div class="variant-section">
                <div class="variant-label">ไซซ์: <span class="variant-label-value" x-text="selectedSize || 'เลือกไซซ์'"></span> <span style="color:#ef4444">*</span></div>
                <div class="variant-options" :class="{ 'shake': showSizeError }">
                    @foreach($sizes as $size)
                    @php $sizeVariant = $product->variants->firstWhere('size', $size); @endphp
                    <button class="variant-btn"
                        :class="{ 'active': selectedSize === '{{ $size }}', 'error-highlight': showSizeError && !selectedSize, 'sold-out': {{ (int) ($sizeVariant->stock_quantity ?? 0) }} <= 0 }"
                        :disabled="{{ (int) ($sizeVariant->stock_quantity ?? 0) <= 0 ? 'true' : 'false' }}"
                        @click="selectVariant('{{ $size }}'); showSizeError = false">{{ $size }}</button>
                    @endforeach
                </div>
                <p x-show="showSizeError" x-cloak style="color:#ef4444;font-size:13px;margin-top:6px;"><i class="bi bi-exclamation-circle"></i> กรุณาเลือกไซซ์ก่อน</p>
                <p x-show="selectedSize" x-cloak style="font-size:13px;margin-top:8px;color:#555;">
                    คงเหลือไซซ์ <strong x-text="selectedSize"></strong>: <strong x-text="maxStock" style="color:var(--kgm-green-700);"></strong> ชิ้น
                </p>
            </div>
            @endif
            @endif

            {{-- Embroidery (ปักชื่อ) — แสดงเฉพาะประเภทสินค้าที่รองรับงานปัก --}}
            @if($product->productType && $product->productType->has_embroidery)
            <div class="embroidery-section" style="margin:18px 0;padding:16px;border:1.5px solid #e8ecef;border-radius:14px;background:#fafbfa;">
                <label style="display:flex;align-items:flex-start;gap:10px;cursor:pointer;">
                    <input type="checkbox" x-model="embroidery" style="margin-top:3px;width:18px;height:18px;flex-shrink:0;accent-color:var(--kgm-green-600);">
                    <span>
                        <span style="font-weight:700;color:var(--kgm-green-800);font-size:15px;">
                            <i class="bi bi-pen"></i> ปักชื่อ / โลโก้บนเสื้อ
                        </span>
                        <span style="display:block;font-size:13px;color:#777;margin-top:2px;">
                            @if($embroideryPrice > 0)
                                คิดค่าบริการปักตัวละ <strong style="color:var(--kgm-green-700);">฿{{ number_format($embroideryPrice, 0) }}</strong>
                            @else
                                <strong style="color:#16a34a;">ปักฟรี!</strong> ไม่มีค่าใช้จ่ายเพิ่มเติม
                            @endif
                        </span>
                    </span>
                </label>

                <div x-show="embroidery" x-cloak style="margin-top:14px;">
                    <label class="variant-label" style="display:block;margin-bottom:6px;">รายละเอียดงานปัก <span style="color:#ef4444">*</span></label>
                    <textarea x-model="embroideryText" rows="4"
                        class="form-control"
                        style="width:100%;border:1.5px solid #e8ecef;border-radius:12px;padding:10px 12px;font-family:inherit;font-size:14px;resize:vertical;"
                        :class="{ 'is-invalid': showEmbroideryError }"
                        @input="showEmbroideryError = false"
                        placeholder="กรุณากรอก ชื่อ-สกุล / ตัวย่อโรงเรียน ที่ต้องการปัก"></textarea>
                    <p x-show="showEmbroideryError" x-cloak style="color:#ef4444;font-size:13px;margin-top:6px;"><i class="bi bi-exclamation-circle"></i> กรุณากรอกรายละเอียดงานปักก่อน</p>
                    @if($embroideryPrice > 0)
                    <p style="font-size:13px;color:#777;margin-top:8px;">
                        <i class="bi bi-info-circle"></i> ค่าปักรวม: <strong style="color:var(--kgm-green-700);" x-text="'฿' + (embroideryPrice * qty).toLocaleString()"></strong>
                        <span style="color:#aaa;">(฿{{ number_format($embroideryPrice, 0) }} × <span x-text="qty"></span> ตัว)</span>
                    </p>
                    @endif
                </div>
            </div>
            @endif

            {{-- Quantity --}}
            <div class="qty-row">
                <div class="qty-ctrl">
                    <button class="qty-btn" @click="qty = Math.max(1, qty-1)"><i class="bi bi-dash"></i></button>
                    <input type="number" class="qty-input" x-model.number="qty" min="1" :max="maxStock" @input="clampQty()" @change="clampQty()">
                    <button class="qty-btn" @click="qty = Math.min(maxStock, qty+1)" :disabled="qty >= maxStock"><i class="bi bi-plus"></i></button>
                </div>
                <span class="qty-stock">สินค้าคงเหลือ: <strong x-text="maxStock"></strong> ชิ้น</span>
            </div>

            {{-- Actions --}}
            <div class="product-actions">
                <button @click="addToCartFn()" class="btn btn-primary btn-lg" :disabled="maxStock <= 0">
                    <i class="bi bi-cart-plus"></i> เพิ่มลงตะกร้า
                </button>
                <a href="{{ route('cart') }}" @click.prevent="addToCartFn(true)" class="btn btn-gold btn-lg">
                    <i class="bi bi-lightning-charge"></i> ซื้อเลย
                </a>
                @auth
                <button onclick="document.querySelector('form[action*=wishlist]').submit()"
                    class="btn btn-outline wishlist-btn {{ $inWishlist ? 'active' : '' }}"
                    title="{{ $inWishlist ? 'ลบออกจากรายการโปรด' : 'เพิ่มในรายการโปรด' }}">
                    <i class="bi bi-heart{{ $inWishlist ? '-fill' : '' }}"></i>
                </button>
                <form method="POST" action="{{ route('account.wishlist.toggle', $product) }}" style="display:none;">@csrf</form>
                @endauth
            </div>

            <div class="product-meta">
                <div><i class="bi bi-shield-check"></i> รับประกันคุณภาพ 30 วัน</div>
                @if($product->sku)<div><i class="bi bi-upc sku-icon"></i> SKU: {{ $product->sku }}</div>@endif
            </div>

        </div>
    </div>

    
    {{-- คูปองส่วนลดสำหรับสินค้านี้ (slide เก็บได้เลย) --}}
    @if($coupons->isNotEmpty())
    <div style="margin-top:18px;min-width:0;max-width:100%;overflow:hidden;">
        <div style="font-weight:800;color:var(--kgm-green-800);font-size:14px;margin-bottom:2px;"><i class="bi bi-ticket-perforated"></i> คูปองส่วนลดที่ใช้ได้</div>
        @include('frontend.partials.coupon-carousel', ['coupons' => $coupons, 'collectedIds' => $collectedIds, 'arrows' => false])
    </div>
    @endif

    {{-- Tabs --}}
    <div class="product-tabs" x-data="{ tab: 'detail' }">
        <div class="tab-list">
            <button class="tab-btn" :class="{ active: tab==='detail' }" @click="tab='detail'"><i class="bi bi-info-circle"></i> รายละเอียดสินค้า</button>
            <button class="tab-btn" :class="{ active: tab==='review' }" @click="tab='review'"><i class="bi bi-star"></i> รีวิว ({{ $product->reviews->count() }})</button>
        </div>

        <div x-show="tab==='detail'" x-cloak>
            <div class="tab-panel">
                {!! $product->description !!}
            </div>
        </div>

        <div x-show="tab==='review'" x-cloak>
            <div class="tab-panel">
                @if($product->reviews->isEmpty())
                <p class="tab-empty">ยังไม่มีรีวิว เป็นคนแรกที่รีวิวสินค้านี้</p>
                @else
                @foreach($product->reviews as $review)
                <div class="review-item">
                    <div class="review-header">
                        <div class="review-avatar">{{ strtoupper(substr($review->customer->name,0,1)) }}</div>
                        <div>
                            <div class="review-name">{{ $review->customer->name }}</div>
                            <div class="review-stars">@for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$review->rating?'-fill':'' }}"></i>@endfor</div>
                        </div>
                        <span class="review-date">{{ $review->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($review->title)<div class="review-title">{{ $review->title }}</div>@endif
                    <p class="review-body">{{ $review->body }}</p>
                </div>
                @endforeach
                @endif

                @auth('customer')
                <div class="review-form" x-data="reviewForm()">
                    <h4>เขียนรีวิวของคุณ</h4>
                    @if(session('success'))
                    <div class="alert alert-success" style="padding:10px 14px;background:#d1fae5;color:#065f46;border-radius:8px;margin-bottom:12px;">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                    <div class="alert alert-error" style="padding:10px 14px;background:#fee2e2;color:#991b1b;border-radius:8px;margin-bottom:12px;">{{ session('error') }}</div>
                    @endif
                    <form method="POST" action="{{ route('shop.review', $product) }}">
                        @csrf
                        <div class="rating-row">
                            <label class="rating-heading">คะแนน <span style="color:#ef4444">*</span></label>
                            <div class="rating-stars" @mouseleave="hovered=0">
                                @for($i=1;$i<=5;$i++)
                                <label @mouseenter="hovered={{ $i }}" @click="selected={{ $i }}">
                                    <input type="radio" name="rating" value="{{ $i }}" x-model="selected" style="display:none;" required>
                                    <i :class="(hovered||selected) >= {{ $i }} ? 'bi bi-star-fill' : 'bi bi-star'"></i>
                                </label>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" placeholder="หัวข้อรีวิว (ไม่บังคับ)" value="{{ old('title') }}">
                        </div>
                        <div class="form-group">
                            <textarea name="body" class="form-control" rows="4" placeholder="แชร์ประสบการณ์การใช้งาน...">{{ old('body') }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> ส่งรีวิว</button>
                    </form>
                </div>
                @else
                <p style="margin-top:16px;color:#6b7280;font-size:14px;"><a href="{{ route('login') }}" style="color:var(--kgm-gold-500)">เข้าสู่ระบบ</a> เพื่อเขียนรีวิว</p>
                @endauth
            </div>
        </div>
    </div>

    {{-- Related Products --}}
    @if($relatedProducts->isNotEmpty())
    <div class="related-section">
        <h2 class="related-title"><i class="bi bi-grid-3x3-gap"></i> สินค้าที่เกี่ยวข้อง</h2>
        <div class="grid grid-4">
            @foreach($relatedProducts as $product)
            @include('components.product-card')
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
function productPage(productId, variants, basePrice, initialImage, allImages, flashSalePrice = null, embroideryPrice = 0, productStock = 0) {
    return {
        productId,
        variants,
        basePrice,
        flashSalePrice,
        currentPrice: basePrice,
        productStock,
        selectedVariantId: null,
        selectedSize: null,
        showSizeError: false,
        hasSizes: variants.some(v => v.size),
        get maxStock() {
            if (this.hasSizes && this.selectedVariantId) {
                const v = this.variants.find(v => v.id === this.selectedVariantId);
                return v ? parseInt(v.stock_quantity) || 0 : 0;
            }
            return this.productStock;
        },
        clampQty() {
            let n = parseInt(this.qty) || 1;
            if (n < 1) n = 1;
            if (this.maxStock > 0 && n > this.maxStock) n = this.maxStock;
            this.qty = n;
        },
        embroidery: false,
        embroideryText: '',
        embroideryPrice,
        showEmbroideryError: false,
        qty: 1,
        currentImage: initialImage || '',
        allImages: allImages || [],
        lightboxOpen: false,
        lightboxImage: '',
        lightboxIndex: 0,
        openLightbox(src) {
            this.lightboxImage = src;
            this.lightboxIndex = this.allImages.indexOf(src);
            this.lightboxOpen = true;
        },
        lightboxPrev() {
            this.lightboxIndex = (this.lightboxIndex - 1 + this.allImages.length) % this.allImages.length;
            this.lightboxImage = this.allImages[this.lightboxIndex];
        },
        lightboxNext() {
            this.lightboxIndex = (this.lightboxIndex + 1) % this.allImages.length;
            this.lightboxImage = this.allImages[this.lightboxIndex];
        },
        selectVariant(size) {
            this.selectedSize = size;
            const v = variants.find(v => v.size === size);
            if (v) {
                this.selectedVariantId = v.id;
                const adj = parseFloat(v.price_adjustment || 0);
                this.currentPrice = (this.flashSalePrice !== null ? this.flashSalePrice : basePrice) + adj;
            } else {
                this.selectedVariantId = null;
            }
            this.clampQty();
        },
        addToCartFn(redirect = false) {
            if (this.hasSizes && !this.selectedSize) {
                this.showSizeError = true;
                document.querySelector('.variant-section')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            if (this.maxStock <= 0) {
                if (window.Swal) Swal.fire({ icon: 'error', title: 'สินค้าหมด', text: 'ไม่สามารถสั่งซื้อได้', confirmButtonColor: 'var(--kgm-green-600)' });
                return;
            }
            if (this.qty > this.maxStock) {
                this.qty = this.maxStock;
                if (window.Swal) Swal.fire({ icon: 'warning', title: 'จำนวนเกินสต๊อก', text: 'สั่งซื้อได้สูงสุด ' + this.maxStock + ' ชิ้น', confirmButtonColor: 'var(--kgm-green-600)' });
                return;
            }
            if (this.embroidery && !this.embroideryText.trim()) {
                this.showEmbroideryError = true;
                document.querySelector('.embroidery-section')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                return;
            }
            addToCart(this.productId, this.selectedVariantId, this.qty, this.flashSalePrice, {
                embroidery: this.embroidery,
                embroidery_text: this.embroidery ? this.embroideryText.trim() : null,
            });
            if (redirect) setTimeout(() => window.location = '/cart', 500);
        }
    };
}
function reviewForm() {
    return { selected: 0, hovered: 0 };
}
function setImage(src, thumb) {
    const root = thumb.closest('[x-data]');
    if (root && root._x_dataStack) {
        root._x_dataStack[0].currentImage = src;
    }
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>
@endpush
