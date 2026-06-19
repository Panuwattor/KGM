@extends('layouts.admin')
@section('title', 'บริษัทขนส่ง')
@section('content')
<div class="page-header">
    <div class="page-title">บริษัทขนส่ง</div>
</div>

<form method="POST" action="{{ route('admin.settings.shipping-providers.update') }}"
      x-data="{ rows: {{ $providers->map(fn($p) => ['name' => $p->name, 'is_active' => $p->is_active])->values()->toJson() }} }">
    @csrf
    <div class="form-card">
        <h3><i class="bi bi-truck"></i> รายชื่อบริษัทขนส่ง</h3>
        <p style="color:#888;font-size:0.875rem;margin-bottom:16px;">
            รายชื่อที่เปิดใช้งานจะแสดงเป็นตัวเลือกตอนกรอกเลข Tracking ในหน้าออเดอร์<br>
            ลากเพิ่ม/ลบได้ตามต้องการ ไม่ต้องแก้โค้ด
        </p>

        <div style="display:grid;grid-template-columns:1fr 80px 40px;gap:10px;margin-bottom:6px;">
            <div class="form-label">ชื่อบริษัทขนส่ง</div>
            <div class="form-label">เปิดใช้</div>
            <div></div>
        </div>

        <template x-for="(row, i) in rows" :key="i">
            <div style="display:grid;grid-template-columns:1fr 80px 40px;gap:10px;align-items:center;margin-bottom:8px;">
                <input type="text" :name="`providers[${i}][name]`" x-model="row.name"
                       class="form-control" placeholder="เช่น Kerry Express" required>
                <div style="display:flex;justify-content:center;">
                    <input type="checkbox" :name="`providers[${i}][is_active]`" value="1"
                           :checked="row.is_active" style="width:20px;height:20px;accent-color:var(--g500);">
                </div>
                <button type="button" @click="rows.splice(i,1)" class="btn btn-sm btn-danger" style="width:36px;height:36px;padding:0;display:flex;align-items:center;justify-content:center;">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </template>

        <button type="button"
                @click="rows.push({ name: '', is_active: true })"
                class="btn btn-sm btn-light" style="margin-top:4px;">
            <i class="bi bi-plus-lg"></i> เพิ่มบริษัทขนส่ง
        </button>

        @error('providers')
            <div class="text-danger" style="margin-top:8px;">{{ $message }}</div>
        @enderror

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
        </div>
    </div>
</form>
@endsection
