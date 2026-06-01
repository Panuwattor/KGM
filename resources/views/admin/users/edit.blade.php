@extends('layouts.admin')
@section('title', 'แก้ไขสมาชิก')
@section('content')
<div class="page-header">
    <div class="page-title">แก้ไขสมาชิก: {{ $user->name }}</div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:500px;">
<form method="POST" action="{{ route('admin.users.update', $user) }}">
    @csrf @method('PUT')
    <div class="form-card">
        <h3><i class="bi bi-person"></i> สิทธิ์และสถานะ</h3>
        <div class="form-group"><label class="form-label">บทบาท</label>
            <select name="role" class="form-control">
                <option value="customer" {{ $user->role=='customer'?'selected':'' }}>ลูกค้า</option>
                <option value="staff" {{ $user->role=='staff'?'selected':'' }}>พนักงาน</option>
                <option value="admin" {{ $user->role=='admin'?'selected':'' }}>แอดมิน</option>
            </select>
        </div>
        <div class="form-check"><input type="checkbox" name="is_active" id="is_active" value="1" {{ $user->is_active?'checked':'' }}><label for="is_active">บัญชีปกติ</label></div>
        <button type="submit" class="btn btn-primary" style="margin-top:16px;"><i class="bi bi-floppy"></i> บันทึก</button>
    </div>
</form>
</div>
@endsection
