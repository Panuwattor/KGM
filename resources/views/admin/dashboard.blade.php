@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">ภาพรวมระบบ</div>
        <div class="page-subtitle">ข้อมูล ณ วันที่ {{ now()->locale('th')->isoFormat('D MMMM YYYY') }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.orders.index', ['status'=>'payment_uploaded']) }}" class="btn btn-primary"><i class="bi bi-credit-card"></i> ตรวจสลิปรอดำเนินการ</a>
        <a href="{{ route('admin.products.create') }}" class="btn btn-gold"><i class="bi bi-plus-lg"></i> เพิ่มสินค้า</a>
    </div>
</div>

{{-- Stats --}}
<div class="stats-grid">
    <div class="stat-card green">
        <div class="stat-icon green"><i class="bi bi-receipt"></i></div>
        <div class="stat-value">{{ $todayOrders }}</div>
        <div class="stat-label">ออเดอร์วันนี้</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-icon gold"><i class="bi bi-currency-exchange"></i></div>
        <div class="stat-value">฿{{ number_format($todayRevenue, 0) }}</div>
        <div class="stat-label">ยอดขายวันนี้</div>
    </div>
    <div class="stat-card red">
        <div class="stat-icon red"><i class="bi bi-clock-history"></i></div>
        <div class="stat-value">{{ $pendingOrders }}</div>
        <div class="stat-label">รอดำเนินการ</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="bi bi-people"></i></div>
        <div class="stat-value">{{ $totalCustomers }}</div>
        <div class="stat-label">สมาชิกทั้งหมด</div>
    </div>
</div>

{{-- Quick Status --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:24px;">
    @if($lowStockProducts > 0)
    <a href="{{ route('admin.products.index', ['status'=>'low_stock']) }}" style="text-decoration:none;">
        <div style="background:linear-gradient(135deg,#e74c3c,#c0392b);border-radius:16px;padding:16px 20px;color:white;display:flex;align-items:center;gap:12px;">
            <i class="bi bi-exclamation-triangle-fill" style="font-size:24px;"></i>
            <div><div style="font-size:20px;font-weight:800;">{{ $lowStockProducts }}</div><div style="font-size:13px;opacity:0.9;">สินค้าใกล้หมดสต๊อก</div></div>
        </div>
    </a>
    @endif
    @if($unreadMessages > 0)
    <a href="{{ route('admin.contacts.index') }}" style="text-decoration:none;">
        <div style="background:linear-gradient(135deg,#3498db,#2980b9);border-radius:16px;padding:16px 20px;color:white;display:flex;align-items:center;gap:12px;">
            <i class="bi bi-chat-left-text-fill" style="font-size:24px;"></i>
            <div><div style="font-size:20px;font-weight:800;">{{ $unreadMessages }}</div><div style="font-size:13px;opacity:0.9;">ข้อความที่ยังไม่ได้อ่าน</div></div>
        </div>
    </a>
    @endif
    @if($pendingQuotes > 0)
    <a href="{{ route('admin.quotes.index') }}" style="text-decoration:none;">
        <div style="background:linear-gradient(135deg,var(--gold-d),var(--gold));border-radius:16px;padding:16px 20px;color:var(--g900);display:flex;align-items:center;gap:12px;">
            <i class="bi bi-file-earmark-text-fill" style="font-size:24px;"></i>
            <div><div style="font-size:20px;font-weight:800;">{{ $pendingQuotes }}</div><div style="font-size:13px;opacity:0.8;">คำขอใบเสนอราคารอดำเนินการ</div></div>
        </div>
    </a>
    @endif
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    {{-- Recent Orders --}}
    <div class="table-wrap">
        <div class="table-header">
            <h3><i class="bi bi-receipt" style="color:var(--g500);"></i> ออเดอร์ล่าสุด</h3>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light">ดูทั้งหมด <i class="bi bi-arrow-right"></i></a>
        </div>
        <table>
            <thead><tr>
                <th>เลขที่ออเดอร์</th><th>ลูกค้า</th><th>ยอดรวม</th><th>สถานะ</th><th></th>
            </tr></thead>
            <tbody>
            @forelse($recentOrders as $order)
            <tr>
                <td><span style="font-weight:700;font-size:13px;color:var(--g700);">{{ $order->order_number }}</span></td>
                <td>
                    <div style="font-weight:600;">{{ $order->ship_name }}</div>
                    <div style="font-size:12px;color:#888;">{{ $order->created_at->diffForHumans() }}</div>
                </td>
                <td><span style="font-weight:700;color:var(--g600);">฿{{ number_format($order->total, 0) }}</span></td>
                <td><span class="status-badge status-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                <td><a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a></td>
            </tr>
            @empty
            <tr><td colspan="5" style="text-align:center;color:#aaa;padding:32px;">ยังไม่มีออเดอร์</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Top Products --}}
    <div class="table-wrap">
        <div class="table-header">
            <h3><i class="bi bi-trophy" style="color:var(--gold);"></i> สินค้าขายดี</h3>
        </div>
        <div style="padding:16px;">
            @forelse($topProducts as $i => $product)
            <div style="display:flex;align-items:center;gap:12px;padding:10px 0;border-bottom:1px solid #f5f7f5;">
                <div style="width:28px;height:28px;border-radius:999px;background:{{ $i==0 ? 'var(--gold)' : ($i==1 ? '#aaa' : ($i==2 ? '#cd7f32' : '#e8ecef')) }};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:{{ $i < 3 ? 'white' : '#666' }};">{{ $i+1 }}</div>
                <div style="flex:1;overflow:hidden;">
                    <div style="font-weight:600;font-size:13px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $product->name }}</div>
                    <div style="font-size:11px;color:#888;">ขายไป {{ number_format($product->total_sold ?? 0) }} ชิ้น</div>
                </div>
                <div style="font-size:13px;font-weight:700;color:var(--g600);">฿{{ number_format($product->total_revenue ?? 0, 0) }}</div>
            </div>
            @empty
            <p style="text-align:center;color:#aaa;padding:20px 0;">ยังไม่มีข้อมูล</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
