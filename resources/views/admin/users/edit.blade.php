@extends('layouts.admin')
@section('title', 'แก้ไขผู้ใช้งาน')
@section('content')
<div class="page-header">
    <div class="page-title">แก้ไขผู้ใช้งาน: {{ $user->name }}</div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:520px;">
    <form method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf @method('PUT')
        <div class="form-card">
            <h3><i class="bi bi-shield-check"></i> สิทธิ์และสถานะ</h3>
            <div style="background:#f8faf8;border-radius:12px;padding:14px 16px;margin-bottom:20px;font-size:14px;">
                <div style="color:#888;margin-bottom:4px;">บัญชีนี้</div>
                <div style="font-weight:700;">{{ $user->name }}</div>
                <div style="color:#555;">{{ $user->email }}</div>
            </div>
            <div class="form-group">
                <label class="form-label">บทบาท</label>
                <select name="role" class="form-control">
                    <option value="staff" {{ $user->role === 'staff' ? 'selected' : '' }}>พนักงาน (Staff)</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>แอดมิน (Admin)</option>
                </select>
            </div>
            <div class="form-check">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}>
                <label for="is_active">บัญชีปกติ (ปิดเพื่อระงับการเข้าถึง)</label>
            </div>
            <button type="submit" class="btn btn-primary" style="margin-top:20px;"><i class="bi bi-floppy"></i> บันทึก</button>
        </div>
    </form>
</div>
@endsection
