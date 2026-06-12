@extends('layouts.admin')
@section('title', isset($post) ? 'แก้ไขบทความ' : 'สร้างบทความ')
@push('styles')
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<style>
    .ql-container { font-family: 'Sarabun', sans-serif; font-size: 15px; }
    .ql-editor { min-height: 320px; }
    .ql-toolbar.ql-snow, .ql-container.ql-snow { border-color: #e8ecef; }
    .ql-toolbar.ql-snow { border-radius: 14px 14px 0 0; background: #fafafa; }
    .ql-container.ql-snow { border-radius: 0 0 14px 14px; }
    .is-invalid-quill .ql-container.ql-snow { border-color: #e74c3c; }
    .cover-preview { width: 100%; border-radius: 12px; margin-top: 10px; object-fit: cover; max-height: 200px; display: block; }
    .cover-placeholder { width: 100%; height: 140px; border-radius: 12px; background: #f0f4f0; border: 2px dashed #c8d8cc; display: flex; flex-direction: column; align-items: center; justify-content: center; color: #aaa; gap: 6px; margin-top: 10px; font-size: 13px; cursor: pointer; }
    .cover-placeholder i { font-size: 28px; }
</style>
@endpush
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($post) ? 'แก้ไขบทความ' : 'สร้างบทความ' }}</div>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<form method="POST" action="{{ isset($post) ? route('admin.posts.update',$post) : route('admin.posts.store') }}" enctype="multipart/form-data" id="post-form">
    @csrf @if(isset($post)) @method('PUT') @endif
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div>
            <div class="form-card">
                <h3><i class="bi bi-newspaper"></i> เนื้อหาบทความ</h3>
                <div class="form-group">
                    <label class="form-label">ชื่อบทความ *</label>
                    <input type="text" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title',$post->title??'') }}" required>
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">สรุปย่อ</label>
                    <textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt',$post->excerpt??'') }}</textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">เนื้อหา *</label>
                    <div id="quill-editor" class="{{ $errors->has('body') ? 'is-invalid-quill' : '' }}"></div>
                    <textarea name="body" id="post-body" style="display:none;">{{ old('body',$post->body??'') }}</textarea>
                    @error('body')<div class="invalid-feedback" style="display:block;">{{ $message }}</div>@enderror
                </div>
            </div>
            <div class="form-card">
                <h3><i class="bi bi-search"></i> SEO</h3>
                <div class="form-group"><label class="form-label">Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title',$post->meta_title??'') }}"></div>
                <div class="form-group"><label class="form-label">Meta Description</label><textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description',$post->meta_description??'') }}</textarea></div>
            </div>
        </div>
        <div>
            <div class="form-card">
                <h3><i class="bi bi-image"></i> รูปปกบทความ</h3>
                <div class="form-group">
                    <input type="file" name="featured_image" id="featured_image_input" class="form-control" accept="image/*" style="border-radius:12px;" onchange="previewCover(this)">
                    <div id="cover-preview-wrap">
                        @if(isset($post) && $post->featured_image)
                            <img id="cover-preview-img" src="{{ asset('storage/'.$post->featured_image) }}" class="cover-preview">
                        @else
                            <div class="cover-placeholder" onclick="document.getElementById('featured_image_input').click()">
                                <i class="bi bi-image-alt"></i>
                                <span>คลิกเพื่อเลือกรูปปก</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="form-card">
                <h3><i class="bi bi-toggles"></i> การตั้งค่า</h3>
                <div class="form-group">
                    <label class="form-label">หมวดหมู่</label>
                    <select name="post_category_id" class="form-control">
                        <option value="">-- เลือกหมวด --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('post_category_id',$post->post_category_id??'')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">สถานะ</label>
                    <select name="status" class="form-control">
                        <option value="draft" {{ old('status',$post->status??'draft')=='draft'?'selected':'' }}>Draft</option>
                        <option value="published" {{ old('status',$post->status??'')=='published'?'selected':'' }}>เผยแพร่</option>
                        <option value="scheduled" {{ old('status',$post->status??'')=='scheduled'?'selected':'' }}>ตั้งเวลา</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">วันที่เผยแพร่</label>
                    <input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at', ($post??null)?->published_at?->format('Y-m-d H:i')??'') }}">
                </div>
                <div class="form-group" style="background:#fffbeb;border-radius:14px;padding:14px 16px;border:2px solid #fde68a;">
                    <div class="form-check" style="margin:0;">
                        <input type="checkbox" name="is_top" id="is_top" value="1"
                            {{ old('is_top', $post->is_top ?? false) ? 'checked' : '' }}>
                        <label for="is_top" style="font-weight:700;color:#92400e;">
                            <i class="bi bi-pin-angle-fill" style="color:#f59e0b;"></i> ปักหมุดบทความแนะนำ (Top)
                        </label>
                    </div>
                    <p style="font-size:12px;color:#a16207;margin-top:6px;margin-left:26px;">แสดงแบนเนอร์แนวนอนที่ด้านบนหน้าบทความ — มีได้ 1 บทความเท่านั้น</p>
                </div>
                <button type="submit" class="btn btn-primary w-full" style="justify-content:center;margin-top:8px;"><i class="bi bi-floppy"></i> บันทึก</button>
            </div>
        </div>
    </div>
</form>
@endsection
@push('scripts')
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
<script>
const quill = new Quill('#quill-editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ color: [] }, { background: [] }],
            [{ list: 'ordered' }, { list: 'bullet' }],
            [{ align: [] }],
            ['blockquote'],
            ['link', 'image'],
            ['clean'],
        ]
    }
});

const initialBody = document.getElementById('post-body').value;
if (initialBody) {
    quill.root.innerHTML = initialBody;
}

document.getElementById('post-form').addEventListener('submit', function () {
    document.getElementById('post-body').value = quill.root.innerHTML;
});

function previewCover(input) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = function (e) {
        const wrap = document.getElementById('cover-preview-wrap');
        wrap.innerHTML = '<img id="cover-preview-img" src="' + e.target.result + '" class="cover-preview">';
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
