@extends('layouts.app')
@section('title', 'ตะกร้าสินค้า')
@section('content')
<div class="container" style="padding-top:32px;padding-bottom:64px;">
    <h1 style="font-size:28px;font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;"><i class="bi bi-cart3"></i> ตะกร้าสินค้า</h1>

    @if($cartItems->isEmpty())
    <div style="text-align:center;padding:80px 0;background:white;border-radius:24px;">
        <i class="bi bi-cart-x" style="font-size:64px;color:#ddd;display:block;margin-bottom:20px;"></i>
        <h2 style="color:#aaa;font-size:22px;">ตะกร้าของคุณว่างเปล่า</h2>
        <p style="color:#aaa;margin:8px 0 24px;">เลือกสินค้าที่ต้องการแล้วเพิ่มลงตะกร้า</p>
        <a href="{{ route('shop') }}" class="btn btn-primary btn-lg"><i class="bi bi-shop"></i> ช็อปปิ้งต่อ</a>
    </div>
    @else
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:28px;align-items:start;">
        {{-- Cart Items --}}
        <div>
            {{-- Free Shipping Bar --}}
            @if($amountForFreeShipping > 0)
            <div style="background:linear-gradient(135deg,var(--kgm-gold-100),white);border:2px solid var(--kgm-gold-300);border-radius:16px;padding:14px 20px;margin-bottom:16px;display:flex;align-items:center;gap:10px;">
                <i class="bi bi-truck" style="color:var(--kgm-gold-600);font-size:20px;"></i>
                <div style="flex:1;">
                    <span style="font-weight:700;color:var(--kgm-green-800);">ซื้อเพิ่มอีก ฿{{ number_format($amountForFreeShipping, 0) }} จะได้ส่งฟรี!</span>
                    <div style="background:#e8ecef;border-radius:999px;height:6px;margin-top:6px;overflow:hidden;">
                        <div style="background:linear-gradient(to right,var(--kgm-gold-500),var(--kgm-gold-300));height:100%;border-radius:999px;width:{{ min(100, ($subtotal/$freeShippingThreshold)*100) }}%;transition:width 0.5s;"></div>
                    </div>
                </div>
            </div>
            @else
            <div style="background:var(--kgm-green-100);border-radius:16px;padding:12px 20px;margin-bottom:16px;display:flex;align-items:center;gap:8px;color:var(--kgm-green-700);font-weight:700;">
                <i class="bi bi-truck-flatbed"></i> คุณได้รับการจัดส่งฟรี!
            </div>
            @endif

            <div style="background:white;border-radius:20px;box-shadow:0 2px 10px rgba(0,0,0,0.06);overflow:hidden;">
                @foreach($cartItems as $item)
                <div style="display:flex;gap:16px;padding:20px;border-bottom:1px solid #f5f7f5;" id="cart-item-{{ $item->id }}">
                    <a href="{{ route('shop.show', $item->product->slug) }}">
                        <img src="{{ $item->product->main_image ? asset('storage/'.$item->product->main_image) : asset('images/no-product.png') }}"
                            style="width:90px;height:90px;object-fit:cover;border-radius:14px;flex-shrink:0;" alt="">
                    </a>
                    <div style="flex:1;">
                        <a href="{{ route('shop.show', $item->product->slug) }}" style="font-weight:700;font-size:15px;color:var(--kgm-green-900);">{{ $item->product->name }}</a>
                        @if($item->variant)<div style="font-size:13px;color:#888;margin-top:2px;">{{ $item->variant->label }}</div>@endif
                        <div style="font-size:16px;font-weight:700;color:var(--kgm-green-600);margin-top:8px;">฿{{ number_format($item->product->current_price + ($item->variant?->price_adjustment ?? 0), 0) }}</div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:10px;">
                        <form method="POST" action="{{ route('cart.remove', $item->id) }}">
                            @csrf @method('DELETE')
                            <button type="submit" style="background:none;border:none;cursor:pointer;color:#aaa;font-size:18px;" title="ลบ"><i class="bi bi-trash3"></i></button>
                        </form>
                        <div style="display:flex;align-items:center;border:2px solid #e8ecef;border-radius:999px;overflow:hidden;">
                            <form method="POST" action="{{ route('cart.update', $item->id) }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="quantity" value="{{ max(0, $item->quantity-1) }}">
                                <button type="submit" style="width:36px;height:36px;background:none;border:none;cursor:pointer;font-size:16px;color:var(--kgm-green-600);">-</button>
                            </form>
                            <span style="width:40px;text-align:center;font-weight:700;">{{ $item->quantity }}</span>
                            <form method="POST" action="{{ route('cart.update', $item->id) }}" style="display:inline;">
                                @csrf
                                <input type="hidden" name="quantity" value="{{ $item->quantity+1 }}">
                                <button type="submit" style="width:36px;height:36px;background:none;border:none;cursor:pointer;font-size:16px;color:var(--kgm-green-600);">+</button>
                            </form>
                        </div>
                        <div style="font-weight:800;color:var(--kgm-green-800);">฿{{ number_format($item->subtotal, 0) }}</div>
                    </div>
                </div>
                @endforeach
            </div>

            <div style="display:flex;justify-content:space-between;margin-top:16px;">
                <a href="{{ route('shop') }}" class="btn btn-outline"><i class="bi bi-arrow-left"></i> ช็อปปิ้งต่อ</a>
            </div>
        </div>

        {{-- Summary --}}
        <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);position:sticky;top:90px;">
            <h3 style="font-size:18px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;padding-bottom:12px;border-bottom:2px solid #f0f2f0;"><i class="bi bi-receipt"></i> สรุปรายการ</h3>

            {{-- Coupon --}}
            @if($coupon)
            <div style="background:var(--kgm-green-100);border-radius:12px;padding:12px;margin-bottom:16px;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-weight:700;color:var(--kgm-green-700);"><i class="bi bi-ticket-perforated"></i> {{ $coupon->code }}</span>
                <form method="DELETE" action="{{ route('cart.coupon.remove') }}" onsubmit="this.method='post'">
                    @csrf @method('DELETE')
                    <button type="submit" style="background:none;border:none;color:#e74c3c;cursor:pointer;font-size:14px;"><i class="bi bi-x-lg"></i></button>
                </form>
            </div>
            @else
            <form method="POST" action="{{ route('cart.coupon') }}" style="display:flex;gap:8px;margin-bottom:16px;">
                @csrf
                <input type="text" name="coupon_code" class="form-control" placeholder="รหัสคูปอง" style="border-radius:12px;flex:1;">
                <button type="submit" class="btn btn-sm btn-gold"><i class="bi bi-check-lg"></i></button>
            </form>
            @endif

            {{-- Totals --}}
            <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px;">
                <div style="display:flex;justify-content:space-between;font-size:14px;"><span>ยอดสินค้า</span><span>฿{{ number_format($subtotal, 2) }}</span></div>
                @if($discountAmount > 0)<div style="display:flex;justify-content:space-between;font-size:14px;color:#e74c3c;"><span>ส่วนลด</span><span>-฿{{ number_format($discountAmount, 2) }}</span></div>@endif
                <div style="display:flex;justify-content:space-between;font-size:14px;"><span>ค่าจัดส่ง</span><span>{{ $shippingFee > 0 ? '฿'.number_format($shippingFee,2) : 'ฟรี' }}</span></div>
                <div style="border-top:2px solid #e8ecef;padding-top:10px;display:flex;justify-content:space-between;font-size:18px;font-weight:800;color:var(--kgm-green-700);">
                    <span>ยอดรวม</span><span>฿{{ number_format($total, 2) }}</span>
                </div>
            </div>

            @auth
            <a href="{{ route('checkout') }}" class="btn btn-primary w-full btn-lg" style="justify-content:center;">
                <i class="bi bi-bag-check"></i> ดำเนินการสั่งซื้อ
            </a>
            @else
            <a href="{{ route('login') }}" class="btn btn-primary w-full btn-lg" style="justify-content:center;">
                <i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบเพื่อสั่งซื้อ
            </a>
            @endauth
        </div>
    </div>
    @endif
</div>
@endsection
