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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
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
            <a href="{{ route('about') }}"     class="nav-link {{ request()->routeIs('about')     ? 'active' : '' }}">เกี่ยวกับเรา</a>
            <a href="{{ route('services') }}"  class="nav-link {{ request()->routeIs('services')  ? 'active' : '' }}">บริการ</a>
            <a href="{{ route('shop') }}"      class="nav-link {{ request()->routeIs('shop*')     ? 'active' : '' }}">สินค้า</a>
            <a href="{{ route('showrooms') }}" class="nav-link {{ request()->routeIs('showrooms') ? 'active' : '' }}">สาขา</a>
            <a href="{{ route('news') }}"      class="nav-link {{ request()->routeIs('news*')     ? 'active' : '' }}">ข่าวสาร</a>
            <a href="{{ route('contact') }}"   class="nav-link {{ request()->routeIs('contact')   ? 'active' : '' }}">ติดต่อ</a>
            <a href="{{ route('quote') }}"     class="nav-link nav-link-quote">
                <i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคา
            </a>
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
                    <div class="user-avatar">{{ strtoupper(substr(auth('customer')->user()->name, 0, 1)) }}</div>
                </button>
                <div class="user-dropdown-menu" x-show="open" x-transition x-cloak>
                    <div class="user-dropdown-header">
                        <div class="user-avatar user-avatar-lg">{{ strtoupper(substr(auth('customer')->user()->name, 0, 1)) }}</div>
                        <div>
                            <div style="font-weight:700;font-size:14px;color:#2c3e50;line-height:1.3;">{{ auth('customer')->user()->name }}</div>
                            <div style="font-size:11px;color:#999;">{{ auth('customer')->user()->email }}</div>
                        </div>
                    </div>
                    <a href="{{ route('account.index') }}"     class="user-dropdown-item" @click="open=false"><i class="bi bi-speedometer2"></i> ภาพรวมบัญชี</a>
                    <a href="{{ route('account.orders') }}"    class="user-dropdown-item" @click="open=false"><i class="bi bi-receipt"></i> คำสั่งซื้อของฉัน</a>
                    <a href="{{ route('account.quotes') }}"    class="user-dropdown-item" @click="open=false"><i class="bi bi-file-earmark-text"></i> ใบเสนอราคา</a>
                    <a href="{{ route('account.wishlist') }}"  class="user-dropdown-item" @click="open=false"><i class="bi bi-heart"></i> รายการโปรด</a>
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
            <a href="{{ route('login') }}" class="nav-btn" title="เข้าสู่ระบบ"><i class="bi bi-person-circle" style="font-size:20px;"></i></a>
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
                <span style="color:white;font-weight:700;font-size:15px;">KGM</span>
            </div>
            <button class="mobile-drawer-close" @click="open = false"><i class="bi bi-x-lg"></i></button>
        </div>

        <a href="{{ route('home') }}"      class="mobile-nav-link {{ request()->routeIs('home')      ? 'active' : '' }}" @click="open=false"><i class="bi bi-house"></i> หน้าแรก</a>
        <a href="{{ route('shop') }}"      class="mobile-nav-link {{ request()->routeIs('shop*')     ? 'active' : '' }}" @click="open=false"><i class="bi bi-bag"></i> สินค้า</a>
        <a href="{{ route('about') }}"     class="mobile-nav-link {{ request()->routeIs('about')     ? 'active' : '' }}" @click="open=false"><i class="bi bi-building"></i> เกี่ยวกับเรา</a>
        <a href="{{ route('services') }}"  class="mobile-nav-link {{ request()->routeIs('services')  ? 'active' : '' }}" @click="open=false"><i class="bi bi-tools"></i> บริการ</a>
        <a href="{{ route('showrooms') }}" class="mobile-nav-link {{ request()->routeIs('showrooms') ? 'active' : '' }}" @click="open=false"><i class="bi bi-shop"></i> สาขา/โชว์รูม</a>
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
                    <div style="width:46px;height:46px;background:linear-gradient(135deg,var(--kgm-gold-500),var(--kgm-gold-300));border-radius:12px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:20px;color:var(--kgm-green-900);">K</div>
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
@if(!session('cookie_consent'))
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

function addToCart(productId, variantId, qty = 1) {
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ product_id: productId, variant_id: variantId, quantity: qty })
    })
    .then(r => r.json())
    .then(data => {
        updateCartBadge(data.count);
        const n = document.createElement('div');
        n.className = 'alert alert-success';
        n.style.cssText = 'position:fixed;top:76px;right:16px;z-index:9999;padding:10px 18px;min-width:180px;max-width:90vw;border-radius:14px;box-shadow:0 4px 16px rgba(0,0,0,0.15);';
        n.innerHTML = '<i class="bi bi-cart-check-fill"></i> ' + (data.message || 'เพิ่มลงตะกร้าแล้ว');
        document.body.appendChild(n);
        setTimeout(() => n.remove(), 2500);
    });
}
</script>
@stack('scripts')
</body>
</html>
