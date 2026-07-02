<?php

namespace Database\Seeders;

use App\Models\NewsTicker;
use Illuminate\Database\Seeder;

class NewsTickerSeeder extends Seeder
{
    public function run(): void
    {
        $tickers = [
            [
                'plain_text' => 'ยินดีต้อนรับสู่เว็บไซต์กิจเจริญการ์เมนท์ | รับสมัครตัวแทนจำหน่ายทั่วประเทศ',
                'link_text' => 'สมัครเลย',
                'link_url' => route('dealer'),
                'is_active' => true,
                'order' => 1,
            ],
            [
                'plain_text' => 'มีบริการออกแบบและสั่งผลิตเครื่องแบบนักเรียน ชุดยูนิฟอร์ม ให้กับโรงเรียนและองค์กร',
                'link_text' => 'ดูรายละเอียด',
                'link_url' => route('school-uniforms'),
                'is_active' => true,
                'order' => 2,
            ],
            [
                'plain_text' => 'รับปักและสกรีนโลโก้บนเสื้อผ้า งานคุณภาพด้วยเครื่องจักรทันสมัย',
                'link_text' => 'คลิกที่นี่',
                'link_url' => route('embroidery-screen'),
                'is_active' => true,
                'order' => 3,
            ],
            [
                'plain_text' => 'สินค้าคุณภาพพรีเมียม ส่งฟรีทั่วประเทศสำหรับคำสั่งซื้อตั้งแต่ 1,500 บาท',
                'link_text' => 'เข้าชม',
                'link_url' => route('shop'),
                'is_active' => true,
                'order' => 4,
            ],
        ];

        foreach ($tickers as $ticker) {
            NewsTicker::create($ticker);
        }

        $this->command->info('✅ สร้างข้อมูลข่าวสำคัญเรียบร้อยแล้ว');
    }
}
