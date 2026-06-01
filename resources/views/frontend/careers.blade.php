@extends('layouts.app')
@section('title', 'ร่วมงานกับเรา')
@section('content')
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="container"><h1 style="font-size:40px;font-weight:800;color:white;">ร่วมงานกับเรา</h1><p style="color:rgba(255,255,255,0.8);margin-top:10px;">เปิดรับสมัครพนักงานทุกตำแหน่ง</p></div>
</div>
<div class="container" style="padding:64px 24px;max-width:900px;">
    @if($careers->isEmpty())
    <div style="text-align:center;background:white;border-radius:20px;padding:64px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
        <i class="bi bi-briefcase" style="font-size:48px;color:#ddd;display:block;margin-bottom:16px;"></i>
        <p style="color:#aaa;">ขณะนี้ยังไม่มีตำแหน่งที่เปิดรับ กรุณากลับมาตรวจสอบอีกครั้ง</p>
    </div>
    @else
    <div style="display:flex;flex-direction:column;gap:16px;">
        @foreach($careers as $career)
        <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
            <div style="display:flex;align-items:start;justify-content:space-between;flex-wrap:wrap;gap:12px;">
                <div>
                    <h3 style="font-size:18px;font-weight:800;color:var(--kgm-green-800);">{{ $career->title }}</h3>
                    <div style="display:flex;gap:10px;margin-top:6px;flex-wrap:wrap;">
                        @if($career->department)<span style="font-size:12px;background:var(--kgm-green-100);color:var(--kgm-green-700);padding:3px 10px;border-radius:999px;font-weight:600;">{{ $career->department }}</span>@endif
                        <span style="font-size:12px;background:#f0f4f0;color:#555;padding:3px 10px;border-radius:999px;">{{ ['full_time'=>'ประจำ','part_time'=>'นอกเวลา','contract'=>'สัญญาจ้าง'][$career->type] }}</span>
                        @if($career->location)<span style="font-size:12px;color:#888;"><i class="bi bi-geo-alt"></i> {{ $career->location }}</span>@endif
                    </div>
                </div>
                <a href="{{ route('careers.show', $career) }}" class="btn btn-primary"><i class="bi bi-arrow-right-circle"></i> ดูรายละเอียด/สมัคร</a>
            </div>
            <p style="font-size:14px;color:#666;margin-top:12px;line-height:1.7;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ strip_tags($career->description) }}</p>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
