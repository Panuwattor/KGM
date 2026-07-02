<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::withCount('users');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $roles = $query->orderBy('name')->paginate(20)->withQueryString();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(fn($p) => explode(' ', $p->name)[1] ?? 'อื่นๆ');
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'web']);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('success', 'เพิ่มตำแหน่งแล้ว');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::orderBy('name')->get()->groupBy(fn($p) => explode(' ', $p->name)[1] ?? 'อื่นๆ');
        $rolePermissions = $role->permissions->pluck('name')->toArray();
        return view('admin.roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->input('permissions', []));

        return redirect()->route('admin.roles.index')->with('success', 'อัปเดตตำแหน่งแล้ว');
    }

    public function destroy(Role $role)
    {
        if (in_array($role->name, ['executive', 'sales', 'accounting', 'marketing', 'staff', 'customer'])) {
            return back()->with('error', 'ไม่สามารถลบตำแหน่งหลักของระบบได้');
        }
        if ($role->users()->count() > 0) {
            return back()->with('error', 'ไม่สามารถลบตำแหน่งที่มีผู้ใช้งานอยู่ได้');
        }
        $role->delete();
        return redirect()->route('admin.roles.index')->with('success', 'ลบตำแหน่งแล้ว');
    }
}
