@extends('layouts.app')
@section('title', 'เครื่องแบบนักเรียน - KGM')

@section('content')

<style>
.sch-uniforms-grid { display:grid; grid-template-columns:repeat(5,1fr); gap:16px; }
.sch-scout-grid    { display:grid; grid-template-columns:repeat(4,1fr); gap:20px; max-width:900px; margin:0 auto; }
@media(max-width:767px){
    .sch-uniforms-grid        { grid-template-columns:repeat(2,1fr); }
    .sch-scout-grid           { grid-template-columns:repeat(2,1fr); }
    .grid.grid-4              { grid-template-columns:repeat(2,1fr) !important; }
}
@media(min-width:768px) and (max-width:991px){
    .sch-uniforms-grid { grid-template-columns:repeat(3,1fr); }
}
</style>

{{-- ══ HERO ══ --}}
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:80px 0 60px;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin-bottom:10px;">ผลิตภัณฑ์ของเรา</div>
        <h1 style="font-size:clamp(28px,5vw,48px);font-weight:800;color:white;margin:0 0 14px;line-height:1.3;">เครื่องแบบนักเรียน</h1>
        <p style="color:rgba(255,255,255,0.8);font-size:16px;max-width:580px;margin:0 auto 28px;line-height:1.8;">
            ผลิตครบชุดทุกระดับชั้น ชาย-หญิง อนุบาลถึงมัธยม<br>
            รวมถึงชุดลูกเสือ เนตรนารี และยุวกาชาด
        </p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('quote') }}" class="btn btn-gold btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี</a>
            <a href="{{ route('shop') }}" class="btn btn-lg" style="background:rgba(255,255,255,0.12);color:white;border:1.5px solid rgba(255,255,255,0.3);"><i class="bi bi-grid-3x3-gap"></i> ดูสินค้าในร้าน</a>
        </div>
    </div>
</div>

{{-- ══ SCHOOL UNIFORMS GRID ══ --}}
<section class="section" style="background:white;">
    <div class="container">
        <div style="text-align:center;margin-bottom:36px;">
            <div class="section-subtitle" style="text-align:center;">ตัวอย่างสินค้า</div>
            <div class="section-title">ตัวอย่างเครื่องแบบนักเรียน</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div class="sch-uniforms-grid">
            @foreach([
                ['01', 'เสื้ออนุบาลปกบัว+กระโปรง'],
                ['02', 'เสื้อฮาวายดุมเอว+กางเกงสีแดง'],
                ['03', 'กระโปรงอนุบาลรังดุม-สีแดง'],
                ['04', 'เสื้อเชิ้ตนักเรียนชาย+กางเกงกากี'],
                ['05', 'เสื้อเชิ้ตนักเรียนชาย+กางเกงดำ'],
                ['06', 'เสื้อเชิ้ตนักเรียนชาย+กางเกงกรมท่า'],
                ['07', 'เสื้อปกบัวผ่าตลอด+กระโปรงจีบรอบ'],
                ['08', 'เสื้อปกทหารเรือ+กระโปรง 6 จีบ'],
                ['09', 'เสื้อปกบัวโปโล+กระโปรงจีบรอบ'],
                ['10', 'เสื้อเตรียมแขนพอง+กระโปรง 6 จีบ'],
            ] as [$num, $name])
            <div style="border-radius:14px;overflow:hidden;background:#f8f8f8;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
                <div style="aspect-ratio:9/16;overflow:hidden;background:#eee;">
                    <img src="/images/uniforms/{{ $num }}.jpg"
                         alt="{{ $name }}"
                         loading="lazy"
                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;"
                         onmouseover="this.style.transform='scale(1.04)'"
                         onmouseout="this.style.transform=''">
                </div>
                <div style="padding:12px 14px;">
                    <p style="font-size:13px;font-weight:600;color:var(--kgm-green-800);margin:0;line-height:1.5;">{{ $name }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ SCOUT / GUIDE ══ --}}
