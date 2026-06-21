<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Career;
use App\Models\Customer;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\ShippingProvider;
use App\Models\ShippingRate;
use App\Models\ShippingSetting;
use App\Models\Showroom;
use App\Models\SiteSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Users ─────────────────────────────────────────────────────────────
        if(User::count() == 0) {
            User::firstOrCreate(
                ['email' => 'admin@admin.com'],
                ['name' => 'แอดมิน KGM', 'password' => Hash::make('a12345678'), 'role' => 'admin', 'is_active' => true]
            );
        }
        // ── Site Settings ──────────────────────────────────────────────────────
        foreach ([
            ['key' => 'site_name',         'value' => 'กิจเจริญการ์เมนท์ (1993) จำกัด'],
            ['key' => 'site_phone',        'value' => '02-000-1234'],
            ['key' => 'site_email',        'value' => 'info@kgm1993.com'],
            ['key' => 'site_line',         'value' => '@KGM1993'],
            ['key' => 'promptpay_number',  'value' => '02-000-1234'],
            ['key' => 'bank_account_name', 'value' => 'บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด'],
            ['key' => 'meta_title',        'value' => 'กิจเจริญการ์เมนท์ | เครื่องแบบนักเรียนและยูนิฟอร์มคุณภาพ'],
            ['key' => 'meta_description',  'value' => 'โรงงานผลิตเครื่องแบบนักเรียน ยูนิฟอร์ม บริการปักและสกรีน ประสบการณ์กว่า 30 ปี'],
        ] as $s) {
            SiteSetting::updateOrCreate(['key' => $s['key']], ['value' => $s['value'], 'group' => 'general']);
        }

        ShippingSetting::firstOrCreate(
            ['provider_name' => 'Kerry Express'],
            ['flat_rate' => 50, 'free_shipping_threshold' => 1000, 'is_active' => true, 'sort_order' => 1]
        );

        // ── Shipping Providers (บริษัทขนส่ง) ───────────────────────────────────
        // มีข้อมูลอยู่แล้วก็ข้าม (กันทับค่าที่แอดมินแก้เอง)
        if (ShippingProvider::count() === 0) {
            foreach (['ไปรษณีย์ไทย', 'Kerry Express', 'Flash Express', 'J&T Express', 'DHL'] as $i => $name) {
                ShippingProvider::create(['name' => $name, 'sort_order' => $i, 'is_active' => true]);
            }
        }

        // ── Shipping Rates ─────────────────────────────────────────────────────
        $this->call(ShippingRateSeeder::class);

        // ── จังหวัด / อำเภอ / ตำบล (seed ครั้งเดียว ถ้ามีแล้วจะข้าม) ───────────
        $this->call(LocationSeeder::class);

        // ── Brands & Categories & Products ────────────────────────────────────
        $this->call([
            BrandCategorySeeder::class,
            ProductTypeSeeder::class,
            ProductSeeder::class,
        ]);

        // ── Banners ───────────────────────────────────────────────────────────
        if(Banner::count() == 0) {
            Banner::create([
                'image_path' => 'banners/default.jpg',
                'link_url'   => '/shop',
                'is_active'  => true,
                'sort_order' => 1,
            ]);
        }

        // ── Showrooms ──────────────────────────────────────────────────────────
        if(Showroom::count() == 0) {
            Showroom::firstOrCreate(
                ['name' => 'สำนักงานใหญ่ บางรัก'],
                [
                    'address'    => '123 ถนนเจริญกรุง แขวงบางรัก เขตบางรัก กรุงเทพฯ 10500',
                    'phone'      => '02-000-1234',
                    'line_id'    => '@KGM1993',
                    'open_hours' => 'จันทร์-ศุกร์ 08:00-17:30 น.',
                    'is_active'  => true,
                    'is_main'    => true,
                    'sort_order' => 1,
                ]
            );
        }
        // ── Careers ───────────────────────────────────────────────────────────
        if(Career::count() == 0) {
            Career::firstOrCreate(
                ['title' => 'พนักงานขาย (Sales)'],
                [
                    'department'   => 'ฝ่ายขาย',
                    'type'         => 'full_time',
                    'description'  => "รับผิดชอบการขายสินค้าและให้คำปรึกษาลูกค้า\nดูแลฐานลูกค้าเดิมและหาลูกค้าใหม่",
                    'requirements' => "- จบการศึกษาระดับปริญญาตรีขึ้นไป\n- มีประสบการณ์งานขาย 1-2 ปี",
                    'benefits'     => "- เงินเดือน + Commission\n- ประกันสุขภาพ\n- โบนัสประจำปี",
                    'location'     => 'กรุงเทพฯ',
                    'vacancies'    => 3,
                    'is_active'    => true,
                ]
            );
        }

        // ── Posts ──────────────────────────────────────────────────────────────
        $newscat = PostCategory::firstOrCreate(['slug' => 'news'],      ['name' => 'ข่าวสาร',   'type' => 'news']);
        PostCategory::firstOrCreate(['slug' => 'promotion'], ['name' => 'โปรโมชั่น', 'type' => 'promotion']);

        Post::firstOrCreate(
            ['slug' => 'kgm-new-products-2024'],
            [
                'post_category_id' => $newscat->id,
                'author_id'        => 1,
                'title'            => 'KGM เปิดตัวสินค้าใหม่ เครื่องแบบนักเรียน Season 2024',
                'excerpt'          => 'บริษัท กิจเจริญการ์เมนท์ เปิดตัวสินค้าใหม่สำหรับปีการศึกษา 2024',
                'body'             => '<p>บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด ขอแนะนำเครื่องแบบนักเรียนรุ่นใหม่ล่าสุดสำหรับปีการศึกษา 2024</p>',
                'status'           => 'published',
                'published_at'     => now()->subDays(5),
            ]
        );
    }
}
