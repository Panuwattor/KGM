@push('styles')
<style>
.acc-wrap { padding: 32px 0 64px; }
.acc-layout { display: grid; grid-template-columns: 240px 1fr; gap: 28px; align-items: start; }

/* Mobile nav — hidden on desktop, spans full grid width */
.acc-mobile-nav { grid-column: 1 / -1; display: none; background: white; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,.06); overflow-x: auto; -webkit-overflow-scrolling: touch; scrollbar-width: none; }
.acc-mobile-nav::-webkit-scrollbar { display: none; }
.acc-mobile-nav-inner { display: flex; padding: 10px; gap: 6px; min-width: max-content; }
.acc-mobile-nav-link { display: flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 10px; font-size: 13px; color: #555; text-decoration: none; white-space: nowrap; transition: all .2s; }
.acc-mobile-nav-link.active { color: var(--kgm-green-700); background: var(--kgm-green-100); font-weight: 700; }
.acc-mobile-nav-link:hover:not(.active) { background: #f5f7f5; }

/* Desktop sidebar */
.acc-profile-card { background: white; border-radius: 20px; padding: 24px; box-shadow: 0 2px 10px rgba(0,0,0,.06); margin-bottom: 16px; text-align: center; }
.acc-profile-avatar { width: 64px; height: 64px; background: linear-gradient(135deg, var(--kgm-green-600), var(--kgm-green-400)); border-radius: 999px; display: flex; align-items: center; justify-content: center; font-size: 26px; color: white; margin: 0 auto 10px; }
.acc-profile-name { font-weight: 800; font-size: 15px; }
.acc-profile-sub { font-size: 12px; color: #888; margin-top: 2px; }
.acc-sidebar-nav { background: white; border-radius: 20px; padding: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
.acc-nav-link { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 12px; font-size: 14px; color: #555; text-decoration: none; transition: all .2s; }
.acc-nav-link.active { color: var(--kgm-green-700); background: var(--kgm-green-100); font-weight: 700; }
.acc-nav-link:hover:not(.active) { background: #f5f7f5; }
.acc-nav-logout { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 12px; font-size: 14px; color: #e74c3c; background: none; border: none; cursor: pointer; width: 100%; font-family: inherit; margin-top: 8px; }

/* Stats */
.acc-stats-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 20px; }
.acc-stat { text-align: center; border-radius: 16px; padding: 20px 12px; }
.acc-stat-num { font-size: 28px; font-weight: 800; }
.acc-stat-label { font-size: 13px; color: #888; margin-top: 4px; }

/* Card */
.acc-card { background: white; border-radius: 20px; padding: 28px; box-shadow: 0 2px 10px rgba(0,0,0,.06); }

/* Order rows */
.acc-order-row { display: flex; align-items: center; gap: 14px; padding: 12px 0; border-bottom: 1px solid #f5f7f5; }
.acc-order-row:last-child { border-bottom: none; }
.acc-order-icon { width: 44px; height: 44px; background: var(--kgm-green-100); border-radius: 12px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; }
.acc-order-info { flex: 1; min-width: 0; }
.acc-order-num { font-weight: 700; font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.acc-order-date { font-size: 12px; color: #888; }
.acc-order-amount { font-weight: 700; color: var(--kgm-green-700); white-space: nowrap; }

@media (max-width: 768px) {
    .acc-wrap { padding: 16px 16px 48px; }
    .acc-layout { grid-template-columns: 1fr; gap: 0; }
    .acc-mobile-nav { display: block; margin-bottom: 16px; }
    .acc-sidebar { display: none; }
    .acc-stats-grid { gap: 10px; }
    .acc-stat { padding: 14px 8px; }
    .acc-stat-num { font-size: 22px; }
    .acc-card { padding: 20px 16px; border-radius: 16px; }
    .acc-order-amount { display: none; }
}
@media (max-width: 400px) {
    .acc-stat { padding: 12px 4px; }
    .acc-stat-num { font-size: 20px; }
    .acc-stat-label { font-size: 11px; }
}
</style>
@endpush

@php $authUser = auth('customer')->user(); @endphp

{{-- Mobile nav tabs --}}
<div class="acc-mobile-nav" id="accMobileNav">
    <div class="acc-mobile-nav-inner">
        @foreach([
            ['account.index',    'bi-speedometer2',      'ภาพรวม'],
            ['account.profile',  'bi-person',            'ข้อมูลส่วนตัว'],
            ['account.orders',   'bi-receipt',           'คำสั่งซื้อ'],
            ['account.addresses','bi-geo-alt',           'ที่อยู่'],
            ['account.wishlist', 'bi-heart',             'รายการโปรด'],
            ['account.quotes',   'bi-file-earmark-text', 'ใบเสนอราคา'],
        ] as [$r, $ic, $lb])
        <a href="{{ route($r) }}" class="acc-mobile-nav-link {{ request()->routeIs($r) ? 'active' : '' }}">
            <i class="bi {{ $ic }}"></i> {{ $lb }}
        </a>
        @endforeach
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var nav = document.getElementById('accMobileNav');
    var active = nav && nav.querySelector('.acc-mobile-nav-link.active');
    if (active) {
        var navCenter = nav.offsetWidth / 2;
        var itemCenter = active.offsetLeft + active.offsetWidth / 2;
        nav.scrollLeft = itemCenter - navCenter;
    }
});
</script>

{{-- Desktop sidebar --}}
<div class="acc-sidebar">
    <div class="acc-profile-card">
        <div class="acc-profile-avatar"><i class="bi bi-person-fill"></i></div>
        <div class="acc-profile-name">{{ $authUser->name }}</div>
        @if($authUser->email)
        <div class="acc-profile-sub">{{ $authUser->email }}</div>
        @else
        <div class="acc-profile-sub">{{ $authUser->phone }}</div>
        @endif
    </div>
    <nav class="acc-sidebar-nav">
        @foreach([
            ['account.index',    'bi-speedometer2',      'ภาพรวม'],
            ['account.profile',  'bi-person',            'ข้อมูลส่วนตัว'],
            ['account.orders',   'bi-receipt',           'คำสั่งซื้อ'],
            ['account.addresses','bi-geo-alt',           'ที่อยู่จัดส่ง'],
            ['account.wishlist', 'bi-heart',             'รายการโปรด'],
            ['account.quotes',   'bi-file-earmark-text', 'ใบเสนอราคา'],
        ] as [$r, $ic, $lb])
        <a href="{{ route($r) }}" class="acc-nav-link {{ request()->routeIs($r) ? 'active' : '' }}">
            <i class="bi {{ $ic }}" style="font-size:16px;"></i> {{ $lb }}
        </a>
        @endforeach
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="acc-nav-logout">
                <i class="bi bi-box-arrow-right" style="font-size:16px;"></i> ออกจากระบบ
            </button>
        </form>
    </nav>
</div>
