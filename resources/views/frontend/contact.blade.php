@extends('layouts.app')
@section('title', 'ติดต่อเรา - กิจเจริญการ์เมนท์')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/contact.css') }}">
@endpush

@section('content')

{{-- ── Hero ── --}}
<div class="contact-hero">
    <div class="container" style="position:relative;z-index:1;">
        <div class="contact-hero-badge"><i class="bi bi-envelope-heart-fill"></i> ติดต่อเรา</div>
        <h1>ยินดีให้คำปรึกษาเสมอ</h1>
        <p>มีคำถาม ต้องการสั่งผลิต หรืออยากเยี่ยมชมโรงงาน ทีมงานของเราพร้อมให้บริการทุกวัน</p>
    </div>
</div>

{{-- ── Quick-info strip ── --}}
<div class="quick-strip">
    <div class="container">
        <div class="quick-grid">
            <div class="quick-item">
                <div class="quick-icon"><i class="bi bi-telephone-fill"></i></div>
                <div>
                    <div class="quick-label">โทรศัพท์หลัก</div>
                    <div class="quick-value">0-4561-1111<br>0-4563-3111</div>
                </div>
            </div>
            <div class="quick-item">
                <div class="quick-icon"><i class="bi bi-envelope-fill"></i></div>
                <div>
                    <div class="quick-label">อีเมล</div>
                    <div class="quick-value">info@kgm.co.th</div>
                </div>
            </div>
            <div class="quick-item">
                <div class="quick-icon"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <div class="quick-label">เวลาทำการ</div>
                    <div class="quick-value">จันทร์ – เสาร์<br>08:00 – 17:00 น.</div>
                </div>
            </div>
            <div class="quick-item">
                <div class="quick-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                    <div class="quick-label">จังหวัด</div>
                    <div class="quick-value">ศรีสะเกษ<br>33000</div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── Body ── --}}
