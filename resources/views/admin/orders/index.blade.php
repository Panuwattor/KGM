@extends('layouts.admin')
@section('title', 'คำสั่งซื้อ')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">คำสั่งซื้อทั้งหมด</div>
        <div class="page-subtitle">{{ $orders->total() }} รายการ</div>
    </div>
</div>

{{-- Status Tabs --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-light' }}">ทั้งหมด</a>
    @foreach(\App\Models\Order::STATUS_LABELS as $key => $info)
    <a href="{{ route('admin.orders.index', ['status'=>$key]) }}" class="btn btn-sm {{ request('status')==$key ? 'btn-primary' : 'btn-light' }}">
        {{ $info['label'] }}
        @if(isset($statusCounts[$key])) <span style="background:rgba(0,0,0,0.15);border-radius:999px;padding:1px 6px;font-size:11px;margin-left:4px;">{{ $statusCounts[$key] }}</span> @endif
    </a>
    @endforeach
</div>

<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:2;">
        <label>ค้นหา</label>
        <input type="text" name="search" class="form-control" placeholder="เลขออเดอร์, ชื่อลูกค้า, เบอร์โทร" value="{{ request('search') }}">
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
</form>

<div class="table-wrap">
    <table>
        <thead><tr>
            <th>เลขที่ออเดอร์</th><th>ลูกค้า</th><th>สินค้า</th><th>ยอดรวม</th><th>การชำระ</th><th>สถานะ</th><th>วันที่</th><th></th>
        </tr></thead>
        <tbody>
        @forelse($orders as $order)
        <tr>
            <td><span style="font-weight:700;color:var(--g700);">{{ $order->order_number }}</span></td>
            <td>
                <div style="font-weight:600;">{{ $order->ship_name }}</div>
                <div style="font-size:12px;color:#888;">{{ $order->ship_phone }}</div>
            </td>
            <td style="font-size:13px;">{{ $order->items()->count() }} รายการ</td>
            <td><span style="font-weight:700;color:var(--g600);">฿{{ number_format($order->total, 0) }}</span></td>
            <td>
                @if($order->payment_slip)
                    <span class="status-badge status-blue"><i class="bi bi-receipt"></i> มีสลิป</span>
                @else
                    <span class="status-badge status-yellow">รอชำระ</span>
                @endif
            </td>
            <td><span class="status-badge status-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
            <td style="font-size:12px;color:#888;">{{ $order->created_at->format('d/m/y H:i') }}</td>
            <td>
                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i> ดู</a>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:40px;color:#aaa;">ไม่พบคำสั่งซื้อ</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px 20px;">{{ $orders->links() }}</div>
</div>
@endsection
