<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($request->filled('search')) {
            $query->where(fn($q) => $q
                ->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'));
        }

        $users = $query->latest()->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::whereNotIn('name', ['customer'])->orderBy('name')->get();
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  => 'required|string|max:255',
            'email'                 => 'required|email|unique:users,email',
            'password'              => 'required|min:8|confirmed',
            'role'                  => 'required|exists:roles,name',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'is_active' => true,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'เพิ่มผู้ใช้งานระบบแล้ว');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::whereNotIn('name', ['customer'])->orderBy('name')->get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        if ($user->id === auth()->id() && $request->role !== 'executive') {
            return back()->with('error', 'ไม่สามารถเปลี่ยนตำแหน่งของตัวเองออกจากผู้บริหารได้');
        }

        $user->update([
            'is_active' => $request->boolean('is_active'),
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'อัปเดตข้อมูลผู้ใช้งานแล้ว');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'ไม่สามารถระงับบัญชีของตัวเองได้');
        }
        if ($user->id === 1) {
            return back()->with('error', 'ไม่สามารถระงับบัญชีผู้ดูแลระบบหลักได้');
        }

        // Soft delete - ไม่ลบจริง แค่ทำเครื่องหมายว่าถูกลบ
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'ระงับบัญชีผู้ใช้งานแล้ว (สามารถกู้คืนได้)');
    }
}
