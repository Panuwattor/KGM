@extends('layouts.app')
@section('title', 'ข่าวสารและบทความ')
@section('content')
<div class="container" style="padding:40px 24px 64px;">
    <div style="display:grid;grid-template-columns:3fr 1fr;gap:28px;">
        <div>
            <h1 style="font-size:28px;font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;"><i class="bi bi-newspaper"></i> ข่าวสารและบทความ</h1>
            <div class="grid grid-2">
                @forelse($posts as $post)
                <div class="card">
                    @if($post->featured_image)<div style="height:180px;overflow:hidden;"><img src="{{ asset('storage/'.$post->featured_image) }}" style="width:100%;height:100%;object-fit:cover;"></div>@endif
                    <div class="card-body">
                        <div style="font-size:12px;color:var(--kgm-green-500);font-weight:600;margin-bottom:6px;">{{ $post->postCategory?->name }}</div>
                        <h3 style="font-weight:800;font-size:16px;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;margin-bottom:8px;">{{ $post->title }}</h3>
                        <p style="font-size:13px;color:#666;overflow:hidden;display:-webkit-box;-webkit-line-clamp:3;-webkit-box-orient:vertical;margin-bottom:12px;">{{ $post->excerpt }}</p>
                        <a href="{{ route('news.show', $post->slug) }}" class="btn btn-sm btn-outline"><i class="bi bi-arrow-right"></i> อ่านต่อ</a>
                    </div>
                </div>
                @empty
                <div style="grid-column:1/-1;text-align:center;padding:48px;color:#aaa;">ยังไม่มีบทความ</div>
                @endforelse
            </div>
            <div class="pagination">{{ $posts->links() }}</div>
        </div>
        <div>
            <div style="background:white;border-radius:20px;padding:20px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
                <h3 style="font-weight:800;margin-bottom:12px;font-size:15px;color:var(--kgm-green-800);">หมวดหมู่</h3>
                @foreach($categories as $cat)
                <a href="{{ route('news', ['category'=>$cat->slug]) }}" style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid #f5f7f5;font-size:14px;color:#555;text-decoration:none;">
                    {{ $cat->name }}<span style="background:#f0f4f0;border-radius:999px;padding:2px 8px;font-size:12px;">{{ $cat->posts_count }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
