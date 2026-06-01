@extends('layouts.admin')
@section('title', 'ตำแหน่งงาน')
@section('content')
<div class="page-header">
    <div class="page-title">ตำแหน่งงาน</div>
    <a href="{{ route('admin.careers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มตำแหน่ง</a>
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th>ตำแหน่ง</th><th>แผนก</th><th>ประเภท</th><th>ที่สมัคร</th><th>สถานะ</th><th></th></tr></thead>
        <tbody>
        @forelse($careers as $c)
        <tr>
            <td><strong>{{ $c->title }}</strong></td>
            <td>{{ $c->department ?? '-' }}</td>
            <td>{{ ['full_time'=>'ประจำ','part_time'=>'นอกเวลา','contract'=>'สัญญาจ้าง'][$c->type] }}</td>
            <td><a href="{{ route('admin.careers.applications', $c) }}" class="btn btn-sm btn-light">{{ $c->applications_count }} คน</a></td>
            <td><span class="status-badge {{ $c->is_active ? 'status-green' : 'status-red' }}">{{ $c->is_active ? 'เปิดรับ' : 'ปิด' }}</span></td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.careers.edit', $c) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('admin.careers.destroy', $c) }}" onsubmit="return confirm('ลบ?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีตำแหน่งงาน</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $careers->links() }}</div>
</div>
@endsection
