<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CartService;
use App\Services\SmsService;
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

            $intended = session()->pull('url.intended', route('home'));
            if (str_contains($intended, '/admin')) {
                $intended = route('home');
            }
            return redirect($intended);
        }

        return back()->withErrors(['phone' => 'เบอร์โทรหรือรหัสผ่านไม่ถูกต้อง'])->onlyInput('phone');
    }

    public function sendLoginOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string|max:20']);

        $phone = preg_replace('/\D/', '', $request->phone);

        $customer = Customer::where('phone', $phone)->first();
        if (!$customer) {
            return response()->json(['ok' => false, 'message' => 'ไม่พบเบอร์โทรนี้ในระบบ']);
        }
        if (!$customer->isActive()) {
            return response()->json(['ok' => false, 'message' => 'บัญชีนี้ถูกระงับการใช้งาน']);
        }

        $lastSent = session('login_otp_last_sent');
        if ($lastSent && (time() - $lastSent) < 60) {
            $wait = 60 - (time() - $lastSent);
            return response()->json(['ok' => false, 'message' => "กรุณารอ {$wait} วินาทีก่อนส่งอีกครั้ง"]);
        }

        $result = app(SmsService::class)->sendOtp($phone);
        if (!$result) {
            return response()->json(['ok' => false, 'message' => 'ไม่สามารถส่ง OTP ได้ กรุณาลองใหม่อีกครั้ง']);
        }

        session([
            'login_otp' => [
                'token'      => $result['token'],
                'phone'      => $phone,
                'expires_at' => time() + 300,
            ],
            'login_otp_last_sent' => time(),
        ]);

        return response()->json(['ok' => true, 'message' => "ส่ง OTP ไปยัง {$phone} แล้ว"]);
    }

    public function loginWithOtp(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|max:20',
            'otp'   => 'required|digits:6',
        ], [
            'otp.required' => 'กรุณากรอกรหัส OTP',
            'otp.digits'   => 'รหัส OTP ต้องเป็นตัวเลข 6 หลัก',
        ]);

        $phone   = preg_replace('/\D/', '', $request->phone);
        $otpData = session('login_otp');

        if (!$otpData || $otpData['phone'] !== $phone || $otpData['expires_at'] < time()) {
            return back()->withErrors(['otp' => 'กรุณาขอ OTP ใหม่ (หมดอายุหรือเบอร์ไม่ตรง)'])->withInput();
        }

        $verified = app(SmsService::class)->verifyOtp($otpData['token'], $request->otp);
        if (!$verified) {
            return back()->withErrors(['otp' => 'รหัส OTP ไม่ถูกต้อง กรุณาตรวจสอบและลองอีกครั้ง'])->withInput();
        }

        session()->forget(['login_otp', 'login_otp_last_sent']);

        $customer = Customer::where('phone', $phone)->firstOrFail();
        Auth::guard('customer')->login($customer);
        app(CartService::class)->mergeSessionCart();

        $intended = session()->pull('url.intended', route('home'));
        if (str_contains($intended, '/admin')) {
            $intended = route('home');
        }
        return redirect($intended);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        $request->session()->regenerateToken();
        return redirect()->route('home');
    }
}
