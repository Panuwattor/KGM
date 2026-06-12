<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'กิจเจริญการ์เมนท์') | KGM</title>
    <meta name="description" content="@yield('meta_description', 'กิจเจริญการ์เมนท์ (1993) จำกัด โรงงานผลิตเครื่องแบบนักเรียน ยูนิฟอร์ม และเสื้อผ้าคุณภาพสูง')">
    @yield('og_tags')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" type="image/png" href="/images/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    @stack('styles')
</head>
<body>

{{-- ══ Main Navbar ══ --}}
<nav class="navbar-kgm" x-data="mobileNav()">
    <div class="navbar-main">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="navbar-brand">
            <img src="{{ asset('images/kgm_logo.png') }}" alt="KGM Logo" class="brand-logo">
        </a>

        {{-- Desktop links --}}
        <div class="nav-links">
            <a href="{{ route('home') }}"      class="nav-link {{ request()->routeIs('home')      ? 'active' : '' }}">หน้าแรก</a>
            <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop') ? 'active' : '' }}">สินค้า</a>

            {{-- ประเภทสินค้า dropdown --}}
            @if($navProductTypes->isNotEmpty())
            <div class="nav-dropdown" x-data="{ open: false, t: null }" @mouseenter="clearTimeout(t); open=true" @mouseleave="t = setTimeout(() => open=false, 120)">
                <a href="{{ route('shop') }}" class="nav-link {{ request()->fullUrlWithQuery(['type' => '']) && request()->has('type') ? 'active' : '' }}">
                    ประเภท <i class="bi bi-chevron-down" style="font-size:10px;margin-left:2px;"></i>
                </a>
                <div class="nav-dropdown-panel nav-dropdown-panel-sm" x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
                    <a href="{{ route('shop') }}" class="nav-dropdown-item">
                        <i class="bi bi-grid-3x3-gap"></i> ทั้งหมด
                    </a>
                    @foreach($navProductTypes as $pt)
                    <a href="{{ route('shop') }}?type={{ $pt->slug }}" class="nav-dropdown-item {{ request()->get('type') === $pt->slug ? 'nav-dropdown-item-active' : '' }}">
                        <image src="{{ $pt->image }}" alt="{{ $pt->name }}" style="width:16px;height:16px;object-fit:contain;margin-right:6px;">
                         {{ $pt->name }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- หมวดหมู่ dropdown --}}
            @if($navCategories->isNotEmpty())
            <div class="nav-dropdown" x-data="{ open: false, t: null }" @mouseenter="clearTimeout(t); open=true" @mouseleave="t = setTimeout(() => open=false, 120)">
                <a href="{{ route('shop') }}" class="nav-link {{ request()->routeIs('shop.category') ? 'active' : '' }}">
                    หมวดหมู่ <i class="bi bi-chevron-down" style="font-size:10px;margin-left:2px;"></i>
                </a>
                <div class="nav-dropdown-panel nav-dropdown-panel-sm" x-show="open"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-100"
                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-cloak>
                    @foreach($navCategories as $cat)
                    <a href="{{ route('shop.category', $cat->slug) }}" class="nav-dropdown-item nav-dropdown-parent {{ request()->route('slug') === $cat->slug ? 'nav-dropdown-item-active' : '' }}">
                        <i class="bi bi-folder2"></i> {{ $cat->name }}
                    </a>
                    @foreach($cat->children as $child)
                    <a href="{{ route('shop.category', $child->slug) }}" class="nav-dropdown-item nav-dropdown-child {{ request()->route('slug') === $child->slug ? 'nav-dropdown-item-active' : '' }}">
                        <i class="bi bi-dash"></i> {{ $child->name }}
                    </a>
                    @endforeach
                    @endforeach
                </div>
            </div>
            @endif
            <a href="{{ route('coupons.index') }}" class="nav-link {{ request()->routeIs('coupons*') ? 'active' : '' }}">คูปอง</a>
            <a href="{{ route('services') }}"  class="nav-link {{ request()->routeIs('services')  ? 'active' : '' }}">บริการ</a>
            <a href="{{ route('about') }}"     class="nav-link {{ request()->routeIs('about')     ? 'active' : '' }}">เกี่ยวกับเรา</a>
            <a href="{{ route('news') }}"      class="nav-link {{ request()->routeIs('news*')     ? 'active' : '' }}">ข่าวสาร</a>
            <a href="{{ route('careers') }}"   class="nav-link {{ request()->routeIs('careers*')  ? 'active' : '' }}">ร่วมงานกับเรา</a>
            <a href="{{ route('contact') }}"   class="nav-link {{ request()->routeIs('contact')   ? 'active' : '' }}">ติดต่อเรา</a>
        </div>

        {{-- Action icons --}}
        <div class="nav-actions">
            <a href="{{ route('shop') }}" class="nav-btn" title="ค้นหา"><i class="bi bi-search"></i></a>

            @auth('customer')
            {{-- Wishlist --}}
            <a href="{{ route('account.wishlist') }}" class="nav-btn" title="รายการโปรด"><i class="bi bi-heart"></i></a>
            @endauth

            {{-- Cart --}}
            <a href="{{ route('cart') }}" class="nav-btn" title="ตะกร้า">
                <i class="bi bi-cart3"></i>
                <span class="cart-badge" id="cart-count" style="display:none;">0</span>
            </a>

            {{-- User icon (desktop) --}}
            @if(auth('customer')->check())
            <div class="user-dropdown" x-data="{ open: false }" @click.outside="open = false">
                <button class="nav-btn" style="padding:0;" @click="open = !open" title="{{ auth('customer')->user()->name }}">
                    <div class="user-avatar"><i class="bi bi-person-fill"></i></div>
                </button>
                <div class="user-dropdown-menu" x-show="open" x-transition x-cloak>
                    <div class="user-dropdown-header">
                        <div class="user-avatar user-avatar-lg"><i class="bi bi-person-fill"></i></div>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#2c3e50;line-height:1.3;">{{ auth('customer')->user()->name }}</div>
                            <div style="font-size:11px;color:#999;">{{ auth('customer')->user()->email }}</div>
                        </div>
                    </div>
                    <a href="{{ route('account.index') }}"     class="user-dropdown-item" @click="open=false"><i class="bi bi-speedometer2"></i> ภาพรวมบัญชี</a>
                    <a href="{{ route('account.orders') }}"    class="user-dropdown-item" @click="open=false"><i class="bi bi-receipt"></i> คำสั่งซื้อของฉัน</a>
                    <a href="{{ route('account.quotes') }}"    class="user-dropdown-item" @click="open=false"><i class="bi bi-file-earmark-text"></i> ใบเสนอราคา</a>
                    <a href="{{ route('account.wishlist') }}"  class="user-dropdown-item" @click="open=false"><i class="bi bi-heart"></i> รายการโปรด</a>
                    <a href="{{ route('account.coupons') }}"   class="user-dropdown-item" @click="open=false"><i class="bi bi-ticket-perforated"></i> คูปองของฉัน</a>
                    <a href="{{ route('account.addresses') }}" class="user-dropdown-item" @click="open=false"><i class="bi bi-geo-alt"></i> ที่อยู่จัดส่ง</a>
                    <a href="{{ route('account.profile') }}"   class="user-dropdown-item" @click="open=false"><i class="bi bi-person-gear"></i> แก้ไขโปรไฟล์</a>
                    <hr class="user-dropdown-sep">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="user-dropdown-item user-dropdown-logout">
                            <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
                        </button>
                    </form>
                </div>
            </div>
            @else
            <a href="{{ route('login') }}" class="nav-login-btn">
                <i class="bi bi-person-circle"></i> เข้าสู่ระบบ
            </a>
            @endif

            {{-- Hamburger (mobile) --}}
            <button class="hamburger" @click="open = true" aria-label="เมนู">
                <i class="bi bi-list"></i>
            </button>
        </div>
    </div>

    {{-- Mobile Overlay --}}
    <div class="mobile-overlay" :class="{ open: open }" @click="open = false"></div>

    {{-- Mobile Drawer --}}
    <div class="mobile-drawer" :class="{ open: open }">
        <div class="mobile-drawer-header">
            <div style="display:flex;align-items:center;gap:10px;">
                <img src="{{ asset('images/kgm_logo.png') }}" alt="KGM" style="height:36px;width:auto;object-fit:contain;">
                <span style="color:white;font-weight:700;font-size:15px;"></span>
            </div>
            <button class="mobile-drawer-close" @click="open = false"><i class="bi bi-x-lg"></i></button>
        </div>

        <a href="{{ route('home') }}"      class="mobile-nav-link {{ request()->routeIs('home')      ? 'active' : '' }}" @click="open=false"><i class="bi bi-house"></i> หน้าแรก</a>
        <a href="{{ route('shop') }}" class="mobile-nav-link {{ request()->routeIs('shop') ? 'active' : '' }}" @click="open=false"><i class="bi bi-bag"></i> สินค้า</a>

        {{-- ประเภทสินค้า accordion (mobile) --}}
        @if($navProductTypes->isNotEmpty())
        <div x-data="{ typeOpen: false }">
            <button class="mobile-nav-link w-full {{ request()->has('type') ? 'active' : '' }}" @click="typeOpen = !typeOpen" style="background:none;border:none;cursor:pointer;">
                <i class="bi bi-tag"></i>
                <span style="flex:1;text-align:left;">ประเภท</span>
                <i class="bi" :class="typeOpen ? 'bi-chevron-up' : 'bi-chevron-down'" style="font-size:11px;width:auto;"></i>
            </button>
            <div x-show="typeOpen" x-cloak style="padding:0 0 4px 0;">
                <a href="{{ route('shop') }}" class="mobile-nav-sub" @click="open=false"><i class="bi bi-grid-3x3-gap"></i> ทั้งหมด</a>
                @foreach($navProductTypes as $pt)
                <a href="{{ route('shop') }}?type={{ $pt->slug }}" class="mobile-nav-sub" @click="open=false">
                    <i class="bi bi-tag"></i> {{ $pt->name }}
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- หมวดหมู่ accordion (mobile) --}}
        @if($navCategories->isNotEmpty())
        <div x-data="{ catOpen: false }">
            <button class="mobile-nav-link w-full {{ request()->routeIs('shop.category') ? 'active' : '' }}" @click="catOpen = !catOpen" style="background:none;border:none;cursor:pointer;">
                <i class="bi bi-folder2"></i>
                <span style="flex:1;text-align:left;">หมวดหมู่</span>
                <i class="bi" :class="catOpen ? 'bi-chevron-up' : 'bi-chevron-down'" style="font-size:11px;width:auto;"></i>
            </button>
            <div x-show="catOpen" x-cloak style="padding:0 0 4px 0;">
                @foreach($navCategories as $cat)
                <a href="{{ route('shop.category', $cat->slug) }}" class="mobile-nav-sub mobile-nav-sub-parent" @click="open=false">
                    <i class="bi bi-folder2"></i> {{ $cat->name }}
                </a>
                @foreach($cat->children as $child)
                <a href="{{ route('shop.category', $child->slug) }}" class="mobile-nav-sub mobile-nav-sub-child" @click="open=false">
                    <i class="bi bi-dash"></i> {{ $child->name }}
                </a>
                @endforeach
                @endforeach
            </div>
        </div>
        @endif
        <a href="{{ route('coupons.index') }}" class="mobile-nav-link {{ request()->routeIs('coupons*')  ? 'active' : '' }}" @click="open=false"><i class="bi bi-ticket-perforated"></i> คูปอง</a>
        <a href="{{ route('about') }}"         class="mobile-nav-link {{ request()->routeIs('about')      ? 'active' : '' }}" @click="open=false"><i class="bi bi-building"></i> เกี่ยวกับเรา</a>
        <a href="{{ route('services') }}"  class="mobile-nav-link {{ request()->routeIs('services')  ? 'active' : '' }}" @click="open=false"><i class="bi bi-tools"></i> บริการ</a>
        <a href="{{ route('news') }}"      class="mobile-nav-link {{ request()->routeIs('news*')     ? 'active' : '' }}" @click="open=false"><i class="bi bi-newspaper"></i> ข่าวสาร</a>
        <a href="{{ route('careers') }}"   class="mobile-nav-link {{ request()->routeIs('careers*')  ? 'active' : '' }}" @click="open=false"><i class="bi bi-briefcase"></i> ร่วมงานกับเรา</a>
        <a href="{{ route('contact') }}"   class="mobile-nav-link {{ request()->routeIs('contact')   ? 'active' : '' }}" @click="open=false"><i class="bi bi-chat-left-text"></i> ติดต่อเรา</a>

        <div class="mobile-drawer-footer">
            <a href="{{ route('quote') }}" class="btn btn-gold w-full" style="border-radius:14px;" @click="open=false">
                <i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคา
            </a>
            @if(auth('customer')->check())
            <a href="{{ route('account.index') }}" class="btn btn-light w-full" style="border-radius:14px;" @click="open=false">
                <i class="bi bi-person-circle"></i> บัญชีของฉัน
            </a>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary w-full" style="border-radius:14px;" @click="open=false">
                <i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ
            </a>
            <a href="{{ route('register') }}" class="btn btn-outline w-full" style="border-radius:14px;border-color:rgba(255,255,255,0.3);color:rgba(255,255,255,0.85);" @click="open=false">
                <i class="bi bi-person-plus"></i> สมัครสมาชิก
            </a>
            @endif
        </div>
    </div>
