@extends('layouts.app')
@section('title', 'สมัครตัวแทนจำหน่าย')
@section('content')
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="container"><h1 style="font-size:40px;font-weight:800;color:white;">ร่วมเป็นตัวแทนจำหน่าย KGM</h1></div>
</div>
<div class="container" style="padding:64px 24px;max-width:800px;">
    <div style="display:grid;grid-template-columns:1fr 1.5fr;gap:32px;align-items:start;">
        <div>
            <h2 style="font-size:22px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;">ประโยชน์ที่ได้รับ</h2>
            @foreach(['ราคาพิเศษส่วนลดตัวแทน','สต๊อกสินค้าครบ พร้อมจัดส่ง','สนับสนุนสื่อการตลาด','ทีมงานให้คำปรึกษา','อบรมผลิตภัณฑ์ฟรี'] as $b)
            <div style="display:flex;gap:10px;margin-bottom:12px;font-size:14px;"><i class="bi bi-check-circle-fill" style="color:var(--kgm-gold-500);font-size:16px;flex-shrink:0;"></i>{{ $b }}</div>
            @endforeach
        </div>
        <div style="background:white;border-radius:24px;padding:32px;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <form method="POST" action="{{ route('dealer.submit') }}">
                @csrf
                <div class="form-group"><label class="form-label">ชื่อร้าน/บริษัท *</label><input type="text" name="business_name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">ชื่อผู้ติดต่อ *</label><input type="text" name="contact_name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">อีเมล *</label><input type="email" name="email" class="form-control" required></div>
                <div class="form-group"><label class="form-label">เบอร์โทร *</label><input type="text" name="phone" class="form-control" required></div>
                <div class="form-group"><label class="form-label">ที่อยู่ *</label><textarea name="address" class="form-control" rows="2" required></textarea></div>
                <div class="form-group"><label class="form-label">จังหวัด *</label><input type="text" name="province" class="form-control" required></div>
                <div class="form-group"><label class="form-label">ประเภทธุรกิจ</label><input type="text" name="business_type" class="form-control" placeholder="ร้านค้า, ออนไลน์, ฯลฯ"></div>
                <div class="form-group"><label class="form-label">เลขประจำตัวผู้เสียภาษี</label><input type="text" name="tax_id" class="form-control"></div>
                <button type="submit" class="btn btn-primary w-full btn-lg" style="justify-content:center;"><i class="bi bi-shop"></i> สมัครตัวแทนจำหน่าย</button>
            </form>
        </div>
    </div>
</div>
@endsection
