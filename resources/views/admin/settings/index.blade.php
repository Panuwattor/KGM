@extends('layouts.admin')
@section('title', 'ตั้งค่าระบบ')
@section('content')
<div class="page-header">
    <div class="page-title">ตั้งค่าระบบ</div>
</div>
<div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
    <a href="{{ route('admin.settings.index') }}"          class="btn btn-primary"><i class="bi bi-gear"></i> ทั่วไป</a>
    <a href="{{ route('admin.settings.logs') }}"           class="btn btn-light"><i class="bi bi-journal-text"></i> Audit Log</a>
    <a href="{{ route('admin.settings.consent-logs') }}"   class="btn btn-light"><i class="bi bi-shield-check"></i> PDPA Log</a>
</div>
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf
    <div class="form-card">
        <h3><i class="bi bi-building"></i> ข้อมูลบริษัท</h3>
        <div class="form-grid">
            @foreach([
                ['site_name','ชื่อเว็บไซต์'],
                ['site_phone','เบอร์โทรศัพท์'],
                ['site_email','อีเมล'],
                ['site_line','Line ID'],
                ['site_address','ที่อยู่'],
                ['telegram_chat_id','Telegram Chat ID'],
            ] as [$key,$label])
            <div class="form-group">
                <label class="form-label">{{ $label }}</label>
                <input type="text" name="settings[{{ $key }}]" class="form-control" value="{{ $settings[$key] ?? '' }}">
            </div>
            @endforeach
        </div>
    </div>
    <div class="form-card">
        <h3><i class="bi bi-credit-card"></i> ข้อมูล PromptPay</h3>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">เบอร์/หมายเลข PromptPay</label>
                <input type="text" name="settings[promptpay_number]" class="form-control" value="{{ $settings['promptpay_number'] ?? '' }}">
            </div>
            <div class="form-group">
                <label class="form-label">ชื่อบัญชี</label>
                <input type="text" name="settings[bank_account_name]" class="form-control" value="{{ $settings['bank_account_name'] ?? '' }}">
            </div>
        </div>
    </div>
    <div class="form-card">
        <h3><i class="bi bi-pen"></i> บริการปัก</h3>
        <div class="form-grid">
            <div class="form-group">
                <label class="form-label">ราคาปักชื่อ (บาท/ตัว)</label>
                <input type="number" step="0.01" min="0" name="settings[embroidery_name_price]" class="form-control" value="{{ $settings['embroidery_name_price'] ?? 45 }}">
            </div>
        </div>
    </div>
    <div class="form-card">
        <h3><i class="bi bi-search"></i> SEO ทั่วไป</h3>
        <div class="form-group">
            <label class="form-label">Default Meta Title</label>
            <input type="text" name="settings[meta_title]" class="form-control" value="{{ $settings['meta_title'] ?? '' }}">
        </div>
        <div class="form-group">
            <label class="form-label">Default Meta Description</label>
            <textarea name="settings[meta_description]" class="form-control" rows="3">{{ $settings['meta_description'] ?? '' }}</textarea>
        </div>
        <div class="form-group">
            <label class="form-label">Google Analytics ID</label>
            <input type="text" name="settings[ga_id]" class="form-control" placeholder="G-XXXXXXXXXX" value="{{ $settings['ga_id'] ?? '' }}">
        </div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึกการตั้งค่า</button>
</form>
@endsection
