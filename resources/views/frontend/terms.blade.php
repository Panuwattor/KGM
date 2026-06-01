@extends('layouts.app')
@section('title', 'ข้อกำหนดการใช้งาน')
@section('content')
<div class="container" style="padding:48px 24px;max-width:860px;">
    <h1 style="font-size:32px;font-weight:800;color:var(--kgm-green-800);margin-bottom:24px;"><i class="bi bi-file-text"></i> ข้อกำหนดการใช้งาน</h1>
    <div style="background:white;border-radius:20px;padding:32px;box-shadow:0 2px 10px rgba(0,0,0,0.06);font-size:15px;line-height:1.9;color:#444;">
        <p>การใช้งานเว็บไซต์นี้ถือว่าท่านยอมรับข้อกำหนดและเงื่อนไขต่อไปนี้</p>
        <h2 style="font-size:18px;font-weight:700;color:var(--kgm-green-800);margin:24px 0 12px;">1. การสั่งซื้อสินค้า</h2>
        <p>ราคาสินค้าและข้อมูลอาจมีการเปลี่ยนแปลงโดยไม่แจ้งล่วงหน้า บริษัทขอสงวนสิทธิ์ในการยกเลิกคำสั่งซื้อหากสินค้าหมดสต็อก</p>
        <h2 style="font-size:18px;font-weight:700;color:var(--kgm-green-800);margin:24px 0 12px;">2. การชำระเงิน</h2>
        <p>การชำระเงินผ่านการโอนเงิน ลูกค้าต้องอัปโหลดสลิปการโอนเงินภายใน 24 ชั่วโมงหลังสั่งซื้อ</p>
        <h2 style="font-size:18px;font-weight:700;color:var(--kgm-green-800);margin:24px 0 12px;">3. การคืนสินค้า</h2>
        <p>รับคืนสินค้าภายใน 7 วัน หากสินค้ามีตำหนิจากการผลิต</p>
    </div>
</div>
@endsection
