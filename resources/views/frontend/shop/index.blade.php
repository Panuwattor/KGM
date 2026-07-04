@extends('layouts.app')
@section('title', ($selectedCategory ? $selectedCategory->name.' - ' : ($selectedType ? $selectedType->name.' - ' : '')).'ร้านค้า')

@push('styles')
<style>
.shop-layout { display: grid; grid-template-columns: 260px 1fr; gap: 28px; padding: 32px 0; }
.shop-sidebar { position: sticky; top: 90px; height: calc(100vh - 110px); overflow-y: auto; scrollbar-width: thin; scrollbar-color: var(--kgm-green-200) transparent; }
.shop-sidebar::-webkit-scrollbar { width: 4px; }
.shop-sidebar::-webkit-scrollbar-thumb { background: var(--kgm-green-200); border-radius: 4px; }
.filter-card { background: white; border-radius: 20px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); margin-bottom: 16px; }
.filter-card h4 { font-size: 15px; font-weight: 700; color: var(--kgm-green-800); margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid var(--kgm-green-100); display: flex; align-items: center; gap: 6px; }
.cat-link { display: block; padding: 8px 12px; border-radius: 10px; font-size: 14px; color: #555; transition: all 0.2s; }
.cat-link:hover, .cat-link.active { background: var(--kgm-green-100); color: var(--kgm-green-700); font-weight: 600; }
.cat-sub { padding-left: 20px; }

/* Filter bar on top */
.filter-bar { background: white; border-radius: 16px; padding: 14px 18px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); margin-bottom: 20px; }
.filter-bar form { display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap; }
.filter-group { display: flex; flex-direction: column; gap: 4px; }
.filter-group label { font-size: 12px; font-weight: 600; color: var(--kgm-green-800); }
.filter-group .form-control { font-size: 13px; border-radius: 10px; }
.price-range { display: flex; gap: 6px; align-items: center; }
.price-range input { width: 88px; }
.filter-actions { display: flex; gap: 8px; margin-left: auto; align-items: flex-end; }

