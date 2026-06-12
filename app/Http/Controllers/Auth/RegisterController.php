<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\CartService;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['phone' => 'required|string|max:20']);

        $phone = preg_replace('/\D/', '', $request->phone);

        if (Customer::where('phone', $phone)->exists()) {
            return response()->json(['ok' => false, 'message' => 'เบอร์โทรนี้ถูกใช้งานแล้ว']);
        }

        // Rate limit 60 วินาที
        $lastSent = session('otp_last_sent');
        if ($lastSent && (time() - $lastSent) < 60) {
            $wait = 60 - (time() - $lastSent);
            return response()->json(['ok' => false, 'message' => "กรุณารอ {$wait} วินาทีก่อนส่งอีกครั้ง"]);
        }

        $result = app(SmsService::class)->sendOtp($phone);

        if (!$result) {
            return response()->json(['ok' => false, 'message' => 'ไม่สามารถส่ง OTP ได้ กรุณาลองใหม่อีกครั้ง']);
        }

        session([
            'register_otp'  => [
                'token'      => $result['token'],
                'phone'      => $phone,
                'expires_at' => time() + 300,
            ],
            'otp_last_sent' => time(),
        ]);

        return response()->json(['ok' => true, 'message' => "ส่ง OTP ไปยัง {$phone} แล้ว"]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'nullable|email|unique:customers',
            'phone'    => 'required|string|max:20|unique:customers',
            'password' => 'required|string|min:8|confirmed',
            'otp'      => 'required|digits:6',
        ], [
            'otp.required' => 'กรุณากรอกรหัส OTP',
            'otp.digits'   => 'รหัส OTP ต้องเป็นตัวเลข 6 หลัก',
        ]);

        $phone   = preg_replace('/\D/', '', $request->phone);
        $otpData = session('register_otp');

        if (!$otpData || $otpData['phone'] !== $phone || $otpData['expires_at'] < time()) {
            return back()
                ->withErrors(['otp' => 'กรุณาขอ OTP ใหม่ (หมดอายุหรือเบอร์ไม่ตรง)'])
                ->withInput();
        }

        $verified = app(SmsService::class)->verifyOtp($otpData['token'], $request->otp);

        if (!$verified) {
            return back()
                ->withErrors(['otp' => 'รหัส OTP ไม่ถูกต้อง กรุณาตรวจสอบและลองอีกครั้ง'])
                ->withInput();
        }

        session()->forget(['register_otp', 'otp_last_sent']);

        $customer = Customer::create([
            'name'     => $request->name,
            'email'    => $request->email ?: null,
            'phone'    => $phone,
            'password' => Hash::make($request->password),
            'status'   => 'active',
        ]);

        $oldSessionId = session()->getId();
        Auth::guard('customer')->login($customer);
        session()->regenerate();
        app(CartService::class)->mergeSessionCart($oldSessionId);

        return redirect()->route('home')->with('success', 'สมัครสมาชิกสำเร็จ! ยินดีต้อนรับครับ');
    }
}
