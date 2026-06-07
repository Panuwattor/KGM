@extends('layouts.admin')
@section('title', 'แก้ไขข้อมูลลูกค้า')
@section('content')
<div class="page-header">
    <div>
        <div class="page-title">แก้ไขข้อมูลลูกค้า</div>
        <div class="page-subtitle">{{ $customer->name }}</div>
    </div>
    <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>

<form method="POST" action="{{ route('admin.customers.update', $customer) }}">
    @csrf @method('PUT')
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;align-items:start;">

        <div class="form-card">
            <h3><i class="bi bi-person"></i> ข้อมูลส่วนตัว</h3>
            <div class="form-group">
                <label class="form-label">ชื่อ-นามสกุล <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name', $customer->name) }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">อีเมล</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', $customer->email) }}">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">เบอร์โทร <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                    value="{{ old('phone', $customer->phone) }}" required>
                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">ที่อยู่</label>
                <textarea name="address" class="form-control @error('address') is-invalid @enderror" rows="3">{{ old('address', $customer->address) }}</textarea>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">สถานะ</label>
                <select name="status" class="form-control">
                    <option value="active" {{ old('status', $customer->status) === 'active' ? 'selected' : '' }}>ปกติ</option>
                    <option value="inactive" {{ old('status', $customer->status) === 'inactive' ? 'selected' : '' }}>ระงับ</option>
                </select>
            </div>
        </div>

        <div class="form-card">
            <h3><i class="bi bi-lock"></i> เปลี่ยนรหัสผ่าน</h3>
            <p style="font-size:13px;color:#888;margin-bottom:16px;">เว้นว่างหากไม่ต้องการเปลี่ยนรหัสผ่าน</p>
            <div class="form-group">
                <label class="form-label">รหัสผ่านใหม่</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                    placeholder="อย่างน้อย 8 ตัวอักษร">
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">ยืนยันรหัสผ่านใหม่</label>
                <input type="password" name="password_confirmation" class="form-control">
            </div>
        </div>

    </div>
    <div style="margin-top:4px;">
        <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึกการเปลี่ยนแปลง</button>
        <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-light" style="margin-left:8px;">ยกเลิก</a>
    </div>
</form>
@endsection
