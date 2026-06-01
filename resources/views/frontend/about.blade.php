@extends('layouts.app')
@section('title', 'เกี่ยวกับบริษัท')
@section('content')
{{-- Hero --}}
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:80px 0;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-weight:700;letter-spacing:2px;text-transform:uppercase;font-size:13px;margin-bottom:10px;">เกี่ยวกับเรา</div>
        <h1 style="font-size:44px;font-weight:800;color:white;">กิจเจริญการ์เมนท์ (1993)</h1>
        <p style="color:rgba(255,255,255,0.8);font-size:18px;margin-top:12px;">ประสบการณ์กว่า 30 ปี ในอุตสาหกรรมเครื่องแบบ</p>
    </div>
</div>

{{-- Story --}}
<section class="section">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:60px;align-items:center;">
            <div>
                <div class="section-subtitle">ประวัติบริษัท</div>
                <h2 class="section-title">เริ่มต้นจากความมุ่งมั่น<br>สู่มาตรฐานระดับประเทศ</h2>
                <div class="section-divider"></div>
                <p style="color:#555;line-height:1.9;font-size:15px;margin-bottom:16px;">บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด ก่อตั้งขึ้นเมื่อปี พ.ศ. 2536 โดยมีจุดเริ่มต้นจากโรงงานขนาดเล็กที่มุ่งมั่นผลิตเครื่องแบบนักเรียนคุณภาพสูง</p>
                <p style="color:#555;line-height:1.9;font-size:15px;margin-bottom:20px;">ตลอดระยะเวลากว่า 30 ปี เราได้พัฒนาและขยายกิจการอย่างต่อเนื่อง จนปัจจุบันเป็นหนึ่งในผู้ผลิตเครื่องแบบนักเรียนและยูนิฟอร์มชั้นนำของประเทศ</p>
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;text-align:center;">
                    @foreach([['30+','ปีประสบการณ์'],['500+','โรงเรียนทั่วไทย'],['1M+','เครื่องแบบต่อปี']] as [$num,$label])
                    <div style="background:var(--kgm-green-100);border-radius:16px;padding:16px;">
                        <div style="font-size:26px;font-weight:800;color:var(--kgm-green-600);">{{ $num }}</div>
                        <div style="font-size:12px;color:#666;">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <div style="border-radius:24px;overflow:hidden;box-shadow:0 12px 40px rgba(0,0,0,0.12);">
                <div style="background:linear-gradient(135deg,var(--kgm-green-700),var(--kgm-green-500));height:400px;display:flex;align-items:center;justify-content:center;color:white;font-size:80px;opacity:0.8;">
                    <i class="bi bi-building-fill"></i>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Vision & Mission --}}
<section class="section" style="background:#f8faf8;">
    <div class="container">
        <div style="text-align:center;margin-bottom:40px;">
            <div class="section-subtitle" style="text-align:center;">แนวทางองค์กร</div>
            <div class="section-title">วิสัยทัศน์และพันธกิจ</div>
            <div class="section-divider" style="margin:12px auto;"></div>
        </div>
        <div class="grid grid-2">
            <div style="background:linear-gradient(135deg,var(--kgm-green-800),var(--kgm-green-600));border-radius:24px;padding:36px;color:white;">
                <div style="width:52px;height:52px;background:rgba(255,255,255,0.15);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;margin-bottom:16px;"><i class="bi bi-eye"></i></div>
                <h3 style="font-size:20px;font-weight:800;margin-bottom:12px;">วิสัยทัศน์</h3>
                <p style="opacity:0.85;line-height:1.8;">เป็นผู้นำด้านการผลิตเครื่องแบบและเสื้อผ้าสำเร็จรูปคุณภาพสูง ที่ได้รับการยอมรับในระดับประเทศและภูมิภาคอาเซียน</p>
            </div>
            <div style="background:linear-gradient(135deg,var(--kgm-gold-700),var(--kgm-gold-500));border-radius:24px;padding:36px;">
                <div style="width:52px;height:52px;background:rgba(255,255,255,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;color:white;margin-bottom:16px;"><i class="bi bi-bullseye"></i></div>
                <h3 style="font-size:20px;font-weight:800;margin-bottom:12px;color:var(--kgm-green-900);">พันธกิจ</h3>
                <ul style="padding-left:0;list-style:none;display:flex;flex-direction:column;gap:8px;">
                    @foreach(['ผลิตสินค้าคุณภาพมาตรฐาน ISO','พัฒนาบุคลากรอย่างต่อเนื่อง','ใส่ใจสิ่งแวดล้อมในกระบวนการผลิต','มอบบริการที่เกินความคาดหวัง'] as $m)
                    <li style="display:flex;gap:8px;font-size:14px;color:var(--kgm-green-900);"><i class="bi bi-check-circle-fill" style="color:var(--kgm-green-700);flex-shrink:0;"></i>{{ $m }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</section>

{{-- Certifications --}}
<section class="section">
    <div class="container" style="text-align:center;">
        <div class="section-subtitle" style="text-align:center;">รางวัลและมาตรฐาน</div>
        <div class="section-title">ใบรับรองและรางวัล</div>
        <div class="section-divider" style="margin:12px auto 40px;"></div>
        <div class="grid grid-4">
            @foreach(['ISO 9001:2015','Thai Quality Award','Green Industry','SME Excellence'] as $cert)
            <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 2px 12px rgba(0,0,0,0.07);text-align:center;">
                <div style="width:64px;height:64px;background:var(--kgm-gold-100);border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:28px;color:var(--kgm-gold-600);margin:0 auto 14px;"><i class="bi bi-award-fill"></i></div>
                <div style="font-weight:700;color:var(--kgm-green-800);">{{ $cert }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
