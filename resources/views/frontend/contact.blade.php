@extends('layouts.app')
@section('title', 'ติดต่อเรา')
@section('content')
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-weight:700;letter-spacing:2px;text-transform:uppercase;font-size:13px;margin-bottom:10px;">ติดต่อเรา</div>
        <h1 style="font-size:40px;font-weight:800;color:white;">ยินดีให้คำปรึกษาเสมอ</h1>
    </div>
</div>
<div class="container" style="padding:64px 24px;">
    <div style="display:grid;grid-template-columns:1fr 1.5fr;gap:40px;">
        <div>
            <h2 style="font-size:24px;font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;">ข้อมูลติดต่อ</h2>
            @foreach([
                ['bi-geo-alt-fill','ที่อยู่','123 ถนนเจริญกรุง แขวงบางรัก กรุงเทพฯ 10500'],
                ['bi-telephone-fill','โทรศัพท์','02-000-1234'],
                ['bi-envelope-fill','อีเมล','info@kgm1993.com'],
                ['bi-line','Line ID','@KGM1993'],
                ['bi-clock-fill','เวลาทำการ','จ-ศ 08:00-17:30 น.'],
            ] as [$icon,$label,$value])
            <div style="display:flex;gap:14px;margin-bottom:20px;">
                <div style="width:44px;height:44px;background:var(--kgm-green-100);border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:var(--kgm-green-600);font-size:18px;"><i class="bi {{ $icon }}"></i></div>
                <div><div style="font-weight:700;font-size:14px;">{{ $label }}</div><div style="color:#666;font-size:14px;">{{ $value }}</div></div>
            </div>
            @endforeach
        </div>
        <div style="background:white;border-radius:24px;padding:32px;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            <h2 style="font-size:20px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;">ส่งข้อความถึงเรา</h2>
            <form method="POST" action="{{ route('contact.submit') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group"><label class="form-label">ชื่อ *</label><input type="text" name="name" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">อีเมล *</label><input type="email" name="email" class="form-control" required></div>
                    <div class="form-group"><label class="form-label">เบอร์โทร</label><input type="text" name="phone" class="form-control"></div>
                    <div class="form-group"><label class="form-label">เรื่อง</label><input type="text" name="subject" class="form-control"></div>
                    <div class="form-group col-span-2"><label class="form-label">ข้อความ *</label><textarea name="message" class="form-control" rows="5" required></textarea></div>
                </div>
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-send"></i> ส่งข้อความ</button>
            </form>
        </div>
    </div>
</div>
@endsection
