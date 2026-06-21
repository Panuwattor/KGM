@extends('layouts.admin')
@section('title', 'ลูกค้า: '.$customer->name)
@section('content')
<div class="page-header">
    <div>
        <div class="page-title">{{ $customer->name }}</div>
        <div class="page-subtitle">ข้อมูลและประวัติการสั่งซื้อของลูกค้า</div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> แก้ไขข้อมูล
        </a>
        <form method="POST" action="{{ route('admin.customers.update', $customer) }}">
            @csrf @method('PUT')
            <input type="hidden" name="status" value="{{ $customer->status === 'active' ? 'inactive' : 'active' }}">
            <button class="btn {{ $customer->status === 'active' ? 'btn-danger' : 'btn-primary' }}"
                onclick="return confirm('{{ $customer->status === 'active' ? 'ระงับ' : 'เปิด' }}บัญชีนี้?')">
                <i class="bi bi-{{ $customer->status === 'active' ? 'ban' : 'check-circle' }}"></i>
                {{ $customer->status === 'active' ? 'ระงับบัญชี' : 'เปิดบัญชี' }}
            </button>
        </form>
        <a href="{{ route('admin.customers.index') }}" class="btn btn-light"><i class="bi bi-arrow-left"></i> กลับ</a>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:24px;align-items:start;">
    {{-- ข้อมูลลูกค้า --}}
    <div>
        <div class="form-card">
            <h3><i class="bi bi-person"></i> ข้อมูลลูกค้า</h3>
            <div style="font-size:14px;line-height:2.4;">
                <div><strong>ชื่อ:</strong> {{ $customer->name }}</div>
                <div><strong>อีเมล:</strong> {{ $customer->email ?? '-' }}</div>
                <div><strong>เบอร์โทร:</strong> {{ $customer->phone }}</div>
                @if($customer->address)
                <div><strong>ที่อยู่:</strong> {{ $customer->address }}</div>
                @endif
                <div><strong>สมัครเมื่อ:</strong> {{ $customer->created_at->format('d/m/Y H:i') }}</div>
                <div>
                    <strong>สถานะ:</strong>
                    <span class="status-badge {{ $customer->status === 'active' ? 'status-green' : 'status-red' }}">
                        {{ $customer->status === 'active' ? 'ปกติ' : 'ระงับ' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ที่อยู่จัดส่ง --}}
        <div class="form-card">
            <h3 style="display:flex;align-items:center;justify-content:space-between;">
                <span><i class="bi bi-geo-alt"></i> ที่อยู่จัดส่ง ({{ $customer->addresses->count() }})</span>
                <button type="button" class="btn btn-sm btn-primary" onclick="openAddAddress()">
                    <i class="bi bi-plus"></i> เพิ่ม
                </button>
            </h3>

            @forelse($customer->addresses as $addr)
            <div style="font-size:13px;padding:12px 0;border-bottom:1px solid #f5f5f5;line-height:1.9;{{ $loop->last ? 'border:none;padding-bottom:0' : '' }}">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:4px;">
                    <div style="display:flex;gap:5px;">
                        @if($addr->is_default)<span class="status-badge status-blue">หลัก</span>@endif
                        @if($addr->label)<span class="status-badge status-gray">{{ $addr->label }}</span>@endif
                    </div>
                    <div style="display:flex;gap:4px;">
                        <button type="button" class="btn btn-sm btn-light"
                            onclick="openEditAddress({{ $addr->id }}, @js($addr->label), @js($addr->recipient_name), @js($addr->phone), @js($addr->address_line1), @js($addr->address_line2), @js($addr->district), @js($addr->amphoe), @js($addr->province), @js($addr->postcode), {{ $addr->is_default ? 'true' : 'false' }})">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" action="{{ route('admin.customers.addresses.destroy', [$customer, $addr]) }}"
                            onsubmit="return confirm('ลบที่อยู่นี้?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
                        </form>
                    </div>
                </div>
                <div style="font-weight:600;">{{ $addr->recipient_name }} | {{ $addr->phone }}</div>
                <div style="color:#555;">
                    {{ $addr->address_line1 }}
                    @if($addr->address_line2) {{ $addr->address_line2 }}@endif<br>
                    ต.{{ $addr->district }} อ.{{ $addr->amphoe }} จ.{{ $addr->province }} {{ $addr->postcode }}
                </div>
            </div>
            @empty
            <div style="text-align:center;padding:20px 0;color:#aaa;font-size:13px;">ยังไม่มีที่อยู่จัดส่ง</div>
            @endforelse
        </div>
    </div>

    {{-- ประวัติออเดอร์ --}}
    <div class="table-wrap">
        <div class="table-header">
            <h3>ประวัติการสั่งซื้อ</h3>
        </div>
        <table>
            <thead>
                <tr><th>เลขออเดอร์</th><th>ยอดรวม</th><th>สถานะ</th><th>วันที่</th></tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
            <tr>
                <td><a href="{{ route('admin.orders.show', $order) }}" style="font-weight:700;color:var(--g600);">{{ $order->order_number }}</a></td>
                <td>฿{{ number_format($order->total, 0) }}</td>
                <td><span class="status-badge status-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                <td style="font-size:12px;color:#888;">{{ $order->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            @empty
            <tr><td colspan="4" style="text-align:center;padding:32px;color:#aaa;">ยังไม่มีออเดอร์</td></tr>
            @endforelse
            </tbody>
        </table>
        <div style="padding:16px;">{{ $orders->links() }}</div>
    </div>
</div>