</nav>

{{-- ══ Alerts ══ --}}
<div class="container">
    @if(session('success'))
        <div class="alert alert-success" style="margin-top:14px;"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" style="margin-top:14px;"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
    @endif
</div>

@yield('content')

{{-- ══ Footer ══ --}}
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            {{-- Brand --}}
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                    <img src="{{ asset('images/kgm_logo.png') }}" alt="KGM" style="width:70px;height:70px;object-fit:contain;">
                    <div style="color:white;font-weight:700;font-size:15px;">กิจเจริญการ์เมนท์<span style="display:block;color:rgba(255,255,255,0.45);font-size:11px;font-weight:400;">(1993) จำกัด</span></div>
                </div>
                <p style="font-size:13px;line-height:1.8;color:rgba(255,255,255,0.55);margin:0 0 4px;">โรงงานผลิตเครื่องแบบนักเรียนและยูนิฟอร์มคุณภาพสูง ประสบการณ์กว่า 30 ปี</p>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-line"></i></a>
                </div>
            </div>

            {{-- Quick links --}}
            <div>
                <h5>เมนูด่วน</h5>
                <div class="footer-links">
                    <a href="{{ route('about') }}"><i class="bi bi-chevron-right" style="font-size:10px;"></i> เกี่ยวกับบริษัท</a>
                    <a href="{{ route('services') }}"><i class="bi bi-chevron-right" style="font-size:10px;"></i> บริการของเรา</a>
                    <a href="{{ route('shop') }}"><i class="bi bi-chevron-right" style="font-size:10px;"></i> ร้านค้า</a>
                    <a href="{{ route('showrooms') }}"><i class="bi bi-chevron-right" style="font-size:10px;"></i> สาขา/โชว์รูม</a>
                    <a href="{{ route('careers') }}"><i class="bi bi-chevron-right" style="font-size:10px;"></i> ร่วมงานกับเรา</a>
                    <a href="{{ route('dealer') }}"><i class="bi bi-chevron-right" style="font-size:10px;"></i> สมัครตัวแทน</a>
                    <a href="{{ route('quote') }}"><i class="bi bi-chevron-right" style="font-size:10px;"></i> ขอใบเสนอราคา</a>
                </div>
            </div>

            {{-- Policy + Payment --}}
            <div>
                <h5>นโยบาย</h5>
                <div class="footer-links" style="margin-bottom:20px;">
                    <a href="{{ route('privacy-policy') }}"><i class="bi bi-chevron-right" style="font-size:10px;"></i> นโยบายความเป็นส่วนตัว</a>
                    <a href="{{ route('terms') }}"><i class="bi bi-chevron-right" style="font-size:10px;"></i> ข้อกำหนดการใช้งาน</a>
                    <a href="{{ route('cookie-policy') }}"><i class="bi bi-chevron-right" style="font-size:10px;"></i> นโยบายคุกกี้</a>
                </div>
                <h5>ช่องทางชำระเงิน</h5>
                <div style="display:flex;gap:8px;flex-wrap:wrap;">
                    <span style="background:rgba(255,255,255,0.1);border-radius:8px;padding:5px 10px;font-size:12px;"><i class="bi bi-bank"></i> โอนเงิน</span>
                    <span style="background:rgba(255,255,255,0.1);border-radius:8px;padding:5px 10px;font-size:12px;"><i class="bi bi-qr-code"></i> PromptPay</span>
                </div>
            </div>

            {{-- Contact --}}
            <div>
                <h5>ติดต่อเรา</h5>
                <div style="display:flex;flex-direction:column;gap:10px;font-size:14px;">
                    <div><i class="bi bi-geo-alt" style="color:var(--kgm-gold-300);margin-right:6px;"></i>123 ถ.เจริญกรุง แขวงบางรัก กรุงเทพฯ 10500</div>
                    <div><i class="bi bi-telephone" style="color:var(--kgm-gold-300);margin-right:6px;"></i><a href="tel:020001234">02-000-1234</a></div>
                    <div><i class="bi bi-envelope" style="color:var(--kgm-gold-300);margin-right:6px;"></i><a href="mailto:info@kgm1993.com">info@kgm1993.com</a></div>
                    <div><i class="bi bi-clock" style="color:var(--kgm-gold-300);margin-right:6px;"></i>จ-ศ 08:00–17:30 น.</div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">© {{ date('Y') }} กิจเจริญการ์เมนท์ (1993) จำกัด — สงวนลิขสิทธิ์ทุกประการ</div>
    </div>
