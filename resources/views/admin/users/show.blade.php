@extends('layouts.admin')
@section('title', 'ผู้ใช้งาน: '.$user->name)
@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ $user->name }}</div>
        <div class="page-subtitle">
            <span class="status-badge {{ $user->role === 'admin' ? 'status-purple' : 'status-indigo' }}">
                {{ $user->role === 'admin' ? 'แอดมิน' : 'พนักงาน' }}
            </span>
        </div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary"><i class="bi bi-pencil"></i> แก้ไข</a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
    </div>
</div>
<div style="max-width:520px;">
    <div class="form-card">
        <h3><i class="bi bi-person"></i> ข้อมูลบัญชี</h3>
        <div style="font-size:14px;line-height:2.6;">
            <div><strong>ชื่อ:</strong> {{ $user->name }}</div>
            <div><strong>อีเมล:</strong> {{ $user->email }}</div>
            <div><strong>บทบาท:</strong>
                <span class="status-badge {{ $user->role === 'admin' ? 'status-purple' : 'status-indigo' }}">
                    {{ $user->role === 'admin' ? 'แอดมิน' : 'พนักงาน' }}
                </span>
            </div>
            <div><strong>สถานะ:</strong>
                <span class="status-badge {{ $user->is_active ? 'status-green' : 'status-red' }}">
                    {{ $user->is_active ? 'ปกติ' : 'ระงับ' }}
                </span>
            </div>
            <div><strong>เพิ่มเมื่อ:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</div>
        </div>
    </div>
</div>
@endsection
