@extends('layouts.app')
@section('title', 'นโยบายความเป็นส่วนตัว')
@section('meta_description', 'นโยบายความเป็นส่วนตัวของบริษัท กิจเจริญการ์เมนท์ (1993) จำกัด — การเก็บรวบรวม ใช้ และคุ้มครองข้อมูลส่วนบุคคลตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA)')
@section('content')

{{-- Hero --}}
<div style="background:linear-gradient(135deg,var(--kgm-green-800),var(--kgm-green-700));padding:56px 24px 40px;text-align:center;">
    <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;background:rgba(255,255,255,0.15);border-radius:18px;font-size:28px;color:var(--kgm-gold-300);margin-bottom:16px;">
        <i class="bi bi-shield-lock"></i>
    </div>
    <h1 style="font-size:28px;font-weight:800;color:white;margin:0 0 8px;">นโยบายความเป็นส่วนตัว</h1>
    <p style="color:rgba(255,255,255,0.65);font-size:14px;margin:0;">
        อัปเดตล่าสุด: 31 พฤษภาคม 2568 &nbsp;·&nbsp; บังคับใช้ตาม พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA)
    </p>
</div>

<div class="container" style="padding:40px 24px 64px;max-width:860px;">

    {{-- TOC --}}
    <div style="background:#f8fdf9;border:1.5px solid #d4edda;border-radius:16px;padding:20px 24px;margin-bottom:32px;">
        <div style="font-weight:700;font-size:13px;color:var(--kgm-green-700);margin-bottom:12px;text-transform:uppercase;letter-spacing:.5px;">สารบัญ</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px 24px;font-size:13.5px;">
            <a href="#controller" style="color:var(--kgm-green-700);text-decoration:none;">1. ผู้ควบคุมข้อมูลส่วนบุคคล</a>
            <a href="#collect" style="color:var(--kgm-green-700);text-decoration:none;">2. ข้อมูลที่เราเก็บรวบรวม</a>
            <a href="#how-collect" style="color:var(--kgm-green-700);text-decoration:none;">3. วิธีเก็บรวบรวมข้อมูล</a>
            <a href="#legal-basis" style="color:var(--kgm-green-700);text-decoration:none;">4. ฐานทางกฎหมาย</a>
            <a href="#purpose" style="color:var(--kgm-green-700);text-decoration:none;">5. วัตถุประสงค์การใช้ข้อมูล</a>
            <a href="#sharing" style="color:var(--kgm-green-700);text-decoration:none;">6. การเปิดเผยข้อมูล</a>
            <a href="#retention" style="color:var(--kgm-green-700);text-decoration:none;">7. ระยะเวลาเก็บรักษา</a>
            <a href="#rights" style="color:var(--kgm-green-700);text-decoration:none;">8. สิทธิของเจ้าของข้อมูล</a>
            <a href="#security" style="color:var(--kgm-green-700);text-decoration:none;">9. มาตรการรักษาความปลอดภัย</a>
            <a href="#transfer" style="color:var(--kgm-green-700);text-decoration:none;">10. การโอนข้อมูลระหว่างประเทศ</a>
            <a href="#changes" style="color:var(--kgm-green-700);text-decoration:none;">11. การเปลี่ยนแปลงนโยบาย</a>
            <a href="#dpo" style="color:var(--kgm-green-700);text-decoration:none;">12. ติดต่อเจ้าหน้าที่ (DPO)</a>
        </div>
    </div>

    <div style="background:white;border-radius:20px;padding:36px;box-shadow:0 2px 16px rgba(0,0,0,0.07);font-size:15px;line-height:1.9;color:#444;">

        <p>บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด ("บริษัท", "เรา") ให้ความสำคัญสูงสุดต่อการคุ้มครองข้อมูลส่วนบุคคลของท่าน นโยบายนี้อธิบายถึงวิธีที่เราเก็บรวบรวม ใช้ เปิดเผย และคุ้มครองข้อมูลส่วนบุคคลของท่านตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562 (PDPA) กรุณาอ่านนโยบายนี้โดยละเอียดก่อนใช้งานเว็บไซต์ <strong>www.kgm1993.com</strong></p>

        {{-- 1 --}}
        <h2 id="controller" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">1. ผู้ควบคุมข้อมูลส่วนบุคคล</h2>
        <div style="background:#f6fbf8;border-radius:12px;padding:20px 24px;display:flex;flex-direction:column;gap:8px;font-size:14px;">
            <div><i class="bi bi-building" style="color:var(--kgm-green-600);width:20px;"></i> <strong>บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด</strong></div>
            <div><i class="bi bi-hash" style="color:var(--kgm-green-600);width:20px;"></i> เลขทะเบียนนิติบุคคล: 0105536XXXXXX</div>
            <div><i class="bi bi-geo-alt" style="color:var(--kgm-green-600);width:20px;"></i> 123 ถ.เจริญกรุง แขวงบางรัก เขตบางรัก กรุงเทพมหานคร 10500</div>
            <div><i class="bi bi-telephone" style="color:var(--kgm-green-600);width:20px;"></i> <a href="tel:020001234" style="color:var(--kgm-green-700);">02-000-1234</a></div>
            <div><i class="bi bi-envelope" style="color:var(--kgm-green-600);width:20px;"></i> <a href="mailto:info@kgm1993.com" style="color:var(--kgm-green-700);">info@kgm1993.com</a></div>
        </div>

        {{-- 2 --}}
        <h2 id="collect" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">2. ข้อมูลส่วนบุคคลที่เราเก็บรวบรวม</h2>
        <p>เราอาจเก็บรวบรวมข้อมูลส่วนบุคคลของท่านในหลายหมวดหมู่ ดังนี้:</p>

        <div style="display:flex;flex-direction:column;gap:12px;margin-top:16px;">
            <div style="border:1.5px solid #e8ecef;border-radius:12px;overflow:hidden;">
                <div style="background:#f6fbf8;padding:10px 16px;font-weight:700;font-size:13.5px;color:var(--kgm-green-800);border-bottom:1px solid #e8ecef;">
                    <i class="bi bi-person-badge"></i> ข้อมูลเพื่อการระบุตัวตน
                </div>
                <div style="padding:12px 16px;font-size:14px;">ชื่อ-นามสกุล, อีเมลแอดเดรส, เบอร์โทรศัพท์, ชื่อผู้ใช้งาน, รหัสผ่าน (เข้ารหัส)</div>
            </div>
            <div style="border:1.5px solid #e8ecef;border-radius:12px;overflow:hidden;">
                <div style="background:#f6fbf8;padding:10px 16px;font-weight:700;font-size:13.5px;color:var(--kgm-green-800);border-bottom:1px solid #e8ecef;">
                    <i class="bi bi-geo-alt"></i> ข้อมูลที่อยู่และการจัดส่ง
                </div>
                <div style="padding:12px 16px;font-size:14px;">ที่อยู่จัดส่งสินค้า, รหัสไปรษณีย์, จังหวัด, ที่อยู่สำหรับออกใบกำกับภาษี, เลขประจำตัวผู้เสียภาษี</div>
            </div>
            <div style="border:1.5px solid #e8ecef;border-radius:12px;overflow:hidden;">
                <div style="background:#f6fbf8;padding:10px 16px;font-weight:700;font-size:13.5px;color:var(--kgm-green-800);border-bottom:1px solid #e8ecef;">
                    <i class="bi bi-bag-check"></i> ข้อมูลการซื้อขาย
                </div>
                <div style="padding:12px 16px;font-size:14px;">ประวัติคำสั่งซื้อ, รายการสินค้า, ขนาดและสี, ใบขอเสนอราคา, หลักฐานการชำระเงิน (สลิปโอนเงิน)</div>
            </div>
            <div style="border:1.5px solid #e8ecef;border-radius:12px;overflow:hidden;">
                <div style="background:#f6fbf8;padding:10px 16px;font-weight:700;font-size:13.5px;color:var(--kgm-green-800);border-bottom:1px solid #e8ecef;">
                    <i class="bi bi-globe"></i> ข้อมูลทางเทคนิคและการใช้งาน
                </div>
                <div style="padding:12px 16px;font-size:14px;">IP Address, ประเภทเบราว์เซอร์, ระบบปฏิบัติการ, หน้าที่เยี่ยมชม, เวลาที่ใช้งาน, ข้อมูลจากคุกกี้</div>
            </div>
            <div style="border:1.5px solid #e8ecef;border-radius:12px;overflow:hidden;">
                <div style="background:#f6fbf8;padding:10px 16px;font-weight:700;font-size:13.5px;color:var(--kgm-green-800);border-bottom:1px solid #e8ecef;">
                    <i class="bi bi-chat-text"></i> ข้อมูลการติดต่อ
                </div>
                <div style="padding:12px 16px;font-size:14px;">ข้อความที่ส่งผ่านแบบฟอร์มติดต่อ, การสมัครงาน (ใบสมัครและ Resume), บันทึกการสื่อสารผ่านอีเมล</div>
            </div>
        </div>
        <p style="margin-top:14px;font-size:13.5px;color:#888;"><i class="bi bi-info-circle"></i> เราไม่เก็บรวบรวมข้อมูลอ่อนไหว (Sensitive Data) ตามมาตรา 26 แห่ง PDPA เช่น ข้อมูลสุขภาพ ศาสนา หรือเชื้อชาติ โดยไม่ได้รับความยินยอมโดยชัดแจ้ง</p>

        {{-- 3 --}}
        <h2 id="how-collect" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">3. วิธีที่เราเก็บรวบรวมข้อมูล</h2>
        <ul style="padding-left:20px;margin:0;">
            <li style="margin-bottom:8px;"><strong>ข้อมูลที่ท่านให้โดยตรง:</strong> เมื่อสมัครสมาชิก สั่งซื้อสินค้า ขอใบเสนอราคา กรอกแบบฟอร์มติดต่อ หรือสมัครงาน</li>
            <li style="margin-bottom:8px;"><strong>ข้อมูลที่เก็บโดยอัตโนมัติ:</strong> ผ่านคุกกี้และเทคโนโลยีติดตาม เมื่อท่านท่องเว็บไซต์ของเรา</li>
            <li style="margin-bottom:8px;"><strong>ข้อมูลจากบุคคลที่สาม:</strong> อาจได้รับจากพันธมิตรทางธุรกิจ แพลตฟอร์มโซเชียลมีเดีย หรือบริการยืนยันตัวตน</li>
            <li><strong>ข้อมูลจากการติดต่อ:</strong> ผ่านโทรศัพท์ อีเมล LINE หรือการเยี่ยมชมหน้าร้าน</li>
        </ul>

        {{-- 4 --}}
        <h2 id="legal-basis" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">4. ฐานทางกฎหมายในการประมวลผลข้อมูล</h2>
        <p>เราประมวลผลข้อมูลส่วนบุคคลของท่านภายใต้ฐานทางกฎหมายอย่างน้อยหนึ่งฐาน ได้แก่:</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-top:12px;">
            <div style="background:#f0faf4;border-radius:10px;padding:14px 16px;font-size:13.5px;">
                <div style="font-weight:700;color:var(--kgm-green-700);margin-bottom:4px;"><i class="bi bi-check-circle-fill"></i> ความยินยอม</div>
                <p style="margin:0;color:#666;">มาตรา 19 — สำหรับคุกกี้วิเคราะห์/การตลาด และการส่งข่าวสารทางการตลาด</p>
            </div>
            <div style="background:#f0faf4;border-radius:10px;padding:14px 16px;font-size:13.5px;">
                <div style="font-weight:700;color:var(--kgm-green-700);margin-bottom:4px;"><i class="bi bi-file-earmark-check-fill"></i> สัญญา</div>
                <p style="margin:0;color:#666;">มาตรา 24(3) — สำหรับการดำเนินการสั่งซื้อ จัดส่ง และบริการหลังการขาย</p>
            </div>
            <div style="background:#f0faf4;border-radius:10px;padding:14px 16px;font-size:13.5px;">
                <div style="font-weight:700;color:var(--kgm-green-700);margin-bottom:4px;"><i class="bi bi-bank"></i> พันธกรณีทางกฎหมาย</div>
                <p style="margin:0;color:#666;">มาตรา 24(1) — สำหรับการปฏิบัติตามกฎหมายบัญชี ภาษี และกฎระเบียบที่เกี่ยวข้อง</p>
            </div>
            <div style="background:#f0faf4;border-radius:10px;padding:14px 16px;font-size:13.5px;">
                <div style="font-weight:700;color:var(--kgm-green-700);margin-bottom:4px;"><i class="bi bi-heart-pulse-fill"></i> ประโยชน์โดยชอบด้วยกฎหมาย</div>
                <p style="margin:0;color:#666;">มาตรา 24(5) — สำหรับการป้องกันการทุจริต การรักษาความปลอดภัย และการวิเคราะห์ธุรกิจ</p>
            </div>
        </div>

        {{-- 5 --}}
        <h2 id="purpose" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">5. วัตถุประสงค์ในการใช้ข้อมูลส่วนบุคคล</h2>
        <ul style="padding-left:20px;margin:0;">
            <li style="margin-bottom:8px;"><strong>ดำเนินการสั่งซื้อและจัดส่งสินค้า</strong> รวมถึงการออกใบกำกับภาษีและเอกสารที่เกี่ยวข้อง</li>
            <li style="margin-bottom:8px;"><strong>บริหารจัดการบัญชีสมาชิก</strong> การตรวจสอบตัวตน และการดูแลรักษาความปลอดภัยบัญชี</li>
            <li style="margin-bottom:8px;"><strong>ประมวลใบเสนอราคาและคำสั่งตัดชุดนักเรียน/ยูนิฟอร์ม</strong> สำหรับลูกค้าองค์กรและโรงเรียน</li>
            <li style="margin-bottom:8px;"><strong>บริการลูกค้าและการสนับสนุนหลังการขาย</strong> ติดตามสถานะสินค้า แก้ไขปัญหา และรับข้อร้องเรียน</li>
            <li style="margin-bottom:8px;"><strong>การสื่อสารทางการตลาด</strong> ส่งข่าวสาร โปรโมชัน และข้อมูลสินค้าใหม่ (เมื่อได้รับความยินยอม)</li>
            <li style="margin-bottom:8px;"><strong>วิเคราะห์และปรับปรุงเว็บไซต์</strong> เข้าใจพฤติกรรมผู้ใช้เพื่อพัฒนาประสบการณ์การใช้งาน</li>
            <li style="margin-bottom:8px;"><strong>พิจารณาใบสมัครงาน</strong> สำหรับผู้ที่ยื่นสมัครงานกับบริษัท</li>
            <li><strong>ปฏิบัติตามข้อกำหนดทางกฎหมาย</strong> เช่น กฎหมายบัญชี กฎหมายภาษีอากร และการปฏิบัติตามคำสั่งของหน่วยงานภาครัฐ</li>
        </ul>

        {{-- 6 --}}
        <h2 id="sharing" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">6. การเปิดเผยข้อมูลให้บุคคลที่สาม</h2>
        <p>บริษัท<strong>ไม่ขาย</strong>ข้อมูลส่วนบุคคลของท่านให้กับบุคคลภายนอก อย่างไรก็ตาม เราอาจเปิดเผยข้อมูลในกรณีดังต่อไปนี้:</p>
        <ul style="padding-left:20px;margin:0;">
            <li style="margin-bottom:8px;"><strong>ผู้ให้บริการขนส่ง</strong> (เช่น Kerry, Flash, EMS) เพื่อจัดส่งสินค้าถึงมือท่าน</li>
            <li style="margin-bottom:8px;"><strong>ผู้ให้บริการชำระเงิน</strong> เพื่อดำเนินการตรวจสอบการโอนเงินและการออกใบเสร็จ</li>
            <li style="margin-bottom:8px;"><strong>ผู้ให้บริการ IT และ Cloud</strong> เช่น เซิร์ฟเวอร์โฮสติ้ง บริการอีเมล และระบบ CRM ซึ่งทำสัญญาปกปักรักษาข้อมูลกับเรา</li>
            <li style="margin-bottom:8px;"><strong>ผู้ให้บริการวิเคราะห์ข้อมูล</strong> (เช่น Google Analytics) ในรูปแบบข้อมูลรวมที่ไม่ระบุตัวตน</li>
            <li style="margin-bottom:8px;"><strong>หน่วยงานภาครัฐ</strong> เช่น กรมสรรพากร กรมพัฒนาธุรกิจการค้า เมื่อมีข้อกำหนดทางกฎหมาย</li>
            <li><strong>ที่ปรึกษาทางวิชาชีพ</strong> เช่น ผู้สอบบัญชี ทนายความ ภายใต้ข้อตกลงรักษาความลับ</li>
        </ul>

        {{-- 7 --}}
        <h2 id="retention" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">7. ระยะเวลาในการเก็บรักษาข้อมูล</h2>
        <div style="overflow-x:auto;border-radius:12px;border:1.5px solid #e8ecef;">
            <table style="width:100%;border-collapse:collapse;font-size:13.5px;">
                <thead>
                    <tr style="background:var(--kgm-green-800);color:white;">
                        <th style="padding:12px 16px;text-align:left;font-weight:600;">ประเภทข้อมูล</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;">ระยะเวลาเก็บรักษา</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;">เหตุผล</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;">ข้อมูลบัญชีสมาชิก</td>
                        <td style="padding:11px 16px;white-space:nowrap;">ตลอดอายุบัญชี + 1 ปี</td>
                        <td style="padding:11px 16px;font-size:13px;color:#666;">นับจากการลบบัญชีหรือหยุดใช้งาน</td>
                    </tr>
                    <tr style="background:#fafafa;border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;">ข้อมูลการสั่งซื้อและใบกำกับภาษี</td>
                        <td style="padding:11px 16px;white-space:nowrap;">5 ปี</td>
                        <td style="padding:11px 16px;font-size:13px;color:#666;">ตามกฎหมายบัญชีและกรมสรรพากร</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;">ใบขอเสนอราคา</td>
                        <td style="padding:11px 16px;white-space:nowrap;">3 ปี</td>
                        <td style="padding:11px 16px;font-size:13px;color:#666;">นับจากวันที่ออกใบเสนอราคา</td>
                    </tr>
                    <tr style="background:#fafafa;border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;">ข้อมูลการสมัครงาน</td>
                        <td style="padding:11px 16px;white-space:nowrap;">1 ปี</td>
                        <td style="padding:11px 16px;font-size:13px;color:#666;">เพื่อพิจารณาตำแหน่งที่เปิดรับในอนาคต</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;">ข้อมูลการวิเคราะห์เว็บไซต์</td>
                        <td style="padding:11px 16px;white-space:nowrap;">26 เดือน</td>
                        <td style="padding:11px 16px;font-size:13px;color:#666;">ตามนโยบาย Google Analytics</td>
                    </tr>
                    <tr>
                        <td style="padding:11px 16px;">บันทึกการติดต่อและร้องเรียน</td>
                        <td style="padding:11px 16px;white-space:nowrap;">2 ปี</td>
                        <td style="padding:11px 16px;font-size:13px;color:#666;">เพื่อการติดตามและอ้างอิงในอนาคต</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- 8 --}}
        <h2 id="rights" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">8. สิทธิของเจ้าของข้อมูลส่วนบุคคล</h2>
        <p>ภายใต้ PDPA ท่านมีสิทธิ์ดังต่อไปนี้ ซึ่งสามารถใช้ได้โดยติดต่อเราตามช่องทางที่ระบุด้านล่าง:</p>
        <div style="display:flex;flex-direction:column;gap:10px;margin-top:16px;">
            <div style="display:flex;gap:14px;align-items:flex-start;background:#f8f9fa;border-radius:12px;padding:14px 18px;">
                <div style="width:36px;height:36px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:14px;flex-shrink:0;">1</div>
                <div><strong style="color:var(--kgm-green-800);">สิทธิในการเข้าถึงข้อมูล (Right of Access)</strong><br><span style="font-size:13.5px;color:#666;">ท่านมีสิทธิ์ขอรับสำเนาข้อมูลส่วนบุคคลของท่านที่เราเก็บรักษา และตรวจสอบว่าเราประมวลผลข้อมูลอย่างชอบด้วยกฎหมายหรือไม่</span></div>
            </div>
            <div style="display:flex;gap:14px;align-items:flex-start;background:#f8f9fa;border-radius:12px;padding:14px 18px;">
                <div style="width:36px;height:36px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:14px;flex-shrink:0;">2</div>
                <div><strong style="color:var(--kgm-green-800);">สิทธิในการแก้ไขข้อมูล (Right of Rectification)</strong><br><span style="font-size:13.5px;color:#666;">ท่านมีสิทธิ์ขอให้แก้ไขข้อมูลที่ไม่ถูกต้อง ไม่ครบถ้วน หรือล้าสมัย</span></div>
            </div>
            <div style="display:flex;gap:14px;align-items:flex-start;background:#f8f9fa;border-radius:12px;padding:14px 18px;">
                <div style="width:36px;height:36px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:14px;flex-shrink:0;">3</div>
                <div><strong style="color:var(--kgm-green-800);">สิทธิในการลบข้อมูล (Right to Erasure)</strong><br><span style="font-size:13.5px;color:#666;">ท่านมีสิทธิ์ขอให้ลบหรือทำลายข้อมูล เมื่อไม่มีความจำเป็นต้องเก็บรักษาอีกต่อไป หรือเมื่อถอนความยินยอม ทั้งนี้ขึ้นอยู่กับข้อกำหนดทางกฎหมายที่บังคับใช้</span></div>
            </div>
            <div style="display:flex;gap:14px;align-items:flex-start;background:#f8f9fa;border-radius:12px;padding:14px 18px;">
                <div style="width:36px;height:36px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:14px;flex-shrink:0;">4</div>
                <div><strong style="color:var(--kgm-green-800);">สิทธิในการจำกัดการประมวลผล (Right to Restriction)</strong><br><span style="font-size:13.5px;color:#666;">ท่านมีสิทธิ์ขอให้ระงับการใช้ข้อมูลชั่วคราว เช่น ระหว่างที่เราตรวจสอบความถูกต้องของข้อมูล</span></div>
            </div>
            <div style="display:flex;gap:14px;align-items:flex-start;background:#f8f9fa;border-radius:12px;padding:14px 18px;">
                <div style="width:36px;height:36px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:14px;flex-shrink:0;">5</div>
                <div><strong style="color:var(--kgm-green-800);">สิทธิในการพกพาข้อมูล (Right to Data Portability)</strong><br><span style="font-size:13.5px;color:#666;">ท่านมีสิทธิ์ขอรับข้อมูลในรูปแบบที่อ่านได้ด้วยเครื่อง (Machine-readable) หรือขอให้โอนข้อมูลไปยังผู้ควบคุมข้อมูลรายอื่น</span></div>
            </div>
            <div style="display:flex;gap:14px;align-items:flex-start;background:#f8f9fa;border-radius:12px;padding:14px 18px;">
                <div style="width:36px;height:36px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:14px;flex-shrink:0;">6</div>
                <div><strong style="color:var(--kgm-green-800);">สิทธิในการคัดค้าน (Right to Object)</strong><br><span style="font-size:13.5px;color:#666;">ท่านมีสิทธิ์คัดค้านการประมวลผลข้อมูลเพื่อวัตถุประสงค์ทางการตลาดโดยตรงได้ตลอดเวลา</span></div>
            </div>
            <div style="display:flex;gap:14px;align-items:flex-start;background:#f8f9fa;border-radius:12px;padding:14px 18px;">
                <div style="width:36px;height:36px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:14px;flex-shrink:0;">7</div>
                <div><strong style="color:var(--kgm-green-800);">สิทธิในการถอนความยินยอม (Right to Withdraw Consent)</strong><br><span style="font-size:13.5px;color:#666;">ท่านสามารถถอนความยินยอมได้ตลอดเวลา โดยไม่กระทบต่อการประมวลผลที่ได้ดำเนินการไปแล้วก่อนการถอนความยินยอม</span></div>
            </div>
            <div style="display:flex;gap:14px;align-items:flex-start;background:#f8f9fa;border-radius:12px;padding:14px 18px;">
                <div style="width:36px;height:36px;background:var(--kgm-green-600);border-radius:10px;display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:14px;flex-shrink:0;">8</div>
                <div><strong style="color:var(--kgm-green-800);">สิทธิในการร้องเรียน (Right to Lodge a Complaint)</strong><br><span style="font-size:13.5px;color:#666;">หากท่านเชื่อว่าเราละเมิดสิทธิของท่าน ท่านมีสิทธิ์ร้องเรียนต่อสำนักงานคณะกรรมการคุ้มครองข้อมูลส่วนบุคคล (PDPC)</span></div>
            </div>
        </div>
        <p style="margin-top:14px;font-size:13.5px;">เราจะดำเนินการตามคำขอของท่านภายใน <strong>30 วัน</strong> นับจากวันที่ได้รับคำขอ หากมีเหตุจำเป็นอาจขยายเวลาได้ไม่เกิน 60 วัน พร้อมแจ้งเหตุผล</p>

        {{-- 9 --}}
        <h2 id="security" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">9. มาตรการรักษาความปลอดภัย</h2>
        <p>เราใช้มาตรการรักษาความปลอดภัยทั้งทางเทคนิคและองค์กร เพื่อปกป้องข้อมูลส่วนบุคคลของท่านจากการสูญหาย การเข้าถึงโดยไม่ได้รับอนุญาต การเปิดเผย การเปลี่ยนแปลง และการทำลาย ได้แก่:</p>
        <ul style="padding-left:20px;margin:0;">
            <li style="margin-bottom:8px;"><strong>การเข้ารหัส SSL/TLS</strong> — ข้อมูลทุกการรับ-ส่งระหว่างเบราว์เซอร์และเซิร์ฟเวอร์ถูกเข้ารหัส</li>
            <li style="margin-bottom:8px;"><strong>การเข้ารหัสรหัสผ่าน</strong> — รหัสผ่านถูกแฮชด้วย bcrypt ไม่มีการจัดเก็บรหัสผ่านในรูปแบบ plaintext</li>
            <li style="margin-bottom:8px;"><strong>การควบคุมการเข้าถึง</strong> — จำกัดสิทธิ์การเข้าถึงข้อมูลตามหน้าที่ความรับผิดชอบ (Role-based Access Control)</li>
            <li style="margin-bottom:8px;"><strong>การตรวจสอบและบันทึกกิจกรรม</strong> — ระบบบันทึก Log การเข้าถึงข้อมูลสำคัญ</li>
            <li style="margin-bottom:8px;"><strong>การสำรองข้อมูล</strong> — สำรองข้อมูลอย่างสม่ำเสมอในสถานที่ที่ปลอดภัย</li>
            <li><strong>การฝึกอบรมพนักงาน</strong> — พนักงานทุกคนได้รับการฝึกอบรมเรื่องการปกป้องข้อมูลส่วนบุคคล</li>
        </ul>
        <p>หากเกิดเหตุการณ์ละเมิดความปลอดภัยที่มีความเสี่ยงสูง เราจะแจ้งท่านและสำนักงาน PDPC ภายใน 72 ชั่วโมง</p>

        {{-- 10 --}}
        <h2 id="transfer" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">10. การโอนข้อมูลระหว่างประเทศ</h2>
        <p>บริษัทอาจใช้บริการของผู้ให้บริการที่มีเซิร์ฟเวอร์ตั้งอยู่ในต่างประเทศ เช่น Google Cloud, Cloudflare ในกรณีดังกล่าว เราจะดำเนินการให้มีมาตรการปกป้องข้อมูลที่เหมาะสม รวมถึงการทำสัญญาตามมาตรฐาน Standard Contractual Clauses (SCCs) หรือตรวจสอบว่าประเทศปลายทางมีมาตรฐานการคุ้มครองข้อมูลที่เพียงพอ</p>

        {{-- 11 --}}
        <h2 id="changes" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">11. การเปลี่ยนแปลงนโยบายนี้</h2>
        <p>บริษัทอาจปรับปรุงนโยบายความเป็นส่วนตัวนี้เป็นครั้งคราว เมื่อมีการเปลี่ยนแปลงที่มีสาระสำคัญ เราจะแจ้งท่านผ่านอีเมลที่ลงทะเบียนไว้ หรือประกาศบนเว็บไซต์อย่างชัดเจน การใช้งานเว็บไซต์ต่อไปหลังจากได้รับการแจ้งเตือน ถือว่าท่านยอมรับนโยบายที่ปรับปรุงแล้ว</p>

        {{-- 12 --}}
        <h2 id="dpo" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">12. ติดต่อเจ้าหน้าที่คุ้มครองข้อมูลส่วนบุคคล (DPO)</h2>
        <p>หากท่านต้องการใช้สิทธิ์ใดๆ ตามนโยบายนี้ หรือมีข้อสงสัยเกี่ยวกับการใช้ข้อมูลส่วนบุคคลของท่าน กรุณาติดต่อ:</p>
        <div style="background:#f6fbf8;border-radius:12px;padding:20px 24px;display:flex;flex-direction:column;gap:10px;font-size:14px;">
            <div><i class="bi bi-person-badge" style="color:var(--kgm-green-600);width:20px;"></i> <strong>เจ้าหน้าที่คุ้มครองข้อมูลส่วนบุคคล (DPO)</strong></div>
            <div><i class="bi bi-building" style="color:var(--kgm-green-600);width:20px;"></i> บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด</div>
            <div><i class="bi bi-geo-alt" style="color:var(--kgm-green-600);width:20px;"></i> 123 ถ.เจริญกรุง แขวงบางรัก กรุงเทพฯ 10500</div>
            <div><i class="bi bi-envelope" style="color:var(--kgm-green-600);width:20px;"></i> <a href="mailto:dpo@kgm1993.com" style="color:var(--kgm-green-700);">dpo@kgm1993.com</a></div>
            <div><i class="bi bi-telephone" style="color:var(--kgm-green-600);width:20px;"></i> <a href="tel:020001234" style="color:var(--kgm-green-700);">02-000-1234</a> ต่อ 101</div>
        </div>

        <div style="background:#fff8e1;border-left:4px solid #ffc107;border-radius:0 12px 12px 0;padding:14px 18px;margin-top:20px;">
            <p style="margin:0;font-size:13.5px;">
                <i class="bi bi-info-circle-fill" style="color:#f57c00;"></i>
                <strong>สำนักงานคณะกรรมการคุ้มครองข้อมูลส่วนบุคคล (PDPC)</strong><br>
                หากท่านไม่ได้รับการแก้ไขจากเราอย่างพอใจ ท่านมีสิทธิ์ร้องเรียนต่อ PDPC ที่ <a href="https://www.pdpc.or.th" target="_blank" style="color:#e65100;">www.pdpc.or.th</a>
            </p>
        </div>

        <p style="margin-top:28px;font-size:12.5px;color:#aaa;text-align:center;">
            อ่านเพิ่มเติม:
            <a href="{{ route('cookie-policy') }}" style="color:var(--kgm-green-600);">นโยบายคุกกี้</a> &nbsp;·&nbsp;
            <a href="{{ route('terms') }}" style="color:var(--kgm-green-600);">ข้อกำหนดการใช้งาน</a>
        </p>

    </div>
</div>
@endsection
