@extends('layouts.admin')
@section('title', 'ประเภทสินค้า')
@section('content')
<div class="page-header">
    <div class="page-title">ประเภทสินค้า</div>
    <a href="{{ route('admin.product-types.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มประเภท</a>
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th width="60">รูป</th><th>ชื่อประเภท</th><th>สินค้า</th><th>บริการปัก</th><th>แสดงหน้าแรก</th><th>สถานะ</th><th></th></tr></thead>
        <tbody>
        @forelse($types as $type)
        <tr>
            <td>
                @if($type->image)
                    <img src="{{ asset('storage/'.$type->image) }}" style="width:48px;height:48px;object-fit:cover;border-radius:10px;">
                @else
                    <div style="width:48px;height:48px;background:#f0f4f0;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#ccc;">
                        <i class="bi bi-grid-3x3-gap"></i>
                    </div>
                @endif
            </td>
            <td><strong>{{ $type->name }}</strong><div style="font-size:12px;color:#aaa;">{{ $type->slug }}</div></td>
            <td>{{ $type->products_count }}</td>
            <td>
                <span class="status-badge {{ $type->has_embroidery ? 'status-green' : 'status-gray' }}">
                    {{ $type->has_embroidery ? 'มี' : 'ไม่มี' }}
                </span>
            </td>
            <td>
                <span class="status-badge {{ $type->show_on_home ? 'status-green' : 'status-gray' }}">
                    {{ $type->show_on_home ? 'แสดง' : 'ซ่อน' }}
                </span>
            </td>
            <td><span class="status-badge {{ $type->is_active ? 'status-green' : 'status-red' }}">{{ $type->is_active ? 'เปิด' : 'ปิด' }}</span></td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.product-types.edit', $type) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('admin.product-types.destroy', $type) }}" onsubmit="return confirm('ลบประเภทนี้?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีประเภทสินค้า</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
