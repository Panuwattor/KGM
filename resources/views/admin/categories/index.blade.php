@extends('layouts.admin')
@section('title', 'หมวดหมู่สินค้า')
@section('content')
<div class="page-header">
    <div class="page-title">หมวดหมู่สินค้า</div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มหมวดหมู่</a>
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th width="60">รูป</th><th>ชื่อหมวดหมู่</th><th>หมวดหมู่หลัก</th><th>สินค้า</th><th>สถานะ</th><th></th></tr></thead>
        <tbody>
        @forelse($categories as $cat)
        <tr>
            <td>
                @if($cat->image)
                    <img src="{{ media_url($cat->image) }}" style="width:48px;height:48px;object-fit:cover;border-radius:10px;">
                @else
                    <div style="width:48px;height:48px;background:#f0f4f0;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#ccc;">
                        <i class="bi bi-tag"></i>
                    </div>
                @endif
            </td>
            <td><strong>{{ $cat->parent_id ? '— ' : '' }}{{ $cat->name }}</strong></td>
            <td>{{ $cat->parent?->name ?? '-' }}</td>
            <td>{{ $cat->products_count }}</td>
            <td><span class="status-badge {{ $cat->is_active ? 'status-green' : 'status-red' }}">{{ $cat->is_active ? 'เปิด' : 'ปิด' }}</span></td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('ลบหมวดหมู่นี้?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีหมวดหมู่</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
