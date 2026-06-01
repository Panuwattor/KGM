@extends('layouts.admin')
@section('title', 'จัดการสาขา')
@section('content')
<div class="page-header">
    <div class="page-title">สาขา/โชว์รูม</div>
    <a href="{{ route('admin.showrooms.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มสาขา</a>
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th>ชื่อสาขา</th><th>ที่อยู่</th><th>โทร</th><th>ประเภท</th><th>สถานะ</th><th></th></tr></thead>
        <tbody>
        @forelse($showrooms as $s)
        <tr>
            <td><strong>{{ $s->name }}</strong></td>
            <td style="font-size:13px;max-width:300px;">{{ Str::limit($s->address, 80) }}</td>
            <td>{{ $s->phone ?? '-' }}</td>
            <td>@if($s->is_main)<span class="status-badge" style="background:#fef9c3;color:#854d0e;">สาขาหลัก</span>@else<span style="font-size:12px;color:#888;">สาขาย่อย</span>@endif</td>
            <td><span class="status-badge {{ $s->is_active ? 'status-green' : 'status-red' }}">{{ $s->is_active ? 'เปิด' : 'ปิด' }}</span></td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.showrooms.edit', $s) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('admin.showrooms.destroy', $s) }}" onsubmit="return confirm('ลบ?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีสาขา</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