/* Type pill filter */
.type-pills { display: flex; flex-wrap: wrap; gap: 8px; }
.type-pill {
    display: inline-flex; align-items: center; gap: 6px;
    padding: 7px 14px; border-radius: 999px; font-size: 13px; font-weight: 600;
    text-decoration: none; transition: all 0.2s;
    background: var(--kgm-green-50, #f0faf4);
    color: var(--kgm-green-700, #2d6a4f);
    border: 2px solid var(--kgm-green-100, #d8f3dc);
}
.type-pill:hover { background: var(--kgm-green-100, #d8f3dc); border-color: var(--kgm-green-300, #74c69d); }
.type-pill.active { background: var(--kgm-green-600, #2d6a4f); color: white; border-color: var(--kgm-green-600, #2d6a4f); }

/* Mobile category toggle button */
.cat-toggle-btn { display: none; width: 100%; background: white; border: 2px solid var(--kgm-green-200); border-radius: 14px; padding: 12px 16px; font-size: 14px; font-weight: 700; color: var(--kgm-green-800); cursor: pointer; text-align: left; box-shadow: 0 2px 8px rgba(0,0,0,0.06); margin-bottom: 10px; }
.cat-toggle-btn i.toggle-arrow { margin-left: auto; transition: transform 0.2s; }
.cat-toggle-btn[aria-expanded="true"] i.toggle-arrow { transform: rotate(180deg); }

/* Products grid */
.products-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }

@media(max-width:900px) {
    .shop-layout { grid-template-columns: 1fr; gap: 16px; padding: 16px 0; }
    .shop-sidebar { position: static; height: auto; }
    .cat-toggle-btn { display: flex; align-items: center; gap: 8px; }
    .products-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }

    .filter-bar { padding: 12px; }
    .filter-bar form { flex-direction: row; flex-wrap: wrap; gap: 10px; }
    .filter-group { flex: 1 1 calc(50% - 10px); min-width: 0; }
    .filter-group .form-control { width: 100% !important; min-width: 0 !important; }
    .price-range { gap: 4px; }
    .price-range input { width: 0 !important; flex: 1; min-width: 0; }
    .filter-actions { flex: 1 1 100%; margin-left: 0; display: flex; gap: 8px; }
    .filter-actions .btn { flex: 1; justify-content: center; }
}
@media(min-width:901px) and (max-width:1200px) {
    .products-grid { grid-template-columns: repeat(3, 1fr); }
}
/* On desktop: always show category list regardless of Alpine state */
@media(min-width:901px) {
    .shop-sidebar .block-on-desktop { display: block !important; }
    .cat-toggle-btn { display: none !important; }
}
</style>
@endpush

@section('content')
<div class="container">
    {{-- Breadcrumb --}}
    <div class="breadcrumb">
        <a href="{{ route('home') }}"><i class="bi bi-house"></i></a>
        <span class="breadcrumb-sep">/</span>
        <a href="{{ route('shop') }}">ร้านค้า</a>
        @if($selectedCategory)
            @if($selectedCategory->parent)
            <span class="breadcrumb-sep">/</span>
            <a href="{{ route('shop.category', $selectedCategory->parent->slug) }}">{{ $selectedCategory->parent->name }}</a>
            @endif
            <span class="breadcrumb-sep">/</span>
            <span>{{ $selectedCategory->name }}</span>
        @elseif($selectedType)
            <span class="breadcrumb-sep">/</span>
            <span>{{ $selectedType->name }}</span>
        @endif
    </div>

    <div class="shop-layout">
        {{-- Sidebar: Categories --}}
        <aside class="shop-sidebar" x-data="{ open: false }" :class="{ 'open': open }">
            {{-- Mobile toggle button --}}
            <button class="cat-toggle-btn" @click="open = !open" :aria-expanded="open.toString()">
                <i class="bi bi-tags"></i>
                หมวดหมู่
                @if($selectedCategory)<span style="font-weight:400;font-size:13px;color:var(--kgm-green-600);">— {{ $selectedCategory->name }}</span>@endif
                <i class="bi bi-chevron-down toggle-arrow"></i>
            </button>

            {{-- Category list: always visible on desktop, collapsible on mobile --}}
            <div x-show="open" x-transition:enter="transition-all duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition-all duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="block-on-desktop">
                <div class="filter-card">
                    <h4><i class="bi bi-grid-3x3-gap"></i> ประเภทสินค้า</h4>
                    <div class="type-pills">
                        <a href="{{ route('shop') }}" class="type-pill {{ !$selectedType && !$selectedCategory ? 'active' : '' }}">
                            <i class="bi bi-grid"></i> ทั้งหมด
                        </a>
                        @foreach($productTypes as $pt)
                        <a href="{{ route('shop') }}?type={{ $pt->slug }}" class="type-pill {{ $selectedType?->id === $pt->id ? 'active' : '' }}">
                            {{ $pt->name }}
                        </a>
                        @endforeach
                    </div>
                </div>

                <div class="filter-card">
                    <h4><i class="bi bi-tags"></i> หมวดหมู่</h4>
                    <a href="{{ route('shop') }}" class="cat-link {{ !$selectedCategory && !$selectedType ? 'active' : '' }}"><i class="bi bi-grid"></i> สินค้าทั้งหมด</a>
                    @foreach($categories as $cat)
                    <a href="{{ route('shop.category', $cat->slug) }}" class="cat-link {{ $selectedCategory?->id === $cat->id ? 'active' : '' }}">
                        <i class="bi bi-chevron-right" style="font-size:11px;"></i> {{ $cat->name }}
                    </a>
                    @if($cat->children->isNotEmpty())
                        @foreach($cat->children as $child)
                        <a href="{{ route('shop.category', $child->slug) }}" class="cat-link cat-sub {{ $selectedCategory?->id === $child->id ? 'active' : '' }}">
                            {{ $child->name }}
                        </a>
                        @endforeach
                    @endif
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- Main Content --}}
        <div>
            {{-- Filter Bar (top) --}}
            <div class="filter-bar">
                <form method="GET" action="{{ $selectedCategory ? route('shop.category', $selectedCategory->slug) : route('shop') }}">
                    <div class="filter-group">
                        <label>ช่วงราคา (฿)</label>
                        <div class="price-range">
                            <input type="number" name="min_price" class="form-control" placeholder="ต่ำสุด" value="{{ request('min_price') }}">
                            <span style="color:#aaa;">—</span>
                            <input type="number" name="max_price" class="form-control" placeholder="สูงสุด" value="{{ request('max_price') }}">
                        </div>
                    </div>
                    <div class="filter-group">
                        <label>ไซซ์</label>
                        <select name="size" class="form-control" style="min-width:110px;">
                            <option value="">ทุกไซซ์</option>
                            @foreach(['28','30','32','34','36','38','40','42','44','S','M','L','XL','2XL','3XL'] as $size)
                            <option {{ request('size')==$size?'selected':'' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>เรียงลำดับ</label>
                        <select name="sort" class="form-control" style="min-width:155px;">
                            <option value="">ค่าเริ่มต้น</option>
                            <option value="newest" {{ request('sort')=='newest'?'selected':'' }}>สินค้าใหม่สุด</option>
                            <option value="bestseller" {{ request('sort')=='bestseller'?'selected':'' }}>ขายดีสุด</option>
                            <option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>ราคา: ต่ำ → สูง</option>
                            <option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>ราคา: สูง → ต่ำ</option>
                        </select>
                    </div>
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> กรองสินค้า</button>
                        <a href="{{ $selectedCategory ? route('shop.category', $selectedCategory->slug) : route('shop') }}" class="btn btn-light"><i class="bi bi-x"></i> ล้าง</a>
                    </div>
                </form>
            </div>

            {{-- Page Header --}}
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
                <div>
                    <h1 style="font-size:22px;font-weight:800;color:var(--kgm-green-800);">
                        @if($selectedCategory)
                            {{ $selectedCategory->name }}
                        @elseif($selectedType)
                            <i class="bi bi-grid-3x3-gap" style="font-size:18px;margin-right:6px;"></i>{{ $selectedType->name }}
                        @elseif(request('filter') === 'featured')
                            <i class="bi bi-star-fill" style="font-size:18px;margin-right:6px;color:var(--kgm-gold-400,#c9a84c);"></i>สินค้าแนะนำ
                        @elseif(request('filter') === 'new')
                            <i class="bi bi-stars" style="font-size:18px;margin-right:6px;color:var(--kgm-green-600);"></i>สินค้าใหม่
                        @elseif(request('filter') === 'bestseller')
                            <i class="bi bi-fire" style="font-size:18px;margin-right:6px;color:#e74c3c;"></i>สินค้าขายดี
                        @else
                            สินค้าทั้งหมด
                        @endif
                    </h1>
                    <p style="font-size:13px;color:#888;">พบ {{ $products->total() }} รายการ</p>
                </div>
                @if($selectedType)
                <a href="{{ route('shop') }}" class="btn btn-light" style="font-size:13px;"><i class="bi bi-x"></i> ล้างประเภท</a>
                @endif
            </div>

            {{-- Products --}}
            @if($products->isEmpty())
            <div style="text-align:center;padding:80px 0;color:#aaa;">
                <i class="bi bi-box-seam" style="font-size:64px;display:block;margin-bottom:16px;"></i>
                <div style="font-size:18px;font-weight:600;">ไม่พบสินค้า</div>
                <p>ลองปรับตัวกรองหรือค้นหาด้วยคำอื่น</p>
            </div>
            @else
            <div class="products-grid">
                @foreach($products as $product)
                @include('components.product-card')
                @endforeach
            </div>
            <div class="pagination">{{ $products->links() }}</div>
            @endif
        </div>
    </div>
</div>
@endsection
