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

        // กำหนดสิทธิ์ (permissions) ตามกลุ่มงาน
        $permissions = [
            // สินค้า
            'view products', 'create products', 'edit products', 'delete products',
            // คำสั่งซื้อ
            'view orders', 'edit orders', 'delete orders',
            // ลูกค้า / ผู้ใช้
            'view users', 'create users', 'edit users', 'delete users',
            // เนื้อหา / บทความ
            'view content', 'create content', 'edit content', 'delete content',
            // รายงาน
            'view reports',
            // ตั้งค่าระบบ
            'manage settings',
            // จัดการสิทธิ์ผู้ใช้
            'manage roles',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // สร้าง role
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staff = Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $customer = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);

        // admin = ทุกสิทธิ์
        $admin->syncPermissions(Permission::all());

        // staff = สิทธิ์การจัดการทั่วไป (ไม่รวมตั้งค่าระบบ / จัดการสิทธิ์ / ลบผู้ใช้)
        $staff->syncPermissions([
            'view products', 'create products', 'edit products',
            'view orders', 'edit orders',
            'view users',
            'view content', 'create content', 'edit content',
            'view reports',
        ]);

        // customer = ไม่มีสิทธิ์ฝั่งหลังบ้าน
        $customer->syncPermissions([]);

        // ผูก role ให้ผู้ใช้เดิมตามคอลัมน์ role ที่มีอยู่
        User::query()->whereIn('role', ['admin', 'staff', 'customer'])
            ->each(function (User $user) {
                if (in_array($user->role, ['admin', 'staff', 'customer'])) {
                    $user->syncRoles([$user->role]);
                }
            });
    }
}
