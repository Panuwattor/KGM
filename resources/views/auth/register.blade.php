@extends('layouts.app')
@section('title', 'สมัครสมาชิก')
@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:40px 0;">
    <div style="background:white;border-radius:24px;padding:40px;width:100%;max-width:480px;box-shadow:0 8px 40px rgba(0,0,0,0.1);">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="width:60px;height:60px;background:linear-gradient(135deg,var(--kgm-gold-500),var(--kgm-gold-300));border-radius:16px;display:flex;align-items:center;justify-content:center;font-weight:900;font-size:26px;color:var(--kgm-green-900);margin:0 auto 14px;">K</div>
            <h1 style="font-size:26px;font-weight:800;color:var(--kgm-green-800);">สมัครสมาชิก</h1>
            <p style="color:#888;font-size:14px;">สร้างบัญชีใหม่กับ KGM</p>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="form-label"><i class="bi bi-person"></i> ชื่อ-นามสกุล *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-envelope"></i> อีเมล *</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-telephone"></i> เบอร์โทรศัพท์</label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-lock"></i> รหัสผ่าน *</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-lock-fill"></i> ยืนยันรหัสผ่าน *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-full btn-lg" style="justify-content:center;"><i class="bi bi-person-plus"></i> สมัครสมาชิก</button>
        </form>
        <div style="text-align:center;margin-top:20px;font-size:14px;color:#888;">
            มีบัญชีอยู่แล้ว? <a href="{{ route('login') }}" style="color:var(--kgm-green-600);font-weight:700;">เข้าสู่ระบบ</a>
        </div>
    </div>
</div>
@endsection