<section class="section" style="background:#f8faf8;">
    <div class="container">
        <div style="text-align:center;margin-bottom:36px;">
            <div class="section-subtitle" style="text-align:center;">ชุดพิเศษ</div>
            <div class="section-title">เครื่องแบบลูกเสือ-เนตรนารี-ยุวกาชาด</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div class="sch-scout-grid">
            @foreach([
                ['11', 'ชุดลูกเสือสำรอง'],
                ['12', 'ชุดลูกเสือสามัญ'],
                ['13', 'ชุดยุวกาชาด'],
                ['14', 'ชุดเนตรนารี'],
            ] as [$num, $name])
            <div style="border-radius:14px;overflow:hidden;background:white;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
                <div style="aspect-ratio:9/16;overflow:hidden;background:#eee;">
                    <img src="/images/uniforms/{{ $num }}.jpg"
                         alt="{{ $name }}"
                         loading="lazy"
                         style="width:100%;height:100%;object-fit:cover;transition:transform 0.3s;"
                         onmouseover="this.style.transform='scale(1.04)'"
                         onmouseout="this.style.transform=''">
                </div>
                <div style="padding:12px 14px;text-align:center;">
                    <p style="font-size:14px;font-weight:700;color:var(--kgm-green-800);margin:0;">{{ $name }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ WHY KGM ══ --}}
<section class="section" style="background:white;">
    <div class="container">
        <div style="text-align:center;margin-bottom:32px;">
            <div class="section-subtitle" style="text-align:center;">ทำไมต้องเลือก KGM</div>
            <div class="section-title">มาตรฐานที่ผู้ปกครองและโรงเรียนวางใจ</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div class="grid grid-4" style="gap:16px;">
            @foreach([
                ['bi-award-fill',       'ประสบการณ์ 40+ ปี',     'ผลิตเครื่องแบบนักเรียนมาตั้งแต่ปี 2536'],
                ['bi-scissors',         'ตัดเย็บแม่นยำ',          'ทุกชิ้นผ่านการตรวจสอบขนาดและคุณภาพผ้า'],
                ['bi-truck',            'จัดส่งทั่วประเทศ',       'ส่งตรงถึงโรงเรียนและผู้ปกครองทุกจังหวัด'],
                ['bi-patch-check-fill', 'ผ้าได้มาตรฐาน MOE',     'ใช้ผ้าตรงตามมาตรฐานกระทรวงศึกษาธิการ'],
            ] as [$icon, $title, $desc])
            <div style="text-align:center;padding:24px 18px;background:#f8faf8;border-radius:16px;">
                <div style="width:52px;height:52px;background:var(--kgm-green-100);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;color:var(--kgm-green-600);margin:0 auto 12px;">
                    <i class="bi {{ $icon }}"></i>
                </div>
                <h3 style="font-size:14px;font-weight:800;color:var(--kgm-green-800);margin:0 0 6px;">{{ $title }}</h3>
                <p style="font-size:12px;color:#777;line-height:1.7;margin:0;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA ══ --}}
<section style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:2px;margin-bottom:10px;">สั่งผลิตและสอบถามราคา</div>
        <h2 style="font-size:clamp(20px,4.5vw,34px);font-weight:800;color:white;margin:0 0 12px;line-height:1.55;">เครื่องแบบนักเรียนคุณภาพสูง<br>ราคาเหมาะสม ส่งทั่วประเทศ</h2>
        <p style="font-size:15px;color:rgba(255,255,255,0.7);margin:0 0 26px;">ติดต่อสอบถามราคา สั่งผลิตตามแบบ หรือเลือกซื้อสินค้าพร้อมส่งได้เลย</p>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('quote') }}" class="btn btn-gold btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี</a>
            <a href="{{ route('shop') }}" class="btn btn-lg" style="background:rgba(255,255,255,0.12);color:white;border:1.5px solid rgba(255,255,255,0.25);"><i class="bi bi-bag"></i> ซื้อสินค้าออนไลน์</a>
        </div>
    </div>
</section>

@endsection
