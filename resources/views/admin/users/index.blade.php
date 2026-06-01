@extends('layouts.admin')
@section('title', 'จัดการสมาชิก')
@section('content')
<div class="page-header">
    <div class="page-title">สมาชิกทั้งหมด</div>
</div>
<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:2;"><label>ค้นหา</label><input type="text" name="search" class="form-control" placeholder="ชื่อ, อีเมล" value="{{ request('search') }}"></div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
</form>
<div class="table-wrap">
    <table>
        <thead><tr><th>ชื่อ</th><th>อีเมล</th><th>เบอร์โทร</th><th>ออเดอร์</th><th>สมัครเมื่อ</th><th>สถานะ</th><th></th></tr></thead>
        <tbody>
        @forelse($users as $user)
        <tr>
            <td><span style="font-weight:700;">{{ $user->name }}</span></td>
            <td>{{ $user->email }}</td>
            <td>{{ $user->phone ?? '-' }}</td>
            <td>{{ $user->orders_count }}</td>
            <td style="font-size:12px;color:#888;">{{ $user->created_at->format('d/m/Y') }}</td>
            <td><span class="status-badge {{ $user->is_active ? 'status-green' : 'status-red' }}">{{ $user->is_active ? 'ปกติ' : 'ระงับ' }}</span></td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('ระงับสมาชิกนี้?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-ban"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:32px;color:#aaa;">ไม่พบสมาชิก</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $users->links() }}</div>
</div>
@endsection
