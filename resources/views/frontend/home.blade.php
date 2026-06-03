@extends('layouts.app')
@section('title', 'หน้าแรก - กิจเจริญการ์เมนท์')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/home.css') }}">
@endpush

@section('content')

{{-- ══ HERO ══ --}}
@if($banners->isNotEmpty())
<div class="hero" id="hero-slider">
    @foreach($banners as $i => $banner)
    <div class="hero-slide {{ $i === 0 ? 'active' : '' }}">
        @if($banner->link_url)
        <a href="{{ $banner->link_url }}">
            <img src="{{ asset('storage/'.$banner->image_path) }}" alt="Banner {{ $i+1 }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" onerror="this.onerror=null;this.src='/images/logo.png'">
        </a>
        @else
        <img src="{{ asset('storage/'.$banner->image_path) }}" alt="Banner {{ $i+1 }}" loading="{{ $i === 0 ? 'eager' : 'lazy' }}" onerror="this.onerror=null;this.src='/images/logo.png'">
        @endif
    </div>
    @endforeach

    @if($banners->count() > 1)
    <button class="hero-nav prev" onclick="heroSlide(-1)" aria-label="ก่อนหน้า"><i class="bi bi-chevron-left"></i></button>
    <button class="hero-nav next" onclick="heroSlide(1)"  aria-label="ถัดไป"><i class="bi bi-chevron-right"></i></button>
    <div class="hero-dots" id="hero-dots">
        @foreach($banners as $i => $_)
        <button class="hero-dot {{ $i===0?'active':'' }}" onclick="heroGo({{ $i }})" aria-label="สไลด์ {{ $i+1 }}"></button>
        @endforeach
    </div>
    @endif
</div>
@endif

{{-- ══ FEATURES BAR ══ --}}
<div class="features-bar">
    <div class="container">
        <div class="features-grid">
            @foreach([
                ['bi-award',     'คุณภาพมาตรฐาน',   'ISO Certified ผ่าน QC ทุกชิ้น'],
                ['bi-truck',     'จัดส่งทั่วไทย',    'ส่งฟรีเมื่อซื้อครบ ฿1,000'],
                ['bi-palette',   'ออกแบบเอง',        'Custom Design ตามต้องการ'],
                ['bi-headset',   'บริการหลังการขาย', 'ทีมงานพร้อมช่วยเหลือ'],
            ] as [$icon,$title,$sub])
            <div class="feature-item">
                <div class="feature-icon"><i class="bi {{ $icon }}"></i></div>
                <div class="feature-text">
                    <strong>{{ $title }}</strong>
                    <span>{{ $sub }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ══ CATEGORIES ══ --}}
@if($categories->isNotEmpty())
<section class="cat-section">
    <div class="container">
        <div style="text-align:center;margin-bottom:24px;">
            <div class="section-subtitle" style="text-align:center;">เลือกตามหมวดหมู่</div>
            <div class="section-title">หมวดหมู่สินค้า</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div class="cat-grid">
            @foreach($categories as $cat)
            <a href="{{ route('shop.category', $cat->slug) }}" class="cat-card">
                <div class="cat-img-wrap">
                    <img src="{{ asset('storage/'.$cat->image) }}" alt="{{ $cat->name }}" loading="lazy">
                    <div class="cat-overlay"><i class="bi bi-heart"></i></div>
                </div>
                <div class="cat-name">{{ $cat->name }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══ PRODUCTS ══ --}}
@if($featuredProducts->isNotEmpty() || $bestsellerProducts->isNotEmpty() || $newProducts->isNotEmpty())
<section class="section" style="background:white;">
    <div class="container">
        <div style="text-align:center;margin-bottom:28px;">
            <div class="section-subtitle" style="text-align:center;">สินค้าของเรา</div>
            <div class="section-title">สินค้าแนะนำและยอดนิยม</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>

        <div class="product-tabs">
            @if($featuredProducts->isNotEmpty())
            <button class="product-tab active" onclick="switchTab('featured',this)"><i class="bi bi-star-fill"></i> สินค้าแนะนำ</button>
            @endif
            @if($bestsellerProducts->isNotEmpty())
            <button class="product-tab {{ $featuredProducts->isEmpty()?'active':'' }}" onclick="switchTab('bestseller',this)"><i class="bi bi-fire"></i> ขายดี</button>
            @endif
            @if($newProducts->isNotEmpty())
            <button class="product-tab" onclick="switchTab('new',this)"><i class="bi bi-lightning-charge-fill"></i> สินค้าใหม่</button>
            @endif
        </div>

        <div id="tab-featured"><div class="grid grid-5" style="gap:14px;">@foreach($featuredProducts as $product)@include('components.product-card')@endforeach</div></div>
        <div id="tab-bestseller" style="display:none;"><div class="grid grid-5" style="gap:14px;">@foreach($bestsellerProducts as $product)@include('components.product-card')@endforeach</div></div>
        <div id="tab-new"        style="display:none;"><div class="grid grid-5" style="gap:14px;">@foreach($newProducts as $product)@include('components.product-card')@endforeach</div></div>

        <div style="text-align:center;margin-top:24px;">
            <a href="{{ route('shop') }}" class="btn btn-outline"><i class="bi bi-grid-3x3-gap"></i> ดูสินค้าทั้งหมด</a>
        </div>
    </div>
