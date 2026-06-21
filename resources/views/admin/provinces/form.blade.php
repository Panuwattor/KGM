@extends('layouts.admin')
@section('title', isset($province) ? 'แก้ไขจังหวัด' : 'เพิ่มจังหวัด')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($province) ? 'แก้ไขจังหวัด' : 'เพิ่มจังหวัด' }}</div>
    <a href="{{ route('admin.provinces.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:600px;">
<form method="POST"
    action="{{ isset($province) ? route('admin.provinces.update', $province) : route('admin.provinces.store') }}">
    @csrf @if(isset($province)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-geo-alt"></i> ข้อมูลจังหวัด</h3>
        <div class="form-group">
            <label class="form-label">ชื่อจังหวัด (ไทย) *</label>
            <input type="text" name="name_in_thai" class="form-control" value="{{ old('name_in_thai', $province->name_in_thai ?? '') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">ชื่อจังหวัด (อังกฤษ)</label>
            <input type="text" name="name_in_english" class="form-control" value="{{ old('name_in_english', $province->name_in_english ?? '') }}">
        </div>
        <div class="form-group">
            <label class="form-label">รหัสจังหวัด</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $province->code ?? '') }}">
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
    </div>
</form>
</div>
@endsection
