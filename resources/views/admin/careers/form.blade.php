@extends('layouts.admin')
@section('title', isset($career) ? 'แก้ไขตำแหน่ง' : 'เพิ่มตำแหน่งงาน')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($career) ? 'แก้ไขตำแหน่ง' : 'เพิ่มตำแหน่งงาน' }}</div>
    <a href="{{ route('admin.careers.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:800px;">
<form method="POST" action="{{ isset($career) ? route('admin.careers.update',$career) : route('admin.careers.store') }}">
    @csrf @if(isset($career)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-briefcase"></i> ข้อมูลตำแหน่งงาน</h3>
        <div class="form-grid">
            <div class="form-group"><label class="form-label">ชื่อตำแหน่ง *</label><input type="text" name="title" class="form-control" value="{{ old('title',$career->title??'') }}" required></div>
            <div class="form-group"><label class="form-label">แผนก</label><input type="text" name="department" class="form-control" value="{{ old('department',$career->department??'') }}"></div>
            <div class="form-group"><label class="form-label">ประเภท</label>
                <select name="type" class="form-control">
                    <option value="full_time" {{ old('type',$career->type??'full_time')=='full_time'?'selected':'' }}>ประจำ</option>
                    <option value="part_time" {{ old('type',$career->type??'')=='part_time'?'selected':'' }}>นอกเวลา</option>
                    <option value="contract" {{ old('type',$career->type??'')=='contract'?'selected':'' }}>สัญญาจ้าง</option>
                </select>
            </div>
            <div class="form-group"><label class="form-label">สถานที่</label><input type="text" name="location" class="form-control" value="{{ old('location',$career->location??'') }}"></div>
            <div class="form-group"><label class="form-label">จำนวนอัตรา</label><input type="number" name="vacancies" class="form-control" min="1" value="{{ old('vacancies',$career->vacancies??1) }}"></div>
            <div class="form-group"><label class="form-label">ปิดรับสมัคร</label><input type="date" name="closes_at" class="form-control" value="{{ old('closes_at',$career->closes_at?->format('Y-m-d')??'') }}"></div>
        </div>
        <div class="form-group"><label class="form-label">รายละเอียดงาน *</label><textarea name="description" class="form-control" rows="5" required>{{ old('description',$career->description??'') }}</textarea></div>
        <div class="form-group"><label class="form-label">คุณสมบัติที่ต้องการ</label><textarea name="requirements" class="form-control" rows="5">{{ old('requirements',$career->requirements??'') }}</textarea></div>
        <div class="form-group"><label class="form-label">สวัสดิการ</label><textarea name="benefits" class="form-control" rows="4">{{ old('benefits',$career->benefits??'') }}</textarea></div>
        <div class="form-check"><input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active',$career->is_active??true)?'checked':'' }}><label for="is_active">เปิดรับสมัคร</label></div>
        <button type="submit" class="btn btn-primary" style="margin-top:16px;"><i class="bi bi-floppy"></i> บันทึก</button>
    </div>
</form>
</div>
@endsection