</footer>

{{-- ══ Cookie Consent (PDPA) ══ --}}
@if(!session('cookie_consent') && !request()->cookie('cookie_consent'))
<div x-data="cookieConsent()" x-cloak>

    {{-- Banner --}}
    <div id="cookie-banner" x-show="showBanner"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="cb-icon"><i class="bi bi-shield-check"></i></div>
        <div class="cb-body">
            <div class="cb-title">เว็บไซต์นี้ใช้คุกกี้</div>
            <p class="cb-text">
                เราใช้คุกกี้เพื่อให้เว็บไซต์ทำงานได้อย่างถูกต้อง รวมถึงวิเคราะห์การใช้งานและนำเสนอเนื้อหาที่เกี่ยวข้อง
                คุณสามารถเลือกการตั้งค่าได้ตามต้องการ — <a href="{{ route('cookie-policy') }}">อ่านนโยบายคุกกี้</a>
            </p>
        </div>
        <div class="cb-actions">
            <button class="btn btn-light" @click="reject()">ปฏิเสธที่ไม่จำเป็น</button>
            <button class="btn btn-outline" @click="showModal = true">
                <i class="bi bi-sliders"></i> จัดการตั้งค่า
            </button>
            <button class="btn btn-primary" @click="acceptAll()">
                <i class="bi bi-check-lg"></i> ยอมรับทั้งหมด
            </button>
        </div>
    </div>

    {{-- Settings Modal --}}
    <div id="cookie-modal-overlay" x-show="showModal"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click.self="showModal = false">
        <div id="cookie-modal"
             x-transition:enter="transition ease-out duration-250"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="cm-header">
                <div class="cm-header-icon"><i class="bi bi-shield-lock-fill"></i></div>
                <div class="cm-header-text">
                    <h3>ตั้งค่าความเป็นส่วนตัว</h3>
                    <p>เลือกประเภทคุกกี้ที่คุณยินยอม</p>
                </div>
                <button class="cm-close" @click="showModal = false"><i class="bi bi-x-lg"></i></button>
            </div>

            <div class="cm-body">
                {{-- Required --}}
                <div class="cm-row active">
                    <div class="cm-row-icon icon-required"><i class="bi bi-lock-fill"></i></div>
                    <div class="cm-row-body">
                        <div style="display:flex;align-items:center;gap:8px;margin-bottom:3px;">
                            <h4 style="margin:0;">คุกกี้ที่จำเป็น</h4>
                            <span class="cm-row-badge">จำเป็น</span>
                        </div>
                        <p>จำเป็นสำหรับการทำงานหลักของเว็บไซต์ เช่น ตะกร้าสินค้า การล็อกอิน และความปลอดภัย ไม่สามารถปิดได้</p>
                    </div>
                    <label class="cm-toggle">
                        <input type="checkbox" checked disabled>
                        <span class="cm-toggle-slider"></span>
                    </label>
                </div>

                {{-- Analytics --}}
                <div class="cm-row" :class="{ active: prefs.analytics }">
                    <div class="cm-row-icon icon-analytics"><i class="bi bi-bar-chart-fill"></i></div>
                    <div class="cm-row-body">
                        <h4>คุกกี้วิเคราะห์</h4>
                        <p>ช่วยให้เราเข้าใจวิธีที่ผู้เยี่ยมชมใช้งานเว็บไซต์ เพื่อปรับปรุงประสบการณ์และเนื้อหาให้ดีขึ้น</p>
                    </div>
                    <label class="cm-toggle">
                        <input type="checkbox" x-model="prefs.analytics">
                        <span class="cm-toggle-slider"></span>
                    </label>
                </div>

                {{-- Marketing --}}
                <div class="cm-row" :class="{ active: prefs.marketing }">
                    <div class="cm-row-icon icon-marketing"><i class="bi bi-megaphone-fill"></i></div>
                    <div class="cm-row-body">
                        <h4>คุกกี้การตลาด</h4>
                        <p>ใช้เพื่อแสดงโฆษณาและเนื้อหาที่ตรงกับความสนใจของคุณ รวมถึงวัดประสิทธิภาพแคมเปญ</p>
                    </div>
                    <label class="cm-toggle">
                        <input type="checkbox" x-model="prefs.marketing">
                        <span class="cm-toggle-slider"></span>
                    </label>
                </div>
            </div>

            <div class="cm-footer">
                <button class="btn btn-light" @click="reject()">ปฏิเสธที่ไม่จำเป็น</button>
                <button class="btn btn-outline" @click="savePreferences()">บันทึกการตั้งค่า</button>
                <button class="btn btn-primary" @click="acceptAll()">
                    <i class="bi bi-check-lg"></i> ยอมรับทั้งหมด
                </button>
            </div>
        </div>
    </div>

