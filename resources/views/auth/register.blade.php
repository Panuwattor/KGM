@extends('layouts.app')
@section('title', 'สมัครสมาชิก')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
.otp-input {
    letter-spacing: 10px;
    font-size: 26px;
    text-align: center;
    font-weight: 800;
    padding: 12px;
}
</style>
@endpush
@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:40px 0;">
    <div style="background:white;border-radius:24px;padding:40px;width:100%;max-width:480px;box-shadow:0 8px 40px rgba(0,0,0,0.1);">
        <div style="text-align:center;margin-bottom:14px;">
            <img src="{{ asset('images/kgm_logo.png') }}" alt="KGM" style="width:100px;object-fit:contain;margin:0 auto 8px;display:block;">
            <h1 style="font-size:26px;font-weight:800;color:var(--kgm-green-800);">สมัครสมาชิก</h1>
            <p style="color:#888;font-size:14px;">สร้างบัญชีใหม่กับ KGM</p>
        </div>

        <form method="POST" action="{{ route('register') }}" x-data="registerForm()">
            @csrf

            {{-- ขั้นตอนที่ 1: ข้อมูลพื้นฐาน --}}
            <div class="form-group">
                <label class="form-label"><i class="bi bi-person"></i> ชื่อ-นามสกุล *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                       value="{{ old('name') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="bi bi-envelope"></i> อีเมล <span style="color:#aaa;font-size:12px;font-weight:400;">(ไม่จำเป็น)</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                       value="{{ old('email') }}" placeholder="example@email.com">
            </div>

            <div class="form-group">
                <label class="form-label"><i class="bi bi-lock"></i> รหัสผ่าน *</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            </div>

            <div class="form-group">
                <label class="form-label"><i class="bi bi-lock-fill"></i> ยืนยันรหัสผ่าน *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>

            <hr style="border:none;border-top:1px dashed #e0e0e0;margin:20px 0;">

            {{-- ขั้นตอนที่ 2: ยืนยันเบอร์โทร --}}
            <p style="font-size:13px;color:#888;margin-bottom:12px;text-align:center;">
                <i class="bi bi-telephone-fill" style="color:var(--kgm-green-600);"></i>
                ขั้นตอนสุดท้าย — ยืนยันเบอร์โทรศัพท์ด้วย OTP
            </p>

            <div class="form-group">
                <label class="form-label"><i class="bi bi-telephone"></i> เบอร์โทรศัพท์ *</label>
                <div style="display:flex;gap:8px;">
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           x-model="phone" :readonly="otpSent"
                           value="{{ old('phone') }}" required placeholder="เช่น 0812345678"
                           style="flex:1;">
                    <button type="button" @click="sendOtp()"
                            :disabled="sending || countdown > 0"
                            class="btn btn-outline"
                            style="white-space:nowrap;flex-shrink:0;padding:0 16px;">
                        <span x-show="!sending && countdown === 0" x-cloak>
                            <i class="bi bi-send"></i> <span x-text="otpSent ? 'ส่งอีกครั้ง' : 'ขอ OTP'"></span>
                        </span>
                        <span x-show="sending" x-cloak>
                            <i class="bi bi-arrow-repeat"></i>
                        </span>
                        <span x-show="!sending && countdown > 0" x-cloak x-text="countdown + 'วิ'"></span>
                    </button>
                </div>
            </div>

            {{-- OTP input + ปุ่มสมัคร โผล่หลังขอ OTP เท่านั้น --}}
            <template x-if="otpSent">
                <div>
                    <div class="form-group"
                         style="background:var(--kgm-green-100);border-radius:16px;padding:16px;">
                        <label class="form-label" style="color:var(--kgm-green-800);">
                            <i class="bi bi-shield-check"></i> รหัส OTP *
                        </label>
                        <input type="text" name="otp" class="form-control otp-input @error('otp') is-invalid @enderror"
                               x-model="otp" maxlength="6" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code"
                               value="{{ old('otp') }}" placeholder="——————">
                        <div style="font-size:12px;margin-top:8px;color:var(--kgm-green-700);"
                             x-show="otpMessage" x-text="otpMessage" x-cloak></div>
                        @error('otp')
                        <div style="color:#e74c3c;font-size:13px;margin-top:6px;"><i class="bi bi-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                        <div style="text-align:right;margin-top:8px;">
                            <button type="button" @click="sendOtp()" :disabled="sending || countdown > 0"
                                    style="background:none;border:none;color:var(--kgm-green-600);cursor:pointer;font-size:13px;font-weight:600;">
                                <i class="bi bi-arrow-clockwise"></i>
                                <span x-show="countdown === 0" x-cloak>ส่งรหัสอีกครั้ง</span>
                                <span x-show="countdown > 0" x-cloak x-text="'รออีก ' + countdown + ' วิ'"></span>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full btn-lg" style="justify-content:center;">
                        <i class="bi bi-person-plus"></i> สมัครสมาชิก
                    </button>
                </div>
            </template>

        </form>

        <div style="text-align:center;margin-top:20px;font-size:14px;color:#888;">
            มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}" style="color:var(--kgm-green-600);font-weight:700;">เข้าสู่ระบบ</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function registerForm() {
    return {
        phone:    '{{ old('phone', '') }}',
        otp:      '{{ old('otp', '') }}',
        otpSent:  {{ session('register_otp') ? 'true' : 'false' }},
        sending:  false,
        countdown: 0,
        otpMessage: '',
        _timer: null,

        sendOtp() {
            const phone = this.phone.trim();
            if (!phone) {
                Swal.fire({ icon: 'warning', title: 'กรุณากรอกเบอร์โทร', confirmButtonColor: '#2d7d32' });
                return;
            }
            this.sending = true;
            this.otpMessage = '';

            fetch('{{ route('otp.send') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({ phone }),
            })
            .then(r => r.json())
            .then(data => {
                this.sending = false;
                if (data.ok) {
                    this.otpSent = true;
                    this.otpMessage = data.message;
                    this.startCountdown(60);
                } else {
                    Swal.fire({ icon: 'error', title: 'ส่ง OTP ไม่ได้', text: data.message, confirmButtonColor: '#2d7d32' });
                }
            })
            .catch(() => {
                this.sending = false;
                Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ไม่สามารถส่ง OTP ได้ กรุณาลองใหม่', confirmButtonColor: '#2d7d32' });
            });
        },

        startCountdown(sec) {
            this.countdown = sec;
            clearInterval(this._timer);
            this._timer = setInterval(() => {
                this.countdown--;
                if (this.countdown <= 0) clearInterval(this._timer);
            }, 1000);
        },
    };
}
</script>
@if($errors->any())
<script>
(function () {
    const msgs = @json($errors->all());
    const list = msgs.map(m => `<li>${m}</li>`).join('');
    Swal.fire({
        icon: 'error',
        title: 'กรุณาตรวจสอบข้อมูล',
        html: `<ul style="text-align:left;margin:8px 0 0;padding-left:20px;line-height:2;">${list}</ul>`,
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#2d7d32',
    });
})();
</script>
@endif
@endpush
