@extends('layouts.admin')
@section('title', 'คูปองส่วนลด')
@section('content')
<div class="page-header">
    <div class="page-title">คูปองส่วนลด</div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> สร้างคูปอง</a>
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th>โค้ด</th><th>ชื่อ</th><th>ประเภท</th><th>มูลค่า</th><th>ขั้นต่ำ</th><th>ใช้แล้ว</th><th>หมดอายุ</th><th>สถานะ</th><th></th></tr></thead>
        <tbody>
        @forelse($coupons as $c)
        <tr>
            <td><code style="background:#f0f4f0;padding:3px 8px;border-radius:8px;font-size:13px;font-weight:700;color:var(--g700);">{{ $c->code }}</code></td>
            <td>{{ $c->name }}</td>
            <td>{{ $c->type === 'percent' ? 'เปอร์เซ็นต์' : ($c->type === 'fixed' ? 'จำนวนบาท' : 'ส่งฟรี') }}</td>
            <td>{{ $c->type === 'percent' ? $c->value.'%' : '฿'.number_format($c->value,0) }}</td>
            <td>฿{{ number_format($c->minimum_order, 0) }}</td>
            <td>{{ $c->used_count }}{{ $c->usage_limit ? '/'.$c->usage_limit : '' }}</td>
            <td style="font-size:12px;">{{ $c->expires_at?->format('d/m/Y') ?? 'ไม่จำกัด' }}</td>
            <td><span class="status-badge {{ $c->is_active && $c->isValid() ? 'status-green' : 'status-red' }}">{{ $c->isValid() ? 'ใช้ได้' : 'หมดอายุ/ปิด' }}</span></td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.coupons.edit', $c) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('admin.coupons.destroy', $c) }}" onsubmit="return confirm('ลบ?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="9" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีคูปอง</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $coupons->links() }}</div>
</div>
@endsection
