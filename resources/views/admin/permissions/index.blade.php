@extends('layouts.admin')
@section('title', 'จัดการสิทธิ์')
@section('content')
<div class="page-header">
    <div>
        <div class="page-title">จัดการสิทธิ์</div>
        <div class="page-subtitle">รายการสิทธิ์การใช้งานที่นำไปกำหนดให้ตำแหน่งต่างๆ</div>
    </div>
</div>
<div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;">
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>ชื่อสิทธิ์</th><th>ใช้งานในตำแหน่ง</th><th></th></tr>
            </thead>
            <tbody>
            @forelse($permissions as $permission)
            <tr>
                <td style="font-weight:700;">{{ $permission->name }}</td>
                <td>{{ $permission->roles_count }} ตำแหน่ง</td>
                <td>
                    <form method="POST" action="{{ route('admin.permissions.destroy', $permission) }}"
                        onsubmit="return confirm('ลบสิทธิ์ {{ $permission->name }}?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;padding:32px;color:#aaa;">ไม่พบสิทธิ์</td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="padding:16px;">{{ $permissions->links() }}</div>
    </div>
    <div class="form-card">
        <h3><i class="bi bi-plus-lg"></i> เพิ่มสิทธิ์ใหม่</h3>
        <form method="POST" action="{{ route('admin.permissions.store') }}">
            @csrf
            <div class="form-group">
                <label class="form-label">ชื่อสิทธิ์ <span style="color:#e74c3c;">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                    value="{{ old('name') }}" placeholder="เช่น view products" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
        </form>
    </div>
</div>
@endsection
