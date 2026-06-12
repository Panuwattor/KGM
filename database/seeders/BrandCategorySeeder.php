<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Seeder;

class BrandCategorySeeder extends Seeder
{
    public function run(): void
    {
        // ── Brands (ตราผ้า) ────────────────────────────────────────────────────
        foreach ([
            [
                'name'        => 'ราชพฤกษ์',
                'slug'        => 'rachapruek',
                'description' => 'ผ้า TC (65% Polyester, 35% Cotton) คุณภาพดี ทนทาน ซักง่าย ไม่ยับง่าย',
                'image'       => 'brands/rachapruek.jpg',
                'sort_order'  => 1,
            ],
            [
                'name'        => 'Coolkidz',
                'slug'        => 'coolkidz',
                'description' => 'ผ้า TC (65% Polyester, 35% Cotton) ผิวสัมผัสนุ่มลื่น แห้งเร็ว Non-azo dyes',
                'image'       => 'brands/coolkidz.jpg',
                'sort_order'  => 2,
            ],
            [
                'name'        => 'BigSave',
                'slug'        => 'bigsave',
                'description' => 'ผ้า TC คุณภาพมาตรฐาน ราคาประหยัด คงรูปทรง ไม่เป็นขุยขน รีดง่าย',
                'image'       => 'brands/bigsave.jpg',
                'sort_order'  => 3,
            ],
            [
                'name'        => 'ชัยพฤกษ์',
                'slug'        => 'chaiyapruek',
                'description' => 'ผ้ามันดีวาย (Divai) เนื้อผ้ามันวาว ทิ้งตัวสวย ทรงแคบ ไม่อ้า',
                'image'       => 'brands/chaiyapruek.jpg',
                'sort_order'  => 4,
            ],
            [
                'name'        => 'KGM',
                'slug'        => 'kgm',
                'description' => 'เสื้อกีฬาและอุปกรณ์ ผลิตโดย กิจเจริญการ์เมนท์ (1993) จำกัด',
                'image'       => 'brands/kgm.jpg',
                'sort_order'  => 5,
            ],
        ] as $b) {
            Brand::firstOrCreate(['slug' => $b['slug']], $b + ['is_active' => true]);
        }

        // ── Categories ─────────────────────────────────────────────────────────
        // ตรงกับโครงสร้างเว็บจริง store.kgm.co.th:
        //   • 2 โรงเรียน → มีหมวดย่อย (uniform type)
        //   • 3 ตราสินค้า → ไม่มีหมวดย่อย (products ผูกกับ parent โดยตรง)

        // ── โรงเรียนอนุบาลศรีสะเกษ ─────────────────────────────────────────
        $siSaket = Category::firstOrCreate(
            ['slug' => 'anuban-si-saket'],
            [
                'name'        => 'โรงเรียนอนุบาลศรีสะเกษ',
                'description' => 'เครื่องแบบนักเรียนโรงเรียนอนุบาลศรีสะเกษ ครบทุกประเภท',
                'image'       => 'categories/anuban-si-saket.jpg',
                'sort_order'  => 1,
                'is_active'   => true,
            ]
        );

        foreach ([
            ['ชุดนักเรียน ระดับอนุบาล',       'nursery-si-saket',   1],
            ['ชุดนักเรียน ระดับประถม',        'primary-si-saket',   2],
            ['ชุดกีฬา ระดับอนุบาล',           'sport-nur-si-saket', 3],
            ['ชุดกีฬา ระดับประถม ป.1-ป.3',   'sport-p13-si-saket', 4],
            ['ชุดกีฬา ระดับประถม ป.4-ป.6',   'sport-p46-si-saket', 5],
            ['ชุดลูกเสือ',                    'scout-si-saket',     6],
            ['ชุดยุวกาชาด',                   'cadet-si-saket',     7],
            ['อุปกรณ์อื่นๆ',                  'equip-si-saket',     8],
        ] as [$name, $slug, $order]) {
            Category::firstOrCreate(
                ['slug' => $slug],
                ['parent_id' => $siSaket->id, 'name' => $name, 'sort_order' => $order, 'is_active' => true]
            );
        }

        // ── โรงเรียนอนุบาลวัดพระโต ─────────────────────────────────────────
        $phraTo = Category::firstOrCreate(
            ['slug' => 'anuban-phra-to'],
            [
                'name'        => 'โรงเรียนอนุบาลวัดพระโต',
                'description' => 'เครื่องแบบนักเรียนโรงเรียนอนุบาลวัดพระโต ครบทุกประเภท',
                'image'       => 'categories/anuban-phra-to.jpg',
                'sort_order'  => 2,
                'is_active'   => true,
            ]
        );

        foreach ([
            ['ชุดนักเรียน ระดับอนุบาล',   'nursery-phra-to', 1],
            ['ชุดนักเรียน ระดับประถม',    'primary-phra-to', 2],
            ['ชุดกีฬาและอุปกรณ์',         'sport-phra-to',   3],
        ] as [$name, $slug, $order]) {
            Category::firstOrCreate(
                ['slug' => $slug],
                ['parent_id' => $phraTo->id, 'name' => $name, 'sort_order' => $order, 'is_active' => true]
            );
        }

        // ── หมวดตราสินค้า (ไม่มีหมวดย่อย) ──────────────────────────────────
        foreach ([
            ['ชุดนักเรียนตราคูลคิดส์', 'cat-coolkidz',   'categories/coolkidz.jpg',    3],
            ['ชุดนักเรียนตราBigSave',  'cat-bigsave',    'categories/bigsave.jpg',     4],
            ['ชุดนักเรียนชัยพฤกษ์',   'cat-chaiyapruek','categories/chaiyapruek.jpg', 5],
        ] as [$name, $slug, $img, $order]) {
            Category::firstOrCreate(
                ['slug' => $slug],
                ['name' => $name, 'image' => $img, 'sort_order' => $order, 'is_active' => true]
            );
        }
    }
}
