@extends('layouts.admin')
@section('title', 'ข้อความจาก '.$message->name)
@section('content')
<div class="page-header">
    <div class="page-title">ข้อความจาก {{ $message->name }}</div>
    <a href="{{ route('admin.contacts.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="max-width:800px;">
    <div class="form-card">
        <h3><i class="bi bi-envelope"></i> รายละเอียดข้อความ</h3>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;font-size:14px;">
            <div><strong>ชื่อ:</strong> {{ $message->name }}</div>
            <div><strong>อีเมล:</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a></div>
            <div><strong>เบอร์โทร:</strong> {{ $message->phone ?? '-' }}</div>
            <div><strong>เรื่อง:</strong> {{ $message->subject ?? '-' }}</div>
        </div>
        <div style="background:#f8faf8;border-radius:14px;padding:16px;font-size:14px;line-height:1.8;">{{ $message->message }}</div>
        @if($message->replied_at)
        <div style="margin-top:20px;background:var(--g100);border-radius:14px;padding:16px;">
            <div style="font-size:12px;color:#555;margin-bottom:8px;font-weight:700;">ตอบกลับเมื่อ {{ $message->replied_at->format('d/m/Y H:i') }}</div>
            <div style="font-size:14px;line-height:1.8;">{{ $message->reply }}</div>
        </div>
        @endif
    </div>
    <div class="form-card">
        <h3><i class="bi bi-reply"></i> ตอบกลับ</h3>
        <form method="POST" action="{{ route('admin.contacts.reply', $message) }}">
            @csrf
            <div class="form-group">
                <textarea name="reply" class="form-control" rows="5" placeholder="พิมพ์ข้อความตอบกลับ..." required>{{ $message->reply }}</textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="bi bi-send"></i> ส่งคำตอบ</button>
        </form>
    </div>
</div>
@endsection
