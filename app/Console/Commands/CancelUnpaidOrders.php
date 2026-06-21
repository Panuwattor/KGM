<?php

namespace App\Console\Commands;

use App\Models\AdminLog;
use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CancelUnpaidOrders extends Command
{
    protected $signature = 'orders:cancel-unpaid {--days=2 : จำนวนวันที่รอชำระเงินก่อนจะยกเลิกอัตโนมัติ}';
    protected $description = 'ยกเลิกออเดอร์ที่ "รอชำระเงิน" เกิน X วัน และคืนสินค้าเข้าสต๊อก';

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = now()->subDays($days);

        $orders = Order::where('status', 'pending_payment')
            ->where('created_at', '<=', $cutoff)
            ->get();

        if ($orders->isEmpty()) {
            $this->info("ไม่มีออเดอร์ที่รอชำระเงินเกิน {$days} วัน");
            return self::SUCCESS;
        }

        foreach ($orders as $order) {
            DB::transaction(function () use ($order) {
                $this->restoreStock($order);
                $order->update([
                    'status'           => 'cancelled',
                    'rejection_reason' => "ยกเลิกอัตโนมัติ: ไม่ชำระเงินภายในเวลาที่กำหนด",
                ]);
                AdminLog::record('updated', "ยกเลิกออเดอร์อัตโนมัติ {$order->order_number} (รอชำระเงินเกินกำหนด)", $order);
            });
            $this->line("ยกเลิก {$order->order_number} (สั่งซื้อเมื่อ {$order->created_at->format('d/m/Y H:i')})");
        }

        $this->info("เสร็จสิ้น: ยกเลิก {$orders->count()} ออเดอร์ และคืนสต๊อกแล้ว (เกิน {$days} วัน)");
        return self::SUCCESS;
    }

    private function restoreStock(Order $order): void
    {
        $order->loadMissing('items.product');
        foreach ($order->items as $item) {
            if ($item->product && $item->product->manage_stock) {
                $item->product->increment('stock_quantity', $item->quantity);
                $item->product->decrement('sale_count', $item->quantity);
            }
        }
    }
}
