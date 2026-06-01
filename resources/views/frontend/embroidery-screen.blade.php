@extends('layouts.app')
@section('title', 'บริการงานปักและสกรีน - KGM')

@section('content')

{{-- ══ HERO ══ --}}
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:80px 0 60px;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin-bottom:10px;">บริการของเรา</div>
        <h1 style="font-size:clamp(28px,5vw,48px);font-weight:800;color:white;margin:0 0 14px;line-height:1.3;">บริการงานปักและสกรีน</h1>
        <p style="color:rgba(255,255,255,0.8);font-size:16px;max-width:600px;margin:0 auto 28px;line-height:1.8;">
            Embroidery and Screen Printing<br>
            คุณภาพมาตรฐานสากล รองรับงานจำนวนมาก ด้วยทีมงานประสบการณ์กว่า 40 ปี
        </p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('quote') }}" class="btn btn-gold btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี</a>
            <a href="tel:0851100010" class="btn btn-lg" style="background:rgba(255,255,255,0.12);color:white;border:1.5px solid rgba(255,255,255,0.3);"><i class="bi bi-telephone"></i> 085-110-0010</a>
        </div>
    </div>
</div>

{{-- ══ INTRO WITH IMAGE ══ --}}
<section class="section" style="background:white;">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;">
            <div>
                <img src="https://www.kgm.co.th/wp-content/uploads/2018/03/pak_1500.jpg"
                     alt="เครื่องปักอัตโนมัติ KGM"
                     style="width:100%;border-radius:20px;box-shadow:0 8px 32px rgba(0,0,0,0.12);"
                     loading="eager">
            </div>
            <div>
                <div class="section-subtitle">เกี่ยวกับบริการ</div>
                <div class="section-title" style="margin-bottom:16px;">งานปักและสกรีน<br>คุณภาพมาตรฐานสากล</div>
                <p style="font-size:15px;color:#555;line-height:1.95;margin:0 0 20px;">
                    บริการงานปักและสกรีน เราบริหารคุณภาพมาตรฐานสากล มีเครื่องปักอัตโนมัติ <strong>ขนาด 20 หัวปัก, 4 หัวปัก และ 1 หัวปัก</strong>
                    พร้อมด้วยทีมงานออกแบบลายที่มีประสบการณ์มากกว่า 40 ปี รองรับงานปักจำนวนมาก ด้วยเครื่องจักรที่มีคุณภาพสูง
                </p>
                <div style="display:flex;flex-direction:column;gap:12px;">
                    @foreach([
                        ['bi-cpu-fill',         'เครื่องปักอัตโนมัติ 20 หัว', 'รองรับปริมาณงานสูง ผลิตชิ้นงานได้จำนวนมากต่อวัน'],
                        ['bi-grid-3x3',         'เครื่องปัก 4 หัว / 1 หัว',   'เหมาะกับงานพิเศษ ลายละเอียด และจำนวนน้อย-ปานกลาง'],
                        ['bi-palette2',         'ทีมออกแบบลาย',               'ออกแบบลายปักตามโลโก้หรือแบบที่ลูกค้าต้องการ'],
                        ['bi-patch-check-fill', 'ตรวจคุณภาพทุกชิ้น',          'QC ก่อนส่งมอบ รับประกันความคมชัดและทนทาน'],
                    ] as [$icon, $title, $sub])
                    <div style="display:flex;align-items:center;gap:12px;">
                        <div style="width:40px;height:40px;background:var(--kgm-green-100);border-radius:11px;display:flex;align-items:center;justify-content:center;font-size:17px;color:var(--kgm-green-600);flex-shrink:0;">
                            <i class="bi {{ $icon }}"></i>
                        </div>
                        <div>
                            <div style="font-size:14px;font-weight:700;color:var(--kgm-green-800);">{{ $title }}</div>
                            <div style="font-size:12px;color:#888;">{{ $sub }}</div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ SERVICE TYPES ══ --}}
