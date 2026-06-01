@extends('layouts.app')
@section('title', 'สาขา/โชว์รูม')
@section('content')
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="container"><h1 style="font-size:40px;font-weight:800;color:white;">สาขา/โชว์รูม</h1></div>
</div>
<div class="container" style="padding:64px 24px;">
    @if($showrooms->isEmpty())
    <p style="text-align:center;color:#aaa;">กำลังอัปเดตข้อมูลสาขา</p>
    @else
    <div class="grid grid-2">
        @foreach($showrooms as $showroom)
        <div style="background:white;border-radius:24px;overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
            @if($showroom->image)
            <div style="height:200px;overflow:hidden;"><img src="{{ asset('storage/'.$showroom->image) }}" style="width:100%;height:100%;object-fit:cover;" alt="{{ $showroom->name }}"></div>
            @else
            <div style="height:160px;background:linear-gradient(135deg,var(--kgm-green-700),var(--kgm-green-500));display:flex;align-items:center;justify-content:center;"><i class="bi bi-shop" style="font-size:48px;color:rgba(255,255,255,0.5);"></i></div>
            @endif
            <div style="padding:24px;">
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <h3 style="font-size:18px;font-weight:800;color:var(--kgm-green-800);">{{ $showroom->name }}</h3>
                    @if($showroom->is_main)<span class="badge badge-hot">สาขาหลัก</span>@endif
                </div>
                <div style="font-size:14px;color:#555;line-height:1.8;">
                    <div><i class="bi bi-geo-alt" style="color:var(--kgm-green-500);"></i> {{ $showroom->address }}</div>
                    @if($showroom->phone)<div><i class="bi bi-telephone" style="color:var(--kgm-green-500);"></i> {{ $showroom->phone }}</div>@endif
                    @if($showroom->open_hours)<div><i class="bi bi-clock" style="color:var(--kgm-green-500);"></i> {{ $showroom->open_hours }}</div>@endif
                </div>
                @if($showroom->map_embed_url)
                <div style="margin-top:16px;border-radius:14px;overflow:hidden;">
                    {!! $showroom->map_embed_url !!}
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection
