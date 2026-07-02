# คู่มือระบบ Role & Permission

## ภาพรวม

ระบบนี้ใช้ **Spatie Laravel Permission** เพื่อจัดการสิทธิ์การเข้าถึงในระบบ โดยแบ่งเป็น:
- **Roles (บทบาท)**: ตำแหน่งงานของผู้ใช้
- **Permissions (สิทธิ์)**: สิทธิ์ในการเข้าถึงเมนูและฟังก์ชันต่างๆ

## Roles (ตำแหน่ง) - 5 ตำแหน่ง

### 1. ผู้บริหาร (executive)
- มีสิทธิ์เข้าถึงทุกอย่างในระบบ
- เหมาะสำหรับ: CEO, ผู้จัดการทั่วไป

### 2. ฝ่ายขาย (sales)
สิทธิ์ที่มี:
- ดู Dashboard และรายงาน
- ดูและจัดการคำสั่งซื้อ
- ดูและจัดการลูกค้า
- ดูและจัดการใบเสนอราคา
- ดูตัวแทนจำหน่าย
- ดูสินค้า (ไม่สามารถแก้ไข)

### 3. บัญชี (accounting)
สิทธิ์ที่มี:
- ดู Dashboard
- ดูและจัดการคำสั่งซื้อ (เน้นการเงิน)
- ดูและจัดการรายงาน (ส่งออกรายงาน)
- ดูลูกค้า
- ดูคูปองส่วนลด
- ดูใบเสนอราคา

### 4. การตลาด (marketing)
สิทธิ์ที่มี:
- ดู Dashboard และรายงาน
- ดูสินค้า (ไม่สามารถแก้ไข)
- จัดการแบนเนอร์
- จัดการบทความ/ข่าวสาร
- จัดการวิดีโอ
- จัดการคูปองส่วนลด
- จัดการ Flash Sale
- จัดการการตลาด
- จัดการรีวิวสินค้า
- จัดการ Landing Page

### 5. พนักงาน (staff)
สิทธิ์พื้นฐาน (ดูข้อมูลเท่านั้น):
- ดู Dashboard
- ดูสินค้า
- ดูคำสั่งซื้อ
- ดูลูกค้า
- ดูข้อความติดต่อ
- ดูใบสมัครงาน

## Permissions (สิทธิ์) - แบ่งเป็น 2 ประเภท

ทุกเมนูจะมี 2 สิทธิ์:
- `xxx_view` - ดูข้อมูลได้
- `xxx_manage` - จัดการได้ (เพิ่ม แก้ไข ลบ)

### รายการ Permissions ทั้งหมด

#### ภาพรวม
- `dashboard_view` - ดูแดชบอร์ด
- `dashboard_manage` - จัดการแดชบอร์ด
- `report_view` - ดูรายงานต่างๆ
- `report_manage` - จัดการและส่งออกรายงาน

#### สินค้า
- `product_view` - ดูรายการสินค้า
- `product_manage` - จัดการสินค้า (เพิ่ม แก้ไข ลบ)
- `category_view` - ดูหมวดหมู่สินค้า
- `category_manage` - จัดการหมวดหมู่สินค้า
- `product_type_view` - ดูประเภทสินค้า
- `product_type_manage` - จัดการประเภทสินค้า

#### ออเดอร์
- `order_view` - ดูคำสั่งซื้อ
- `order_manage` - จัดการคำสั่งซื้อ (อัพเดทสถานะ ยืนยันการชำระเงิน)
- `quote_view` - ดูใบเสนอราคา
- `quote_manage` - จัดการใบเสนอราคา

#### ลูกค้า
- `customer_view` - ดูข้อมูลลูกค้า
- `customer_manage` - จัดการข้อมูลลูกค้า
- `review_view` - ดูรีวิวสินค้า
- `review_manage` - จัดการรีวิวสินค้า (อนุมัติ ปฏิเสธ ลบ)

#### การตลาด
- `banner_view` - ดูแบนเนอร์
- `banner_manage` - จัดการแบนเนอร์
- `post_view` - ดูบทความและข่าวสาร
- `post_manage` - จัดการบทความและข่าวสาร
- `video_view` - ดูวิดีโอ
- `video_manage` - จัดการวิดีโอ
- `coupon_view` - ดูคูปองส่วนลด
- `coupon_manage` - จัดการคูปองส่วนลด
- `flash_sale_view` - ดูโปรโมชั่นพิเศษ
- `flash_sale_manage` - จัดการโปรโมชั่นพิเศษ
- `marketing_view` - ดูข้อมูลการตลาด
- `marketing_manage` - จัดการการตลาด
- `landing_page_view` - ดูหน้า Landing Page
- `landing_page_manage` - จัดการหน้า Landing Page

#### ผู้ใช้งาน
- `user_view` - ดูรายการผู้ใช้งานระบบ
- `user_manage` - จัดการผู้ใช้งานระบบ (เพิ่ม แก้ไข ลบ)
- `role_view` - ดูบทบาทและสิทธิ์
- `role_manage` - จัดการบทบาทและสิทธิ์

#### อื่นๆ
- `contact_view` - ดูข้อความติดต่อจากลูกค้า
- `contact_manage` - จัดการข้อความติดต่อ (ตอบกลับ ลบ)
- `career_view` - ดูตำแหน่งงานและใบสมัคร
- `career_manage` - จัดการตำแหน่งงานและใบสมัคร
- `dealer_view` - ดูข้อมูลตัวแทนจำหน่าย
- `dealer_manage` - จัดการตัวแทนจำหน่าย
- `showroom_view` - ดูโชว์รูม
- `showroom_manage` - จัดการโชว์รูม
- `location_view` - ดูข้อมูลที่อยู่
- `location_manage` - จัดการข้อมูลที่อยู่
- `setting_view` - ดูการตั้งค่าระบบ
- `setting_manage` - จัดการการตั้งค่าระบบ

## วิธีใช้งานในโค้ด

### 1. ใน Blade Template (View)

#### ซ่อน/แสดงเมนูตาม permission
```blade
@can('product_view')
    <a href="{{ route('admin.products.index') }}">จัดการสินค้า</a>
@endcan
```

#### ซ่อน/แสดงปุ่มตาม permission
```blade
@can('product_manage')
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> เพิ่มสินค้าใหม่
    </a>
@endcan

@can('product_manage')
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-light">
        <i class="bi bi-pencil"></i> แก้ไข
    </a>
@endcan
```

#### ตรวจสอบหลายสิทธิ์พร้อมกัน
```blade
@if(auth()->user()->can('product_view') || auth()->user()->can('category_view'))
    <div class="nav-section">
        <div class="nav-section-title">สินค้า</div>
        @can('product_view')
            <a href="{{ route('admin.products.index') }}">จัดการสินค้า</a>
        @endcan
        @can('category_view')
            <a href="{{ route('admin.categories.index') }}">หมวดหมู่</a>
        @endcan
    </div>
@endif
```

### 2. ใน Controller

```php
// ตรวจสอบก่อนทำงาน
public function index()
{
    if (!auth()->user()->can('product_view')) {
        abort(403, 'ไม่มีสิทธิ์เข้าถึงส่วนนี้');
    }

    $products = Product::all();
    return view('admin.products.index', compact('products'));
}

// หรือใช้ authorize()
public function store(Request $request)
{
    $this->authorize('product_manage');

    // ทำงานต่อ...
}
```

### 3. ใน Routes (web.php)

```php
// ใช้ middleware check.permission
Route::middleware(['check.permission:product_view'])->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
});

Route::middleware(['check.permission:product_manage'])->group(function () {
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});
```

## การจัดการ Roles & Permissions

