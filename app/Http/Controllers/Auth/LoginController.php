<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'phone'    => 'required|string',
            'password' => 'required',
        ]);

        if (Auth::guard('customer')->attempt($credentials, $request->boolean('remember'))) {
            if (!Auth::guard('customer')->user()->isActive()) {
                Auth::guard('customer')->logout();
                return back()->withErrors(['phone' => 'บัญชีนี้ถูกระงับการใช้งาน'])->onlyInput('phone');
            }

            $request->session()->regenerate();
            app(CartService::class)->mergeSessionCart();
            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['phone' => 'เบอร์โทรหรือรหัสผ่านไม่ถูกต้อง'])->onlyInput('phone');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
