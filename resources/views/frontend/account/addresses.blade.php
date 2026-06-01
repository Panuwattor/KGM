@extends('layouts.app')
@section('title', 'ที่อยู่จัดส่ง')
@section('content')
<div class="container" style="padding:32px 0 64px;max-width:900px;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;">
        <h1 style="font-size:24px;font-weight:800;color:var(--kgm-green-800);"><i class="bi bi-geo-alt"></i> ที่อยู่จัดส่ง</h1>
    </div>
    <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:16px;margin-bottom:32px;">
        @forelse($addresses as $addr)
        <div style="background:white;border-radius:16px;padding:20px;box-shadow:0 2px 10px rgba(0,0,0,0.06);position:relative;border:2px solid {{ $addr->is_default ? 'var(--kgm-green-500)' : '#e8ecef' }};">
            @if($addr->is_default)<div style="position:absolute;top:12px;right:12px;background:var(--kgm-green-500);color:white;padding:2px 10px;border-radius:999px;font-size:11px;font-weight:700;">ค่าเริ่มต้น</div>@endif
            <div style="font-weight:800;font-size:15px;margin-bottom:4px;">{{ $addr->recipient_name }}</div>
            <div style="font-size:13px;color:#555;line-height:1.8;">
                {{ $addr->address_line1 }}<br>
                ต.{{ $addr->district }} อ.{{ $addr->amphoe }}<br>
                จ.{{ $addr->province }} {{ $addr->postcode }}<br>
                <i class="bi bi-telephone"></i> {{ $addr->phone }}
            </div>
            <div style="display:flex;gap:8px;margin-top:12px;">
                <form method="POST" action="{{ route('account.addresses.delete', $addr) }}" onsubmit="return confirm('ลบ?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> ลบ</button>
                </form>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1;text-align:center;color:#aaa;padding:32px;">ยังไม่มีที่อยู่</div>
        @endforelse
    </div>
    <div style="background:white;border-radius:20px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,0.06);">
        <h3 style="font-weight:800;margin-bottom:20px;"><i class="bi bi-plus-circle"></i> เพิ่มที่อยู่ใหม่</h3>
        <form method="POST" action="{{ route('account.addresses.store') }}">
            @csrf
            <div class="form-grid">
                <div class="form-group"><label class="form-label">ชื่อผู้รับ *</label><input type="text" name="recipient_name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">เบอร์โทร *</label><input type="text" name="phone" class="form-control" required></div>
                <div class="form-group col-span-2"><label class="form-label">ที่อยู่ *</label><input type="text" name="address_line1" class="form-control" required></div>
                <div class="form-group"><label class="form-label">แขวง/ตำบล *</label><input type="text" name="district" class="form-control" required></div>
                <div class="form-group"><label class="form-label">เขต/อำเภอ *</label><input type="text" name="amphoe" class="form-control" required></div>
                <div class="form-group"><label class="form-label">จังหวัด *</label><input type="text" name="province" class="form-control" required></div>
                <div class="form-group"><label class="form-label">รหัสไปรษณีย์ *</label><input type="text" name="postcode" class="form-control" maxlength="5" required></div>
            </div>
            <div class="form-check"><input type="checkbox" name="is_default" id="default" value="1"><label for="default">ตั้งเป็นที่อยู่หลัก</label></div>
            <button type="submit" class="btn btn-primary" style="margin-top:12px;"><i class="bi bi-plus"></i> เพิ่มที่อยู่</button>
        </form>
    </div>
</div>
@endsection
