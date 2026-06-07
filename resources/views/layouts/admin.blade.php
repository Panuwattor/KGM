<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'แอดมิน') | KGM Admin</title>
    <link rel="icon" type="image/png" href="/images/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>
        :root {
            --g900: #0d2818; --g800: #1a3a2a; --g700: #1e4d35; --g600: #2d6a4f;
            --g500: #40916c; --g400: #52b788; --g100: #d8f3dc;
            --gold: #c9a84c; --gold-l: #f0c040; --gold-d: #8b6914;
            --sidebar-w: 260px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Sarabun', sans-serif; background: #f0f4f0; color: #2c3e50; display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar { width: var(--sidebar-w); background: linear-gradient(180deg, var(--g900) 0%, var(--g800) 100%); height: 100vh; position: fixed; left: 0; top: 0; z-index: 100; overflow-y: auto; transition: transform 0.3s; }
        .sidebar-logo { padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); }
        .sidebar-logo-inner { display: flex; align-items: center; gap: 10px; }
        .logo-icon { width: 44px; height: 44px; background: linear-gradient(135deg, var(--gold), var(--gold-l)); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-weight: 900; font-size: 20px; color: var(--g900); }
        .logo-text { color: white; font-weight: 700; font-size: 15px; line-height: 1.2; }
        .logo-text span { color: rgba(255,255,255,0.4); font-size: 11px; font-weight: 400; display: block; }
        .sidebar-nav { padding: 16px 12px; }
        .nav-section { margin-bottom: 24px; }
        .nav-section-title { font-size: 10px; text-transform: uppercase; letter-spacing: 1.5px; color: rgba(255,255,255,0.3); padding: 0 10px; margin-bottom: 6px; font-weight: 600; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; border-radius: 12px; color: rgba(255,255,255,0.7); font-size: 14px; cursor: pointer; transition: all 0.2s; text-decoration: none; position: relative; }
        .nav-item i { font-size: 18px; min-width: 22px; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: white; }
        .nav-item.active { background: linear-gradient(135deg, var(--g600), var(--g500)); color: white; box-shadow: 0 4px 16px rgba(40,144,108,0.3); }
        .nav-item .badge-dot { position: absolute; right: 12px; background: #e74c3c; color: white; border-radius: 999px; padding: 1px 6px; font-size: 10px; font-weight: 700; }
        .nav-item .badge-gold { position: absolute; right: 12px; background: var(--gold); color: var(--g900); border-radius: 999px; padding: 1px 6px; font-size: 10px; font-weight: 700; }

        /* Main */
        .main-wrapper { margin-left: var(--sidebar-w); flex: 1; min-height: 100vh; display: flex; flex-direction: column; }

        /* Topbar */
        .admin-topbar { background: white; padding: 0 28px; height: 64px; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 0 #e8ecef; position: sticky; top: 0; z-index: 50; }
        .topbar-title { font-size: 20px; font-weight: 700; color: var(--g800); }
        .topbar-actions { display: flex; align-items: center; gap: 12px; }
        .topbar-btn { width: 38px; height: 38px; background: #f5f7f5; border-radius: 999px; display: flex; align-items: center; justify-content: center; cursor: pointer; border: none; color: #555; font-size: 16px; transition: all 0.2s; position: relative; }
        .topbar-btn:hover { background: var(--g100); color: var(--g600); }
        .topbar-notif { background: #e74c3c; color: white; border-radius: 999px; padding: 1px 5px; font-size: 10px; position: absolute; top: 2px; right: 2px; font-weight: 700; }
        .admin-avatar { width: 38px; height: 38px; background: linear-gradient(135deg, var(--g600), var(--g500)); border-radius: 999px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 15px; cursor: pointer; }

        /* Content */
        .admin-content { padding: 28px; flex: 1; }
        .page-header { margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; }
        .page-title { font-size: 24px; font-weight: 700; color: var(--g800); }
        .page-subtitle { font-size: 13px; color: #888; margin-top: 2px; }

        /* Stats Cards */
        .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 28px; }
        .stat-card { background: white; border-radius: 20px; padding: 22px; position: relative; overflow: hidden; box-shadow: 0 2px 12px rgba(0,0,0,0.06); }
        .stat-card::before { content: ''; position: absolute; top: -20px; right: -20px; width: 80px; height: 80px; border-radius: 50%; opacity: 0.08; }
        .stat-card.green::before { background: var(--g500); }
        .stat-card.gold::before { background: var(--gold); }
        .stat-card.red::before { background: #e74c3c; }
        .stat-card.blue::before { background: #3498db; }
        .stat-icon { width: 50px; height: 50px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 22px; margin-bottom: 14px; }
        .stat-icon.green { background: var(--g100); color: var(--g600); }
        .stat-icon.gold { background: #fef9ec; color: var(--gold-d); }
        .stat-icon.red { background: #fdf0ef; color: #c0392b; }
        .stat-icon.blue { background: #ebf5fb; color: #2980b9; }
        .stat-value { font-size: 28px; font-weight: 800; color: var(--g800); line-height: 1; }
        .stat-label { font-size: 13px; color: #888; margin-top: 4px; }
        .stat-change { font-size: 12px; font-weight: 600; margin-top: 8px; }
        .stat-change.up { color: var(--g500); }
        .stat-change.down { color: #e74c3c; }

        /* Table */
        .table-wrap { background: white; border-radius: 20px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); overflow: hidden; }
        .table-header { padding: 20px 24px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #f0f2f0; flex-wrap: wrap; gap: 12px; }
        .table-header h3 { font-size: 16px; font-weight: 700; color: var(--g800); }
        table { width: 100%; border-collapse: collapse; }
        th { background: #f8faf8; padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 700; color: #666; text-transform: uppercase; letter-spacing: 0.5px; border-bottom: 1px solid #eee; }
        td { padding: 14px 16px; border-bottom: 1px solid #f5f7f5; font-size: 14px; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafcfa; }

        /* Status badges */
        .status-badge { padding: 4px 12px; border-radius: 999px; font-size: 12px; font-weight: 700; display: inline-block; }
        .status-yellow { background: #fef9c3; color: #854d0e; }
        .status-blue { background: #dbeafe; color: #1e40af; }
        .status-green { background: #dcfce7; color: #14532d; }
        .status-indigo { background: #e0e7ff; color: #3730a3; }
        .status-purple { background: #f3e8ff; color: #581c87; }
        .status-emerald { background: #d1fae5; color: #064e3b; }
        .status-red { background: #fee2e2; color: #7f1d1d; }
        .status-gray { background: #f3f4f6; color: #374151; }

        /* Forms */
        .form-card { background: white; border-radius: 20px; padding: 28px; box-shadow: 0 2px 12px rgba(0,0,0,0.06); margin-bottom: 20px; }
        .form-card h3 { font-size: 16px; font-weight: 700; color: var(--g800); margin-bottom: 20px; padding-bottom: 12px; border-bottom: 1px solid #f0f2f0; display: flex; align-items: center; gap: 8px; }
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px; }
        .form-grid.cols-3 { grid-template-columns: repeat(3, 1fr); }
        .col-span-2 { grid-column: span 2; }
        .col-span-full { grid-column: 1 / -1; }
        .form-group { margin-bottom: 16px; }
        .form-label { display: block; font-size: 13px; font-weight: 600; color: #555; margin-bottom: 6px; }
        .form-control { width: 100%; padding: 10px 16px; border: 2px solid #e8ecef; border-radius: 14px; font-size: 14px; font-family: inherit; transition: all 0.2s; background: #fafafa; outline: none; color: #2c3e50; }
        .form-control:focus { border-color: var(--g500); background: white; box-shadow: 0 0 0 3px rgba(64,145,108,0.1); }
        textarea.form-control { border-radius: 14px; resize: vertical; }
        select.form-control { background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='%23333' d='M7.247 11.14L2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3e%3c/svg%3e"); background-repeat: no-repeat; background-position: right 14px center; background-size: 12px; -webkit-appearance: none; }
        .form-check { display: flex; align-items: center; gap: 8px; padding: 8px 0; }
        .form-check input[type="checkbox"] { width: 18px; height: 18px; border-radius: 6px; accent-color: var(--g500); }
        .form-check label { font-size: 14px; color: #444; cursor: pointer; }
        .invalid-feedback { color: #e74c3c; font-size: 12px; margin-top: 4px; }
        .form-control.is-invalid { border-color: #e74c3c; }

        /* Buttons */
        .btn { display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; border-radius: 12px; font-weight: 600; font-size: 14px; cursor: pointer; border: none; transition: all 0.2s; text-decoration: none; font-family: inherit; }
        .btn-sm { padding: 6px 14px; font-size: 12px; border-radius: 8px; }
        .btn-primary { background: linear-gradient(135deg, var(--g600), var(--g500)); color: white; }
        .btn-primary:hover { background: linear-gradient(135deg, var(--g700), var(--g600)); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(40,144,108,0.3); }
        .btn-gold { background: linear-gradient(135deg, var(--gold-d), var(--gold)); color: var(--g900); }
        .btn-gold:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(200,160,60,0.3); }
        .btn-danger { background: linear-gradient(135deg, #e74c3c, #c0392b); color: white; }
        .btn-danger:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(231,76,60,0.25); }
        .btn-outline { background: transparent; border: 2px solid #e8ecef; color: #555; }
        .btn-outline:hover { border-color: var(--g500); color: var(--g600); }
        .btn-light { background: #f5f7f5; color: #555; }
        .btn-light:hover { background: var(--g100); color: var(--g700); }
        .btn-secondary { background: #e8ecef; color: #555; }

        /* Alert */
        .alert { padding: 12px 18px; border-radius: 14px; margin-bottom: 16px; display: flex; align-items: center; gap: 10px; font-size: 14px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-danger { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .alert-warning { background: #fff3cd; color: #856404; border: 1px solid #ffeeba; }

        /* Search / Filter bar */
        .filter-bar { background: white; border-radius: 16px; padding: 16px 20px; margin-bottom: 20px; display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .filter-item { flex: 1; min-width: 150px; }
        .filter-item label { display: block; font-size: 12px; font-weight: 600; color: #666; margin-bottom: 5px; }

        /* Pagination */
        .kgm-pagination { display: flex; align-items: center; gap: 4px; }
        .pg-btn {
            width: 36px; height: 36px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            font-size: 15px; color: #555; background: #f5f7f5;
            text-decoration: none; transition: all 0.2s; border: none; cursor: pointer;
        }
        .pg-btn:hover { background: var(--g100); color: var(--g700); }
        .pg-btn.disabled { color: #ccc; pointer-events: none; background: transparent; }
        .pg-info { font-size: 13px; font-weight: 600; color: #888; padding: 0 8px; min-width: 60px; text-align: center; }

        /* Responsive */
        @media(max-width: 1100px) { .stats-grid { grid-template-columns: repeat(2, 1fr); } }
        @media(max-width: 768px) { .sidebar { transform: translateX(-100%); } .main-wrapper { margin-left: 0; } .stats-grid { grid-template-columns: 1fr 1fr; } }
        @media(max-width: 480px) { .stats-grid { grid-template-columns: 1fr; } .form-grid { grid-template-columns: 1fr; } }
        [x-cloak] { display: none; }
    </style>
    @stack('styles')
</head>
<body x-data="{ sidebarOpen: true }">

{{-- Sidebar --}}
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="sidebar-logo-inner">
                <img src="{{ asset('images/logo.png') }}" style="width: 40px; height: 40px;" alt="Logo">
            <div class="logo-text">KGM Admin<span>ระบบจัดการหลังบ้าน</span></div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-title">ภาพรวม</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
            <a href="{{ route('admin.reports') }}" class="nav-item {{ request()->routeIs('admin.reports') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> รายงาน
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-section-title">สินค้า</div>
            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i> จัดการสินค้า
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <i class="bi bi-tags"></i> หมวดหมู่
            </a>
            <a href="{{ route('admin.product-types.index') }}" class="nav-item {{ request()->routeIs('admin.product-types*') ? 'active' : '' }}">
                <i class="bi bi-grid-3x3-gap"></i> ประเภทสินค้า
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-section-title">ออเดอร์</div>
            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <i class="bi bi-receipt"></i> คำสั่งซื้อ
                @php $pendingCount = \App\Models\Order::where('status','payment_uploaded')->count(); @endphp
                @if($pendingCount > 0)<span class="badge-dot">{{ $pendingCount }}</span>@endif
            </a>
            <a href="{{ route('admin.quotes.index') }}" class="nav-item {{ request()->routeIs('admin.quotes*') ? 'active' : '' }}">
                <i class="bi bi-file-earmark-text"></i> ใบเสนอราคา
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-section-title">การตลาด</div>
            <a href="{{ route('admin.coupons.index') }}" class="nav-item {{ request()->routeIs('admin.coupons*') ? 'active' : '' }}">
                <i class="bi bi-ticket-perforated"></i> คูปองส่วนลด
            </a>
            <a href="{{ route('admin.marketing.index') }}" class="nav-item {{ request()->routeIs('admin.marketing*') || request()->routeIs('admin.flash-sales*') ? 'active' : '' }}">
                <i class="bi bi-lightning-charge"></i> Flash Sale
            </a>
            <a href="{{ route('admin.banners.index') }}" class="nav-item {{ request()->routeIs('admin.banners*') ? 'active' : '' }}">
                <i class="bi bi-image"></i> จัดการ Banner
            </a>
            <a href="{{ route('admin.posts.index') }}" class="nav-item {{ request()->routeIs('admin.posts*') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i> บทความ/ข่าวสาร
            </a>
            <a href="{{ route('admin.videos.index') }}" class="nav-item {{ request()->routeIs('admin.videos*') ? 'active' : '' }}">
                <i class="bi bi-play-circle"></i> เรื่องราวที่น่าสนใจ
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-section-title">ผู้ใช้งาน</div>
            <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> ลูกค้า
            </a>
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="bi bi-person-badge"></i> ผู้ใช้งานระบบ
            </a>
            <a href="{{ route('admin.contacts.index') }}" class="nav-item {{ request()->routeIs('admin.contacts*') ? 'active' : '' }}">
                <i class="bi bi-chat-left-text"></i> ข้อความติดต่อ
                @php $unread = \App\Models\ContactMessage::where('is_read',false)->count(); @endphp
                @if($unread > 0)<span class="badge-dot">{{ $unread }}</span>@endif
            </a>
            <a href="{{ route('admin.careers.index') }}" class="nav-item {{ request()->routeIs('admin.careers*') ? 'active' : '' }}">
                <i class="bi bi-briefcase"></i> ใบสมัครงาน
            </a>
            <a href="{{ route('admin.dealers.index') }}" class="nav-item {{ request()->routeIs('admin.dealers*') ? 'active' : '' }}">
                <i class="bi bi-shop"></i> ตัวแทนจำหน่าย
            </a>
        </div>
        <div class="nav-section">
            <div class="nav-section-title">ระบบ</div>
            <a href="{{ route('admin.showrooms.index') }}" class="nav-item {{ request()->routeIs('admin.showrooms*') ? 'active' : '' }}">
                <i class="bi bi-geo-alt"></i> สาขา/โชว์รูม
            </a>
            <a href="{{ route('admin.settings.index') }}" class="nav-item {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                <i class="bi bi-gear"></i> ตั้งค่าระบบ
            </a>
            <a href="{{ route('admin.settings.logs') }}" class="nav-item {{ request()->routeIs('admin.settings.logs') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Audit Log
            </a>
        </div>
    </nav>
</aside>

{{-- Main --}}
<div class="main-wrapper">
    {{-- Topbar --}}
    <header class="admin-topbar">
        <div>
            <div class="topbar-title">@yield('title', 'Dashboard')</div>
        </div>
        <div class="topbar-actions">
            <a href="{{ route('home') }}" class="topbar-btn" target="_blank" title="ดูหน้าเว็บ">
                <i class="bi bi-box-arrow-up-right"></i>
            </a>
            <div class="topbar-btn" title="แจ้งเตือน">
                <i class="bi bi-bell"></i>
                @if(isset($unread) && $unread > 0)<span class="topbar-notif">{{ $unread }}</span>@endif
            </div>
            <div x-data="{ open: false }" style="position:relative;">
                <div class="admin-avatar" @click="open=!open"><i class="bi bi-person-fill"></i></div>
                <div x-show="open" @click.away="open=false" x-cloak
                    style="position:absolute;right:0;top:48px;background:white;border-radius:16px;box-shadow:0 8px 32px rgba(0,0,0,0.12);min-width:180px;overflow:hidden;z-index:999;">
                    <div style="padding:14px 16px;border-bottom:1px solid #f0f2f0;">
                        <div style="font-weight:700;font-size:14px;">{{ auth()->user()->name }}</div>
                        <div style="font-size:12px;color:#888;">{{ auth()->user()->email }}</div>
                    </div>
                    <a href="{{ route('admin.settings.index') }}" style="display:flex;align-items:center;gap:8px;padding:10px 16px;font-size:13px;color:#555;text-decoration:none;"><i class="bi bi-gear"></i> ตั้งค่า</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" style="display:flex;align-items:center;gap:8px;padding:10px 16px;font-size:13px;color:#e74c3c;background:none;border:none;cursor:pointer;width:100%;font-family:inherit;">
                            <i class="bi bi-box-arrow-right"></i> ออกจากระบบ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Content --}}
    <main class="admin-content">
        @if(session('success'))
            <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger"><i class="bi bi-exclamation-circle-fill"></i> {{ session('error') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <div><strong>กรุณาตรวจสอบข้อมูล:</strong><ul style="margin:4px 0 0 16px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
            </div>
        @endif
        @yield('content')
    </main>
</div>

@stack('scripts')
<script>
    const sidebar = document.querySelector('.sidebar');
    const active = sidebar?.querySelector('.nav-item.active');
    if (sidebar && active) {
        const offset = active.offsetTop - sidebar.clientHeight / 2 + active.clientHeight / 2;
        sidebar.scrollTop = Math.max(0, offset);
    }
</script>
</body>
</html>
