<div class="product-card">
    @if($product->is_new)
    <div class="product-badge-tl"><span class="badge badge-new">ใหม่</span></div>
    @endif
    @if($product->is_bestseller)
    <div class="product-badge-tr"><span class="badge badge-hot">ขายดี</span></div>
    @endif
    <div class="card">
        <div class="product-img-wrap">

            @if($product->sale_price && $product->discount_percent > 0)
            <div class="product-badge-bl"><span class="badge badge-sale">-{{ $product->discount_percent }}%</span></div>
            @endif
            <a href="{{ route('shop.show', $product->slug) }}" style="display:block;line-height:0;">
                <img src="{{ $product->main_image ? media_url($product->main_image) : asset('images/logo.png') }}"
                    alt="{{ $product->name }}" class="product-img" width="400" height="400" loading="lazy"
                    onerror="this.onerror=null;this.src='/images/logo.png'">
            </a>
            @auth('customer')
            @php
                static $wishlistIds = null;
                if ($wishlistIds === null) {
                    $wishlistIds = auth('customer')->user()->wishlists()->pluck('product_id')->all();
                }
                $inWishlist = in_array($product->id, $wishlistIds);
            @endphp
            <button type="button"
                class="product-wishlist {{ $inWishlist ? 'active' : '' }}"
                title="รายการโปรด"
                data-url="{{ route('account.wishlist.toggle', $product) }}"
                onclick="toggleWishlist(this)">
                <i class="bi bi-heart{{ $inWishlist ? '-fill' : '' }}"></i>
            </button>
            @endauth
        </div>
        <div class="product-info">
            @if($product->category?->name)
            <div class="product-category">{{ $product->category->name }}</div>
            @endif
            <a href="{{ route('shop.show', $product->slug) }}" class="product-name" style="display:block;color:inherit;">{{ $product->name }}</a>
            <div class="product-price">
                <span class="price-current">฿{{ number_format($product->current_price, 0) }}</span>
                @if($product->sale_price)<span class="price-original">฿{{ number_format($product->price, 0) }}</span>@endif
            </div>
            @if($product->stock_quantity > 0)
            <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-primary product-add-btn">
                <i class="bi bi-cart-plus"></i> เลือกไซซ์
            </a>
            @else
            <button disabled class="btn product-add-btn" style="background:#f0f0f0;color:#bbb;cursor:not-allowed;">
                <i class="bi bi-x-circle"></i> สินค้าหมด
            </button>
            @endif
        </div>
    </div>
</div>
