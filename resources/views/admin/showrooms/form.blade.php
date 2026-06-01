@extends('layouts.admin')
@section('title', isset($showroom) ? 'แก้ไขสาขา' : 'เพิ่มสาขา')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($showroom) ? 'แก้ไขสาขา' : 'เพิ่มสาขา' }}</div>
    <a href="{{ route('admin.showrooms.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:700px;">
<form method="POST" action="{{ isset($showroom) ? route('admin.showrooms.update',$showroom) : route('admin.showrooms.store') }}" enctype="multipart/form-data">
    @csrf @if(isset($showroom)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-geo-alt"></i> ข้อมูลสาขา</h3>
        <div class="form-group"><label class="form-label">ชื่อสาขา *</label><input type="text" name="name" class="form-control" value="{{ old('name',$showroom->name??'') }}" required></div>
        <div class="form-group"><label class="form-label">ที่อยู่ *</label><textarea name="address" class="form-control" rows="3" required>{{ old('address',$showroom->address??'') }}</textarea></div>
        <div class="form-grid">
            <div class="form-group"><label class="form-label">เบอร์โทร</label><input type="text" name="phone" class="form-control" value="{{ old('phone',$showroom->phone??'') }}"></div>
            <div class="form-group"><label class="form-label">Line ID</label><input type="text" name="line_id" class="form-control" value="{{ old('line_id',$showroom->line_id??'') }}"></div>
            <div class="form-group"><label class="form-label">อีเมล</label><input type="email" name="email" class="form-control" value="{{ old('email',$showroom->email??'') }}"></div>
        </div>
        <div class="form-group"><label class="form-label">เวลาทำการ</label><textarea name="open_hours" class="form-control" rows="3">{{ old('open_hours',$showroom->open_hours??'') }}</textarea></div>
        <div class="form-group"><label class="form-label">Google Maps Embed Code (iframe)</label><textarea name="map_embed_url" class="form-control" rows="3">{{ old('map_embed_url',$showroom->map_embed_url??'') }}</textarea></div>
        <div class="form-group"><label class="form-label">รูปภาพสาขา</label><input type="file" name="image" class="form-control" accept="image/*" style="border-radius:12px;"></div>
        <div class="form-check"><input type="checkbox" name="is_main" id="is_main" value="1" {{ old('is_main',$showroom->is_main??false)?'checked':'' }}><label for="is_main">สาขาหลัก</label></div>
        <div class="form-check"><input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active',$showroom->is_active??true)?'checked':'' }}><label for="is_active">เปิดใช้งาน</label></div>
        <button type="submit" class="btn btn-primary" style="margin-top:16px;"><i class="bi bi-floppy"></i> บันทึก</button>
    </div>
</form>
</div>
@endsection
