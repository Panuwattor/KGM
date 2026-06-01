@extends('layouts.app')
@section('title', 'บริการออกแบบและสั่งผลิต - KGM')

@section('content')

{{-- ══ HERO ══ --}}
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:80px 0 60px;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:2px;margin-bottom:10px;">บริการของเรา</div>
        <h1 style="font-size:clamp(28px,5vw,48px);font-weight:800;color:white;margin:0 0 14px;line-height:1.3;">บริการออกแบบและสั่งผลิต</h1>
        <p style="color:rgba(255,255,255,0.8);font-size:16px;max-width:600px;margin:0 auto 28px;line-height:1.8;">
            ครบวงจรตั้งแต่ออกแบบจนถึงส่งมอบ ด้วยประสบการณ์มากกว่า 40 ปี<br>
            เสื้อยูนิฟอร์ม · เครื่องแบบนักเรียน · ชุดองค์กร · และอื่นๆ
        </p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('quote') }}" class="btn btn-gold btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี</a>
            <a href="tel:0851100010" class="btn btn-lg" style="background:rgba(255,255,255,0.12);color:white;border:1.5px solid rgba(255,255,255,0.3);"><i class="bi bi-telephone"></i> 085-110-0010</a>
        </div>
    </div>
</div>

{{-- ══ WHAT WE PRODUCE ══ --}}
<section class="section" style="background:white;">
    <div class="container">
        <div style="text-align:center;margin-bottom:36px;">
            <div class="section-subtitle" style="text-align:center;">สินค้าที่รับผลิต</div>
            <div class="section-title">ผลิตได้ทุกประเภท</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:16px;">
            @foreach([
                ['bi-person-badge-fill',     'เสื้อยูนิฟอร์ม',         'ยูนิฟอร์มองค์กร บริษัท โรงแรม ร้านอาหาร ทุกสไตล์'],
                ['bi-building-fill',         'เสื้อหน่วยงาน',           'ออกแบบตามอัตลักษณ์องค์กร โลโก้คมชัด ทนทาน'],
                ['bi-mortarboard-fill',      'เครื่องแบบนักเรียน',      'ครบชุดทุกระดับชั้น ชาย-หญิง ลูกเสือ-เนตรนารี และชุดพละ'],
                ['bi-award-fill',            'เสื้อโปโล',               'ผ้าคุณภาพสูง ปักโลโก้ สกรีนลาย ตามแบบที่ต้องการ'],
                ['bi-wind',                  'เสื้อแจ็คเก็ต',           'แจ็คเก็ตองค์กร ชมรม มหาวิทยาลัย ตามสเปกที่กำหนด'],
                ['bi-circle-fill',           'เสื้อยืดคอกลม',           'ทรงหลากหลาย ผ้าดี ปักหรือสกรีนลายตามต้องการ'],
                ['bi-shield-check',          'ชุดพนักงานราชการ',        'ออกแบบตามระเบียบราชการ ผ้าตรงมาตรฐาน'],
                ['bi-plus-circle-fill',      'ชุดคนไข้โรงพยาบาล',      'ผ้าพิเศษนุ่มสบาย ดูแลรักษาง่าย ตัดเย็บแม่นยำ'],
                ['bi-trophy-fill',           'ชุดกีฬา',                 'ชุดกีฬาสีสดใส ระบายอากาศดี พร้อมชื่อและหมายเลข'],
                ['bi-bag-fill',              'ถุงผ้า',                  'ถุงผ้าสำหรับของชำร่วย รับน้อง และงานอีเวนต์ต่างๆ'],
            ] as [$icon, $title, $desc])
            <div style="background:#f8faf8;border-radius:16px;padding:22px 18px;text-align:center;">
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

{{-- ══ ORDER PROCESS ══ --}}
<section class="section" style="background:#f8faf8;">
    <div class="container">
        <div style="text-align:center;margin-bottom:40px;">
            <div class="section-subtitle" style="text-align:center;">ขั้นตอนการสั่งผลิต</div>
            <div class="section-title">สั่งผลิตง่ายๆ 5 ขั้นตอน</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div style="display:grid;grid-template-columns:repeat(5,1fr);gap:0;position:relative;">
            {{-- Connector line --}}
            <div style="position:absolute;top:36px;left:10%;right:10%;height:2px;background:linear-gradient(90deg,var(--kgm-green-200),var(--kgm-gold-300));z-index:0;"></div>
            @foreach([
                ['bi-chat-dots-fill',    '01','ติดต่อสอบถาม',          'โทรหาทีมขายหรือส่งข้อความผ่าน Line @KGM444'],
                ['bi-file-earmark-text', '02','รับใบเสนอราคา',         'เราประเมินราคาและส่งใบเสนอราคาให้ภายใน 1-2 วัน'],
                ['bi-check2-circle',     '03','ยืนยันและชำระมัดจำ',    'อนุมัติแบบและชำระมัดจำ 30-50% เริ่มเตรียมการผลิต'],
                ['bi-gear-fill',         '04','ผลิตและตรวจ QC',        'ผลิตตามแบบที่ตกลง ผ่านการตรวจคุณภาพทุกชิ้น'],
                ['bi-truck',             '05','จัดส่งสินค้า',           'จัดส่งทั่วประเทศ หรือรับสินค้าได้ที่โรงงาน จ.ศรีสะเกษ'],
            ] as [$icon, $num, $title, $desc])
            <div style="text-align:center;padding:0 8px;position:relative;z-index:1;">
                <div style="width:72px;height:72px;background:var(--kgm-green-700);border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:26px;color:white;margin:0 auto 14px;box-shadow:0 4px 14px rgba(26,66,42,0.25);">
                    <i class="bi {{ $icon }}"></i>
                </div>
                <div style="font-size:11px;font-weight:800;color:var(--kgm-gold-600);letter-spacing:1px;margin-bottom:4px;">STEP {{ $num }}</div>
                <h3 style="font-size:14px;font-weight:800;color:var(--kgm-green-800);margin:0 0 6px;">{{ $title }}</h3>
                <p style="font-size:12px;color:#777;line-height:1.7;margin:0;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ WHY KGM ══ --}}
<section class="section" style="background:white;">
    <div class="container">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;">
            <div>
                <div class="section-subtitle">ทำไมต้องเลือก KGM</div>
                <div class="section-title" style="margin-bottom:20px;">มั่นใจทุกขั้นตอน<br>ตั้งแต่ออกแบบจนส่งมอบ</div>
                <p style="font-size:15px;color:#555;line-height:1.9;margin:0 0 24px;">
                    บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด มีประสบการณ์ผลิตเครื่องแบบมากกว่า 40 ปี
                    โรงงานตั้งอยู่ที่ จ.ศรีสะเกษ พร้อมทีมช่างฝีมือและเครื่องจักรทันสมัย ควบคุมคุณภาพทุกขั้นตอน
                </p>
                <div style="display:flex;flex-direction:column;gap:14px;">
                    @foreach([
                        ['bi-patch-check-fill', 'คุณภาพผ่านมาตรฐาน ISO', 'ตรวจสอบคุณภาพทุกชิ้นก่อนส่งมอบ'],
                        ['bi-palette2',          'ออกแบบฟรีตามสเปก',      'ทีมดีไซน์ช่วยออกแบบและทำแบบตัวอย่าง'],
                        ['bi-people-fill',       'รับออเดอร์ทุกขนาด',     'ตั้งแต่ 50 ชิ้นขึ้นไป ทั้งลูกค้ารายย่อยและองค์กร'],
                        ['bi-clock-fill',        'ส่งตรงเวลา',             'บริหารการผลิตให้ทันกำหนดส่งมอบทุกออเดอร์'],
                    ] as [$icon, $title, $sub])
                    <div style="display:flex;align-items:center;gap:14px;">
                        <div style="width:44px;height:44px;background:var(--kgm-green-100);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:18px;color:var(--kgm-green-600);flex-shrink:0;">
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
            <div style="background:linear-gradient(135deg,var(--kgm-green-100),var(--kgm-gold-100));border-radius:24px;padding:36px;">
                <div style="font-size:13px;font-weight:700;color:var(--kgm-green-700);text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;">ติดต่อทีมขาย</div>
                <h3 style="font-size:22px;font-weight:800;color:var(--kgm-green-900);margin:0 0 20px;">พร้อมให้คำปรึกษา<br>ฟรีทุกวันจันทร์-เสาร์</h3>
                <div style="display:flex;flex-direction:column;gap:14px;margin-bottom:24px;">
                    <a href="tel:0851100010" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;">
                        <div style="width:40px;height:40px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;flex-shrink:0;"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <div style="font-weight:700;color:var(--kgm-green-800);font-size:15px;">085-110-0010</div>
                            <div style="font-size:12px;color:#666;">คุณมิน (ฝ่ายขาย)</div>
                        </div>
                    </a>
                    <a href="tel:0851100060" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;">
                        <div style="width:40px;height:40px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;flex-shrink:0;"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <div style="font-weight:700;color:var(--kgm-green-800);font-size:15px;">085-110-0060</div>
                            <div style="font-size:12px;color:#666;">คุณตั๊ก (ฝ่ายขาย)</div>
                        </div>
                    </a>
                    <a href="https://line.me/R/ti/p/@KGM444" target="_blank" rel="noopener" style="display:flex;align-items:center;gap:12px;text-decoration:none;color:inherit;">
                        <div style="width:40px;height:40px;background:#06c755;border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-size:16px;flex-shrink:0;"><i class="bi bi-chat-dots-fill"></i></div>
                        <div>
                            <div style="font-weight:700;color:var(--kgm-green-800);font-size:15px;">Line: @KGM444</div>
                            <div style="font-size:12px;color:#666;">จันทร์-เสาร์ 08:00–17:00 น.</div>
                        </div>
                    </a>
                </div>
                <a href="{{ route('quote') }}" class="btn btn-primary" style="width:100%;justify-content:center;">
                    <i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี
                </a>
            </div>
        </div>
    </div>
</section>

{{-- ══ CTA ══ --}}
<section style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:2px;margin-bottom:10px;">เริ่มต้นได้เลยวันนี้</div>
        <h2 style="font-size:clamp(20px,4.5vw,34px);font-weight:800;color:white;margin:0 0 12px;line-height:1.55;">สั่งผลิตเครื่องแบบคุณภาพสูง<br>ในราคาที่คุ้มค่า</h2>
        <p style="font-size:15px;color:rgba(255,255,255,0.7);margin:0 0 26px;">ส่งรายละเอียดความต้องการ รับใบเสนอราคาภายใน 1-2 วันทำการ</p>
        <div style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('quote') }}" class="btn btn-gold btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี</a>
            <a href="{{ route('contact') }}" class="btn btn-lg" style="background:rgba(255,255,255,0.12);color:white;border:1.5px solid rgba(255,255,255,0.25);"><i class="bi bi-telephone"></i> ติดต่อเรา</a>
        </div>
    </div>
</section>

@endsection