<section class="section" style="background:#f8faf8;">
    <div class="container">
        <div style="text-align:center;margin-bottom:36px;">
            <div class="section-subtitle" style="text-align:center;">ประเภทบริการ</div>
            <div class="section-title">งานปัก vs งานสกรีน</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div class="grid grid-2" style="gap:24px;">

            {{-- งานปัก --}}
            <div style="background:white;border-radius:20px;padding:32px;box-shadow:0 4px 20px rgba(0,0,0,0.06);">
                <div style="width:60px;height:60px;background:var(--kgm-green-100);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:28px;color:var(--kgm-green-600);margin-bottom:16px;">
                    <i class="bi bi-stars"></i>
                </div>
                <h3 style="font-size:20px;font-weight:800;color:var(--kgm-green-800);margin:0 0 10px;">งานปักโลโก้ (Embroidery)</h3>
                <p style="font-size:14px;color:#666;line-height:1.8;margin:0 0 18px;">
                    ปักลวดลายและโลโก้ลงบนผ้าด้วยด้ายคุณภาพสูง ได้งานที่ทนทาน คงรูป ไม่ลอก ไม่ซีด เหมาะสำหรับเครื่องแบบระยะยาว
                </p>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                    @foreach(['ปักโลโก้องค์กร / บริษัท','ปักตราสัญลักษณ์โรงเรียน','ปักชื่อ-นามสกุล / หมายเลข','ปักลายบนหน้าอก, แขน, หลัง','รองรับทุกชนิดผ้า ผ้าหนา-บาง'] as $item)
                    <li style="display:flex;align-items:center;gap:8px;font-size:14px;color:#444;">
                        <i class="bi bi-check-circle-fill" style="color:var(--kgm-green-500);font-size:13px;flex-shrink:0;"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- งานสกรีน --}}
            <div style="background:white;border-radius:20px;padding:32px;box-shadow:0 4px 20px rgba(0,0,0,0.06);">
                <div style="width:60px;height:60px;background:var(--kgm-gold-100);border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:28px;color:var(--kgm-gold-600);margin-bottom:16px;">
                    <i class="bi bi-printer-fill"></i>
                </div>
                <h3 style="font-size:20px;font-weight:800;color:var(--kgm-green-800);margin:0 0 10px;">งานสกรีน (Screen Printing)</h3>
                <p style="font-size:14px;color:#666;line-height:1.8;margin:0 0 18px;">
                    สกรีนลายด้วยหมึกคุณภาพสูง สีสดใส คมชัด ทนต่อการซักหลายครั้ง เหมาะสำหรับงานที่ต้องการสีสันและพื้นที่พิมพ์ขนาดใหญ่
                </p>
                <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:8px;">
                    @foreach(['สกรีนโลโก้ / ลาย / ข้อความ','สกรีนชื่อทีม หมายเลข ชุดกีฬา','สกรีนสีทึบ หลายสี (Multicolor)','สกรีนบนเสื้อยืด เสื้อโปโล ถุงผ้า','รองรับงานจำนวนน้อยถึงจำนวนมาก'] as $item)
                    <li style="display:flex;align-items:center;gap:8px;font-size:14px;color:#444;">
                        <i class="bi bi-check-circle-fill" style="color:var(--kgm-gold-500);font-size:13px;flex-shrink:0;"></i>
                        {{ $item }}
                    </li>
                    @endforeach
                </ul>
            </div>

        </div>
    </div>
</section>

{{-- ══ GALLERY ══ --}}
<section class="section" style="background:white;">
    <div class="container">
        <div style="text-align:center;margin-bottom:36px;">
            <div class="section-subtitle" style="text-align:center;">ผลงานของเรา</div>
            <div class="section-title">ตัวอย่างงานปักของเรา</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;align-items:start;">
            <div>
                <img src="https://www.kgm.co.th/wp-content/uploads/2018/03/pak_1500.jpg"
                     alt="โรงงานเครื่องปักอัตโนมัติ KGM"
                     style="width:100%;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.1);"
                     loading="lazy">
                <p style="margin-top:10px;font-size:13px;color:#888;text-align:center;">
                    <i class="bi bi-building"></i> เครื่องปักอัตโนมัติ ภายในโรงงาน KGM จ.ศรีสะเกษ
                </p>
            </div>
            <div>
                <img src="https://www.kgm.co.th/wp-content/uploads/2024/02/KGM-pak-scaled.jpg"
                     alt="ตัวอย่างงานปักตราสัญลักษณ์โรงเรียน KGM"
                     style="width:100%;border-radius:16px;box-shadow:0 4px 20px rgba(0,0,0,0.1);"
                     loading="lazy">
                <p style="margin-top:10px;font-size:13px;color:#888;text-align:center;">
                    <i class="bi bi-patch-check"></i> ตัวอย่างงานปักตราสัญลักษณ์โรงเรียนต่างๆ ทั่วประเทศ
                </p>
            </div>
        </div>
    </div>
