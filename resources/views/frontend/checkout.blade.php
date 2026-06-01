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
</style>
@endpush

@section('content')
<div class="container" style="padding-top:32px;padding-bottom:64px;max-width:1100px;">
    <h1 style="font-size:28px;font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;"><i class="bi bi-bag-check"></i> ชำระเงิน</h1>

    <form method="POST" action="{{ route('checkout.store') }}" x-data="checkoutForm()">
        @csrf
        <div style="display:grid;grid-template-columns:3fr 2fr;gap:28px;align-items:start;">
            <div>
                {{-- Address --}}
                <div style="background:white;border-radius:20px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,0.06);margin-bottom:20px;">
                    <h3 style="font-size:17px;font-weight:800;color:var(--kgm-green-800);margin-bottom:20px;"><i class="bi bi-geo-alt"></i> ที่อยู่จัดส่ง</h3>

                    @if($addresses->isNotEmpty())
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
                    <div x-show="newAddr">
                    @endif

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">ชื่อผู้รับ *</label>
                            <input type="text" name="ship_name" class="form-control @error('ship_name') is-invalid @enderror" x-model="form.ship_name" required>
                            @error('ship_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="form-group">
                            <label class="form-label">เบอร์โทรศัพท์ *</label>
                            <input type="text" name="ship_phone" class="form-control @error('ship_phone') is-invalid @enderror" x-model="form.ship_phone" required>
                        </div>
                        <div class="form-group col-span-2">
                            <label class="form-label">ที่อยู่ *</label>
                            <input type="text" name="ship_address" class="form-control" x-model="form.ship_address" placeholder="บ้านเลขที่ ถนน ซอย" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">แขวง/ตำบล *</label>
                            <input type="text" name="ship_district" class="form-control" x-model="form.ship_district" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">เขต/อำเภอ *</label>
                            <input type="text" name="ship_amphoe" class="form-control" x-model="form.ship_amphoe" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">จังหวัด *</label>
                            <input type="text" name="ship_province" class="form-control" x-model="form.ship_province" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">รหัสไปรษณีย์ *</label>
                            <input type="text" name="ship_postcode" class="form-control" x-model="form.ship_postcode" maxlength="5" required>
                        </div>
                    </div>

                    @if($addresses->isNotEmpty())
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
            <div style="position:sticky;top:90px;">
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
                        <div style="display:flex;justify-content:space-between;font-size:14px;margin-bottom:6px;"><span>ค่าจัดส่ง</span><span>{{ $shippingFee > 0 ? '฿'.number_format($shippingFee,2) : 'ฟรี' }}</span></div>
                        <div style="display:flex;justify-content:space-between;font-size:18px;font-weight:800;color:var(--kgm-green-700);border-top:2px solid #e8ecef;padding-top:12px;margin-top:6px;"><span>ยอดรวม</span><span>฿{{ number_format($total, 2) }}</span></div>
                    </div>
                    <div style="background:#fef9ec;border-radius:14px;padding:14px;margin-top:16px;text-align:center;">
                        <div style="font-size:13px;color:#888;margin-bottom:8px;font-weight:700;"><i class="bi bi-qr-code"></i> ชำระผ่าน PromptPay / โอนเงิน</div>
                        <div style="font-size:12px;color:#666;">หลังสั่งซื้อ กรุณาอัปโหลดสลิปการโอนเงินในหน้าออเดอร์</div>
                    </div>
                    <button type="submit" class="btn btn-primary w-full btn-lg" style="justify-content:center;margin-top:16px;">
                        <i class="bi bi-bag-check"></i> ยืนยันคำสั่งซื้อ ฿{{ number_format($total, 0) }}
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
