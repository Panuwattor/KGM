@extends('layouts.admin')
@section('title', 'สิทธิ์การใช้งาน')
@section('content')
<div class="page-header">
    <div>
        <div class="page-title">สิทธิ์การใช้งาน</div>
        <div class="page-subtitle">รายการสิทธิ์ทั้งหมดในระบบ (จัดการโดย Developer)</div>
    </div>
</div>
<div class="table-wrap">
    <table>
        <thead>
            <tr><th>ชื่อสิทธิ์</th><th>คำอธิบาย</th><th>ใช้งานในตำแหน่ง</th></tr>
        </thead>
        <tbody>
        @forelse($permissions as $permission)
        <tr>
            <td style="font-weight:700;font-size:13px;font-family:monospace;color:#555;">{{ $permission->name }}</td>
            <td>{{ $permission->description ?? '-' }}</td>
            <td>{{ $permission->roles_count }} ตำแหน่ง</td>
        </tr>
        @empty
        <tr><td colspan="3" style="text-align:center;padding:32px;color:#aaa;">ไม่พบสิทธิ์</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $permissions->links() }}</div>
</div>
<div style="background:#f8f9fa;border-left:4px solid #6c757d;padding:16px;margin-top:20px;border-radius:8px;">
    <div style="font-weight:700;margin-bottom:8px;"><i class="bi bi-info-circle"></i> หมายเหตุสำหรับผู้ดูแลระบบ</div>
    <div style="color:#666;font-size:14px;line-height:1.6;">
        การเพิ่ม แก้ไข หรือลบสิทธิ์ต้องทำผ่าน Developer โดยแก้ไขที่ไฟล์ <code>database/seeders/RolePermissionSeeder.php</code><br>
        หลังจากแก้ไขแล้วให้รันคำสั่ง <code>php artisan db:seed --class=RolePermissionSeeder</code>
    </div>
</div>
@endsection
