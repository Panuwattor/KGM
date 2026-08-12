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

@if($order->status === 'pending_payment' && $order->rejection_reason)
<div class="form-card mb-4" style="border-left:4px solid #e74c3c;background:#fdecea;margin-bottom:0;">
    <div style="color:#c0392b;font-weight:700;"><i class="bi bi-exclamation-triangle"></i> สลิปถูกปฏิเสธ — รอลูกค้าอัปโหลดใหม่</div>
    <div style="font-size:14px;color:#7d2d22;margin-top:4px;">เหตุผลที่แจ้งลูกค้า: {{ $order->rejection_reason }}</div>
</div>
@endif

<div class="row g-4">
    <div class="col-lg-8">
        {{-- Items --}}
        <div class="form-card">
            <h3><i class="bi bi-cart3"></i> รายการสินค้า</h3>
            @foreach($order->items as $item)
            <div style="display:flex;gap:12px;padding:12px 0;border-bottom:1px solid #f5f7f5;">
                <img src="{{ $item->product_image ? media_url($item->product_image) : asset('images/no-product.png') }}"
                    style="width:60px;height:60px;object-fit:cover;border-radius:10px;flex-shrink:0;">
                <div style="flex:1;">
                    <div style="font-weight:700;">{{ $item->product_name }}</div>
                    @if($item->variant_label)
                    <div style="margin-top:4px;">
                        <span style="display:inline-block;font-size:12px;font-weight:600;color:var(--g700);background:var(--g100);border-radius:6px;padding:2px 8px;">
                            <i class="bi bi-rulers"></i> ไซซ์: {{ $item->variant_label }}
                        </span>
                    </div>
                    @endif
                    @if($item->embroidery)
                    <div style="margin-top:4px;">
                        <span style="display:inline-block;font-size:12px;font-weight:600;color:#15803d;background:#dcfce7;border-radius:6px;padding:2px 8px;">
                            <i class="bi bi-pen"></i> ปักชื่อ
                            @if($item->embroidery_price > 0)+฿{{ number_format($item->embroidery_price,0) }}/ตัว@else(ฟรี)@endif
                        </span>
                    </div>
                    @if($item->embroidery_text)
                    <div style="font-size:12px;color:#444;margin-top:4px;white-space:pre-line;background:#f8fafc;border:1px solid #e5e7eb;border-radius:8px;padding:8px 10px;">{{ $item->embroidery_text }}</div>
                    @endif
                    @endif
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
            @php $slipUrl = media_url($order->payment_slip); @endphp
            <div style="position:relative;display:inline-block;">
                <img src="{{ $slipUrl }}" onclick="openSlipLightbox()"
                    style="max-width:300px;border-radius:14px;border:2px solid #eee;cursor:zoom-in;display:block;" alt="สลิปโอนเงิน" title="คลิกเพื่อดูแบบขยาย">
                <span style="position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,0.55);color:#fff;border-radius:8px;padding:4px 8px;font-size:12px;pointer-events:none;">
                    <i class="bi bi-zoom-in"></i> ขยาย
                </span>
            </div>
            <div style="display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;">
                <button type="button" class="btn btn-light btn-sm" onclick="copySlipImage(this)" data-url="{{ $slipUrl }}">
                    <i class="bi bi-clipboard"></i> คัดลอกรูป
                </button>
                <a href="{{ $slipUrl }}" download class="btn btn-light btn-sm">
                    <i class="bi bi-download"></i> ดาวน์โหลด
                </a>
            </div>
            <div style="margin-top:12px;font-size:13px;color:#888;">อัปโหลดเมื่อ: {{ $order->payment_uploaded_at?->format('d/m/Y H:i') }}</div>

            {{-- Lightbox ดูสลิปแบบขยาย (ไม่เปิดแท็บใหม่) --}}
            <div id="slip-lightbox" onclick="closeSlipLightbox()"
                style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.85);align-items:center;justify-content:center;padding:24px;cursor:zoom-out;">
                <img src="{{ $slipUrl }}" alt="สลิปโอนเงิน"
                    style="max-width:92vw;max-height:90vh;border-radius:12px;box-shadow:0 8px 40px rgba(0,0,0,0.5);">
                <button type="button" onclick="closeSlipLightbox()"
                    style="position:absolute;top:18px;right:22px;background:rgba(255,255,255,0.15);color:#fff;border:none;border-radius:999px;width:42px;height:42px;font-size:20px;cursor:pointer;">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
            @if($order->status === 'payment_uploaded')
            <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;">
                <form method="POST" action="{{ route('admin.orders.verify-payment', $order) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary"
                        data-confirm="อนุมัติการชำระเงิน?"
                        data-confirm-text="ยืนยันว่าตรวจสอบสลิปถูกต้องแล้ว"
                        data-confirm-icon="question"
                        data-confirm-yes="อนุมัติ"><i class="bi bi-check-circle"></i> อนุมัติการชำระเงิน</button>
                </form>
                <form method="POST" action="{{ route('admin.orders.reject-payment', $order) }}">
                    @csrf
                    <input type="hidden" name="reason">
                    <button type="submit" class="btn btn-danger"
                        data-confirm="ปฏิเสธสลิปการชำระเงินนี้?"
                        data-confirm-input="reason"
                        data-confirm-placeholder="ระบุเหตุผลที่จะแจ้งลูกค้า เช่น ยอดเงินไม่ตรง"
                        data-confirm-icon="warning"
                        data-confirm-color="#e74c3c"
                        data-confirm-yes="ปฏิเสธสลิป"><i class="bi bi-x-circle"></i> ปฏิเสธ</button>
                </form>
            </div>
            @elseif($order->payment_verified_at)
            <div style="margin-top:8px;" class="status-badge status-green">
                <i class="bi bi-check-circle-fill"></i> อนุมัติแล้ว {{ $order->payment_verified_at->format('d/m/Y H:i') }}
            </div>
            @endif
        </div>
        @endif

        {{-- Tracking / Pickup --}}
        @if($order->is_pickup)
        <div class="form-card">
            <h3><i class="bi bi-shop"></i> รับเองที่ร้าน</h3>
            @if($order->pickupShowroom)
            <div style="background:var(--g100);border-radius:12px;padding:14px;">
                <div style="font-weight:800;color:var(--g700);">{{ $order->pickupShowroom->name }}</div>
                <div style="font-size:13px;color:#555;margin-top:4px;">{{ $order->pickupShowroom->address }}</div>
            </div>
            @endif
            <div style="font-size:13px;color:#888;margin-top:12px;">
                ลูกค้ามารับเองที่ร้าน — เปลี่ยนสถานะเป็น <strong>“พร้อมรับที่ร้าน”</strong> เมื่อสินค้าพร้อม และ <strong>“รับสินค้าแล้ว”</strong> เมื่อลูกค้ามารับ (ในกล่องเปลี่ยนสถานะด้านล่าง)
            </div>
        </div>
        @else
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
                    @forelse($shippingProviders as $provider)
                    <option value="{{ $provider }}" {{ $order->shipping_provider === $provider ? 'selected' : '' }}>{{ $provider }}</option>
                    @empty
                    <option value="" disabled>— ยังไม่มีบริษัทขนส่ง กรุณาเพิ่มในเมนูตั้งค่า —</option>
                    @endforelse
                </select>
                <input type="text" name="tracking_number" class="form-control" placeholder="เลข Tracking" style="flex:2;" value="{{ $order->tracking_number }}" required>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> อัปเดต</button>
            </form>
            @endif
        </div>
        @endif

        {{-- Status Update --}}
        <div class="form-card">
            <h3><i class="bi bi-arrow-repeat"></i> เปลี่ยนสถานะ</h3>
            @if(in_array($order->status, ['cancelled', 'refunded']))
            <div style="background:#fdecea;border:1px solid #f5c6cb;border-radius:12px;padding:14px;color:#c0392b;font-size:14px;">
                <i class="bi bi-lock-fill"></i> ออเดอร์นี้ถูก{{ $order->status === 'refunded' ? 'คืนเงิน' : 'ยกเลิก' }}แล้ว และคืนสินค้าเข้าสต๊อกเรียบร้อย — ถือเป็นสถานะสิ้นสุด ไม่สามารถเปลี่ยนสถานะได้อีก
            </div>
            @else
            <form method="POST" action="{{ route('admin.orders.update', $order) }}" style="display:flex;gap:10px;">
                @csrf @method('PUT')
                <select name="status" class="form-control">
                    @foreach(\App\Models\Order::STATUS_LABELS as $key => $info)
                    <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $order->is_pickup ? (\App\Models\Order::PICKUP_STATUS_LABELS[$key] ?? $info['label']) : $info['label'] }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-primary"
                    data-confirm="เปลี่ยนสถานะออเดอร์นี้?"
                    data-confirm-text="ลูกค้าจะเห็นสถานะใหม่ทันที"
                    data-confirm-icon="question"
                    data-confirm-yes="เปลี่ยนสถานะ"><i class="bi bi-floppy"></i> บันทึก</button>
            </form>
            @endif
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Shipping / Contact Info --}}
        <div class="form-card">
            <h3>
                <i class="bi {{ $order->is_pickup ? 'bi-shop' : 'bi-geo-alt' }}"></i>
                {{ $order->is_pickup ? 'ผู้รับสินค้า (รับเองที่ร้าน)' : 'ที่อยู่จัดส่ง' }}
            </h3>
            <div style="font-weight:700;font-size:15px;">{{ $order->ship_name }}</div>
            @if($order->is_guest)
            <div style="margin-top:8px;background:#fdf6e3;border:1px solid #f0e2b6;border-radius:10px;padding:8px 10px;font-size:12.5px;color:#8a6d1f;line-height:1.6;">
                <i class="bi bi-person-badge"></i> <b>ลูกค้าทั่วไป</b> (ไม่ได้สมัครสมาชิก)<br>
                <span style="color:#a08c56;">ติดต่อกลับทางเบอร์โทรเท่านั้น</span>
            </div>
            @endif
            <div style="margin-top:6px;font-size:14px;line-height:1.8;color:#555;">
                @unless($order->is_pickup)
                {{ $order->ship_address }}<br>
                ต.{{ $order->ship_district }} อ.{{ $order->ship_amphoe }}<br>
                จ.{{ $order->ship_province }} {{ $order->ship_postcode }}<br>
                @endunless
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

