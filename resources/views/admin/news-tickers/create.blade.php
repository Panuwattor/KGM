@extends('layouts.admin')
@section('title', 'เพิ่มข่าวสำคัญ')
@section('content')
<div class="page-header">
    <div class="page-title">เพิ่มข่าวสำคัญ</div>
    <a href="{{ route('admin.news-tickers.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:700px;">
<form method="POST" action="{{ route('admin.news-tickers.store') }}">
    @csrf
    <div class="form-card">
        <h3><i class="bi bi-megaphone-fill"></i> ข้อมูลข่าวสำคัญ</h3>

        <div class="form-group">
            <label class="form-label">ข้อความเฉยๆ <small style="color:#888;">(ข้อความสีขาวที่ไม่สามารถคลิกได้)</small></label>
            <textarea name="plain_text" class="form-control" rows="3" placeholder="เช่น: ยินดีต้อนรับสู่เว็บไซต์กิจเจริญการ์เมนท์">{{ old('plain_text') }}</textarea>
            @error('plain_text')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">ข้อความที่กดได้ <small style="color:#888;">(ข้อความสีเหลืองที่สามารถคลิกได้)</small></label>
            <input type="text" name="link_text" class="form-control" value="{{ old('link_text') }}" placeholder="เช่น: สมัครเลย, คลิกที่นี่">
            @error('link_text')<div class="text-danger">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
            <label class="form-label">ลิงก์ (URL)</label>
            <input type="url" name="link_url" class="form-control" value="{{ old('link_url') }}" placeholder="https://...">
            @error('link_url')<div class="text-danger">{{ $message }}</div>@enderror
            <small style="color:#888;">หากมีข้อความที่กดได้ ควรใส่ลิงก์ด้วย</small>
        </div>

        <div class="form-group">
            <label class="form-label">ลำดับการแสดงผล</label>
            <input type="number" name="order" class="form-control" value="{{ old('order', 0) }}" min="0">
            @error('order')<div class="text-danger">{{ $message }}</div>@enderror
            <small style="color:#888;">เลขน้อยจะแสดงก่อน</small>
        </div>

        <div class="form-check">
            <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <label for="is_active">เปิดใช้งาน</label>
        </div>

        <button type="submit" class="btn btn-primary" style="margin-top:16px;">
            <i class="bi bi-floppy"></i> บันทึก
        </button>
    </div>
</form>
</div>
@endsection