<div class="contact-body">
    <div class="container">
        <div class="contact-grid">

            {{-- Info column --}}
            <div style="display:flex;flex-direction:column;gap:24px;">

                {{-- Address & main contact --}}
                <div class="info-card">
                    <div class="info-card-title"><i class="bi bi-building-fill" style="color:var(--kgm-green-500);"></i> ข้อมูลติดต่อ</div>

                    <div class="contact-row">
                        <div class="contact-row-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <div class="contact-row-label">ที่อยู่</div>
                            <div class="contact-row-value">364 หมู่ 6 ถ.ศรีสะเกษ-กันทรลักษ์<br>ต.โพนข่า อ.เมืองศรีสะเกษ จ.ศรีสะเกษ 33000</div>
                        </div>
                    </div>

                    <div class="contact-row">
                        <div class="contact-row-icon"><i class="bi bi-telephone-fill"></i></div>
                        <div>
                            <div class="contact-row-label">โทรศัพท์หลัก</div>
                            <div class="contact-row-value">
                                <a href="tel:04561111">0-4561-1111</a> &nbsp;|&nbsp;
                                <a href="tel:04563311">0-4563-3111</a>
                            </div>
                        </div>
                    </div>

                    <div class="contact-row">
                        <div class="contact-row-icon"><i class="bi bi-printer-fill"></i></div>
                        <div>
                            <div class="contact-row-label">แฟกซ์</div>
                            <div class="contact-row-value">0-4561-7333 &nbsp;|&nbsp; 0-4561-1333</div>
                        </div>
                    </div>

                    <div class="contact-row">
                        <div class="contact-row-icon"><i class="bi bi-envelope-fill"></i></div>
                        <div>
                            <div class="contact-row-label">อีเมล</div>
                            <div class="contact-row-value"><a href="mailto:info@kgm.co.th">info@kgm.co.th</a></div>
                        </div>
                    </div>
                    <div class="contact-row" style="border-bottom:none;padding-bottom:0;">
                        <div class="contact-row-icon"><i class="bi bi-clock-fill"></i></div>
                        <div>
                            <div class="contact-row-label">เวลาทำการ</div>
                            <div class="contact-row-value">จันทร์ – เสาร์ &nbsp; 08:00 – 17:00 น.</div>
                        </div>
                    </div>
                </div>

                {{-- Dept contacts --}}
                <div class="info-card">
                    <div class="info-card-title"><i class="bi bi-diagram-3-fill" style="color:var(--kgm-green-500);"></i> ติดต่อแผนก</div>
                    <div class="dept-grid">
                        @foreach([
                            ['ฝ่ายขาย',      '085-110-0010<br>085-110-0060'],
                            ['ฝ่าย HR',       '085-110-0040'],
                            ['ฝ่ายจัดซื้อ',   '085-110-0070'],
                            ['ฝ่ายการเงิน',   '085-110-0080'],
                            ['ฝ่ายการตลาด',   '085-110-0090'],
                            ['ฝ่ายบัญชี',     '081-000-0202<br>081-000-0505'],
                        ] as [$dept,$phone])
                        <div class="dept-item">
                            <div class="dept-name">{{ $dept }}</div>
                            <div class="dept-phone">{!! $phone !!}</div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- Form column --}}
            <div class="form-card">
                <div class="form-card-title"><i class="bi bi-send-fill" style="color:var(--kgm-green-500);margin-right:8px;"></i>ส่งข้อความถึงเรา</div>
                <div class="form-card-sub">กรอกข้อมูลด้านล่าง ทีมงานจะติดต่อกลับภายใน 1 วันทำการ</div>

                @if(session('success'))
                <div class="alert-success-custom">
                    <i class="bi bi-check-circle-fill" style="font-size:20px;flex-shrink:0;"></i>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                <form method="POST" action="{{ route('contact.submit') }}">
                    @csrf
                    <div class="two-col">
                        <div class="field-group">
                            <label class="field-label">ชื่อ-นามสกุล <span>*</span></label>
                            <input type="text" name="name" class="field-input" placeholder="กรอกชื่อของคุณ" value="{{ old('name') }}" required>
                            @error('name')<div style="font-size:12px;color:#e74c3c;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                        <div class="field-group">
                            <label class="field-label">อีเมล <span>*</span></label>
                            <input type="email" name="email" class="field-input" placeholder="your@email.com" value="{{ old('email') }}" required>
                            @error('email')<div style="font-size:12px;color:#e74c3c;margin-top:4px;">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="two-col">
                        <div class="field-group">
                            <label class="field-label">เบอร์โทรศัพท์</label>
                            <input type="tel" name="phone" class="field-input" placeholder="08x-xxx-xxxx" value="{{ old('phone') }}">
                        </div>
                        <div class="field-group">
                            <label class="field-label">หัวข้อ</label>
                            <select name="subject" class="field-input">
                                <option value="">-- เลือกหัวข้อ --</option>
                                <option value="สั่งผลิตเครื่องแบบ" {{ old('subject')=='สั่งผลิตเครื่องแบบ'?'selected':'' }}>สั่งผลิตเครื่องแบบ</option>
                                <option value="สอบถามราคา" {{ old('subject')=='สอบถามราคา'?'selected':'' }}>สอบถามราคา</option>
                                <option value="งานปักและสกรีน" {{ old('subject')=='งานปักและสกรีน'?'selected':'' }}>งานปักและสกรีน</option>
                                <option value="ข้อมูลสินค้า" {{ old('subject')=='ข้อมูลสินค้า'?'selected':'' }}>ข้อมูลสินค้า</option>
                                <option value="อื่นๆ" {{ old('subject')=='อื่นๆ'?'selected':'' }}>อื่นๆ</option>
                            </select>
                        </div>
                    </div>
                    <div class="field-group">
                        <label class="field-label">ข้อความ <span>*</span></label>
                        <textarea name="message" class="field-input" placeholder="รายละเอียดที่ต้องการ เช่น ประเภทชุด จำนวน ขนาด ไซซ์ ..." required>{{ old('message') }}</textarea>
                        @error('message')<div style="font-size:12px;color:#e74c3c;margin-top:4px;">{{ $message }}</div>@enderror
                    </div>
                    <button type="submit" class="submit-btn">
                        <i class="bi bi-send-fill"></i> ส่งข้อความ
                    </button>
                </form>

                <div style="margin-top:24px;padding-top:20px;border-top:1px solid #f0f0f0;display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:12px;background:#06c755;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-chat-fill" style="color:white;font-size:18px;"></i>
                    </div>
                    <div>
                        <div style="font-size:12px;color:#888;margin-bottom:2px;">ต้องการคำตอบเร็วกว่านี้?</div>
                        <div style="font-size:14px;font-weight:700;color:#333;">ติดต่อผ่าน <a href="tel:0851100010" style="color:var(--kgm-green-600);text-decoration:none;">085-110-0010</a> (ฝ่ายขาย)</div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- ── Map ── --}}
<div class="map-section pt-0 pb-5">
    <div class="container">
        <div class="map-section-title">
            <i class="bi bi-map-fill" style="color:var(--kgm-green-500);"></i> แผนที่และเส้นทางมาบริษัท
        </div>
        <div class="row">

            {{-- Route image --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="route-card-header">
                        <i class="bi bi-signpost-2-fill"></i> เส้นทางการเดินทาง
                    </div>
                    <div class="route-img-scroll">
                        <img src="{{ asset('images/map2019.jpg') }}" alt="แผนที่เส้นทางมาบริษัท กิจเจริญการ์เมนท์" loading="lazy">
                    </div>
                    <div style="padding:12px 16px;background:#f8faf9;border-top:1px solid #eee;font-size:12px;color:#888;text-align:center;flex-shrink:0;">
                        <i class="bi bi-hand-index-thumb" style="margin-right:4px;"></i> เลื่อนดูได้
                    </div>
                </div>
            </div>

            {{-- Google Maps --}}
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="gmap-card">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3852.3178089945327!2d104.3426217!3d15.085803099999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3116e2da6a5f4905%3A0xd657a85f727cc09d!2z4Lia4Lij4Li04Lip4Lix4LiXIOC4geC4tOC4iOC5gOC4iOC4o-C4tOC4jeC4geC4suC4o-C5jOC5gOC4oeC4meC4l-C5jCAoMTk5Mykg4LiI4Liz4LiB4Lix4LiU!5e0!3m2!1sth!2sth!4v1780487447326!5m2!1sth!2sth" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection
