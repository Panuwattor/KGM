@extends('layouts.app')
@section('title', 'ขอใบเสนอราคา')
@section('content')
<div style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="container">
        <div style="color:var(--kgm-gold-300);font-weight:700;letter-spacing:2px;text-transform:uppercase;font-size:13px;margin-bottom:10px;">B2B Service</div>
        <h1 style="font-size:40px;font-weight:800;color:white;">ขอใบเสนอราคา</h1>
        <p style="color:rgba(255,255,255,0.8);margin-top:12px;">สำหรับลูกค้าองค์กรที่ต้องการสั่งผลิตจำนวนมาก</p>
    </div>
</div>
<div class="container" style="padding:64px 24px;max-width:800px;">
    <div style="background:white;border-radius:24px;padding:40px;box-shadow:0 4px 20px rgba(0,0,0,0.08);">
        <form method="POST" action="{{ route('quote.submit') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-grid">
                <div class="form-group"><label class="form-label">ชื่อบริษัท/องค์กร *</label><input type="text" name="company_name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">ชื่อผู้ติดต่อ *</label><input type="text" name="contact_name" class="form-control" required></div>
                <div class="form-group"><label class="form-label">อีเมล *</label><input type="email" name="email" class="form-control" required></div>
                <div class="form-group"><label class="form-label">เบอร์โทร *</label><input type="text" name="phone" class="form-control" required></div>
                <div class="form-group col-span-2"><label class="form-label">รายละเอียดสินค้าที่ต้องการ *</label><textarea name="product_details" class="form-control" rows="4" placeholder="ระบุประเภท สี ไซส์ หรือรายละเอียดอื่นๆ" required></textarea></div>
                <div class="form-group"><label class="form-label">จำนวน (ชิ้น) *</label><input type="number" name="quantity" class="form-control" min="1" required></div>
                <div class="form-group"><label class="form-label">แนบไฟล์ประกอบ (โลโก้, แบบเสื้อ)</label><input type="file" name="attachment" class="form-control" style="border-radius:12px;" accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"></div>
                <div class="form-group col-span-2"><label class="form-label">หมายเหตุเพิ่มเติม</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
            </div>
            <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-file-earmark-text"></i> ส่งคำขอใบเสนอราคา</button>
        </form>
    </div>
</div>
@endsection
