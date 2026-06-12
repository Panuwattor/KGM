@extends('layouts.admin')
@section('title', 'ใบสมัคร: '.$career->title)
@section('content')
<div class="page-header">
    <div><div class="page-title">ใบสมัคร: {{ $career->title }}</div></div>
    <a href="{{ route('admin.careers.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ชื่อ-สกุล</th>
                <th>ตำแหน่งที่สมัคร</th>
                <th>อีเมล</th>
                <th>โทร</th>
                <th>วันเกิด</th>
                <th>สถานะ</th>
                <th>วันที่สมัคร</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        @forelse($applications as $app)
        <tr>
            <td><strong>{{ $app->full_name }}</strong></td>
            <td>{{ $app->position_applied ?: $career->title }}</td>
            <td>{{ $app->email }}</td>
            <td>{{ $app->phone }}</td>
            <td style="font-size:12px;color:#888;">{{ $app->birthdate?->format('d/m/Y') ?? '-' }}</td>
            <td>
                <form method="POST" action="{{ route('admin.career-applications.status', $app) }}" style="display:inline-flex;gap:6px;align-items:center;">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control" style="width:150px;font-size:12px;border-radius:10px;padding:4px 10px;" onchange="this.form.submit()">
                        @foreach(['new'=>'ใหม่','reviewing'=>'กำลังพิจารณา','interviewed'=>'สัมภาษณ์','hired'=>'รับเข้าทำงาน','rejected'=>'ปฏิเสธ'] as $v=>$l)
                        <option value="{{ $v }}" {{ $app->status===$v?'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </form>
            </td>
            <td style="font-size:12px;color:#888;">{{ $app->created_at->format('d/m/Y') }}</td>
            <td style="white-space:nowrap;display:flex;gap:6px;">
                <button type="button" class="btn btn-sm btn-light" onclick="showDetail({{ $app->id }})">
                    <i class="bi bi-eye"></i> ดูรายละเอียด
                </button>
                @if($app->resume_path)
                <a href="{{ route('admin.career-applications.resume', $app) }}" class="btn btn-sm btn-light" target="_blank">
                    <i class="bi bi-file-pdf"></i> Resume
                </a>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="8" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีผู้สมัคร</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $applications->links() }}</div>
</div>

{{-- Detail modals --}}
@foreach($applications as $app)
<div id="detail-{{ $app->id }}" style="display:none;position:fixed;inset:0;z-index:1000;background:rgba(0,0,0,.45);align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;width:min(580px,95vw);max-height:85vh;overflow-y:auto;padding:28px 32px;position:relative;">
        <button onclick="document.getElementById('detail-{{ $app->id }}').style.display='none'" style="position:absolute;top:14px;right:18px;background:none;border:none;font-size:22px;cursor:pointer;color:#888;">&times;</button>
        <h3 style="margin:0 0 18px;font-size:18px;">{{ $app->full_name }}</h3>
        <table style="width:100%;border-collapse:collapse;font-size:14px;">
            <tr><td style="padding:6px 0;color:#888;width:130px;">ตำแหน่งที่สมัคร</td><td><strong>{{ $app->position_applied ?: $career->title }}</strong></td></tr>
            <tr><td style="padding:6px 0;color:#888;">อีเมล</td><td>{{ $app->email }}</td></tr>
            <tr><td style="padding:6px 0;color:#888;">โทรศัพท์</td><td>{{ $app->phone }}</td></tr>
            <tr><td style="padding:6px 0;color:#888;">วันเกิด</td><td>{{ $app->birthdate?->format('d/m/Y') ?? '-' }}</td></tr>
            <tr><td style="padding:6px 0;color:#888;">วันที่สมัคร</td><td>{{ $app->created_at->format('d/m/Y H:i') }}</td></tr>
        </table>
        @if($app->cover_letter)
        <div style="margin-top:16px;">
            <div style="font-size:13px;font-weight:600;color:#666;margin-bottom:6px;">จดหมายแนะนำตัว</div>
            <div style="background:#f8f9fa;border-radius:8px;padding:14px;font-size:14px;white-space:pre-wrap;">{{ $app->cover_letter }}</div>
        </div>
        @endif
        <div style="margin-top:16px;">
            <form method="POST" action="{{ route('admin.career-applications.status', $app) }}">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="{{ $app->status }}">
                <div style="font-size:13px;font-weight:600;color:#666;margin-bottom:6px;">หมายเหตุ (Admin)</div>
                <textarea name="admin_note" rows="3" class="form-control" style="font-size:14px;border-radius:8px;" placeholder="บันทึกหมายเหตุ...">{{ $app->admin_note }}</textarea>
                <button type="submit" class="btn btn-primary" style="margin-top:10px;font-size:13px;">บันทึกหมายเหตุ</button>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
function showDetail(id) {
    document.getElementById('detail-' + id).style.display = 'flex';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('[id^="detail-"]').forEach(el => el.style.display = 'none');
    }
});
</script>
@endsection
