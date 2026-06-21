@extends('layouts.admin')
@section('title', isset($product) ? 'แก้ไขสินค้า' : 'เพิ่มสินค้า')

@section('breadcrumb')
    <span class="sep">/</span>
    <a href="{{ route('admin.products.index') }}">จัดการสินค้า</a>
    <span class="sep">/</span>
    <span class="current">{{ isset($product) ? 'แก้ไข' : 'เพิ่มสินค้า' }}</span>
@endsection

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ isset($product) ? 'แก้ไขสินค้า: '.$product->name : 'เพิ่มสินค้าใหม่' }}</div>
    </div>
    <a href="{{ route('admin.products.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>

<form method="POST" action="{{ isset($product) ? route('admin.products.update', $product) : route('admin.products.store') }}" enctype="multipart/form-data" id="product-form">
    @csrf
    @if(isset($product)) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div>
            {{-- Basic Info --}}
            <div class="form-card">
                <h3><i class="bi bi-info-circle"></i> ข้อมูลสินค้า</h3>
                <div class="form-group">
                    <label class="form-label">ชื่อสินค้า <span style="color:#e74c3c;">*</span></label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name ?? '') }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-grid cols-3">
                    <div class="form-group">
                        <label class="form-label">หมวดหมู่</label>
                        <select name="category_id" class="form-control">
                            <option value="">-- เลือกหมวดหมู่ --</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->parent ? '— ' : '' }}{{ $cat->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ประเภทสินค้า</label>
                        <select name="product_type_id" class="form-control">
                            <option value="">-- เลือกประเภท --</option>
                            @foreach($productTypes as $pt)
                            <option value="{{ $pt->id }}" {{ old('product_type_id', $product->product_type_id ?? '') == $pt->id ? 'selected' : '' }}>
                                {{ $pt->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" value="{{ old('sku', $product->sku ?? '') }}">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">คำอธิบายสั้น</label>
                    <textarea name="short_description" class="form-control" rows="2">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">รายละเอียดสินค้า</label>
                    <textarea name="description" class="form-control" rows="8" id="description">{{ old('description', $product->description ?? '') }}</textarea>
                </div>
            </div>

            {{-- Pricing --}}
            <div class="form-card">
                <h3><i class="bi bi-currency-exchange"></i> ราคา</h3>
                <div class="form-grid cols-3">
                    <div class="form-group">
                        <label class="form-label">ราคาปกติ (บาท) <span style="color:#e74c3c;">*</span></label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0" value="{{ old('price', $product->price ?? '0') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">ราคาพิเศษ (บาท)</label>
                        <input type="number" name="sale_price" class="form-control" step="0.01" min="0" value="{{ old('sale_price', $product->sale_price ?? '') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">ราคาส่ง (บาท)</label>
                        <input type="number" name="wholesale_price" class="form-control" step="0.01" min="0" value="{{ old('wholesale_price', $product->wholesale_price ?? '') }}">
                    </div>
                </div>
            </div>

            {{-- Stock --}}
            <div class="form-card">
                <h3><i class="bi bi-boxes"></i> สต๊อก</h3>
                <div class="form-check" style="margin-bottom:16px;">
                    <input type="checkbox" name="manage_stock" id="manage_stock" value="1" {{ old('manage_stock', $product->manage_stock ?? true) ? 'checked' : '' }}>
                    <label for="manage_stock">จัดการสต๊อก</label>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">จำนวนสต๊อก</label>
                        <input type="number" name="stock_quantity" class="form-control" min="0" value="{{ old('stock_quantity', $product->stock_quantity ?? '0') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">แจ้งเตือนเมื่อสต๊อกต่ำกว่า</label>
                        <input type="number" name="low_stock_threshold" class="form-control" min="0" value="{{ old('low_stock_threshold', $product->low_stock_threshold ?? '5') }}">
                    </div>
                </div>
            </div>

            {{-- Variants --}}
            <div class="form-card" x-data="variantManager({{ json_encode($product->variants ?? []) }})">
                <h3><i class="bi bi-diagram-3"></i> ตัวเลือกสินค้า (Variants)</h3>
                <template x-for="(v, i) in variants" :key="i">
                    <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr 80px auto;gap:8px;margin-bottom:10px;align-items:end;">
                        <div>
                            <label class="form-label" x-show="i===0">ไซส์</label>
                            <input type="text" :name="`variants[${i}][size]`" x-model="v.size" class="form-control" placeholder="S, M, L, XL...">
                        </div>
                        <div>
                            <label class="form-label" x-show="i===0">สี</label>
                            <input type="text" :name="`variants[${i}][color]`" x-model="v.color" class="form-control" placeholder="ดำ, ขาว...">
                        </div>
                        <div>
                            <label class="form-label" x-show="i===0">SKU</label>
                            <input type="text" :name="`variants[${i}][sku]`" x-model="v.sku" class="form-control">
                        </div>
                        <div>
                            <label class="form-label" x-show="i===0">ราคาเพิ่ม (฿)</label>
                            <input type="number" :name="`variants[${i}][price_adjustment]`" x-model="v.price_adjustment" class="form-control" step="0.01">
                        </div>
                        <div>
                            <label class="form-label" x-show="i===0">สต๊อก</label>
                            <input type="number" :name="`variants[${i}][stock_quantity]`" x-model="v.stock_quantity" class="form-control" min="0">
                        </div>
                        <div>
                            <label class="form-label" x-show="i===0" style="visibility:hidden;">ลบ</label>
                            <button type="button" @click="variants.splice(i,1)" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                </template>
                <button type="button" @click="variants.push({size:'',color:'',sku:'',price_adjustment:0,stock_quantity:0})" class="btn btn-sm btn-light">
                    <i class="bi bi-plus-lg"></i> เพิ่มตัวเลือก
                </button>
            </div>

            {{-- SEO --}}
            <div class="form-card">
                <h3><i class="bi bi-search"></i> SEO</h3>
                <div class="form-group">
                    <label class="form-label">URL Slug <small style="color:#aaa;font-weight:400;">(ปล่อยว่างให้ระบบสร้างอัตโนมัติ)</small></label>
                    <div style="display:flex;align-items:center;gap:0;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
                        <span style="background:#f5f7f5;padding:10px 12px;font-size:13px;color:#888;white-space:nowrap;border-right:1px solid #e5e7eb;">/shop/</span>
                        <input type="text" name="slug" class="form-control" style="border:none;border-radius:0;"
                            value="{{ old('slug', $product->slug ?? '') }}"
                            placeholder="auto-generated">
                    </div>
                    @if(isset($product))
                    <div style="font-size:12px;color:#888;margin-top:4px;">
                        URL ปัจจุบัน: <a href="{{ route('shop.show', $product->slug) }}" target="_blank" style="color:var(--admin-primary);">{{ url('/shop/'.$product->slug) }}</a>
                    </div>
                    @endif
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $product->meta_title ?? '') }}" placeholder="ปล่อยว่างใช้ชื่อสินค้า">
                </div>
                <div class="form-group">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="3" placeholder="ปล่อยว่างใช้คำอธิบายย่อ">{{ old('meta_description', $product->meta_description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        <div>
            {{-- Status --}}
            <div class="form-card">
                <h3><i class="bi bi-toggles"></i> สถานะและตัวเลือก</h3>
                <div class="form-check"><input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}><label for="is_active">เปิดใช้งาน</label></div>
                <div class="form-check"><input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured ?? false) ? 'checked' : '' }}><label for="is_featured">สินค้าแนะนำ</label></div>
                <div class="form-check"><input type="checkbox" name="is_new" id="is_new" value="1" {{ old('is_new', $product->is_new ?? false) ? 'checked' : '' }}><label for="is_new">สินค้าใหม่</label></div>
                <div class="form-check"><input type="checkbox" name="is_bestseller" id="is_bestseller" value="1" {{ old('is_bestseller', $product->is_bestseller ?? false) ? 'checked' : '' }}><label for="is_bestseller">สินค้าขายดี</label></div>
                <div class="form-group" style="margin-top:16px;">
                    <label class="form-label">ลำดับการแสดง</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $product->sort_order ?? '0') }}">
                </div>
                <button type="submit" class="btn btn-primary w-full" style="margin-top:12px;justify-content:center;">
                    <i class="bi bi-floppy"></i> {{ isset($product) ? 'บันทึกการแก้ไข' : 'สร้างสินค้า' }}
                </button>
            </div>

            {{-- Images --}}
            @if(isset($product))
            <div class="form-card" x-data="imageManager({{ $product->id }})">
                <h3><i class="bi bi-images"></i> รูปภาพสินค้า</h3>
                <div id="image-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-bottom:12px;">
                    @foreach($product->images as $img)
                    <div style="position:relative;border-radius:12px;overflow:hidden;aspect-ratio:1;">
                        <img src="{{ asset('storage/'.$img->image_path) }}" style="width:100%;height:100%;object-fit:cover;">
                        <button type="button" onclick="deleteImage({{ $product->id }}, {{ $img->id }}, this)"
                            style="position:absolute;top:4px;right:4px;background:rgba(231,76,60,0.9);color:white;border:none;border-radius:999px;width:24px;height:24px;cursor:pointer;font-size:12px;">
                            <i class="bi bi-x"></i>
                        </button>
                        @if($img->is_primary)<div style="position:absolute;bottom:4px;left:4px;background:var(--gold);color:var(--g900);border-radius:999px;padding:1px 8px;font-size:10px;font-weight:700;">หลัก</div>@endif
                    </div>
                    @endforeach
                </div>
                <label style="display:block;padding:20px;border:2px dashed #ddd;border-radius:14px;text-align:center;cursor:pointer;color:#888;transition:all 0.2s;"
                    onmouseover="this.style.borderColor='var(--g500)'" onmouseout="this.style.borderColor='#ddd'">
                    <i class="bi bi-cloud-upload" style="font-size:24px;display:block;margin-bottom:6px;"></i>
                    คลิกเพื่ออัปโหลดรูปภาพ<br><span style="font-size:12px;">PNG, JPG ขนาดสูงสุด 5MB</span>
                    <input type="file" multiple accept="image/*" style="display:none;" onchange="uploadImages({{ $product->id }}, this)">
                </label>
            </div>
            @endif
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function variantManager(initial) {
    return { variants: initial.length ? initial : [] };
}

function uploadImages(productId, input) {
    const fd = new FormData();
    Array.from(input.files).forEach(f => fd.append('images[]', f));
    fd.append('_token', document.querySelector('meta[name=csrf-token]').content);
    fetch(`/admin/products/${productId}/images`, { method: 'POST', body: fd })
        .then(r => r.json()).then(() => location.reload());
}

function deleteImage(productId, imageId, btn) {
    if (!confirm('ลบรูปนี้?')) return;
    fetch(`/admin/products/${productId}/images/${imageId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
    }).then(() => btn.closest('div').remove());
}
</script>
@endpush
