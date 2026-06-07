@extends('layouts.admin')
@section('title', 'จัดการลูกค้า')
@section('content')
<div class="page-header">
    <div>
        <div class="page-title">ลูกค้าทั้งหมด</div>
        <div class="page-subtitle">ผู้ที่สมัครสมาชิกและซื้อสินค้าในระบบ</div>
    </div>
</div>
<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:3;">
        <label>ค้นหา</label>
        <input type="text" name="search" class="form-control" placeholder="ชื่อ, อีเมล, เบอร์โทร" value="{{ request('search') }}">
    </div>
    <div class="filter-item">
        <label>สถานะ</label>
        <select name="status" class="form-control">
            <option value="">ทั้งหมด</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>ปกติ</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>ระงับ</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
    @if(request()->hasAny(['search','status']))
        <a href="{{ route('admin.customers.index') }}" class="btn btn-light">ล้าง</a>
    @endif
</form>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ลูกค้า</th>
                <th>เบอร์โทร</th>
                <th>ออเดอร์</th>
                <th>สมัครเมื่อ</th>
                <th>สถานะ</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($customers as $customer)
        <tr>
            <td>
                <div style="font-weight:700;">{{ $customer->name }}</div>
                <div style="font-size:12px;color:#888;">{{ $customer->email ?? '-' }}</div>
            </td>
            <td>{{ $customer->phone }}</td>
            <td>
                <span style="font-weight:700;color:{{ $customer->orders_count > 0 ? 'var(--g600)' : '#aaa' }}">
                    {{ $customer->orders_count }}
                </span>
            </td>
            <td style="font-size:12px;color:#888;">{{ $customer->created_at->format('d/m/Y') }}</td>
            <td>
                <span class="status-badge {{ $customer->status === 'active' ? 'status-green' : 'status-red' }}">
                    {{ $customer->status === 'active' ? 'ปกติ' : 'ระงับ' }}
                </span>
            </td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-light">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-light">
                    <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
                    @csrf @method('PUT')
                    <input type="hidden" name="status" value="{{ $customer->status === 'active' ? 'inactive' : 'active' }}">
                    <button class="btn btn-sm {{ $customer->status === 'active' ? 'btn-danger' : 'btn-primary' }}"
                        onclick="return confirm('{{ $customer->status === 'active' ? 'ระงับ' : 'เปิด' }}บัญชีลูกค้านี้?')">
                        <i class="bi bi-{{ $customer->status === 'active' ? 'ban' : 'check-circle' }}"></i>
                    </button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ไม่พบลูกค้า</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $customers->links() }}</div>
</div>
@endsection
