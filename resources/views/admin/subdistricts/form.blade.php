@extends('layouts.admin')
@section('title', isset($subdistrict) ? 'แก้ไขตำบล' : 'เพิ่มตำบล')
@section('content')
<div class="page-header">
    <div>
        <div class="page-subtitle" style="margin-bottom:4px;">
            <a href="{{ route('admin.provinces.index') }}" style="color:var(--g600);text-decoration:none;">จังหวัด</a>
            <i class="bi bi-chevron-right" style="font-size:10px;"></i>
            <a href="{{ route('admin.provinces.districts.index', $province) }}" style="color:var(--g600);text-decoration:none;">{{ $province->name_in_thai }}</a>
            <i class="bi bi-chevron-right" style="font-size:10px;"></i>
            <a href="{{ route('admin.districts.subdistricts.index', $district) }}" style="color:var(--g600);text-decoration:none;">{{ $district->name_in_thai }}</a>
        </div>
        <div class="page-title">{{ isset($subdistrict) ? 'แก้ไขตำบล' : 'เพิ่มตำบล' }}</div>
    </div>
    <a href="{{ route('admin.districts.subdistricts.index', $district) }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:700px;">
<form method="POST"
    action="{{ isset($subdistrict) ? route('admin.subdistricts.update', $subdistrict) : route('admin.districts.subdistricts.store', $district) }}">
    @csrf @if(isset($subdistrict)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-pin-map"></i> ข้อมูลตำบล — {{ $district->name_in_thai }}</h3>
        <div class="form-grid">
            <div class="form-group col-span-full">
                <label class="form-label">ชื่อตำบล (ไทย) *</label>
                <input type="text" name="name_in_thai" class="form-control" value="{{ old('name_in_thai', $subdistrict->name_in_thai ?? '') }}" required>
            </div>
            <div class="form-group col-span-full">
                <label class="form-label">ชื่อตำบล (อังกฤษ)</label>
                <input type="text" name="name_in_english" class="form-control" value="{{ old('name_in_english', $subdistrict->name_in_english ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">รหัสตำบล</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $subdistrict->code ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">รหัสไปรษณีย์</label>
                <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', $subdistrict->zip_code ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Latitude</label>
                <input type="text" name="latitude" class="form-control" value="{{ old('latitude', $subdistrict->latitude ?? '') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Longitude</label>
                <input type="text" name="longitude" class="form-control" value="{{ old('longitude', $subdistrict->longitude ?? '') }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
    </div>
</form>
</div>
@endsection
