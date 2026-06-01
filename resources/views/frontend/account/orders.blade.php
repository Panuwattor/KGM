@extends('layouts.app')
@section('title', 'คำสั่งซื้อของฉัน')
@section('content')
<div class="container" style="padding:32px 0 64px;">
    <h1 style="font-size:26px;font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;"><i class="bi bi-receipt"></i> คำสั่งซื้อของฉัน</h1>
    @if($orders->isEmpty())
    <div style="background:white;border-radius:20px;padding:64px;text-align:center;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
        <i class="bi bi-bag-x" style="font-size:64px;color:#ddd;display:block;margin-bottom:16px;"></i>
        <p style="color:#aaa;">ยังไม่มีคำสั่งซื้อ</p>
        <a href="{{ route('shop') }}" class="btn btn-primary">เริ่มช็อปปิ้ง</a>
    </div>
    @else
    <div style="display:flex;flex-direction:column;gap:16px;">
        @foreach($orders as $order)
        <div style="background:white;border-radius:20px;padding:20px 24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;flex-wrap:wrap;gap:10px;">
                <div>
                    <span style="font-weight:800;font-size:16px;color:var(--kgm-green-700);">{{ $order->order_number }}</span>
                    <span style="font-size:12px;color:#888;margin-left:10px;">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                </div>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span class="status-badge status-{{ $order->status_color }}">{{ $order->status_label }}</span>
                    <span style="font-weight:800;font-size:16px;color:var(--kgm-green-700);">฿{{ number_format($order->total, 0) }}</span>
                </div>
            </div>
            @if($order->tracking_number)
            <div style="background:var(--kgm-green-100);border-radius:10px;padding:8px 14px;font-size:13px;color:var(--kgm-green-700);margin-bottom:10px;">
                <i class="bi bi-truck"></i> {{ $order->shipping_provider }} | Tracking: <strong>{{ $order->tracking_number }}</strong>
            </div>
            @endif
            <div style="display:flex;gap:8px;flex-wrap:wrap;">
                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-outline"><i class="bi bi-eye"></i> ดูรายละเอียด</a>
                <form method="POST" action="{{ route('account.orders.reorder', $order) }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light"><i class="bi bi-arrow-clockwise"></i> สั่งซื้อซ้ำ</button>
                </form>
                @if($order->status === 'pending_payment')
                <a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-gold"><i class="bi bi-cloud-upload"></i> อัปโหลดสลิป</a>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    <div class="pagination">{{ $orders->links() }}</div>
    @endif
</div>
@endsection
