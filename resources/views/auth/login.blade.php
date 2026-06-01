@extends('layouts.app')
@section('title', 'เข้าสู่ระบบ')
@section('content')
<div style="min-height:75vh;display:flex;align-items:center;justify-content:center;padding:32px 16px;">
    <div style="background:white;border-radius:24px;padding:clamp(28px,5vw,44px);width:100%;max-width:440px;box-shadow:0 8px 40px rgba(0,0,0,0.1);">

        {{-- Header --}}
        <div style="text-align:center;margin-bottom:28px;">
            <div style="width:58px;height:58px;background:linear-gradient(135deg,var(--kgm-gold-500),var(--kgm-gold-300));border-radius:16px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:26px;color:var(--kgm-green-900);margin:0 auto 14px;">K</div>
            <h1 style="font-size:24px;font-weight:800;color:var(--kgm-green-800);margin:0 0 4px;">เข้าสู่ระบบ</h1>
            <p style="color:#888;font-size:14px;margin:0;">ยินดีต้อนรับกลับมา</p>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label class="form-label"><i class="bi bi-envelope"></i> อีเมล</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email') }}" required autofocus autocomplete="email">
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
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
