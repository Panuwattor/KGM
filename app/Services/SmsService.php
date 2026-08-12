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

    /**
     * ส่ง SMS ข้อความอิสระ (คนละ endpoint กับ OTP — ตัวนี้กำหนดข้อความเองได้)
     * ใช้เครดิต Broadcast ของบัญชี smsmkt
     *
     * @param  string  $phone    เบอร์ปลายทาง 08XXXXXXXX (หลายเบอร์คั่นด้วย ,)
     * @param  string  $message  ข้อความ UTF-8
     * @return bool    ส่งสำเร็จหรือไม่ (ไม่ throw — ผู้เรียกไม่ต้องดักเอง)
     */
    public function sendMessage(string $phone, string $message): bool
    {
        if (! config('services.smsmkt.enabled')) {
            Log::info('smsmkt send-message ปิดใช้งาน (SMSMKT_ENABLED=false)', ['phone' => $phone]);
            return false;
        }

        $phone = $this->normalizePhone($phone);
        if ($phone === '') {
            Log::warning('smsmkt send-message: เบอร์ไม่ถูกต้อง', ['phone' => $phone]);
            return false;
        }

        try {
            $payload = [
                'message' => $message,
                'phone'   => $phone,
                'sender'  => config('services.smsmkt.sender'),
            ];
            // project_id เป็น optional — ใส่เฉพาะเมื่อตั้งค่าไว้
            if ($projectId = config('services.smsmkt.project_id')) {
                $payload['project_id'] = $projectId;
            }

            $response = Http::withHeaders($this->headers())
                ->timeout(15)
                ->post("{$this->baseUrl}/send-message", $payload);

            $body = $response->json();
            $ok   = $response->successful() && ($body['code'] ?? null) === '000';

            Log::info('smsmkt send-message', [
                'phone'   => $phone,
                'status'  => $response->status(),
                'code'    => $body['code'] ?? null,
                'credit'  => $body['result']['usedcredit'] ?? null,
                'success' => $ok,
            ]);

            if (! $ok) {
                Log::warning('smsmkt send-message ไม่สำเร็จ', ['body' => $body]);
            }

            return $ok;
        } catch (\Throwable $e) {
            Log::error('smsmkt sendMessage exception', ['message' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * ส่งลิงก์ติดตามออเดอร์ให้ลูกค้า (ใช้กับลูกค้าที่สั่งซื้อโดยไม่ได้สมัครสมาชิก)
     *
     * ข้อความไทยใช้ 2 เครดิต/ครั้ง (SMS ไทยได้ 67 ตัว/เครดิต ส่วนอังกฤษ 153 ตัว)
     * ตัวลิงก์ยาว ~96 ตัวอักษรและตัดสั้นไม่ได้ จึงเลี่ยงคำฟุ่มเฟือยให้มากที่สุด
     */
    public function sendOrderLink(\App\Models\Order $order): bool
    {
        $link = $order->guest_url;
        if (! $link || ! $order->ship_phone) return false;

        // ไม่ใส่เลขออเดอร์ในข้อความไทย เพราะมีอยู่ในลิงก์และในหน้าเว็บอยู่แล้ว
        // ใส่เพิ่มจะทำให้เกิน 134 ตัว = เสีย 3 เครดิตแทนที่จะเป็น 2
        $message = config('services.smsmkt.thai_message')
            ? "KGM สั่งซื้อสำเร็จ\nดูออเดอร์/แนบสลิป\n{$link}"
            : "KGM Order {$order->order_number}\nView order / upload slip:\n{$link}";

        return $this->sendMessage($order->ship_phone, $message);
    }

    /** แปลงเบอร์ให้เหลือเฉพาะตัวเลขในรูปแบบ 08XXXXXXXX ที่ smsmkt รับ */
    private function normalizePhone(string $phone): string
    {
        $digits = preg_replace('/\D/', '', $phone);
        // +66 81 234 5678 → 0812345678
        if (str_starts_with($digits, '66') && strlen($digits) >= 11) {
            $digits = '0' . substr($digits, 2);
        }
        return preg_match('/^0\d{8,9}$/', $digits) ? $digits : '';
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
