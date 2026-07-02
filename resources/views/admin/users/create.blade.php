@extends('layouts.admin')
@section('title', 'เพิ่มพนักงาน')
@section('content')
<div class="page-header">
    <div class="page-title">เพิ่มพนักงาน</div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:520px;">
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf
        <div class="form-card">
            <h3><i class="bi bi-person-plus"></i> ข้อมูลผู้ใช้งาน</h3>
            <div class="form-group">
                <label class="form-label">ชื่อ-นามสกุล <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">อีเมล <span style="color:#e74c3c;">*</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">รหัสผ่าน <span style="color:#e74c3c;">*</span></label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="อย่างน้อย 8 ตัวอักษร" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">ยืนยันรหัสผ่าน <span style="color:#e74c3c;">*</span></label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">ตำแหน่ง <span style="color:#e74c3c;">*</span></label>
                <select name="role" class="form-control @error('role') is-invalid @enderror">
                    @foreach($roles as $r)
                        <option value="{{ $r->name }}" {{ old('role') === $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                    @endforeach
                </select>
                @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
        </div>
    </form>
</div>
@endsection
