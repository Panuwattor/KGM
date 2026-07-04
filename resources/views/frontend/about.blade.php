@extends('layouts.app')
@section('title', 'เกี่ยวกับบริษัท')

@push('styles')
<style>
.about-nav { position: sticky; top: 64px; z-index: 99; background: #fff; border-bottom: 2px solid var(--kgm-green-100); box-shadow: 0 2px 12px rgba(0,0,0,.06); }
#history, #vision, #mission, #certificate, #factory, #showroom { scroll-margin-top: 120px; }
.about-nav-inner { display: flex; overflow-x: auto; scrollbar-width: none; justify-content: flex-start; }
@media (min-width: 768px) { .about-nav-inner { justify-content: center; } }
.about-nav-inner::-webkit-scrollbar { display: none; }
.anav-link { display: inline-flex; align-items: center; gap: 6px; padding: 14px 18px; font-size: 13px; font-weight: 600; color: #777; white-space: nowrap; border-bottom: 3px solid transparent; text-decoration: none; transition: color .2s, border-color .2s; cursor: pointer; }
.anav-link:hover, .anav-link.active { color: var(--kgm-green-700); border-bottom-color: var(--kgm-green-600); }

.tl-item { position: relative; }
.tl-item::after { content: ''; position: absolute; left: 39px; top: 80px; bottom: -32px; width: 2px; background: var(--kgm-green-100); }
.tl-item:last-child::after { display: none; }
.tl-dot { width: 80px; height: 80px; flex-shrink: 0; background: var(--kgm-green-700); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #fff; font-weight: 800; font-size: 12px; text-align: center; line-height: 1.3; z-index: 1; }

.cert-card { transition: transform .2s, box-shadow .2s; }
.cert-card:hover { transform: translateY(-4px); }

.showroom-img { width: 100%; height: 100%; object-fit: cover; display: block; }
</style>
@endpush

@section('content')

{{-- ══ HERO ══ --}}
<div class="py-5 text-center" style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));">
    <div class="container">
        <div class="fw-bold text-uppercase mb-2" style="color:var(--kgm-gold-300);letter-spacing:2px;font-size:12px;">ABOUT US</div>
        <h1 class="fw-bolder text-white mb-3" style="font-size:40px;">บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด</h1>
        <p class="mx-auto mb-4" style="color:rgba(255,255,255,.8);font-size:16px;max-width:580px;">
            ผู้ผลิตและจำหน่ายเครื่องแบบนักเรียนคุณภาพสูง มากกว่า 40 ปี<br>
            มุ่งมั่นสร้างสรรค์เครื่องแบบเพื่อเยาวชนและประชาชนชาวไทยทั่วประเทศ
        </p>
        <div class="d-flex justify-content-center flex-wrap gap-5">
            @foreach([['40+','ปีประสบการณ์','bi-clock-history'],['500+','โรงเรียนทั่วไทย','bi-building'],['1M+','เครื่องแบบ/ปี','bi-box-seam-fill'],['ISO','มาตรฐานสากล','bi-patch-check-fill']] as [$n,$l,$ic])
            <div class="text-center">
                <i class="bi {{ $ic }} d-block mb-1 fs-4" style="color:var(--kgm-gold-300);"></i>
                <div class="fw-bolder text-white" style="font-size:26px;">{{ $n }}</div>
                <div style="font-size:12px;color:rgba(255,255,255,.65);">{{ $l }}</div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══ STICKY NAV ══ --}}
<div class="about-nav" x-data="aboutNav()" @scroll.window="onScroll()">
    <div class="container">
        <div class="about-nav-inner">
            @php
            $navItems = [
                ['history','bi-clock-history','ประวัติบริษัท'],
                ['vision','bi-eye','วิสัยทัศน์'],
                ['mission','bi-bullseye','พันธกิจ'],
                ['certificate','bi-award','ใบรับรอง'],
                ['factory','bi-building-gear','เยี่ยมชมโรงงาน'],
                ['showroom','bi-shop','โชว์รูม'],
            ];
            @endphp
            @foreach($navItems as [$id,$ic,$lbl])
            <a class="anav-link" :class="{ active: active==='{{ $id }}' }" @click.prevent="goto('{{ $id }}')">
                <i class="bi {{ $ic }}"></i>{{ $lbl }}
            </a>
            @endforeach
        </div>
    </div>