</section>
@endif

{{-- ══ ABOUT + SERVICE HIGHLIGHTS ══ --}}
<img src="/images/cartoon.jpg" alt="KGM" class="">
<section style="background:#E1CD94;" class="pt-0 pb-5">
    <div class="container">
        <div class="grid" style="gap:24px;align-items:stretch;">
            <div class="row justify-content-center g-0">
                <div class="col-md-10">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3 mt-0" style="padding:32px 36px;display:flex;flex-direction:column;justify-content:center;box-sizing:border-box;">
                                <h2 style="font-size:15px;font-weight:800;color:var(--kgm-green-900);margin:0 0 12px;">บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด</h2>
                                <p style="font-size:14px;color:#555;line-height:1.95;margin:0;text-indent:2em;">
                                    เราคือผู้ผลิตและจำหน่ายเครื่องแบบนักเรียนมากกว่า 40 ปี เรามุ่งมั่นสร้างสรรค์ชุดเครื่องแบบเพื่อคนไทยและเด็กนักเรียนทั่วประเทศ
                                    โดยคำนึงถึงคุณภาพและความคุ้มค่ามาเป็นที่หนึ่ง ใส่ใจในทุกรายละเอียด และพิถีพิถันทุกขั้นตอนการผลิตอย่างที่สุด
                                </p>
                                <a href="{{ route('about') }}" class="btn btn-outline" style="display:inline-flex;align-items:center;gap:6px;margin-top:20px;font-size:13px;font-weight:700;text-decoration:none;">
                                    อ่านเพิ่มเติมเกี่ยวกับเรา <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>

                        </div>
                        <div class="col-md-6">
                            {{-- Service highlight cards --}}
                            <div style="display:flex;flex-direction:column;gap:12px;justify-content:space-between;">
                                @foreach([
                                    ['bi-palette-fill',      'Design & Production Services', 'บริการออกแบบและสั่งผลิต',      'order'],
                                    ['bi-layers-fill',        'Embroidery and Screen Printing','บริการงานปักและสกรีน',         'embroidery-screen'],
                                    ['bi-mortarboard-fill',   'School & Corporate Uniforms',  'เครื่องแบบนักเรียนและองค์กร',  'school-uniforms'],
                                ] as [$icon, $title, $sub, $r])
                                <a href="{{ route($r) }}" class="card" style="display:flex;flex-direction:row;align-items:stretch;padding:0;overflow:hidden;text-decoration:none;transition:box-shadow 0.2s,transform 0.2s;"
                                onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(0,0,0,0.13)'"
                                onmouseout="this.style.transform='';this.style.boxShadow=''">
                                    {{-- col-4: icon --}}
                                    <div style="flex:0 0 33.33%;background:var(--kgm-green-100);display:flex;align-items:center;justify-content:center;">
                                        <i class="bi {{ $icon }}" style="font-size:40px;color:var(--kgm-green-600);"></i>
                                    </div>
                                    {{-- col-8: text --}}
                                    <div style="flex:0 0 66.66%;padding:18px 20px;display:flex;flex-direction:column;justify-content:center;gap:4px;">
                                        <div style="font-size:15px;font-weight:800;color:var(--kgm-green-900);line-height:1.3;">{{ $title }}</div>
                                        <div style="font-size:12px;color:#999;">{{ $sub }}</div>
                                    </div>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ PROMO BANNERS ══ --}}
