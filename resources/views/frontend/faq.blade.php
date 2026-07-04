@extends('layouts.app')
@section('title', 'คำถามที่พบบ่อย (FAQ)')
@section('meta_description', 'รวมคำถามที่พบบ่อยเกี่ยวกับสินค้า การสั่งซื้อ การชำระเงิน การจัดส่ง และบริการต่าง ๆ ของกิจเจริญการ์เมนท์')

@section('content')

{{-- Hero --}}
<div class="faq-hero" style="background:linear-gradient(135deg,var(--kgm-green-900),var(--kgm-green-700));padding:60px 0;text-align:center;">
    <div class="sg-badge"><i class="bi bi-patch-question-fill"></i> FAQ</div>
    <h1 style="font-size:40px;font-weight:800;color:white;">คำถามที่พบบ่อย</h1>
    <p style="color:white;">รวบรวมคำถามยอดนิยมจากลูกค้า<br>หากไม่พบคำตอบที่ต้องการ <a href="{{ route('contact') }}" class="faq-hero-link">ติดต่อเราได้เลย</a></p>
</div>

<div class="container faq-wrap" style="max-width:860px;" x-data="{ open: null }">

    {{-- การสั่งซื้อ --}}
    <div class="faq-group">
        <h2 class="faq-group-title"><i class="bi bi-bag-check"></i> การสั่งซื้อ</h2>

        <div class="faq-item" :class="{ active: open === 'o1' }">
            <button class="faq-q" @click="open = open === 'o1' ? null : 'o1'">
                <span>สั่งซื้อขั้นต่ำเท่าไหร่?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'o1'" x-collapse>
                <p>สำหรับสินค้าสำเร็จรูปในเว็บไซต์ไม่มีขั้นต่ำ สามารถสั่งได้ตั้งแต่ 1 ตัว สำหรับงานสั่งตัดหรืองานปักสกรีน มีขั้นต่ำตามรายการสินค้า กรุณา<a href="{{ route('quote') }}">ขอใบเสนอราคา</a>เพื่อรับข้อมูลที่ถูกต้อง</p>
            </div>
        </div>

        <div class="faq-item" :class="{ active: open === 'o2' }">
            <button class="faq-q" @click="open = open === 'o2' ? null : 'o2'">
                <span>สามารถสั่งซื้อผ่านช่องทางไหนได้บ้าง?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'o2'" x-collapse>
                <ul>
                    <li>เว็บไซต์ kgm.co.th (ชำระออนไลน์ได้ทันที)</li>
                    <li>LINE Official: @kgmgarment</li>
                    <li>โทรศัพท์ หรือมาที่ Showroom โดยตรง</li>
                    <li>Shopee / Lazada / TikTok Shop (สินค้าสำเร็จรูป)</li>
                </ul>
            </div>
        </div>

        <div class="faq-item" :class="{ active: open === 'o3' }">
            <button class="faq-q" @click="open = open === 'o3' ? null : 'o3'">
                <span>ต้องการสั่งทำชุดพนักงาน/ยูนิฟอร์มทำได้ไหม?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'o3'" x-collapse>
                <p>ได้เลย บริการรับตัดชุดพนักงาน ชุดยูนิฟอร์มองค์กร ชุดนักเรียน และเสื้อกีฬา ทั้งแบบมาตรฐานและแบบกำหนดเองตามดีไซน์ที่ต้องการ กรุณา<a href="{{ route('quote') }}">ขอใบเสนอราคา</a>หรือ<a href="{{ route('contact') }}">ติดต่อเรา</a>เพื่อหารือรายละเอียด</p>
            </div>
        </div>

        <div class="faq-item" :class="{ active: open === 'o4' }">
            <button class="faq-q" @click="open = open === 'o4' ? null : 'o4'">
                <span>ระยะเวลาผลิตสินค้าสั่งตัดนานแค่ไหน?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'o4'" x-collapse>
                <p>โดยทั่วไปอยู่ที่ประมาณ <strong>14–21 วันทำการ</strong> ขึ้นอยู่กับปริมาณและความซับซ้อนของงาน งานเร่งด่วนสามารถแจ้งได้และทีมงานจะแจ้งระยะเวลาจริงกลับ</p>
            </div>
        </div>
    </div>

    {{-- สินค้าและไซส์ --}}
    <div class="faq-group">
        <h2 class="faq-group-title"><i class="bi bi-rulers"></i> สินค้าและไซส์</h2>

        <div class="faq-item" :class="{ active: open === 's1' }">
            <button class="faq-q" @click="open = open === 's1' ? null : 's1'">
                <span>จะรู้ได้อย่างไรว่าไซส์ไหนพอดีกับตัว?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 's1'" x-collapse>
                <p>แนะนำให้ดู<a href="{{ route('size-guide') }}">คู่มือวัดไซส์</a>ของเรา มีขั้นตอนการวัดรอบอก รอบเอว ความยาวแขน และอื่น ๆ อย่างละเอียด หากยังไม่แน่ใจสามารถติดต่อทีมงานเพื่อรับคำแนะนำเพิ่มเติม</p>
            </div>
        </div>

        <div class="faq-item" :class="{ active: open === 's2' }">
            <button class="faq-q" @click="open = open === 's2' ? null : 's2'">
                <span>มีบริการปักโลโก้หรือสกรีนชื่อบนเสื้อไหม?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 's2'" x-collapse>
                <p>มีครับ/ค่ะ บริการปักลาย (Embroidery) และสกรีน (Screen Printing) ทั้งโลโก้บริษัท ชื่อพนักงาน และลวดลายต่าง ๆ สามารถดูข้อมูลเพิ่มเติมได้ที่<a href="{{ route('embroidery-screen') }}">หน้าบริการปักสกรีน</a></p>
            </div>
        </div>

        <div class="faq-item" :class="{ active: open === 's3' }">
            <button class="faq-q" @click="open = open === 's3' ? null : 's3'">
                <span>สินค้าในเว็บไซต์มีสต็อกพร้อมส่งเสมอไหม?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 's3'" x-collapse>
                <p>สินค้าสำเร็จรูปในเว็บไซต์มีสต็อกพร้อมส่ง โดยจะแสดงจำนวนคงเหลือในหน้าสินค้า หากไซส์หรือสีที่ต้องการหมด สามารถสอบถามเพื่อจองรุ่นผลิตใหม่ได้</p>
            </div>
        </div>
    </div>

    {{-- การชำระเงิน --}}
    <div class="faq-group">
        <h2 class="faq-group-title"><i class="bi bi-credit-card"></i> การชำระเงิน</h2>

        <div class="faq-item" :class="{ active: open === 'p1' }">
            <button class="faq-q" @click="open = open === 'p1' ? null : 'p1'">
                <span>ชำระเงินได้ด้วยวิธีใดบ้าง?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'p1'" x-collapse>
                <ul>
                    <li>โอนเงินผ่านธนาคาร (แนบสลิปในระบบ)</li>
                    <li>QR Code พร้อมเพย์</li>
                    <li>เงินสด (กรณีรับสินค้าที่ Showroom)</li>
                </ul>
            </div>
        </div>

        <div class="faq-item" :class="{ active: open === 'p2' }">
            <button class="faq-q" @click="open = open === 'p2' ? null : 'p2'">
                <span>หลังจากโอนเงินต้องทำอะไรบ้าง?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'p2'" x-collapse>
                <p>เข้าสู่ระบบในเว็บไซต์ แล้วไปที่ <strong>บัญชีของฉัน → คำสั่งซื้อ</strong> จากนั้นกดอัปโหลดสลิปการโอนเงิน ทีมงานจะตรวจสอบและยืนยันภายใน 1–2 ชั่วโมงในวันทำการ</p>
            </div>
        </div>

        <div class="faq-item" :class="{ active: open === 'p3' }">
            <button class="faq-q" @click="open = open === 'p3' ? null : 'p3'">
                <span>ออกใบกำกับภาษีได้ไหม?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'p3'" x-collapse>
                <p>ได้ครับ/ค่ะ กรุณาแจ้งข้อมูลบริษัท เลขประจำตัวผู้เสียภาษี และที่อยู่สำหรับออกใบกำกับภาษีมาในช่องหมายเหตุตอนสั่งซื้อ หรือแจ้งผ่าน LINE</p>
            </div>
        </div>
    </div>

    {{-- การจัดส่ง --}}
    <div class="faq-group">
        <h2 class="faq-group-title"><i class="bi bi-truck"></i> การจัดส่ง</h2>

        <div class="faq-item" :class="{ active: open === 'd1' }">
            <button class="faq-q" @click="open = open === 'd1' ? null : 'd1'">
                <span>ใช้ขนส่งบริษัทไหน และส่งนานแค่ไหน?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'd1'" x-collapse>
                <p>จัดส่งผ่าน <strong>Kerry Express / Flash Express / J&T Express</strong> ระยะเวลาจัดส่งในกรุงเทพฯ และปริมณฑล 1–2 วัน ต่างจังหวัด 2–4 วันทำการ หลังจากยืนยันการชำระเงินแล้ว</p>
            </div>
        </div>

        <div class="faq-item" :class="{ active: open === 'd2' }">
            <button class="faq-q" @click="open = open === 'd2' ? null : 'd2'">
                <span>มีบริการส่งฟรีไหม?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'd2'" x-collapse>
                <p>มีครับ/ค่ะ สำหรับคำสั่งซื้อที่มียอดรวมตามเกณฑ์ที่กำหนด (ดูรายละเอียดในหน้าตะกร้าสินค้า) จะได้รับสิทธิ์จัดส่งฟรีทั่วประเทศ</p>
            </div>
        </div>

        <div class="faq-item" :class="{ active: open === 'd3' }">
            <button class="faq-q" @click="open = open === 'd3' ? null : 'd3'">
                <span>ติดตามสถานะพัสดุได้อย่างไร?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'd3'" x-collapse>
                <p>เมื่อจัดส่งแล้วระบบจะอัปเดตหมายเลขพัสดุในหน้า <strong>บัญชีของฉัน → คำสั่งซื้อ</strong> สามารถนำหมายเลขพัสดุไปติดตามในเว็บไซต์ของบริษัทขนส่งได้ทันที</p>
            </div>
        </div>
    </div>

    {{-- คืน/เปลี่ยนสินค้า --}}
    <div class="faq-group">
        <h2 class="faq-group-title"><i class="bi bi-arrow-repeat"></i> การคืนและเปลี่ยนสินค้า</h2>

        <div class="faq-item" :class="{ active: open === 'r1' }">
            <button class="faq-q" @click="open = open === 'r1' ? null : 'r1'">
                <span>สามารถคืนหรือเปลี่ยนสินค้าได้ไหม?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'r1'" x-collapse>
                <p>รับเปลี่ยนสินค้าภายใน <strong>7 วัน</strong> นับจากวันที่ได้รับสินค้า เฉพาะกรณีสินค้าชำรุดจากการผลิต หรือได้รับสินค้าผิดรุ่น/ผิดขนาดจากที่สั่ง โดยสินค้าต้องอยู่ในสภาพใหม่ ไม่ผ่านการใช้งาน และมีป้ายครบ</p>
            </div>
        </div>

        <div class="faq-item" :class="{ active: open === 'r2' }">
            <button class="faq-q" @click="open = open === 'r2' ? null : 'r2'">
                <span>สินค้าสั่งตัด/ปักสกรีนคืนได้ไหม?</span>
                <i class="bi bi-chevron-down faq-arrow"></i>
            </button>
            <div class="faq-a" x-show="open === 'r2'" x-collapse>
                <p>สินค้าที่ผลิตตามออเดอร์ (Made to Order) หรือมีการปักสกรีนโลโก้/ชื่อเฉพาะ ไม่สามารถคืนได้ ยกเว้นกรณีที่สินค้ามีตำหนิจากฝั่งผู้ผลิต</p>
            </div>
        </div>
    </div>

</div>

{{-- CTA --}}
<div style="background:linear-gradient(135deg,var(--kgm-green-800),var(--kgm-green-700));padding:48px 20px;text-align:center;margin-top:16px;">
    <div class="container">
        <h3 style="color:#fff;font-size:22px;font-weight:800;margin:0 0 10px;">ยังไม่เจอคำตอบที่ต้องการ?</h3>
        <p style="color:rgba(255,255,255,0.75);margin:0 0 24px;">ทีมงานพร้อมช่วยเหลือคุณทุกวัน</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('contact') }}" class="btn btn-gold" style="border-radius:14px;padding:12px 28px;">
                <i class="bi bi-envelope"></i> ติดต่อเรา
            </a>
            <a href="{{ route('quote') }}" class="btn" style="border-radius:14px;padding:12px 28px;background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);">
                <i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคา
            </a>
        </div>
    </div>
</div>

@endsection
