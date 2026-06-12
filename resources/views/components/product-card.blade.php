<div class="product-card">
    <div class="product-badges">
        @if($product->is_new)<span class="badge badge-new">ใหม่</span>@endif
        @if($product->is_bestseller)<span class="badge badge-hot">ขายดี</span>@endif
        @if($product->sale_price)<span class="badge badge-sale">-{{ $product->discount_percent }}%</span>@endif
    </div>
    <div class="card">
        <div class="product-img-wrap">
            <a href="{{ route('shop.show', $product->slug) }}" style="display:block;line-height:0;">
                <img src="{{ $product->main_image ? asset('storage/'.$product->main_image) : asset('images/logo.png') }}"
                    alt="{{ $product->name }}" class="product-img" loading="lazy"
                    onerror="this.onerror=null;this.src='/images/logo.png'">
            </a>
            @auth('customer')
            @php $inWishlist = auth('customer')->user()->wishlists()->where('product_id', $product->id)->exists(); @endphp
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
                <i class="bi bi-cart-plus"></i> เลือกไซส์
            </a>
            @else
            <button disabled class="btn product-add-btn" style="background:#f0f0f0;color:#bbb;cursor:not-allowed;">
                <i class="bi bi-x-circle"></i> สินค้าหมด
            </button>
            @endif
        </div>
    </div>
</div>
