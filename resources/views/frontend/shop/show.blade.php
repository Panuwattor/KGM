@extends('layouts.app')
@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('og_tags')
<link rel="canonical" href="{{ url()->current() }}">
<meta property="og:type" content="product">
<meta property="og:url" content="{{ url()->current() }}">
<meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
<meta property="og:description" content="{{ $product->meta_description ?? $product->short_description }}">
<meta property="og:image" content="{{ $product->main_image ? asset('storage/'.$product->main_image) : '' }}">
<meta property="og:site_name" content="กิจเจริญการ์เมนท์">
@php
    $jsonImages = $product->images->map(fn($img) => asset('storage/'.$img->image_path))->values()->all();
    if (empty($jsonImages) && $product->main_image) {
        $jsonImages = [asset('storage/'.$product->main_image)];
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
        $allImages = $product->images->map(fn($img) => asset('storage/'.$img->image_path))->values()->toArray();
        if (empty($allImages) && $product->main_image) $allImages = [asset('storage/'.$product->main_image)];
        $firstImage = $allImages[0] ?? '';
    @endphp

    <div class="product-layout" x-data="productPage({{ $product->id }}, {{ json_encode($product->variants) }}, {{ $product->current_price }}, '{{ $firstImage }}', {{ json_encode($allImages) }})">

        {{-- Gallery --}}
        <div class="product-gallery">
            <div class="main-img" @click="openLightbox(currentImage)">
                <img :src="currentImage" alt="{{ $product->name }}">
            </div>
            <div class="thumb-list">
                @foreach($product->images as $i => $img)
                <div class="thumb {{ $i===0?'active':'' }}" onclick="setImage('{{ asset('storage/'.$img->image_path) }}', this)">
                    <img src="{{ asset('storage/'.$img->image_path) }}" alt="">
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
        <div>
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

            <div class="product-price">
                <span class="product-price-current" x-text="'฿' + currentPrice.toLocaleString()"></span>
                @if($product->sale_price)
                <span class="product-price-old">฿{{ number_format($product->price, 0) }}</span>
                <span class="badge badge-sale">-{{ $product->discount_percent }}%</span>
                @endif
            </div>

            @if($product->short_description)
            <p class="product-desc">{{ $product->short_description }}</p>
            @endif

            {{-- Variants --}}
            @if($product->variants->isNotEmpty())
            @php $sizes = $product->variants->pluck('size')->filter()->unique(); $colors = $product->variants->pluck('color')->filter()->unique(); @endphp
            @if($sizes->isNotEmpty())
            <div class="variant-section">
                <div class="variant-label">ไซส์: <span class="variant-label-value" x-text="selectedSize || 'เลือกไซส์'"></span></div>
                <div class="variant-options">
                    @foreach($sizes as $size)
                    <button class="variant-btn" :class="{ 'active': selectedSize === '{{ $size }}' }" @click="selectVariant('{{ $size }}', null)">{{ $size }}</button>
                    @endforeach
                </div>
            </div>
            @endif
            @if($colors->isNotEmpty())
            <div class="variant-section-color">
                <div class="variant-label">สี: <span class="variant-label-value" x-text="selectedColor || 'เลือกสี'"></span></div>
                <div class="variant-options">
                    @foreach($colors as $color)
                    <button class="variant-btn" :class="{ 'active': selectedColor === '{{ $color }}' }" @click="selectVariant(null, '{{ $color }}')">{{ $color }}</button>
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            {{-- Quantity --}}
            <div class="qty-row">
                <div class="qty-ctrl">
                    <button class="qty-btn" @click="qty = Math.max(1, qty-1)"><i class="bi bi-dash"></i></button>
                    <input type="number" class="qty-input" x-model="qty" min="1" max="{{ $product->stock_quantity }}">
                    <button class="qty-btn" @click="qty = Math.min({{ $product->stock_quantity }}, qty+1)"><i class="bi bi-plus"></i></button>
                </div>
                <span class="qty-stock">สินค้าคงเหลือ: <strong>{{ $product->stock_quantity }}</strong> ชิ้น</span>
            </div>

            {{-- Actions --}}
            <div class="product-actions">
                <button @click="addToCartFn()" class="btn btn-primary btn-lg" :disabled="{{ $product->stock_quantity <= 0 ? 'true' : 'false' }}">
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
                <div><i class="bi bi-truck"></i> ส่งฟรีเมื่อซื้อครบ ฿1,000</div>
                <div><i class="bi bi-shield-check"></i> รับประกันคุณภาพ 30 วัน</div>
                @if($product->sku)<div><i class="bi bi-upc sku-icon"></i> SKU: {{ $product->sku }}</div>@endif
            </div>
        </div>
    </div>

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
                        <div class="review-avatar">{{ strtoupper(substr($review->user->name,0,1)) }}</div>
                        <div>
                            <div class="review-name">{{ $review->user->name }}</div>
                            <div class="review-stars">@for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$review->rating?'-fill':'' }}"></i>@endfor</div>
                        </div>
                        <span class="review-date">{{ $review->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($review->title)<div class="review-title">{{ $review->title }}</div>@endif
                    <p class="review-body">{{ $review->body }}</p>
                </div>
                @endforeach
                @endif

                @auth
                <div class="review-form">
                    <h4>เขียนรีวิวของคุณ</h4>
                    <form method="POST" action="{{ route('shop.review', $product) }}">
                        @csrf
                        <div class="rating-row">
                            <label class="rating-heading">คะแนน</label>
                            <div class="rating-stars">
                                @for($i=1;$i<=5;$i++)
                                <label>
                                    <input type="radio" name="rating" value="{{ $i }}" style="display:none;" required>
                                    <i class="bi bi-star-fill"></i>
                                </label>
                                @endfor
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" name="title" class="form-control" placeholder="หัวข้อรีวิว (ไม่บังคับ)">
                        </div>
                        <div class="form-group">
                            <textarea name="body" class="form-control" rows="4" placeholder="แชร์ประสบการณ์การใช้งาน..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> ส่งรีวิว</button>
                    </form>
                </div>
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
function productPage(productId, variants, basePrice, initialImage, allImages) {
    return {
        productId,
        variants,
        basePrice,
        currentPrice: basePrice,
        selectedVariantId: null,
        selectedSize: null,
        selectedColor: null,
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
        selectVariant(size, color) {
            if (size !== null) this.selectedSize = size;
            if (color !== null) this.selectedColor = color;
            const v = variants.find(v =>
                (size ? v.size === this.selectedSize : true) &&
                (color ? v.color === this.selectedColor : true)
            );
            if (v) {
                this.selectedVariantId = v.id;
                this.currentPrice = basePrice + parseFloat(v.price_adjustment || 0);
            }
        },
        addToCartFn(redirect = false) {
            addToCart(this.productId, this.selectedVariantId, this.qty);
            if (redirect) setTimeout(() => window.location = '/cart', 500);
        }
    };
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
