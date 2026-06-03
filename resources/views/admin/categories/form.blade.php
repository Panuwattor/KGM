@extends('layouts.admin')
@section('title', isset($category) ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($category) ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่' }}</div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:700px;">
<form method="POST" enctype="multipart/form-data"
    action="{{ isset($category) ? route('admin.categories.update',$category) : route('admin.categories.store') }}">
    @csrf @if(isset($category)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-tags"></i> ข้อมูลหมวดหมู่</h3>
        <div class="form-group">
            <label class="form-label">ชื่อหมวดหมู่ *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $category->name ?? '') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">หมวดหมู่หลัก (ถ้ามี)</label>
            <select name="parent_id" class="form-control">
                <option value="">-- ไม่มีหมวดหมู่หลัก --</option>
                @foreach($parents as $p)
                <option value="{{ $p->id }}" {{ old('parent_id', $category->parent_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">คำอธิบาย</label>
            <textarea name="description" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea>
        </div>

        {{-- Image Upload --}}
        <div class="form-group" x-data="{
            preview: '{{ isset($category) && $category->image ? asset('storage/'.$category->image) : '' }}',
            hasImage: {{ isset($category) && $category->image ? 'true' : 'false' }},
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
            <label class="form-label">รูปหมวดหมู่</label>

            <div style="display:flex;align-items:flex-start;gap:16px;flex-wrap:wrap;">
                {{-- Preview --}}
                <div x-show="hasImage" style="position:relative;">
                    <img :src="preview" style="width:100px;height:100px;object-fit:cover;border-radius:14px;border:2px solid #e8ecef;">
                    <button type="button" @click="remove()"
                        style="position:absolute;top:-8px;right:-8px;width:22px;height:22px;border-radius:50%;background:#e74c3c;color:white;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:12px;">
                        <i class="bi bi-x"></i>
                    </button>
                </div>

                {{-- Upload zone --}}
                <input type="file" name="image" accept="image/*" x-ref="input" @change="pick($event)" style="display:none;">
                <div x-show="!hasImage">
                    <div @click="$refs.input.click()"
                        @dragover.prevent="$el.style.borderColor='var(--g500)'"
                        @dragleave="$el.style.borderColor='#ddd'"
                        @drop.prevent="pick({target:{files:$event.dataTransfer.files}})"
                        style="width:100px;height:100px;border:2px dashed #ddd;border-radius:14px;cursor:pointer;color:#aaa;transition:all .2s;position:relative;">
                        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;width:100%;">
                            <i class="bi bi-image" style="font-size:24px;display:block;"></i>
                            <span style="font-size:12px;display:block;margin-top:4px;">อัปโหลด</span>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="remove_image" :value="removed ? '1' : '0'">
            <div style="font-size:12px;color:#aaa;margin-top:6px;">JPG, PNG, WEBP ขนาดไม่เกิน 2MB</div>
            @error('image')<div class="invalid-feedback" style="display:block;">{{ $message }}</div>@enderror
        </div>

        <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" value="1"
                {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active">เปิดใช้งาน</label>
        </div>
        <div class="form-group" style="margin-top:16px;">
            <label class="form-label">Meta Title (SEO)</label>
            <input type="text" name="meta_title" class="form-control" value="{{ old('meta_title', $category->meta_title ?? '') }}">
        </div>
        <div class="form-group">
            <label class="form-label">Meta Description (SEO)</label>
            <textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description', $category->meta_description ?? '') }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
    </div>
</form>
</div>
@endsection
