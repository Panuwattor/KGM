<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// ออเดอร์ที่จัดส่งเกิน 20 วัน -> ส่งถึงแล้วอัตโนมัติ (รันทุกวัน)
Schedule::command('orders:auto-deliver')->dailyAt('01:00');

// ออเดอร์ที่รอชำระเงินเกิน 2 วัน -> ยกเลิก + คืนสต๊อก (รันทุกวัน)
Schedule::command('orders:cancel-unpaid')->dailyAt('02:00');
