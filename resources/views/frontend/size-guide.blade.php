@extends('layouts.app')
@section('title', 'วิธีวัดไซส์')
@section('meta_description', 'วิธีวัดไซส์เสื้อและกางเกงอย่างถูกต้อง เพื่อให้ได้ชุดที่พอดีตัวตั้งแต่วันแรกที่ได้รับ')


@section('content')

{{-- Hero --}}
<div class="sg-hero">
    <div class="sg-badge"><i class="bi bi-rulers"></i> คู่มือการวัดไซส์</div>
    <h1>วิธีวัดไซส์</h1>
    <p>วัดไซส์ให้แม่น เพื่อชุดที่เป๊ะตั้งแต่วันแรกที่ได้รับ<br>ใส่ชุดให้ดูดีเหมือนสั่งตัด ต้องเริ่มที่การวัดไซส์ที่ถูกต้อง</p>
</div>

    {{-- เสื้อ --}}
    <div class="sg-section">
        <div class="container" style="max-width:1100px;">
            <h2 class="sg-section-title"><i class="bi bi-person-arms-up"></i> วิธีวัดไซส์เสื้อ</h2>
            <div class="sg-grid">
                <div class="sg-card">
                    <img src="/images/size/01.jpg"
                         alt="วิธีวัดไซส์เสื้อ"
                         class="sg-guide-img"
                         loading="lazy">
                </div>

                <div>
                    <div class="sg-card" style="padding:24px;margin-bottom:20px;">
                        <div class="sg-step">
                            <div class="sg-step-num">1</div>
                            <div class="sg-step-body">
                                <h5>วัดรอบอก (Chest)</h5>
                                <p>วัดรอบอกในส่วนที่กว้างที่สุด โดยให้สายวัดขนานกับพื้น ไม่รัดแน่นหรือหลวมเกินไป</p>
                            </div>
                        </div>
                        <div class="sg-step">
                            <div class="sg-step-num">2</div>
                            <div class="sg-step-body">
                                <h5>วัดรอบไหล่ (Shoulder)</h5>
                                <p>วัดจากปลายไหล่ข้างหนึ่งไปยังปลายไหล่อีกข้าง ผ่านด้านหลัง</p>
                            </div>
                        </div>
                        <div class="sg-step">
                            <div class="sg-step-num">3</div>
                            <div class="sg-step-body">
                                <h5>วัดความยาวแขน (Sleeve)</h5>
                                <p>วัดจากไหล่ลงมาตามแขนจนถึงข้อมือ โดยงอข้อศอกเล็กน้อย</p>
                            </div>
                        </div>
                        <div class="sg-step">
                            <div class="sg-step-num">4</div>
                            <div class="sg-step-body">
                                <h5>วัดความยาวเสื้อ (Length)</h5>
                                <p>วัดจากคอลงมาถึงปลายเสื้อด้านหน้า ตามแนวตั้งตรง</p>
                            </div>
                        </div>
                    </div>

                    <div class="sg-tip-card">
                        <h4><i class="bi bi-lightbulb-fill" style="color:#f0c040;margin-right:6px;"></i> เคล็ดลับการวัดเสื้อ</h4>
                        <ul>
                            <li>ใส่เสื้อบาง ๆ ขณะวัด ไม่ควรวัดทับเสื้อหนา</li>
                            <li>ยืนตรง ผ่อนคลาย ไม่กลั้นหายใจขณะวัดรอบอก</li>
                            <li>หากตัวเลขอยู่ระหว่างไซส์ แนะนำให้เลือกไซส์ที่ใหญ่กว่า</li>
                            <li>วัดซ้ำ 2 ครั้งเพื่อความแม่นยำ</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <hr class="sg-divider">

    {{-- กางเกง --}}
    <div class="sg-section">
        <div class="container" style="max-width:1100px;">
            <h2 class="sg-section-title"><i class="bi bi-person-standing"></i> วิธีวัดไซส์กางเกง</h2>
            <div class="sg-grid">
                <div class="sg-card">
                    <img src="/images/size/02.jpg"
                         alt="วิธีวัดไซส์กางเกง"
                         class="sg-guide-img"
                         loading="lazy">
                </div>

                <div>
                    <div class="sg-card" style="padding:24px;margin-bottom:20px;">
                        <div class="sg-step">
                            <div class="sg-step-num">1</div>
                            <div class="sg-step-body">
                                <h5>วัดรอบเอว (Waist)</h5>
                                <p>วัดรอบเอวตรงส่วนที่เล็กที่สุดของลำตัว โดยให้สายวัดขนานกับพื้น ไม่รัดแน่นเกินไป</p>
                            </div>
                        </div>
                        <div class="sg-step">
                            <div class="sg-step-num">2</div>
                            <div class="sg-step-body">
                                <h5>วัดรอบสะโพก (Hip)</h5>
                                <p>วัดรอบสะโพกในส่วนที่กว้างที่สุด โดยให้สายวัดขนานกับพื้น</p>
                            </div>
                        </div>
                        <div class="sg-step">
                            <div class="sg-step-num">3</div>
                            <div class="sg-step-body">
                                <h5>วัดความยาวขา (Inseam)</h5>
                                <p>วัดจากขาหนีบลงไปตามด้านในขาจนถึงข้อเท้า ในท่ายืนตรง</p>
                            </div>
                        </div>
                        <div class="sg-step">
                            <div class="sg-step-num">4</div>
                            <div class="sg-step-body">
                                <h5>วัดความยาวกางเกง (Length)</h5>
                                <p>วัดจากเอวลงมาตามด้านข้างขาจนถึงความยาวที่ต้องการ</p>
                            </div>
                        </div>
                    </div>

                    <div class="sg-tip-card">
                        <h4><i class="bi bi-lightbulb-fill" style="color:#f0c040;margin-right:6px;"></i> เคล็ดลับการวัดกางเกง</h4>
                        <ul>
                            <li>ยืนตรง เท้าชิด ขณะวัดรอบเอวและสะโพก</li>
                            <li>วัดรอบเอวตอนหายใจออก เพื่อความสบายในการสวมใส่</li>
                            <li>หากสะโพกใหญ่กว่าเอวมาก ให้ยึดขนาดสะโพกเป็นหลัก</li>
                            <li>ควรวัดขณะสวมชุดชั้นในปกติ</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{-- CTA --}}
<div style="background:linear-gradient(135deg,var(--kgm-green-800),var(--kgm-green-700));padding:48px 20px;text-align:center;">
    <div class="container">
        <h3 style="color:#fff;font-size:22px;font-weight:800;margin:0 0 10px;">พร้อมสั่งซื้อแล้วใช่ไหม?</h3>
        <p style="color:rgba(255,255,255,0.75);margin:0 0 24px;">เลือกไซส์ได้มั่นใจ แล้วมาเลือกสินค้าของเราได้เลย</p>
        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap;">
            <a href="{{ route('shop') }}" class="btn btn-gold" style="border-radius:14px;padding:12px 28px;">
                <i class="bi bi-bag"></i> ดูสินค้าทั้งหมด
            </a>
            <a href="{{ route('quote') }}" class="btn" style="border-radius:14px;padding:12px 28px;background:rgba(255,255,255,0.15);color:#fff;border:1px solid rgba(255,255,255,0.3);">
                <i class="bi bi-file-earmark-text"></i> ขอใบเสนอราคา
            </a>
        </div>
    </div>
</div>

@endsection
