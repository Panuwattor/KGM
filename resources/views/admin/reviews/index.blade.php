@extends('layouts.admin')
@section('title', 'รีวิวสินค้า')
@section('content')

<div class="page-header">
    <div>
        <div class="page-title">รีวิวสินค้า</div>
        <div class="page-subtitle">{{ $reviews->total() }} รายการ</div>
    </div>
</div>

@if(session('success'))
<div class="alert-success" style="background:#d1fae5;color:#065f46;padding:10px 16px;border-radius:8px;margin-bottom:16px;">
    <i class="bi bi-check-circle"></i> {{ session('success') }}
</div>
@endif

{{-- Status Tabs --}}
<div style="display:flex;gap:8px;margin-bottom:20px;flex-wrap:wrap;">
    <a href="{{ route('admin.reviews.index', request()->except('status')) }}"
       class="btn btn-sm {{ !request('status') ? 'btn-primary' : 'btn-light' }}">
        ทั้งหมด
    </a>
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'), ['status'=>'pending'])) }}"
       class="btn btn-sm {{ request('status')==='pending' ? 'btn-primary' : 'btn-light' }}">
        รอตรวจสอบ
        @if($statusCounts['pending'] > 0)
        <span style="background:rgba(0,0,0,0.15);border-radius:999px;padding:1px 6px;font-size:11px;margin-left:4px;">{{ $statusCounts['pending'] }}</span>
        @endif
    </a>
    <a href="{{ route('admin.reviews.index', array_merge(request()->except('status'), ['status'=>'approved'])) }}"
       class="btn btn-sm {{ request('status')==='approved' ? 'btn-primary' : 'btn-light' }}">
        อนุมัติแล้ว
        <span style="background:rgba(0,0,0,0.15);border-radius:999px;padding:1px 6px;font-size:11px;margin-left:4px;">{{ $statusCounts['approved'] }}</span>
    </a>
</div>

{{-- Filter Bar --}}
<form method="GET" class="filter-bar">
    <div class="filter-item" style="flex:2;">
        <label>ค้นหา</label>
        <input type="text" name="search" class="form-control" placeholder="ชื่อสินค้า หรือชื่อลูกค้า" value="{{ request('search') }}">
    </div>
    <div class="filter-item">
        <label>คะแนน</label>
        <select name="rating" class="form-control">
            <option value="">ทุกคะแนน</option>
            @for($i=5;$i>=1;$i--)
            <option value="{{ $i }}" @selected(request('rating')==$i)>{{ $i }} ดาว</option>
            @endfor
        </select>
    </div>
    <input type="hidden" name="status" value="{{ request('status') }}">
    <button type="submit" class="btn btn-primary"><i class="bi bi-search"></i> ค้นหา</button>
    @if(request()->hasAny(['search','rating']))
    <a href="{{ route('admin.reviews.index', request()->only('status')) }}" class="btn btn-light">ล้าง</a>
    @endif
</form>

