@extends('layouts.admin')
@section('title', 'ผู้ใช้งานระบบ')
@section('content')
<div class="page-header">
    <div>
        <div class="page-title">ผู้ใช้งานระบบ</div>
        <div class="page-subtitle">แอดมินและพนักงานที่มีสิทธิ์เข้าหลังบ้าน</div>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus"></i> เพิ่มผู้ใช้งาน
    </a>
</div>
<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:2;">
        <label>ค้นหา</label>
        <input type="text" name="search" class="form-control" placeholder="ชื่อ, อีเมล" value="{{ request('search') }}">
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
    @if(request()->filled('search'))
        <a href="{{ route('admin.users.index') }}" class="btn btn-light">ล้าง</a>
    @endif
</form>
<div class="table-wrap">
    <table>
        <thead>
            <tr><th>ชื่อ</th><th>อีเมล</th><th>บทบาท</th><th>สถานะ</th><th>เพิ่มเมื่อ</th><th></th></tr>
        </thead>
        <tbody>
        @forelse($users as $user)
        <tr>
            <td>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,var(--g600),var(--g500));display:flex;align-items:center;justify-content:center;color:white;font-weight:700;font-size:14px;flex-shrink:0;">
                        {{ mb_substr($user->name, 0, 1) }}
                    </div>
                    <span style="font-weight:700;">{{ $user->name }}</span>
                    @if($user->id === auth()->id())
                        <span class="status-badge status-blue" style="font-size:10px;">คุณ</span>
                    @endif
                </div>
            </td>
            <td style="color:#555;">{{ $user->email }}</td>
            <td>
                <span class="status-badge {{ $user->role === 'admin' ? 'status-purple' : 'status-indigo' }}">
                    {{ $user->role === 'admin' ? 'แอดมิน' : 'พนักงาน' }}
                </span>
            </td>
            <td>
                <span class="status-badge {{ $user->is_active ? 'status-green' : 'status-red' }}">
                    {{ $user->is_active ? 'ปกติ' : 'ระงับ' }}
                </span>
            </td>
            <td style="font-size:12px;color:#888;">{{ $user->created_at->format('d/m/Y') }}</td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-light">
                    <i class="bi bi-pencil"></i>
                </a>
                @if($user->id !== auth()->id())
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                    onsubmit="return confirm('ลบผู้ใช้งาน {{ $user->name }}?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ไม่พบผู้ใช้งาน</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $users->links() }}</div>
</div>
@endsection
