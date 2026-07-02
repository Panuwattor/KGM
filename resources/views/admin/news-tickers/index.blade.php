@extends('layouts.admin')
@section('title', 'จัดการข่าวสำคัญ (News Ticker)')
@section('content')
<div class="page-header">
    <div class="page-title">จัดการข่าวสำคัญ (News Ticker)</div>
    <a href="{{ route('admin.news-tickers.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มข่าวสำคัญ</a>
</div>
<div class="table-wrap">
    <table>
        <thead>
            <tr>
                <th>ลำดับ</th>
                <th>ข้อความเฉยๆ</th>
                <th>ข้อความที่กดได้</th>
                <th>ลิงก์</th>
                <th>สถานะ</th>
                <th style="width:120px;"></th>
            </tr>
        </thead>
        <tbody>
        @forelse($tickers as $ticker)
        <tr>
            <td>{{ $ticker->order }}</td>
            <td>{{ Str::limit($ticker->plain_text ?? '-', 50) }}</td>
            <td>{{ $ticker->link_text ?? '-' }}</td>
            <td>
                @if($ticker->link_url)
                    <a href="{{ $ticker->link_url }}" target="_blank" style="color:var(--primary);text-decoration:underline;">{{ Str::limit($ticker->link_url, 30) }}</a>
                @else
                    -
                @endif
            </td>
            <td>
                <span class="status-badge {{ $ticker->is_active ? 'status-green' : 'status-red' }}">
                    {{ $ticker->is_active ? 'เปิด' : 'ปิด' }}
                </span>
            </td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.news-tickers.edit', $ticker) }}" class="btn btn-sm btn-light">
                    <i class="bi bi-pencil"></i>
                </a>
                <form method="POST" action="{{ route('admin.news-tickers.destroy', $ticker) }}" onsubmit="return confirm('ลบข่าวสำคัญนี้?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;padding:32px;color:#aaa;">
                ยังไม่มีข่าวสำคัญ
            </td>
        </tr>
        @endforelse
        </tbody>
    </table>
</div>

@if($tickers->hasPages())
<div style="margin-top:20px;">
    {{ $tickers->links() }}
</div>
@endif
@endsection
