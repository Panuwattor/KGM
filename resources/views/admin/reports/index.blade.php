@extends('layouts.admin')
@section('title', 'รายงาน')
@section('content')
<div class="page-header">
    <div class="page-title">รายงานและสถิติ</div>
    <form method="GET" style="display:flex;gap:8px;align-items:center;">
        <label style="font-size:13px;color:#666;">แสดงข้อมูล:</label>
        <select name="period" class="form-control" style="width:150px;" onchange="this.form.submit()">
            <option value="7" {{ $period=='7'?'selected':'' }}>7 วันล่าสุด</option>
            <option value="30" {{ $period=='30'?'selected':'' }}>30 วันล่าสุด</option>
            <option value="90" {{ $period=='90'?'selected':'' }}>90 วันล่าสุด</option>
        </select>
    </form>
</div>

<div class="stats-grid">
    <div class="stat-card green">
        <div class="stat-icon green"><i class="bi bi-currency-exchange"></i></div>
        <div class="stat-value">฿{{ number_format($totalRevenue, 0) }}</div>
        <div class="stat-label">ยอดขายรวม</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-icon gold"><i class="bi bi-receipt"></i></div>
        <div class="stat-value">{{ number_format($totalOrders) }}</div>
        <div class="stat-label">ออเดอร์ทั้งหมด</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="bi bi-person-plus"></i></div>
        <div class="stat-value">{{ number_format($newCustomers) }}</div>
        <div class="stat-label">สมาชิกใหม่</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><i class="bi bi-graph-up"></i></div>
        <div class="stat-value">฿{{ number_format($avgOrderValue, 0) }}</div>
        <div class="stat-label">ยอดเฉลี่ยต่อออเดอร์</div>
    </div>
</div>

<div style="display:grid;grid-template-columns:3fr 2fr;gap:24px;">
    <div class="table-wrap">
        <div class="table-header"><h3><i class="bi bi-calendar3" style="color:var(--g500);"></i> ยอดขายรายวัน</h3></div>
        <div style="padding:20px;">
            @if($dailyRevenue->isEmpty())
            <p style="text-align:center;color:#aaa;">ยังไม่มีข้อมูล</p>
            @else
            @php $maxRevenue = $dailyRevenue->max('total') ?: 1; @endphp
            @foreach($dailyRevenue->take(14) as $day)
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                <div style="font-size:12px;color:#888;width:70px;text-align:right;">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</div>
                <div style="flex:1;background:#f0f4f0;border-radius:999px;height:20px;overflow:hidden;">
                    <div style="background:linear-gradient(to right,var(--g600),var(--g400));border-radius:999px;height:100%;width:{{ ($day->total/$maxRevenue)*100 }}%;transition:width 0.5s;"></div>
                </div>
                <div style="font-size:13px;font-weight:700;color:var(--g700);width:90px;text-align:right;">฿{{ number_format($day->total,0) }}</div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
    <div class="table-wrap">
        <div class="table-header"><h3><i class="bi bi-trophy" style="color:var(--gold);"></i> สินค้าขายดีสุด</h3></div>
        <table>
            <thead><tr><th>#</th><th>สินค้า</th><th>จำนวน</th><th>ยอด</th></tr></thead>
            <tbody>
            @forelse($topProducts as $i => $p)
            <tr>
                <td><span style="font-weight:800;color:{{ $i==0?'var(--gold-d)':($i==1?'#aaa':'#cd7f32') }};">{{ $i+1 }}</span></td>
                <td style="font-size:13px;max-width:200px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p->name }}</td>
                <td style="font-weight:700;">{{ number_format($p->total_qty) }}</td>
                <td style="font-weight:700;color:var(--g600);">฿{{ number_format($p->total_revenue,0) }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:24px;color:#aaa;">ยังไม่มีข้อมูล</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
