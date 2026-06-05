@extends('layouts.admin')
@section('title', isset($productType) ? 'แก้ไขประเภทสินค้า' : 'เพิ่มประเภทสินค้า')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($productType) ? 'แก้ไขประเภทสินค้า' : 'เพิ่มประเภทสินค้า' }}</div>
    <a href="{{ route('admin.product-types.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:700px;">
<form method="POST" enctype="multipart/form-data"
    action="{{ isset($productType) ? route('admin.product-types.update', $productType) : route('admin.product-types.store') }}">
    @csrf @if(isset($productType)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-grid-3x3-gap"></i> ข้อมูลประเภทสินค้า</h3>
        <div class="form-group">
            <label class="form-label">ชื่อประเภท *</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $productType->name ?? '') }}" required placeholder="เช่น เสื้อ, กางเกง, กระเป๋า">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="form-group">
            <label class="form-label">คำอธิบาย</label>
            <textarea name="description" class="form-control" rows="3" placeholder="คำอธิบายสั้นๆ ของประเภทสินค้า">{{ old('description', $productType->description ?? '') }}</textarea>
        </div>

        {{-- Image Upload --}}
        <div class="form-group" x-data="{
            preview: '{{ isset($productType) && $productType->image ? asset('storage/'.$productType->image) : '' }}',
            hasImage: {{ isset($productType) && $productType->image ? 'true' : 'false' }},
            removed: false,
            pick(e) {
                const file = e.target.files[0];
                if (!file) return;
                this.preview = URL.createObjectURL(file);
                this.hasImage = true;
                this.removed = false;
            },
            remove() {
                this.preview = '';
                this.hasImage = false;
                this.removed = true;
                this.$refs.input.value = '';
            }
        }">
            <label class="form-label">รูปประเภทสินค้า <small style="color:#aaa;font-weight:400;">(แนะนำ 400×400 px)</small></label>
            <div style="display:flex;align-items:flex-start;gap:16px;flex-wrap:wrap;">
                <div x-show="hasImage" style="position:relative;">
                    <img :src="preview" style="width:120px;height:120px;object-fit:cover;border-radius:16px;border:2px solid #e8ecef;">
                    <button type="button" @click="remove()"
                        style="position:absolute;top:-8px;right:-8px;width:22px;height:22px;border-radius:50%;background:#e74c3c;color:white;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
                <input type="file" name="image" accept="image/*" x-ref="input" @change="pick($event)" style="display:none;">
                <div x-show="!hasImage">
                    <div @click="$refs.input.click()"
                        @dragover.prevent="$el.style.borderColor='var(--g500)'"
                        @dragleave="$el.style.borderColor='#ddd'"
                        @drop.prevent="pick({target:{files:$event.dataTransfer.files}})"
                        style="width:120px;height:120px;border:2px dashed #ddd;border-radius:16px;cursor:pointer;color:#aaa;transition:all .2s;position:relative;">
                        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;width:100%;">
                            <i class="bi bi-image" style="font-size:28px;display:block;"></i>
                            <span style="font-size:12px;display:block;margin-top:4px;">อัปโหลด</span>
                        </div>
                    </div>
                </div>
            </div>
            <input type="hidden" name="remove_image" :value="removed ? '1' : '0'">
            <div style="font-size:12px;color:#aaa;margin-top:6px;">JPG, PNG, WEBP ขนาดไม่เกิน 2MB</div>
            @error('image')<div class="invalid-feedback" style="display:block;">{{ $message }}</div>@enderror
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">ลำดับการแสดง</label>
                <input type="number" name="sort_order" class="form-control" min="0"
                    value="{{ old('sort_order', $productType->sort_order ?? 0) }}">
            </div>
        </div>

        <div class="form-check">
            <input type="checkbox" name="show_on_home" id="show_on_home" value="1"
                {{ old('show_on_home', $productType->show_on_home ?? true) ? 'checked' : '' }}>
            <label for="show_on_home">แสดงในหน้าแรก</label>
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $productType->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active">เปิดใช้งาน</label>
        </div>

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
        </div>
    </div>
</form>
</div>
@endsection
