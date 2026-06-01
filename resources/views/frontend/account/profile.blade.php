@extends('layouts.app')
@section('title', 'ข้อมูลส่วนตัว')
@section('content')
<div class="container" style="padding:32px 0 64px;max-width:700px;">
    <h1 style="font-size:24px;font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;"><i class="bi bi-person-circle"></i> ข้อมูลส่วนตัว</h1>
    <div style="background:white;border-radius:20px;padding:32px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
        <form method="POST" action="{{ route('account.profile.update') }}">
            @csrf
            <div class="form-group"><label class="form-label">ชื่อ-นามสกุล *</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required></div>
            <div class="form-group"><label class="form-label">อีเมล</label><input type="email" class="form-control" value="{{ $user->email }}" disabled style="opacity:0.7;"></div>
            <div class="form-group"><label class="form-label">เบอร์โทร</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}"></div>
            <div class="form-group"><label class="form-label">วันเกิด</label><input type="date" name="birthdate" class="form-control" value="{{ old('birthdate', $user->birthdate?->format('Y-m-d')) }}"></div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>
</div>
@endsection
