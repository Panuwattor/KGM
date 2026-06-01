@extends('layouts.admin')
@section('title', 'จัดการสินค้า')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">จัดการสินค้า</div>
        <div class="page-subtitle">สินค้าทั้งหมด {{ $products->total() }} รายการ</div>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มสินค้าใหม่</a>
</div>

<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:2;">
        <label>ค้นหา</label>
        <input type="text" name="search" class="form-control" placeholder="ชื่อสินค้า, SKU..." value="{{ request('search') }}">
    </div>
    <div class="filter-item">
        <label>หมวดหมู่</label>
        <select name="category_id" class="form-control">
            <option value="">ทั้งหมด</option>
            @foreach($categories as $cat)
            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="filter-item">
        <label>สถานะ</label>
        <select name="status" class="form-control">
            <option value="">ทั้งหมด</option>
            <option value="active" {{ request('status')=='active' ? 'selected' : '' }}>เปิดใช้งาน</option>
            <option value="inactive" {{ request('status')=='inactive' ? 'selected' : '' }}>ปิดใช้งาน</option>
            <option value="low_stock" {{ request('status')=='low_stock' ? 'selected' : '' }}>สต๊อกใกล้หมด</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-light"><i class="bi bi-x-lg"></i></a>
</form>

<div class="table-wrap">
    <table>
        <thead><tr>
            <th width="60">รูป</th><th>ชื่อสินค้า</th><th>หมวดหมู่</th><th>ราคา</th>
            <th>สต๊อก</th><th>สถานะ</th><th width="130">จัดการ</th>
        </tr></thead>
        <tbody>
        @forelse($products as $product)
        <tr>
            <td>
                @if($product->images->first())
                    <img src="{{ asset('storage/'.$product->images->first()->image_path) }}" style="width:50px;height:50px;object-fit:cover;border-radius:10px;">
                @else
                    <div style="width:50px;height:50px;background:#f0f4f0;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#ccc;"><i class="bi bi-image"></i></div>
                @endif
            </td>
            <td>
                <div style="font-weight:700;">{{ $product->name }}</div>
                <div style="font-size:12px;color:#888;">SKU: {{ $product->sku ?? '-' }}</div>
                <div style="display:flex;gap:4px;margin-top:4px;">
                    @if($product->is_new)<span class="status-badge status-green" style="font-size:10px;">ใหม่</span>@endif
                    @if($product->is_featured)<span class="status-badge status-blue" style="font-size:10px;">แนะนำ</span>@endif
                    @if($product->is_bestseller)<span class="status-badge" style="background:#fef9c3;color:#854d0e;font-size:10px;">ขายดี</span>@endif
                </div>
            </td>
            <td style="font-size:13px;">{{ $product->category?->name ?? '-' }}</td>
            <td>
                <div style="font-weight:700;color:var(--g600);">฿{{ number_format($product->current_price, 0) }}</div>
                @if($product->sale_price)
                    <div style="font-size:12px;color:#aaa;text-decoration:line-through;">฿{{ number_format($product->price, 0) }}</div>
                @endif
            </td>
            <td>
                @if($product->isLowStock())
                    <span style="color:#e74c3c;font-weight:700;">{{ $product->stock_quantity }} <i class="bi bi-exclamation-triangle-fill"></i></span>
                @else
                    <span style="font-weight:600;">{{ $product->stock_quantity }}</span>
                @endif
            </td>
            <td>
                <span class="status-badge {{ $product->is_active ? 'status-green' : 'status-red' }}">
                    {{ $product->is_active ? 'เปิดใช้งาน' : 'ปิดใช้งาน' }}
                </span>
            </td>
            <td>
                <div style="display:flex;gap:6px;">
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-light" title="แก้ไข"><i class="bi bi-pencil"></i></a>
                    <a href="{{ route('shop.show', $product->slug) }}" class="btn btn-sm btn-light" target="_blank" title="ดูหน้าเว็บ"><i class="bi bi-eye"></i></a>
                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('ลบสินค้านี้?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="ลบ"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:40px;color:#aaa;"><i class="bi bi-box-seam" style="font-size:32px;display:block;margin-bottom:8px;"></i>ไม่พบสินค้า</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px 20px;">{{ $products->links() }}</div>
</div>
@endsection
