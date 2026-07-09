<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // รีเซ็ต cache ของ permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // กำหนดสิทธิ์ (permissions) แบ่งตามเมนู แต่ละเมนูมี 2 สิทธิ์: ดูได้ (_view) และแก้ไขได้ (_manage)
        $permissions = [
            // Dashboard
            ['name' => 'dashboard_view', 'description' => 'ดูแดชบอร์ด'],
            ['name' => 'dashboard_manage', 'description' => 'จัดการแดชบอร์ด'],

            // Products (สินค้า)
            ['name' => 'product_view', 'description' => 'ดูรายการสินค้า'],
            ['name' => 'product_manage', 'description' => 'จัดการสินค้า (เพิ่ม แก้ไข ลบ)'],

            // Categories (หมวดหมู่สินค้า)
            ['name' => 'category_view', 'description' => 'ดูหมวดหมู่สินค้า'],
            ['name' => 'category_manage', 'description' => 'จัดการหมวดหมู่สินค้า'],

            // Product Types (ประเภทสินค้า)
            ['name' => 'product_type_view', 'description' => 'ดูประเภทสินค้า'],
            ['name' => 'product_type_manage', 'description' => 'จัดการประเภทสินค้า'],

            // Orders (คำสั่งซื้อ)
            ['name' => 'order_view', 'description' => 'ดูคำสั่งซื้อ'],
            ['name' => 'order_manage', 'description' => 'จัดการคำสั่งซื้อ (อัพเดทสถานะ ยืนยันการชำระเงิน)'],

            // Customers (ลูกค้า)
            ['name' => 'customer_view', 'description' => 'ดูข้อมูลลูกค้า'],
            ['name' => 'customer_manage', 'description' => 'จัดการข้อมูลลูกค้า'],

            // Users (ผู้ใช้งานระบบ)
            ['name' => 'user_view', 'description' => 'ดูรายการผู้ใช้งานระบบ'],
            ['name' => 'user_manage', 'description' => 'จัดการผู้ใช้งานระบบ (เพิ่ม แก้ไข ลบ)'],

            // Roles & Permissions (บทบาทและสิทธิ์)
            ['name' => 'role_view', 'description' => 'ดูบทบาทและสิทธิ์'],
            ['name' => 'role_manage', 'description' => 'จัดการบทบาทและสิทธิ์'],

            // Banners (แบนเนอร์)
            ['name' => 'banner_view', 'description' => 'ดูแบนเนอร์'],
            ['name' => 'banner_manage', 'description' => 'จัดการแบนเนอร์'],

            // News Tickers (ข่าวสำคัญ)
            ['name' => 'news_ticker_view', 'description' => 'ดูข่าวสำคัญ'],
            ['name' => 'news_ticker_manage', 'description' => 'จัดการข่าวสำคัญ'],

            // Posts (บทความ/ข่าวสาร)
            ['name' => 'post_view', 'description' => 'ดูบทความและข่าวสาร'],
            ['name' => 'post_manage', 'description' => 'จัดการบทความและข่าวสาร'],

            // Videos (วิดีโอ)
            ['name' => 'video_view', 'description' => 'ดูวิดีโอ'],
            ['name' => 'video_manage', 'description' => 'จัดการวิดีโอ'],

            // Showrooms (โชว์รูม)
            ['name' => 'showroom_view', 'description' => 'ดูโชว์รูม'],
            ['name' => 'showroom_manage', 'description' => 'จัดการโชว์รูม'],

            // Manufacturing Products (สินค้าที่รับผลิต)
            ['name' => 'manufacturing_product_view', 'description' => 'ดูสินค้าที่รับผลิต'],
            ['name' => 'manufacturing_product_manage', 'description' => 'จัดการสินค้าที่รับผลิต'],

            // Coupons (คูปอง)
            ['name' => 'coupon_view', 'description' => 'ดูคูปองส่วนลด'],
            ['name' => 'coupon_manage', 'description' => 'จัดการคูปองส่วนลด'],

            // Flash Sales (โปรโมชั่นพิเศษ)
            ['name' => 'flash_sale_view', 'description' => 'ดูโปรโมชั่นพิเศษ'],
            ['name' => 'flash_sale_manage', 'description' => 'จัดการโปรโมชั่นพิเศษ'],

            // Marketing (การตลาด)
            ['name' => 'marketing_view', 'description' => 'ดูข้อมูลการตลาด'],
            ['name' => 'marketing_manage', 'description' => 'จัดการการตลาด'],

            // Reviews (รีวิวสินค้า)
            ['name' => 'review_view', 'description' => 'ดูรีวิวสินค้า'],
            ['name' => 'review_manage', 'description' => 'จัดการรีวิวสินค้า (อนุมัติ ปฏิเสธ ลบ)'],

            // Quotes (ใบเสนอราคา B2B)
            ['name' => 'quote_view', 'description' => 'ดูใบเสนอราคา'],
            ['name' => 'quote_manage', 'description' => 'จัดการใบเสนอราคา'],

            // Dealers (ตัวแทนจำหน่าย)
            ['name' => 'dealer_view', 'description' => 'ดูข้อมูลตัวแทนจำหน่าย'],
            ['name' => 'dealer_manage', 'description' => 'จัดการตัวแทนจำหน่าย'],

            // Careers (สมัครงาน)
            ['name' => 'career_view', 'description' => 'ดูตำแหน่งงานและใบสมัคร'],
            ['name' => 'career_manage', 'description' => 'จัดการตำแหน่งงานและใบสมัคร'],

            // Contacts (ข้อความติดต่อ)
            ['name' => 'contact_view', 'description' => 'ดูข้อความติดต่อจากลูกค้า'],
            ['name' => 'contact_manage', 'description' => 'จัดการข้อความติดต่อ (ตอบกลับ ลบ)'],

            // Locations (จังหวัด/อำเภอ/ตำบล)
            ['name' => 'location_view', 'description' => 'ดูข้อมูลที่อยู่'],
            ['name' => 'location_manage', 'description' => 'จัดการข้อมูลที่อยู่'],

            // Reports (รายงาน)
            ['name' => 'report_view', 'description' => 'ดูรายงานต่างๆ'],
            ['name' => 'report_manage', 'description' => 'จัดการและส่งออกรายงาน'],

            // Settings (ตั้งค่าระบบ)
            ['name' => 'setting_view', 'description' => 'ดูการตั้งค่าระบบ'],
            ['name' => 'setting_manage', 'description' => 'จัดการการตั้งค่าระบบ'],

            // Landing Pages (หน้า Landing)
            ['name' => 'landing_page_view', 'description' => 'ดูหน้า Landing Page'],
            ['name' => 'landing_page_manage', 'description' => 'จัดการหน้า Landing Page'],
        ];

        // สร้าง permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                ['description' => $permission['description']]
            );
        }

        // สร้าง Roles พร้อมกำหนดสิทธิ์

        // 1. ผู้บริหาร - มีสิทธิ์ทุกอย่าง
        $executive = Role::firstOrCreate(['name' => 'executive', 'guard_name' => 'web']);
        $executive->syncPermissions(Permission::all());

        // 2. ฝ่ายขาย - เน้นคำสั่งซื้อ ลูกค้า สินค้า ใบเสนอราคา
        $sales = Role::firstOrCreate(['name' => 'sales', 'guard_name' => 'web']);
        $sales->syncPermissions([
            'dashboard_view',
            'product_view',
            'order_view', 'order_manage',
            'customer_view', 'customer_manage',
            'quote_view', 'quote_manage',
            'dealer_view',
            'report_view',
        ]);

        // 3. บัญชี - เน้นคำสั่งซื้อ รายงาน การเงิน
        $accounting = Role::firstOrCreate(['name' => 'accounting', 'guard_name' => 'web']);
        $accounting->syncPermissions([
            'dashboard_view',
            'order_view', 'order_manage',
            'customer_view',
            'report_view', 'report_manage',
            'coupon_view',
            'quote_view',
        ]);

        // 4. การตลาด - เน้นเนื้อหา โปรโมชั่น การตลาด
        $marketing = Role::firstOrCreate(['name' => 'marketing', 'guard_name' => 'web']);
        $marketing->syncPermissions([
            'dashboard_view',
            'product_view',
            'banner_view', 'banner_manage',
            'news_ticker_view', 'news_ticker_manage',
            'post_view', 'post_manage',
            'video_view', 'video_manage',
            'coupon_view', 'coupon_manage',
            'flash_sale_view', 'flash_sale_manage',
            'marketing_view', 'marketing_manage',
            'review_view', 'review_manage',
            'landing_page_view', 'landing_page_manage',
            'manufacturing_product_view', 'manufacturing_product_manage',
            'report_view',
        ]);

        // 5. พนักงาน - สิทธิ์พื้นฐาน ดูข้อมูลเป็นหลัก
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $staff->syncPermissions([
            'dashboard_view',
            'product_view',
            'order_view',
            'customer_view',
            'contact_view',
            'career_view',
        ]);

        // เช็คและผูก User ID 1 เป็น executive เสมอ (ทุกครั้งที่รัน seeder)
        $userOne = User::find(1);
        if ($userOne) {
            // ถ้า User ID 1 ไม่ใช่ executive ให้แก้เป็น executive
            if (!$userOne->hasRole('executive')) {
                $userOne->syncRoles(['executive']);
                $this->command->info('✅ แก้ไข User ID 1 เป็น executive แล้ว');
            } else {
                $this->command->info('✅ User ID 1 เป็น executive อยู่แล้ว');
            }
        }

        // ผูก role ให้ผู้ใช้คนอื่นๆ ตามคอลัมน์ role ที่มีอยู่ (เฉพาะที่ยังไม่มี role)
        User::query()->where('id', '!=', 1)->get()->each(function (User $user) {
            // ถ้ายังไม่มี role เลย ให้แปลงจาก column role
            if ($user->roles->isEmpty()) {
                $roleMapping = [
                    'admin' => 'executive',
                    'staff' => 'staff',
                ];

                if (isset($roleMapping[$user->role])) {
                    $user->syncRoles([$roleMapping[$user->role]]);
                }
            }
        });

        $this->command->info('✅ Roles และ Permissions ถูกสร้างเรียบร้อยแล้ว');
    }
}
