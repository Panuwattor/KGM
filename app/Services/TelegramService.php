<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    protected string $token;
    protected string $chatId;

    public function __construct()
    {
        $this->token = config('services.telegram.bot_token', '');
        $this->chatId = config('services.telegram.chat_id', '');
    }

    public function send(string $message): bool
    {
        if (empty($this->token) || empty($this->chatId)) return false;

        try {
            $response = Http::post("https://api.telegram.org/bot{$this->token}/sendMessage", [
                'chat_id' => $this->chatId,
                'text' => $message,
                'parse_mode' => 'HTML',
            ]);
            return $response->successful();
        } catch (\Exception $e) {
            \Log::error('Telegram notification failed: ' . $e->getMessage());
            return false;
        }
    }

    public function notifyNewOrder(\App\Models\Order $order): void
    {
        $message = "🛒 <b>ออเดอร์ใหม่!</b>\n"
            . "📋 เลขที่: <b>{$order->order_number}</b>\n"
            . "👤 ชื่อ: {$order->ship_name}\n"
            . "💰 ยอดรวม: ฿" . number_format($order->total, 2) . "\n"
            . "🕐 เวลา: " . now()->setTimezone('Asia/Bangkok')->format('d/m/Y H:i') . "\n"
            . "🔗 <a href=\"" . config('app.url') . "/admin/orders/{$order->id}\">ดูออเดอร์</a>";
        $this->send($message);
    }

    public function notifyPaymentSlip(\App\Models\Order $order): void
    {
        $message = "💳 <b>ลูกค้าอัปโหลดสลิปใหม่!</b>\n"
            . "📋 เลขที่: <b>{$order->order_number}</b>\n"
            . "👤 ชื่อ: {$order->ship_name}\n"
            . "💰 ยอดรวม: ฿" . number_format($order->total, 2) . "\n"
            . "🔗 <a href=\"" . config('app.url') . "/admin/orders/{$order->id}\">ตรวจสอบสลิป</a>";
        $this->send($message);
    }
}
