@extends('layouts.app')
@section('title', 'สมัครสมาชิก')
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
@section('content')
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:40px 0;">
    <div style="background:white;border-radius:24px;padding:40px;width:100%;max-width:480px;box-shadow:0 8px 40px rgba(0,0,0,0.1);">
        <div style="text-align:center;margin-bottom:14px;">
            <img src="{{ asset('images/kgm_logo.png') }}" alt="KGM" style="width:100px;object-fit:contain;margin:0 auto 8px;display:block;">
            <h1 style="font-size:26px;font-weight:800;color:var(--kgm-green-800);">สมัครสมาชิก</h1>
            <p style="color:#888;font-size:14px;">สร้างบัญชีใหม่กับ KGM</p>
        </div>
        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="form-group">
                <label class="form-label"><i class="bi bi-person"></i> ชื่อ-นามสกุล *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-telephone"></i> เบอร์โทรศัพท์ *</label>
                <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required placeholder="เช่น 0812345678">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-envelope"></i> อีเมล <span style="color:#aaa;font-size:12px;font-weight:400;">(ไม่จำเป็น)</span></label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="example@email.com">
            </div>
            <div class="form-group">
                <label class="form-label"><i class="bi bi-lock"></i> รหัสผ่าน *</label>
                <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
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
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
