{{-- ช่องทางการชำระเงิน (ใช้ซ้ำได้) | $payAmount = ยอดที่ต้องโอน (optional), $payRef = อ้างอิง (optional) --}}
@php
    $ppBiller  = config('payment.promptpay_biller_id');
    $ppName    = config('payment.promptpay_name');
    $ppAmount  = isset($payAmount) ? (float) $payAmount : null;
    $ppRef     = $payRef ?? null;
    $qrPng     = promptpay_qr_png($ppBiller, $ppAmount, $ppRef);
    $qrPoster  = promptpay_qr_poster($ppBiller, $ppAmount, $ppRef);
    $bankAcc   = config('payment.bank_account');
    $bankAccDigits = preg_replace('/[^0-9]/', '', $bankAcc);
@endphp
<div x-data="{ method: 'qr', copied: false, copy(n) { navigator.clipboard.writeText(n); this.copied = true; setTimeout(() => this.copied = false, 1500); } }">

    {{-- ปุ่มเลือกวิธีชำระ (segmented control) --}}
    <div style="display:flex;gap:6px;background:#eef2ef;border:1px solid #e2e8e3;border-radius:999px;padding:5px;margin-bottom:18px;">
        <button type="button" @click="method = 'qr'"
            :class="method === 'qr' ? 'btn-primary' : ''"
            :style="method === 'qr' ? '' : 'background:transparent;color:#7a8a7f;box-shadow:none;'"
            class="btn" style="flex:1;border:none;border-radius:999px;padding:9px 8px;font-weight:700;font-size:clamp(12px,3.4vw,14px);white-space:nowrap;display:flex;align-items:center;justify-content:center;gap:6px;transition:.18s;">
            <i class="bi bi-qr-code"></i> QR พร้อมเพย์
        </button>
        <button type="button" @click="method = 'bank'"
            :class="method === 'bank' ? 'btn-primary' : ''"
            :style="method === 'bank' ? '' : 'background:transparent;color:#7a8a7f;box-shadow:none;'"
            class="btn" style="flex:1;border:none;border-radius:999px;padding:9px 8px;font-weight:700;font-size:clamp(12px,3.4vw,14px);white-space:nowrap;display:flex;align-items:center;justify-content:center;gap:6px;transition:.18s;">
            <i class="bi bi-bank"></i> โอนเข้าบัญชี
        </button>
    </div>

    {{-- ===== แท็บ: QR พร้อมเพย์ ===== --}}
    <div x-show="method === 'qr'" x-cloak style="text-align:center;">
        <div style="margin-bottom:12px;">
            <img src="{{ asset('images/promt-pay-logo.jpg') }}" alt="PromptPay" style="height:46px;width:auto;display:inline-block;">
        </div>
        <div style="display:inline-block;background:white;border:2px solid var(--kgm-green-100);border-radius:16px;padding:12px;">
            <img src="{{ $qrPng }}" alt="PromptPay QR" style="width:220px;height:220px;display:block;">
        </div>
        <div style="font-size:13px;color:#555;margin-top:8px;line-height:1.6;">
            {{ $ppName }}
            @isset($payAmount)
            <br><span style="font-weight:800;color:var(--kgm-green-700);">ยอด ฿{{ number_format($payAmount, 2) }}</span> จะแสดงในแอปธนาคารอัตโนมัติ
            @endisset
        </div>
        <a href="{{ $qrPoster }}" download="promptpay-qr{{ $ppRef ? '-'.$ppRef : '' }}.png"
           class="btn btn-light" style="margin-top:12px;font-size:14px;padding:8px 18px;">
            <i class="bi bi-download"></i> บันทึกรูป QR
        </a>
        <div style="font-size:12px;color:#999;margin-top:6px;">บันทึกแล้วเปิดแอปธนาคาร → สแกนจากอัลบั้มรูปได้</div>
    </div>

    {{-- ===== แท็บ: โอนเข้าบัญชี ===== --}}
    <div x-show="method === 'bank'" x-cloak>
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:14px;">
            <img src="{{ asset('images/scb.png') }}" alt="SCB" style="width:44px;height:44px;border-radius:12px;object-fit:cover;flex-shrink:0;">
            <div>
                <div style="font-weight:800;color:var(--kgm-green-800);">{{ config('payment.bank_name') }}</div>
                <div style="font-size:12px;color:#888;">{{ config('payment.bank_branch') }}</div>
            </div>
        </div>

        <div style="background:#f6f8f7;border-radius:12px;padding:14px 16px;">
            <div style="font-size:13px;color:#888;">ชื่อบัญชี</div>
            <div style="font-weight:700;color:#333;margin-bottom:10px;">{{ config('payment.account_name') }}</div>

            <div style="font-size:13px;color:#888;">เลขที่บัญชี</div>
            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <span style="font-size:clamp(16px,4.5vw,20px);font-weight:800;color:var(--kgm-green-700);white-space:nowrap;">{{ $bankAcc }}</span>
                <button type="button" @click="copy('{{ $bankAccDigits }}')" class="btn btn-sm btn-light" style="padding:4px 10px;font-size:12px;white-space:nowrap;">
                    <i class="bi" :class="copied ? 'bi-check-lg' : 'bi-clipboard'"></i>
                    <span x-text="copied ? 'คัดลอกแล้ว' : 'คัดลอก'"></span>
                </button>
            </div>
        </div>
    </div>

    {{-- ยอดที่ต้องโอน (แสดงทั้งสองแท็บ) --}}
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
