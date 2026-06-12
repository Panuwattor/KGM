@extends('layouts.admin')
@section('title', 'Landing Page (Popup)')
@section('suppress_alerts', true)
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Landing Page (Popup)</div>
        <div class="page-subtitle">รูปที่แสดง popup เมื่อผู้เยี่ยมชมเข้าเว็บ — แสดงซ้ำทุก 1 วัน</div>
    </div>
</div>

{{-- Upload form --}}
<div class="form-card" style="margin-bottom:24px;">
    <h3><i class="bi bi-cloud-arrow-up"></i> อัปโหลดรูปใหม่</h3>
    <form method="POST" action="{{ route('admin.landing-pages.store') }}" enctype="multipart/form-data" id="upload-form">
        @csrf
        <div style="display:flex;align-items:flex-end;gap:12px;flex-wrap:wrap;">
            <div style="flex:1;min-width:240px;">
                <label class="form-label">เลือกไฟล์รูป <span style="color:#aaa;font-weight:400;">(jpg/png/webp/gif, ไม่เกิน 4MB)</span></label>
                <input type="file" name="image" id="image-input" accept="image/*" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มรูป</button>
        </div>
    </form>
</div>

{{-- List --}}
<div class="table-wrap">
    <div class="table-header">
        <h3><i class="bi bi-images"></i> รูปทั้งหมด ({{ $landingPages->count() }})</h3>
        <span style="font-size:12px;color:#aaa;">เปิดใช้งานได้ 1 รูปต่อครั้ง</span>
    </div>
    @if($landingPages->isEmpty())
        <div style="text-align:center;padding:48px;color:#aaa;">
            <i class="bi bi-image" style="font-size:48px;display:block;margin-bottom:12px;"></i>
            ยังไม่มีรูป Landing Page
        </div>
    @else
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;padding:20px;">
        @foreach($landingPages as $page)
        <div style="background:#fff;border:2px solid {{ $page->is_active ? 'var(--g500)' : '#e8ecef' }};border-radius:16px;overflow:hidden;position:relative;box-shadow:{{ $page->is_active ? '0 0 0 3px rgba(64,145,108,0.15)' : 'none' }};">

            @if($page->is_active)
            <div style="position:absolute;top:10px;left:10px;z-index:1;">
                <span class="status-badge status-green"><i class="bi bi-broadcast"></i> กำลังแสดง</span>
            </div>
            @endif

            <img src="{{ asset('storage/'.$page->image_path) }}"
                 alt="Landing Page"
                 style="width:100%;height:200px;object-fit:cover;display:block;"
                 onerror="this.src='/images/logo.png'">

            <div style="padding:14px;display:flex;align-items:center;justify-content:space-between;gap:8px;">
                <div style="font-size:12px;color:#888;">
                    {{ $page->created_at->locale('th')->diffForHumans() }}
                </div>
                <div style="display:flex;gap:6px;">
                    {{-- Toggle --}}
                    <form method="POST" action="{{ route('admin.landing-pages.toggle', $page) }}">
                        @csrf
                        <button type="submit"
                                class="btn btn-sm {{ $page->is_active ? 'btn-outline' : 'btn-primary' }}"
                                title="{{ $page->is_active ? 'ปิดใช้งาน' : 'เปิดใช้งาน' }}">
                            <i class="bi {{ $page->is_active ? 'bi-toggle-on' : 'bi-toggle-off' }}"></i>
                            {{ $page->is_active ? 'ปิด' : 'เปิด' }}
                        </button>
                    </form>
                    {{-- Delete --}}
                    <form method="POST" action="{{ route('admin.landing-pages.destroy', $page) }}" class="delete-form">
                        @csrf @method('DELETE')
                        <button type="button" class="btn btn-sm btn-danger btn-delete">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ── Validate file size before upload ──
document.getElementById('image-input').addEventListener('change', function() {
    var file = this.files[0];
    if (!file) return;
    var maxMB = 4;
    if (file.size > maxMB * 1024 * 1024) {
        Swal.fire({
            icon: 'error',
            title: 'ไฟล์ใหญ่เกินไป',
            text: 'ขนาดไฟล์ต้องไม่เกิน ' + maxMB + ' MB (ไฟล์ที่เลือก: ' + (file.size / 1024 / 1024).toFixed(1) + ' MB)',
            confirmButtonColor: '#2d6a4f',
            confirmButtonText: 'รับทราบ',
        });
        this.value = '';
        return;
    }
    var allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
    if (!allowed.includes(file.type)) {
        Swal.fire({
            icon: 'error',
            title: 'ประเภทไฟล์ไม่ถูกต้อง',
            text: 'รองรับเฉพาะ jpg, png, webp, gif เท่านั้น',
            confirmButtonColor: '#2d6a4f',
            confirmButtonText: 'รับทราบ',
        });
        this.value = '';
    }
});

// ── Delete confirm ──
document.querySelectorAll('.btn-delete').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var form = btn.closest('.delete-form');
        Swal.fire({
            icon: 'warning',
            title: 'ลบรูปนี้?',
            text: 'ไม่สามารถกู้คืนได้หลังจากลบ',
            showCancelButton: true,
            confirmButtonColor: '#e74c3c',
            cancelButtonColor: '#aaa',
            confirmButtonText: 'ลบเลย',
            cancelButtonText: 'ยกเลิก',
        }).then(function(result) {
            if (result.isConfirmed) form.submit();
        });
    });
});

// ── Session alerts ──
@if(session('success'))
Swal.fire({
    icon: 'success',
    title: 'สำเร็จ',
    text: @json(session('success')),
    confirmButtonColor: '#2d6a4f',
    confirmButtonText: 'ตกลง',
});
@endif

@if($errors->any())
Swal.fire({
    icon: 'error',
    title: 'เกิดข้อผิดพลาด',
    text: @json($errors->first()),
    confirmButtonColor: '#2d6a4f',
    confirmButtonText: 'ตกลง',
});
@endif
</script>
@endpush
