@extends('layouts.admin')
@section('title', 'Audit Log')
@section('content')
<div class="page-header">
    <div class="page-title">Audit Log (บันทึกการทำงานของแอดมิน)</div>
</div>
<div style="display:flex;gap:16px;margin-bottom:20px;">
    <a href="{{ route('admin.settings.index') }}" class="btn btn-light"><i class="bi bi-gear"></i> ตั้งค่า</a>
    <a href="{{ route('admin.settings.logs') }}" class="btn btn-primary"><i class="bi bi-journal-text"></i> Audit Log</a>
    <a href="{{ route('admin.settings.consent-logs') }}" class="btn btn-light"><i class="bi bi-shield-check"></i> PDPA Log</a>
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th>แอดมิน</th><th>การกระทำ</th><th>รายละเอียด</th><th>Model</th><th>IP</th><th>เวลา</th></tr></thead>
        <tbody>
        @forelse($logs as $log)
        <tr>
            <td><span style="font-weight:600;">{{ $log->admin_name ?? 'System' }}</span></td>
            <td><span class="status-badge status-{{ $log->action === 'deleted' ? 'red' : ($log->action === 'created' ? 'green' : 'blue') }}">{{ $log->action }}</span></td>
            <td style="max-width:300px;font-size:13px;">{{ $log->description }}</td>
            <td style="font-size:12px;color:#888;">{{ $log->model_type }} #{{ $log->model_id }}</td>
            <td style="font-size:12px;color:#888;">{{ $log->ip_address }}</td>
            <td style="font-size:12px;color:#888;">{{ $log->created_at->format('d/m/y H:i') }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มี Log</td></tr>
        @endforelse
        </tbody>
    </table>
    <div style="padding:16px;">{{ $logs->links() }}</div>
</div>
@endsection
