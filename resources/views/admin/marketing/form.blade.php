@extends('layouts.admin')
@section('title', isset($flashSale) ? 'แก้ไข Flash Sale' : 'สร้าง Flash Sale')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($flashSale) ? 'แก้ไข Flash Sale' : 'สร้าง Flash Sale' }}</div>
    <a href="{{ route('admin.marketing.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<form method="POST" action="{{ isset($flashSale) ? route('admin.flash-sales.update',$flashSale) : route('admin.flash-sales.store') }}" enctype="multipart/form-data">
    @csrf @if(isset($flashSale)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-lightning-charge"></i> ข้อมูล Flash Sale</h3>
        <div class="form-grid">
            <div class="form-group"><label class="form-label">ชื่อ Flash Sale *</label><input type="text" name="name" class="form-control" value="{{ old('name', ($flashSale ?? null)?->name ?? '') }}" required></div>
            <div class="form-group col-span-full">
                <label class="form-label"><i class="bi bi-image"></i> รูป Banner Flash Sale <span style="color:#aaa;font-size:12px;">(บังคับขนาด 1200×400px เท่านั้น, ไม่เกิน 3MB)</span></label>
                @if(($flashSale ?? null)?->image)
                <div style="margin-bottom:10px;">
                    <img src="{{ media_url($flashSale->image) }}" alt="Banner" style="max-width:360px;border-radius:10px;border:1px solid #e0e0e0;">
                    <div style="font-size:12px;color:#888;margin-top:4px;">อัปโหลดรูปใหม่เพื่อเปลี่ยน</div>
                </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*" onchange="checkBannerSize(this)">
                @error('image')<div style="color:#dc2626;font-size:13px;margin-top:6px;">{{ $message }}</div>@enderror
                <div id="bannerSizeError" style="color:#dc2626;font-size:13px;margin-top:6px;display:none;"></div>
            </div>
            <div class="form-group col-span-full" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div><label class="form-label">เวลาเริ่มต้น *</label><input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', ($flashSale ?? null)?->starts_at?->format('Y-m-d H:i') ?? '') }}" required></div>
                <div><label class="form-label">เวลาสิ้นสุด *</label><input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', ($flashSale ?? null)?->ends_at?->format('Y-m-d H:i') ?? '') }}" required></div>
            </div>
        </div>
        <div class="form-check"><input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', ($flashSale ?? null)?->is_active ?? true) ? 'checked' : '' }}><label for="is_active">เปิดใช้งาน</label></div>
    </div>
    @php
        $existingItems = isset($flashSale) ? $flashSale->items->keyBy('product_id') : collect();
        $productsData = $products->map(function ($p) use ($existingItems) {
            $item = $existingItems->get($p->id);
            $old = old('products.' . $p->id);
            return [
                'id'          => $p->id,
                'name'        => $p->name,
                'price'       => (float) $p->price,
                'category'    => $p->category?->name ?? 'ไม่มีหมวดหมู่',
                'image'       => $p->images->first() ? media_url($p->images->first()->image_path) : null,
                'selected'    => $old ? !empty($old['selected']) : (bool) $item,
                'sale_price'  => $old['price'] ?? ($item?->sale_price ?? ''),
                'stock_limit' => $old['stock_limit'] ?? ($item?->stock_limit ?? ''),
            ];
        })->values();
        $categories = $productsData->pluck('category')->unique()->sort()->values();
    @endphp
    <div class="form-card" x-data="flashSaleProducts()">
        <h3><i class="bi bi-box-seam"></i> เลือกสินค้าใน Flash Sale</h3>

        {{-- ตัวเลือก: ค้นหา + ฟิลเตอร์หมวดหมู่ --}}
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-bottom:12px;">
            <div style="position:relative;flex:1;min-width:220px;">
                <i class="bi bi-search" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#aaa;"></i>
                <input type="text" x-model="search" placeholder="ค้นหาชื่อสินค้า..." class="form-control" style="padding-left:36px;border-radius:10px;">
            </div>
            <select x-model="category" class="form-control" style="width:auto;min-width:180px;border-radius:10px;">
                <option value="">ทุกหมวดหมู่</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}">{{ $cat }}</option>
                @endforeach
            </select>
            <span style="color:#888;font-size:13px;" x-text="`เลือกแล้ว ${selectedCount} รายการ`"></span>
        </div>

        {{-- รายการสินค้าให้เลือก (คลิกเพื่อเพิ่ม) --}}
        <div style="max-height:300px;overflow-y:auto;border:1px solid #eee;border-radius:12px;padding:6px;">
            <template x-for="p in filtered" :key="p.id">
                <div @click="toggle(p)" x-data="{ hover: false }" @mouseenter="hover=true" @mouseleave="hover=false"
                     style="display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:10px;cursor:pointer;transition:background .15s;"
                     :style="{ background: p.selected ? '#eef6ff' : (hover ? '#f5f5f5' : 'transparent') }">
                    <img :src="p.image" x-show="p.image" style="width:38px;height:38px;object-fit:cover;border-radius:8px;flex-shrink:0;" onerror="this.style.display='none'">
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:14px;font-weight:500;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;" x-text="p.name"></div>
                        <div style="font-size:12px;color:#999;" x-text="`${p.category} · ฿${p.price.toLocaleString()}`"></div>
                    </div>
                    <i class="bi" :class="p.selected ? 'bi-check-circle-fill' : 'bi-plus-circle'" style="font-size:20px;flex-shrink:0;" :style="{ color: p.selected ? '#2563eb' : '#bbb' }"></i>
                </div>
            </template>
            <div x-show="filtered.length === 0" style="text-align:center;color:#aaa;padding:24px;">ไม่พบสินค้า</div>
        </div>

        {{-- ตารางสินค้าที่เลือก: กรอกราคา + จำนวน --}}
        <div style="margin-top:18px;" x-show="selectedCount > 0" x-cloak>
            <h4 style="margin-bottom:8px;font-size:15px;"><i class="bi bi-list-check"></i> สินค้าที่เลือก (<span x-text="selectedCount"></span>)</h4>
            <table style="width:100%;">
                <thead><tr>
                    <th>สินค้า</th>
                    <th>ราคาปกติ (ฐาน)</th>
                    <th>ราคา Flash Sale * <small style="font-weight:400;color:#aaa;">(ก่อนบวกค่าไซส์)</small></th>
                    <th>จำนวนจำกัด</th>
                    <th></th>
                </tr></thead>
                <tbody>
                <template x-for="p in selectedProducts" :key="p.id">
                    <tr>
                        <td>
                            <input type="hidden" :name="`products[${p.id}][selected]`" value="1">
                            <span x-text="p.name"></span>
                        </td>
                        <td x-text="`฿${p.price.toLocaleString()}`"></td>
                        <td><input type="number" :name="`products[${p.id}][price]`" x-model="p.sale_price" class="form-control" style="width:120px;border-radius:10px;" placeholder="ราคา Flash" step="0.01" required></td>
                        <td><input type="number" :name="`products[${p.id}][stock_limit]`" x-model="p.stock_limit" class="form-control" style="width:100px;border-radius:10px;" placeholder="ไม่จำกัด"></td>
                        <td><button type="button" @click="toggle(p)" class="btn btn-light btn-sm" style="color:#dc2626;"><i class="bi bi-x-lg"></i></button></td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
        <div x-show="selectedCount === 0" x-cloak style="margin-top:14px;color:#aaa;font-size:14px;">ยังไม่ได้เลือกสินค้า — คลิกสินค้าจากรายการด้านบนเพื่อเพิ่ม</div>
    </div>

    <script>
    function checkBannerSize(input) {
        const errBox = document.getElementById('bannerSizeError');
        errBox.style.display = 'none';
        errBox.textContent = '';
        const file = input.files && input.files[0];
        if (!file) return;
        const img = new Image();
        img.onload = function () {
            URL.revokeObjectURL(img.src);
            if (img.naturalWidth !== 1200 || img.naturalHeight !== 400) {
                errBox.textContent = `รูป Banner ต้องมีขนาด 1200×400px เท่านั้น (ไฟล์นี้ ${img.naturalWidth}×${img.naturalHeight}px)`;
                errBox.style.display = 'block';
                input.value = '';
            }
        };
        img.src = URL.createObjectURL(file);
    }

    function flashSaleProducts() {
        return {
            search: '',
            category: '',
            products: @json($productsData),
            get filtered() {
                const s = this.search.trim().toLowerCase();
                return this.products.filter(p =>
                    (!this.category || p.category === this.category) &&
                    (!s || p.name.toLowerCase().includes(s))
                );
            },
            get selectedProducts() {
                return this.products.filter(p => p.selected);
            },
            get selectedCount() {
                return this.selectedProducts.length;
            },
            toggle(p) {
                p.selected = !p.selected;
            },
        };
    }
    </script>
    <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-floppy"></i> บันทึก Flash Sale</button>
</form>
@endsection
