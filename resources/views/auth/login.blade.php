@extends('layouts.app')
@section('title', 'เข้าสู่ระบบ')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
.login-tab-bar {
    display: flex;
    background: #f5f5f5;
    border-radius: 12px;
    padding: 4px;
    margin-bottom: 24px;
    gap: 4px;
}
.login-tab {
    flex: 1;
    padding: 9px 0;
    border: none;
    background: none;
    border-radius: 9px;
    font-size: 14px;
    font-weight: 600;
    color: #999;
    cursor: pointer;
    transition: all .2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    font-family: inherit;
}
.login-tab.active {
    background: white;
    color: var(--kgm-green-700);
    box-shadow: 0 1px 6px rgba(0,0,0,0.10);
}
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
<div style="min-height:75vh;display:flex;align-items:center;justify-content:center;padding:32px 16px;">
    <div style="background:white;border-radius:24px;padding:clamp(28px,5vw,44px);width:100%;max-width:440px;box-shadow:0 8px 40px rgba(0,0,0,0.1);"
         x-data="loginPage()">

        {{-- Header --}}
        <div style="text-align:center;margin-bottom:20px;">
            <img src="{{ asset('images/kgm_logo.png') }}" alt="KGM" style="width:100px;object-fit:contain;margin:0 auto 8px;display:block;">
            <h1 style="font-size:24px;font-weight:800;color:var(--kgm-green-800);margin:0 0 4px;">เข้าสู่ระบบ</h1>
            <p style="color:#888;font-size:14px;margin:0;">ยินดีต้อนรับกลับมา</p>
        </div>

        {{-- Tab bar --}}
        <div class="login-tab-bar">
            <button type="button" class="login-tab" :class="{ active: tab === 'password' }" @click="tab = 'password'">
                <i class="bi bi-lock"></i> รหัสผ่าน
            </button>
            <button type="button" class="login-tab" :class="{ active: tab === 'otp' }" @click="tab = 'otp'">
                <i class="bi bi-phone"></i> OTP
            </button>
        </div>

        {{-- ─── Password Tab ─── --}}
        <div x-show="tab === 'password'" x-cloak>
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label"><i class="bi bi-telephone"></i> เบอร์โทรศัพท์</label>
                    <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror"
                           value="{{ old('phone') }}" required autofocus autocomplete="tel" placeholder="เช่น 0812345678">
                    @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label"><i class="bi bi-lock"></i> รหัสผ่าน</label>
                    <input type="password" name="password" class="form-control" required autocomplete="current-password">
                </div>
                <div class="form-check" style="margin-bottom:22px;">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember" style="font-size:14px;cursor:pointer;">จดจำฉันไว้</label>
                </div>
                <button type="submit" class="btn btn-primary w-full btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ
                </button>
            </form>
        </div>

        {{-- ─── OTP Tab ─── --}}
        <div x-show="tab === 'otp'" x-cloak>
            <form method="POST" action="{{ route('login.otp') }}">
                @csrf

                {{-- เบอร์โทร + ปุ่มส่ง OTP --}}
                <div class="form-group">
                    <label class="form-label"><i class="bi bi-telephone"></i> เบอร์โทรศัพท์</label>
                    <div style="display:flex;gap:8px;">
                        <input type="tel" name="phone" class="form-control @error('otp') is-invalid @enderror"
                               x-model="phone" :readonly="otpSent"
                               value="{{ old('phone') }}" required placeholder="เช่น 0812345678"
                               style="flex:1;">
                        <button type="button" @click="sendOtp()"
                                :disabled="sending || countdown > 0"
                                class="btn btn-outline"
                                style="white-space:nowrap;flex-shrink:0;padding:0 16px;">
                            <span x-show="!sending && countdown === 0" x-cloak>
                                <i class="bi bi-send"></i> ขอ OTP
                            </span>
                            <span x-show="sending" x-cloak>
                                <i class="bi bi-arrow-repeat"></i>
                            </span>
                            <span x-show="!sending && countdown > 0" x-cloak x-text="countdown + 'วิ'"></span>
                        </button>
                    </div>
                </div>

                {{-- OTP input --}}
                <div class="form-group" x-show="otpSent" x-cloak
                     style="background:var(--kgm-green-100);border-radius:16px;padding:16px;">
                    <label class="form-label" style="color:var(--kgm-green-800);">
                        <i class="bi bi-shield-check"></i> รหัส OTP
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

                <button type="submit" :disabled="!otpSent"
                        class="btn btn-primary w-full btn-lg"
                        :style="!otpSent ? 'opacity:.5;cursor:not-allowed;' : ''">
                    <i class="bi bi-box-arrow-in-right"></i> เข้าสู่ระบบ
                </button>

                <p x-show="!otpSent" x-cloak style="text-align:center;font-size:12px;color:#aaa;margin-top:10px;">
                    <i class="bi bi-info-circle"></i> กรอกเบอร์โทรและกดขอ OTP
                </p>
            </form>
        </div>

        {{-- Divider --}}
        <div style="display:flex;align-items:center;gap:12px;margin:22px 0;">
            <div style="flex:1;height:1px;background:#eee;"></div>
            <span style="font-size:13px;color:#bbb;">หรือ</span>
            <div style="flex:1;height:1px;background:#eee;"></div>
        </div>

        {{-- Register CTA --}}
        <a href="{{ route('register') }}" class="btn btn-outline w-full">
            <i class="bi bi-person-plus"></i> สมัครสมาชิกใหม่
        </a>

        <p style="text-align:center;font-size:12px;color:#bbb;margin:16px 0 0;">
            สมัครสมาชิกฟรี ไม่มีค่าใช้จ่าย
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function loginPage() {
    return {
        tab: '{{ $errors->has("otp") || session("login_otp") ? "otp" : "password" }}',
        phone:    '{{ old('phone', '') }}',
        otp:      '{{ old('otp', '') }}',
        otpSent:  {{ session('login_otp') ? 'true' : 'false' }},
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

            fetch('{{ route('login.otp.send') }}', {
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
@if($errors->has('otp'))
<script>
(function () {
    const msgs = @json($errors->get('otp'));
    Swal.fire({
        icon: 'error',
        title: 'OTP ไม่ถูกต้อง',
        text: msgs[0] ?? '',
        confirmButtonText: 'ตกลง',
        confirmButtonColor: '#2d7d32',
    });
})();
</script>
@endif
@endpush
