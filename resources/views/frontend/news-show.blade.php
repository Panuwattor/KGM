@extends('layouts.app')
@section('title', $post->meta_title ?? $post->title)
@section('meta_description', $post->meta_description ?? $post->excerpt)
@push('styles')
<style>
.news-article { background:white;border-radius:20px;padding:36px;box-shadow:0 2px 10px rgba(0,0,0,0.06); }
.news-article h1 { font-size:30px;font-weight:800;color:var(--kgm-green-800);margin-bottom:16px; }
@media(max-width:600px) {
    .news-article { padding:20px 16px; border-radius:14px; }
    .news-article h1 { font-size:22px; }
}
</style>
@endpush
@section('content')
<div class="container" style="padding:28px 16px 64px;max-width:900px;">
    <div class="breadcrumb"><a href="{{ route('home') }}"><i class="bi bi-house"></i></a><span class="breadcrumb-sep">/</span><a href="{{ route('news') }}">ข่าวสาร</a><span class="breadcrumb-sep">/</span><span>{{ Str::limit($post->title,40) }}</span></div>
    <article class="news-article">
        <div style="font-size:13px;color:var(--kgm-green-500);font-weight:700;margin-bottom:8px;">{{ $post->postCategory?->name }}</div>
        <h1>{{ $post->title }}</h1>
        <div style="display:flex;flex-wrap:wrap;gap:12px;font-size:13px;color:#888;margin-bottom:24px;">
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
            <div class="card">
                @if($r->featured_image)
                <div style="height:160px;overflow:hidden;border-radius:14px 14px 0 0;">
                    <img src="{{ asset('storage/'.$r->featured_image) }}" style="width:100%;height:100%;object-fit:cover;" alt="{{ $r->title }}">
                </div>
                @endif
                <div class="card-body">
                    @if($r->postCategory)<div style="font-size:12px;color:var(--kgm-green-500);font-weight:600;margin-bottom:6px;">{{ $r->postCategory->name }}</div>@endif
                    <div style="font-size:15px;font-weight:700;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;margin-bottom:10px;">{{ $r->title }}</div>
                    <a href="{{ route('news.show',$r->slug) }}" style="font-size:13px;color:var(--kgm-green-600);font-weight:600;">อ่านต่อ →</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