### เพิ่ม Role ใหม่
```php
use Spatie\Permission\Models\Role;

$role = Role::create(['name' => 'accountant', 'guard_name' => 'web']);
```

### เพิ่ม Permission ใหม่
```php
use Spatie\Permission\Models\Permission;

Permission::create(['name' => 'product_view', 'guard_name' => 'web']);
Permission::create(['name' => 'product_manage', 'guard_name' => 'web']);
```

### กำหนด Permission ให้ Role
```php
$role = Role::findByName('sales');
$role->givePermissionTo(['product_view', 'order_view', 'order_manage']);
```

### กำหนด Role ให้ User
```php
$user = User::find(1);
$user->assignRole('sales');
```

## การรัน Seeder

เพื่อสร้าง roles และ permissions พร้อมกำหนดสิทธิ์เริ่มต้น:

```bash
php artisan db:seed --class=RolePermissionSeeder
```

หรือถ้าต้องการรีเซ็ตข้อมูลทั้งหมด:

```bash
php artisan migrate:fresh --seed
```

## Best Practices

### 1. ตั้งชื่อ Permission แบบ Consistent
- ใช้รูปแบบ `{module}_{action}`
- ตัวอย่าง: `product_view`, `product_manage`

### 2. แบ่ง Permission เป็น 2 ระดับ
- `_view` = ดูได้อย่างเดียว
- `_manage` = เพิ่ม แก้ไข ลบได้

### 3. ใช้ Middleware ในระดับ Route
- ปลอดภัยกว่า เพราะตรวจสอบก่อนเข้าถึง Controller
- ใช้ `check.permission:{permission}` ใน routes

### 4. ซ่อนปุ่มที่ไม่มีสิทธิ์ใน View
- ใช้ `@can()` ใน Blade
- ทำให้ UI เรียบง่ายและไม่สับสน

### 5. ตรวจสอบซ้ำใน Controller
- ใช้ `$this->authorize()` หรือ `Gate::allows()`
- เพิ่มความปลอดภัยอีกชั้น

## ตัวอย่างการใช้งานจริง

### กรณีที่ 1: หน้า Products Index

**View (products/index.blade.php)**
```blade
<div class="page-header">
    <div>
        <div class="page-title">จัดการสินค้า</div>
    </div>
    @can('product_manage')
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-lg"></i> เพิ่มสินค้าใหม่
        </a>
    @endcan
</div>

<table>
    @foreach($products as $product)
        <tr>
            <td>{{ $product->name }}</td>
            <td>
                @can('product_manage')
                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-light">
                        <i class="bi bi-pencil"></i>
                    </a>
                @endcan
            </td>
        </tr>
    @endforeach
</table>
```

**Routes (web.php)**
```php
Route::middleware(['check.permission:product_view'])->group(function () {
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
});

Route::middleware(['check.permission:product_manage'])->group(function () {
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products', [ProductController::class, 'store'])->name('products.store');
    Route::put('products/{product}', [ProductController::class, 'update'])->name('products.update');
});
```

## Troubleshooting

### ปัญหา: หลังจากเพิ่ม permission ใหม่แล้วไม่ทำงาน
**วิธีแก้:** Clear cache permission
```bash
php artisan permission:cache-reset
```

### ปัญหา: User ไม่มีสิทธิ์แม้จะกำหนด role แล้ว
**วิธีแก้:**
1. ตรวจสอบว่า role มี permission หรือไม่
2. ตรวจสอบว่า user ถูก assign role แล้วหรือไม่
```php
$user->roles; // ดู roles ที่มี
$user->permissions; // ดู permissions ทั้งหมด
$user->can('product_view'); // ทดสอบ permission
```

### ปัญหา: Sidebar ไม่แสดงเมนู
**วิธีแก้:**
- ตรวจสอบว่าใช้ `@can()` ถูกต้อง
- ตรวจสอบ permission name ว่าตรงกับใน database หรือไม่
