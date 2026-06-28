@extends('layouts.admin')
@section('title', isset($banner) ? 'แก้ไข Banner' : 'เพิ่ม Banner')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($banner) ? 'แก้ไข Banner' : 'เพิ่ม Banner' }}</div>
    <a href="{{ route('admin.banners.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:700px;">
<form method="POST" action="{{ isset($banner) ? route('admin.banners.update',$banner) : route('admin.banners.store') }}" enctype="multipart/form-data">
    @csrf @if(isset($banner)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-image"></i> ข้อมูล Banner</h3>
        <div class="form-group">
            <label class="form-label">ลิงก์ (URL)</label>
            <input type="text" name="link_url" class="form-control" value="{{ old('link_url', $banner->link_url ?? '') }}" placeholder="https://...">
        </div>
        <div class="form-group">
            <label class="form-label">ลำดับ</label>
            <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $banner->sort_order ?? 0) }}">
        </div>
        <div class="form-group">
            <label class="form-label">รูปภาพ {{ isset($banner) ? '' : '*' }} <small style="color:#888;">(1400 × 609 px, ไม่เกิน 2 MB)</small></label>
            <input type="file" id="bannerImage" name="image" class="form-control" accept="image/*" style="border-radius:12px;" {{ isset($banner) ? '' : 'required' }}>
            <div id="imageError" style="color:#e74c3c;font-size:13px;margin-top:6px;display:none;"></div>
            @if(isset($banner))<img src="{{ media_url($banner->image_path) }}" style="max-width:300px;border-radius:12px;margin-top:8px;" onerror="this.style.display='none'">@endif
        </div>
        <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $banner->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active">เปิดใช้งาน</label>
        </div>
        <button type="submit" class="btn btn-primary" style="margin-top:16px;"><i class="bi bi-floppy"></i> บันทึก</button>
    </div>
</form>
</div>
@push('scripts')
<script>
document.getElementById('bannerImage').addEventListener('change', function () {
    const file = this.files[0];
    const err  = document.getElementById('imageError');
    err.style.display = 'none';
    err.textContent   = '';
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        err.textContent   = 'ขนาดไฟล์เกิน 2 MB';
        err.style.display = 'block';
        this.value = '';
        return;
    }

    const img = new Image();
    img.onload = () => {
        URL.revokeObjectURL(img.src);
        if (img.width !== 1400 || img.height !== 609) {
            err.textContent   = `ขนาดรูปต้องเป็น 1400 × 609 px (รูปที่เลือก: ${img.width} × ${img.height} px)`;
            err.style.display = 'block';
            this.value = '';
        }
    };
    img.src = URL.createObjectURL(file);
});
</script>
@endpush
@endsection