<section style="padding:28px 0;">
    <div class="container">
        <div class="grid grid-2" style="gap:14px;">
            <div class="promo-banner green">
                <div>
                    <div style="color:rgba(255,255,255,0.65);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">บริการพิเศษ</div>
                    <div class="promo-title" style="color:white;">ออกแบบเครื่องแบบ<br>ตามสั่งได้ทุกแบบ</div>
                    <a href="{{ route('quote') }}" class="btn btn-gold btn-sm"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคา</a>
                </div>
            </div>
            <div class="promo-banner gold">
                <div>
                    <div style="color:rgba(0,0,0,0.4);font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:1px;">ตัวแทนจำหน่าย</div>
                    <div class="promo-title" style="color:var(--kgm-green-900);">สนใจเป็นตัวแทน<br>จำหน่าย KGM</div>
                    <a href="{{ route('dealer') }}" class="btn btn-sm" style="background:var(--kgm-green-800);color:white;"><i class="bi bi-shop"></i> สมัครตัวแทน</a>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ══ SERVICES ══ --}}
<section class="section" style="background:#f8faf8;">
    <div class="container">
        <div style="text-align:center;margin-bottom:28px;">
            <div class="section-subtitle" style="text-align:center;">สิ่งที่เราทำ</div>
            <div class="section-title">บริการของเรา</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div class="grid grid-3" style="gap:14px;">
            @foreach([
                ['bi-mortarboard-fill','เครื่องแบบนักเรียน','ผลิตครบชุดทุกระดับชั้น ชาย-หญิง ได้มาตรฐาน MOE'],
                ['bi-person-badge-fill','ยูนิฟอร์มองค์กร','ออกแบบและผลิตสำหรับบริษัท โรงแรม โรงพยาบาล'],
                ['bi-scissors','ปักและสกรีน','ปักโลโก้ สกรีนลาย ด้วยเครื่องจักรทันสมัย คมชัด ทนทาน'],
            ] as [$icon,$title,$desc])
            <div class="card" style="text-align:center;padding:24px 18px;">
                <div style="width:54px;height:54px;background:var(--kgm-green-100);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:24px;color:var(--kgm-green-600);margin:0 auto 12px;">
                    <i class="bi {{ $icon }}"></i>
                </div>
                <h3 style="font-size:15px;font-weight:700;color:var(--kgm-green-800);margin:0 0 8px;">{{ $title }}</h3>
                <p style="font-size:13px;color:#666;line-height:1.7;margin:0;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
        <div style="text-align:center;margin-top:22px;">
            <a href="{{ route('services') }}" class="btn btn-outline"><i class="bi bi-arrow-right-circle"></i> ดูบริการทั้งหมด</a>
        </div>
    </div>
</section>

{{-- ══ TESTIMONIALS ══ --}}
@if($testimonials->isNotEmpty())
<section class="section" style="background:var(--kgm-green-800);">
    <div class="container">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="color:var(--kgm-gold-300);font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:2px;margin-bottom:6px;">ความคิดเห็น</div>
            <div style="font-size:clamp(20px,4vw,28px);font-weight:700;color:white;">เสียงจากลูกค้าของเรา</div>
        </div>
        <div class="testimonials-grid">
            @foreach($testimonials as $review)
            <div style="background:rgba(255,255,255,0.08);border-radius:16px;padding:20px;">
                <div style="color:var(--kgm-gold-300);font-size:15px;margin-bottom:10px;">
                    @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$review->rating?'-fill':'' }}"></i>@endfor
                </div>
                <p style="color:rgba(255,255,255,0.85);font-size:14px;line-height:1.8;margin:0 0 14px;">"{{ $review->body }}"</p>
                <div style="display:flex;align-items:center;gap:10px;">
                    <div style="width:34px;height:34px;background:var(--kgm-gold-500);border-radius:999px;display:flex;align-items:center;justify-content:center;font-weight:700;color:var(--kgm-green-900);font-size:13px;flex-shrink:0;">{{ strtoupper(substr($review->user->name,0,1)) }}</div>
                    <div>
                        <div style="color:white;font-weight:700;font-size:13px;">{{ $review->user->name }}</div>
                        <div style="color:rgba(255,255,255,0.45);font-size:11px;">{{ $review->product->name }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══ LATEST NEWS ══ --}}
