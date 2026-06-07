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

{{-- ══ COUPON CAROUSEL ══ --}}
@if($homeCoupons->isNotEmpty())
<section class="cq-sec">
    <div class="container">
        <div class="cq-wrap">
            <button class="cq-arrow cq-prev" onclick="cqSlide(-1)" aria-label="ก่อนหน้า"><i class="bi bi-chevron-left"></i></button>
            <button class="cq-arrow cq-next" onclick="cqSlide(1)"  aria-label="ถัดไป"><i class="bi bi-chevron-right"></i></button>

            <div class="cq-viewport">
                <div class="cq-track" id="cq-track">
                    @foreach($homeCoupons as $i => $coupon)
                    <div class="cq-ticket">
                        {{-- LEFT: image bg or gradient --}}
                        <div class="cq-left"
                             @if($coupon->image) style="background-image:url('{{ asset('storage/'.$coupon->image) }}');" @endif>
                            @if(!$coupon->image)
                            <div class="cq-left-inner">
                                @if($coupon->type === 'free_shipping')
                                    <i class="bi bi-truck" style="font-size:20px;"></i>
                                    <span class="cq-lbl">ส่งฟรี</span>
                                @elseif($coupon->type === 'percent')
                                    <span class="cq-num">{{ (int)$coupon->value }}%</span>
                                    <span class="cq-lbl">ส่วนลด</span>
                                @else
                                    <span class="cq-num" style="font-size:17px;">฿{{ number_format((float)$coupon->value,0) }}</span>
                                    <span class="cq-lbl">ส่วนลด</span>
                                @endif
                            </div>
                            @endif
                        </div>
                        {{-- notch circles --}}
                        <span class="cq-notch cq-nt"></span>
                        <span class="cq-notch cq-nb"></span>
                        {{-- RIGHT --}}
                        <div class="cq-right">
                            <div class="cq-name">{{ $coupon->name }}</div>
                            @if($coupon->minimum_order > 0)
                            <div class="cq-cond">ซื้อขั้นต่ำ ฿{{ number_format($coupon->minimum_order,0) }}</div>
                            @endif
                            <a href="{{ route('coupons.index') }}" class="cq-btn">เก็บคูปอง</a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>
@endif
{{-- ══ PRODUCT TYPES ══ --}}
@if($productTypes->isNotEmpty())
<section class="type-section">
    <div class="container">
        <div class="type-grid">
            @foreach($productTypes as $pt)
            <a href="{{ route('shop') }}?type={{ $pt->slug }}" class="type-card">
                <div class="type-img-wrap">
                    @if($pt->image)
                        <img src="{{ asset('storage/'.$pt->image) }}" alt="{{ $pt->name }}" loading="lazy">
                    @else
                        <div class="type-img-placeholder"><i class="bi bi-grid-3x3-gap"></i></div>
                    @endif
                </div>
                <div class="type-name">{{ $pt->name }}</div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif



{{-- ══ CATEGORIES ══ --}}
@if($categories->isNotEmpty())
<section class="cat-section">
    <div class="container">
        <div style="text-align:center;margin-bottom:24px;">
            <div class="section-title">หมวดหมู่สินค้า</div>
            <div class="section-divider" style="margin:10px auto 0;"></div>
        </div>
        <div class="cat-grid">
            @foreach($categories as $cat)
            <a href="{{ route('shop.category', $cat->slug) }}" class="cat-card">
                <div class="cat-img-wrap">
                    <img src="{{ asset('storage/'.$cat->image) }}" alt="{{ $cat->name }}" loading="lazy">
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
<section class="pt-5 pb-0" style="background:white;">
    <div class="container">
        <div style="text-align:center;margin-bottom:28px;">
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
                        <div class="col-md-6  mb-3">
                            <div class="card" style="height:100%;padding:32px 36px;display:flex;flex-direction:column;justify-content:center;box-sizing:border-box;">
                                <h2 style="font-size:19px;color:#236237;font-weight:800;color:var(--kgm-green-900);margin:0 0 12px;">บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด</h2>
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

{{-- ══ VIDEOS ══ --}}
@if(isset($featuredVideos) && $featuredVideos->isNotEmpty())
<section class="section" style="background:#f8faf8;">
    <div class="container">
        <div style="display:flex;align-items:flex-end;justify-content:space-between;margin-bottom:22px;gap:10px;flex-wrap:wrap;">
            <div>
                <div class="section-subtitle">YouTube</div>
                <div class="section-title">เรื่องราวที่น่าสนใจ</div>
                <div class="section-divider"></div>
            </div>
            <a href="{{ route('videos') }}" class="btn btn-outline btn-sm" style="margin-bottom:6px;"><i class="bi bi-play-circle"></i> ดูทั้งหมด</a>
        </div>
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
            @foreach($featuredVideos->take(3) as $video)
            <div style="background:white;border-radius:14px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.07);cursor:pointer;transition:transform .2s,box-shadow .2s;"
                 onmouseenter="this.style.transform='translateY(-4px)';this.style.boxShadow='0 8px 28px rgba(0,0,0,.13)'"
                 onmouseleave="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(0,0,0,.07)'"
                 onclick="homeOpenVideo('{{ $video->youtube_id }}')">
                <div style="position:relative;padding-bottom:56.25%;background:#111;overflow:hidden;">
                    <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" loading="lazy"
                         style="position:absolute;top:0;left:0;width:100%;height:100%;object-fit:cover;"
                         onerror="this.src='/images/logo.png'">
                    <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(0,0,0,.25);">
                        <div style="width:52px;height:52px;background:rgba(255,255,255,.9);border-radius:50%;display:flex;align-items:center;justify-content:center;padding-left:4px;box-shadow:0 4px 16px rgba(0,0,0,.3);">
                            <i class="bi bi-play-fill" style="font-size:22px;color:#e30000;"></i>
                        </div>
                    </div>
                </div>
                <div style="padding:14px;">
                    <div style="font-size:14px;font-weight:700;color:#1a2e1a;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;margin-bottom:6px;line-height:1.4;">{{ $video->title }}</div>
                    @if($video->description)
                    <div style="font-size:12px;color:#888;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $video->description }}</div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- Home Video Modal --}}
