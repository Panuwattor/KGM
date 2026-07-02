@extends('layouts.admin')
@section('title', 'จัดการตำแหน่ง')
@section('content')
<div class="page-header">
    <div>
        <div class="page-title">จัดการตำแหน่ง</div>
        <div class="page-subtitle">กำหนดตำแหน่งและสิทธิ์การใช้งานสำหรับพนักงาน</div>
    </div>
    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> เพิ่มตำแหน่ง
    </a>
</div>
<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:2;">
        <label>ค้นหา</label>
        <input type="text" name="search" class="form-control" placeholder="ชื่อตำแหน่ง" value="{{ request('search') }}">
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
    @if(request()->filled('search'))
        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">ล้าง</a>
    @endif
</form>
<div class="table-wrap">
    <table>
        <thead>
            <tr><th>ตำแหน่ง</th><th>จำนวนสิทธิ์</th><th>จำนวนพนักงาน</th><th></th></tr>
        </thead>
        <tbody>
        @forelse($roles as $role)
        <tr>
            <td style="font-weight:700;">{{ $role->name }}</td>
            <td>{{ $role->permissions->count() }} สิทธิ์</td>
            <td>{{ $role->users_count }} คน</td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-light">
                    <i class="bi bi-pencil"></i>
                </a>
                @if(!in_array($role->name, ['admin', 'staff', 'customer']))
                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}"
                    onsubmit="return confirm('ลบตำแหน่ง {{ $role->name }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;padding:32px;color:#aaa;">ไม่พบตำแหน่ง</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $roles->links() }}</div>
</div>
@endsection
