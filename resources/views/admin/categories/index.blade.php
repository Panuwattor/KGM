@extends('layouts.admin')
@section('title', 'หมวดหมู่สินค้า')
@section('content')
<div class="page-header">
    <div class="page-title">หมวดหมู่สินค้า</div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> เพิ่มหมวดหมู่</a>
</div>
<div style="font-size:13px;color:#888;margin-bottom:12px;">
    <i class="bi bi-info-circle"></i> ลากรายการโดยจับที่ไอคอน <i class="bi bi-grip-vertical"></i> เพื่อจัดลำดับการแสดงผลที่หน้าแรก (ลำดับมีผลเฉพาะหมวดหมู่หลักที่แสดงบนหน้าแรก)
</div>
<div class="table-wrap">
    <table>
        <thead><tr><th width="30"></th><th width="60">รูป</th><th>ชื่อหมวดหมู่</th><th>หมวดหมู่หลัก</th><th>สินค้า</th><th>สถานะ</th><th></th></tr></thead>
        <tbody id="category-sortable">
        @forelse($categories as $cat)
        <tr data-id="{{ $cat->id }}">
            <td style="cursor:grab;color:#ccc;text-align:center;" class="drag-handle"><i class="bi bi-grip-vertical"></i></td>
            <td>
                @if($cat->image)
                    <img src="{{ media_url($cat->image) }}" style="width:48px;height:48px;object-fit:cover;border-radius:10px;">
                @else
                    <div style="width:48px;height:48px;background:#f0f4f0;border-radius:10px;display:flex;align-items:center;justify-content:center;color:#ccc;">
                        <i class="bi bi-tag"></i>
                    </div>
                @endif
            </td>
            <td><strong>{{ $cat->parent_id ? '— ' : '' }}{{ $cat->name }}</strong></td>
            <td>{{ $cat->parent?->name ?? '-' }}</td>
            <td>{{ $cat->products_count }}</td>
            <td><span class="status-badge {{ $cat->is_active ? 'status-green' : 'status-red' }}">{{ $cat->is_active ? 'เปิด' : 'ปิด' }}</span></td>
            <td style="display:flex;gap:6px;">
                <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-light"><i class="bi bi-pencil"></i></a>
                <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('ลบหมวดหมู่นี้?')">@csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button></form>
            </td>
        </tr>
        @empty
        <tr><td colspan="7" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีหมวดหมู่</td></tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.2/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const el = document.getElementById('category-sortable');
    if (!el) return;

    Sortable.create(el, {
        handle: '.drag-handle',
        animation: 150,
        onEnd: function () {
            const ids = Array.from(el.querySelectorAll('tr[data-id]')).map(tr => tr.dataset.id);
            fetch('{{ route("admin.categories.reorder") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ ids }),
            });
        },
    });
});
</script>
@endpush
