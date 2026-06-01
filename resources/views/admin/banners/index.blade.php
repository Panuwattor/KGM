@extends('layouts.admin')
@section('title', 'จัดการ Banner')
@section('content')
<div class="page-header">
    <div class="page-title">จัดการ Banner</div>
    <a href="{{ route('admin.banners.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่ม Banner</a>
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th>รูป</th><th>ลิงก์</th><th>ลำดับ</th><th>สถานะ</th><th></th></tr></thead>
        <tbody>
        @forelse($banners as $b)
        <tr>
            <td><img src="{{ asset('storage/'.$b->image_path) }}" style="width:100px;height:50px;object-fit:cover;border-radius:10px;" onerror="this.style.display='none'"></td>
            <td>{{ $b->link_url ?? '-' }}</td>
            <td>{{ $b->sort_order }}</td>
            <td><span class="status-badge {{ $b->is_active ? 'status-green' : 'status-red' }}">{{ $b->is_active ? 'เปิด' : 'ปิด' }}</span></td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.banners.edit', $b) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('admin.banners.destroy', $b) }}" onsubmit="return confirm('ลบ?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มี Banner</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
