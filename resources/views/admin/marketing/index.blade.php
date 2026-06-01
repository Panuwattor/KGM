@extends('layouts.admin')
@section('title', 'Marketing - Flash Sale')
@section('content')
<div class="page-header">
    <div class="page-title">Flash Sale & โปรโมชั่น</div>
    <a href="{{ route('admin.flash-sales.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> สร้าง Flash Sale</a>
</div>
<div class="table-wrap">
    <div class="table-header"><h3><i class="bi bi-lightning-charge" style="color:var(--gold);"></i> Flash Sales</h3></div>
    <table>
        <thead><tr><th>ชื่อ</th><th>เริ่ม</th><th>สิ้นสุด</th><th>สถานะ</th><th>สินค้า</th><th></th></tr></thead>
        <tbody>
        @forelse($flashSales as $fs)
        <tr>
            <td><strong>{{ $fs->name }}</strong></td>
            <td style="font-size:13px;">{{ $fs->starts_at->format('d/m/Y H:i') }}</td>
            <td style="font-size:13px;">{{ $fs->ends_at->format('d/m/Y H:i') }}</td>
            <td>
                @if($fs->isActive())<span class="status-badge status-green">กำลังดำเนินการ</span>
                @elseif($fs->starts_at > now())<span class="status-badge status-blue">กำลังจะมา</span>
                @else<span class="status-badge status-gray">สิ้นสุด</span>@endif
            </td>
            <td>{{ $fs->items->count() }} รายการ</td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.flash-sales.edit', $fs) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('admin.flash-sales.destroy', $fs) }}" onsubmit="return confirm('ลบ?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มี Flash Sale</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
