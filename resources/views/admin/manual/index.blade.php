@extends('layouts.admin')
@section('title', 'คู่มือการใช้งานระบบ')

@push('styles')
<style>
/* ── Manual Layout ── */
.manual-wrap { display: grid; grid-template-columns: 240px 1fr; gap: 28px; align-items: start; }
.manual-toc {
    background: white; border-radius: 20px; box-shadow: 0 2px 12px rgba(0,0,0,.06);
    padding: 20px; position: sticky; top: 92px; max-height: calc(100vh - 120px); overflow-y: auto;
}
.manual-toc h4 {
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1.2px;
    color: #999; margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #f0f2f0;
}
.toc-group { margin-bottom: 16px; }
.toc-group-title {
    font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
    color: var(--g600); margin-bottom: 6px; display: flex; align-items: center; gap: 5px;
}
.toc-item {
    display: flex; align-items: center; gap: 7px; padding: 6px 8px; border-radius: 8px;
    color: #666; font-size: 13px; text-decoration: none; transition: all .15s;
}
.toc-item:hover { background: #f0f8f4; color: var(--g700); }
.toc-item i { font-size: 14px; width: 16px; text-align: center; }

/* ── Content ── */
.manual-content { display: flex; flex-direction: column; gap: 28px; }

.manual-section {
    background: white; border-radius: 20px; box-shadow: 0 2px 12px rgba(0,0,0,.06);
    overflow: hidden;
}
.ms-header {
    padding: 24px 28px 20px;
    border-bottom: 1px solid #f0f4f0;
    display: flex; align-items: center; gap: 14px;
}
.ms-icon {
    width: 48px; height: 48px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center; font-size: 22px; flex-shrink: 0;
}
.ms-icon.green  { background: #d1fae5; color: #065f46; }
.ms-icon.gold   { background: #fef3c7; color: #92400e; }
.ms-icon.blue   { background: #dbeafe; color: #1e40af; }
.ms-icon.red    { background: #fee2e2; color: #991b1b; }
.ms-icon.purple { background: #ede9fe; color: #5b21b6; }
.ms-icon.teal   { background: #ccfbf1; color: #0f766e; }
.ms-icon.indigo { background: #e0e7ff; color: #3730a3; }
.ms-icon.rose   { background: #ffe4e6; color: #9f1239; }

.ms-header-text h2 { font-size: 18px; font-weight: 800; color: var(--g800); margin-bottom: 3px; }
.ms-header-text p  { font-size: 13px; color: #888; margin: 0; }

.ms-body { padding: 24px 28px; }

/* Sub-menu card */
.sub-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px; }
.sub-card {
    border: 1.5px solid #f0f2f0; border-radius: 14px; padding: 18px 20px;
    transition: border-color .2s, box-shadow .2s;
}
.sub-card:hover { border-color: var(--g400); box-shadow: 0 4px 16px rgba(64,145,108,.08); }
.sub-card-head {
    display: flex; align-items: center; gap: 10px; margin-bottom: 10px;
}
.sub-card-icon {
    width: 34px; height: 34px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0;
    background: #f0f8f4; color: var(--g600);
}
.sub-card-head h4 { font-size: 14px; font-weight: 700; color: var(--g800); margin: 0; }
.sub-card p { font-size: 13px; color: #666; line-height: 1.7; margin: 0 0 10px; }

/* Steps */
.step-list { display: flex; flex-direction: column; gap: 10px; margin-top: 10px; }
.step-item {
    display: flex; align-items: flex-start; gap: 10px; font-size: 13px; color: #555;
}
.step-num {
    width: 22px; height: 22px; background: var(--g600); color: white;
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 700; flex-shrink: 0; margin-top: 1px;
}
.step-item b { color: var(--g800); }

/* Status pills */
.pill-row { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 10px; }
.pill {
    display: inline-flex; align-items: center; gap: 5px; padding: 4px 12px;
    border-radius: 999px; font-size: 12px; font-weight: 600;
}
.pill-yellow { background: #fef9c3; color: #854d0e; }
.pill-blue   { background: #dbeafe; color: #1e40af; }
.pill-green  { background: #dcfce7; color: #14532d; }
.pill-indigo { background: #e0e7ff; color: #3730a3; }
.pill-purple { background: #f3e8ff; color: #581c87; }
.pill-red    { background: #fee2e2; color: #7f1d1d; }
.pill-gray   { background: #f3f4f6; color: #374151; }

/* Tips */
.tip-box {
    background: #f0fdf4; border: 1.5px solid #bbf7d0; border-radius: 12px;
    padding: 12px 16px; margin-top: 14px; font-size: 13px; color: #166534;
    display: flex; align-items: flex-start; gap: 8px;
}
.tip-box i { flex-shrink: 0; margin-top: 1px; }
.warn-box {
    background: #fffbeb; border: 1.5px solid #fde68a; border-radius: 12px;
    padding: 12px 16px; margin-top: 14px; font-size: 13px; color: #92400e;
    display: flex; align-items: flex-start; gap: 8px;
}
.warn-box i { flex-shrink: 0; margin-top: 1px; }

/* Divider */
.sub-divider { height: 1px; background: #f0f2f0; margin: 20px 0; }

/* Hero */
.manual-hero {
    background: linear-gradient(135deg, var(--g800) 0%, var(--g600) 100%);
    border-radius: 20px; padding: 32px 36px; color: white; margin-bottom: 8px;
    display: flex; align-items: center; gap: 24px;
    box-shadow: 0 8px 32px rgba(29,74,51,.25);
}
.manual-hero-icon {
    width: 72px; height: 72px; background: rgba(255,255,255,.12); border-radius: 20px;
    display: flex; align-items: center; justify-content: center; font-size: 36px; flex-shrink: 0;
}
.manual-hero h1 { font-size: 26px; font-weight: 800; margin-bottom: 6px; }
.manual-hero p  { font-size: 14px; color: rgba(255,255,255,.7); margin: 0; }
.manual-hero-meta { display: flex; gap: 16px; margin-top: 12px; flex-wrap: wrap; }
.manual-hero-meta span {
    background: rgba(255,255,255,.15); border-radius: 999px;
    padding: 4px 14px; font-size: 12px; font-weight: 600;
}

@media(max-width: 900px) {
    .manual-wrap { grid-template-columns: 1fr; }
    .manual-toc { position: static; max-height: none; }
}
</style>
@endpush

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">คู่มือการใช้งานระบบ</div>
        <div class="page-subtitle">KGM Admin System — เวอร์ชันสำหรับผู้ดูแลระบบ</div>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline">
        <i class="bi bi-arrow-left"></i> กลับ Dashboard
    </a>
</div>

{{-- Hero --}}
<div class="manual-hero">
    <div class="manual-hero-icon"><i class="bi bi-book-half"></i></div>
    <div>
        <h1>คู่มือการใช้งานระบบ KGM Admin</h1>
        <p>คู่มือฉบับสมบูรณ์สำหรับผู้ดูแลระบบ ครอบคลุมทุกเมนูและฟังก์ชันการทำงาน</p>
        <div class="manual-hero-meta">
            <span><i class="bi bi-grid-3x3-gap-fill"></i> 7 กลุ่มเมนูหลัก</span>
            <span><i class="bi bi-ui-checks"></i> 25+ ฟังก์ชัน</span>
            <span><i class="bi bi-calendar3"></i> อัปเดต {{ now()->locale('th')->isoFormat('MMMM YYYY') }}</span>
        </div>
    </div>
</div>

<div class="manual-wrap">

    {{-- Table of Contents --}}
    <aside class="manual-toc">
        <h4><i class="bi bi-list-ul"></i> สารบัญ</h4>

        <div class="toc-group">
            <div class="toc-group-title"><i class="bi bi-house"></i> ภาพรวม</div>
            <a href="#overview"   class="toc-item"><i class="bi bi-speedometer2"></i> Dashboard</a>
            <a href="#reports"    class="toc-item"><i class="bi bi-bar-chart-line"></i> รายงาน</a>
        </div>

        <div class="toc-group">
            <div class="toc-group-title"><i class="bi bi-box-seam"></i> สินค้า</div>
            <a href="#products"      class="toc-item"><i class="bi bi-box-seam"></i> จัดการสินค้า</a>
            <a href="#categories"    class="toc-item"><i class="bi bi-tags"></i> หมวดหมู่</a>
            <a href="#product-types" class="toc-item"><i class="bi bi-grid-3x3-gap"></i> ประเภทสินค้า</a>
        </div>

        <div class="toc-group">
            <div class="toc-group-title"><i class="bi bi-receipt"></i> ออเดอร์</div>
            <a href="#orders"   class="toc-item"><i class="bi bi-receipt"></i> คำสั่งซื้อ</a>
            <a href="#quotes"   class="toc-item"><i class="bi bi-file-earmark-text"></i> ใบเสนอราคา</a>
            <a href="#shipping" class="toc-item"><i class="bi bi-truck"></i> อัตราค่าขนส่ง</a>
        </div>

        <div class="toc-group">
            <div class="toc-group-title"><i class="bi bi-megaphone"></i> การตลาด</div>
            <a href="#coupons"       class="toc-item"><i class="bi bi-ticket-perforated"></i> คูปองส่วนลด</a>
            <a href="#flashsale"     class="toc-item"><i class="bi bi-lightning-charge"></i> Flash Sale</a>
            <a href="#banners"       class="toc-item"><i class="bi bi-image"></i> Banner</a>
            <a href="#landing-pages" class="toc-item"><i class="bi bi-easel2"></i> Landing Page</a>
            <a href="#posts"         class="toc-item"><i class="bi bi-newspaper"></i> บทความ/ข่าวสาร</a>
            <a href="#videos"        class="toc-item"><i class="bi bi-play-circle"></i> เรื่องราว</a>
        </div>

        <div class="toc-group">
            <div class="toc-group-title"><i class="bi bi-people"></i> ผู้ใช้งาน</div>
            <a href="#customers" class="toc-item"><i class="bi bi-people"></i> ลูกค้า</a>
            <a href="#users"     class="toc-item"><i class="bi bi-person-badge"></i> ผู้ใช้งานระบบ</a>
            <a href="#reviews"   class="toc-item"><i class="bi bi-star-half"></i> รีวิวสินค้า</a>
            <a href="#contacts"  class="toc-item"><i class="bi bi-chat-left-text"></i> ข้อความติดต่อ</a>
            <a href="#careers"   class="toc-item"><i class="bi bi-briefcase"></i> ใบสมัครงาน</a>
            <a href="#dealers"   class="toc-item"><i class="bi bi-shop"></i> ตัวแทนจำหน่าย</a>
        </div>

        <div class="toc-group">
            <div class="toc-group-title"><i class="bi bi-gear"></i> ระบบ</div>
            <a href="#showrooms" class="toc-item"><i class="bi bi-geo-alt"></i> สาขา/โชว์รูม</a>
            <a href="#settings"  class="toc-item"><i class="bi bi-gear"></i> ตั้งค่าระบบ</a>
            <a href="#logs"      class="toc-item"><i class="bi bi-journal-text"></i> Audit Log</a>
        </div>
    </aside>

    {{-- Main Content --}}
    <div class="manual-content">

        {{-- ════════════════════════ OVERVIEW ════════════════════════ --}}

        {{-- Dashboard --}}
        <div class="manual-section" id="overview">
            <div class="ms-header">
                <div class="ms-icon green"><i class="bi bi-speedometer2"></i></div>
                <div class="ms-header-text">
                    <h2>Dashboard — ภาพรวมระบบ</h2>
                    <p>หน้าแรกหลังเข้าสู่ระบบ แสดงข้อมูลสรุปทั้งหมดของร้านในวันนั้น</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-graph-up"></i></div>
                            <h4>สถิติวันนี้</h4>
                        </div>
                        <p>แสดงจำนวนออเดอร์วันนี้, ยอดขายวันนี้ (บาท), ออเดอร์รอดำเนินการ และจำนวนสมาชิกทั้งหมด</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-clock-history"></i></div>
                            <h4>สถานะออเดอร์ล่าสุด</h4>
                        </div>
                        <p>แสดงรายการคำสั่งซื้อที่อัปโหลดสลิปแล้วรอตรวจสอบ พร้อมปุ่มลัดตรวจสลิปโดยตรง</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-bar-chart-fill"></i></div>
                            <h4>กราฟยอดขาย 30 วัน</h4>
                        </div>
                        <p>กราฟแท่งแสดงยอดขายรายวันย้อนหลัง 30 วัน ช่วยติดตามแนวโน้มรายได้</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-box-seam"></i></div>
                            <h4>สินค้าขายดี</h4>
                        </div>
                        <p>Top 5 สินค้าที่มียอดขายสูงสุด พร้อมจำนวนที่ขายได้และยอดเงิน</p>
                    </div>
                </div>
                <div class="tip-box">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>ทุกครั้งที่เข้าสู่ระบบ ให้ตรวจสอบ Dashboard ก่อนเพื่อดูออเดอร์ที่รอตรวจสลิปในทันที</span>
                </div>
            </div>
        </div>

        {{-- Reports --}}
        <div class="manual-section" id="reports">
            <div class="ms-header">
                <div class="ms-icon blue"><i class="bi bi-bar-chart-line"></i></div>
                <div class="ms-header-text">
                    <h2>รายงาน</h2>
                    <p>สรุปยอดขาย สินค้า และพฤติกรรมลูกค้าแบบละเอียด</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-calendar3"></i></div>
                            <h4>รายงานตามช่วงเวลา</h4>
                        </div>
                        <p>เลือกช่วงวันที่เพื่อดูยอดขาย จำนวนออเดอร์ และรายได้รวมในช่วงนั้น</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-trophy"></i></div>
                            <h4>สินค้าขายดีสูงสุด</h4>
                        </div>
                        <p>จัดอันดับสินค้าที่มียอดขายสูงสุดในช่วงเวลาที่เลือก ใช้วางแผนสต็อกและโปรโมชัน</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════ PRODUCTS ════════════════════════ --}}

        <div class="manual-section" id="products">
            <div class="ms-header">
                <div class="ms-icon green"><i class="bi bi-box-seam"></i></div>
                <div class="ms-header-text">
                    <h2>จัดการสินค้า</h2>
                    <p>เพิ่ม แก้ไข และลบสินค้า พร้อมจัดการรูปภาพและตัวเลือกสินค้า</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-plus-circle"></i></div>
                            <h4>เพิ่มสินค้าใหม่</h4>
                        </div>
                        <p>กรอกข้อมูลสินค้า ตั้งราคา เลือกหมวดหมู่ ประเภท และเพิ่มรูปภาพ</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>คลิก <b>เพิ่มสินค้า</b> มุมขวาบน</span></div>
                            <div class="step-item"><div class="step-num">2</div><span>กรอกชื่อ, คำอธิบาย, ราคา, หมวดหมู่</span></div>
                            <div class="step-item"><div class="step-num">3</div><span>อัปโหลดรูปภาพสินค้า (รองรับหลายรูป)</span></div>
                            <div class="step-item"><div class="step-num">4</div><span>เพิ่มตัวเลือกสินค้า เช่น ไซซ์ สี</span></div>
                            <div class="step-item"><div class="step-num">5</div><span>คลิก <b>บันทึก</b></span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-images"></i></div>
                            <h4>จัดการรูปภาพสินค้า</h4>
                        </div>
                        <p>อัปโหลดรูปหลายภาพ กำหนดภาพหลัก (cover) และลบรูปที่ไม่ต้องการ</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>เข้าหน้าแก้ไขสินค้า</span></div>
                            <div class="step-item"><div class="step-num">2</div><span>ส่วน <b>รูปภาพ</b> — ลากหรือคลิกเพื่ออัปโหลด</span></div>
                            <div class="step-item"><div class="step-num">3</div><span>คลิกรูปเพื่อตั้งเป็นภาพหลัก</span></div>
                            <div class="step-item"><div class="step-num">4</div><span>คลิกไอคอนถังขยะเพื่อลบรูป</span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-sliders"></i></div>
                            <h4>ตัวเลือกสินค้า (Variants)</h4>
                        </div>
                        <p>กำหนดตัวเลือกสินค้า เช่น ไซซ์ S/M/L/XL หรือสี พร้อมตั้งราคาและสต็อกแยกตามตัวเลือก</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-toggle-on"></i></div>
                            <h4>สถานะสินค้า</h4>
                        </div>
                        <p>เปิด/ปิดการแสดงสินค้าหน้าร้าน กำหนดเป็น "สินค้าแนะนำ" หรือ "สินค้าใหม่" ได้ทันที</p>
                    </div>
                </div>
                <div class="warn-box">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>การลบสินค้าจะลบข้อมูลทั้งหมดรวมถึงรูปภาพและตัวเลือก ควรปิดการแสดงผลแทนการลบหากสินค้าเคยมีออเดอร์</span>
                </div>
            </div>
        </div>

        {{-- Categories --}}
        <div class="manual-section" id="categories">
            <div class="ms-header">
                <div class="ms-icon gold"><i class="bi bi-tags"></i></div>
                <div class="ms-header-text">
                    <h2>หมวดหมู่สินค้า</h2>
                    <p>จัดกลุ่มสินค้าเป็นหมวดหมู่หลักและหมวดหมู่ย่อย รองรับโครงสร้างแบบ 2 ชั้น</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-folder-plus"></i></div>
                            <h4>หมวดหมู่หลัก</h4>
                        </div>
                        <p>สร้างหมวดหมู่ระดับบน เช่น "ชุดนักเรียน", "ยูนิฟอร์ม" ใส่ชื่อและ Slug สำหรับ URL</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-folder2-open"></i></div>
                            <h4>หมวดหมู่ย่อย</h4>
                        </div>
                        <p>สร้างหมวดหมู่ย่อยภายใต้หมวดหมู่หลัก เช่น "ชุดนักเรียนชาย", "ชุดนักเรียนหญิง"</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>คลิก <b>เพิ่มหมวดหมู่</b></span></div>
                            <div class="step-item"><div class="step-num">2</div><span>ใส่ชื่อหมวดหมู่</span></div>
                            <div class="step-item"><div class="step-num">3</div><span>เลือก <b>หมวดหมู่แม่</b> หากต้องการให้เป็นหมวดย่อย</span></div>
                            <div class="step-item"><div class="step-num">4</div><span>บันทึก</span></div>
                        </div>
                    </div>
                </div>
                <div class="tip-box">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>Slug จะถูกใช้ใน URL เช่น <code>/shop/category/school-uniform</code> ควรใช้ภาษาอังกฤษตัวเล็กและขีดกลาง</span>
                </div>
            </div>
        </div>

        {{-- Product Types --}}
        <div class="manual-section" id="product-types">
            <div class="ms-header">
                <div class="ms-icon teal"><i class="bi bi-grid-3x3-gap"></i></div>
                <div class="ms-header-text">
                    <h2>ประเภทสินค้า</h2>
                    <p>แบ่งสินค้าตามประเภทการใช้งาน เช่น นักเรียน ยูนิฟอร์ม มีไอคอนและรูปภาพแสดงในเมนูหน้าร้าน</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-card-image"></i></div>
                            <h4>เพิ่มประเภทสินค้า</h4>
                        </div>
                        <p>กำหนดชื่อ, Slug, รูปไอคอน และลำดับการแสดงผลในเมนูหน้าร้าน</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-sort-numeric-down"></i></div>
                            <h4>จัดลำดับ</h4>
                        </div>
                        <p>กำหนด "ลำดับ" (sort order) เพื่อควบคุมว่าประเภทไหนจะแสดงก่อนในเมนู Dropdown</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════ ORDERS ════════════════════════ --}}

        <div class="manual-section" id="orders">
            <div class="ms-header">
                <div class="ms-icon red"><i class="bi bi-receipt"></i></div>
                <div class="ms-header-text">
                    <h2>คำสั่งซื้อ</h2>
                    <p>จัดการออเดอร์ทั้งหมด ตรวจสอบสลิปการชำระเงิน และอัปเดตสถานะการจัดส่ง</p>
                </div>
            </div>
            <div class="ms-body">

                <h3 style="font-size:14px;font-weight:700;color:var(--g800);margin-bottom:14px;"><i class="bi bi-info-circle"></i> สถานะออเดอร์</h3>
                <div class="pill-row" style="margin-bottom:20px;">
                    <span class="pill pill-yellow"><i class="bi bi-circle-fill" style="font-size:6px;"></i> รอชำระเงิน</span>
                    <span class="pill pill-blue"><i class="bi bi-circle-fill" style="font-size:6px;"></i> อัปโหลดสลิปแล้ว</span>
                    <span class="pill pill-indigo"><i class="bi bi-circle-fill" style="font-size:6px;"></i> ชำระเงินแล้ว</span>
                    <span class="pill pill-purple"><i class="bi bi-circle-fill" style="font-size:6px;"></i> กำลังจัดส่ง</span>
                    <span class="pill pill-green"><i class="bi bi-circle-fill" style="font-size:6px;"></i> จัดส่งแล้ว</span>
                    <span class="pill pill-red"><i class="bi bi-circle-fill" style="font-size:6px;"></i> ยกเลิก</span>
                </div>

                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-credit-card"></i></div>
                            <h4>ตรวจสอบสลิปชำระเงิน</h4>
                        </div>
                        <p>ลูกค้าอัปโหลดสลิป ระบบจะแจ้งเตือนด้วยจุดแดงที่เมนูคำสั่งซื้อ</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>คลิกเข้าออเดอร์ที่มีสถานะ <b>อัปโหลดสลิปแล้ว</b></span></div>
                            <div class="step-item"><div class="step-num">2</div><span>ดูรูปสลิปที่ลูกค้าแนบ</span></div>
                            <div class="step-item"><div class="step-num">3</div><span>คลิก <b>ยืนยันการชำระเงิน</b> หรือ <b>ปฏิเสธ</b></span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-truck"></i></div>
                            <h4>อัปเดตการจัดส่ง</h4>
                        </div>
                        <p>เมื่อส่งสินค้าแล้ว ให้ใส่เลข Tracking และเลือกบริษัทขนส่ง ลูกค้าจะได้รับแจ้ง</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>เปิดออเดอร์ที่ชำระเงินแล้ว</span></div>
                            <div class="step-item"><div class="step-num">2</div><span>คลิก <b>อัปเดตการจัดส่ง</b></span></div>
                            <div class="step-item"><div class="step-num">3</div><span>ใส่เลข Tracking + เลือกบริษัทขนส่ง</span></div>
                            <div class="step-item"><div class="step-num">4</div><span>บันทึก — สถานะเปลี่ยนเป็น <b>กำลังจัดส่ง</b></span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-funnel"></i></div>
                            <h4>กรองและค้นหา</h4>
                        </div>
                        <p>กรองออเดอร์ตามสถานะ วันที่ หรือค้นหาด้วยชื่อลูกค้า เบอร์โทร หรือเลขออเดอร์</p>
                    </div>
                </div>
                <div class="warn-box">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>ตรวจสอบสลิปให้ถูกต้องก่อนกด "ยืนยัน" เพราะระบบจะเปลี่ยนสถานะทันทีและแจ้งลูกค้าอัตโนมัติ</span>
                </div>
            </div>
        </div>

        {{-- Quotes --}}
        <div class="manual-section" id="quotes">
            <div class="ms-header">
                <div class="ms-icon indigo"><i class="bi bi-file-earmark-text"></i></div>
                <div class="ms-header-text">
                    <h2>ใบเสนอราคา</h2>
                    <p>รับและตอบกลับคำขอใบเสนอราคาจากลูกค้าองค์กร/โรงเรียนที่สั่งซื้อจำนวนมาก</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-envelope-open"></i></div>
                            <h4>ดูคำขอใบเสนอราคา</h4>
                        </div>
                        <p>แสดงรายละเอียดสินค้าที่ลูกค้าต้องการ, จำนวน, ข้อความพิเศษ และข้อมูลติดต่อ</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-reply"></i></div>
                            <h4>ตอบกลับใบเสนอราคา</h4>
                        </div>
                        <p>พิมพ์ราคาและเงื่อนไขในช่องตอบกลับ ระบบจะส่งอีเมลแจ้งลูกค้าโดยอัตโนมัติ</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>คลิกเข้าใบเสนอราคาที่ต้องการ</span></div>
                            <div class="step-item"><div class="step-num">2</div><span>กรอกราคาและรายละเอียดในช่อง <b>ตอบกลับ</b></span></div>
                            <div class="step-item"><div class="step-num">3</div><span>คลิก <b>ส่งใบเสนอราคา</b></span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-patch-check"></i></div>
                            <h4>อัปเดตสถานะ</h4>
                        </div>
                        <p>เปลี่ยนสถานะใบเสนอราคาจาก รอดำเนินการ → ตอบกลับแล้ว → อนุมัติ/ปฏิเสธ</p>
                        <div class="pill-row">
                            <span class="pill pill-yellow">รอดำเนินการ</span>
                            <span class="pill pill-blue">ตอบกลับแล้ว</span>
                            <span class="pill pill-green">อนุมัติ</span>
                            <span class="pill pill-red">ปฏิเสธ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shipping --}}
        <div class="manual-section" id="shipping">
            <div class="ms-header">
                <div class="ms-icon teal"><i class="bi bi-truck"></i></div>
                <div class="ms-header-text">
                    <h2>อัตราค่าขนส่ง</h2>
                    <p>กำหนดค่าจัดส่งตามน้ำหนักหรือยอดสั่งซื้อ และตั้งค่าส่งฟรีเมื่อถึงขั้นต่ำ</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-table"></i></div>
                            <h4>ตารางค่าขนส่ง</h4>
                        </div>
                        <p>ตั้งราคาค่าขนส่งแบบขั้นบันได เช่น น้ำหนัก 0-1 กก. = 50 บาท, 1-3 กก. = 80 บาท</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-gift"></i></div>
                            <h4>ส่งฟรีเมื่อครบขั้นต่ำ</h4>
                        </div>
                        <p>กำหนดยอดสั่งซื้อขั้นต่ำที่จะได้รับสิทธิ์ส่งฟรี เช่น สั่งครบ 1,000 บาท ส่งฟรี</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════ MARKETING ════════════════════════ --}}

        {{-- Coupons --}}
        <div class="manual-section" id="coupons">
            <div class="ms-header">
                <div class="ms-icon gold"><i class="bi bi-ticket-perforated"></i></div>
                <div class="ms-header-text">
                    <h2>คูปองส่วนลด</h2>
                    <p>สร้างและจัดการโค้ดส่วนลดสำหรับแคมเปญ โปรโมชัน หรือมอบให้ลูกค้าพิเศษ</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-plus-circle"></i></div>
                            <h4>สร้างคูปอง</h4>
                        </div>
                        <p>กำหนดโค้ด, ประเภทส่วนลด (บาท/เปอร์เซ็นต์), มูลค่า, จำนวนครั้งที่ใช้ได้ และวันหมดอายุ</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>คลิก <b>สร้างคูปอง</b></span></div>
                            <div class="step-item"><div class="step-num">2</div><span>กำหนด <b>รหัสคูปอง</b> (เช่น WELCOME20)</span></div>
                            <div class="step-item"><div class="step-num">3</div><span>เลือกประเภท: ลดเป็น <b>บาท</b> หรือ <b>เปอร์เซ็นต์</b></span></div>
                            <div class="step-item"><div class="step-num">4</div><span>กำหนดจำนวนครั้งที่ใช้ได้และวันหมดอายุ</span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-bar-chart"></i></div>
                            <h4>ติดตามการใช้งาน</h4>
                        </div>
                        <p>ดูจำนวนครั้งที่คูปองถูกใช้แล้ว เทียบกับจำนวนสูงสุดที่กำหนด และสถานะ เปิด/ปิด</p>
                    </div>
                </div>
                <div class="tip-box">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>ตั้งค่า "ยอดขั้นต่ำ" เพื่อกำหนดให้ใช้คูปองได้เฉพาะเมื่อสั่งซื้อถึงจำนวนที่กำหนด</span>
                </div>
            </div>
        </div>

        {{-- Flash Sale --}}
        <div class="manual-section" id="flashsale">
            <div class="ms-header">
                <div class="ms-icon red"><i class="bi bi-lightning-charge"></i></div>
                <div class="ms-header-text">
                    <h2>Flash Sale</h2>
                    <p>สร้างแคมเปญลดราคาพิเศษแบบมีเวลาจำกัด สินค้าจะแสดง Countdown Timer ในหน้าร้าน</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-calendar-event"></i></div>
                            <h4>สร้าง Flash Sale</h4>
                        </div>
                        <p>กำหนดชื่อแคมเปญ, วันและเวลาเริ่ม-สิ้นสุด และอัปโหลดรูป Banner ประกอบ</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>คลิก <b>สร้าง Flash Sale</b></span></div>
                            <div class="step-item"><div class="step-num">2</div><span>ใส่ชื่อ, วันที่เริ่ม-สิ้นสุด</span></div>
                            <div class="step-item"><div class="step-num">3</div><span>เพิ่มสินค้าและกำหนดราคา Flash</span></div>
                            <div class="step-item"><div class="step-num">4</div><span>อัปโหลดรูป Banner (ถ้ามี)</span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-tags"></i></div>
                            <h4>เพิ่มสินค้าใน Flash Sale</h4>
                        </div>
                        <p>เลือกสินค้าและตั้งราคา Flash Sale แยกจากราคาปกติ ระบบจะแสดง "ลด XX%" อัตโนมัติ</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-eye"></i></div>
                            <h4>การแสดงผลหน้าร้าน</h4>
                        </div>
                        <p>เมื่อ Flash Sale ยังไม่หมดเวลา สินค้าจะแสดง Countdown Timer, แบนเนอร์แจ้งเตือน และราคาพิเศษ</p>
                    </div>
                </div>
                <div class="warn-box">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>สามารถมี Flash Sale ที่ Active ได้ครั้งละ 1 แคมเปญเท่านั้น ควรวางแผนช่วงเวลาให้ไม่ทับซ้อนกัน</span>
                </div>
            </div>
        </div>

        {{-- Banners --}}
        <div class="manual-section" id="banners">
            <div class="ms-header">
                <div class="ms-icon blue"><i class="bi bi-image"></i></div>
                <div class="ms-header-text">
                    <h2>จัดการ Banner</h2>
                    <p>อัปโหลดและจัดการสไลด์โชว์ Banner หน้าแรกของเว็บไซต์</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-upload"></i></div>
                            <h4>เพิ่ม Banner</h4>
                        </div>
                        <p>อัปโหลดรูปภาพ Banner พร้อมกำหนด URL ปลายทางเมื่อคลิก, ชื่อและลำดับการแสดง</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>คลิก <b>เพิ่ม Banner</b></span></div>
                            <div class="step-item"><div class="step-num">2</div><span>อัปโหลดรูป (แนะนำ 1920×600 px)</span></div>
                            <div class="step-item"><div class="step-num">3</div><span>ใส่ URL ปลายทาง (ถ้ากดแล้วจะไปหน้าไหน)</span></div>
                            <div class="step-item"><div class="step-num">4</div><span>กำหนดลำดับและสถานะ เปิด/ปิด</span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-sort-numeric-down"></i></div>
                            <h4>จัดลำดับ Banner</h4>
                        </div>
                        <p>กำหนดตัวเลขลำดับ — Banner ที่มีตัวเลขน้อยกว่าจะแสดงก่อน ปิดการแสดงชั่วคราวได้โดยไม่ต้องลบ</p>
                    </div>
                </div>
                <div class="tip-box">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>ขนาดรูป Banner ที่เหมาะสม: <b>1920 × 600 px</b> สำหรับ Desktop และ <b>768 × 400 px</b> สำหรับ Mobile</span>
                </div>
            </div>
        </div>

        {{-- Landing Pages --}}
        <div class="manual-section" id="landing-pages">
            <div class="ms-header">
                <div class="ms-icon purple"><i class="bi bi-easel2"></i></div>
                <div class="ms-header-text">
                    <h2>Landing Page (Popup)</h2>
                    <p>อัปโหลดรูปป็อปอัพที่จะแสดงต้อนรับลูกค้าเมื่อเข้าเว็บไซต์ครั้งแรกในรอบ 24 ชั่วโมง</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-window-plus"></i></div>
                            <h4>เพิ่ม Popup</h4>
                        </div>
                        <p>อัปโหลดรูปภาพ Popup ที่ต้องการให้แสดง เหมาะสำหรับประกาศโปรโมชัน, สินค้าใหม่ หรือข่าวสาร</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-toggle-on"></i></div>
                            <h4>เปิด/ปิด Popup</h4>
                        </div>
                        <p>สามารถมีหลาย Landing Page แต่เปิดใช้งานได้ครั้งละ 1 รูปเท่านั้น คลิก Toggle เพื่อเปิด/ปิด</p>
                    </div>
                </div>
                <div class="tip-box">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>Popup จะแสดงซ้ำทุก 24 ชั่วโมง — ใช้สำหรับโปรโมชันระยะสั้นหรือประกาศสำคัญ</span>
                </div>
            </div>
        </div>

        {{-- Posts --}}
        <div class="manual-section" id="posts">
            <div class="ms-header">
                <div class="ms-icon teal"><i class="bi bi-newspaper"></i></div>
                <div class="ms-header-text">
                    <h2>บทความ / ข่าวสาร</h2>
                    <p>เขียนและจัดการบทความ ข่าวสาร และเนื้อหาของบริษัทที่แสดงในหน้าข่าวสาร</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-pencil-square"></i></div>
                            <h4>เขียนบทความ</h4>
                        </div>
                        <p>มี Editor สำหรับพิมพ์เนื้อหา ใส่หัวข้อ, รูปภาพปก, หมวดหมู่ และ Slug สำหรับ URL</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>คลิก <b>เขียนบทความใหม่</b></span></div>
                            <div class="step-item"><div class="step-num">2</div><span>ใส่หัวข้อและเนื้อหา</span></div>
                            <div class="step-item"><div class="step-num">3</div><span>อัปโหลดรูปปก (Thumbnail)</span></div>
                            <div class="step-item"><div class="step-num">4</div><span>เลือกสถานะ: <b>ร่าง</b> หรือ <b>เผยแพร่</b></span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-toggle-on"></i></div>
                            <h4>เผยแพร่/ซ่อนบทความ</h4>
                        </div>
                        <p>บทความสถานะ "ร่าง" จะไม่แสดงหน้าร้าน เปลี่ยนเป็น "เผยแพร่" เมื่อพร้อม</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Videos --}}
        <div class="manual-section" id="videos">
            <div class="ms-header">
                <div class="ms-icon rose"><i class="bi bi-play-circle"></i></div>
                <div class="ms-header-text">
                    <h2>เรื่องราวที่น่าสนใจ (วิดีโอ)</h2>
                    <p>เพิ่มลิงก์วิดีโอ YouTube หรือ Facebook เพื่อแสดงในส่วน "เรื่องราวที่น่าสนใจ" หน้าเว็บ</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-youtube"></i></div>
                            <h4>เพิ่มวิดีโอ</h4>
                        </div>
                        <p>วางลิงก์ YouTube หรือ Facebook Video พร้อมใส่ชื่อและคำอธิบาย ระบบจะดึง Thumbnail มาแสดงอัตโนมัติ</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-sort-down"></i></div>
                            <h4>จัดลำดับวิดีโอ</h4>
                        </div>
                        <p>กำหนดลำดับการแสดงผล วิดีโอที่ลำดับน้อยกว่าจะแสดงก่อนในหน้าร้าน</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════ USERS ════════════════════════ --}}

        {{-- Customers --}}
        <div class="manual-section" id="customers">
            <div class="ms-header">
                <div class="ms-icon blue"><i class="bi bi-people"></i></div>
                <div class="ms-header-text">
                    <h2>ลูกค้า</h2>
                    <p>ดูข้อมูลสมาชิก ประวัติการสั่งซื้อ และจัดการที่อยู่จัดส่งของลูกค้า</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-person-lines-fill"></i></div>
                            <h4>ข้อมูลลูกค้า</h4>
                        </div>
                        <p>ดูชื่อ, อีเมล, เบอร์โทร, วันที่สมัคร และออเดอร์ทั้งหมดของลูกค้าแต่ละราย</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-geo-alt"></i></div>
                            <h4>จัดการที่อยู่จัดส่ง</h4>
                        </div>
                        <p>เพิ่ม แก้ไข หรือลบที่อยู่จัดส่งของลูกค้าในกรณีที่ลูกค้าติดต่อขอแก้ไขข้อมูล</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-person-x"></i></div>
                            <h4>ระงับ/ลบบัญชี</h4>
                        </div>
                        <p>ปิดการใช้งานบัญชีลูกค้าที่มีพฤติกรรมผิดปกติ หรือลบบัญชีตามคำขอ (PDPA)</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- System Users --}}
        <div class="manual-section" id="users">
            <div class="ms-header">
                <div class="ms-icon purple"><i class="bi bi-person-badge"></i></div>
                <div class="ms-header-text">
                    <h2>ผู้ใช้งานระบบ (Admin)</h2>
                    <p>จัดการบัญชีผู้ดูแลระบบที่สามารถเข้าถึง Admin Panel ได้</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-person-plus"></i></div>
                            <h4>เพิ่มผู้ดูแลระบบ</h4>
                        </div>
                        <p>สร้างบัญชี Admin ใหม่ กำหนดชื่อ อีเมล และรหัสผ่าน สำหรับทีมงานที่ต้องเข้าระบบหลังบ้าน</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>คลิก <b>เพิ่มผู้ใช้งาน</b></span></div>
                            <div class="step-item"><div class="step-num">2</div><span>กรอกชื่อ, อีเมล, รหัสผ่าน</span></div>
                            <div class="step-item"><div class="step-num">3</div><span>บันทึก — แจ้งรหัสผ่านให้ผู้ใช้ด้วยตัวเอง</span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-key"></i></div>
                            <h4>เปลี่ยนรหัสผ่าน</h4>
                        </div>
                        <p>แก้ไขข้อมูลและเปลี่ยนรหัสผ่านของ Admin ได้ในหน้าแก้ไขผู้ใช้งาน</p>
                    </div>
                </div>
                <div class="warn-box">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>บัญชี Admin มีสิทธิ์เข้าถึงข้อมูลทั้งหมด — มอบบัญชีเฉพาะกับบุคคลที่ไว้วางใจได้เท่านั้น</span>
                </div>
            </div>
        </div>

        {{-- Reviews --}}
        <div class="manual-section" id="reviews">
            <div class="ms-header">
                <div class="ms-icon gold"><i class="bi bi-star-half"></i></div>
                <div class="ms-header-text">
                    <h2>รีวิวสินค้า</h2>
                    <p>ตรวจสอบและอนุมัติรีวิวจากลูกค้าก่อนแสดงในหน้าสินค้า</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-check-circle"></i></div>
                            <h4>อนุมัติรีวิว</h4>
                        </div>
                        <p>รีวิวใหม่จะยังไม่แสดงหน้าร้านจนกว่า Admin จะอนุมัติ คลิก <b>อนุมัติ</b> เพื่อให้รีวิวแสดงสาธารณะ</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-x-circle"></i></div>
                            <h4>ปฏิเสธ/ลบรีวิว</h4>
                        </div>
                        <p>ปฏิเสธรีวิวที่มีเนื้อหาไม่เหมาะสม หรือลบรีวิวที่ผ่านแล้วออกจากระบบ</p>
                    </div>
                </div>
                <div class="tip-box">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>จุดแดงที่เมนูรีวิวสินค้าจะแสดงเมื่อมีรีวิวรอการอนุมัติ — ตรวจสอบทุกวันเพื่อให้ลูกค้าเห็นรีวิวได้เร็ว</span>
                </div>
            </div>
        </div>

        {{-- Contacts --}}
        <div class="manual-section" id="contacts">
            <div class="ms-header">
                <div class="ms-icon teal"><i class="bi bi-chat-left-text"></i></div>
                <div class="ms-header-text">
                    <h2>ข้อความติดต่อ</h2>
                    <p>รับและตอบกลับข้อความที่ลูกค้าส่งผ่านหน้า "ติดต่อเรา" ของเว็บไซต์</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-envelope-open"></i></div>
                            <h4>ดูข้อความ</h4>
                        </div>
                        <p>ดูข้อความพร้อมชื่อ, อีเมล, เบอร์โทร และเนื้อหาที่ลูกค้าส่งมา ข้อความที่ยังไม่อ่านจะแสดงจุดแดง</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-reply-fill"></i></div>
                            <h4>ตอบกลับทางอีเมล</h4>
                        </div>
                        <p>พิมพ์ข้อความตอบกลับในระบบ ระบบจะส่งอีเมลไปยังลูกค้าโดยตรง</p>
                        <div class="step-list">
                            <div class="step-item"><div class="step-num">1</div><span>เปิดข้อความที่ต้องการตอบ</span></div>
                            <div class="step-item"><div class="step-num">2</div><span>พิมพ์ข้อความตอบกลับในช่องที่กำหนด</span></div>
                            <div class="step-item"><div class="step-num">3</div><span>คลิก <b>ส่งอีเมลตอบกลับ</b></span></div>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-trash"></i></div>
                            <h4>ลบข้อความ</h4>
                        </div>
                        <p>ลบข้อความที่ดำเนินการเสร็จแล้วหรือ Spam ออกจากระบบเพื่อให้กล่องข้อความสะอาด</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Careers --}}
        <div class="manual-section" id="careers">
            <div class="ms-header">
                <div class="ms-icon indigo"><i class="bi bi-briefcase"></i></div>
                <div class="ms-header-text">
                    <h2>ใบสมัครงาน</h2>
                    <p>ประกาศตำแหน่งงานว่างและจัดการใบสมัครที่ส่งเข้ามาผ่านเว็บไซต์</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-megaphone"></i></div>
                            <h4>ประกาศตำแหน่งงาน</h4>
                        </div>
                        <p>สร้างประกาศรับสมัครงาน ระบุตำแหน่ง, คุณสมบัติ, เงินเดือน และวันที่ปิดรับสมัคร</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-person-check"></i></div>
                            <h4>จัดการใบสมัคร</h4>
                        </div>
                        <p>ดูรายชื่อผู้สมัคร, ดาวน์โหลด Resume และอัปเดตสถานะใบสมัครของแต่ละคน</p>
                        <div class="pill-row">
                            <span class="pill pill-yellow">รอพิจารณา</span>
                            <span class="pill pill-blue">นัดสัมภาษณ์</span>
                            <span class="pill pill-green">รับเข้าทำงาน</span>
                            <span class="pill pill-red">ไม่ผ่าน</span>
                        </div>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-download"></i></div>
                            <h4>ดาวน์โหลด Resume</h4>
                        </div>
                        <p>คลิกปุ่มดาวน์โหลดเพื่อรับไฟล์ Resume ที่ผู้สมัครแนบมา (PDF/Word)</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dealers --}}
        <div class="manual-section" id="dealers">
            <div class="ms-header">
                <div class="ms-icon green"><i class="bi bi-shop"></i></div>
                <div class="ms-header-text">
                    <h2>ตัวแทนจำหน่าย</h2>
                    <p>จัดการคำขอสมัครเป็นตัวแทนจำหน่ายจากพ่อค้า/แม่ค้าที่สนใจ</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-list-check"></i></div>
                            <h4>ดูรายการคำขอ</h4>
                        </div>
                        <p>รายชื่อผู้ที่กรอกแบบฟอร์มสมัครเป็นตัวแทน พร้อมข้อมูลชื่อ, ที่อยู่ร้าน และช่องทางการขาย</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-patch-check"></i></div>
                            <h4>อนุมัติ/ปฏิเสธ</h4>
                        </div>
                        <p>เปลี่ยนสถานะคำขอเป็น อนุมัติ หรือ ปฏิเสธ เพื่อจัดการรายชื่อตัวแทนจำหน่าย</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════ SYSTEM ════════════════════════ --}}

        {{-- Showrooms --}}
        <div class="manual-section" id="showrooms">
            <div class="ms-header">
                <div class="ms-icon teal"><i class="bi bi-geo-alt"></i></div>
                <div class="ms-header-text">
                    <h2>สาขา / โชว์รูม</h2>
                    <p>จัดการข้อมูลสาขาและโชว์รูมที่แสดงในหน้า "สาขา" ของเว็บไซต์</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-building"></i></div>
                            <h4>เพิ่มสาขา</h4>
                        </div>
                        <p>ใส่ชื่อสาขา, ที่อยู่, เบอร์โทร, เวลาทำการ และลิงก์ Google Maps</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-camera"></i></div>
                            <h4>รูปภาพสาขา</h4>
                        </div>
                        <p>อัปโหลดรูปภาพของสาขา/โชว์รูม เพื่อให้ลูกค้าสามารถจดจำสถานที่ได้ง่ายขึ้น</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Settings --}}
        <div class="manual-section" id="settings">
            <div class="ms-header">
                <div class="ms-icon indigo"><i class="bi bi-gear"></i></div>
                <div class="ms-header-text">
                    <h2>ตั้งค่าระบบ</h2>
                    <p>ปรับแต่งข้อมูลทั่วไปของเว็บไซต์ ข้อมูลบริษัท และการตั้งค่าต่างๆ</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-building-gear"></i></div>
                            <h4>ข้อมูลบริษัท</h4>
                        </div>
                        <p>แก้ไขชื่อบริษัท, ที่อยู่, เบอร์โทร, อีเมลติดต่อ และ Social Media Links</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-truck"></i></div>
                            <h4>ตั้งค่าการจัดส่ง</h4>
                        </div>
                        <p>กำหนดบริษัทขนส่งที่ใช้บริการ และตั้งค่าการคำนวณค่าขนส่งอัตโนมัติ</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-envelope-at"></i></div>
                            <h4>การแจ้งเตือนอีเมล</h4>
                        </div>
                        <p>กำหนดอีเมลที่จะรับการแจ้งเตือนเมื่อมีออเดอร์ใหม่, ใบเสนอราคา หรือข้อความติดต่อ</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-shield-check"></i></div>
                            <h4>PDPA / Cookie Consent</h4>
                        </div>
                        <p>ดูสถิติการยินยอมคุกกี้ของผู้เข้าชม และตั้งค่านโยบายคุกกี้ของเว็บไซต์</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Logs --}}
        <div class="manual-section" id="logs">
            <div class="ms-header">
                <div class="ms-icon rose"><i class="bi bi-journal-text"></i></div>
                <div class="ms-header-text">
                    <h2>Audit Log</h2>
                    <p>บันทึกทุกการกระทำของ Admin ในระบบ เพื่อตรวจสอบย้อนหลังเมื่อเกิดปัญหา</p>
                </div>
            </div>
            <div class="ms-body">
                <div class="sub-grid">
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-clock-history"></i></div>
                            <h4>ประวัติการดำเนินการ</h4>
                        </div>
                        <p>ดูว่า Admin คนใดทำอะไร เมื่อไหร่ เช่น เพิ่มสินค้า แก้ไขราคา อนุมัติออเดอร์</p>
                    </div>
                    <div class="sub-card">
                        <div class="sub-card-head">
                            <div class="sub-card-icon"><i class="bi bi-shield-lock"></i></div>
                            <h4>Cookie Consent Log</h4>
                        </div>
                        <p>บันทึกการยินยอม/ปฏิเสธคุกกี้ของผู้เข้าชม เพื่อให้เป็นไปตามมาตรฐาน PDPA</p>
                    </div>
                </div>
                <div class="tip-box">
                    <i class="bi bi-lightbulb-fill"></i>
                    <span>Audit Log ช่วยตรวจสอบย้อนหลังเมื่อเกิดปัญหา เช่น สินค้าถูกลบโดยไม่ทราบสาเหตุ</span>
                </div>
            </div>
        </div>

        {{-- Footer note --}}
        <div style="background:white;border-radius:20px;padding:24px 28px;box-shadow:0 2px 12px rgba(0,0,0,.06);display:flex;align-items:center;gap:16px;">
            <div style="width:48px;height:48px;background:#f0f8f4;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:22px;color:var(--g600);flex-shrink:0;">
                <i class="bi bi-headset"></i>
            </div>
            <div>
                <div style="font-size:15px;font-weight:700;color:var(--g800);margin-bottom:4px;">ต้องการความช่วยเหลือเพิ่มเติม?</div>
                <div style="font-size:13px;color:#888;">หากพบปัญหาการใช้งานระบบ ติดต่อทีม Developer ได้ทางอีเมล หรือแจ้งผ่านช่องทาง Line</div>
            </div>
        </div>

    </div>
</div>
@endsection
