@extends('layouts.app')
@section('title', 'บัญชีของฉัน')
@section('content')
<div class="container acc-wrap">
    <div class="acc-layout">
        @include('frontend.account._sidebar')
        <div>
            {{-- Stats --}}
            <div class="acc-stats-grid">
                <div class="acc-stat" style="background:#fff;border:2px solid #e8f0e9;">
                    <div class="acc-stat-num" style="color:var(--kgm-green-600);">{{ $totalOrders }}</div>
                    <div class="acc-stat-label">คำสั่งซื้อ</div>
                </div>
                <div class="acc-stat" style="background:#fff;border:2px solid #f0e6c0;">
                    <div class="acc-stat-num" style="color:var(--kgm-gold-600);">{{ $wishlistCount }}</div>
                    <div class="acc-stat-label">รายการโปรด</div>
                </div>
                <div class="acc-stat" style="background:#fff;border:2px solid #e8f0e9;">
                    <div class="acc-stat-num" style="color:var(--kgm-green-600);">{{ $user->addresses()->count() }}</div>
                    <div class="acc-stat-label">ที่อยู่จัดส่ง</div>
                </div>
            </div>

            {{-- Recent orders --}}
            <div class="acc-card">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                    <h3 style="font-size:16px;font-weight:800;color:var(--kgm-green-800);"><i class="bi bi-receipt"></i> คำสั่งซื้อล่าสุด</h3>
                    <a href="{{ route('account.orders') }}" style="font-size:13px;color:var(--kgm-green-600);font-weight:600;">ดูทั้งหมด <i class="bi bi-arrow-right"></i></a>
                </div>
                @forelse($recentOrders as $order)
                <div class="acc-order-row">
                    <div class="acc-order-icon"><i class="bi bi-receipt" style="color:var(--kgm-green-600);font-size:18px;"></i></div>
                    <div class="acc-order-info">
                        <div class="acc-order-num">{{ $order->order_number }}</div>
                        <div class="acc-order-date">{{ $order->created_at->format('d/m/Y') }}</div>
                    </div>
                    <span class="badge status-badge status-{{ $order->status_color }}">{{ $order->status_label }}</span>
                    <div class="acc-order-amount">฿{{ number_format($order->total, 0) }}</div>
                    <a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                </div>
                @empty
                <p style="text-align:center;color:#aaa;padding:24px 0;">ยังไม่มีคำสั่งซื้อ</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
