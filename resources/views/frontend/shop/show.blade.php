@extends('layouts.app')
@section('title', $product->meta_title ?? $product->name)
@section('meta_description', $product->meta_description ?? $product->short_description)

@section('og_tags')
<meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
<meta property="og:description" content="{{ $product->meta_description ?? $product->short_description }}">
<meta property="og:image" content="{{ $product->main_image ? asset('storage/'.$product->main_image) : '' }}">
<meta property="og:type" content="product">
@endsection

@push('styles')
<style>
.product-gallery { position: relative; }
.main-img { width: 100%; border-radius: 20px; overflow: hidden; aspect-ratio: 1; background: #f8f9fa; }
.main-img img { width: 100%; height: 100%; object-fit: cover; cursor: zoom-in; transition: transform 0.4s; }
.main-img:hover img { transform: scale(1.08); }
.thumb-list { display: flex; gap: 8px; margin-top: 10px; flex-wrap: wrap; }
.thumb { width: 70px; height: 70px; border-radius: 12px; overflow: hidden; cursor: pointer; border: 2px solid transparent; transition: all 0.2s; flex-shrink: 0; }
.thumb.active, .thumb:hover { border-color: var(--kgm-green-500); }
.thumb img { width: 100%; height: 100%; object-fit: cover; }

.variant-btn { padding: 8px 18px; border-radius: 999px; border: 2px solid #e8ecef; background: white; cursor: pointer; font-size: 14px; font-weight: 600; transition: all 0.2s; font-family: inherit; }
.variant-btn:hover, .variant-btn.active { border-color: var(--kgm-green-500); background: var(--kgm-green-600); color: white; }
.variant-btn.sold-out { opacity: 0.5; cursor: not-allowed; }

.qty-ctrl { display: flex; align-items: center; gap: 0; border: 2px solid #e8ecef; border-radius: 999px; overflow: hidden; }
.qty-btn { width: 40px; height: 40px; background: none; border: none; cursor: pointer; font-size: 18px; color: var(--kgm-green-600); transition: all 0.2s; }
.qty-btn:hover { background: var(--kgm-green-100); }
.qty-input { width: 52px; text-align: center; border: none; outline: none; font-size: 16px; font-weight: 700; font-family: inherit; }

.tab-list { display: flex; gap: 4px; border-bottom: 2px solid #e8ecef; margin-bottom: 20px; }
.tab-btn { padding: 10px 20px; font-size: 15px; font-weight: 600; border: none; background: none; cursor: pointer; color: #888; border-bottom: 3px solid transparent; margin-bottom: -2px; transition: all 0.2s; font-family: inherit; }
.tab-btn.active { color: var(--kgm-green-700); border-bottom-color: var(--kgm-green-600); }
</style>
@endpush

@section('content')
<div class="container" style="padding-top:24px;padding-bottom:48px;">
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

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;" x-data="productPage({{ $product->id }}, {{ json_encode($product->variants) }}, {{ $product->current_price }})">
        {{-- Gallery --}}
        <div class="product-gallery">
            <div class="main-img">
                <img :src="currentImage" id="main-img" alt="{{ $product->name }}">
            </div>
            <div class="thumb-list">
                @foreach($product->images as $i => $img)
                <div class="thumb {{ $i===0?'active':'' }}" onclick="setImage('{{ asset('storage/'.$img->image_path) }}', this)">
                    <img src="{{ asset('storage/'.$img->image_path) }}" alt="">
                </div>
                @endforeach
            </div>
        </div>

        {{-- Info --}}
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                @if($product->category)<a href="{{ route('shop.category', $product->category->slug) }}" style="font-size:13px;color:var(--kgm-green-500);font-weight:600;">{{ $product->category->name }}</a>@endif
                @if($product->is_new)<span class="badge badge-new">ใหม่</span>@endif
                @if($product->is_bestseller)<span class="badge badge-hot">ขายดี</span>@endif
            </div>
            <h1 style="font-size:28px;font-weight:800;color:var(--kgm-green-900);margin-bottom:12px;">{{ $product->name }}</h1>

            {{-- Rating --}}
            @if($product->reviews->count())
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:16px;">
                <div style="color:var(--kgm-gold-400);font-size:16px;">
                    @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=round($product->average_rating)?'-fill':'' }}"></i>@endfor
                </div>
                <span style="font-weight:700;font-size:15px;">{{ number_format($product->average_rating,1) }}</span>
                <span style="color:#888;font-size:13px;">({{ $product->reviews->count() }} รีวิว)</span>
            </div>
            @endif

            {{-- Price --}}
            <div style="margin-bottom:20px;">
                <span style="font-size:36px;font-weight:800;color:var(--kgm-green-600);" x-text="'฿' + currentPrice.toLocaleString()"></span>
                @if($product->sale_price)
                <span style="font-size:18px;color:#aaa;text-decoration:line-through;margin-left:10px;">฿{{ number_format($product->price, 0) }}</span>
                <span class="badge badge-sale" style="margin-left:8px;">-{{ $product->discount_percent }}%</span>
                @endif
            </div>

            @if($product->short_description)
            <p style="font-size:15px;color:#555;line-height:1.8;margin-bottom:20px;">{{ $product->short_description }}</p>
            @endif

            {{-- Variants --}}
            @if($product->variants->isNotEmpty())
            @php $sizes = $product->variants->pluck('size')->filter()->unique(); $colors = $product->variants->pluck('color')->filter()->unique(); @endphp
            @if($sizes->isNotEmpty())
            <div style="margin-bottom:16px;">
                <div style="font-weight:700;font-size:14px;margin-bottom:10px;">ไซส์: <span x-text="selectedSize || 'เลือกไซส์'" style="color:var(--kgm-green-600);"></span></div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach($sizes as $size)
                    <button class="variant-btn" :class="{ 'active': selectedSize === '{{ $size }}' }" @click="selectVariant('{{ $size }}', null)">{{ $size }}</button>
                    @endforeach
                </div>
            </div>
            @endif
            @if($colors->isNotEmpty())
            <div style="margin-bottom:20px;">
                <div style="font-weight:700;font-size:14px;margin-bottom:10px;">สี: <span x-text="selectedColor || 'เลือกสี'" style="color:var(--kgm-green-600);"></span></div>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    @foreach($colors as $color)
                    <button class="variant-btn" :class="{ 'active': selectedColor === '{{ $color }}' }" @click="selectVariant(null, '{{ $color }}')">{{ $color }}</button>
                    @endforeach
                </div>
            </div>
            @endif
            @endif

            {{-- Qty --}}
            <div style="display:flex;align-items:center;gap:16px;margin-bottom:24px;">
                <div class="qty-ctrl">
                    <button class="qty-btn" @click="qty = Math.max(1, qty-1)"><i class="bi bi-dash"></i></button>
                    <input type="number" class="qty-input" x-model="qty" min="1" max="{{ $product->stock_quantity }}">
                    <button class="qty-btn" @click="qty = Math.min({{ $product->stock_quantity }}, qty+1)"><i class="bi bi-plus"></i></button>
                </div>
                <span style="font-size:13px;color:#888;">สินค้าคงเหลือ: <strong>{{ $product->stock_quantity }}</strong> ชิ้น</span>
            </div>

            {{-- Actions --}}
            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;">
                <button @click="addToCartFn()" class="btn btn-primary btn-lg" style="flex:1;justify-content:center;" :disabled="{{ $product->stock_quantity <= 0 ? 'true' : 'false' }}">
                    <i class="bi bi-cart-plus"></i> เพิ่มลงตะกร้า
                </button>
                <a href="{{ route('cart') }}" @click.prevent="addToCartFn(true)" class="btn btn-gold btn-lg" style="flex:1;justify-content:center;">
                    <i class="bi bi-lightning-charge"></i> ซื้อเลย
                </a>
                @auth
                <button onclick="document.querySelector('form[action*=wishlist]').submit()" class="btn btn-outline" title="{{ $inWishlist ? 'ลบออกจากรายการโปรด' : 'เพิ่มในรายการโปรด' }}"
                    style="width:52px;height:52px;padding:0;justify-content:center;font-size:20px;{{ $inWishlist ? 'border-color:#e74c3c;color:#e74c3c;' : '' }}">
                    <i class="bi bi-heart{{ $inWishlist ? '-fill' : '' }}"></i>
                </button>
                <form method="POST" action="{{ route('account.wishlist.toggle', $product) }}" style="display:none;">@csrf</form>
                @endauth
            </div>

            <div style="border-top:1px solid #eee;padding-top:16px;font-size:13px;color:#888;display:flex;flex-direction:column;gap:6px;">
                <div><i class="bi bi-truck" style="color:var(--kgm-green-500);margin-right:6px;"></i> ส่งฟรีเมื่อซื้อครบ ฿1,000</div>
                <div><i class="bi bi-shield-check" style="color:var(--kgm-green-500);margin-right:6px;"></i> รับประกันคุณภาพ 30 วัน</div>
                @if($product->sku)<div><i class="bi bi-upc" style="color:#aaa;margin-right:6px;"></i> SKU: {{ $product->sku }}</div>@endif
            </div>
        </div>
    </div>

    {{-- Tabs --}}
    <div style="margin-top:48px;" x-data="{ tab: 'detail' }">
        <div class="tab-list">
            <button class="tab-btn" :class="{ active: tab==='detail' }" @click="tab='detail'"><i class="bi bi-info-circle"></i> รายละเอียดสินค้า</button>
            <button class="tab-btn" :class="{ active: tab==='review' }" @click="tab='review'"><i class="bi bi-star"></i> รีวิว ({{ $product->reviews->count() }})</button>
        </div>
        <div x-show="tab==='detail'" x-cloak>
            <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                {!! $product->description !!}
            </div>
        </div>
        <div x-show="tab==='review'" x-cloak>
            <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                @if($product->reviews->isEmpty())
                <p style="text-align:center;color:#aaa;padding:32px 0;">ยังไม่มีรีวิว เป็นคนแรกที่รีวิวสินค้านี้</p>
                @else
                @foreach($product->reviews as $review)
                <div style="padding:16px 0;border-bottom:1px solid #f5f7f5;">
                    <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                        <div style="width:36px;height:36px;background:var(--kgm-green-500);border-radius:999px;display:flex;align-items:center;justify-content:center;color:white;font-weight:700;">{{ strtoupper(substr($review->user->name,0,1)) }}</div>
                        <div>
                            <div style="font-weight:700;font-size:14px;">{{ $review->user->name }}</div>
                            <div style="color:var(--kgm-gold-400);font-size:13px;">@for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$review->rating?'-fill':'' }}"></i>@endfor</div>
                        </div>
                        <span style="margin-left:auto;font-size:12px;color:#aaa;">{{ $review->created_at->format('d/m/Y') }}</span>
                    </div>
                    @if($review->title)<div style="font-weight:700;margin-bottom:4px;">{{ $review->title }}</div>@endif
                    <p style="font-size:14px;color:#555;line-height:1.7;">{{ $review->body }}</p>
                </div>
                @endforeach
                @endif
                @auth
                <div style="margin-top:24px;">
                    <h4 style="font-weight:700;margin-bottom:16px;">เขียนรีวิวของคุณ</h4>
                    <form method="POST" action="{{ route('shop.review', $product) }}">
                        @csrf
                        <div style="margin-bottom:12px;">
                            <label style="font-weight:600;font-size:14px;display:block;margin-bottom:6px;">คะแนน</label>
                            <div style="display:flex;gap:4px;font-size:24px;" x-data="{ rating: 0 }">
                                @for($i=1;$i<=5;$i++)
                                <label style="cursor:pointer;color:var(--kgm-gold-400);">
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

    {{-- Related --}}
    @if($relatedProducts->isNotEmpty())
    <div style="margin-top:48px;">
        <h2 style="font-size:22px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;"><i class="bi bi-grid-3x3-gap"></i> สินค้าที่เกี่ยวข้อง</h2>
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
function productPage(productId, variants, basePrice) {
    return {
        productId,
        variants,
        basePrice,
        currentPrice: basePrice,
        selectedVariantId: null,
        selectedSize: null,
        selectedColor: null,
        qty: 1,
        currentImage: document.getElementById('main-img')?.src || '',
        selectVariant(size, color) {
            if (size !== null) this.selectedSize = size;
            if (color !== null) this.selectedColor = color;
            const v = variants.find(v =>
                (size ? v.size === this.selectedSize : true) &&
                (color ? v.color === this.selectedColor : true)
            );
            if (v) { this.selectedVariantId = v.id; this.currentPrice = basePrice + parseFloat(v.price_adjustment || 0); }
        },
        addToCartFn(redirect = false) {
            addToCart(this.productId, this.selectedVariantId, this.qty);
            if (redirect) setTimeout(() => window.location = '/cart', 500);
        }
    };
}
function setImage(src, thumb) {
    document.getElementById('main-img').src = src;
    document.querySelectorAll('.thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}
</script>
@endpush
