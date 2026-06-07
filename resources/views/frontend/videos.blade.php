@extends('layouts.app')
@section('title', 'เรื่องราวที่น่าสนใจ - กิจเจริญการ์เมนท์')

@push('styles')
<style>
.videos-hero {
    background: linear-gradient(135deg, var(--kgm-green-800) 0%, var(--kgm-green-600) 100%);
    padding: 56px 0 44px;
    text-align: center;
    color: white;
}
.videos-hero h1 { font-size: 32px; font-weight: 800; margin-bottom: 8px; }
.videos-hero p  { font-size: 15px; opacity: .75; }

.videos-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
    padding: 48px 0;
}
@media(max-width:900px) { .videos-grid { grid-template-columns: repeat(2,1fr); gap:16px; } }
@media(max-width:600px) { .videos-grid { grid-template-columns: 1fr; } }

.video-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
    transition: transform .2s, box-shadow .2s;
    cursor: pointer;
}
.video-card:hover { transform: translateY(-4px); box-shadow: 0 8px 32px rgba(0,0,0,.13); }

.video-thumb {
    position: relative;
    padding-bottom: 56.25%;
    background: #111;
    overflow: hidden;
}
.video-thumb img {
    position: absolute; top: 0; left: 0;
    width: 100%; height: 100%; object-fit: cover;
    transition: opacity .3s;
}
.video-play-btn {
    position: absolute; inset: 0;
    display: flex; align-items: center; justify-content: center;
    background: rgba(0,0,0,.3);
    transition: background .2s;
}
.video-card:hover .video-play-btn { background: rgba(0,0,0,.5); }
.play-icon {
    width: 56px; height: 56px;
    background: rgba(255,255,255,.92);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    color: #e30000;
    padding-left: 4px;
    box-shadow: 0 4px 20px rgba(0,0,0,.3);
    transition: transform .2s;
}
.video-card:hover .play-icon { transform: scale(1.1); }

.video-info { padding: 16px; }
.video-info h3 {
    font-size: 15px; font-weight: 700; margin: 0 0 8px;
    overflow: hidden; display: -webkit-box;
    -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    color: #1a2e1a;
    line-height: 1.4;
}
.video-info p {
    font-size: 13px; color: #666; margin: 0;
    overflow: hidden; display: -webkit-box;
    -webkit-line-clamp: 2; -webkit-box-orient: vertical;
    line-height: 1.5;
}

/* Modal */
.yt-modal-overlay {
    display: none;
    position: fixed; inset: 0; z-index: 9999;
    background: rgba(0,0,0,.85);
    align-items: center; justify-content: center;
}
.yt-modal-overlay.open { display: flex; }
.yt-modal-box {
    width: 90%; max-width: 860px;
    background: #000;
    border-radius: 12px;
    overflow: hidden;
    position: relative;
}
.yt-modal-close {
    position: absolute; top: -40px; right: 0;
    background: none; border: none;
    color: white; font-size: 28px; cursor: pointer; line-height: 1;
}
.yt-modal-wrap {
    position: relative; padding-bottom: 56.25%; height: 0;
}
.yt-modal-wrap iframe {
    position: absolute; top: 0; left: 0; width: 100%; height: 100%;
}
</style>
@endpush

@section('content')

<div class="videos-hero">
    <div class="container">
        <h1><i class="bi bi-play-circle-fill" style="color:rgba(255,255,255,.7);margin-right:10px;"></i>เรื่องราวที่น่าสนใจ</h1>
        <p>คลิปวิดีโอแนะนำสินค้า เบื้องหลัง และเรื่องราวจากกิจเจริญการ์เมนท์</p>
    </div>
</div>

<section style="background:#f8faf8;">
    <div class="container">
        @if($videos->isEmpty())
        <div style="text-align:center;padding:80px 0;color:#aaa;">
            <i class="bi bi-play-circle" style="font-size:64px;display:block;margin-bottom:16px;"></i>
            <div style="font-size:18px;font-weight:600;">ยังไม่มีวิดีโอในขณะนี้</div>
            <div style="font-size:14px;margin-top:8px;">กลับมาใหม่เร็ว ๆ นี้</div>
        </div>
        @else
        <div class="videos-grid">
            @foreach($videos as $video)
            <div class="video-card" onclick="openVideo('{{ $video->youtube_id }}')">
                <div class="video-thumb">
                    <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}" loading="lazy"
                         onerror="this.src='/images/logo.png'">
                    <div class="video-play-btn">
                        <div class="play-icon"><i class="bi bi-play-fill"></i></div>
                    </div>
                </div>
                <div class="video-info">
                    <h3>{{ $video->title }}</h3>
                    @if($video->description)
                    <p>{{ $video->description }}</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        @if($videos->hasPages())
        <div style="display:flex;justify-content:center;padding-bottom:40px;">
            {{ $videos->links() }}
        </div>
        @endif
        @endif
    </div>
</section>

{{-- Video Modal --}}
<div class="yt-modal-overlay" id="yt-modal" onclick="closeVideo(event)">
    <div class="yt-modal-box">
        <button class="yt-modal-close" onclick="closeVideo(null, true)">&times;</button>
        <div class="yt-modal-wrap">
            <iframe id="yt-modal-iframe" src="" frameborder="0"
                    allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture"
                    allowfullscreen></iframe>
        </div>
    </div>
</div>

@push('scripts')
<script>
function openVideo(id) {
    document.getElementById('yt-modal-iframe').src = 'https://www.youtube.com/embed/' + id + '?autoplay=1&rel=0';
    document.getElementById('yt-modal').classList.add('open');
    document.body.style.overflow = 'hidden';
}
function closeVideo(e, force) {
    if (force || e.target === document.getElementById('yt-modal')) {
        document.getElementById('yt-modal-iframe').src = '';
        document.getElementById('yt-modal').classList.remove('open');
        document.body.style.overflow = '';
    }
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeVideo(null, true);
});
</script>
@endpush
@endsection
