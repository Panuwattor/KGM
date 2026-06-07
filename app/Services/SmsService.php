<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SmsService
{
    private string $baseUrl = 'https://portal-otp.smsmkt.com/api';

    private function headers(): array
    {
        return [
            'api_key'    => config('services.smsmkt.api_key'),
            'secret_key' => config('services.smsmkt.secret_key'),
        ];
    }

    // ส่ง OTP — smsmkt generate เอง คืน token สำหรับ verify ทีหลัง
    public function sendOtp(string $phone): array|false
    {
        $phone = preg_replace('/\D/', '', $phone); // 08XXXXXXXX

        try {
            $response = Http::withHeaders($this->headers())
                ->post("{$this->baseUrl}/otp-send", [
                    'project_key' => config('services.smsmkt.project_key'),
                    'phone'       => $phone,
                ]);

            $body = $response->json();
            Log::info('smsmkt otp-send', ['phone' => $phone, 'status' => $response->status(), 'body' => $body]);

            if (!$response->successful() || empty($body['result']['token'])) {
                Log::warning('smsmkt send failed', ['body' => $body]);
                return false;
            }

            return $body['result']; // { token, ref_code }
        } catch (\Throwable $e) {
            Log::error('smsmkt sendOtp exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    // ยืนยัน OTP ที่ user กรอก
    public function verifyOtp(string $token, string $otpCode): bool
    {
        try {
            $response = Http::withHeaders($this->headers())
                ->post("{$this->baseUrl}/otp-validate", [
                    'token'    => $token,
                    'otp_code' => $otpCode,
                ]);

            $body = $response->json();
            Log::info('smsmkt otp-validate', ['body' => $body]);

            return $response->successful()
                && ($body['code'] ?? null) === '000'
                && ($body['result']['status'] ?? false) === true;
        } catch (\Throwable $e) {
            Log::error('smsmkt verifyOtp exception', ['message' => $e->getMessage()]);
            return false;
        }
    }
}
