@extends('layouts.app')
@section('title', 'ข้อมูลส่วนตัว')
@section('content')
<div class="container acc-wrap">
    <div class="acc-layout">
        @include('frontend.account._sidebar')
        <div>
            <div class="acc-card">
                <h2 style="font-size:18px;font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;"><i class="bi bi-person-circle"></i> ข้อมูลส่วนตัว</h2>
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
    </div>
</div>
@endsection
