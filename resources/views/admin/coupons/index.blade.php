@extends('layouts.admin')
@section('title', 'คูปองส่วนลด')
@section('content')
<div class="page-header">
    <div class="page-title">คูปองส่วนลด</div>
    <a href="{{ route('admin.coupons.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> สร้างคูปอง</a>
</div>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th style="width:64px;"></th>
                <th>โค้ด</th>
                <th>ชื่อ</th>
                <th>ประเภท / มูลค่า</th>
                <th>ขั้นต่ำ</th>
                <th>สินค้า/หมวด</th>
                <th>ใช้แล้ว</th>
                <th>เริ่ม / หมดอายุ</th>
                <th>สถานะ</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($coupons as $c)
        <tr>
            <td>
                @if($c->image)
                <img src="{{ media_url($c->image) }}" alt="{{ $c->name }}"
                     style="width:52px;height:52px;object-fit:cover;border-radius:10px;border:1px solid #e8ecef;">
                @else
                <div style="width:52px;height:52px;border-radius:10px;background:linear-gradient(135deg,var(--g700),var(--g500));display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-ticket-perforated" style="color:white;font-size:20px;"></i>
                </div>
                @endif
            </td>
            <td>
                <code style="background:#f0f4f0;padding:3px 8px;border-radius:8px;font-size:13px;font-weight:700;color:var(--g700);">{{ $c->code }}</code>
                @if($c->is_public)
                <span style="font-size:10px;background:#e8f4fd;color:#0077b6;border-radius:6px;padding:2px 6px;margin-left:4px;">สาธารณะ</span>
                @endif
            </td>
            <td>{{ $c->name }}</td>
            <td>
                @if($c->type === 'free_shipping')
                    <span style="color:#27ae60;"><i class="bi bi-truck"></i> ส่งฟรี</span>
                @elseif($c->type === 'percent')
                    <span><i class="bi bi-percent"></i> {{ (float)$c->value }}%</span>
                    @if($c->maximum_discount)<small style="color:#888;"> (สูงสุด ฿{{ number_format($c->maximum_discount,0) }})</small>@endif
                @else
                    <span><i class="bi bi-currency-dollar"></i> ฿{{ number_format($c->value,0) }}</span>
                @endif
            </td>
            <td>{{ $c->minimum_order > 0 ? '฿'.number_format($c->minimum_order,0) : '-' }}</td>
            <td style="font-size:12px;color:#666;">
                @php $c->loadMissing(['products','categories']); @endphp
                @if($c->products->isNotEmpty() || $c->categories->isNotEmpty())
                    @if($c->categories->isNotEmpty())
                        <div>หมวด: {{ $c->categories->pluck('name')->join(', ') }}</div>
                    @endif
                    @if($c->products->isNotEmpty())
                        <div>สินค้า: {{ $c->products->count() }} รายการ</div>
                    @endif
                @else
                    <span style="color:#aaa;">ทั้งหมด</span>
                @endif
            </td>
            <td>{{ $c->used_count }}{{ $c->usage_limit ? '/'.$c->usage_limit : '' }}</td>
            <td style="font-size:12px;">
                <div>{{ $c->starts_at?->format('d/m/y H:i') ?? '—' }}</div>
                <div style="color:#e74c3c;">{{ $c->expires_at?->format('d/m/y H:i') ?? 'ไม่จำกัด' }}</div>
            </td>
            <td>
                <span class="status-badge {{ $c->is_active && $c->isValid() ? 'status-green' : 'status-red' }}">
                    {{ $c->isValid() ? 'ใช้ได้' : 'หมดอายุ/ปิด' }}
                </span>
            </td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.coupons.edit', $c) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('admin.coupons.destroy', $c) }}" onsubmit="return confirm('ลบ?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="10" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีคูปอง</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $coupons->links() }}</div>
</div>
@endsection
