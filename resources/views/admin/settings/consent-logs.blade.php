@extends('layouts.admin')
@section('title', 'PDPA Consent Log')
@section('content')
<div class="page-header">
    <div class="page-title">PDPA Consent Log</div>
</div>
<div style="display:flex;gap:16px;margin-bottom:20px;">
    <a href="{{ route('admin.settings.index') }}" class="btn btn-light"><i class="bi bi-gear"></i> ตั้งค่า</a>
    <a href="{{ route('admin.settings.logs') }}" class="btn btn-light"><i class="bi bi-journal-text"></i> Audit Log</a>
    <a href="{{ route('admin.settings.consent-logs') }}" class="btn btn-primary"><i class="bi bi-shield-check"></i> PDPA Log</a>
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th>ผู้ใช้</th><th>Analytics</th><th>Marketing</th><th>Necessary</th><th>IP</th><th>เวลา</th></tr></thead>
        <tbody>
        @forelse($logs as $log)
        <tr>
            <td>{{ $log->user_id ? 'User #'.$log->user_id : 'ผู้เยี่ยมชม' }}</td>
            <td><span class="status-badge {{ $log->analytics_consent ? 'status-green' : 'status-red' }}">{{ $log->analytics_consent ? 'ยินยอม' : 'ปฏิเสธ' }}</span></td>
            <td><span class="status-badge {{ $log->marketing_consent ? 'status-green' : 'status-red' }}">{{ $log->marketing_consent ? 'ยินยอม' : 'ปฏิเสธ' }}</span></td>
            <td><span class="status-badge status-green">ยินยอม</span></td>
            <td style="font-size:12px;">{{ $log->ip_address }}</td>
            <td style="font-size:12px;color:#888;">{{ \Carbon\Carbon::parse($log->consented_at)->format('d/m/y H:i') }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีข้อมูล</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $logs->links() }}</div>
</div>
@endsection
