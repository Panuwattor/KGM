@extends('layouts.app')
@section('title', 'ข่าวสารและบทความ')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/news.css') }}">
@endpush
@section('content')

{{-- Hero --}}
<div class="news-hero">
    <div class="container news-hero-inner">
        <div class="news-hero-title"><i class="bi bi-newspaper" style="margin-right:10px;"></i>ข่าวสารและบทความ</div>
        <div class="news-hero-sub">อัปเดตข่าวสาร เทรนด์แฟชั่น และเรื่องราวจากกิจเจริญการ์เมนท์</div>
    </div>
    {{-- Category pills --}}
    <div class="cat-tab-bar">
        <div class="container">
            <div class="cat-pills">
                <a href="{{ route('news') }}"
                   class="cat-pill {{ !request('category') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap-fill"></i> ทั้งหมด
                </a>
                @foreach($categories as $cat)
                <a href="{{ route('news', ['category' => $cat->slug]) }}"
                   class="cat-pill {{ request('category') === $cat->slug ? 'active' : '' }}">
                    {{ $cat->name }}
                    <span style="opacity:.6;font-size:11px;">{{ $cat->posts_count }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Main --}}
<div class="container">
    <div class="news-main">

        <div>
            @php $featured = $posts->first(); $rest = $posts->skip(1); @endphp

            {{-- Featured post (first item only on page 1) --}}
            @if($featured && !request('page') || request('page') == 1)
            @if($featured)
            <a href="{{ route('news.show', $featured->slug) }}" class="featured-card">
                <div class="featured-card-img">
                    @if($featured->featured_image)
                        <img src="{{ media_url($featured->featured_image) }}" alt="{{ $featured->title }}" loading="lazy" onerror="this.src='/images/logo.png'">
                    @else
                        <div style="width:100%;height:100%;background:linear-gradient(135deg,#e8f5e9,#c8e6c9);display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-newspaper" style="font-size:56px;color:#a5d6a7;"></i>
                        </div>
                    @endif
                </div>
                <div class="featured-card-body">
                    @if($featured->postCategory)
                    <span class="post-cat">{{ $featured->postCategory->name }}</span>
                    @endif
                    <h2 style="font-size:20px;font-weight:900;line-height:1.4;margin-bottom:10px;color:#1a2e22;">{{ $featured->title }}</h2>
                    <p style="font-size:14px;color:#777;line-height:1.65;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;margin-bottom:0;">{{ $featured->excerpt }}</p>
                    <div style="display:flex;align-items:center;gap:10px;margin-top:14px;padding-top:12px;border-top:1px solid #f0f2f0;">
                        <span style="font-size:12px;color:#aaa;display:flex;align-items:center;gap:4px;"><i class="bi bi-calendar3"></i> {{ $featured->published_at->locale('th')->translatedFormat('d M Y') }}</span>
                    </div>
                    <span class="read-more">อ่านบทความ <i class="bi bi-arrow-right"></i></span>
                </div>
            </a>
            @endif
            @endif

            {{-- Grid --}}
            @if($posts->isEmpty())
            <div class="news-empty">
                <i class="bi bi-newspaper"></i>
                <div style="font-size:16px;font-weight:600;">ยังไม่มีบทความ</div>
                <div style="font-size:14px;margin-top:6px;">ลองเลือกหมวดหมู่อื่น</div>
            </div>
            @else
            <div class="post-grid">
                @foreach(( (!request('page') || request('page') == 1) ? $rest : $posts ) as $post)
                <a href="{{ route('news.show', $post->slug) }}" class="post-card">
                    <div class="post-card-img">
                        @if($post->featured_image)
                            <img src="{{ media_url($post->featured_image) }}" alt="{{ $post->title }}" loading="lazy" onerror="this.src='/images/logo.png'">
                        @else
                            <div style="width:100%;height:100%;background:linear-gradient(135deg,#e8f5e9,#c8e6c9);display:flex;align-items:center;justify-content:center;">
                                <i class="bi bi-newspaper" style="font-size:36px;color:#a5d6a7;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="post-card-body">
                        @if($post->postCategory)
                        <span class="post-cat">{{ $post->postCategory->name }}</span>
                        @endif
                        <div class="post-title">{{ $post->title }}</div>
                        @if($post->excerpt)
                        <div class="post-excerpt">{{ $post->excerpt }}</div>
                        @endif
                        <div class="post-meta">
                            <span><i class="bi bi-calendar3"></i> {{ $post->published_at->locale('th')->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            @endif

            {{-- Pagination --}}
            @if($posts->hasPages())
            <div style="margin-top:36px;">{{ $posts->links() }}</div>
            @endif
        </div>

    </div>
</div>

@endsection
