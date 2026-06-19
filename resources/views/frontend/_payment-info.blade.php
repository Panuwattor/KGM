{{-- ช่องทางการชำระเงิน (ใช้ซ้ำได้) | $payAmount = ยอดที่ต้องโอน (optional) --}}
<div x-data="{ copied: false, copy(n) { navigator.clipboard.writeText(n); this.copied = true; setTimeout(() => this.copied = false, 1500); } }">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
        <img src="{{ asset('images/scb.png') }}" alt="SCB" style="width:44px;height:44px;border-radius:12px;object-fit:cover;flex-shrink:0;">

        <div>
            <div style="font-weight:800;color:var(--kgm-green-800);">ธนาคารไทยพาณิชย์ (SCB)</div>
            <div style="font-size:12px;color:#888;">บัญชีออมทรัพย์ · สาขาศรีสะเกษ</div>
        </div>
    </div>

    <div style="background:#f6f8f7;border-radius:12px;padding:14px 16px;">
        <div style="font-size:13px;color:#888;">ชื่อบัญชี</div>
        <div style="font-weight:700;color:#333;margin-bottom:10px;">บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด</div>

        <div style="font-size:13px;color:#888;">เลขที่บัญชี</div>
        <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
            <span style="font-size:clamp(16px,4.5vw,20px);font-weight:800;color:var(--kgm-green-700);white-space:nowrap;">557-2-29938-9</span>
            <button type="button" @click="copy('5572299389')" class="btn btn-sm btn-light" style="padding:4px 10px;font-size:12px;white-space:nowrap;">
                <i class="bi" :class="copied ? 'bi-check-lg' : 'bi-clipboard'"></i>
                <span x-text="copied ? 'คัดลอกแล้ว' : 'คัดลอก'"></span>
            </button>
        </div>
    </div>

    @isset($payAmount)
    <div style="display:flex;justify-content:space-between;align-items:center;background:var(--kgm-green-100);border-radius:12px;padding:12px 16px;margin-top:12px;">
        <span style="font-weight:700;color:var(--kgm-green-800);font-size:14px;">ยอดที่ต้องโอน</span>
        <span style="font-size:clamp(16px,4.5vw,20px);font-weight:900;color:var(--kgm-green-700);white-space:nowrap;">฿{{ number_format($payAmount, 2) }}</span>
    </div>
    @endisset

    <div style="font-size:13px;color:#a06200;background:#fef9ec;border:1px solid var(--kgm-gold-300);border-radius:12px;padding:12px 14px;margin-top:12px;line-height:1.7;">
        <i class="bi bi-exclamation-circle"></i> โอนเงินแล้วกรุณา<strong>อัปโหลดสลิป</strong>เพื่อยืนยันการชำระเงิน<br>
        <span style="color:#c0392b;"><i class="bi bi-clock"></i> ออเดอร์ที่ไม่แจ้งชำระเงินภายใน 2 วัน จะถูกยกเลิกอัตโนมัติ</span>
    </div>
</div>