@if($latestPosts->isNotEmpty())
<section class="section">
    <div class="container">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;gap:10px;flex-wrap:wrap;">
            <div>
                <div class="section-subtitle">บทความและข่าวสาร</div>
                <div class="section-title">ข่าวสารล่าสุด</div>
                <div class="section-divider"></div>
            </div>
            <a href="{{ route('news') }}" class="btn btn-outline btn-sm" style="margin-bottom:6px;"><i class="bi bi-newspaper"></i> ดูทั้งหมด</a>
        </div>
        <div class="grid grid-3" style="gap:14px;">
            @foreach($latestPosts as $post)
            <div class="card">
                @if($post->featured_image)
                <div style="height:165px;overflow:hidden;">
                    <img src="{{ asset('storage/'.$post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" style="width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.src='/images/logo.png'">
                </div>
                @endif
                <div class="card-body">
                    <div style="font-size:11px;color:var(--kgm-green-500);font-weight:700;text-transform:uppercase;margin-bottom:5px;">{{ $post->postCategory?->name }}</div>
                    <h3 style="font-size:14px;font-weight:700;margin:0 0 7px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $post->title }}</h3>
                    <p style="font-size:13px;color:#666;margin:0 0 10px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $post->excerpt }}</p>
                    <a href="{{ route('news.show', $post->slug) }}" style="font-size:13px;font-weight:700;color:var(--kgm-green-600);">อ่านต่อ <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ══ ONLINE CHANNELS ══ --}}
<section class="section online-channels-section">
    <div class="container">
        <div style="text-align:center;">
            <div class="online-channels-title">Online Shopping</div>
            <div class="online-channels-subtitle">สั่งซื้อออนไลน์</div>
        </div>
        <div class="online-channels-grid">
            @foreach([
                ['https://shopee.co.th/kgmuniform',          '/images/online/online_shopping_shopee.jpg',  'Shopee'],
                ['https://www.lazada.co.th/shop/kgmuniform', '/images/online/online_shopping_Lazada.jpg',  'Lazada'],
                ['https://www.tiktok.com/@kgmuniform',       '/images/online/online_shopping_tiktok.jpg',  'TikTok Shop'],
            ] as [$url, $img, $name])
            <a href="{{ $url }}" target="_blank" rel="noopener noreferrer" class="online-channel-card">
                <img src="{{ $img }}" alt="{{ $name }}" loading="lazy">
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ══ CTA ══ --}}
<section style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-weight:700;font-size:12px;text-transform:uppercase;letter-spacing:2px;margin-bottom:10px;">พร้อมเริ่มต้นแล้วหรือยัง?</div>
        <h2 style="font-size:clamp(20px,4.5vw,36px);font-weight:800;color:white;margin:0 0 12px;line-height:1.55;">สั่งผลิตเครื่องแบบคุณภาพสูง<br>ในราคาที่คุ้มค่า</h2>
        <p style="font-size:15px;color:rgba(255,255,255,0.7);margin:0 0 26px;">ติดต่อเราเพื่อรับใบเสนอราคาฟรี ไม่มีค่าใช้จ่าย</p>
        <div class="cta-btns" style="display:flex;gap:10px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('quote') }}"   class="btn btn-gold btn-lg"><i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคาฟรี</a>
            <a href="{{ route('contact') }}" class="btn btn-lg" style="background:rgba(255,255,255,0.12);color:white;border:1.5px solid rgba(255,255,255,0.25);"><i class="bi bi-telephone"></i> ติดต่อเรา</a>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
// ── Hero slider ──
let heroIdx = 0;
const heroSlides = document.querySelectorAll('.hero-slide');
const heroDots   = document.querySelectorAll('.hero-dot');
let heroTimer;

function heroGo(n) {
    heroSlides[heroIdx]?.classList.remove('active');
    heroDots[heroIdx]?.classList.remove('active');
    heroIdx = (n + heroSlides.length) % heroSlides.length;
    heroSlides[heroIdx]?.classList.add('active');
    heroDots[heroIdx]?.classList.add('active');
}
function heroSlide(dir) {
    clearInterval(heroTimer);
    heroGo(heroIdx + dir);
    heroTimer = setInterval(() => heroSlide(1), 5500);
}
if (heroSlides.length > 1) heroTimer = setInterval(() => heroSlide(1), 5500);

// Touch swipe on hero
const heroEl = document.getElementById('hero-slider');
if (heroEl) {
    let tx = 0;
    heroEl.addEventListener('touchstart', e => { tx = e.touches[0].clientX; }, { passive: true });
    heroEl.addEventListener('touchend',   e => {
        const diff = tx - e.changedTouches[0].clientX;
        if (Math.abs(diff) > 40) heroSlide(diff > 0 ? 1 : -1);
    });
}

// ── Product tabs ──
function switchTab(name, btn) {
    document.querySelectorAll('[id^="tab-"]').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.product-tab').forEach(el => el.classList.remove('active'));
    const pane = document.getElementById('tab-' + name);
    if (pane) pane.style.display = '';
    btn.classList.add('active');
}
</script>
@endpush
