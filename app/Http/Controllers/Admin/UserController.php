<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');
        if ($request->filled('search')) {
            $query->where(fn($q) => $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%'));
        }
        $users = $query->withCount('orders')->latest()->paginate(20)->withQueryString();
        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('orders', 'addresses');
        $orders = $user->orders()->latest()->paginate(10);
        return view('admin.users.show', compact('user', 'orders'));
    }

    public function edit(User $user) { return view('admin.users.edit', compact('user')); }

    public function update(Request $request, User $user)
    {
        $request->validate(['is_active' => 'boolean', 'role' => 'in:customer,staff,admin']);
        $user->update($request->only('is_active', 'role'));
        return back()->with('success', 'อัปเดตสมาชิกแล้ว');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'ระงับสมาชิกแล้ว');
    }
}
