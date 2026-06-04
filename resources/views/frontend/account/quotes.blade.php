@extends('layouts.app')
@section('title', 'ใบเสนอราคาของฉัน')
@section('content')
<div class="container acc-wrap">
    <div class="acc-layout">
        @include('frontend.account._sidebar')
        <div>
            <h2 style="font-size:20px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;"><i class="bi bi-file-earmark-text"></i> คำขอใบเสนอราคา</h2>
            @forelse($quotes as $q)
            <div class="acc-card" style="margin-bottom:16px;">
                <div style="display:flex;justify-content:space-between;align-items:start;flex-wrap:wrap;gap:10px;margin-bottom:12px;">
                    <div>
                        <div style="font-weight:800;font-size:16px;">{{ $q->company_name }}</div>
                        <div style="font-size:13px;color:#888;">{{ $q->created_at->format('d/m/Y') }}</div>
                    </div>
                    <span class="status-badge {{ $q->status === 'quoted' ? 'status-blue' : ($q->status === 'accepted' ? 'status-green' : 'status-yellow') }}">
                        {{ ['pending'=>'รอดำเนินการ','quoted'=>'ได้รับใบเสนอราคาแล้ว','accepted'=>'ตอบรับ','rejected'=>'ปฏิเสธ','closed'=>'ปิด'][$q->status] }}
                    </span>
                </div>
                <div style="font-size:13px;color:#555;margin-bottom:8px;">{{ Str::limit($q->product_details, 200) }}</div>
                @if($q->quoted_price)
                <div style="font-size:18px;font-weight:800;color:var(--kgm-green-700);">ราคาที่เสนอ: ฿{{ number_format($q->quoted_price, 2) }}</div>
                @endif
                @if($q->quote_pdf)
                <a href="{{ asset('storage/'.$q->quote_pdf) }}" class="btn btn-sm btn-gold" target="_blank" style="margin-top:8px;"><i class="bi bi-file-pdf"></i> ดาวน์โหลดใบเสนอราคา</a>
                @endif
            </div>
            @empty
            <div class="acc-card" style="text-align:center;padding:64px 28px;">
                <p style="color:#aaa;margin-bottom:16px;">ยังไม่มีคำขอใบเสนอราคา</p>
                <a href="{{ route('quote') }}" class="btn btn-primary">ขอใบเสนอราคา</a>
            </div>
            @endforelse
            <div class="pagination">{{ $quotes->links() }}</div>
        </div>
    </div>
</div>
@endsection