</div>

<script>
function aboutNav() {
    return {
        active: 'history',
        ids: ['history','vision','mission','certificate','factory','showroom'],
        init() {
            this.$nextTick(() => this.onScroll());
        },
        onScroll() {
            const threshold = 130;
            let found = null;
            for (const id of this.ids) {
                const el = document.getElementById(id);
                if (el && el.getBoundingClientRect().top <= threshold) found = id;
            }
            if (found) this.active = found;
        },
        goto(id) {
            const el = document.getElementById(id);
            if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }
}
</script>

{{-- ══ 1. ประวัติบริษัท ══ --}}
<section class="section" id="history">
    <div class="row justify-content-center g-0">
        <div class="col-md-8">
            <div class="container">
                <div class="text-center mb-4">
                    <div class="section-subtitle">OUR STORY</div>
                    <h2 class="section-title">ประวัติบริษัท</h2>
                    <div class="section-divider mx-auto mt-2"></div>
                </div>
                <div class="text-center">
                    <img src="{{ asset('images/history.jpg') }}" alt="ประวัติบริษัท KGM" class="img-fluid rounded-4 shadow">
                </div>
            </div>
        </div>
    </div>

</section>

{{-- ══ 2. วิสัยทัศน์ ══ --}}
<section class="section" id="vision" style="background:#f8faf8;">
    <div class="container">
        <div class="text-center mb-4">
            <div class="section-subtitle">VISION</div>
            <h2 class="section-title">วิสัยทัศน์</h2>
            <div class="section-divider mx-auto mt-2"></div>
        </div>

        <div class="rounded-4 text-center mx-auto p-5 mb-4" style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));max-width:820px;">
            <i class="bi bi-eye d-block mb-3 fs-1" style="color:var(--kgm-gold-300);"></i>
            <p class="fw-bolder text-white mb-2" style="font-size:24px;line-height:1.6;">
                "สินค้าดีมีคุณภาพ &nbsp;ลูกค้าชมชอบ<br>ส่งมอบตรงเวลา &nbsp;พัฒนาอย่างต่อเนื่อง"
            </p>
            <p class="mb-0" style="color:rgba(255,255,255,.6);font-size:14px;">วิสัยทัศน์องค์กร — บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด</p>
        </div>

        <div class="row row-cols-2 row-cols-md-4 g-3">
            @foreach([
                ['bi-gem','สินค้าดีมีคุณภาพ','ผลิตสินค้าด้วยวัตถุดิบคัดสรร ผ่านระบบควบคุมคุณภาพในทุกขั้นตอน'],
                ['bi-heart-fill','ลูกค้าชมชอบ','มุ่งสร้างความพึงพอใจสูงสุดด้วยสินค้าและบริการที่เกินคาด'],
                ['bi-truck-front-fill','ส่งมอบตรงเวลา','บริหารการผลิตและโลจิสติกส์อย่างมีประสิทธิภาพ ตรงตามกำหนด'],
                ['bi-arrow-repeat','พัฒนาต่อเนื่อง','ไม่หยุดพัฒนาบุคลากร เทคโนโลยี และกระบวนการผลิตให้ทันสมัย'],
            ] as [$ic,$ttl,$dsc])
            <div class="col">
                <div class="bg-white rounded-4 p-4 text-center shadow-sm h-100">
                    <div class="d-flex align-items-center justify-content-center rounded-3 mx-auto mb-3" style="width:54px;height:54px;background:var(--kgm-green-100);">
                        <i class="bi {{ $ic }} fs-4" style="color:var(--kgm-green-700);"></i>
                    </div>
                    <h6 class="fw-bold mb-2" style="color:var(--kgm-green-800);">{{ $ttl }}</h6>
                    <p class="text-muted mb-0" style="font-size:13px;">{{ $dsc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ 3. พันธกิจ ══ --}}
