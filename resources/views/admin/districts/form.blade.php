@extends('layouts.admin')
@section('title', isset($district) ? 'แก้ไขอำเภอ' : 'เพิ่มอำเภอ')
@section('content')
<div class="page-header">
    <div>
        <div class="page-subtitle" style="margin-bottom:4px;">
            <a href="{{ route('admin.provinces.index') }}" style="color:var(--g600);text-decoration:none;">จังหวัด</a>
            <i class="bi bi-chevron-right" style="font-size:10px;"></i>
            <a href="{{ route('admin.provinces.districts.index', $province) }}" style="color:var(--g600);text-decoration:none;">{{ $province->name_in_thai }}</a>
        </div>
        <div class="page-title">{{ isset($district) ? 'แก้ไขอำเภอ' : 'เพิ่มอำเภอ' }}</div>
    </div>
    <a href="{{ route('admin.provinces.districts.index', $province) }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:600px;">
<form method="POST"
    action="{{ isset($district) ? route('admin.districts.update', $district) : route('admin.provinces.districts.store', $province) }}">
    @csrf @if(isset($district)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-diagram-3"></i> ข้อมูลอำเภอ — {{ $province->name_in_thai }}</h3>
        <div class="form-group">
            <label class="form-label">ชื่ออำเภอ (ไทย) *</label>
            <input type="text" name="name_in_thai" class="form-control" value="{{ old('name_in_thai', $district->name_in_thai ?? '') }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">ชื่ออำเภอ (อังกฤษ)</label>
            <input type="text" name="name_in_english" class="form-control" value="{{ old('name_in_english', $district->name_in_english ?? '') }}">
        </div>
        <div class="form-group">
            <label class="form-label">รหัสอำเภอ</label>
            <input type="text" name="code" class="form-control" value="{{ old('code', $district->code ?? '') }}">
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
    </div>
</form>
</div>
@endsection
