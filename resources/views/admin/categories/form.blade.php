@extends('layouts.admin')
@section('title', isset($category) ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($category) ? 'แก้ไขหมวดหมู่' : 'เพิ่มหมวดหมู่' }}</div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:700px;">
<form method="POST" action="{{ isset($category) ? route('admin.categories.update',$category) : route('admin.categories.store') }}">
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
        <div class="form-check"><input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $category->is_active ?? true) ? 'checked' : '' }}><label for="is_active">เปิดใช้งาน</label></div>
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
