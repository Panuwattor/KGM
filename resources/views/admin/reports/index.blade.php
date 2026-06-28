@extends('layouts.admin')
@section('title', 'รายงาน')

@push('styles')
<style>
    .rpt-section-title{display:flex;align-items:center;gap:8px;font-size:16px;font-weight:800;color:var(--g800,#1f2937);margin:28px 0 14px;}
    .rpt-section-title i{color:var(--g500,#3a8a5e);}
    .rpt-grid-3{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;}
    .rpt-grid-2{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
    .rpt-bar-row{display:flex;align-items:center;gap:10px;margin-bottom:9px;}
    .rpt-bar-label{font-size:12.5px;color:#444;width:90px;flex-shrink:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
    .rpt-bar-track{flex:1;background:#f0f4f0;border-radius:999px;height:16px;overflow:hidden;}
    .rpt-bar-fill{background:linear-gradient(to right,var(--g600,#2f7a51),var(--g400,#67b98a));height:100%;border-radius:999px;}
    .rpt-bar-val{font-size:12.5px;font-weight:700;color:var(--g700,#256346);width:78px;text-align:right;flex-shrink:0;}
    .rpt-chg{font-size:12px;font-weight:700;}
    .rpt-chg.up{color:#16a34a;} .rpt-chg.down{color:#dc2626;} .rpt-chg.flat{color:#9ca3af;}
    .rpt-pill{display:inline-flex;align-items:center;gap:6px;padding:4px 10px;border-radius:999px;font-size:12px;font-weight:600;}
    .rpt-mini{font-size:12px;color:#888;}
    @media(max-width:900px){.rpt-grid-3,.rpt-grid-2{grid-template-columns:1fr;}}
</style>
@endpush

@section('content')
@php
    $statusLabels = \App\Models\Order::STATUS_LABELS;
    $statusColors = [
        'pending_payment'=>'#f59e0b','payment_uploaded'=>'#3b82f6','payment_verified'=>'#22c55e',
        'processing'=>'#6366f1','shipped'=>'#a855f7','delivered'=>'#10b981','cancelled'=>'#ef4444','refunded'=>'#9ca3af',
    ];
    $chg = function($v){
        if(is_null($v) || round($v,1) == 0) return null;
        if($v > 0) return ['up','+'.number_format($v,1).'%','bi-arrow-up-short'];
        return ['down',number_format($v,1).'%','bi-arrow-down-short'];
    };
@endphp

<div class="page-header">
    <div class="page-title">รายงานและสถิติ</div>
    <form method="GET" x-data="{ mode: '{{ $period }}' }" style="display:flex;gap:8px;align-items:center;">
        <select name="period" x-model="mode" class="form-control" style="width:150px;"
            @change="if (mode !== 'custom') $event.target.form.submit()">
            <option value="7" {{ $period=='7'?'selected':'' }}>7 วันล่าสุด</option>
            <option value="30" {{ $period=='30'?'selected':'' }}>30 วันล่าสุด</option>
            <option value="90" {{ $period=='90'?'selected':'' }}>90 วันล่าสุด</option>
            <option value="365" {{ $period=='365'?'selected':'' }}>1 ปีล่าสุด</option>
            <option value="custom" {{ $period=='custom'?'selected':'' }}>กำหนดเอง</option>
        </select>
        <template x-if="mode === 'custom'">
            <span style="display:flex;gap:8px;align-items:center;">
                <input type="date" name="from" value="{{ $from }}" class="form-control" style="width:150px;">
                <span style="color:#999;">ถึง</span>
                <input type="date" name="to" value="{{ $to }}" class="form-control" style="width:150px;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-funnel"></i> ดู</button>
            </span>
        </template>
    </form>
</div>

{{-- ===== KPI ===== --}}
<div class="stats-grid">
    <div class="stat-card green">
        <div class="stat-icon green"><i class="bi bi-currency-exchange"></i></div>
        <div class="stat-value">฿{{ number_format($totalRevenue, 0) }}</div>
        <div class="stat-label">ยอดขายรวม
            @php $c = $chg($revenueChange); @endphp
            @if($c)<span class="rpt-chg {{ $c[0] }}"><i class="bi {{ $c[2] }}"></i>{{ $c[1] }}</span>@endif
        </div>
    </div>
    <div class="stat-card gold">
        <div class="stat-icon gold"><i class="bi bi-receipt"></i></div>
        <div class="stat-value">{{ number_format($totalOrders) }}</div>
        <div class="stat-label">ออเดอร์ทั้งหมด
            @php $c = $chg($ordersChange); @endphp
            @if($c)<span class="rpt-chg {{ $c[0] }}"><i class="bi {{ $c[2] }}"></i>{{ $c[1] }}</span>@endif
        </div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="bi bi-graph-up"></i></div>
        <div class="stat-value">฿{{ number_format($avgOrderValue, 0) }}</div>
        <div class="stat-label">ยอดเฉลี่ยต่อออเดอร์</div>
    </div>
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="bi bi-person-plus"></i></div>
        <div class="stat-value">{{ number_format($newCustomers) }}</div>
        <div class="stat-label">สมาชิกใหม่</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-icon gold"><i class="bi bi-tag"></i></div>
        <div class="stat-value">฿{{ number_format($totalDiscount, 0) }}</div>
        <div class="stat-label">ส่วนลดที่ใช้ไป</div>
    </div>
    <div class="stat-card green">
        <div class="stat-icon green"><i class="bi bi-bag-check"></i></div>
        <div class="stat-value">{{ number_format($paidOrders) }}</div>
        <div class="stat-label">ออเดอร์ที่เป็นยอดขาย</div>
    </div>
</div>

{{-- ===== ยอดขายรายวัน + ขายดี ===== --}}
<div style="display:grid;grid-template-columns:3fr 2fr;gap:20px;margin-top:20px;">
    <div class="table-wrap">
        <div class="table-header"><h3><i class="bi bi-calendar3" style="color:var(--g500);"></i> ยอดขายรายวัน</h3></div>
        <div style="padding:20px;">
            @if($dailyRevenue->isEmpty())
                <p style="text-align:center;color:#aaa;">ยังไม่มีข้อมูล</p>
            @else
                @php $maxRevenue = $dailyRevenue->max('total') ?: 1; @endphp
                @foreach($dailyRevenue->take(20) as $day)
                <div class="rpt-bar-row">
                    <div class="rpt-bar-label" style="text-align:right;">{{ \Carbon\Carbon::parse($day->date)->format('d/m') }}</div>
                    <div class="rpt-bar-track"><div class="rpt-bar-fill" style="width:{{ ($day->total/$maxRevenue)*100 }}%;"></div></div>
                    <div class="rpt-bar-val">฿{{ number_format($day->total,0) }}</div>
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

{{-- ===== ยอดขายตามหมวด / ประเภท / ไซส์ ===== --}}
<div class="rpt-section-title"><i class="bi bi-pie-chart"></i> ยอดขายตามกลุ่มสินค้า</div>
<div class="rpt-grid-3">
    @php
        $groupBlocks = [
            ['หมวดหมู่', 'bi-tags', $salesByCategory],
            ['ประเภทสินค้า', 'bi-collection', $salesByType],
            ['ไซส์ (Variant)', 'bi-rulers', $salesBySize],
        ];
    @endphp
    @foreach($groupBlocks as [$title, $icon, $data])
    <div class="table-wrap">
        <div class="table-header"><h3><i class="bi {{ $icon }}" style="color:var(--g500);"></i> {{ $title }}</h3></div>
        <div style="padding:18px;">
            @if($data->isEmpty())
                <p style="text-align:center;color:#aaa;">ยังไม่มีข้อมูล</p>
            @else
                @php $mx = $data->max('revenue') ?: 1; @endphp
                @foreach($data->take(10) as $row)
                <div class="rpt-bar-row">
                    <div class="rpt-bar-label" title="{{ $title=='ไซส์ (Variant)' ? $row->size : $row->name }}">{{ $title=='ไซส์ (Variant)' ? $row->size : $row->name }}</div>
                    <div class="rpt-bar-track"><div class="rpt-bar-fill" style="width:{{ ($row->revenue/$mx)*100 }}%;"></div></div>
                    <div class="rpt-bar-val">฿{{ number_format($row->revenue,0) }}</div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
    @endforeach
</div>

{{-- ===== สต๊อก ===== --}}
<div class="rpt-section-title"><i class="bi bi-boxes"></i> สต๊อกสินค้า</div>
<div class="stats-grid">
    <div class="stat-card blue">
        <div class="stat-icon blue"><i class="bi bi-cash-stack"></i></div>
        <div class="stat-value">฿{{ number_format($inventoryValue, 0) }}</div>
        <div class="stat-label">มูลค่าสต๊อก (ตามราคาขาย)</div>
    </div>
    <div class="stat-card gold">
        <div class="stat-icon gold"><i class="bi bi-exclamation-triangle"></i></div>
        <div class="stat-value">{{ number_format($lowStockCount) }}</div>
        <div class="stat-label">สินค้าสต๊อกต่ำ</div>
    </div>
    <div class="stat-card" style="--c:#ef4444;">
        <div class="stat-icon" style="background:#fee2e2;color:#ef4444;"><i class="bi bi-x-octagon"></i></div>
        <div class="stat-value">{{ number_format($outOfStockCount) }}</div>
        <div class="stat-label">สินค้าหมดสต๊อก</div>
    </div>
    <div class="stat-card" style="--c:#9ca3af;">
        <div class="stat-icon" style="background:#f3f4f6;color:#6b7280;"><i class="bi bi-hourglass-split"></i></div>
        <div class="stat-value">{{ number_format($slowMovingCount) }}</div>
        <div class="stat-label">สินค้าขายช้า (ไม่มียอดในช่วงนี้)</div>
    </div>
</div>

<div class="rpt-grid-2" style="margin-top:18px;">
    <div class="table-wrap">
        <div class="table-header"><h3><i class="bi bi-exclamation-triangle" style="color:#f59e0b;"></i> สินค้า/ไซส์ ที่สต๊อกต่ำ</h3></div>
        <table>
            <thead><tr><th>สินค้า</th><th>ไซส์</th><th>คงเหลือ</th><th>เกณฑ์</th></tr></thead>
            <tbody>
            @forelse($lowStockList as $row)
            <tr>
                <td style="font-size:13px;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $row->name }}</td>
                <td>{{ $row->size ? $row->size : '—' }}</td>
                <td><span style="font-weight:800;color:{{ $row->stock<=0?'#ef4444':'#f59e0b' }};">{{ $row->stock }}</span></td>
                <td class="rpt-mini">{{ $row->threshold }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:24px;color:#aaa;">ไม่มีสินค้าสต๊อกต่ำ 🎉</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="table-wrap">
        <div class="table-header"><h3><i class="bi bi-hourglass-split" style="color:#9ca3af;"></i> สินค้าขายช้า (มีของแต่ไม่ขยับ)</h3></div>
        <table>
            <thead><tr><th>สินค้า</th><th>คงเหลือ</th></tr></thead>
            <tbody>
            @forelse($slowMoving as $p)
            <tr>
                <td style="font-size:13px;max-width:260px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">{{ $p->name }}</td>
                <td style="font-weight:700;">{{ number_format($p->stock_quantity) }}</td>
            </tr>
            @empty
            <tr><td colspan="2" style="text-align:center;padding:24px;color:#aaa;">ไม่มีข้อมูล</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ===== ลูกค้า ===== --}}
<div class="rpt-section-title"><i class="bi bi-people"></i> ลูกค้า</div>
<div class="rpt-grid-2">
    <div class="table-wrap">
        <div class="table-header"><h3><i class="bi bi-star" style="color:var(--gold);"></i> ลูกค้าซื้อสูงสุด</h3></div>
        <table>
            <thead><tr><th>#</th><th>ลูกค้า</th><th>ออเดอร์</th><th>ยอดซื้อ</th></tr></thead>
            <tbody>
            @forelse($topCustomers as $i => $c)
            <tr>
                <td style="font-weight:800;color:#bbb;">{{ $i+1 }}</td>
                <td style="font-size:13px;">{{ $customerNames[$c->customer_id] ?? 'ลูกค้า #'.$c->customer_id }}</td>
                <td>{{ number_format($c->orders) }}</td>
                <td style="font-weight:700;color:var(--g600);">฿{{ number_format($c->spent,0) }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:24px;color:#aaa;">ยังไม่มีข้อมูล</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div>
        <div class="stats-grid" style="grid-template-columns:1fr 1fr;">
            <div class="stat-card green">
                <div class="stat-icon green"><i class="bi bi-person-check"></i></div>
                <div class="stat-value">{{ number_format($newBuyerCount) }}</div>
                <div class="stat-label">ลูกค้าใหม่ที่ซื้อในช่วงนี้</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon blue"><i class="bi bi-arrow-repeat"></i></div>
                <div class="stat-value">{{ number_format($returningCount) }}</div>
                <div class="stat-label">ลูกค้าเก่าที่กลับมาซื้อ</div>
            </div>
        </div>
        <div class="table-wrap" style="margin-top:18px;">
            <div class="table-header"><h3><i class="bi bi-geo-alt" style="color:var(--g500);"></i> ยอดขายตามจังหวัด</h3></div>
            <table>
                <thead><tr><th>จังหวัด</th><th>ออเดอร์</th><th>ยอด</th></tr></thead>
                <tbody>
                @forelse($topProvinces as $prov)
                <tr>
                    <td style="font-size:13px;">{{ $prov->ship_province }}</td>
                    <td>{{ number_format($prov->orders) }}</td>
                    <td style="font-weight:700;color:var(--g600);">฿{{ number_format($prov->revenue,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="3" style="text-align:center;padding:24px;color:#aaa;">ยังไม่มีข้อมูล (อาจเป็นออเดอร์รับที่ร้าน)</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===== ออเดอร์ตามสถานะ ===== --}}
<div class="rpt-section-title"><i class="bi bi-clipboard-data"></i> ออเดอร์ตามสถานะ
    <span class="rpt-mini" style="font-weight:400;margin-left:8px;">อัตรายกเลิก/คืนเงิน: <b style="color:{{ $cancellationRate>10?'#dc2626':'#16a34a' }};">{{ number_format($cancellationRate,1) }}%</b></span>
</div>
<div class="table-wrap">
    <div style="padding:18px;display:flex;flex-wrap:wrap;gap:12px;">
        @forelse($statusLabels as $key => $info)
            @php $row = $ordersByStatus[$key] ?? null; $color = $statusColors[$key] ?? '#9ca3af'; @endphp
            <div style="flex:1;min-width:160px;border:1px solid #eef0ee;border-radius:14px;padding:14px;">
                <div class="rpt-pill" style="background:{{ $color }}1a;color:{{ $color }};"><span style="width:8px;height:8px;border-radius:999px;background:{{ $color }};"></span>{{ $info['label'] }}</div>
                <div style="font-size:24px;font-weight:800;margin-top:8px;color:#222;">{{ number_format($row->count ?? 0) }}</div>
                <div class="rpt-mini">฿{{ number_format($row->total ?? 0, 0) }}</div>
            </div>
        @empty
        @endforelse
    </div>
</div>

{{-- ===== คูปอง + B2B ===== --}}
<div class="rpt-section-title"><i class="bi bi-megaphone"></i> การตลาด & B2B</div>
<div class="rpt-grid-2">
    <div class="table-wrap">
        <div class="table-header"><h3><i class="bi bi-ticket-perforated" style="color:var(--g500);"></i> ประสิทธิภาพคูปอง</h3></div>
        <table>
            <thead><tr><th>คูปอง</th><th>ใช้</th><th>ส่วนลดรวม</th><th>ยอดขาย</th></tr></thead>
            <tbody>
            @forelse($couponPerformance as $cp)
            @php $info = $couponInfo[$cp->coupon_id] ?? null; @endphp
            <tr>
                <td style="font-size:13px;">
                    <b>{{ $info->code ?? '—' }}</b>
                    <div class="rpt-mini">{{ $info->name ?? '' }}</div>
                </td>
                <td style="font-weight:700;">{{ number_format($cp->uses) }}</td>
                <td style="color:#dc2626;">-฿{{ number_format($cp->discount,0) }}</td>
                <td style="font-weight:700;color:var(--g600);">฿{{ number_format($cp->revenue,0) }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:24px;color:#aaa;">ไม่มีการใช้คูปองในช่วงนี้</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div>
        <div class="table-wrap">
            <div class="table-header"><h3><i class="bi bi-file-earmark-text" style="color:var(--g500);"></i> ใบเสนอราคา (B2B)</h3></div>
            <div style="padding:18px;">
                <div style="display:flex;gap:14px;flex-wrap:wrap;margin-bottom:6px;">
                    <div style="flex:1;min-width:90px;text-align:center;">
                        <div style="font-size:24px;font-weight:800;color:#222;">{{ number_format($quoteTotal) }}</div>
                        <div class="rpt-mini">คำขอทั้งหมด</div>
                    </div>
                    <div style="flex:1;min-width:90px;text-align:center;">
                        <div style="font-size:24px;font-weight:800;color:var(--g600);">{{ number_format($quoteRate,0) }}%</div>
                        <div class="rpt-mini">อัตราปิดการขาย</div>
                    </div>
                    <div style="flex:1;min-width:90px;text-align:center;">
                        <div style="font-size:24px;font-weight:800;color:var(--gold-d,#b8860b);">฿{{ number_format($quoteValue,0) }}</div>
                        <div class="rpt-mini">มูลค่าที่ตกลง</div>
                    </div>
                </div>
                <div style="border-top:1px solid #f0f0f0;margin-top:10px;padding-top:10px;">
                    @foreach(['pending'=>'รอเสนอราคา','quoted'=>'เสนอราคาแล้ว','accepted'=>'ตกลงแล้ว','rejected'=>'ปฏิเสธ','closed'=>'ปิดงาน'] as $k=>$lbl)
                    <div style="display:flex;justify-content:space-between;font-size:13px;padding:4px 0;">
                        <span style="color:#555;">{{ $lbl }}</span>
                        <b>{{ number_format($quoteCounts[$k] ?? 0) }}</b>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="stats-grid" style="grid-template-columns:1fr 1fr;margin-top:18px;">
            <div class="stat-card gold">
                <div class="stat-icon gold"><i class="bi bi-star-fill"></i></div>
                <div class="stat-value">{{ number_format($reviewAvg,1) }}</div>
                <div class="stat-label">คะแนนรีวิวเฉลี่ย ({{ number_format($reviewCount) }} รีวิว)</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-icon blue"><i class="bi bi-hourglass"></i></div>
                <div class="stat-value">{{ number_format($reviewPending) }}</div>
                <div class="stat-label">รีวิวรออนุมัติ</div>
            </div>
        </div>
    </div>
</div>

{{-- ===== Wishlist ===== --}}
<div class="rpt-section-title"><i class="bi bi-heart"></i> สินค้าที่ถูกใจมากสุด (Wishlist)</div>
<div class="table-wrap">
    <table>
        <thead><tr><th>#</th><th>สินค้า</th><th>จำนวนที่ถูกใจ</th></tr></thead>
        <tbody>
        @forelse($topWishlist as $i => $w)
        <tr>
            <td style="font-weight:800;color:#bbb;">{{ $i+1 }}</td>
            <td style="font-size:13px;">{{ $wishlistNames[$w->product_id] ?? 'สินค้า #'.$w->product_id }}</td>
            <td style="font-weight:700;color:#ec4899;"><i class="bi bi-heart-fill"></i> {{ number_format($w->c) }}</td>
        </tr>
        @empty
        <tr><td colspan="3" style="text-align:center;padding:24px;color:#aaa;">ยังไม่มีข้อมูล</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
