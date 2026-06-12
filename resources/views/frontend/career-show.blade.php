@extends('layouts.app')
@section('title', $career->title.' - ร่วมงานกับเรา')
@section('content')

<div class="container py-5" style="max-width:1000px;">

    <a href="{{ route('careers') }}" class="d-inline-flex align-items-center gap-2 text-decoration-none fw-semibold mb-4" style="color:var(--kgm-green-600);">
        <i class="bi bi-arrow-left"></i> ตำแหน่งงานทั้งหมด
    </a>

    <div class="row g-4 align-items-start">

        {{-- Left: job detail --}}
        <div class="col-12 col-md-7 d-flex flex-column gap-4">

            <div class="card p-4">
                <h1 class="fw-bold mb-2" style="font-size:24px;color:var(--kgm-green-800);">{{ $career->title }}</h1>
                <div class="d-flex flex-wrap gap-2 mb-3">
                    @if($career->department)
                        <span class="badge badge-new">{{ $career->department }}</span>
                    @endif
                    <span class="text-secondary small"><i class="bi bi-geo-alt"></i> {{ $career->location ?? 'กรุงเทพฯ' }}</span>
                    <span class="text-secondary small"><i class="bi bi-person-fill"></i> รับ {{ $career->vacancies }} อัตรา</span>
                </div>
                <div class="text-secondary lh-lg" style="font-size:15px;">{!! nl2br(e($career->description)) !!}</div>
            </div>

            @if($career->requirements)
            <div class="card p-4">
                <h5 class="fw-bold mb-3" style="color:var(--kgm-green-800);"><i class="bi bi-check-circle me-1"></i> คุณสมบัติที่ต้องการ</h5>
                <div class="text-secondary lh-lg small">{!! nl2br(e($career->requirements)) !!}</div>
            </div>
            @endif

            @if($career->benefits)
            <div class="card p-4">
                <h5 class="fw-bold mb-3" style="color:var(--kgm-green-800);"><i class="bi bi-gift me-1"></i> สวัสดิการ</h5>
                <div class="text-secondary lh-lg small">{!! nl2br(e($career->benefits)) !!}</div>
            </div>
            @endif

        </div>

        {{-- Right: apply form --}}
        <div class="col-12 col-md-5">
            <div class="card p-4">
                <h5 class="fw-bold mb-4" style="color:var(--kgm-green-800);"><i class="bi bi-person-plus me-1"></i> สมัครงาน</h5>
                <form method="POST" action="{{ route('careers.apply', $career) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group"><label class="form-label">ชื่อ *</label><input type="text" name="first_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">นามสกุล *</label><input type="text" name="last_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">อีเมล *</label><input type="email" name="email" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">เบอร์โทร *</label><input type="text" name="phone" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">วันเกิด</label><input type="date" name="birthdate" class="form-control"></div>
                    <div class="form-group"><label class="form-label">Resume (PDF)</label><input type="file" name="resume" class="form-control" accept=".pdf,.doc,.docx"></div>
                    <div class="form-group"><label class="form-label">จดหมายแนะนำตัว</label><textarea name="cover_letter" class="form-control" rows="4"></textarea></div>
                    <button type="submit" class="btn btn-primary w-full" style="justify-content:center;"><i class="bi bi-send"></i> ส่งใบสมัคร</button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
