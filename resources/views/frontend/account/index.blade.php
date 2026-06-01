@extends('layouts.app')
@section('title', 'บัญชีของฉัน')
@section('content')
<div class="container" style="padding:32px 0 64px;">
    <div style="display:grid;grid-template-columns:240px 1fr;gap:28px;">
        {{-- Sidebar --}}
        <div>
            <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:16px;">
                <div style="text-align:center;margin-bottom:16px;">
                    <div style="width:64px;height:64px;background:linear-gradient(135deg,var(--kgm-green-600),var(--kgm-green-400));border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:white;margin:0 auto 10px;">{{ strtoupper(substr($user->name,0,1)) }}</div>
                    <div style="font-weight:800;font-size:15px;">{{ $user->name }}</div>
                    <div style="font-size:12px;color:#888;">{{ $user->email }}</div>
                </div>
            </div>
            <nav style="background:white;border-radius:20px;padding:12px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                @foreach([
                    ['account.index','bi-speedometer2','ภาพรวม'],
                    ['account.profile','bi-person','ข้อมูลส่วนตัว'],
                    ['account.orders','bi-receipt','คำสั่งซื้อ'],
                    ['account.addresses','bi-geo-alt','ที่อยู่จัดส่ง'],
                    ['account.wishlist','bi-heart','รายการโปรด'],
                    ['account.quotes','bi-file-earmark-text','ใบเสนอราคา'],
                ] as [$route,$icon,$label])
                <a href="{{ route($route) }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:12px;font-size:14px;color:{{ request()->routeIs($route) ? 'var(--kgm-green-700)' : '#555' }};background:{{ request()->routeIs($route) ? 'var(--kgm-green-100)' : 'transparent' }};font-weight:{{ request()->routeIs($route) ? '700' : '400' }};text-decoration:none;transition:all 0.2s;">
                    <i class="bi {{ $icon }}" style="font-size:16px;"></i> {{ $label }}
                </a>
                @endforeach
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:12px;font-size:14px;color:#e74c3c;background:none;border:none;cursor:pointer;width:100%;font-family:inherit;margin-top:8px;">
                        <i class="bi bi-box-arrow-right" style="font-size:16px;"></i> ออกจากระบบ
                    </button>
                </form>
            </nav>
        </div>

        {{-- Content --}}
        <div>
            <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;">
                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;">
                    <div style="text-align:center;background:#f8faf8;border-radius:16px;padding:20px;">
                        <div style="font-size:28px;font-weight:800;color:var(--kgm-green-600);">{{ $totalOrders }}</div>
                        <div style="font-size:13px;color:#888;margin-top:4px;">คำสั่งซื้อทั้งหมด</div>
                    </div>
                    <div style="text-align:center;background:#fef9ec;border-radius:16px;padding:20px;">
                        <div style="font-size:28px;font-weight:800;color:var(--kgm-gold-600);">{{ $wishlistCount }}</div>
                        <div style="font-size:13px;color:#888;margin-top:4px;">รายการโปรด</div>
                    </div>
                    <div style="text-align:center;background:#f8faf8;border-radius:16px;padding:20px;">
                        <div style="font-size:28px;font-weight:800;color:var(--kgm-green-600);">{{ $user->addresses()->count() }}</div>
                        <div style="font-size:13px;color:#888;margin-top:4px;">ที่อยู่จัดส่ง</div>
                    </div>
                </div>
            </div>
            <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                    <h3 style="font-size:16px;font-weight:800;color:var(--kgm-green-800);"><i class="bi bi-receipt"></i> คำสั่งซื้อล่าสุด</h3>
                    <a href="{{ route('account.orders') }}" style="font-size:13px;color:var(--kgm-green-600);font-weight:600;">ดูทั้งหมด <i class="bi bi-arrow-right"></i></a>
                </div>
                @forelse($recentOrders as $order)
                <div style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid #f5f7f5;">
                    <div style="width:44px;height:44px;background:var(--kgm-green-100);border-radius:12px;display:flex;align-items:center;justify-content:center;"><i class="bi bi-receipt" style="color:var(--kgm-green-600);font-size:18px;"></i></div>
                    <div style="flex:1;">
                        <div style="font-weight:700;font-size:14px;">{{ $order->order_number }}</div>
                        <div style="font-size:12px;color:#888;">{{ $order->created_at->format('d/m/Y') }}</div>
                    </div>
                    <span class="badge status-badge status-{{ $order->status_color }}">{{ $order->status_label }}</span>
                    <div style="font-weight:700;color:var(--kgm-green-700);">฿{{ number_format($order->total, 0) }}</div>
                    <a href="{{ route('account.orders.show', $order) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i></a>
                </div>
                @empty
                <p style="text-align:center;color:#aaa;padding:24px 0;">ยังไม่มีคำสั่งซื้อ</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
