@extends('layouts.admin')
@section('title', 'อำเภอ - '.$province->name_in_thai)
@section('content')
<div class="page-header">
    <div>
        <div class="page-subtitle" style="margin-bottom:4px;">
            <a href="{{ route('admin.provinces.index') }}" style="color:var(--g600);text-decoration:none;">จังหวัด</a>
            <i class="bi bi-chevron-right" style="font-size:10px;"></i> {{ $province->name_in_thai }}
        </div>
        <div class="page-title">อำเภอใน {{ $province->name_in_thai }}</div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.provinces.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
        <a href="{{ route('admin.provinces.districts.create', $province) }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มอำเภอ</a>
    </div>
</div>
<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:3;">
        <label>ค้นหา</label>
        <input type="text" name="search" class="form-control" placeholder="ชื่ออำเภอ หรือ รหัส" value="{{ request('search') }}">
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
    @if(request('search'))
        <a href="{{ route('admin.provinces.districts.index', $province) }}" class="btn btn-light">ล้าง</a>
    @endif
</form>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th width="100">รหัส</th>
                <th>ชื่อ (ไทย)</th>
                <th>ชื่อ (อังกฤษ)</th>
                <th width="100">ตำบล</th>
                <th width="140"></th>
            </tr>
        </thead>
        <tbody>
        @forelse($districts as $district)
        <tr style="cursor:pointer;" onclick="window.location='{{ route('admin.districts.subdistricts.index', $district) }}'">
            <td>{{ $district->code ?? '-' }}</td>
            <td><strong>{{ $district->name_in_thai }}</strong></td>
            <td>{{ $district->name_in_english ?? '-' }}</td>
            <td><span class="status-badge status-gray">{{ $district->subdistricts_count }} </span></td>
            <td style="display:flex;gap:6px;" onclick="event.stopPropagation();">
                <a href="{{ route('admin.districts.subdistricts.index', $district) }}" class="btn btn-sm btn-primary"><i class="bi bi-diagram-3"></i> ตำบล</a>
                <a href="{{ route('admin.districts.edit', $district) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:32px;color:#aaa;">ไม่พบอำเภอ</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $districts->links() }}</div>
</div>
@endsection