@push('scripts')
<script>
function openSlipLightbox() {
    const lb = document.getElementById('slip-lightbox');
    if (lb) { lb.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
}
function closeSlipLightbox() {
    const lb = document.getElementById('slip-lightbox');
    if (lb) { lb.style.display = 'none'; document.body.style.overflow = ''; }
}
document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeSlipLightbox(); });

async function copySlipImage(btn) {
    const url = btn.dataset.url;
    const original = btn.innerHTML;
    btn.disabled = true;
    try {
        const res = await fetch(url);
        const blob = await res.blob();

        // คลิปบอร์ดส่วนใหญ่รองรับเฉพาะ image/png → แปลงผ่าน canvas ให้ชัวร์
        const pngBlob = await new Promise((resolve, reject) => {
            const img = new Image();
            img.onload = function () {
                const canvas = document.createElement('canvas');
                canvas.width = img.naturalWidth;
                canvas.height = img.naturalHeight;
                canvas.getContext('2d').drawImage(img, 0, 0);
                canvas.toBlob(b => b ? resolve(b) : reject(new Error('toBlob failed')), 'image/png');
            };
            img.onerror = reject;
            img.src = URL.createObjectURL(blob);
        });

        await navigator.clipboard.write([new ClipboardItem({ 'image/png': pngBlob })]);
        if (window.kgmToast) kgmToast('success', 'คัดลอกรูปหลักฐานแล้ว');
        btn.innerHTML = '<i class="bi bi-clipboard-check"></i> คัดลอกแล้ว';
        setTimeout(() => { btn.innerHTML = original; }, 1800);
    } catch (e) {
        // เบราว์เซอร์ไม่รองรับการคัดลอกรูป → คัดลอกลิงก์แทน
        try {
            await navigator.clipboard.writeText(url);
            if (window.kgmToast) kgmToast('info', 'เบราว์เซอร์ไม่รองรับการคัดลอกรูป — คัดลอกลิงก์แทนแล้ว');
        } catch (_) {
            if (window.kgmToast) kgmToast('error', 'ไม่สามารถคัดลอกได้');
        }
    } finally {
        btn.disabled = false;
    }
}
</script>
@endpush
