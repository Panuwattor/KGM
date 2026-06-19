@extends('layouts.app')
@section('title', 'ชำระเงิน')
@push('styles')
<style>
.checkout-step { display: flex; align-items: center; justify-content: center; gap: 0; margin-bottom: 36px; }
.step { display: flex; align-items: center; gap: 8px; }
.step-num { width: 32px; height: 32px; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; background: #e8ecef; color: #aaa; transition: all 0.3s; }
.step.active .step-num { background: var(--kgm-green-600); color: white; }
.step.done .step-num { background: var(--kgm-gold-400); color: var(--kgm-green-900); }
.step-label { font-size: 14px; font-weight: 600; color: #aaa; }
.step.active .step-label, .step.done .step-label { color: var(--kgm-green-700); }
.step-line { flex: 1; height: 2px; background: #e8ecef; max-width: 60px; margin: 0 8px; }

/* Delivery method cards */
.dm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
.dm-card { position: relative; display: flex; flex-direction: column; align-items: center; text-align: center; gap: 8px; padding: 24px 16px; border: 2px solid #e8ecef; border-radius: 16px; cursor: pointer; background: #fff; transition: all 0.2s ease; }
.dm-card:hover { border-color: var(--kgm-green-400); transform: translateY(-2px); box-shadow: 0 6px 16px rgba(45,106,79,0.10); }
.dm-radio { position: absolute; opacity: 0; width: 0; height: 0; pointer-events: none; }
.dm-card.sel { border-color: var(--kgm-green-600); background: var(--kgm-green-100); box-shadow: 0 4px 14px rgba(45,106,79,0.15); }
.dm-icon { width: 54px; height: 54px; border-radius: 999px; display: flex; align-items: center; justify-content: center; font-size: 24px; background: #f0f3f1; color: #b0b8b3; transition: all 0.2s ease; }
.dm-card:hover .dm-icon { background: #e3ece7; color: var(--kgm-green-500); }
.dm-card.sel .dm-icon { background: var(--kgm-green-600); color: #fff; }
.dm-title { font-weight: 800; font-size: 15px; color: #3a3a3a; }
.dm-card.sel .dm-title { color: var(--kgm-green-800); }
.dm-sub { font-size: 12px; color: #9aa39d; }
.dm-free { color: var(--kgm-gold-600); font-weight: 800; }
.dm-check { position: absolute; top: 12px; right: 12px; width: 22px; height: 22px; border-radius: 999px; background: var(--kgm-green-600); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 13px; box-shadow: 0 2px 6px rgba(45,106,79,0.3); }
.dm-card.dm-disabled { opacity: 0.45; cursor: not-allowed; }
.dm-card.dm-disabled:hover { transform: none; box-shadow: none; border-color: #e8ecef; }
.dm-card.dm-disabled:hover .dm-icon { background: #f0f3f1; color: #b0b8b3; }
/* มือถือ: คง 2 คอลัมน์ แต่ย่อขนาดให้พอดีจอ */
/* Pickup branch selector */
.pickup-branch { position: relative; display: flex; align-items: center; gap: 12px; border: 2px solid #e8ecef; border-radius: 14px; padding: 14px 16px; cursor: pointer; transition: all 0.2s ease; }
.pickup-branch:hover { border-color: var(--kgm-green-400); }
.pickup-branch.sel { border-color: var(--kgm-green-600); background: var(--kgm-green-100); }
.pickup-dot { flex-shrink: 0; width: 20px; height: 20px; border-radius: 999px; border: 2px solid #cfd8d3; position: relative; transition: all 0.2s ease; }
.pickup-branch.sel .pickup-dot { border-color: var(--kgm-green-600); }
.pickup-branch.sel .pickup-dot::after { content: ''; position: absolute; inset: 3px; border-radius: 999px; background: var(--kgm-green-600); }

@media (max-width: 480px) {
    .dm-grid { gap: 10px; }
    .dm-card { padding: 16px 8px; gap: 6px; }
    .dm-icon { width: 44px; height: 44px; font-size: 20px; }
    .dm-title { font-size: 13px; }
    .dm-sub { font-size: 11px; }
}

.checkout-layout { display: grid; grid-template-columns: 3fr 2fr; gap: 28px; align-items: start; }
.checkout-summary { position: sticky; top: 90px; }

@media (max-width: 767px) {
    .checkout-layout { grid-template-columns: 1fr; gap: 16px; }
    .checkout-summary { position: static; }
}
</style>
@endpush

@section('content')
<div class="container" style="padding-top:32px;padding-bottom:64px;max-width:1100px;">
    <h1 style="font-size:clamp(20px,5vw,28px);font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;"><i class="bi bi-bag-check"></i> ชำระเงิน</h1>

    <form method="POST" action="{{ route('checkout.store') }}" x-data="checkoutForm()">
        @csrf
        <div class="checkout-layout">
            <div>
                {{-- Delivery method --}}
                <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;">
                    <h3 style="font-size:17px;font-weight:800;color:var(--kgm-green-800);margin-bottom:16px;"><i class="bi bi-truck"></i> วิธีรับสินค้า</h3>
                    <div class="dm-grid">
                        <label class="dm-card" :class="{ 'sel': delivery === 'ship' }">
                            <input type="radio" name="delivery_method" value="ship" x-model="delivery" class="dm-radio">
                            <span class="dm-check" x-show="delivery === 'ship'" x-cloak><i class="bi bi-check-lg"></i></span>
                            <span class="dm-icon"><i class="bi bi-house-door"></i></span>
                            <span class="dm-title">จัดส่งถึงบ้าน</span>
                            <span class="dm-sub">คิดค่าจัดส่งตามจริง</span>
                        </label>
                        <label class="dm-card {{ $pickupShowrooms->isEmpty() ? 'dm-disabled' : '' }}" :class="{ 'sel': delivery === 'pickup' }" @if($pickupShowrooms->isEmpty()) title="ยังไม่มีสาขาให้รับสินค้า" @endif>
                            <input type="radio" name="delivery_method" value="pickup" x-model="delivery" class="dm-radio" @if($pickupShowrooms->isEmpty()) disabled @endif>
                            <span class="dm-check" x-show="delivery === 'pickup'" x-cloak><i class="bi bi-check-lg"></i></span>
                            <span class="dm-icon"><i class="bi bi-shop"></i></span>
                            <span class="dm-title">รับเองที่ร้าน</span>
                            <span class="dm-sub dm-free">ฟรี! ไม่มีค่าจัดส่ง</span>
                        </label>
                    </div>
                </div>

                {{-- Contact + Address --}}
                <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;">
                    <h3 style="font-size:17px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;">
                        <i class="bi bi-geo-alt"></i> <span x-text="delivery === 'pickup' ? 'ข้อมูลผู้รับสินค้า' : 'ที่อยู่จัดส่ง'"></span>
                    </h3>

                    {{-- saved addresses (เฉพาะจัดส่ง) --}}
                    @if($addresses->isNotEmpty())
                    <div x-show="delivery === 'ship'">
                        <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;">
                            @foreach($addresses as $addr)
                            <label style="display:flex;gap:12px;border:2px solid #e8ecef;border-radius:14px;padding:14px;cursor:pointer;transition:all 0.2s;" :class="{ 'border-color: var(--kgm-green-500)': selectedAddr === {{ $addr->id }} }">
                                <input type="radio" name="_address_id" value="{{ $addr->id }}" {{ $addr->is_default ? 'checked' : '' }} @change="fillAddress({{ $addr->id }}, '{{ addslashes($addr->recipient_name) }}', '{{ $addr->phone }}', '{{ addslashes($addr->address_line1) }}', '{{ $addr->district }}', '{{ $addr->amphoe }}', '{{ $addr->province }}', '{{ $addr->postcode }}')">
                                <div>
                                    <div style="font-weight:700;">{{ $addr->recipient_name }} <span style="font-weight:400;color:#888;">| {{ $addr->phone }}</span></div>
                                    <div style="font-size:13px;color:#666;margin-top:2px;">{{ $addr->full_address }}</div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        <div style="font-size:14px;color:var(--kgm-green-600);font-weight:600;cursor:pointer;margin-bottom:16px;" @click="newAddr = !newAddr">
                            <i class="bi bi-plus-circle"></i> ใช้ที่อยู่ใหม่
                        </div>
                    </div>
                    @endif

                    <div class="form-grid" x-show="!hasSavedAddr || newAddr || delivery === 'pickup'">
                        {{-- ผู้ติดต่อ/ผู้รับ จำเป็นทั้งสองแบบ --}}
                        <div class="form-group">
                            <label class="form-label">ชื่อผู้รับ *</label>
                            <input type="text" name="ship_name" class="form-control @error('ship_name') is-invalid @enderror" x-model="form.ship_name" required>
                            @error('ship_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">เบอร์โทรศัพท์ *</label>
                            <input type="text" name="ship_phone" class="form-control @error('ship_phone') is-invalid @enderror" x-model="form.ship_phone" required>
                        </div>

                        {{-- ที่อยู่ เฉพาะจัดส่งถึงบ้าน --}}
                        <template x-if="delivery === 'ship'">
                            <div class="form-grid col-span-2" style="grid-column:1/-1;">
                                <div class="form-group col-span-2">
                                    <label class="form-label">ที่อยู่ *</label>
                                    <input type="text" name="ship_address" class="form-control" x-model="form.ship_address" placeholder="บ้านเลขที่ ถนน ซอย" :required="delivery === 'ship'">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">แขวง/ตำบล *</label>
                                    <input type="text" name="ship_district" class="form-control" x-model="form.ship_district" :required="delivery === 'ship'">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">เขต/อำเภอ *</label>
                                    <input type="text" name="ship_amphoe" class="form-control" x-model="form.ship_amphoe" :required="delivery === 'ship'">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">จังหวัด *</label>
                                    <input type="text" name="ship_province" class="form-control" x-model="form.ship_province" :required="delivery === 'ship'">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">รหัสไปรษณีย์ *</label>
                                    <input type="text" name="ship_postcode" class="form-control" x-model="form.ship_postcode" maxlength="5" :required="delivery === 'ship'">
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- เลือกสาขารับเองที่ร้าน --}}
                    @if($pickupShowrooms->isNotEmpty())
                    <div x-show="delivery === 'pickup'" x-cloak style="margin-top:16px;">
                        <label class="form-label">เลือกสาขาที่ต้องการรับสินค้า *</label>
                        <div style="display:flex;flex-direction:column;gap:10px;">
                            @foreach($pickupShowrooms as $s)
                            <label class="pickup-branch" :class="{ 'sel': pickupId === {{ $s->id }} }">
                                <input type="radio" name="pickup_showroom_id" value="{{ $s->id }}" x-model.number="pickupId" :required="delivery === 'pickup'" style="position:absolute;opacity:0;">
                                <span class="pickup-dot"></span>
                                <span style="flex:1;">
                                    <span style="font-weight:700;display:block;">{{ $s->name }} @if($s->is_main)<span style="font-size:11px;color:var(--kgm-gold-700);font-weight:700;">(สาขาหลัก)</span>@endif</span>
                                    <span style="font-size:13px;color:#666;">{{ $s->address }}</span>
                                </span>
                            </label>
                            @endforeach
                        </div>
                        {{-- รายละเอียดสาขาที่เลือก --}}
                        <template x-if="pickupShop">
                            <div style="background:var(--kgm-green-100);border-radius:14px;padding:16px;margin-top:12px;font-size:14px;color:#555;line-height:1.7;">
                                <template x-if="pickupShop.phone"><div><i class="bi bi-telephone"></i> <span x-text="pickupShop.phone"></span></div></template>
                                <template x-if="pickupShop.open_hours"><div><i class="bi bi-clock"></i> <span x-text="pickupShop.open_hours"></span></div></template>
                                <div style="font-size:12px;color:#888;margin-top:6px;">* ทางร้านจะแจ้งเมื่อสินค้าพร้อมให้เข้ามารับ</div>
                            </div>
                        </template>
                    </div>
                    @endif
                </div>

                {{-- VAT Invoice --}}
                <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;" x-data="{ needTax: false }">
                    <div class="form-check" style="margin-bottom:0;">
                        <input type="checkbox" name="needs_tax_invoice" id="needs_tax" value="1" @change="needTax = $event.target.checked">
                        <label for="needs_tax" style="font-weight:700;font-size:15px;cursor:pointer;"><i class="bi bi-receipt-cutoff"></i> ต้องการใบกำกับภาษีเต็มรูปแบบ</label>
                    </div>
                    <div x-show="needTax" style="margin-top:16px;" x-cloak>
                        <div class="form-grid">
                            <div class="form-group"><label class="form-label">ชื่อบริษัท/ห้าง</label><input type="text" name="tax_company_name" class="form-control"></div>
                            <div class="form-group"><label class="form-label">เลขผู้เสียภาษี 13 หลัก</label><input type="text" name="tax_id" class="form-control" maxlength="13"></div>
                            <div class="form-group"><label class="form-label">สาขา</label><input type="text" name="tax_branch" class="form-control" placeholder="สำนักงานใหญ่ / 00000X"></div>
                            <div class="form-group"><label class="form-label">ที่อยู่สำหรับออกใบกำกับ</label><input type="text" name="tax_address" class="form-control"></div>
                        </div>
                    </div>
                </div>

                {{-- Note --}}
                <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label"><i class="bi bi-chat-left-text"></i> หมายเหตุ (ไม่บังคับ)</label>
                        <textarea name="customer_note" class="form-control" rows="2" placeholder="ต้องการแจ้งอะไรเพิ่มเติม..."></textarea>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="checkout-summary">
                <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                    <h3 style="font-size:17px;font-weight:800;color:var(--kgm-green-800);margin-bottom:16px;"><i class="bi bi-receipt"></i> สรุปออเดอร์</h3>
                    @foreach($cartItems as $item)
                    <div style="display:flex;gap:10px;margin-bottom:10px;">
                        <img src="{{ $item->product->main_image ? asset('storage/'.$item->product->main_image) : '' }}" style="width:48px;height:48px;object-fit:cover;border-radius:10px;">
                        <div style="flex:1;font-size:13px;">
                            <div style="font-weight:600;">{{ $item->product->name }}</div>
                            @if($item->variant)<div style="color:#888;">{{ $item->variant->label }}</div>@endif
                            <div style="color:#888;">x{{ $item->quantity }}</div>
                        </div>
                        <div style="font-weight:700;font-size:13px;">฿{{ number_format($item->subtotal, 0) }}</div>
                    </div>
                    @endforeach
                    <div style="border-top:2px solid #f0f2f0;margin-top:12px;padding-top:12px;">
                        <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:6px;"><span>ยอดสินค้า</span><span>฿{{ number_format($subtotal, 2) }}</span></div>
                        @if($discountAmount > 0)<div style="display:flex;justify-content:space-between;font-size:14px;color:#e74c3c;margin-bottom:6px;"><span>ส่วนลด</span><span>-฿{{ number_format($discountAmount, 2) }}</span></div>@endif
                        <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:6px;"><span>ค่าจัดส่ง</span><span x-text="shipDisplay > 0 ? '฿'+fmt(shipDisplay) : 'ฟรี'">{{ $shippingFee > 0 ? '฿'.number_format($shippingFee,2) : 'ฟรี' }}</span></div>
                        <div style="display:flex;justify-content:space-between;font-size:18px;font-weight:800;color:var(--kgm-green-700);border-top:2px solid #e8ecef;padding-top:12px;margin-top:6px;"><span>ยอดรวม</span><span x-text="'฿'+fmt(grandTotal)">฿{{ number_format($total, 2) }}</span></div>
                    </div>
                    <div style="background:#fef9ec;border-radius:14px;padding:14px;margin-top:16px;text-align:center;">
                        <div style="font-size:13px;color:#888;margin-bottom:8px;font-weight:700;"><i class="bi bi-qr-code"></i> ชำระผ่าน PromptPay / โอนเงิน</div>
                        <div style="font-size:12px;color:#666;">หลังสั่งซื้อ กรุณาอัปโหลดสลิปการโอนเงินในหน้าออเดอร์</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-full btn-lg" style="justify-content:center;margin-top:16px;">
                        <i class="bi bi-bag-check"></i> ยืนยันคำสั่งซื้อ <span x-text="'฿'+fmt(grandTotal, 0)">฿{{ number_format($total, 0) }}</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
@push('scripts')
<script>
function checkoutForm() {
    return {
        newAddr: false,
        selectedAddr: null,
        delivery: 'ship',
        pickupId: {{ $pickupShowrooms->first()?->id ?? 'null' }},
        showrooms: @json($pickupShowrooms->keyBy('id')->map(fn($s) => ['name' => $s->name, 'phone' => $s->phone, 'open_hours' => $s->open_hours])),
        get pickupShop() { return this.showrooms[this.pickupId] || null; },
        hasSavedAddr: {{ $addresses->isNotEmpty() ? 'true' : 'false' }},
        subtotalAfterDiscount: {{ $subtotal - $discountAmount }},
        baseShipping: {{ $shippingFee }},
        get shipDisplay() { return this.delivery === 'pickup' ? 0 : this.baseShipping; },
        get grandTotal() { return this.subtotalAfterDiscount + this.shipDisplay; },
        fmt(n, dec = 2) { return Number(n).toLocaleString('th-TH', { minimumFractionDigits: dec, maximumFractionDigits: dec }); },
        form: {
            ship_name: '{{ old('ship_name', $defaultAddress?->recipient_name ?? auth()->user()->name) }}',
            ship_phone: '{{ old('ship_phone', $defaultAddress?->phone ?? auth()->user()->phone ?? '') }}',
            ship_address: '{{ old('ship_address', $defaultAddress?->address_line1 ?? '') }}',
            ship_district: '{{ old('ship_district', $defaultAddress?->district ?? '') }}',
            ship_amphoe: '{{ old('ship_amphoe', $defaultAddress?->amphoe ?? '') }}',
            ship_province: '{{ old('ship_province', $defaultAddress?->province ?? '') }}',
            ship_postcode: '{{ old('ship_postcode', $defaultAddress?->postcode ?? '') }}',
        },
        fillAddress(id, name, phone, addr, dist, amp, prov, post) {
            this.selectedAddr = id;
            Object.assign(this.form, { ship_name:name, ship_phone:phone, ship_address:addr, ship_district:dist, ship_amphoe:amp, ship_province:prov, ship_postcode:post });
        }
    };
}
</script>
@endpush
