@extends('layouts.admin')
@section('title', 'ข้อความติดต่อ')
@section('content')
<div class="page-header"><div class="page-title">ข้อความติดต่อจากลูกค้า</div></div>
<div class="table-wrap">
    <table>
        <thead><tr><th>ชื่อ</th><th>เรื่อง</th><th>เบอร์โทร</th><th>สถานะ</th><th>วันที่</th><th></th></tr></thead>
        <tbody>
        @forelse($messages as $msg)
        <tr style="{{ !$msg->is_read ? 'font-weight:600;' : '' }}">
            <td>{{ $msg->name }}<br><span style="font-size:12px;color:#888;">{{ $msg->email }}</span></td>
            <td>{{ $msg->subject ?? substr($msg->message,0,50).'...' }}</td>
            <td>{{ $msg->phone ?? '-' }}</td>
            <td>
                @if(!$msg->is_read)<span class="status-badge status-blue">ใหม่</span>
                @elseif($msg->replied_at)<span class="status-badge status-green">ตอบแล้ว</span>
                @else<span class="status-badge status-gray">อ่านแล้ว</span>@endif
            </td>
            <td style="font-size:12px;">{{ $msg->created_at->format('d/m/y H:i') }}</td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.contacts.show', $msg) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i> ดู/ตอบ</a>
                <form method="POST" action="{{ route('admin.contacts.destroy', $msg) }}" onsubmit="return confirm('ลบ?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ไม่มีข้อความ</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $messages->links() }}</div>
</div>
@endsection
