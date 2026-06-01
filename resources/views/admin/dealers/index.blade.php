@extends('layouts.admin')
@section('title', 'ใบสมัครตัวแทนจำหน่าย')
@section('content')
<div class="page-header"><div class="page-title">ใบสมัครตัวแทนจำหน่าย</div></div>
<div class="table-wrap">
    <table>
        <thead><tr><th>ชื่อร้าน/บริษัท</th><th>ผู้ติดต่อ</th><th>จังหวัด</th><th>สถานะ</th><th>วันที่</th><th></th></tr></thead>
        <tbody>
        @forelse($dealers as $d)
        <tr>
            <td><strong>{{ $d->business_name }}</strong></td>
            <td>{{ $d->contact_name }}<br><span style="font-size:12px;color:#888;">{{ $d->phone }}</span></td>
            <td>{{ $d->province }}</td>
            <td>
                <span class="status-badge {{ $d->status === 'approved' ? 'status-green' : ($d->status === 'new' ? 'status-yellow' : 'status-gray') }}">
                    {{ ['new'=>'ใหม่','reviewing'=>'กำลังพิจารณา','approved'=>'อนุมัติ','rejected'=>'ปฏิเสธ'][$d->status] }}
                </span>
            </td>
            <td style="font-size:12px;color:#888;">{{ $d->created_at->format('d/m/Y') }}</td>
            <td>
                <form method="POST" action="{{ route('admin.dealers.status', $d) }}" style="display:inline-flex;gap:6px;align-items:center;">
                    @csrf @method('PATCH')
                    <select name="status" class="form-control" style="width:150px;font-size:12px;border-radius:10px;padding:4px 10px;" onchange="this.form.submit()">
                        @foreach(['new'=>'ใหม่','reviewing'=>'พิจารณา','approved'=>'อนุมัติ','rejected'=>'ปฏิเสธ'] as $v=>$l)
                        <option value="{{ $v }}" {{ $d->status===$v?'selected':'' }}>{{ $l }}</option>
                        @endforeach
                    </select>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีใบสมัคร</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $dealers->links() }}</div>
</div>
@endsection
