@extends('layouts.app')
@section('title', 'สมัครตัวแทนจำหน่าย')
@section('content')

<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));" class="py-5 text-center text-white">
    <div class="container">
        <h1 class="fw-bold mb-0" style="font-size:clamp(24px,5vw,40px);">ร่วมเป็นตัวแทนจำหน่าย KGM</h1>
    </div>
</div>

<div class="container py-5" style="max-width:860px;">
    <div class="row g-4 align-items-start">

        {{-- Left: benefits --}}
        <div class="col-12 col-md-5">
            <h2 class="fw-bold mb-4" style="font-size:20px;color:var(--kgm-green-800);">ประโยชน์ที่ได้รับ</h2>
            @foreach(['ราคาพิเศษส่วนลดตัวแทน','สต๊อกสินค้าครบ พร้อมจัดส่ง','สนับสนุนสื่อการตลาด','ทีมงานให้คำปรึกษา','อบรมผลิตภัณฑ์ฟรี'] as $b)
            <div class="d-flex align-items-start gap-2 mb-3 small">
                <i class="bi bi-check-circle-fill flex-shrink-0 mt-1" style="color:var(--kgm-gold-500);font-size:16px;"></i>
                <span>{{ $b }}</span>
            </div>
            @endforeach
        </div>

        {{-- Right: form --}}
        <div class="col-12 col-md-7">
            <div class="card p-4">
                <h5 class="fw-bold mb-4" style="color:var(--kgm-green-800);"><i class="bi bi-shop me-1"></i> ข้อมูลการสมัคร</h5>
                <form method="POST" action="{{ route('dealer.submit') }}">
                    @csrf
                    <div class="form-group"><label class="form-label">ชื่อร้าน/บริษัท *</label><input type="text" name="business_name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">ชื่อผู้ติดต่อ *</label><input type="text" name="contact_name" class="form-control" required></div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 form-group"><label class="form-label">อีเมล *</label><input type="email" name="email" class="form-control" required></div>
                        <div class="col-12 col-sm-6 form-group"><label class="form-label">เบอร์โทร *</label><input type="text" name="phone" class="form-control" required></div>
                    </div>
                    <div class="form-group"><label class="form-label">ที่อยู่ *</label><textarea name="address" class="form-control" rows="2" required></textarea></div>
                    <div class="row g-3">
                        <div class="col-12 col-sm-6 form-group"><label class="form-label">จังหวัด *</label><input type="text" name="province" class="form-control" required></div>
                        <div class="col-12 col-sm-6 form-group"><label class="form-label">ประเภทธุรกิจ</label><input type="text" name="business_type" class="form-control" placeholder="ร้านค้า, ออนไลน์, ฯลฯ"></div>
                    </div>
                    <div class="form-group"><label class="form-label">เลขประจำตัวผู้เสียภาษี</label><input type="text" name="tax_id" class="form-control"></div>
                    <button type="submit" class="btn btn-primary w-full mt-2" style="justify-content:center;"><i class="bi bi-send"></i> ส่งใบสมัคร</button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection
