<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;

class AutoDeliverOrders extends Command
{
    protected $signature = 'orders:auto-deliver {--days=20 : จำนวนวันหลังจัดส่งที่จะถือว่าส่งถึงแล้ว}';
    protected $description = 'อัปเดตออเดอร์ที่จัดส่งเกิน X วันให้เป็น "ส่งถึงแล้ว" อัตโนมัติ';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $orders = Order::where('status', 'shipped')
            ->whereNotNull('shipped_at')
            ->where('shipped_at', '<=', $cutoff)
            ->get();

        foreach ($orders as $order) {
            $order->update([
                'status'       => 'delivered',
                'delivered_at' => now(),
                'picked_up_at' => $order->is_pickup ? now() : $order->picked_up_at,
            ]);
            $this->line("อัปเดต {$order->order_number} เป็นส่งถึงแล้ว (จัดส่งเมื่อ {$order->shipped_at->format('d/m/Y')})");
        }

        $this->info("เสร็จสิ้น: อัปเดต {$orders->count()} ออเดอร์ (เกิน {$days} วัน)");
        return self::SUCCESS;
    }
}
