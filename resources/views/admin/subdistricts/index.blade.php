@extends('layouts.admin')
@section('title', 'ตำบล - '.$district->name_in_thai)
@section('content')
<div class="page-header">
    <div>
        <div class="page-subtitle" style="margin-bottom:4px;">
            <a href="{{ route('admin.provinces.index') }}" style="color:var(--g600);text-decoration:none;">จังหวัด</a>
            <i class="bi bi-chevron-right" style="font-size:10px;"></i>
            <a href="{{ route('admin.provinces.districts.index', $province) }}" style="color:var(--g600);text-decoration:none;">{{ $province->name_in_thai }}</a>
            <i class="bi bi-chevron-right" style="font-size:10px;"></i> {{ $district->name_in_thai }}
        </div>
        <div class="page-title">ตำบลใน {{ $district->name_in_thai }}</div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.provinces.districts.index', $province) }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
        <a href="{{ route('admin.districts.subdistricts.create', $district) }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มตำบล</a>
    </div>
</div>
<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:3;">
        <label>ค้นหา</label>
        <input type="text" name="search" class="form-control" placeholder="ชื่อตำบล, รหัส หรือ รหัสไปรษณีย์" value="{{ request('search') }}">
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
    @if(request('search'))
        <a href="{{ route('admin.districts.subdistricts.index', $district) }}" class="btn btn-light">ล้าง</a>
    @endif
</form>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th width="90">รหัส</th>
                <th>ชื่อ (ไทย)</th>
                <th>ชื่อ (อังกฤษ)</th>
                <th width="110">ไปรษณีย์</th>
                <th width="60"></th>
            </tr>
        </thead>
        <tbody>
        @forelse($subdistricts as $subdistrict)
        <tr>
            <td>{{ $subdistrict->code ?? '-' }}</td>
            <td><strong>{{ $subdistrict->name_in_thai }}</strong></td>
            <td>{{ $subdistrict->name_in_english ?? '-' }}</td>
            <td>{{ $subdistrict->zip_code ?? '-' }}</td>
            <td>
                <a href="{{ route('admin.subdistricts.edit', $subdistrict) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:32px;color:#aaa;">ไม่พบตำบล</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $subdistricts->links() }}</div>
</div>
@endsection