</div>
@endif

<script>
document.addEventListener('error', function(e) {
    if (e.target.tagName === 'IMG') {
        var fallback = '/images/logo.png';
        if (e.target.src !== window.location.origin + fallback && !e.target.dataset.fallback) {
            e.target.dataset.fallback = '1';
            e.target.src = fallback;
        }
    }
}, true);

function cookieConsent() {
    return {
        showBanner: true,
        showModal: false,
        prefs: { analytics: false, marketing: false },
        acceptAll() {
            this.prefs.analytics = true;
            this.prefs.marketing = true;
            this.save();
        },
        reject() {
            this.prefs.analytics = false;
            this.prefs.marketing = false;
            this.save();
        },
        savePreferences() {
            this.showModal = false;
            this.save();
        },
        save() {
            this.showBanner = false;
            this.showModal = false;
            fetch('/consent', {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Content-Type': 'application/json' },
                body: JSON.stringify(this.prefs)
            });
        }
    };
}

function mobileNav() {
    return {
        open: false,
        init() {
            document.addEventListener('keydown', e => { if (e.key === 'Escape') this.open = false; });
        }
    };
}

function updateCartBadge(count) {
    const b = document.getElementById('cart-count');
    if (!b) return;
    b.textContent = count;
    b.style.display = count > 0 ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    fetch('/cart/count', { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => updateCartBadge(data.count))
        .catch(() => {});
});

