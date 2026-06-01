@extends('layouts.app')
@section('title', ($selectedCategory ? $selectedCategory->name.' - ' : '').'ร้านค้า')

@push('styles')
<style>
.shop-layout { display: grid; grid-template-columns: 260px 1fr; gap: 28px; padding: 32px 0; }
.shop-sidebar { position: sticky; top: 90px; height: fit-content; }
.filter-card { background: white; border-radius: 20px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.06); margin-bottom: 16px; }
.filter-card h4 { font-size: 15px; font-weight: 700; color: var(--kgm-green-800); margin-bottom: 14px; padding-bottom: 10px; border-bottom: 2px solid var(--kgm-green-100); display: flex; align-items: center; gap: 6px; }
.cat-link { display: block; padding: 8px 12px; border-radius: 10px; font-size: 14px; color: #555; transition: all 0.2s; }
.cat-link:hover, .cat-link.active { background: var(--kgm-green-100); color: var(--kgm-green-700); font-weight: 600; }
.cat-sub { padding-left: 20px; }
@media(max-width:900px) { .shop-layout { grid-template-columns: 1fr; } .shop-sidebar { position: static; } }
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
        @endif
    </div>

    <div class="shop-layout">
        {{-- Sidebar --}}
        <aside class="shop-sidebar">
            <div class="filter-card">
                <h4><i class="bi bi-tags"></i> หมวดหมู่</h4>
                <a href="{{ route('shop') }}" class="cat-link {{ !$selectedCategory ? 'active' : '' }}"><i class="bi bi-grid"></i> สินค้าทั้งหมด</a>
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

            <div class="filter-card">
                <h4><i class="bi bi-funnel"></i> กรองสินค้า</h4>
                <form method="GET" action="{{ $selectedCategory ? route('shop.category', $selectedCategory->slug) : route('shop') }}">
                    <div class="form-group">
                        <label class="form-label" style="font-size:13px;">ช่วงราคา (฿)</label>
                        <div style="display:flex;gap:8px;align-items:center;">
                            <input type="number" name="min_price" class="form-control" placeholder="ต่ำสุด" value="{{ request('min_price') }}" style="border-radius:12px;">
                            <span style="color:#aaa;">—</span>
                            <input type="number" name="max_price" class="form-control" placeholder="สูงสุด" value="{{ request('max_price') }}" style="border-radius:12px;">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size:13px;">ไซส์</label>
                        <select name="size" class="form-control" style="border-radius:12px;">
                            <option value="">ทุกไซส์</option>
                            @foreach(['28','30','32','34','36','38','40','42','44','S','M','L','XL','2XL','3XL'] as $size)
                            <option {{ request('size')==$size?'selected':'' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size:13px;">เรียงลำดับ</label>
                        <select name="sort" class="form-control" style="border-radius:12px;">
                            <option value="">ค่าเริ่มต้น</option>
                            <option value="newest" {{ request('sort')=='newest'?'selected':'' }}>สินค้าใหม่สุด</option>
                            <option value="bestseller" {{ request('sort')=='bestseller'?'selected':'' }}>ขายดีสุด</option>
                            <option value="price_asc" {{ request('sort')=='price_asc'?'selected':'' }}>ราคา: ต่ำ → สูง</option>
                            <option value="price_desc" {{ request('sort')=='price_desc'?'selected':'' }}>ราคา: สูง → ต่ำ</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-full" style="justify-content:center;"><i class="bi bi-search"></i> กรองสินค้า</button>
                    <a href="{{ $selectedCategory ? route('shop.category', $selectedCategory->slug) : route('shop') }}" class="btn btn-light w-full" style="justify-content:center;margin-top:6px;"><i class="bi bi-x"></i> ล้างตัวกรอง</a>
                </form>
            </div>
        </aside>

        {{-- Products --}}
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;flex-wrap:wrap;gap:10px;">
                <div>
                    <h1 style="font-size:22px;font-weight:800;color:var(--kgm-green-800);">{{ $selectedCategory ? $selectedCategory->name : 'สินค้าทั้งหมด' }}</h1>
                    <p style="font-size:13px;color:#888;">พบ {{ $products->total() }} รายการ</p>
                </div>
            </div>

            @if($products->isEmpty())
            <div style="text-align:center;padding:80px 0;color:#aaa;">
                <i class="bi bi-box-seam" style="font-size:64px;display:block;margin-bottom:16px;"></i>
                <div style="font-size:18px;font-weight:600;">ไม่พบสินค้า</div>
                <p>ลองปรับตัวกรองหรือค้นหาด้วยคำอื่น</p>
            </div>
            @else
            <div class="grid grid-4" style="grid-template-columns:repeat(4,1fr);">
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
