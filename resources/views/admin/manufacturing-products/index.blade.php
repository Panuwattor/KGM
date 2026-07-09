@extends('layouts.admin')
@section('title', 'สินค้าที่รับผลิต')
@section('content')
<div class="page-header">
    <div class="page-title">สินค้าที่รับผลิต</div>
    @can('manufacturing_product_manage')
    <a href="{{ route('admin.manufacturing-products.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มสินค้า</a>
    @endcan
</div>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:80px;">รูป</th>
                <th>ชื่อสินค้า</th>
                <th>รายละเอียด</th>
                <th style="width:90px;">รูปเพิ่มเติม</th>
                <th style="width:90px;">สถานะ</th>
                <th style="width:110px;"></th>
            </tr>
        </thead>
        <tbody>
        @forelse($products as $p)
        <tr>
            <td>
                @if($p->image)
                    <img src="{{ media_url($p->image) }}" alt="{{ $p->name }}" style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid #eee;">
                @else
                    <div style="width:56px;height:56px;border-radius:8px;background:#f2f2f2;display:flex;align-items:center;justify-content:center;color:#bbb;"><i class="bi bi-image"></i></div>
                @endif
            </td>
            <td><strong>{{ $p->name }}</strong></td>
            <td style="font-size:13px;max-width:360px;color:#666;">{{ Str::limit($p->description, 90) }}</td>
            <td style="text-align:center;">
                <span class="status-badge" style="background:#eef2ff;color:#3730a3;">{{ $p->images_count }} รูป</span>
            </td>
            <td><span class="status-badge {{ $p->is_active ? 'status-green' : 'status-red' }}">{{ $p->is_active ? 'แสดง' : 'ซ่อน' }}</span></td>
            <td style="display:flex;gap:6px;">
                @can('manufacturing_product_manage')
                <a href="{{ route('admin.manufacturing-products.edit', $p) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                @endcan
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีสินค้าที่รับผลิต</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
