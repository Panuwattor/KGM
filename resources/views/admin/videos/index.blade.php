@extends('layouts.admin')
@section('title', 'เรื่องราวที่น่าสนใจ')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">เรื่องราวที่น่าสนใจ</div>
        <div class="page-subtitle">จัดการคลิปวิดีโอ YouTube</div>
    </div>
    <a href="{{ route('admin.videos.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> เพิ่มวิดีโอ
    </a>
</div>

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
    <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="table-wrap">
    <div class="table-header">
        <h3><i class="bi bi-play-circle me-2" style="color:var(--g500)"></i> รายการวิดีโอทั้งหมด ({{ $videos->total() }})</h3>
    </div>
    <table>
        <thead>
            <tr>
                <th style="width:60px">ลำดับ</th>
                <th>วิดีโอ</th>
                <th>หัวข้อ</th>
                <th style="width:110px">หน้าแรก</th>
                <th style="width:100px">สถานะ</th>
                <th style="width:120px">วันที่เพิ่ม</th>
                <th style="width:100px">จัดการ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($videos as $video)
            <tr @if($video->trashed()) style="opacity:.5" @endif>
                <td style="text-align:center;font-weight:700;color:#888;">{{ $video->sort_order }}</td>
                <td>
                    <a href="https://www.youtube.com/watch?v={{ $video->youtube_id }}" target="_blank" rel="noopener">
                        <img src="{{ $video->thumbnail }}" alt="{{ $video->title }}"
                             style="width:120px;height:68px;object-fit:cover;border-radius:8px;display:block;"
                             onerror="this.src='/images/logo.png'">
                    </a>
                </td>
                <td>
                    <div style="font-weight:600;margin-bottom:4px;">{{ $video->title }}</div>
                    @if($video->description)
                    <div style="font-size:12px;color:#888;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">{{ $video->description }}</div>
                    @endif
                    <div style="font-size:11px;color:#aaa;margin-top:4px;font-family:monospace;">{{ $video->youtube_id }}</div>
                </td>
                <td style="text-align:center;">
                    @if($video->is_featured)
                    <span class="status-badge status-gold" style="background:#fef9ec;color:#8b6914;"><i class="bi bi-star-fill"></i> แสดง</span>
                    @else
                    <span class="status-badge status-gray">-</span>
                    @endif
                </td>
                <td>
                    @if($video->trashed())
                    <span class="status-badge status-red">ลบแล้ว</span>
                    @elseif($video->is_active)
                    <span class="status-badge status-green">เผยแพร่</span>
                    @else
                    <span class="status-badge status-gray">ซ่อน</span>
                    @endif
                </td>
                <td style="font-size:12px;color:#888;">{{ $video->created_at->format('d/m/Y') }}</td>
                <td>
                    @if(!$video->trashed())
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.videos.edit', $video) }}" class="btn btn-sm" style="background:var(--g100);color:var(--g700);padding:5px 10px;">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('admin.videos.destroy', $video) }}" method="POST" onsubmit="return confirm('ลบวิดีโอนี้?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm" style="background:#fee2e2;color:#991b1b;padding:5px 10px;border:none;cursor:pointer;">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center;padding:48px;color:#aaa;">
                    <i class="bi bi-play-circle" style="font-size:48px;display:block;margin-bottom:12px;"></i>
                    ยังไม่มีวิดีโอ คลิก "เพิ่มวิดีโอ" เพื่อเริ่มต้น
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($videos->hasPages())
    <div style="padding:16px 24px;">{{ $videos->links() }}</div>
    @endif
</div>
@endsection
