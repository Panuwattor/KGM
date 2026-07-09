@extends('layouts.admin')
@section('title', isset($product) ? 'แก้ไขสินค้าที่รับผลิต' : 'เพิ่มสินค้าที่รับผลิต')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($product) ? 'แก้ไขสินค้าที่รับผลิต' : 'เพิ่มสินค้าที่รับผลิต' }}</div>
    <a href="{{ route('admin.manufacturing-products.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>

@if($errors->any())
<div class="alert alert-danger" style="margin-bottom:16px;">
    <ul style="margin:0;padding-left:18px;">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div style="max-width:760px;">
<form method="POST" action="{{ isset($product) ? route('admin.manufacturing-products.update', $product) : route('admin.manufacturing-products.store') }}" enctype="multipart/form-data">
    @csrf @if(isset($product)) @method('PUT') @endif

    <div class="form-card">
        <h3><i class="bi bi-box-seam"></i> ข้อมูลสินค้า</h3>

        <div class="form-group">
            <label class="form-label">ชื่อสินค้า *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $product->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">รายละเอียด</label>
            <textarea name="description" class="form-control" rows="4">{{ old('description', $product->description ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">รูปหลัก (แสดงเป็นภาพปก)</label>
            @if(isset($product) && $product->image)
                <div style="margin-bottom:8px;">
                    <img src="{{ media_url($product->image) }}" alt="รูปหลัก" style="max-width:200px;border-radius:8px;border:1px solid #ddd;">
                    <div style="font-size:12px;color:#666;margin-top:4px;">อัปโหลดไฟล์ใหม่เพื่อเปลี่ยนรูปหลัก</div>
                </div>
            @endif
            <input type="file" name="image" class="form-control" accept="image/*" style="border-radius:12px;">
            <div style="font-size:12px;color:#888;margin-top:6px;">แนะนำภาพจตุรัส · ขนาดอย่างน้อย 200 × 200 พิกเซล · ไฟล์ไม่เกิน 2 MB</div>
        </div>

        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">ลำดับการแสดง</label>
                <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $product->sort_order ?? 0) }}">
            </div>
        </div>

        <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active ?? true) ? 'checked' : '' }}>
            <label for="is_active">แสดงบนหน้าเว็บ</label>
        </div>
    </div>

    <div class="form-card">
        <h3><i class="bi bi-images"></i> รูปภาพเพิ่มเติม (แกลเลอรี)</h3>

        @if(isset($product) && $product->images->count())
        <div id="image-grid" style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;margin-bottom:12px;">
            @foreach($product->images as $img)
            <div style="position:relative;border-radius:12px;overflow:hidden;aspect-ratio:1;border:1px solid #eee;">
                <img src="{{ media_url($img->image_path) }}" style="width:100%;height:100%;object-fit:cover;">
                <button type="button" onclick="deleteMfgImage({{ $product->id }}, {{ $img->id }}, this)"
                    style="position:absolute;top:4px;right:4px;background:rgba(231,76,60,0.9);color:white;border:none;border-radius:999px;width:24px;height:24px;cursor:pointer;font-size:12px;">
                    <i class="bi bi-x"></i>
                </button>
            </div>
            @endforeach
        </div>
        @endif

        <label class="form-label">เพิ่มรูปได้หลายรูป</label>
        <input type="file" name="images[]" class="form-control" accept="image/*" multiple style="border-radius:12px;">
        <div style="font-size:12px;color:#888;margin-top:6px;">เลือกได้หลายไฟล์พร้อมกัน · PNG, JPG · ไฟล์ไม่เกิน 2 MB ต่อรูป</div>
    </div>

    <button type="submit" class="btn btn-primary" style="margin-top:4px;"><i class="bi bi-floppy"></i> บันทึก</button>
</form>
</div>
@endsection

@push('scripts')
<script>
function deleteMfgImage(productId, imageId, btn) {
    Swal.fire({
        icon: 'warning',
        title: 'ลบรูปนี้?',
        text: 'ไม่สามารถกู้คืนได้หลังจากลบ',
        showCancelButton: true,
        confirmButtonColor: '#e74c3c',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'ลบ',
        cancelButtonText: 'ยกเลิก',
    }).then(function (result) {
        if (!result.isConfirmed) return;
        fetch(`/admin/manufacturing-products/${productId}/images/${imageId}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content }
        }).then(function (r) {
            if (!r.ok) throw new Error();
            btn.closest('div').remove();
            if (window.kgmToast) kgmToast('success', 'ลบรูปแล้ว');
        }).catch(function () {
            Swal.fire({ icon: 'error', title: 'เกิดข้อผิดพลาด', text: 'ลบรูปไม่สำเร็จ กรุณาลองใหม่' });
        });
    });
}
</script>
@endpush
