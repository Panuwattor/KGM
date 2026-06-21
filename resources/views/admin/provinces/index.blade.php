@extends('layouts.admin')
@section('title', 'จัดการจังหวัด')
@section('content')
<div class="page-header">
    <div>
        <div class="page-title">จัดการจังหวัด</div>
        <div class="page-subtitle">เพิ่ม / แก้ไข / ลบ ข้อมูลจังหวัด</div>
    </div>
    <a href="{{ route('admin.provinces.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มจังหวัด</a>
</div>
<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:3;">
        <label>ค้นหา</label>
        <input type="text" name="search" class="form-control" placeholder="ชื่อจังหวัด หรือ รหัส" value="{{ request('search') }}">
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
    @if(request('search'))
        <a href="{{ route('admin.provinces.index') }}" class="btn btn-light">ล้าง</a>
    @endif
</form>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th width="100">รหัส</th>
                <th>ชื่อ (ไทย)</th>
                <th>ชื่อ (อังกฤษ)</th>
                <th width="100">อำเภอ</th>
                <th width="110"></th>
            </tr>
        </thead>
        <tbody>
        @forelse($provinces as $province)
        <tr style="cursor:pointer;" onclick="window.location='{{ route('admin.provinces.districts.index', $province) }}'">
            <td>{{ $province->code ?? '-' }}</td>
            <td><strong>{{ $province->name_in_thai }}</strong></td>
            <td>{{ $province->name_in_english ?? '-' }}</td>
            <td><span class="status-badge status-gray">{{ $province->districts_count }}</span></td>
            <td style="display:flex;gap:6px;" onclick="event.stopPropagation();">
                <a href="{{ route('admin.provinces.districts.index', $province) }}" class="btn btn-sm btn-primary"><i class="bi bi-diagram-3"></i> อำเภอ</a>
                <a href="{{ route('admin.provinces.edit', $province) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:32px;color:#aaa;">ไม่พบจังหวัด</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $provinces->links() }}</div>
</div>
@endsection
