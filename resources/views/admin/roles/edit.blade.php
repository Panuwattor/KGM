@extends('layouts.admin')
@section('title', 'แก้ไขตำแหน่ง')
@section('content')
<div class="page-header">
    <div class="page-title">แก้ไขตำแหน่ง: {{ $role->name }}</div>
    <a href="{{ route('admin.roles.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:760px;">
    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf @method('PUT')
        <div class="form-card">
            <h3><i class="bi bi-diagram-3"></i> ข้อมูลตำแหน่ง</h3>
            <div class="form-group">
                <label class="form-label">ชื่อตำแหน่ง <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $role->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="form-card" style="margin-top:16px;">
            <h3><i class="bi bi-shield-check"></i> สิทธิ์การใช้งาน</h3>
            @include('admin.roles._permission_checks', ['selected' => old('permissions', $rolePermissions)])
            <button type="submit" class="btn btn-primary" style="margin-top:12px;"><i class="bi bi-floppy"></i> บันทึก</button>
        </div>
    </form>
</div>
@endsection
