@extends('layouts.admin')
@section('title', 'บทความ/ข่าวสาร')
@section('content')
<div class="page-header">
    <div class="page-title">บทความและข่าวสาร</div>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> สร้างบทความ</a>
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th>ชื่อบทความ</th><th>หมวดหมู่</th><th>สถานะ</th><th>เผยแพร่</th><th>ยอดวิว</th><th></th></tr></thead>
        <tbody>
        @forelse($posts as $post)
        <tr>
            <td>
                <div style="font-weight:700;display:flex;align-items:center;gap:6px;">
                    @if($post->is_top)<span title="บทความแนะนำ" style="color:#f59e0b;font-size:15px;"><i class="bi bi-pin-angle-fill"></i></span>@endif
                    {{ Str::limit($post->title, 60) }}
                </div>
                @if($post->is_top)<span class="status-badge status-yellow" style="font-size:10px;">Top</span>@endif
                @if($post->trashed())<span class="status-badge status-red" style="font-size:10px;">ถูกลบ</span>@endif
            </td>
            <td style="font-size:13px;">{{ $post->postCategory?->name ?? '-' }}</td>
            <td><span class="status-badge {{ $post->status === 'published' ? 'status-green' : ($post->status === 'scheduled' ? 'status-blue' : 'status-gray') }}">
                {{ ['draft'=>'Draft','published'=>'เผยแพร่','scheduled'=>'ตั้งเวลา'][$post->status] }}
            </span></td>
            <td style="font-size:12px;color:#888;">{{ $post->published_at?->format('d/m/Y') ?? '-' }}</td>
            <td>{{ number_format($post->view_count) }}</td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                @if(!$post->trashed())
                <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" onsubmit="return confirm('ลบ?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีบทความ</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $posts->links() }}</div>
</div>
@endsection