<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>สินค้า</th>
                <th>ลูกค้า</th>
                <th style="text-align:center;width:120px;">คะแนน</th>
                <th>รีวิว</th>
                <th style="text-align:center;width:120px;">สถานะ</th>
                <th style="width:110px;">วันที่</th>
                <th style="width:130px;"></th>
            </tr>
        </thead>
        <tbody>
        @forelse($reviews as $review)
        <tr>
            <td>
                <a href="{{ route('shop.show', $review->product->slug) }}" target="_blank"
                   style="font-weight:600;color:var(--g700);font-size:13px;text-decoration:none;">
                    {{ Str::limit($review->product->name, 32) }}
                </a>
            </td>
            <td>
                <div style="font-weight:600;font-size:13px;">{{ $review->customer->name ?? '-' }}</div>
                <div style="font-size:11px;color:#888;">{{ $review->customer->email ?? '' }}</div>
            </td>
            <td style="text-align:center;">
                <div style="color:var(--kgm-gold-400,#f59e0b);font-size:14px;line-height:1;">
                    @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$review->rating?'-fill':'' }}"></i>@endfor
                </div>
                <div style="font-size:11px;color:#888;margin-top:2px;">{{ $review->rating }}/5</div>
            </td>
            <td style="max-width:280px;">
                @if($review->title)
                <div style="font-weight:600;font-size:13px;margin-bottom:2px;">{{ $review->title }}</div>
                @endif
                @if($review->body)
                <p style="font-size:12px;color:#555;margin:0;line-height:1.5;">{{ Str::limit($review->body, 90) }}</p>
                @else
                <span style="font-size:12px;color:#aaa;">—</span>
                @endif
            </td>
            <td style="text-align:center;">
                @if($review->is_approved)
                <span class="status-badge status-green">อนุมัติแล้ว</span>
                @else
                <span class="status-badge status-yellow">รอตรวจสอบ</span>
                @endif
            </td>
            <td style="font-size:12px;color:#888;">{{ $review->created_at->format('d/m/y') }}<br>{{ $review->created_at->format('H:i') }}</td>
            <td>
                <div style="display:flex;gap:6px;">
                    @if(!$review->is_approved)
                    <form method="POST" action="{{ route('admin.reviews.approve', $review) }}">@csrf
                        <button class="btn btn-sm btn-success" title="อนุมัติ"><i class="bi bi-check-lg"></i></button>
                    </form>
                    @else
                    <form method="POST" action="{{ route('admin.reviews.reject', $review) }}">@csrf
                        <button class="btn btn-sm btn-light" title="ปฏิเสธ / ซ่อน"><i class="bi bi-eye-slash"></i></button>
                    </form>
                    @endif
                    <button class="btn btn-sm btn-light" title="แก้ไข"
                        onclick="openEdit({{ $review->id }}, {{ $review->rating }}, @js($review->title), @js($review->body))">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" onsubmit="return confirm('ลบรีวิวนี้?')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-danger" title="ลบ"><i class="bi bi-trash"></i></button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:48px;color:#aaa;">ไม่พบรีวิว</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px 20px;">{{ $reviews->links() }}</div>
</div>

{{-- Edit Modal --}}
<div id="edit-modal" style="display:none;">
    <div id="edit-backdrop" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9998;" onclick="closeEdit()"></div>
    <div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:9999;background:white;border-radius:20px;width:90%;max-width:500px;padding:28px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.25);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 style="font-size:16px;font-weight:700;color:#1a3a2a;margin:0;">แก้ไขรีวิว</h3>
            <button type="button" onclick="closeEdit()" style="background:none;border:none;font-size:20px;cursor:pointer;color:#888;line-height:1;"><i class="bi bi-x-lg"></i></button>
        </div>
        <form id="edit-form" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label class="form-label">คะแนน</label>
                <div id="edit-stars" style="display:flex;gap:8px;font-size:28px;">
                    @for($i=1;$i<=5;$i++)
                    <i class="bi bi-star-fill" data-val="{{ $i }}" style="cursor:pointer;color:#d1d5db;"
                       onclick="setEditRating({{ $i }})"
                       onmouseenter="hoverRating({{ $i }})"
                       onmouseleave="hoverRating(0)"></i>
                    @endfor
                </div>
                <input type="hidden" name="rating" id="edit-rating" required>
            </div>
            <div class="form-group">
                <label class="form-label">หัวข้อรีวิว</label>
                <input type="text" name="title" id="edit-title" class="form-control" placeholder="ไม่บังคับ">
            </div>
            <div class="form-group">
                <label class="form-label">รีวิว</label>
                <textarea name="body" id="edit-body" class="form-control" rows="4"></textarea>
            </div>
            <div style="display:flex;gap:10px;justify-content:flex-end;margin-top:8px;">
                <button type="button" class="btn btn-light" onclick="closeEdit()">ยกเลิก</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> บันทึก</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let _pendingRating = 0;

function openEdit(id, rating, title, body) {
    document.getElementById('edit-form').action = '/admin/reviews/' + id;
    document.getElementById('edit-title').value = title ?? '';
    document.getElementById('edit-body').value = body ?? '';
    _pendingRating = 0;
    setEditRating(rating);
    document.getElementById('edit-modal').style.display = 'flex';
}
function closeEdit() {
    document.getElementById('edit-modal').style.display = 'none';
}
function setEditRating(val) {
    _pendingRating = val;
    document.getElementById('edit-rating').value = val;
    paintStars(val);
}
function hoverRating(val) {
    paintStars(val || _pendingRating);
}
function paintStars(val) {
    document.querySelectorAll('#edit-stars i').forEach((el, i) => {
        el.style.color = (i + 1) <= val ? '#f59e0b' : '#d1d5db';
    });
}
document.getElementById('edit-modal').addEventListener('click', e => {
    if (e.target === document.getElementById('edit-modal')) closeEdit();
});
</script>
@endpush

@endsection