function addToCart(productId, variantId, qty = 1) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity: qty })
    })
    .then(r => r.json())
    .then(data => {
        if (data.count !== undefined) updateCartBadge(data.count);
        const n = document.createElement('div');
        n.className = 'alert alert-success';
        n.style.cssText = 'position:fixed;top:76px;right:16px;z-index:9999;padding:10px 18px;min-width:180px;max-width:90vw;border-radius:14px;box-shadow:0 4px 16px rgba(0,0,0,0.15);';
        n.innerHTML = '<i class="bi bi-cart-check-fill"></i> ' + (data.message || 'เพิ่มลงตะกร้าแล้ว');
        document.body.appendChild(n);
        setTimeout(() => n.remove(), 2500);
    })
    .catch(() => {});
}

function toggleWishlist(btn) {
    const url = btn.dataset.url;
    btn.disabled = true;
    fetch(url, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        const icon = btn.querySelector('i');
        if (data.in_wishlist) {
            btn.classList.add('active');
            icon.className = 'bi bi-heart-fill';
        } else {
            btn.classList.remove('active');
            icon.className = 'bi bi-heart';
        }
        const n = document.createElement('div');
        n.className = 'alert alert-success';
        n.style.cssText = 'position:fixed;top:76px;right:16px;z-index:9999;padding:10px 18px;min-width:180px;max-width:90vw;border-radius:14px;box-shadow:0 4px 16px rgba(0,0,0,0.15);';
        n.innerHTML = '<i class="bi bi-heart-fill"></i> ' + (data.message || 'อัปเดตรายการโปรดแล้ว');
        document.body.appendChild(n);
        setTimeout(() => n.remove(), 2000);
    })
    .catch(() => {})
    .finally(() => { btn.disabled = false; });
}
</script>
@stack('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/th.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('input[type="date"]').forEach(function (el) {
        flatpickr(el, {
            locale: 'th',
            dateFormat: 'Y-m-d',
            altInput: true,
            altFormat: 'd/m/Y',
            allowInput: true,
        });
    });
    document.querySelectorAll('input[type="datetime-local"]').forEach(function (el) {
        flatpickr(el, {
            locale: 'th',
            dateFormat: 'Y-m-d H:i',
            altInput: true,
            altFormat: 'd/m/Y H:i',
            enableTime: true,
            time_24hr: true,
            allowInput: true,
        });
    });
    document.querySelectorAll('input[type="time"]').forEach(function (el) {
        flatpickr(el, {
            locale: 'th',
            dateFormat: 'H:i',
            enableTime: true,
            noCalendar: true,
            time_24hr: true,
            allowInput: true,
        });
    });
});
</script>
{{-- ══ Landing Page Popup ══ --}}
@if(!empty($activeLandingPage))
<div id="lp-overlay"
     style="display:none;position:fixed;inset:0;z-index:9998;background:rgba(0,0,0,0.72);backdrop-filter:blur(4px);-webkit-backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:16px;">
    <div id="lp-modal" style="animation:lpFadeIn .35s ease;display:inline-flex;flex-direction:column;align-items:center;gap:16px;">
        <img src="{{ asset('storage/'.$activeLandingPage->image_path) }}"
             alt="ยินดีต้อนรับ"
             style="display:block;max-width:min(680px,90vw);max-height:72vh;object-fit:contain;border-radius:16px;box-shadow:0 24px 64px rgba(0,0,0,0.5);">
        <button onclick="closeLandingPage()"
                style="background:linear-gradient(135deg,#c9a84c,#f0c040);color:#1a3a2a;font-weight:700;font-size:15px;border:none;padding:12px 40px;border-radius:40px;cursor:pointer;font-family:inherit;"
        >เข้าสู่หน้าหลัก</button>
    </div>
