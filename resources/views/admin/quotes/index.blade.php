@extends('layouts.admin')
@section('title', 'คำขอใบเสนอราคา')
@section('content')
<div class="page-header"><div class="page-title">คำขอใบเสนอราคา B2B</div></div>
<div class="table-wrap">
    <table>
        <thead><tr><th>บริษัท</th><th>ผู้ติดต่อ</th><th>จำนวน</th><th>สถานะ</th><th>วันที่</th><th></th></tr></thead>
        <tbody>
        @forelse($quotes as $q)
        <tr>
            <td><strong>{{ $q->company_name }}</strong></td>
            <td>{{ $q->contact_name }}<br><span style="font-size:12px;color:#888;">{{ $q->email }}</span></td>
            <td>{{ number_format($q->quantity) }} ชิ้น</td>
            <td><span class="status-badge status-{{ $q->status === 'pending' ? 'yellow' : ($q->status === 'quoted' ? 'blue' : ($q->status === 'accepted' ? 'green' : 'gray')) }}">
                {{ ['pending'=>'รอดำเนินการ','quoted'=>'ส่งราคาแล้ว','accepted'=>'ตอบรับ','rejected'=>'ปฏิเสธ','closed'=>'ปิด'][$q->status] }}
            </span></td>
            <td style="font-size:12px;">{{ $q->created_at->format('d/m/Y') }}</td>
            <td><a href="{{ route('admin.quotes.show', $q) }}" class="btn btn-sm btn-light"><i class="bi bi-eye"></i> ดู</a></td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีคำขอ</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $quotes->links() }}</div>
</div>
@endsection