<div id="home-yt-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.85);align-items:center;justify-content:center;" onclick="homeCloseVideo(event)">
    <div style="width:90%;max-width:860px;background:#000;border-radius:12px;overflow:hidden;position:relative;">
        <button onclick="homeCloseVideo(null,true)" style="position:absolute;top:-40px;right:0;background:none;border:none;color:white;font-size:30px;cursor:pointer;line-height:1;">&times;</button>
        <div style="position:relative;padding-bottom:56.25%;height:0;">
            <iframe id="home-yt-iframe" src="" frameborder="0"
                    style="position:absolute;top:0;left:0;width:100%;height:100%;"
                    allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    </div>
</div>
@push('scripts')
<script>
function homeOpenVideo(id) {
    document.getElementById('home-yt-iframe').src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0';
    const m = document.getElementById('home-yt-modal');
    m.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}
function homeCloseVideo(e, force) {
    if (force || e.target === document.getElementById('home-yt-modal')) {
        document.getElementById('home-yt-iframe').src = '';
        document.getElementById('home-yt-modal').style.display = 'none';
        document.body.style.overflow = '';
    }
}
document.addEventListener('keydown', function(e) { if(e.key==='Escape') homeCloseVideo(null,true); });
</script>
@endpush
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
// ── Coupon carousel (infinite clone) ──
(function(){
    const track = document.getElementById('cq-track');
    if (!track) return;
    const origCards = Array.from(track.querySelectorAll('.cq-ticket'));
    const total = origCards.length;
    if (total === 0) return;

    // Append clones so the list appears to continue forever
    const clones = origCards.map(c => { const cl = c.cloneNode(true); track.appendChild(cl); return cl; });

    const GAP = 14, CARD = 272, STEP = CARD + GAP;
    let cur = 0, tmr = null;

    function vis() {
        const w = track.parentElement.offsetWidth;
        return w < 360 ? 1 : w < 640 ? 2 : Math.min(total, Math.floor((w + GAP) / STEP));
    }
    function maxI() { return Math.max(0, total - vis()); }

    // Show/hide clones depending on whether scrolling is needed
    function updateClones() {
        const show = maxI() > 0;
        clones.forEach(c => { c.style.display = show ? '' : 'none'; });
    }

    // Silent jump — remove transition, move, restore transition
    function snapTo(i) {
        track.style.transition = 'none';
        cur = i;
        track.style.transform = `translateX(-${cur * STEP}px)`;
        requestAnimationFrame(() => { track.style.transition = ''; });
    }

    function goTo(i) {
        updateClones();
        const vw = track.parentElement.offsetWidth;
        const tw = total * CARD + Math.max(0, total - 1) * GAP;
        track.style.marginLeft = tw < vw ? `${(vw - tw) / 2}px` : '0';
        if (maxI() === 0) { snapTo(0); return; }
        cur = i;
        track.style.transform = `translateX(-${cur * STEP}px)`;
    }

    // After the CSS transition (~450ms), silently jump from clone back to original
    function afterSlide() {
        if (cur >= total) snapTo(cur - total);
        else if (cur < 0) snapTo(cur + total);
    }

    function slide(d) {
        reset();
        goTo(cur + d);
        setTimeout(afterSlide, 460);
    }

    function reset() {
        clearInterval(tmr);
        tmr = setInterval(() => { goTo(cur + 1); setTimeout(afterSlide, 460); }, 4500);
    }

    window.cqSlide = d => slide(d);
    window.cqGo    = i => { reset(); goTo(i); };
    goTo(0);
    reset();
    let resizeTmr;
    window.addEventListener('resize', () => { clearTimeout(resizeTmr); resizeTmr = setTimeout(() => { afterSlide(); goTo(cur); }, 150); });

    // drag / swipe
    let startX = 0, startY = 0, horizLock = false;
    const vp = track.parentElement;
    vp.addEventListener('mousedown',  e => { startX = e.clientX; vp.style.cursor = 'grabbing'; });
    vp.addEventListener('mousemove',  e => { if (e.buttons !== 1) return; e.preventDefault(); });
    vp.addEventListener('mouseup',    e => { vp.style.cursor = ''; const dx = startX - e.clientX; if (Math.abs(dx) > 30) slide(dx > 0 ? 1 : -1); });
    vp.addEventListener('mouseleave', () => { vp.style.cursor = ''; });
    vp.addEventListener('touchstart', e => { startX = e.touches[0].clientX; startY = e.touches[0].clientY; horizLock = false; }, { passive: true });
    vp.addEventListener('touchmove',  e => { const dx = Math.abs(startX - e.touches[0].clientX), dy = Math.abs(startY - e.touches[0].clientY); if (!horizLock && dx > dy) horizLock = true; if (horizLock) e.preventDefault(); }, { passive: false });
    vp.addEventListener('touchend',   e => { const dx = startX - e.changedTouches[0].clientX; if (Math.abs(dx) > 30) slide(dx > 0 ? 1 : -1); });
})();

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
