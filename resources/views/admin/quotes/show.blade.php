@extends('layouts.admin')
@section('title', 'ใบเสนอราคา: '.$quote->company_name)
@section('content')
<div class="page-header">
    <div class="page-title">คำขอจาก {{ $quote->company_name }}</div>
    <a href="{{ route('admin.quotes.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
    <div>
        <div class="form-card">
            <h3><i class="bi bi-file-earmark-text"></i> รายละเอียดคำขอ</h3>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;font-size:14px;margin-bottom:16px;">
                <div><strong>บริษัท:</strong> {{ $quote->company_name }}</div>
                <div><strong>ผู้ติดต่อ:</strong> {{ $quote->contact_name }}</div>
                <div><strong>อีเมล:</strong> {{ $quote->email }}</div>
                <div><strong>โทร:</strong> {{ $quote->phone }}</div>
                <div><strong>จำนวน:</strong> {{ number_format($quote->quantity) }} ชิ้น</div>
            </div>
            <div style="background:#f8faf8;border-radius:12px;padding:16px;margin-bottom:12px;">
                <strong style="font-size:14px;">รายละเอียดสินค้า:</strong>
                <p style="font-size:14px;color:#555;margin-top:8px;line-height:1.8;">{{ $quote->product_details }}</p>
            </div>
            @if($quote->notes)<div style="background:#fef9ec;border-radius:12px;padding:14px;"><strong>หมายเหตุ:</strong> {{ $quote->notes }}</div>@endif
            @if($quote->attachment)
            <div style="margin-top:12px;">
                <a href="{{ media_url($quote->attachment) }}" class="btn btn-sm btn-light" target="_blank"><i class="bi bi-paperclip"></i> ดาวน์โหลดไฟล์แนบ</a>
            </div>
            @endif
        </div>
        <div class="form-card">
            <h3><i class="bi bi-send"></i> ตอบกลับใบเสนอราคา</h3>
            <form method="POST" action="{{ route('admin.quotes.respond', $quote) }}" enctype="multipart/form-data">
                @csrf
                <div class="form-grid">
                    <div class="form-group"><label class="form-label">ราคาที่เสนอ (฿)</label><input type="number" name="quoted_price" class="form-control" step="0.01" value="{{ $quote->quoted_price }}"></div>
                    <div class="form-group"><label class="form-label">แนบ PDF ใบเสนอราคา</label><input type="file" name="quote_pdf" class="form-control" accept=".pdf" style="border-radius:12px;"></div>
                    <div class="form-group col-span-2"><label class="form-label">หมายเหตุ</label><textarea name="admin_note" class="form-control" rows="3">{{ $quote->admin_note }}</textarea></div>
                </div>
                <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> ส่งใบเสนอราคา</button>
            </form>
        </div>
    </div>
    <div>
        <div class="form-card">
            <h3><i class="bi bi-arrow-repeat"></i> เปลี่ยนสถานะ</h3>
            <form method="POST" action="{{ route('admin.quotes.status', $quote) }}">
                @csrf @method('PATCH')
                <div class="form-group">
                    <select name="status" class="form-control">
                        @foreach(['pending'=>'รอดำเนินการ','quoted'=>'ส่งราคาแล้ว','accepted'=>'ตอบรับ','rejected'=>'ปฏิเสธ','closed'=>'ปิด'] as $v=>$l)
                        <option value="{{ $v }}" {{ $quote->status===$v?'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-sm btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
            </form>
            @if($quote->quote_pdf)
            <div style="margin-top:16px;">
                <a href="{{ media_url($quote->quote_pdf) }}" class="btn btn-sm btn-gold" target="_blank"><i class="bi bi-file-pdf"></i> ดู PDF ใบเสนอราคา</a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