</section>

{{-- ══ CONTACT CARD ══ --}}
<section class="section" style="background:#f8faf8;">
    <div class="container">
        <div style="max-width:680px;margin:0 auto;background:linear-gradient(135deg,var(--kgm-green-100),var(--kgm-gold-100));border-radius:24px;padding:40px;text-align:center;">
            <div style="font-size:13px;font-weight:700;color:var(--kgm-green-700);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">สั่งซื้องานปักออนไลน์</div>
            <h3 style="font-size:24px;font-weight:800;color:var(--kgm-green-900);margin:0 0 8px;">ติดต่อฝ่ายขาย</h3>
            <p style="font-size:14px;color:#666;margin:0 0 24px;">วันจันทร์-เสาร์ เวลา 08:00–17:00 น.</p>
            <div style="display:flex;justify-content:center;gap:16px;flex-wrap:wrap;margin-bottom:24px;">
                <a href="tel:0851100010" style="display:flex;align-items:center;gap:10px;text-decoration:none;background:white;border-radius:14px;padding:14px 22px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
                    <div style="width:38px;height:38px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div style="text-align:left;">
                        <div style="font-weight:700;color:var(--kgm-green-800);font-size:15px;">085-110-0010</div>
                        <div style="font-size:12px;color:#888;">คุณมิน (ฝ่ายขาย)</div>
                    </div>
                </a>
                <a href="tel:0851100060" style="display:flex;align-items:center;gap:10px;text-decoration:none;background:white;border-radius:14px;padding:14px 22px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
                    <div style="width:38px;height:38px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div style="text-align:left;">
                        <div style="font-weight:700;color:var(--kgm-green-800);font-size:15px;">085-110-0060</div>
                        <div style="font-size:12px;color:#888;">คุณตั๊ก (ฝ่ายขาย)</div>
                    </div>
                </a>
                <a href="https://line.me/R/ti/p/@KGM444" target="_blank" rel="noopener" style="display:flex;align-items:center;gap:10px;text-decoration:none;background:white;border-radius:14px;padding:14px 22px;box-shadow:0 2px 10px rgba(0,0,0,0.07);">
                    <div style="width:38px;height:38px;background:#06c755;border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;">
                        <i class="bi bi-chat-dots-fill"></i>
                    </div>
                    <div style="text-align:left;">
                        <div style="font-weight:700;color:var(--kgm-green-800);font-size:15px;">Line: @KGM444</div>
                        <div style="font-size:12px;color:#888;">Add Friend</div>
                    </div>
                </a>
            </div>
            <a href="{{ route('quote') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี
            </a>
        </div>
    </div>
</section>

{{-- ══ CTA ══ --}}
<section style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:2px;margin-bottom:10px;">พร้อมให้บริการ</div>
        <h2 style="font-size:clamp(20px,4.5vw,34px);font-weight:800;color:white;margin:0 0 12px;line-height:1.55;">งานปักและสกรีนคุณภาพสูง<br>ราคาเหมาะสม ส่งตรงเวลา</h2>
        <p style="font-size:15px;color:rgba(255,255,255,0.7);margin:0 0 26px;">ส่งรายละเอียดลาย จำนวน และประเภทผ้า รับใบเสนอราคาภายใน 1-2 วันทำการ</p>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('quote') }}" class="btn btn-gold btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี</a>
            <a href="{{ route('contact') }}" class="btn btn-lg" style="background:rgba(255,255,255,0.12);color:white;border:1.5px solid rgba(255,255,255,0.25);"><i class="bi bi-telephone"></i> ติดต่อเรา</a>
        </div>
    </div>
</section>

@endsection
