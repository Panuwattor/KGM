@extends('layouts.admin')
@section('title', isset($flashSale) ? 'แก้ไข Flash Sale' : 'สร้าง Flash Sale')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($flashSale) ? 'แก้ไข Flash Sale' : 'สร้าง Flash Sale' }}</div>
    <a href="{{ route('admin.marketing.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<form method="POST" action="{{ isset($flashSale) ? route('admin.flash-sales.update',$flashSale) : route('admin.flash-sales.store') }}" enctype="multipart/form-data">
    @csrf @if(isset($flashSale)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-lightning-charge"></i> ข้อมูล Flash Sale</h3>
        <div class="form-grid">
            <div class="form-group"><label class="form-label">ชื่อ Flash Sale *</label><input type="text" name="name" class="form-control" value="{{ old('name', ($flashSale ?? null)?->name ?? '') }}" required></div>
            <div class="form-group col-span-full">
                <label class="form-label"><i class="bi bi-image"></i> รูป Banner Flash Sale <span style="color:#aaa;font-size:12px;">(แนะนำ 1200×400px, ไม่เกิน 3MB)</span></label>
                @if(($flashSale ?? null)?->image)
                <div style="margin-bottom:10px;">
                    <img src="{{ asset('storage/'.$flashSale->image) }}" alt="Banner" style="max-width:360px;border-radius:10px;border:1px solid #e0e0e0;">
                    <div style="font-size:12px;color:#888;margin-top:4px;">อัปโหลดรูปใหม่เพื่อเปลี่ยน</div>
                </div>
                @endif
                <input type="file" name="image" class="form-control" accept="image/*">
            </div>
            <div class="form-group col-span-full" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div><label class="form-label">เวลาเริ่มต้น *</label><input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at', ($flashSale ?? null)?->starts_at?->format('Y-m-d H:i') ?? '') }}" required></div>
                <div><label class="form-label">เวลาสิ้นสุด *</label><input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at', ($flashSale ?? null)?->ends_at?->format('Y-m-d H:i') ?? '') }}" required></div>
            </div>
        </div>
        <div class="form-check"><input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', ($flashSale ?? null)?->is_active ?? true) ? 'checked' : '' }}><label for="is_active">เปิดใช้งาน</label></div>
    </div>
    @php $existingItems = isset($flashSale) ? $flashSale->items->keyBy('product_id') : collect(); @endphp
    <div class="form-card">
        <h3><i class="bi bi-box-seam"></i> เลือกสินค้าใน Flash Sale</h3>
        <div style="max-height:400px;overflow-y:auto;">
            <table style="width:100%;">
                <thead><tr><th>เลือก</th><th>สินค้า</th><th>ราคาปกติ (ฐาน)</th><th>ราคา Flash Sale * <small style="font-weight:400;color:#aaa;">(ก่อนบวกค่าไซส์)</small></th><th>จำนวนจำกัด</th></tr></thead>
                <tbody>
                @foreach($products as $p)
                @php $item = $existingItems->get($p->id); @endphp
                <tr>
                    <td><input type="checkbox" name="products[{{ $p->id }}][selected]" value="1" {{ $item ? 'checked' : '' }}></td>
                    <td>{{ $p->name }}</td>
                    <td>฿{{ number_format($p->price, 0) }}</td>
                    <td><input type="number" name="products[{{ $p->id }}][price]" class="form-control" style="width:110px;border-radius:10px;" placeholder="ราคา Flash" step="0.01" value="{{ old('products.'.$p->id.'.price', $item?->sale_price ?? '') }}"></td>
                    <td><input type="number" name="products[{{ $p->id }}][stock_limit]" class="form-control" style="width:90px;border-radius:10px;" placeholder="ไม่จำกัด" value="{{ old('products.'.$p->id.'.stock_limit', $item?->stock_limit ?? '') }}"></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-floppy"></i> บันทึก Flash Sale</button>
</form>
@endsection
