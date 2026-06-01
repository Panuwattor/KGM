@extends('layouts.admin')
@section('title', 'สมาชิก: '.$user->name)
@section('content')
<div class="page-header">
    <div class="page-title">{{ $user->name }}</div>
    <a href="{{ route('admin.users.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
</div>
<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;">
    <div class="form-card">
        <h3><i class="bi bi-person"></i> ข้อมูลสมาชิก</h3>
        <div style="font-size:14px;line-height:2.2;">
            <div><strong>ชื่อ:</strong> {{ $user->name }}</div>
            <div><strong>อีเมล:</strong> {{ $user->email }}</div>
            <div><strong>เบอร์:</strong> {{ $user->phone ?? '-' }}</div>
            <div><strong>สมัครเมื่อ:</strong> {{ $user->created_at->format('d/m/Y') }}</div>
        </div>
        <div style="margin-top:16px;">
            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf @method('PUT')
                <div class="form-check"><input type="checkbox" name="is_active" value="1" {{ $user->is_active ? 'checked' : '' }}><label>บัญชีปกติ (ปิดเพื่อระงับ)</label></div>
                <button type="submit" class="btn btn-sm btn-primary" style="margin-top:10px;"><i class="bi bi-floppy"></i> บันทึก</button>
            </form>
        </div>
    </div>
    <div class="table-wrap">
        <div class="table-header"><h3>ประวัติการสั่งซื้อ</h3></div>
        <table>
            <thead><tr><th>เลขออเดอร์</th><th>ยอด</th><th>สถานะ</th><th>วันที่</th></tr></thead>
            <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>฿{{ number_format($order->total, 0) }}</td>
                <td><span class="status-badge status-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                <td style="font-size:12px;">{{ $order->created_at->format('d/m/Y') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:24px;color:#aaa;">ยังไม่มีออเดอร์</td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="padding:16px;">{{ $orders->links() }}</div>
    </div>
</div>
@endsection
