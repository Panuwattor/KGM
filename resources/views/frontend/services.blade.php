@extends('layouts.app')
@section('title', 'บริการของเรา')
@section('content')
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:80px 0;text-align:center;">
    <div class="container">
        <h1 style="font-size:44px;font-weight:800;color:white;">บริการของเรา</h1>
        <p style="color:rgba(255,255,255,0.8);font-size:18px;margin-top:12px;">ครบวงจรตั้งแต่ออกแบบจนถึงส่งมอบ</p>
    </div>
</div>
<div class="container" style="padding:64px 24px;">
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:32px;">
        @foreach([
            ['bi-mortarboard-fill','เครื่องแบบนักเรียน','ผลิตชุดนักเรียนครบชุดทุกระดับชั้น ชาย-หญิง ตั้งแต่ระดับอนุบาลถึงมัธยมปลาย พร้อมชุดพลศึกษา ชุดเครื่องแบบลูกเสือ-เนตรนารี'],
            ['bi-person-badge-fill','ยูนิฟอร์มองค์กร','ออกแบบและผลิตชุดยูนิฟอร์มสำหรับบริษัท โรงแรม โรงพยาบาล ร้านอาหาร และองค์กรทุกประเภท'],
            ['bi-scissors','บริการปักโลโก้','บริการปักโลโก้และตราสัญลักษณ์บนเครื่องแบบ ด้วยเครื่องปักอัตโนมัติ ได้งานละเอียด คมชัด ทนทาน'],
            ['bi-printer','บริการสกรีน','สกรีนลายต่างๆ ลงบนผ้า เสื้อ และชิ้นงาน ด้วยหมึกคุณภาพสูง ทนต่อการซัก'],
        ] as [$icon,$title,$desc])
        <div style="background:white;border-radius:24px;padding:32px;box-shadow:0 4px 20px rgba(0,0,0,0.07);display:flex;gap:20px;">
            <div style="width:60px;height:60px;background:var(--kgm-green-100);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:28px;color:var(--kgm-green-600);flex-shrink:0;"><i class="bi {{ $icon }}"></i></div>
            <div>
                <h3 style="font-size:18px;font-weight:800;color:var(--kgm-green-800);margin-bottom:8px;">{{ $title }}</h3>
                <p style="font-size:14px;color:#666;line-height:1.8;">{{ $desc }}</p>
            </div>
        </div>
        @endforeach
    </div>
    <div style="text-align:center;margin-top:48px;background:linear-gradient(135deg,var(--kgm-green-100),var(--kgm-gold-100));border-radius:24px;padding:48px;">
        <h2 style="font-size:28px;font-weight:800;color:var(--kgm-green-800);margin-bottom:12px;">สนใจใช้บริการ?</h2>
        <p style="color:#555;margin-bottom:24px;">ติดต่อเราเพื่อรับคำปรึกษาและใบเสนอราคาฟรี ไม่มีค่าใช้จ่าย</p>
        <a href="{{ route('quote') }}" class="btn btn-primary btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคา</a>
    </div>
</div>
@endsection
