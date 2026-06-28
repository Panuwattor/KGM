@extends('layouts.admin')
@section('title', $coupon ? 'แก้ไขคูปอง' : 'สร้างคูปอง')
@section('content')
<div class="page-header">
    <div class="page-title">{{ $coupon ? 'แก้ไขคูปอง' : 'สร้างคูปอง' }}</div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:800px;">
<form method="POST" action="{{ $coupon ? route('admin.coupons.update',$coupon) : route('admin.coupons.store') }}"
      x-data="couponForm()" enctype="multipart/form-data">
    @csrf @if($coupon) @method('PUT') @endif

    {{-- ── ข้อมูลทั่วไป ── --}}
    <div class="form-card">
        <h3><i class="bi bi-ticket-perforated"></i> ข้อมูลคูปอง</h3>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">โค้ดคูปอง *</label>
                <input type="text" name="code" class="form-control"
                       value="{{ old('code', $coupon?->code ?? '') }}"
                       required style="text-transform:uppercase;" placeholder="SUMMER20">
            </div>
            <div class="form-group">
                <label class="form-label">ชื่อคูปอง *</label>
                <input type="text" name="name" class="form-control"
                       value="{{ old('name', $coupon?->name ?? '') }}"
                       required placeholder="ลด 20% ฤดูร้อน">
            </div>
            <div class="form-group col-span-2">
                <label class="form-label">รูปภาพคูปอง <span style="color:#aaa;font-weight:400;">(.jpg/.png ไม่เกิน 2MB)</span></label>
                <div style="margin-bottom:10px;">
                    <img id="coupon-img-preview"
                         src="{{ $coupon?->image ? media_url($coupon->image) : '' }}"
                         alt="preview"
                         style="{{ $coupon?->image ? '' : 'display:none;' }}height:80px;border-radius:10px;object-fit:cover;border:1px solid #e8ecef;">
                </div>
                <input type="file" name="image" class="form-control" accept="image/*"
                       onchange="previewCouponImg(this)">
            </div>
            <div class="form-group">
                <label class="form-label">ประเภท *</label>
                <select name="type" class="form-control" x-model="type">
                    <option value="percent"       {{ old('type', $coupon?->type ?? 'percent') === 'percent'       ? 'selected' : '' }}>เปอร์เซ็นต์ (%)</option>
                    <option value="fixed"         {{ old('type', $coupon?->type ?? '')        === 'fixed'         ? 'selected' : '' }}>จำนวนบาท (฿)</option>
                    <option value="free_shipping" {{ old('type', $coupon?->type ?? '')        === 'free_shipping' ? 'selected' : '' }}>ส่งฟรี</option>
                </select>
            </div>
            <div class="form-group" x-show="type !== 'free_shipping'">
                <label class="form-label" x-text="type === 'percent' ? 'ส่วนลด (%)' : 'ส่วนลด (฿)'"> </label>
                <input type="number" name="value" class="form-control" step="0.01" min="0"
                       value="{{ old('value', $coupon?->value ?? '0') }}"
                       :required="type !== 'free_shipping'">
            </div>
            <input type="hidden" name="value" value="0" :disabled="type !== 'free_shipping'">
            <div class="form-group">
                <label class="form-label">ยอดสั่งซื้อขั้นต่ำ (฿)</label>
                <input type="number" name="minimum_order" class="form-control" step="0.01" min="0"
                       value="{{ old('minimum_order', $coupon?->minimum_order ?? '0') }}"
                       placeholder="0 = ไม่จำกัด">
            </div>
            <div class="form-group" x-show="type !== 'free_shipping'">
                <label class="form-label">ส่วนลดสูงสุด (฿) <span style="color:#aaa;font-weight:400;">ถ้าเป็น % กำหนดได้</span></label>
                <input type="number" name="maximum_discount" class="form-control" step="0.01" min="0"
                       value="{{ old('maximum_discount', $coupon?->maximum_discount ?? '') }}"
                       placeholder="ไม่จำกัด">
            </div>
        </div>
    </div>

    {{-- ── ระยะเวลา ── --}}
    <div class="form-card">
        <h3><i class="bi bi-calendar3"></i> ระยะเวลา & การใช้งาน</h3>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">วันเริ่มต้น <span style="color:#aaa;font-weight:400;">(แสดงตั้งแต่วันนี้ถ้าว่าง)</span></label>
                <input type="datetime-local" name="starts_at" class="form-control"
                       value="{{ old('starts_at', $coupon?->starts_at?->format('Y-m-d H:i') ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">วันหมดอายุ</label>
                <input type="datetime-local" name="expires_at" class="form-control"
                       value="{{ old('expires_at', $coupon?->expires_at?->format('Y-m-d H:i') ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">จำกัดจำนวนสิทธิ์ทั้งหมด</label>
                <input type="number" name="usage_limit" class="form-control" min="1"
                       value="{{ old('usage_limit', $coupon?->usage_limit ?? '') }}"
                       placeholder="ไม่จำกัด">
            </div>
            <div class="form-group">
                <label class="form-label">ต่อ 1 ผู้ใช้ (ครั้ง)</label>
                <input type="number" name="per_user_limit" class="form-control" min="1"
                       value="{{ old('per_user_limit', $coupon?->per_user_limit ?? '1') }}">
            </div>
        </div>
        <div style="display:flex;gap:24px;flex-wrap:wrap;">
            <div class="form-check">
                <input type="checkbox" name="is_active" id="is_active" value="1"
                       {{ old('is_active', $coupon?->is_active ?? true) ? 'checked' : '' }}>
                <label for="is_active"><i class="bi bi-toggle-on"></i> เปิดใช้งาน</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_public" id="is_public" value="1"
                       {{ old('is_public', $coupon?->is_public ?? true) ? 'checked' : '' }}>
                <label for="is_public"><i class="bi bi-eye"></i> แสดงในหน้าคูปองสาธารณะ</label>
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_stackable" id="is_stackable" value="1"
                       {{ old('is_stackable', $coupon?->is_stackable ?? false) ? 'checked' : '' }}>
                <label for="is_stackable"><i class="bi bi-layers"></i> ใช้คู่กับคูปองอื่นได้</label>
            </div>
        </div>
        <div style="font-size:12px;color:#888;margin-top:8px;">
            <i class="bi bi-info-circle"></i> คูปองที่ติ๊ก "ใช้คู่ได้" จะสามารถใช้ร่วมกับคูปองส่วนลดอีกใบได้พร้อมกัน เช่น คูปองส่งฟรี + คูปองลด %
        </div>
    </div>

    {{-- ── จำกัดเฉพาะสินค้า/หมวดหมู่ (ใช้ได้เฉพาะ percent/fixed) ── --}}
    <div class="form-card" x-show="type !== 'free_shipping'">
        <h3><i class="bi bi-box-seam"></i> จำกัดเฉพาะสินค้า/หมวดหมู่
            <span style="color:#aaa;font-weight:400;font-size:14px;">— ว่างไว้ = ใช้ได้กับทุกสินค้า</span>
        </h3>

        @php
            $selectedCategories = old('category_ids', $coupon?->categories?->pluck('id')->toArray() ?? []);
            $selectedProducts   = old('product_ids',  $coupon?->products?->pluck('id')->toArray()  ?? []);
        @endphp

        {{-- Categories --}}
        @if($categories->isNotEmpty())
        <div style="margin-bottom:20px;">
            <label class="form-label" style="font-weight:700;">
                <i class="bi bi-folder"></i> หมวดหมู่
            </label>
            <div style="display:flex;flex-wrap:wrap;gap:8px;">
                @foreach($categories as $cat)
                <label style="display:flex;align-items:center;gap:6px;background:#f8f9fa;border:1px solid #e0e0e0;border-radius:8px;padding:6px 12px;cursor:pointer;font-size:14px;"
                       :style="categoryIds.includes({{ $cat->id }}) ? 'background:#eaf3ec;border-color:var(--g400);' : ''">
                    <input type="checkbox" name="category_ids[]" :value="{{ $cat->id }}"
                           x-model.number="categoryIds">
                    {{ $cat->name }}
                </label>
                @endforeach
            </div>
            <div style="font-size:12px;color:#888;margin-top:8px;" x-show="categoryIds.length > 0">
                เลือกแล้ว <span x-text="categoryIds.length"></span> / {{ $categories->count() }} หมวดหมู่
            </div>
        </div>
        @endif

        {{-- Products --}}
        @if($products->isNotEmpty())
        <div>
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:10px;flex-wrap:wrap;gap:8px;">
                <label class="form-label" style="font-weight:700;margin-bottom:0;">
                    <i class="bi bi-bag"></i> สินค้าเฉพาะ
                </label>
                <span style="font-size:12px;color:#888;" x-show="productIds.length > 0">
                    เลือก <span x-text="productIds.length" style="font-weight:700;color:var(--g700);"></span> / {{ $products->count() }}
                </span>
            </div>
            <div style="margin-bottom:8px;">
                <input type="text" placeholder="ค้นหาสินค้า..." x-model="productSearch"
                       class="form-control" style="max-width:360px;">
            </div>
            <div style="max-height:220px;overflow-y:auto;border:1px solid #e8ecef;border-radius:12px;padding:10px;display:flex;flex-direction:column;gap:2px;">
                @foreach($products as $prod)
                <label x-show="productSearch === '' || '{{ strtolower($prod->name) }}'.includes(productSearch.toLowerCase())"
                       style="display:flex;align-items:center;gap:8px;padding:6px 8px;border-radius:8px;cursor:pointer;font-size:14px;transition:background .1s;"
                       :style="productIds.includes({{ $prod->id }}) ? 'background:#eaf3ec;' : 'background:transparent;'">
                    <input type="checkbox" name="product_ids[]" :value="{{ $prod->id }}"
                           x-model.number="productIds">
                    {{ $prod->name }}
                </label>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div style="padding:0 0 32px;">
        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
    </div>
</form>
</div>
@endsection

@push('scripts')
<script>
function couponForm() {
    return {
        type: '{{ old('type', $coupon?->type ?? 'percent') }}',
        productIds:   @json($selectedProducts  ?? []),
        categoryIds:  @json($selectedCategories ?? []),
        productSearch: '',
    };
}

function previewCouponImg(input) {
    const preview = document.getElementById('coupon-img-preview');
    if (input.files && input.files[0]) {
        preview.src = URL.createObjectURL(input.files[0]);
        preview.style.display = 'block';
    } else {
        preview.style.display = preview.dataset.original ? 'block' : 'none';
        if (preview.dataset.original) preview.src = preview.dataset.original;
    }
}
</script>
@endpush
