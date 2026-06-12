<?php

namespace Database\Seeders;

use App\Models\ProductType;
use Illuminate\Database\Seeder;

class ProductTypeSeeder extends Seeder
{
    public function run(): void
    {
        foreach ([
            [
                'name'         => 'เสื้อนักเรียน',
                'slug'         => 'shirt',
                'description'  => 'เสื้อเชิ้ต เสื้อปกบัว เสื้อยุวกาชาด เสื้อลูกเสือ และเสื้ออนุบาลทุกรูปแบบ',
                'show_on_home' => true,
                'sort_order'   => 1,
            ],
            [
                'name'         => 'กางเกง',
                'slug'         => 'pants',
                'description'  => 'กางเกงนักเรียนชายทุกสีและขนาด รวมถึงกางเกงอนุบาล',
                'show_on_home' => true,
                'sort_order'   => 2,
            ],
            [
                'name'         => 'กระโปรง',
                'slug'         => 'skirt',
                'description'  => 'กระโปรงจีบ กระโปรงจีบรอบ กระโปรงทรงแคบ และกระโปรงอนุบาล',
                'show_on_home' => true,
                'sort_order'   => 3,
            ],
            [
                'name'         => 'เสื้อกีฬา',
                'slug'         => 'sport-shirt',
                'description'  => 'เสื้อกีฬาโปโล กางเกงวอร์ม ชุดพลศึกษา',
                'show_on_home' => true,
                'sort_order'   => 4,
            ],
            [
                'name'         => 'กระเป๋า',
                'slug'         => 'bag',
                'description'  => 'กระเป๋าเป้นักเรียนทุกระดับ ประถม มัธยม อนุบาล',
                'show_on_home' => true,
                'sort_order'   => 5,
            ],
            [
                'name'         => 'อุปกรณ์',
                'slug'         => 'accessories',
                'description'  => 'เข็มขัด โบว์ ชุดอุปกรณ์ลูกเสือ ยุวกาชาด และอุปกรณ์อื่นๆ',
                'show_on_home' => true,
                'sort_order'   => 6,
            ],
        ] as $data) {
            ProductType::firstOrCreate(
                ['slug' => $data['slug']],
                $data + ['is_active' => true]
            );
        }
    }
}
