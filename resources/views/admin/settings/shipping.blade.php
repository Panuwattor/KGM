@extends('layouts.admin')
@section('title', 'ตั้งค่าค่าจัดส่ง')
@section('content')
<div class="page-header">
    <div class="page-title">ตั้งค่าค่าจัดส่ง</div>
</div>
<div style="display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap;">
    <a href="{{ route('admin.settings.index') }}"          class="btn btn-light"><i class="bi bi-gear"></i> ทั่วไป</a>
    <a href="{{ route('admin.settings.shipping') }}"       class="btn btn-primary"><i class="bi bi-truck"></i> ผู้ให้บริการขนส่ง</a>
    <a href="{{ route('admin.settings.shipping-rates') }}" class="btn btn-light"><i class="bi bi-table"></i> อัตราค่าขนส่ง</a>
    <a href="{{ route('admin.settings.logs') }}"           class="btn btn-light"><i class="bi bi-journal-text"></i> Audit Log</a>
    <a href="{{ route('admin.settings.consent-logs') }}"   class="btn btn-light"><i class="bi bi-shield-check"></i> PDPA Log</a>
</div>
<form method="POST" action="{{ route('admin.settings.shipping.update') }}" x-data="{ rows: {{ json_encode($shippings) }} }">
    @csrf
    <div class="form-card">
        <h3><i class="bi bi-truck"></i> บริษัทขนส่งและอัตรา</h3>
        <template x-for="(row, i) in rows" :key="i">
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr 80px auto;gap:10px;align-items:end;margin-bottom:10px;">
                <div>
                    <label class="form-label" x-show="i===0">บริษัทขนส่ง</label>
                    <input type="text" :name="`shippings[${i}][provider_name]`" x-model="row.provider_name" class="form-control" placeholder="Kerry, ไปรษณีย์ไทย...">
                </div>
                <div>
                    <label class="form-label" x-show="i===0">ค่าจัดส่ง (฿)</label>
                    <input type="number" :name="`shippings[${i}][flat_rate]`" x-model="row.flat_rate" class="form-control" min="0">
                </div>
                <div>
                    <label class="form-label" x-show="i===0">ส่งฟรีเมื่อยอดถึง (฿)</label>
                    <input type="number" :name="`shippings[${i}][free_shipping_threshold]`" x-model="row.free_shipping_threshold" class="form-control" min="0">
                </div>
                <div>
                    <label class="form-label" x-show="i===0">เปิด</label>
                    <input type="checkbox" :name="`shippings[${i}][is_active]`" value="1" :checked="row.is_active" style="width:20px;height:20px;accent-color:var(--g500);">
                </div>
                <div>
                    <label class="form-label" x-show="i===0" style="visibility:hidden">ลบ</label>
                    <button type="button" @click="rows.splice(i,1)" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </div>
            </div>
        </template>
        <button type="button" @click="rows.push({provider_name:'',flat_rate:50,free_shipping_threshold:1000,is_active:true})" class="btn btn-sm btn-light">
            <i class="bi bi-plus-lg"></i> เพิ่มบริษัทขนส่ง
        </button>
        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
        </div>
    </div>
</form>
@endsection
