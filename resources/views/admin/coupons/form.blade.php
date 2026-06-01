@extends('layouts.admin')
@section('title', isset($coupon) ? 'แก้ไขคูปอง' : 'สร้างคูปอง')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($coupon) ? 'แก้ไขคูปอง' : 'สร้างคูปอง' }}</div>
    <a href="{{ route('admin.coupons.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:700px;">
<form method="POST" action="{{ isset($coupon) ? route('admin.coupons.update',$coupon) : route('admin.coupons.store') }}">
    @csrf @if(isset($coupon)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-ticket-perforated"></i> ข้อมูลคูปอง</h3>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">โค้ดคูปอง *</label>
                <input type="text" name="code" class="form-control" value="{{ old('code',$coupon->code??'') }}" required style="text-transform:uppercase;">
            </div>
            <div class="form-group">
                <label class="form-label">ชื่อคูปอง *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name',$coupon->name??'') }}" required>
            </div>
            <div class="form-group">
                <label class="form-label">ประเภท *</label>
                <select name="type" class="form-control" id="coupon-type" onchange="toggleType()">
                    <option value="percent" {{ old('type',$coupon->type??'percent')=='percent'?'selected':'' }}>เปอร์เซ็นต์ (%)</option>
                    <option value="fixed" {{ old('type',$coupon->type??'')=='fixed'?'selected':'' }}>จำนวนบาท (฿)</option>
                    <option value="free_shipping" {{ old('type',$coupon->type??'')=='free_shipping'?'selected':'' }}>ส่งฟรี</option>
                </select>
            </div>
            <div class="form-group" id="value-field">
                <label class="form-label">มูลค่า *</label>
                <input type="number" name="value" class="form-control" step="0.01" min="0" value="{{ old('value',$coupon->value??'0') }}">
            </div>
            <div class="form-group">
                <label class="form-label">ยอดสั่งซื้อขั้นต่ำ (฿)</label>
                <input type="number" name="minimum_order" class="form-control" step="0.01" min="0" value="{{ old('minimum_order',$coupon->minimum_order??'0') }}">
            </div>
            <div class="form-group">
                <label class="form-label">ส่วนลดสูงสุด (฿)</label>
                <input type="number" name="maximum_discount" class="form-control" step="0.01" min="0" value="{{ old('maximum_discount',$coupon->maximum_discount??'') }}">
            </div>
            <div class="form-group">
                <label class="form-label">จำกัดจำนวนสิทธิ์</label>
                <input type="number" name="usage_limit" class="form-control" min="1" value="{{ old('usage_limit',$coupon->usage_limit??'') }}" placeholder="ไม่จำกัด">
            </div>
            <div class="form-group">
                <label class="form-label">ต่อ 1 ผู้ใช้ (ครั้ง)</label>
                <input type="number" name="per_user_limit" class="form-control" min="1" value="{{ old('per_user_limit',$coupon->per_user_limit??'1') }}">
            </div>
            <div class="form-group">
                <label class="form-label">วันเริ่มต้น</label>
                <input type="date" name="starts_at" class="form-control" value="{{ old('starts_at',$coupon->starts_at?->format('Y-m-d')??'') }}">
            </div>
            <div class="form-group">
                <label class="form-label">วันหมดอายุ</label>
                <input type="date" name="expires_at" class="form-control" value="{{ old('expires_at',$coupon->expires_at?->format('Y-m-d')??'') }}">
            </div>
        </div>
        <div class="form-check"><input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active',$coupon->is_active??true)?'checked':'' }}><label for="is_active">เปิดใช้งาน</label></div>
        <button type="submit" class="btn btn-primary" style="margin-top:16px;"><i class="bi bi-floppy"></i> บันทึก</button>
    </div>
</form>
</div>
@endsection
@push('scripts')
<script>
function toggleType() {
    const t = document.getElementById('coupon-type').value;
    document.getElementById('value-field').style.display = t === 'free_shipping' ? 'none' : 'block';
}
toggleType();
</script>
@endpush
