@extends('layouts.app')
@section('title', 'นโยบายคุกกี้')
@section('meta_description', 'นโยบายคุกกี้ของบริษัท กิจเจริญการ์เมนท์ (1993) จำกัด — ข้อมูลเกี่ยวกับคุกกี้ที่เราใช้และวิธีจัดการตามพระราชบัญญัติคุ้มครองข้อมูลส่วนบุคคล (PDPA)')
@section('content')

{{-- Hero --}}
<div style="background:linear-gradient(135deg,var(--kgm-green-800),var(--kgm-green-700));padding:56px 24px 40px;text-align:center;">
    <div style="display:inline-flex;align-items:center;justify-content:center;width:64px;height:64px;background:rgba(255,255,255,0.15);border-radius:18px;font-size:28px;color:var(--kgm-gold-300);margin-bottom:16px;">
        <i class="bi bi-shield-check"></i>
    </div>
    <h1 style="font-size:28px;font-weight:800;color:white;margin:0 0 8px;">นโยบายคุกกี้</h1>
    <p style="color:rgba(255,255,255,0.65);font-size:14px;margin:0;">
        อัปเดตล่าสุด: 31 พฤษภาคม 2568 &nbsp;·&nbsp; บังคับใช้ตาม พ.ร.บ. คุ้มครองข้อมูลส่วนบุคคล พ.ศ. 2562
    </p>
</div>

