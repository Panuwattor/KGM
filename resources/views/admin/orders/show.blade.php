@extends('layouts.admin')
@section('title', 'ออเดอร์ '.$order->order_number)

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">ออเดอร์ {{ $order->order_number }}</div>
        <div class="page-subtitle">{{ $order->created_at->format('d/m/Y H:i') }}</div>
    </div>
    <div style="display:flex;gap:10px;">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
        <span class="btn status-{{ $order->status_color }}" style="border-radius:12px;font-size:14px;">{{ $order->status_label }}</span>
    </div>
</div>

<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    <div>
        {{-- Items --}}
        <div class="form-card">
            <h3><i class="bi bi-cart3"></i> รายการสินค้า</h3>
            @foreach($order->items as $item)
            <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #f5f7f5;">
                <img src="{{ $item->product_image ? asset('storage/'.$item->product_image) : asset('images/no-product.png') }}"
                    style="width:60px;height:60px;object-fit:cover;border-radius:10px;flex-shrink:0;">
                <div style="flex:1;">
                    <div style="font-weight:700;">{{ $item->product_name }}</div>
                    @if($item->variant_label)<div style="font-size:12px;color:#888;">{{ $item->variant_label }}</div>@endif
                    <div style="font-size:13px;margin-top:4px;">฿{{ number_format($item->unit_price, 0) }} × {{ $item->quantity }}</div>
                </div>
                <div style="font-weight:700;color:var(--g600);">฿{{ number_format($item->subtotal, 0) }}</div>
            </div>
            @endforeach
            <div style="margin-top:16px;padding-top:16px;border-top:2px solid #f0f2f0;">
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:14px;">
                    <span>ยอดสินค้า</span><span>฿{{ number_format($order->subtotal, 2) }}</span>
                </div>
                @if($order->discount_amount > 0)
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:14px;color:#e74c3c;">
                    <span>ส่วนลด ({{ $order->coupon_code }})</span><span>-฿{{ number_format($order->discount_amount, 2) }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:14px;">
                    <span>ค่าจัดส่ง</span><span>{{ $order->shipping_fee > 0 ? '฿'.number_format($order->shipping_fee,2) : 'ฟรี' }}</span>
                </div>
                @if($order->vat_amount > 0)
                <div style="display:flex;justify-content:space-between;margin-bottom:8px;font-size:14px;color:#888;">
                    <span>ภาษีมูลค่าเพิ่ม (VAT 7%)</span><span>฿{{ number_format($order->vat_amount, 2) }}</span>
                </div>
                @endif
                <div style="display:flex;justify-content:space-between;font-size:18px;font-weight:800;color:var(--g600);border-top:2px solid #e8ecef;padding-top:12px;margin-top:8px;">
                    <span>ยอดสุทธิ</span><span>฿{{ number_format($order->total, 2) }}</span>
                </div>
            </div>
        </div>

        {{-- Payment Slip --}}
        @if($order->payment_slip)
        <div class="form-card">
            <h3><i class="bi bi-credit-card"></i> หลักฐานการชำระเงิน</h3>
            <img src="{{ asset('storage/'.$order->payment_slip) }}" style="max-width:300px;border-radius:14px;border:2px solid #eee;" alt="สลิปโอนเงิน">
            <div style="margin-top:12px;font-size:13px;color:#888;">อัปโหลดเมื่อ: {{ $order->payment_uploaded_at?->format('d/m/Y H:i') }}</div>
            @if($order->status === 'payment_uploaded')
            <div style="display:flex;gap:10px;margin-top:16px;">
                <form method="POST" action="{{ route('admin.orders.verify-payment', $order) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-circle"></i> อนุมัติการชำระเงิน</button>
                </form>
                <form method="POST" action="{{ route('admin.orders.reject-payment', $order) }}" x-data="{ reason: '' }">
                    @csrf
                    <input type="text" name="reason" placeholder="เหตุผลการปฏิเสธ" class="form-control" style="display:inline-block;width:220px;" required>
                    <button type="submit" class="btn btn-danger" onclick="return confirm('ปฏิเสธสลิปนี้?')"><i class="bi bi-x-circle"></i> ปฏิเสธ</button>
                </form>
            </div>
            @elseif($order->payment_verified_at)
            <div style="margin-top:8px;" class="status-badge status-green">
                <i class="bi bi-check-circle-fill"></i> อนุมัติแล้ว {{ $order->payment_verified_at->format('d/m/Y H:i') }}
            </div>
            @endif
        </div>
        @endif

        {{-- Tracking --}}
        <div class="form-card">
            <h3><i class="bi bi-truck"></i> ข้อมูลการจัดส่ง</h3>
            @if($order->tracking_number)
            <div style="background:var(--g100);border-radius:12px;padding:14px;margin-bottom:16px;">
                <div style="font-size:13px;color:#555;">บริษัทขนส่ง: <strong>{{ $order->shipping_provider }}</strong></div>
                <div style="font-size:16px;font-weight:800;color:var(--g700);margin-top:4px;"><i class="bi bi-upc"></i> {{ $order->tracking_number }}</div>
            </div>
            @endif
            @if(in_array($order->status, ['payment_verified', 'processing', 'shipped']))
            <form method="POST" action="{{ route('admin.orders.tracking', $order) }}" style="display:flex;gap:10px;flex-wrap:wrap;">
                @csrf
                <select name="shipping_provider" class="form-control" style="flex:1;min-width:150px;" required>
                    <option>ไปรษณีย์ไทย</option>
                    <option>Kerry Express</option>
                    <option>Flash Express</option>
                    <option>J&T Express</option>
                    <option>DHL</option>
                </select>
                <input type="text" name="tracking_number" class="form-control" placeholder="เลข Tracking" style="flex:2;" value="{{ $order->tracking_number }}" required>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> อัปเดต</button>
            </form>
            @endif
        </div>

        {{-- Status Update --}}
        <div class="form-card">
            <h3><i class="bi bi-arrow-repeat"></i> เปลี่ยนสถานะ</h3>
            <form method="POST" action="{{ route('admin.orders.update', $order) }}" style="display:flex;gap:10px;">
                @csrf @method('PUT')
                <select name="status" class="form-control">
                    @foreach(\App\Models\Order::STATUS_LABELS as $key => $info)
                    <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $info['label'] }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
            </form>
        </div>
    </div>

    <div>
        {{-- Shipping Info --}}
        <div class="form-card">
            <h3><i class="bi bi-geo-alt"></i> ที่อยู่จัดส่ง</h3>
            <div style="font-weight:700;font-size:15px;">{{ $order->ship_name }}</div>
            <div style="margin-top:6px;font-size:14px;line-height:1.8;color:#555;">
                {{ $order->ship_address }}<br>
                ต.{{ $order->ship_district }} อ.{{ $order->ship_amphoe }}<br>
                จ.{{ $order->ship_province }} {{ $order->ship_postcode }}<br>
                <i class="bi bi-telephone"></i> {{ $order->ship_phone }}
            </div>
        </div>

        {{-- Tax Invoice --}}
        @if($order->needs_tax_invoice)
        <div class="form-card">
            <h3><i class="bi bi-receipt-cutoff"></i> ข้อมูลใบกำกับภาษี</h3>
            <div style="font-size:14px;line-height:2;color:#555;">
                <div><strong>บริษัท:</strong> {{ $order->tax_company_name }}</div>
                <div><strong>เลขผู้เสียภาษี:</strong> {{ $order->tax_id }}</div>
                <div><strong>สาขา:</strong> {{ $order->tax_branch }}</div>
                <div><strong>ที่อยู่:</strong> {{ $order->tax_address }}</div>
            </div>
        </div>
        @endif

        {{-- Customer Note --}}
        @if($order->customer_note)
        <div class="form-card">
            <h3><i class="bi bi-chat-left-quote"></i> หมายเหตุจากลูกค้า</h3>
            <p style="font-size:14px;color:#555;">{{ $order->customer_note }}</p>
        </div>
        @endif

        {{-- Admin Note --}}
        <div class="form-card">
            <h3><i class="bi bi-pencil-square"></i> หมายเหตุแอดมิน</h3>
            <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                @csrf @method('PUT')
                <input type="hidden" name="status" value="{{ $order->status }}">
                <textarea name="admin_note" class="form-control" rows="3" placeholder="หมายเหตุภายใน...">{{ $order->admin_note }}</textarea>
                <button type="submit" class="btn btn-sm btn-light" style="margin-top:8px;"><i class="bi bi-floppy"></i> บันทึกหมายเหตุ</button>
            </form>
        </div>
    </div>
</div>
@endsection
