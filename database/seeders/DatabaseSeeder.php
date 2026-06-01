<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Career;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\Product;
use App\Models\Showroom;
use App\Models\SiteSetting;
use App\Models\ShippingSetting;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name'     => 'แอดมิน KGM',
            'email'    => 'admin@admin.com',
            'password' => Hash::make('a12345678'),
            'role'     => 'admin',
            'is_active'=> true,
        ]);

        // Test Customer
        Customer::create([
            'name'     => 'ลูกค้าทดสอบ',
            'email'    => 'customer@test.com',
            'phone'    => '0812345678',
            'password' => Hash::make('Password@1'),
            'status'   => 'active',
        ]);

        // Site Settings
        $settings = [
            ['key' => 'site_name',        'value' => 'กิจเจริญการ์เมนท์ (1993) จำกัด'],
            ['key' => 'site_phone',       'value' => '02-000-1234'],
            ['key' => 'site_email',       'value' => 'info@kgm1993.com'],
            ['key' => 'site_line',        'value' => '@KGM1993'],
            ['key' => 'promptpay_number', 'value' => '02-000-1234'],
            ['key' => 'bank_account_name','value' => 'บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด'],
            ['key' => 'meta_title',       'value' => 'กิจเจริญการ์เมนท์ | เครื่องแบบนักเรียนและยูนิฟอร์มคุณภาพ'],
            ['key' => 'meta_description', 'value' => 'โรงงานผลิตเครื่องแบบนักเรียน ยูนิฟอร์ม บริการปักและสกรีน ประสบการณ์กว่า 30 ปี'],
        ];
        foreach ($settings as $s) {
            SiteSetting::create($s + ['group' => 'general']);
        }

        // Shipping
        ShippingSetting::create([
            'provider_name'           => 'Kerry Express',
            'flat_rate'               => 50,
            'free_shipping_threshold' => 1000,
            'is_active'               => true,
            'sort_order'              => 1,
        ]);

        // Categories
        $cats = [
            ['name' => 'เครื่องแบบนักเรียนชาย', 'slug' => 'student-uniform-male',   'sub' => ['ระดับประถม', 'ระดับมัธยม', 'ชุดพลศึกษา', 'ชุดลูกเสือ']],
            ['name' => 'เครื่องแบบนักเรียนหญิง','slug' => 'student-uniform-female', 'sub' => ['ระดับประถม', 'ระดับมัธยม', 'ชุดพลศึกษา', 'ชุดเนตรนารี']],
            ['name' => 'ยูนิฟอร์มองค์กร',        'slug' => 'corporate-uniform',      'sub' => ['เสื้อเชิ้ต', 'โปโล', 'สูท']],
            ['name' => 'เครื่องแบบพยาบาล',       'slug' => 'nurse-uniform',           'sub' => []],
        ];

        foreach ($cats as $catData) {
            $parent = Category::create([
                'name'      => $catData['name'],
                'slug'      => $catData['slug'],
                'is_active' => true,
                'sort_order'=> 0,
            ]);
            foreach ($catData['sub'] as $i => $subName) {
                Category::create([
                    'parent_id'  => $parent->id,
                    'name'       => $subName,
                    'slug'       => $catData['slug'] . '-sub-' . ($i + 1),
                    'is_active'  => true,
                    'sort_order' => $i,
                ]);
            }
        }

        // Products
        $cat1 = Category::where('slug', 'student-uniform-male')->first();
        $cat2 = Category::where('slug', 'student-uniform-female')->first();

        $products = [
            ['ชุดนักเรียนชายประถม เสื้อขาว กางเกงดำ', $cat1->id, 290, null,  true,  true,  false],
            ['ชุดนักเรียนชายมัธยม เสื้อขาว กางเกงดำ', $cat1->id, 350, 320,   true,  true,  false],
            ['ชุดพลศึกษาชาย เสื้อสีฟ้า',              $cat1->id, 250, null,  true,  false, false],
            ['ชุดลูกเสือสามัญรุ่นใหญ่',               $cat1->id, 450, 399,   false, false, true],
            ['ชุดนักเรียนหญิงประถม เสื้อขาว กระโปรงน้ำเงิน', $cat2->id, 320, null, true,  true,  false],
            ['ชุดนักเรียนหญิงมัธยม เสื้อขาว กระโปรงดำ',      $cat2->id, 390, 360,  true,  true,  false],
            ['ชุดพลศึกษาหญิง เสื้อสีฟ้า กางเกงขาสั้น',       $cat2->id, 260, null, false, false, true],
            ['ชุดเนตรนารีสามัญรุ่นใหญ่',               $cat2->id, 480, 430,   false, true,  false],
        ];

        foreach ($products as [$name, $catId, $price, $salePrice, $isNew, $isFeatured, $isBestseller]) {
            $product = Product::create([
                'category_id'       => $catId,
                'name'              => $name,
                'slug'              => Str::slug($name) . '-' . Str::random(5),
                'short_description' => 'ผลิตจากผ้าคุณภาพสูง ทนทาน สวมใส่สบาย ผ่านมาตรฐาน MOE',
                'description'       => '<p>ผลิตจากผ้าคุณภาพสูง ผ่านการควบคุมคุณภาพทุกขั้นตอน เหมาะสำหรับสวมใส่ในโรงเรียน ทนทาน ซักง่าย</p>',
                'price'             => $price,
                'sale_price'        => $salePrice,
                'stock_quantity'    => rand(20, 100),
                'low_stock_threshold'=> 5,
                'manage_stock'      => true,
                'is_active'         => true,
                'is_new'            => $isNew,
                'is_featured'       => $isFeatured,
                'is_bestseller'     => $isBestseller,
                'sale_count'        => rand(10, 200),
                'meta_title'        => $name . ' | KGM',
                'meta_description'  => 'ซื้อ' . $name . ' ราคา ฿' . number_format($salePrice ?? $price),
            ]);

            foreach (['28','30','32','34','36','38','40'] as $size) {
                $product->variants()->create([
                    'size'             => $size,
                    'stock_quantity'   => rand(5, 20),
                    'price_adjustment' => $size >= '38' ? 20 : 0,
                    'is_active'        => true,
                ]);
            }
        }

        // Banners
        Banner::create([
            'image_path' => 'banners/default.jpg',
            'link_url'   => '/shop',
            'is_active'  => true,
            'sort_order' => 1,
        ]);

        // Showrooms
        Showroom::create([
            'name'      => 'สำนักงานใหญ่ บางรัก',
            'address'   => '123 ถนนเจริญกรุง แขวงบางรัก เขตบางรัก กรุงเทพฯ 10500',
            'phone'     => '02-000-1234',
            'line_id'   => '@KGM1993',
            'open_hours'=> 'จันทร์-ศุกร์ 08:00-17:30 น.',
            'is_active' => true,
            'is_main'   => true,
            'sort_order'=> 1,
        ]);

        // Careers
        Career::create([
            'title'       => 'พนักงานขาย (Sales)',
            'department'  => 'ฝ่ายขาย',
            'type'        => 'full_time',
            'description' => "รับผิดชอบการขายสินค้าและให้คำปรึกษาลูกค้า\nดูแลฐานลูกค้าเดิมและหาลูกค้าใหม่",
            'requirements'=> "- จบการศึกษาระดับปริญญาตรีขึ้นไป\n- มีประสบการณ์งานขาย 1-2 ปี",
            'benefits'    => "- เงินเดือน + Commission\n- ประกันสุขภาพ\n- โบนัสประจำปี",
            'location'    => 'กรุงเทพฯ',
            'vacancies'   => 3,
            'is_active'   => true,
        ]);

        // Post Categories
        $newscat  = PostCategory::create(['name' => 'ข่าวสาร',   'slug' => 'news',      'type' => 'news']);
        $promocat = PostCategory::create(['name' => 'โปรโมชั่น', 'slug' => 'promotion', 'type' => 'promotion']);

        Post::create([
            'post_category_id' => $newscat->id,
            'author_id'        => 1,
            'title'            => 'KGM เปิดตัวสินค้าใหม่ เครื่องแบบนักเรียน Season 2024',
            'slug'             => 'kgm-new-products-2024',
            'excerpt'          => 'บริษัท กิจเจริญการ์เมนท์ เปิดตัวสินค้าใหม่สำหรับปีการศึกษา 2024',
            'body'             => '<p>บริษัท กิจเจริญการ์เมนท์ (1993) จำกัด ขอแนะนำเครื่องแบบนักเรียนรุ่นใหม่ล่าสุดสำหรับปีการศึกษา 2024</p>',
            'status'           => 'published',
            'published_at'     => now()->subDays(5),
        ]);
    }
}
