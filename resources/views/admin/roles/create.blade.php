@extends('layouts.admin')
@section('title', 'เพิ่มตำแหน่ง')
@section('content')
<div class="page-header">
    <div class="page-title">เพิ่มตำแหน่ง</div>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:760px;">
    <form method="POST" action="{{ route('admin.roles.store') }}">
        @csrf
        <div class="form-card">
            <h3><i class="bi bi-diagram-3"></i> ข้อมูลตำแหน่ง</h3>
            <div class="form-group">
                <label class="form-label">ชื่อตำแหน่ง <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-card" style="margin-top:16px;">
            <h3><i class="bi bi-shield-check"></i> สิทธิ์การใช้งาน</h3>
            @include('admin.roles._permission_checks', ['selected' => old('permissions', [])])
            <button type="submit" class="btn btn-primary" style="margin-top:12px;"><i class="bi bi-floppy"></i> บันทึก</button>
        </div>
    </form>
</div>
@endsection