{{-- Modal (จะถูก JS ย้ายไปที่ body) --}}
<div id="addr-modal" style="display:none;">
    <div id="addr-backdrop" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9998;" onclick="closeAddressModal()"></div>
    <div style="position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:9999;background:white;border-radius:20px;width:90%;max-width:560px;padding:28px;max-height:90vh;overflow-y:auto;box-shadow:0 20px 60px rgba(0,0,0,0.25);">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
            <h3 id="addr-modal-title" style="font-size:16px;font-weight:700;color:#1a3a2a;margin:0;"></h3>
            <button type="button" onclick="closeAddressModal()" style="background:none;border:none;font-size:20px;cursor:pointer;color:#888;line-height:1;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <form id="addr-form" method="POST">
            @csrf
            <input type="hidden" name="_method" id="addr-method" value="POST">
            <div class="form-grid">
                <div class="form-group">
                    <label class="form-label">ชื่อที่อยู่ (เช่น บ้าน, ที่ทำงาน)</label>
                    <input type="text" name="label" id="addr-label" class="form-control" placeholder="บ้าน">
                </div>
                <div class="form-group">
                    <label class="form-label">ชื่อผู้รับ <span style="color:#e74c3c;">*</span></label>
                    <input type="text" name="recipient_name" id="addr-recipient_name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">เบอร์โทร <span style="color:#e74c3c;">*</span></label>
                    <input type="text" name="phone" id="addr-phone" class="form-control" required>
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">ที่อยู่บรรทัด 1 <span style="color:#e74c3c;">*</span></label>
                    <input type="text" name="address_line1" id="addr-address_line1" class="form-control" required>
                </div>
                <div class="form-group col-span-2">
                    <label class="form-label">ที่อยู่บรรทัด 2</label>
                    <input type="text" name="address_line2" id="addr-address_line2" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">จังหวัด <span style="color:#e74c3c;">*</span></label>
                    <select name="province" id="addr-province" class="form-control" data-kgm-enhanced="1" required></select>
                </div>
                <div class="form-group">
                    <label class="form-label">อำเภอ/เขต <span style="color:#e74c3c;">*</span></label>
                    <select name="amphoe" id="addr-amphoe" class="form-control" data-kgm-enhanced="1" required></select>
                </div>
                <div class="form-group">
                    <label class="form-label">ตำบล/แขวง <span style="color:#e74c3c;">*</span></label>
                    <select name="district" id="addr-district" class="form-control" data-kgm-enhanced="1" required></select>
                </div>
                <div class="form-group">
                    <label class="form-label">รหัสไปรษณีย์ <span style="color:#e74c3c;">*</span></label>
                    <input type="text" name="postcode" id="addr-postcode" class="form-control" required>
                </div>
            </div>
            <div class="form-check" style="margin:4px 0 20px;">
                <input type="checkbox" name="is_default" id="addr-is_default" value="1">
                <label for="addr-is_default">ตั้งเป็นที่อยู่หลัก</label>
            </div>
            <div style="display:flex;gap:8px;">
                <button type="submit" class="btn btn-primary"><i class="bi bi-floppy"></i> บันทึก</button>
                <button type="button" class="btn btn-light" onclick="closeAddressModal()">ยกเลิก</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
@include('partials.location-assets')
<script>
const BASE_URL = '{{ url("admin/customers/" . $customer->id . "/addresses") }}';
const STORE_URL = '{{ route("admin.customers.addresses.store", $customer) }}';

// dropdown จังหวัด/อำเภอ/ตำบล แบบ cascade
let addrLoc = null;

// ย้าย modal ออกจาก layout ไปที่ body เพื่อหลีกเลี่ยง stacking context ทั้งหมด
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('addr-modal');
    document.body.appendChild(modal);

    KGMLocation({
        select2:        true,
        dropdownParent: '#addr-modal',
        province:       document.getElementById('addr-province'),
        amphoe:         document.getElementById('addr-amphoe'),
        district:       document.getElementById('addr-district'),
        zip:            document.getElementById('addr-postcode'),
    }).then(api => { addrLoc = api; });
});

function openAddressModal() {
    document.getElementById('addr-modal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function closeAddressModal() {
    document.getElementById('addr-modal').style.display = 'none';
    document.body.style.overflow = '';
}

function openAddAddress() {
    document.getElementById('addr-modal-title').textContent = 'เพิ่มที่อยู่ใหม่';
    document.getElementById('addr-form').action = STORE_URL;
    document.getElementById('addr-method').value = 'POST';
    ['label','recipient_name','phone','address_line1','address_line2','postcode'].forEach(f => {
        document.getElementById('addr-' + f).value = '';
    });
    if (addrLoc) addrLoc.set({});
    document.getElementById('addr-is_default').checked = false;
    openAddressModal();
}

function openEditAddress(id, label, recipient_name, phone, address_line1, address_line2, district, amphoe, province, postcode, is_default) {
    document.getElementById('addr-modal-title').textContent = 'แก้ไขที่อยู่';
    document.getElementById('addr-form').action = BASE_URL + '/' + id;
    document.getElementById('addr-method').value = 'PUT';
    document.getElementById('addr-label').value = label ?? '';
    document.getElementById('addr-recipient_name').value = recipient_name;
    document.getElementById('addr-phone').value = phone;
    document.getElementById('addr-address_line1').value = address_line1;
    document.getElementById('addr-address_line2').value = address_line2 ?? '';
    document.getElementById('addr-postcode').value = postcode;
    if (addrLoc) addrLoc.set({ province: province, amphoe: amphoe, district: district, zip: postcode });
    document.getElementById('addr-is_default').checked = is_default;
    openAddressModal();
}
</script>
@endpush
@endsection