<div class="container" style="padding:40px 24px 64px;max-width:860px;">

    {{-- TOC --}}
    <div style="background:#f8fdf9;border:1.5px solid #d4edda;border-radius:16px;padding:20px 24px;margin-bottom:32px;">
        <div style="font-weight:700;font-size:13px;color:var(--kgm-green-700);margin-bottom:12px;text-transform:uppercase;letter-spacing:.5px;">สารบัญ</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px 24px;font-size:13.5px;">
            <a href="#what-are-cookies" style="color:var(--kgm-green-700);text-decoration:none;">1. คุกกี้คืออะไร</a>
            <a href="#types" style="color:var(--kgm-green-700);text-decoration:none;">2. ประเภทคุกกี้ที่เราใช้</a>
            <a href="#cookie-table" style="color:var(--kgm-green-700);text-decoration:none;">3. ตารางรายละเอียดคุกกี้</a>
            <a href="#third-party" style="color:var(--kgm-green-700);text-decoration:none;">4. คุกกี้ของบุคคลที่สาม</a>
            <a href="#manage" style="color:var(--kgm-green-700);text-decoration:none;">5. วิธีจัดการคุกกี้</a>
            <a href="#changes" style="color:var(--kgm-green-700);text-decoration:none;">6. การเปลี่ยนแปลงนโยบาย</a>
            <a href="#contact" style="color:var(--kgm-green-700);text-decoration:none;">7. ติดต่อเรา</a>
        </div>
    </div>

    <div style="background:white;border-radius:20px;padding:36px;box-shadow:0 2px 16px rgba(0,0,0,0.07);font-size:15px;line-height:1.9;color:#444;">

        <p>บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด ("บริษัท") ใช้คุกกี้และเทคโนโลยีที่คล้ายคลึงกันบนเว็บไซต์ <strong>www.kgm1993.com</strong> นโยบายนี้อธิบายถึงประเภทของคุกกี้ที่เราใช้ วัตถุประสงค์ และวิธีที่ท่านสามารถควบคุมการใช้งานคุกกี้</p>

        {{-- 1 --}}
        <h2 id="what-are-cookies" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">1. คุกกี้คืออะไร</h2>
        <p>คุกกี้ (Cookie) คือไฟล์ข้อความขนาดเล็กที่เว็บไซต์บันทึกลงในอุปกรณ์ของท่าน (คอมพิวเตอร์ สมาร์ทโฟน หรือแท็บเล็ต) เมื่อท่านเยี่ยมชมเว็บไซต์ คุกกี้ช่วยให้เว็บไซต์จดจำการตั้งค่า การกระทำ และความชื่นชอบของท่าน เพื่อให้ท่านไม่ต้องป้อนข้อมูลซ้ำในการเยี่ยมชมครั้งต่อไป</p>
        <p>คุกกี้ไม่ใช่ไวรัสหรือโปรแกรมที่เป็นอันตราย ไม่สามารถเข้าถึงข้อมูลส่วนตัวในอุปกรณ์ของท่านหรือดำเนินการใดๆ บนอุปกรณ์ของท่านได้</p>

        {{-- 2 --}}
        <h2 id="types" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">2. ประเภทคุกกี้ที่เราใช้</h2>

        {{-- Necessary --}}
        <div style="background:#f6fbf8;border-left:4px solid var(--kgm-green-600);border-radius:0 12px 12px 0;padding:16px 20px;margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <span style="background:var(--kgm-green-600);color:white;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;">จำเป็น</span>
                <strong style="color:var(--kgm-green-800);font-size:15px;">คุกกี้ที่จำเป็น (Strictly Necessary)</strong>
            </div>
            <p style="margin:0;font-size:14px;">คุกกี้ประเภทนี้จำเป็นสำหรับการทำงานพื้นฐานของเว็บไซต์ เช่น การเข้าสู่ระบบ การรักษาข้อมูลในตะกร้าสินค้า และการรักษาความปลอดภัย (CSRF Protection) <strong>ไม่สามารถปิดการใช้งานได้</strong> เนื่องจากเว็บไซต์จะไม่สามารถทำงานได้อย่างถูกต้องหากปราศจากคุกกี้เหล่านี้</p>
        </div>

        {{-- Analytics --}}
        <div style="background:#eff7ff;border-left:4px solid #2196f3;border-radius:0 12px 12px 0;padding:16px 20px;margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <span style="background:#2196f3;color:white;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;">วิเคราะห์</span>
                <strong style="color:#1565c0;font-size:15px;">คุกกี้เพื่อการวิเคราะห์ (Analytics)</strong>
            </div>
            <p style="margin:0;font-size:14px;">ช่วยให้เราเข้าใจพฤติกรรมการใช้งาน เช่น หน้าที่ได้รับความนิยม เส้นทางการนำทาง และระยะเวลาที่ใช้บนเว็บไซต์ ข้อมูลเหล่านี้ใช้เพื่อปรับปรุงประสบการณ์และเนื้อหา ข้อมูลที่รวบรวมเป็นข้อมูลรวม (Aggregate) ไม่สามารถระบุตัวตนบุคคลได้</p>
        </div>

        {{-- Marketing --}}
        <div style="background:#fff8f0;border-left:4px solid #ff9800;border-radius:0 12px 12px 0;padding:16px 20px;margin-bottom:16px;">
            <div style="display:flex;align-items:center;gap:10px;margin-bottom:8px;">
                <span style="background:#ff9800;color:white;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;">การตลาด</span>
                <strong style="color:#e65100;font-size:15px;">คุกกี้เพื่อการตลาด (Marketing)</strong>
            </div>
            <p style="margin:0;font-size:14px;">ใช้เพื่อนำเสนอโฆษณาและเนื้อหาที่ตรงกับความสนใจของท่าน รวมถึงวัดประสิทธิภาพแคมเปญการตลาด คุกกี้ประเภทนี้อาจถูกใช้โดยพันธมิตรโฆษณาของเรา ท่านสามารถปฏิเสธการใช้งานคุกกี้ประเภทนี้ได้โดยไม่กระทบต่อการใช้งานเว็บไซต์</p>
        </div>

        {{-- 3 --}}
        <h2 id="cookie-table" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">3. ตารางรายละเอียดคุกกี้</h2>
        <div style="overflow-x:auto;border-radius:12px;border:1.5px solid #e8ecef;">
            <table style="width:100%;border-collapse:collapse;font-size:13.5px;">
                <thead>
                    <tr style="background:var(--kgm-green-800);color:white;">
                        <th style="padding:12px 16px;text-align:left;font-weight:600;">ชื่อคุกกี้</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;">ประเภท</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;">วัตถุประสงค์</th>
                        <th style="padding:12px 16px;text-align:left;font-weight:600;white-space:nowrap;">ระยะเวลา</th>
                    </tr>
                </thead>
                <tbody>
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#555;">laravel_session</td>
                        <td style="padding:11px 16px;"><span style="background:#e8f5e9;color:var(--kgm-green-700);font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">จำเป็น</span></td>
                        <td style="padding:11px 16px;">เก็บข้อมูล Session ของผู้ใช้ เช่น สถานะล็อกอิน ตะกร้าสินค้า</td>
                        <td style="padding:11px 16px;white-space:nowrap;">Session</td>
                    </tr>
                    <tr style="background:#fafafa;border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#555;">XSRF-TOKEN</td>
                        <td style="padding:11px 16px;"><span style="background:#e8f5e9;color:var(--kgm-green-700);font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">จำเป็น</span></td>
                        <td style="padding:11px 16px;">ป้องกันการโจมตี Cross-Site Request Forgery (CSRF)</td>
                        <td style="padding:11px 16px;white-space:nowrap;">Session</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#555;">remember_web_*</td>
                        <td style="padding:11px 16px;"><span style="background:#e8f5e9;color:var(--kgm-green-700);font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">จำเป็น</span></td>
                        <td style="padding:11px 16px;">จดจำการล็อกอินเมื่อเลือก "จดจำฉัน"</td>
                        <td style="padding:11px 16px;white-space:nowrap;">30 วัน</td>
                    </tr>
                    <tr style="background:#fafafa;border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#555;">cookie_consent</td>
                        <td style="padding:11px 16px;"><span style="background:#e8f5e9;color:var(--kgm-green-700);font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">จำเป็น</span></td>
                        <td style="padding:11px 16px;">บันทึกการตั้งค่าความยินยอมคุกกี้ของท่าน</td>
                        <td style="padding:11px 16px;white-space:nowrap;">1 ปี</td>
                    </tr>
                    <tr style="border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#555;">_ga, _ga_*</td>
                        <td style="padding:11px 16px;"><span style="background:#e3f2fd;color:#1565c0;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">วิเคราะห์</span></td>
                        <td style="padding:11px 16px;">Google Analytics — ติดตามพฤติกรรมผู้เข้าชมในภาพรวม</td>
                        <td style="padding:11px 16px;white-space:nowrap;">2 ปี</td>
                    </tr>
                    <tr style="background:#fafafa;border-bottom:1px solid #f0f0f0;">
                        <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#555;">_gid</td>
                        <td style="padding:11px 16px;"><span style="background:#e3f2fd;color:#1565c0;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">วิเคราะห์</span></td>
                        <td style="padding:11px 16px;">Google Analytics — แยกแยะผู้ใช้แต่ละคนในช่วง 24 ชั่วโมง</td>
                        <td style="padding:11px 16px;white-space:nowrap;">24 ชั่วโมง</td>
                    </tr>
                    <tr>
                        <td style="padding:11px 16px;font-family:monospace;font-size:12px;color:#555;">_fbp</td>
                        <td style="padding:11px 16px;"><span style="background:#fff3e0;color:#e65100;font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;">การตลาด</span></td>
                        <td style="padding:11px 16px;">Facebook Pixel — ติดตามการแปลงและแสดงโฆษณาที่เกี่ยวข้อง</td>
                        <td style="padding:11px 16px;white-space:nowrap;">3 เดือน</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- 4 --}}
        <h2 id="third-party" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">4. คุกกี้ของบุคคลที่สาม</h2>
        <p>บางหน้าของเว็บไซต์อาจฝังเนื้อหาหรือฟีเจอร์จากบุคคลที่สาม ซึ่งอาจมีการใช้คุกกี้ของตัวเอง ได้แก่:</p>
        <ul style="padding-left:20px;margin:12px 0;">
            <li style="margin-bottom:8px;"><strong>Google Analytics (Google LLC)</strong> — วิเคราะห์สถิติการเข้าชม นโยบายความเป็นส่วนตัวของ Google: <a href="https://policies.google.com/privacy" target="_blank" style="color:var(--kgm-green-600);">policies.google.com/privacy</a></li>
            <li style="margin-bottom:8px;"><strong>Facebook / Meta Pixel (Meta Platforms, Inc.)</strong> — วัดผลโฆษณาและรีมาร์เก็ตติ้ง นโยบาย Meta: <a href="https://www.facebook.com/privacy/explanation" target="_blank" style="color:var(--kgm-green-600);">facebook.com/privacy</a></li>
            <li style="margin-bottom:8px;"><strong>Google Maps (Google LLC)</strong> — แสดงแผนที่สาขา</li>
            <li><strong>LINE (LINE Corporation)</strong> — ปุ่มติดต่อและแชร์ผ่าน LINE</li>
        </ul>
        <p>บริษัทไม่มีอำนาจควบคุมคุกกี้ของบุคคลที่สามเหล่านี้ กรุณาอ่านนโยบายความเป็นส่วนตัวของแต่ละผู้ให้บริการโดยตรง</p>

        {{-- 5 --}}
        <h2 id="manage" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">5. วิธีจัดการคุกกี้</h2>
        <p>ท่านมีสิทธิ์ควบคุมการใช้งานคุกกี้ได้หลายวิธี:</p>

        <div style="background:#f8f9fa;border-radius:12px;padding:20px;margin-bottom:16px;">
            <div style="font-weight:700;color:var(--kgm-green-800);margin-bottom:10px;"><i class="bi bi-toggles"></i> ผ่านเว็บไซต์ของเรา</div>
            <p style="margin:0;font-size:14px;">คลิกปุ่ม "จัดการตั้งค่า" ในแบนเนอร์คุกกี้ที่ปรากฏเมื่อเข้าเว็บไซต์ครั้งแรก หรือติดต่อเราเพื่อรีเซ็ตการตั้งค่า ท่านสามารถเปลี่ยนแปลงการตั้งค่าได้ตลอดเวลา</p>
        </div>

        <div style="background:#f8f9fa;border-radius:12px;padding:20px;margin-bottom:16px;">
            <div style="font-weight:700;color:var(--kgm-green-800);margin-bottom:10px;"><i class="bi bi-browser-chrome"></i> ผ่านการตั้งค่าเบราว์เซอร์</div>
            <p style="font-size:14px;margin-bottom:10px;">เบราว์เซอร์ส่วนใหญ่อนุญาตให้ท่านจัดการคุกกี้ผ่านการตั้งค่า:</p>
            <ul style="padding-left:18px;font-size:13.5px;margin:0;">
                <li style="margin-bottom:6px;"><strong>Google Chrome:</strong> การตั้งค่า → ความเป็นส่วนตัวและความปลอดภัย → คุกกี้และข้อมูลไซต์อื่นๆ</li>
                <li style="margin-bottom:6px;"><strong>Mozilla Firefox:</strong> การตั้งค่า → ความเป็นส่วนตัวและความปลอดภัย → คุกกี้และข้อมูลของไซต์</li>
                <li style="margin-bottom:6px;"><strong>Safari:</strong> การตั้งค่า → ความเป็นส่วนตัว → จัดการข้อมูลเว็บไซต์</li>
                <li><strong>Microsoft Edge:</strong> การตั้งค่า → คุกกี้และสิทธิ์ไซต์ → คุกกี้และข้อมูลที่บันทึกไว้</li>
            </ul>
        </div>

        <div style="background:#fff8e1;border-left:4px solid #ffc107;border-radius:0 12px 12px 0;padding:14px 18px;">
            <p style="margin:0;font-size:13.5px;"><i class="bi bi-exclamation-triangle-fill" style="color:#f57c00;"></i> <strong>หมายเหตุ:</strong> การปิดคุกกี้ที่จำเป็นอาจทำให้ฟีเจอร์บางอย่างของเว็บไซต์ไม่ทำงาน เช่น การเข้าสู่ระบบและตะกร้าสินค้า</p>
        </div>

        {{-- 6 --}}
        <h2 id="changes" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">6. การเปลี่ยนแปลงนโยบายนี้</h2>
        <p>บริษัทอาจปรับปรุงนโยบายคุกกี้นี้เป็นครั้งคราวเพื่อให้สอดคล้องกับการเปลี่ยนแปลงทางกฎหมายหรือวิธีการดำเนินงานของเรา การเปลี่ยนแปลงที่มีสาระสำคัญจะแจ้งผ่านแบนเนอร์บนเว็บไซต์ วันที่บังคับใช้ปรากฏที่ด้านบนของหน้านี้</p>

        {{-- 7 --}}
        <h2 id="contact" style="font-size:19px;font-weight:800;color:var(--kgm-green-800);margin:36px 0 14px;padding-top:8px;border-top:2px solid #f0f0f0;">7. ติดต่อเรา</h2>
        <p>หากท่านมีคำถามเกี่ยวกับนโยบายคุกกี้นี้ กรุณาติดต่อ:</p>
        <div style="background:#f6fbf8;border-radius:12px;padding:20px 24px;display:flex;flex-direction:column;gap:10px;font-size:14px;">
            <div><i class="bi bi-building" style="color:var(--kgm-green-600);width:20px;"></i> <strong>บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด</strong></div>
            <div><i class="bi bi-geo-alt" style="color:var(--kgm-green-600);width:20px;"></i> 123 ถ.เจริญกรุง แขวงบางรัก กรุงเทพฯ 10500</div>
            <div><i class="bi bi-telephone" style="color:var(--kgm-green-600);width:20px;"></i> <a href="tel:020001234" style="color:var(--kgm-green-700);">02-000-1234</a></div>
            <div><i class="bi bi-envelope" style="color:var(--kgm-green-600);width:20px;"></i> <a href="mailto:info@kgm1993.com" style="color:var(--kgm-green-700);">info@kgm1993.com</a></div>
            <div><i class="bi bi-clock" style="color:var(--kgm-green-600);width:20px;"></i> จันทร์–ศุกร์ 08:00–17:30 น.</div>
        </div>

        <p style="margin-top:28px;font-size:12.5px;color:#aaa;text-align:center;">
            อ่านเพิ่มเติม:
            <a href="{{ route('privacy-policy') }}" style="color:var(--kgm-green-600);">นโยบายความเป็นส่วนตัว</a> &nbsp;·&nbsp;
            <a href="{{ route('terms') }}" style="color:var(--kgm-green-600);">ข้อกำหนดการใช้งาน</a>
        </p>

    </div>
</div>
@endsection
