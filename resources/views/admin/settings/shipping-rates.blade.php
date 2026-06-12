@extends('layouts.admin')
@section('title', 'อัตราค่าขนส่งตามจำนวน')
@section('content')
<div class="page-header">
    <div class="page-title">อัตราค่าขนส่ง</div>
</div>


@if(session('success'))
    <div class="alert alert-success mb-4">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.settings.shipping-rates.update') }}"
      x-data="{ rows: {{ $rates->map(fn($r) => ['min_qty' => $r->min_qty, 'max_qty' => $r->max_qty ?? '', 'price' => $r->price, 'is_active' => $r->is_active])->values()->toJson() }} }">
    @csrf
    <div class="form-card">
        <h3><i class="bi bi-table"></i> อัตราค่าขนส่งตามจำนวนตัว</h3>
        <p style="color:var(--gray-500);font-size:0.875rem;margin-bottom:16px;">
            ระบบจะเลือก tier ที่ตรงกับจำนวนรวมในออเดอร์โดยอัตโนมัติ<br>
            ช่อง "สูงสุด" เว้นว่างหมายถึงไม่มีเพดาน (∞)
        </p>

        <div style="display:grid;grid-template-columns:130px 130px 130px 80px 40px;gap:10px;margin-bottom:6px;">
            <div class="form-label">จำนวนต่ำสุด (ตัว)</div>
            <div class="form-label">จำนวนสูงสุด (ตัว)</div>
            <div class="form-label">ค่าขนส่ง (฿)</div>
            <div class="form-label">เปิดใช้</div>
            <div></div>
        </div>

        <template x-for="(row, i) in rows" :key="i">
            <div style="display:grid;grid-template-columns:130px 130px 130px 80px 40px;gap:10px;align-items:center;margin-bottom:8px;">
                <input type="number" :name="`rates[${i}][min_qty]`" x-model="row.min_qty"
                       class="form-control" min="1" placeholder="1" required>
                <input type="number" :name="`rates[${i}][max_qty]`" x-model="row.max_qty"
                       class="form-control" min="1" placeholder="∞ (ไม่จำกัด)">
                <input type="number" :name="`rates[${i}][price]`" x-model="row.price"
                       class="form-control" min="0" step="1" required>
                <div style="display:flex;justify-content:center;">
                    <input type="checkbox" :name="`rates[${i}][is_active]`" value="1"
                           :checked="row.is_active" style="width:20px;height:20px;accent-color:var(--g500);">
                </div>
                <button type="button" @click="rows.splice(i,1)" class="btn btn-sm btn-danger" style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </template>

        <button type="button"
                @click="rows.push({ min_qty: rows.length ? (parseInt(rows[rows.length-1].max_qty||0)+1) : 1, max_qty: '', price: 0, is_active: true })"
                class="btn btn-sm btn-light" style="margin-top:4px;">
            <i class="bi bi-plus-lg"></i> เพิ่ม Tier
        </button>

        @error('rates')
            <div class="text-danger" style="margin-top:8px;">{{ $message }}</div>
        @enderror

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
        </div>
    </div>
</form>
@endsection
