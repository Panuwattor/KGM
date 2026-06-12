@extends('layouts.admin')
@section('title', isset($flashSale) ? 'แก้ไข Flash Sale' : 'สร้าง Flash Sale')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($flashSale) ? 'แก้ไข Flash Sale' : 'สร้าง Flash Sale' }}</div>
    <a href="{{ route('admin.marketing.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<form method="POST" action="{{ isset($flashSale) ? route('admin.flash-sales.update',$flashSale) : route('admin.flash-sales.store') }}">
    @csrf @if(isset($flashSale)) @method('PUT') @endif
    <div class="form-card">
        <h3><i class="bi bi-lightning-charge"></i> ข้อมูล Flash Sale</h3>
        <div class="form-grid">
            <div class="form-group"><label class="form-label">ชื่อ Flash Sale *</label><input type="text" name="name" class="form-control" value="{{ old('name',$flashSale->name??'') }}" required></div>
            <div class="form-group col-span-full" style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div><label class="form-label">เวลาเริ่มต้น *</label><input type="datetime-local" name="starts_at" class="form-control" value="{{ old('starts_at',$flashSale->starts_at?->format('Y-m-d H:i')??'') }}" required></div>
                <div><label class="form-label">เวลาสิ้นสุด *</label><input type="datetime-local" name="ends_at" class="form-control" value="{{ old('ends_at',$flashSale->ends_at?->format('Y-m-d H:i')??'') }}" required></div>
            </div>
        </div>
        <div class="form-check"><input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active',$flashSale->is_active??true)?'checked':'' }}><label for="is_active">เปิดใช้งาน</label></div>
    </div>
    <div class="form-card" x-data="{ selectedProducts: [] }">
        <h3><i class="bi bi-box-seam"></i> เลือกสินค้าใน Flash Sale</h3>
        <div style="max-height:400px;overflow-y:auto;">
            <table style="width:100%;">
                <thead><tr><th>เลือก</th><th>สินค้า</th><th>ราคาปกติ</th><th>ราคา Flash Sale</th><th>จำนวนจำกัด</th></tr></thead>
                <tbody>
                @foreach($products as $p)
                <tr>
                    <td><input type="checkbox" name="products[{{ $p->id }}][selected]" value="1"></td>
                    <td>{{ $p->name }}</td>
                    <td>฿{{ number_format($p->price, 0) }}</td>
                    <td><input type="number" name="products[{{ $p->id }}][price]" class="form-control" style="width:100px;border-radius:10px;" placeholder="ราคา Flash" step="0.01"></td>
                    <td><input type="number" name="products[{{ $p->id }}][stock_limit]" class="form-control" style="width:80px;border-radius:10px;" placeholder="ไม่จำกัด"></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-floppy"></i> บันทึก Flash Sale</button>
</form>
@endsection
