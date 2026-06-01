@extends('layouts.admin')
@section('title', isset($post) ? 'แก้ไขบทความ' : 'สร้างบทความ')
@section('content')
<div class="page-header">
    <div class="page-title">{{ isset($post) ? 'แก้ไขบทความ' : 'สร้างบทความ' }}</div>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<form method="POST" action="{{ isset($post) ? route('admin.posts.update',$post) : route('admin.posts.store') }}" enctype="multipart/form-data">
    @csrf @if(isset($post)) @method('PUT') @endif
    <div style="display:grid;grid-template-columns:2fr 1fr;gap:24px;">
        <div>
            <div class="form-card">
                <h3><i class="bi bi-newspaper"></i> เนื้อหาบทความ</h3>
                <div class="form-group"><label class="form-label">ชื่อบทความ *</label><input type="text" name="title" class="form-control" value="{{ old('title',$post->title??'') }}" required></div>
                <div class="form-group"><label class="form-label">สรุปย่อ</label><textarea name="excerpt" class="form-control" rows="3">{{ old('excerpt',$post->excerpt??'') }}</textarea></div>
                <div class="form-group"><label class="form-label">เนื้อหา *</label><textarea name="body" class="form-control" rows="15" id="post-body">{{ old('body',$post->body??'') }}</textarea></div>
            </div>
            <div class="form-card">
                <h3><i class="bi bi-search"></i> SEO</h3>
                <div class="form-group"><label class="form-label">Meta Title</label><input type="text" name="meta_title" class="form-control" value="{{ old('meta_title',$post->meta_title??'') }}"></div>
                <div class="form-group"><label class="form-label">Meta Description</label><textarea name="meta_description" class="form-control" rows="2">{{ old('meta_description',$post->meta_description??'') }}</textarea></div>
            </div>
        </div>
        <div>
            <div class="form-card">
                <h3><i class="bi bi-toggles"></i> การตั้งค่า</h3>
                <div class="form-group"><label class="form-label">หมวดหมู่</label>
                    <select name="post_category_id" class="form-control">
                        <option value="">-- เลือกหมวด --</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ old('post_category_id',$post->post_category_id??'')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group"><label class="form-label">สถานะ</label>
                    <select name="status" class="form-control">
                        <option value="draft" {{ old('status',$post->status??'draft')=='draft'?'selected':'' }}>Draft</option>
                        <option value="published" {{ old('status',$post->status??'')=='published'?'selected':'' }}>เผยแพร่</option>
                        <option value="scheduled" {{ old('status',$post->status??'')=='scheduled'?'selected':'' }}>ตั้งเวลา</option>
                    </select>
                </div>
                <div class="form-group"><label class="form-label">วันที่เผยแพร่</label><input type="datetime-local" name="published_at" class="form-control" value="{{ old('published_at',$post->published_at?->format('Y-m-d\TH:i')??'') }}"></div>
                <div class="form-group"><label class="form-label">รูปหน้าปก</label><input type="file" name="featured_image" class="form-control" accept="image/*" style="border-radius:12px;">
                    @if(isset($post) && $post->featured_image)
                    <img src="{{ asset('storage/'.$post->featured_image) }}" style="width:100%;border-radius:12px;margin-top:8px;">
                    @endif
                </div>
                <button type="submit" class="btn btn-primary w-full" style="justify-content:center;"><i class="bi bi-floppy"></i> บันทึก</button>
            </div>
        </div>
    </div>
</form>
@endsection
