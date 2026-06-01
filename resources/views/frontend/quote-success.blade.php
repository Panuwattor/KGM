@extends('layouts.app')
@section('title', 'ส่งคำขอใบเสนอราคาแล้ว')
@section('content')
<div class="container" style="padding:80px 24px;max-width:600px;text-align:center;">
    <div style="background:white;border-radius:24px;padding:48px;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <div style="width:80px;height:80px;background:var(--kgm-green-100);border-radius:999px;display:flex;align-items:center;justify-content:center;font-size:36px;color:var(--kgm-green-600);margin:0 auto 20px;"><i class="bi bi-check-circle-fill"></i></div>
        <h1 style="font-size:26px;font-weight:800;color:var(--kgm-green-800);margin-bottom:8px;">ส่งคำขอสำเร็จ!</h1>
        <p style="color:#666;margin-bottom:24px;">เราได้รับคำขอใบเสนอราคาของท่านแล้ว ทีมงานจะติดต่อกลับภายใน 1-2 วันทำการ</p>
        <a href="{{ route('home') }}" class="btn btn-primary"><i class="bi bi-house"></i> กลับหน้าแรก</a>
    </div>
</div>
@endsection
