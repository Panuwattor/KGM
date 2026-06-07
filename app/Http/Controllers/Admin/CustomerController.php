<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::query();
        if ($request->filled('search')) {
            $query->where(fn($q) => $q
                ->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%')
                ->orWhere('phone', 'like', '%' . $request->search . '%'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        $customers = $query->withCount('orders')->latest()->paginate(20)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function show(Customer $customer)
    {
        $customer->load('addresses');
        $orders = $customer->orders()->latest()->paginate(10);
        return view('admin.customers.show', compact('customer', 'orders'));
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['nullable', 'email', Rule::unique('customers', 'email')->ignore($customer->id)],
            'phone'    => ['required', Rule::unique('customers', 'phone')->ignore($customer->id)],
            'address'  => 'nullable|string|max:1000',
            'status'   => 'required|in:active,inactive',
            'password' => 'nullable|min:8|confirmed',
        ]);

        $data = $request->only('name', 'email', 'phone', 'address', 'status');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $customer->update($data);
        return redirect()->route('admin.customers.show', $customer)->with('success', 'อัปเดตข้อมูลลูกค้าแล้ว');
    }

    public function destroy(Customer $customer)
    {
        $customer->update(['status' => 'inactive']);
        return redirect()->route('admin.customers.index')->with('success', 'ระงับบัญชีลูกค้าแล้ว');
    }

    // ---- Address management ----

    public function storeAddress(Request $request, Customer $customer)
    {
        $request->validate([
            'label'          => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line1'  => 'required|string|max:255',
            'address_line2'  => 'nullable|string|max:255',
            'district'       => 'required|string|max:100',
            'amphoe'         => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'postcode'       => 'required|string|max:10',
            'is_default'     => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $customer->addresses()->create($request->only(
            'label', 'recipient_name', 'phone',
            'address_line1', 'address_line2',
            'district', 'amphoe', 'province', 'postcode'
        ) + ['is_default' => $request->boolean('is_default')]);

        return back()->with('success', 'เพิ่มที่อยู่แล้ว');
    }

    public function updateAddress(Request $request, Customer $customer, Address $address)
    {
        $request->validate([
            'label'          => 'nullable|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line1'  => 'required|string|max:255',
            'address_line2'  => 'nullable|string|max:255',
            'district'       => 'required|string|max:100',
            'amphoe'         => 'required|string|max:100',
            'province'       => 'required|string|max:100',
            'postcode'       => 'required|string|max:10',
            'is_default'     => 'boolean',
        ]);

        if ($request->boolean('is_default')) {
            $customer->addresses()->where('id', '!=', $address->id)->update(['is_default' => false]);
        }

        $address->update($request->only(
            'label', 'recipient_name', 'phone',
            'address_line1', 'address_line2',
            'district', 'amphoe', 'province', 'postcode'
        ) + ['is_default' => $request->boolean('is_default')]);

        return back()->with('success', 'อัปเดตที่อยู่แล้ว');
    }

    public function destroyAddress(Customer $customer, Address $address)
    {
        $address->delete();
        return back()->with('success', 'ลบที่อยู่แล้ว');
    }
}
