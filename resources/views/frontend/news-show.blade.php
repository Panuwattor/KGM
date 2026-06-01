@extends('layouts.app')
@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)
@section('content')
<div class="container" style="padding:40px 24px 64px;max-width:900px;">
    <div class="breadcrumb"><a href="{{ route('home') }}"><i class="bi bi-house"></i></a><span class="breadcrumb-sep">/</span><a href="{{ route('news') }}">ข่าวสาร</a><span class="breadcrumb-sep">/</span><span>{{ Str::limit($post->title,40) }}</span></div>
    <article style="background:white;border-radius:20px;padding:36px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
        <div style="font-size:13px;color:var(--kgm-green-500);font-weight:700;margin-bottom:8px;">{{ $post->postCategory?->name }}</div>
        <h1 style="font-size:30px;font-weight:800;color:var(--kgm-green-800);margin-bottom:16px;">{{ $post->title }}</h1>
        <div style="display:flex;gap:16px;font-size:13px;color:#888;margin-bottom:24px;">
            <span><i class="bi bi-calendar3"></i> {{ $post->published_at->format('d/m/Y') }}</span>
            @if($post->author)<span><i class="bi bi-person"></i> {{ $post->author->name }}</span>@endif
        </div>
        @if($post->featured_image)
        <img src="{{ asset('storage/'.$post->featured_image) }}" style="width:100%;border-radius:14px;margin-bottom:28px;" alt="{{ $post->title }}">
        @endif
        <div style="font-size:15px;line-height:1.9;color:#444;">{!! $post->body !!}</div>
    </article>
    @if($related->isNotEmpty())
    <div style="margin-top:36px;">
        <h3 style="font-weight:800;font-size:18px;color:var(--kgm-green-800);margin-bottom:16px;">บทความที่เกี่ยวข้อง</h3>
        <div class="grid grid-3">
            @foreach($related as $r)
            <div class="card"><div class="card-body"><div style="font-size:15px;font-weight:700;">{{ $r->title }}</div><a href="{{ route('news.show',$r->slug) }}" style="font-size:13px;color:var(--kgm-green-600);font-weight:600;">อ่านต่อ →</a></div></div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
