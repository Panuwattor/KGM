@extends('layouts.admin')
@section('title', 'ใบสมัคร: '.$career->title)
@section('content')
<div class="page-header">
    <div><div class="page-title">ใบสมัคร: {{ $career->title }}</div></div>
    <a href="{{ route('admin.careers.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th>ชื่อ</th><th>อีเมล</th><th>โทร</th><th>สถานะ</th><th>วันที่</th><th></th></tr></thead>
        <tbody>
        @forelse($applications as $app)
        <tr>
            <td><strong>{{ $app->full_name }}</strong></td>
            <td>{{ $app->email }}</td>
            <td>{{ $app->phone }}</td>
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
            <td>
                @if($app->resume_path)
                <a href="{{ route('admin.career-applications.status',$app) }}" class="btn btn-sm btn-light"><i class="bi bi-file-pdf"></i> Resume</a>
                @endif
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีผู้สมัคร</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $applications->links() }}</div>
</div>
@endsection
