@extends('layouts.admin')
@section('title', 'คำขอใบเสนอราคา')
@section('content')
@php
$statusLabels = ['pending'=>'รอดำเนินการ','quoted'=>'ส่งราคาแล้ว','accepted'=>'ตอบรับ','rejected'=>'ปฏิเสธ','closed'=>'ปิด'];
$statusColors = ['pending'=>'yellow','quoted'=>'blue','accepted'=>'green','rejected'=>'gray','closed'=>'gray'];
@endphp
<div class="page-header">
    <div>
        <div class="page-title">คำขอใบเสนอราคา B2B</div>
        <div class="page-subtitle">{{ $quotes->total() }} รายการ</div>
    </div>
</div>

{{-- Status Tabs --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <a href="{{ route('admin.quotes.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-light' }}">ทั้งหมด</a>
    @foreach($statusLabels as $key => $label)
    <a href="{{ route('admin.quotes.index', ['status'=>$key]) }}" class="btn btn-sm {{ request('status')==$key ? 'btn-primary' : 'btn-light' }}">
        {{ $label }}
        @if(isset($statusCounts[$key])) <span style="background:rgba(0,0,0,0.15);border-radius:999px;padding:1px 6px;font-size:11px;margin-left:4px;">{{ $statusCounts[$key] }}</span> @endif
    </a>
    @endforeach
</div>

<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:2;">
        <label>ค้นหา</label>
        <input type="text" name="search" class="form-control" placeholder="บริษัท, ผู้ติดต่อ, อีเมล, เบอร์โทร" value="{{ request('search') }}">
    </div>
    <div class="filter-item">
        <label>วันที่เริ่ม</label>
        <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
    </div>
    <div class="filter-item">
        <label>วันที่สิ้นสุด</label>
        <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
    </div>
    <input type="hidden" name="status" value="{{ request('status') }}">
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
    @if(request('search') || request('date_from') || request('date_to'))
    <a href="{{ route('admin.quotes.index', array_filter(['status'=>request('status')])) }}" class="btn btn-light"><i class="bi bi-x-lg"></i> ล้าง</a>
    @endif
</form>

<div class="table-wrap">
    <table>
        <thead><tr><th>บริษัท</th><th>ผู้ติดต่อ</th><th>จำนวน</th><th>สถานะ</th><th>วันที่</th><th></th></tr></thead>
        <tbody>
        @forelse($quotes as $q)
        <tr>
            <td><strong>{{ $q->company_name }}</strong></td>
            <td>{{ $q->contact_name }}<br><span style="font-size:12px;color:#888;">{{ $q->email }}</span></td>
            <td>{{ number_format($q->quantity) }} ชิ้น</td>
            <td><span class="status-badge status-{{ $statusColors[$q->status] ?? 'gray' }}">{{ $statusLabels[$q->status] ?? $q->status }}</span></td>
            <td style="font-size:12px;">{{ $q->created_at->format('d/m/Y') }}</td>
            <td><a href="{{ route('admin.quotes.show', $q) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i> ดู</a></td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ไม่พบคำขอ</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $quotes->links() }}</div>
</div>
@endsection
