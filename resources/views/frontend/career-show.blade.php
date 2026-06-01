@extends('layouts.app')
@section('title', $career->title.' - ร่วมงานกับเรา')
@section('content')
<div class="container" style="padding:40px 24px 64px;max-width:900px;">
    <a href="{{ route('careers') }}" style="display:inline-flex;gap:6px;align-items:center;color:var(--kgm-green-600);font-weight:600;margin-bottom:20px;"><i class="bi bi-arrow-left"></i> ตำแหน่งงานทั้งหมด</a>
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;align-items:start;">
        <div>
            <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;">
                <h1 style="font-size:26px;font-weight:800;color:var(--kgm-green-800);margin-bottom:8px;">{{ $career->title }}</h1>
                <div style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:20px;">
                    @if($career->department)<span class="badge badge-new">{{ $career->department }}</span>@endif
                    <span style="font-size:13px;color:#888;"><i class="bi bi-geo-alt"></i> {{ $career->location ?? 'กรุงเทพฯ' }}</span>
                    <span style="font-size:13px;color:#888;"><i class="bi bi-person-fill"></i> รับ {{ $career->vacancies }} อัตรา</span>
                </div>
                <div style="font-size:15px;color:#444;line-height:1.9;">{!! nl2br(e($career->description)) !!}</div>
            </div>
            @if($career->requirements)
            <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;">
                <h3 style="font-weight:800;color:var(--kgm-green-800);margin-bottom:12px;"><i class="bi bi-check-circle"></i> คุณสมบัติที่ต้องการ</h3>
                <div style="font-size:14px;color:#444;line-height:1.9;">{!! nl2br(e($career->requirements)) !!}</div>
            </div>
            @endif
            @if($career->benefits)
            <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                <h3 style="font-weight:800;color:var(--kgm-green-800);margin-bottom:12px;"><i class="bi bi-gift"></i> สวัสดิการ</h3>
                <div style="font-size:14px;color:#444;line-height:1.9;">{!! nl2br(e($career->benefits)) !!}</div>
            </div>
            @endif
        </div>
        <div>
            <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                <h3 style="font-weight:800;margin-bottom:20px;color:var(--kgm-green-800);"><i class="bi bi-person-plus"></i> สมัครงาน</h3>
                <form method="POST" action="{{ route('careers.apply', $career) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group"><label class="form-label">ชื่อ *</label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">นามสกุล *</label><input type="text" name="last_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">อีเมล *</label><input type="email" name="email" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">เบอร์โทร *</label><input type="text" name="phone" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">วันเกิด</label><input type="date" name="birthdate" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Resume (PDF)</label><input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx" style="border-radius:12px;"></div>
                    <div class="form-group"><label class="form-label">จดหมายแนะนำตัว</label><textarea name="cover_letter" class="form-control" rows="4"></textarea></div>
                    <button type="submit" class="btn btn-primary w-full" style="justify-content:center;"><i class="bi bi-send"></i> ส่งใบสมัคร</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