</div>
<style>
@keyframes lpFadeIn { from { opacity:0; transform:scale(.94); } to { opacity:1; transform:scale(1); } }
</style>
<script>
(function(){
    var KEY = 'lp_closed_at';
    var HOURS = 24;
    var last = localStorage.getItem(KEY);
    var show = !last || (Date.now() - parseInt(last)) > HOURS * 3600 * 1000;
    if (show) {
        var el = document.getElementById('lp-overlay');
        if (el) { el.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
    }
})();
function closeLandingPage() {
    localStorage.setItem('lp_closed_at', Date.now());
    var el = document.getElementById('lp-overlay');
    if (el) { el.style.opacity = '0'; el.style.transition = 'opacity .25s'; setTimeout(function(){ el.style.display = 'none'; }, 250); }
    document.body.style.overflow = '';
}
document.addEventListener('keydown', function(e){ if(e.key === 'Escape') closeLandingPage(); });
</script>
@endif

<script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>

{{-- ── Floating Contact ── --}}
<style>
.fc-wrap { position: fixed; bottom: 28px; right: 24px; z-index: 1050; display: flex; flex-direction: column; align-items: flex-end; gap: 10px; }
.fc-item { display: flex; align-items: center; gap: 10px; cursor: pointer; text-decoration: none; transition: transform .22s cubic-bezier(.34,1.56,.64,1); }
.fc-item:hover { transform: translateX(-6px) scale(1.06); }
.fc-icon { width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 22px; color: #fff; box-shadow: 0 4px 14px rgba(0,0,0,.22); flex-shrink: 0; transition: box-shadow .2s; }
.fc-item:hover .fc-icon { box-shadow: 0 8px 24px rgba(0,0,0,.32); }
.fc-main { width: 58px; height: 58px; border-radius: 50%; background: transparent; border: none; padding: 0; overflow: hidden; box-shadow: 0 4px 18px rgba(0,0,0,.22); cursor: pointer; transition: transform .35s cubic-bezier(.34,1.56,.64,1), box-shadow .2s; align-self: flex-end; }
.fc-main:hover { transform: scale(1.12) translateY(-2px); box-shadow: 0 8px 28px rgba(0,0,0,.28); }
.fc-main.is-open { transform: rotate(45deg) scale(1.08); }

@keyframes fc-pop {
    0%   { opacity: 0; transform: translateY(18px) scale(.65); }
    70%  { transform: translateY(-4px) scale(1.06); }
    100% { opacity: 1; transform: translateY(0) scale(1); }
}
.fc-anim-1 { animation: fc-pop .3s cubic-bezier(.34,1.56,.64,1) both; }
.fc-anim-2 { animation: fc-pop .3s cubic-bezier(.34,1.56,.64,1) .08s both; }
.fc-anim-3 { animation: fc-pop .3s cubic-bezier(.34,1.56,.64,1) .16s both; }
.fc-menu { display: flex; flex-direction: column; gap: 10px; align-items: flex-end; margin-bottom: 6px; }
</style>

<div x-data="{ open: false }" class="fc-wrap">
    {{-- 3 เมนู --}}
    <div class="fc-menu"
         x-show="open"
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-120"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         x-cloak>

        <a href="tel:0851100010" class="fc-item fc-anim-3">
            <div class="fc-icon" style="background:#34a853;">
                <i class="bi bi-telephone-fill"></i>
            </div>
        </a>

        <a href="https://line.me/ti/p/~@kgmuniform" target="_blank" class="fc-item fc-anim-2">
            <div class="fc-icon" style="background:#06c755;">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 24 24"><path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.281.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/></svg>
            </div>
        </a>

        <a href="https://m.me/kgmuniform" target="_blank" class="fc-item fc-anim-1">
            <div class="fc-icon" style="background:linear-gradient(135deg,#0099ff,#a033ff);">
                <i class="bi bi-messenger"></i>
            </div>
        </a>
    </div>

    {{-- ปุ่มหลัก --}}
    <button class="fc-main" :class="{ 'is-open': open }" @click="open = !open">
        <img src="{{ asset('images/contact.png') }}" alt="ติดต่อเรา" style="width:100%;height:100%;object-fit:cover;display:block;">
    </button>
</div>

</body>
</html>
