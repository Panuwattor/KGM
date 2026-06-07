@extends('layouts.admin')
@section('title', isset($video) ? 'แก้ไขวิดีโอ' : 'เพิ่มวิดีโอ')

@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ isset($video) ? 'แก้ไขวิดีโอ' : 'เพิ่มวิดีโอใหม่' }}</div>
        <div class="page-subtitle">เรื่องราวที่น่าสนใจ</div>
    </div>
    <a href="{{ route('admin.videos.index') }}" class="btn btn-outline">
        <i class="bi bi-arrow-left"></i> กลับ
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger mb-4">
    @foreach($errors->all() as $error)
    <div><i class="bi bi-exclamation-circle me-1"></i>{{ $error }}</div>
    @endforeach
</div>
@endif

<form action="{{ isset($video) ? route('admin.videos.update', $video) : route('admin.videos.store') }}" method="POST">
    @csrf
    @if(isset($video)) @method('PUT') @endif

    <div style="display:grid;grid-template-columns:1fr 340px;gap:20px;align-items:start;">

        {{-- LEFT COLUMN --}}
        <div>
            {{-- วิธีก๊อปลิงก์ --}}
            <div class="form-card" style="background:linear-gradient(135deg,#f0fdf4,#f8fffb);border:1px solid #d1fae5;margin-bottom:20px;">
                <div style="font-size:15px;font-weight:700;color:var(--g700);margin-bottom:12px;">
                    <i class="bi bi-info-circle-fill" style="color:var(--g500);"></i> วิธีก๊อปลิงก์ YouTube
                </div>
                <div style="font-size:13px;color:#555;line-height:1.9;">
                    <div style="display:flex;gap:10px;margin-bottom:8px;">
                        <span style="background:var(--g500);color:white;border-radius:50%;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">1</span>
                        <span>เปิด YouTube แล้วไปยังวิดีโอที่ต้องการ</span>
                    </div>
                    <div style="display:flex;gap:10px;margin-bottom:8px;">
                        <span style="background:var(--g500);color:white;border-radius:50%;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">2</span>
                        <span>คลิกที่ปุ่ม <strong>"แชร์"</strong> (Share) ใต้วิดีโอ</span>
                    </div>
                    <div style="display:flex;gap:10px;margin-bottom:8px;">
                        <span style="background:var(--g500);color:white;border-radius:50%;width:22px;height:22px;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;flex-shrink:0;">3</span>
                        <span>คลิก <strong>"คัดลอกลิงก์"</strong> หรือ Copy link แล้วนำมาวางในช่องด้านล่าง</span>
                    </div>
                    <div style="margin-top:10px;background:white;border-radius:8px;padding:10px 14px;font-size:12px;color:#888;border:1px solid #e5e7eb;">
                        <strong>ตัวอย่างลิงก์ที่รองรับ:</strong><br>
                        <code style="color:var(--g600);">https://www.youtube.com/watch?v=XXXXX</code><br>
                        <code style="color:var(--g600);">https://youtu.be/XXXXX</code><br>
                        <code style="color:var(--g600);">https://www.youtube.com/shorts/XXXXX</code>
                    </div>
                </div>
            </div>

            {{-- YouTube URL --}}
            <div class="form-card">
                <div class="mb-3">
                    <label class="form-label fw-bold">ลิงก์ YouTube <span style="color:red">*</span></label>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <input type="url" name="youtube_url" id="youtube_url"
                               class="form-control @error('youtube_url') is-invalid @enderror"
                               value="{{ old('youtube_url', $video->youtube_url ?? '') }}"
                               placeholder="https://www.youtube.com/watch?v=..."
                               oninput="previewYoutube(this.value)"
                               required>
                        <button type="button" onclick="previewYoutube(document.getElementById('youtube_url').value)"
                                style="white-space:nowrap;background:var(--g500);color:white;border:none;border-radius:8px;padding:8px 14px;cursor:pointer;font-size:13px;">
                            <i class="bi bi-eye"></i> ดูตัวอย่าง
                        </button>
                    </div>
                    @error('youtube_url')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Preview --}}
                <div id="yt-preview" style="{{ isset($video) ? '' : 'display:none;' }}margin-top:12px;">
                    <div style="font-size:12px;color:#888;margin-bottom:6px;">ตัวอย่างวิดีโอ:</div>
                    <div style="position:relative;padding-bottom:56.25%;height:0;border-radius:10px;overflow:hidden;background:#000;">
                        <iframe id="yt-iframe"
                                src="{{ isset($video) ? $video->embed_url : '' }}"
                                style="position:absolute;top:0;left:0;width:100%;height:100%;"
                                frameborder="0"
                                allow="accelerometer;autoplay;clipboard-write;encrypted-media;gyroscope;picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                </div>
            </div>

            {{-- หัวข้อ + คำอธิบาย --}}
            <div class="form-card">
                <div class="mb-3">
                    <label class="form-label fw-bold">หัวข้อวิดีโอ <span style="color:red">*</span></label>
                    <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title', $video->title ?? '') }}" required maxlength="255">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="form-label fw-bold">คำอธิบาย</label>
                    <textarea name="description" class="form-control" rows="4" maxlength="2000"
                              placeholder="อธิบายเนื้อหาในคลิปนี้...">{{ old('description', $video->description ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div>
            <div class="form-card">
                <div style="font-size:14px;font-weight:700;color:var(--g800);margin-bottom:16px;">การตั้งค่า</div>

                <div class="mb-3">
                    <label class="form-label fw-bold" style="font-size:13px;">ลำดับการแสดง</label>
                    <input type="number" name="sort_order" class="form-control" min="0" max="9999"
                           value="{{ old('sort_order', $video->sort_order ?? 0) }}"
                           placeholder="0 = แสดงก่อน">
                    <div style="font-size:11px;color:#aaa;margin-top:4px;">ตัวเลขน้อย = แสดงก่อน</div>
                </div>

                <div class="mb-3" style="background:#f8faf8;border-radius:10px;padding:14px;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="is_active" value="1" style="width:16px;height:16px;"
                               {{ old('is_active', $video->is_active ?? true) ? 'checked' : '' }}>
                        <div>
                            <div style="font-weight:600;font-size:13px;">เผยแพร่</div>
                            <div style="font-size:11px;color:#888;">แสดงวิดีโอในหน้าดูทั้งหมด</div>
                        </div>
                    </label>
                </div>

                <div style="background:#fef9ec;border-radius:10px;padding:14px;border:1px solid #fde68a;">
                    <label style="display:flex;align-items:center;gap:10px;cursor:pointer;">
                        <input type="checkbox" name="is_featured" value="1" style="width:16px;height:16px;"
                               {{ old('is_featured', $video->is_featured ?? false) ? 'checked' : '' }}>
                        <div>
                            <div style="font-weight:700;font-size:13px;color:#92400e;"><i class="bi bi-star-fill" style="color:#d97706;"></i> แสดงหน้าแรก</div>
                            <div style="font-size:11px;color:#a16207;">วิดีโอจะแสดงในส่วน "เรื่องราวที่น่าสนใจ" บนหน้าหลัก</div>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100" style="padding:12px;">
                <i class="bi bi-{{ isset($video) ? 'check-lg' : 'plus-lg' }}"></i>
                {{ isset($video) ? 'บันทึกการเปลี่ยนแปลง' : 'เพิ่มวิดีโอ' }}
            </button>
        </div>

    </div>
</form>

<script>
function previewYoutube(url) {
    const pattern = /(?:youtube\.com\/(?:watch\?(?:.*&)?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/;
    const match = url.match(pattern);
    const preview = document.getElementById('yt-preview');
    const iframe  = document.getElementById('yt-iframe');
    if (match) {
        iframe.src = 'https://www.youtube.com/embed/' + match[1] + '?rel=0&showinfo=0';
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
        iframe.src = '';
    }
}
</script>
@endsection