<section class="section" id="mission" style="padding:0;">
    <div class="row justify-content-center g-0">
        <div class="col-md-6">
              <div class="container py-5">
        <div class="text-center mb-4">
            <div class="section-subtitle">MISSION</div>
            <h2 class="section-title">พันธกิจ</h2>
            <div class="section-divider mx-auto mt-2"></div>
        </div>

        <div class="mx-auto d-flex flex-column gap-3" style="max-width:760px;">
            @php
            $missions = [
                ['เรามุ่งเน้นให้ลูกค้าได้รับความพึงพอใจเป็นที่หนึ่ง',        'bi-bullseye',    'var(--kgm-green-700)', 'var(--kgm-green-100)'],
                ['การมีส่วนร่วมของพนักงานทุกแผนกเป็นทีม work',               'bi-briefcase',   '#b45309',             '#fef3c7'],
                ['ส่งมอบสินค้าที่มีคุณภาพ ตรงตามกำหนดเวลา',                 'bi-clock-history','#047857',            '#d1fae5'],
                ['มีการพัฒนาความรู้ ความสามารถ และทักษะด้านการทำงาน เพื่อความมั่นคง', 'bi-lightbulb','#7c3aed','#ede9fe'],
                ['มุ่งมั่นพัฒนากระบวนการทำงานให้ทันสมัยอยู่เสมอ และปรับปรุงประสิทธิผลอย่างต่อเนื่อง','bi-globe','#0369a1','#e0f2fe'],
            ];
            @endphp
            @foreach($missions as $i => [$text, $icon, $color, $bg])
            <div class="bg-white rounded-4 shadow-sm d-flex align-items-center gap-4 px-4 py-3">
                <div class="d-flex align-items-center justify-content-center rounded-circle fw-bolder flex-shrink-0"
                     style="width:56px;height:56px;background:{{ $bg }};border:3px solid {{ $color }};color:{{ $color }};font-size:20px;">
                    {{ $i + 1 }}
                </div>
                <p class="flex-grow-1 fw-semibold mb-0" style="font-size:15px;color:#333;">{{ $text }}</p>
                <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0"
                     style="width:44px;height:44px;background:{{ $bg }};color:{{ $color }};font-size:20px;">
                    <i class="bi {{ $icon }}"></i>
                </div>
            </div>
            @endforeach
        </div>
    </div>
        </div>
    </div>

  

</section>

{{-- ══ 4. ใบรับรองบริษัท ══ --}}
<section class="section" id="certificate" style="background:#f8faf8;">
    <div class="container">
        <div class="text-center mb-4">
            <div class="section-subtitle">CERTIFICATIONS & AWARDS</div>
            <h2 class="section-title">ใบรับรองและรางวัล</h2>
            <div class="section-divider mx-auto mt-2 mb-2"></div>
            <p class="text-muted mx-auto" style="max-width:540px;">ความภาคภูมิใจในมาตรฐานคุณภาพระดับสากล</p>
        </div>

        <div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
            @foreach([
                ['bi-patch-check-fill','ISO 9001:2015','ระบบการจัดการคุณภาพสากล','var(--kgm-green-700)','var(--kgm-green-100)'],
                ['bi-award-fill','รางวัลคุณภาพ','Thai Quality Award ระดับประเทศ','#b45309','#fef3c7'],
                ['bi-recycle','อุตสาหกรรมสีเขียว','Green Industry Certificate','#047857','#d1fae5'],
                ['bi-trophy-fill','SME Excellence','ความเป็นเลิศธุรกิจ SME','#7c3aed','#ede9fe'],
            ] as [$ic,$ttl,$sub,$col,$bg])
            <div class="col">
                <div class="cert-card bg-white rounded-4 p-4 text-center shadow-sm h-100">
                    <div class="d-flex align-items-center justify-content-center rounded-circle mx-auto mb-3"
                         style="width:68px;height:68px;background:{{ $bg }};color:{{ $col }};font-size:30px;">
                        <i class="bi {{ $ic }}"></i>
                    </div>
                    <h6 class="fw-bold mb-1" style="color:var(--kgm-green-800);">{{ $ttl }}</h6>
                    <p class="text-muted mb-0" style="font-size:13px;">{{ $sub }}</p>
                </div>
            </div>
            @endforeach
        </div>

        <div class="bg-white rounded-4 p-4 shadow-sm">
            <h6 class="fw-bold text-center mb-3" style="color:var(--kgm-green-800);">
                <i class="bi bi-images me-2"></i>รางวัลและใบรับรองจริง
            </h6>
            <div class="rounded-3 overflow-hidden mb-3">
                <img src="{{ asset('images/certificate_1.jpg') }}" alt="รางวัล KGM" class="w-100 d-block" style="object-fit:contain;background:#f8faf8;">
            </div>
            <div class="d-flex align-items-center justify-content-center gap-4 p-3 rounded-3" style="background:#f8faf8;">
                <img src="{{ asset('images/certificate_2.png') }}" alt="ISO" style="height:80px;object-fit:contain;">
                <div>
                    <div class="fw-bold" style="color:var(--kgm-green-800);">ISO Certified</div>
                    <div class="text-muted mt-1" style="font-size:13px;">ได้รับการรับรองมาตรฐานสากล ISO<br>ยืนยันคุณภาพในทุกกระบวนการผลิต</div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ 5. เยี่ยมชมโรงงาน ══ --}}
<section class="section" id="factory">
    <div class="container">
        <div class="text-center mb-4">
            <div class="section-subtitle">FACTORY TOUR</div>
            <h2 class="section-title">เยี่ยมชมโรงงาน</h2>
            <div class="section-divider mx-auto mt-2 mb-2"></div>
            <p class="text-muted mx-auto" style="max-width:600px;">
                พบกับกระบวนการผลิตที่ทันสมัย ด้วยเครื่องจักรระดับอุตสาหกรรมและทีมช่างฝีมือที่มีประสบการณ์กว่า 40 ปี
            </p>
        </div>
        <div class="rounded-4 overflow-hidden  text-center">
            <img src="{{ asset('images/factory_1.jpg') }}" alt="โรงงาน KGM" class="img-fluid  rounded-3">
        </div>
    </div>
</section>

{{-- ══ 6. โชว์รูม ══ --}}
<section class="section" id="showroom" style="background:#f8faf8;">
    <div class="container">
        <div class="text-center mb-4">
            <div class="section-subtitle">SHOWROOM</div>
            <h2 class="section-title">โชว์รูม</h2>
            <div class="section-divider mx-auto mt-2 mb-2"></div>
            <p class="text-muted mx-auto" style="max-width:540px;">เยี่ยมชมและเลือกสรรสินค้าได้ที่โชว์รูมของเรา 2 สาขา</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="bg-white rounded-4 overflow-hidden shadow-sm mb-4">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-md-6 overflow-hidden">
                            <img src="{{ asset('images/showroom_hq.jpg') }}" alt="ฝ่ายขาย KGM" class="showroom-img">
                        </div>
                        <div class="col-md-6 d-flex flex-column justify-content-center p-4 p-lg-5 gap-2">
                            <h4 class="fw-bolder mb-1" style="color:var(--kgm-green-800);">ฝ่ายขาย (สำนักงานใหญ่)</h4>
                            <p class="text-secondary mb-2" style="font-size:14px;line-height:1.9;">
                                บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด<br>
                                364 หมู่ 6 ถ.ศรีสะเกษ–กันทรลักษ์<br>
                                ต.โพนข่า อ.เมืองศรีสะเกษ จ.ศรีสะเกษ 33000
                            </p>
                            <a href="tel:0851100010" class="text-decoration-none fw-semibold" style="color:var(--kgm-green-700);font-size:14px;">ฝ่ายขาย : 085-110-0010, 085-110-0060</a>
                            <a href="tel:045611111" class="text-decoration-none fw-semibold" style="color:var(--kgm-green-700);font-size:14px;">โทร : (045) 611111, 633111</a>
                            <span class="text-muted" style="font-size:13px;"><i class="bi bi-clock me-1"></i>จ–ส 08:00–17:00 น.</span>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-4 overflow-hidden shadow-sm mb-4">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-md-6 overflow-hidden">
                            <img src="{{ asset('images/showroom_branch.jpg') }}" alt="KGM Showroom" class="showroom-img">
                        </div>
                        <div class="col-md-6 d-flex flex-column justify-content-center p-4 p-lg-5 gap-2">
                            <h4 class="fw-bolder mb-1" style="color:var(--kgm-green-800);">โชว์รูมกิจเจริญการ์เมนท์</h4>
                            <p class="text-secondary mb-2" style="font-size:14px;line-height:1.9;">
                                642/6-8 ถ.ราชการรถไฟ3<br>
                                ต.เมืองใต้ อ.เมือง จ.ศรีสะเกษ 33000
                            </p>
                            <a href="tel:045615555" class="text-decoration-none fw-semibold" style="color:var(--kgm-green-700);font-size:14px;">ฝ่ายขาย : 045-615-555</a>
                            <div class="rounded-3 overflow-hidden mt-2" style="max-width:240px;">
                                <img src="{{ asset('images/showroom_1.jpg') }}" alt="Location QR" class="w-100 d-block">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-10">
                <div class="px-3 pb-3">
                    <img src="{{ asset('images/showroom_2.jpg') }}" alt="KGM Showroom" class="w-100 d-block rounded-3">
                </div>
            </div>
        </div>
           

        {{-- แผนที่ --}}
        <div class="row g-4">
            <div class="col-md-6">
                <div class="bg-white rounded-4 overflow-hidden shadow-sm h-100">
                    <div class="d-flex align-items-center gap-2 px-4 py-3 border-bottom fw-bold">
                        <i class="bi bi-map-fill"></i>สาขา 1 — สำนักงานใหญ่
                    </div>
                    <iframe src="https://maps.google.com/maps?q=364+%E0%B8%AB%E0%B8%A1%E0%B8%B9%E0%B9%88+6+%E0%B8%96.%E0%B8%A8%E0%B8%A3%E0%B8%B5%E0%B8%AA%E0%B8%B0%E0%B9%80%E0%B8%81%E0%B8%A9-%E0%B8%81%E0%B8%B1%E0%B8%99%E0%B8%97%E0%B8%A3%E0%B8%A5%E0%B8%B1%E0%B8%81%E0%B8%A9%E0%B9%8C+%E0%B8%95.%E0%B9%82%E0%B8%9E%E0%B8%99%E0%B8%82%E0%B9%88%E0%B8%B2+%E0%B8%A8%E0%B8%A3%E0%B8%B5%E0%B8%AA%E0%B8%B0%E0%B9%80%E0%B8%81%E0%B8%A9&output=embed&hl=th"
                        class="w-100 d-block border-0" height="340" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
            <div class="col-md-6">
                <div class="bg-white rounded-4 overflow-hidden shadow-sm h-100">
                    <div class="d-flex align-items-center gap-2 px-4 py-3 border-bottom fw-bold">
                        <i class="bi bi-map-fill"></i>สาขา 2 — KGM Showroom
                    </div>
                    <iframe src="https://maps.google.com/maps?q=642%2F6-8+%E0%B8%96.%E0%B8%A3%E0%B8%B2%E0%B8%8A%E0%B8%81%E0%B8%B2%E0%B8%A3%E0%B8%A3%E0%B8%96%E0%B9%84%E0%B8%9F+3+%E0%B8%95.%E0%B9%80%E0%B8%A1%E0%B8%B7%E0%B8%AD%E0%B8%87%E0%B9%83%E0%B8%95%E0%B9%89+%E0%B8%A8%E0%B8%A3%E0%B8%B5%E0%B8%AA%E0%B8%B0%E0%B9%80%E0%B8%81%E0%B8%A9&output=embed&hl=th"
                        class="w-100 d-block border-0" height="340" allowfullscreen loading="lazy"></iframe>
                </div>
            </div>
        </div>

    </div>
</section>

@endsection
