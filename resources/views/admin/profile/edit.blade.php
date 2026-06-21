@extends('layouts.admin')
@section('title', 'จัดการข้อมูลส่วนตัว')
@section('content')
<div class="page-header">
    <div class="page-title">จัดการข้อมูลส่วนตัว</div>
</div>

<form method="POST" action="{{ route('admin.profile.update') }}">
    @csrf
    <div class="form-card">
        <h3><i class="bi bi-person"></i> ข้อมูลบัญชี</h3>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">ชื่อ</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">เบอร์โทรศัพท์</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึกข้อมูล</button>
    </div>
</form>

<form method="POST" action="{{ route('admin.profile.password') }}">
    @csrf
    <div class="form-card">
        <h3><i class="bi bi-key"></i> เปลี่ยนรหัสผ่าน</h3>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">รหัสผ่านปัจจุบัน</label>
                <input type="password" name="current_password" class="form-control" autocomplete="current-password">
            </div>
            <div class="form-group">
                <label class="form-label">รหัสผ่านใหม่</label>
                <input type="password" name="password" class="form-control" autocomplete="new-password">
            </div>
            <div class="form-group">
                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
            </div>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-shield-lock"></i> เปลี่ยนรหัสผ่าน</button>
    </div>
</form>
@endsection
