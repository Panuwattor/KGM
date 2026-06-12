@extends('layouts.app')
@section('title', 'บริการของเรา')
@section('content')
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 16px;text-align:center;">
    <div class="container">
        <h1 style="font-size:clamp(28px,6vw,44px);font-weight:800;color:white;">บริการของเรา</h1>
        <p style="color:rgba(255,255,255,0.8);font-size:clamp(15px,4vw,18px);margin-top:12px;">ครบวงจรตั้งแต่ออกแบบจนถึงส่งมอบ</p>
    </div>
</div>
<div class="container" style="padding:48px 16px;">
    @php
    $services = [
        ['service1.jpg','บริการออกแบบและสั่งผลิต','บริการงานปักและสกรีน เรามีจักรปักคุณภาพมาตรฐานสากล มีทั้งประเภท 20 หัวปัก 4 หัวปัก และ 1 หัวปัก พร้อมด้วยทีมงานออกแบบลายปัก ที่มีประสบการณ์มากว่า 40 ปี รองรับงานปักจำนวนมาก ด้วยไหมปักที่มีคุณภาพ', 'order'],
        ['service2.jpg','เครื่องแบบนักเรียนและองค์กร','ออกแบบและผลิตชุดนักเรียนและชุดยูนิฟอร์มองค์กรตามความต้องการของลูกค้า เราผลิตชุดนักเรียนครบชุดทุกระดับชั้น ชาย-หญิง พร้อมชุดพลศึกษา ชุดเครื่องแบบลูกเสือ-เนตรนารี และยูนิฟอร์มสำหรับบริษัทและองค์กรทุกประเภท', 'school-uniforms'],
        ['service3.jpg','บริการงานปักและสกรีน','บริการออกแบบและสั่งผลิต เสื้อยูนิฟอร์ม เสื้อหน่วยงาน เครื่องแบบนักเรียน เสื้อโปโล เสื้อแจ็คเก็ต เสื้อยืดคอกลม ชุดพนักงานราชการ ชุดคนไข้โรงพยาบาล ชุดกีฬา ถุงผ้า เรามีพนักงานที่มีความสามารถและมีประสบการณ์ กว่า 40 ปี', 'embroidery-screen'],
    ];
    @endphp

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:24px;">
        @foreach($services as [$img,$title,$desc,$route])
        <a href="{{ route($route) }}" style="text-decoration:none;display:block;">
            <div style="background:white;border-radius:20px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.07);transition:transform 0.2s,box-shadow 0.2s;"
                onmouseover="this.style.transform='translateY(-4px)';this.style.boxShadow='0 10px 28px rgba(0,0,0,0.13)'"
                onmouseout="this.style.transform='';this.style.boxShadow='0 4px 20px rgba(0,0,0,0.07)'">
                <img src="{{ asset('images/service/'.$img) }}" alt="{{ $title }}" style="width:100%;aspect-ratio:16/9;object-fit:cover;display:block;">
                <div style="padding:20px;">
                    <h3 style="font-size:16px;font-weight:800;color:var(--kgm-green-800);margin-bottom:8px;">{{ $title }}</h3>
                    <p style="font-size:14px;color:#666;line-height:1.8;margin:0;">{{ $desc }}</p>
                    <div style="margin-top:14px;font-size:13px;font-weight:700;color:var(--kgm-green-600);">ดูเพิ่มเติม <i class="bi bi-arrow-right"></i></div>
                </div>
            </div>
        </a>
        @endforeach
    </div>

    <div style="text-align:center;margin-top:40px;background:linear-gradient(135deg,var(--kgm-green-100),var(--kgm-gold-100));border-radius:20px;padding:36px 24px;">
        <h2 style="font-size:clamp(20px,5vw,28px);font-weight:800;color:var(--kgm-green-800);margin-bottom:12px;">สนใจใช้บริการ?</h2>
        <p style="color:#555;margin-bottom:24px;">ติดต่อเราเพื่อรับคำปรึกษาและใบเสนอราคาฟรี ไม่มีค่าใช้จ่าย</p>
        <a href="{{ route('quote') }}" class="btn btn-primary btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคา</a>
    </div>
</div>
@endsection
