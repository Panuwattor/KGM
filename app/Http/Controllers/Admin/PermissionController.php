<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Permission::withCount('roles');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $permissions = $query->orderBy('name')->paginate(30)->withQueryString();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name',
        ]);

        Permission::create(['name' => $request->name, 'guard_name' => 'web']);

        return redirect()->route('admin.permissions.index')->with('success', 'เพิ่มสิทธิ์แล้ว');
    }

    public function destroy(Permission $permission)
    {
        if ($permission->roles()->count() > 0) {
            return back()->with('error', 'ไม่สามารถลบสิทธิ์ที่ถูกใช้งานในตำแหน่งอยู่ได้');
        }
        $permission->delete();
        return redirect()->route('admin.permissions.index')->with('success', 'ลบสิทธิ์แล้ว');
    }
}
