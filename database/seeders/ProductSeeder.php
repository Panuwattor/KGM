<?php

namespace Database\Seeders;

use App\Helpers\ThaiSlug;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ── Brand & Category lookups ───────────────────────────────────────────
        $brand = fn(string $slug) => Brand::where('slug', $slug)->first()?->id;
        $cat   = fn(string $slug) => Category::where('slug', $slug)->firstOrFail()->id;

        // ── Unique slug generator ─────────────────────────────────────────────
        $usedSlugs = [];
        $makeSlug  = function (string $name) use (&$usedSlugs): string {
            $base    = ThaiSlug::make($name);
            $slug    = $base;
            $counter = 2;
            while (in_array($slug, $usedSlugs) || Product::where('slug', $slug)->exists()) {
                $slug = $base . '-' . $counter++;
            }
            $usedSlugs[] = $slug;
            return $slug;
        };

        $rp  = $brand('rachapruek');
        $ck  = $brand('coolkidz');
        $bs  = $brand('bigsave');
        $cp  = $brand('chaiyapruek');
        $kgm = $brand('kgm');

        // ── Image helper ──────────────────────────────────────────────────────
        // Copies public/kgm-assets/products/{relativePath} → storage/app/public/products/{year}/{month}/{file}
        // Returns stored path (relative to public disk) or null if source not found.
        $img = function (string $relativePath): ?string {
            $source = public_path("kgm-assets/products/{$relativePath}");
            if (!file_exists($source)) return null;

            $folder = 'products/' . dirname($relativePath);
            $file   = basename($relativePath);

            try {
                $stored = Storage::disk('public')->putFileAs($folder, new File($source), $file);
                return $stored ?: null;
            } catch (\Throwable) {
                return null;
            }
        };

        // ── Variant & product helpers ─────────────────────────────────────────
        $v  = fn(string $size, int $adj) => ['size' => $size, 'adj' => $adj, 'stock' => rand(5, 20)];
        $vf = fn(string $size)           => ['size' => $size, 'adj' => 0,   'stock' => rand(5, 20)];
        // single no-size variant (accessories / bags)
        $noSize = [['size' => null, 'adj' => 0, 'stock' => rand(10, 30)]];

        $make = function (array $p) use ($img, $makeSlug) {
            $variants   = $p['variants'] ?? [];
            $totalStock = (int) array_sum(array_column($variants, 'stock'));

            $product = Product::create([
                'category_id'         => $p['cat'],
                'brand_id'            => $p['brand'] ?? null,
                'name'                => $p['name'],
                'slug'                => $makeSlug($p['name']),
                'short_description'   => $p['desc'] ?? null,
                'description'         => $p['body'] ?? null,
                'price'               => $p['price'],
                'sale_price'          => $p['sale'] ?? null,
                'stock_quantity'      => $totalStock ?: rand(10, 50),
                'low_stock_threshold' => 5,
                'manage_stock'        => true,
                'is_active'           => true,
                'is_featured'         => $p['featured']    ?? false,
                'is_new'              => $p['new']         ?? false,
                'is_bestseller'       => $p['bestseller']  ?? false,
                'sale_count'          => rand(5, 300),
                'meta_title'          => $p['name'] . ' | KGM',
                'meta_description'    => 'ซื้อ' . $p['name'] . ' ราคาเริ่มต้น ฿' . number_format($p['price']),
            ]);

            foreach ($variants as $vd) {
                $product->variants()->create([
                    'size'             => $vd['size'],
                    'price_adjustment' => $vd['adj'],
                    'stock_quantity'   => $vd['stock'],
                    'is_active'        => true,
                ]);
            }

            if (isset($p['img'])) {
                $stored = $img($p['img']);
                if ($stored) {
                    $product->images()->create([
                        'image_path' => $stored,
                        'is_primary' => true,
                        'sort_order' => 0,
                    ]);
                }
            }
        };

        // ═══════════════════════════════════════════════════════════════════════
        // ── Shared variant arrays ─────────────────────────────────────────────
        // ═══════════════════════════════════════════════════════════════════════

        // เสื้อเชิ้ตนักเรียนชาย ราชพฤกษ์ รอบอก 30-50  → ฿169-฿279
        $shirtMaleRP = [$v('30',0),$v('32',12),$v('34',22),$v('36',32),$v('38',42),$v('40',52),$v('42',62),$v('44',72),$v('46',82),$v('48',92),$v('50',110)];

        // เสื้อเชิ้ตนักเรียนชาย Coolkidz รอบอก 32-50  → ฿206-฿287
        $shirtMaleCK = [$v('32',0),$v('34',9),$v('36',18),$v('38',27),$v('40',36),$v('42',45),$v('44',54),$v('46',63),$v('48',72),$v('50',81)];

        // เสื้อเชิ้ตนักเรียนชาย BigSave รอบอก 28-44   → ฿125-฿175
        $shirtMaleBS = [$v('28',0),$v('30',6),$v('32',13),$v('34',19),$v('36',25),$v('38',31),$v('40',38),$v('42',44),$v('44',50)];

        // เสื้อปกบัวโปโลแหลม ราชพฤกษ์ (หญิง) S-3XL    → ฿169-฿249
        $shirtFemaleRP = [$v('S (27")',0),$v('M (29")',0),$v('L (31")',0),$v('XL (32")',80),$v('XXL (34")',80),$v('3XL (36")',80)];

        // เสื้อนักเรียนหญิง Coolkidz รอบอก 32-44       → ฿206-฿260
        $shirtFemaleCK = [$v('32',0),$v('34',9),$v('36',18),$v('38',27),$v('40',36),$v('42',45),$v('44',54)];

        // เสื้อปกทหารเรือ / เตรียมแขนพอง Coolkidz 36-46 → ฿224-฿269
        $shirtFemCKLg = [$v('36',0),$v('38',9),$v('40',18),$v('42',27),$v('44',36),$v('46',45)];

        // เสื้อยุวกาชาด / ลูกเสือ ราชพฤกษ์ 30-50       → ฿260-฿380
        $shirtScoutRP = [$v('30',0),$v('32',12),$v('34',24),$v('36',36),$v('38',48),$v('40',60),$v('42',72),$v('44',84),$v('46',96),$v('48',108),$v('50',120)];

        // เสื้ออนุบาลปกบัวดุมเอว ราชพฤกษ์ (หญิง) S-3XL → ฿139-฿209
        $shirtNurseryRP = [$v('S (27")',0),$v('M (29")',0),$v('L (31")',0),$v('XL (32")',70),$v('XXL (34")',70),$v('3XL (36")',70)];

        // เสื้ออนุบาลปกบัวดุมเอว Coolkidz (หญิง) M-XXL  → ฿170-฿197
        $shirtNurseryCK = [$v('M (29")',0),$v('L (31")',9),$v('XL (32")',18),$v('XXL (34")',27)];

        // เสื้ออนุบาลฮาวาย Coolkidz (ชาย) M-XXL         → ฿170-฿197
        $shirtNurseryBoyCK = [$v('M (29")',0),$v('L (31")',9),$v('XL (32")',18),$v('XXL (34")',27)];

        // เสื้อปกบัวผ่าตลอด Coolkidz / เสื้ออนุบาลโปโล BigSave 30-36 → ฿125 flat
        $shirtNurseryBSFlat = [$vf('30'),$vf('32'),$vf('34'),$vf('36')];

        // เสื้ออนุบาล BigSave (ดุมเอว / ฮาวาย) S-L → ฿110 flat
        $shirtNurseryBSSml = [$vf('S (27")'),$vf('M (29")'),$vf('L (31")')];

        // เสื้อเตรียมแขนพอง BigSave 28-44 → ฿175 flat
        $shirtFemBSFlat = [$vf('28'),$vf('30'),$vf('32'),$vf('34'),$vf('36'),$vf('38'),$vf('40'),$vf('42'),$vf('44')];

        // กางเกงนักเรียนชาย ราชพฤกษ์ 11×20–22×36 → ฿195-฿375
        $pantsMaleRP = [
            $v('11×20',0),$v('12×21',8),$v('12×22',13),$v('13×22',18),$v('13×23',23),
            $v('14×23',28),$v('14×24',33),$v('15×24',38),$v('15×25',43),$v('16×25',50),
            $v('16×26',57),$v('17×26',65),$v('17×27',72),$v('18×27',80),$v('18×28',87),
            $v('19×28',95),$v('19×29',102),$v('20×29',110),$v('20×30',115),$v('20×31',120),
            $v('21×31',127),$v('21×32',132),$v('21×34',140),$v('22×32',150),$v('22×34',160),
            $v('22×36',180),
        ];

        // กางเกงอนุบาลรังดุมเอว ราชพฤกษ์ (ชาย) S-3XL → ฿159-฿209
        $pantsNurseryRP = [$v('S (18")',0),$v('M (19")',0),$v('L (20")',0),$v('XL (21")',50),$v('XXL (22")',50),$v('3XL (23")',50)];

        // กางเกงนักเรียนชาย Coolkidz 13×23–21×34 → ฿233-฿359
        $pantsMaleCK = [
            $v('13×23',0),$v('14×24',13),$v('15×25',26),$v('16×26',39),$v('17×27',52),
            $v('18×28',65),$v('19×29',78),$v('20×30',91),$v('20×31',98),$v('21×31',104),
            $v('21×32',110),$v('21×34',126),
        ];

        // กางเกงนักเรียนชาย BigSave 12×22–20×30 → ฿125-฿175
        $pantsMaleBS = [
            $v('12×22',0),$v('13×23',6),$v('14×24',13),$v('15×25',19),
            $v('16×26',25),$v('17×27',31),$v('18×28',38),$v('19×29',44),$v('20×30',50),
        ];

        // กางเกงอนุบาลรังดุม BigSave S-L → ฿110 flat
        $pantsNurseryBS = [$vf('S (11×20")'),$vf('M (12×21")'),$vf('L (12×22")')];

        // กระโปรงจีบ 6 จีบ ราชพฤกษ์ 16×20–28×31 → ฿195-฿385
        $skirt6RP = [
            $v('16×20',0),$v('16×21',5),$v('17×21',10),$v('17×22',15),$v('18×22',20),
            $v('18×23',25),$v('18×24',30),$v('19×24',35),$v('19×25',40),$v('20×24',45),
            $v('20×25',50),$v('20×26',55),$v('21×26',65),$v('21×27',70),$v('22×26',75),
            $v('22×27',80),$v('22×28',85),$v('23×27',90),$v('23×28',95),$v('24×28',100),
            $v('24×29',105),$v('25×28',110),$v('25×29',115),$v('25×30',120),$v('26×28',130),
            $v('26×30',140),$v('26×32',150),$v('27×30',160),$v('27×32',170),$v('28×30',180),
            $v('28×31',190),
        ];

        // กระโปรงจีบ 6 จีบ Coolkidz 16×20–25×32 → ฿296-฿350
        $skirt6CK = [
            $v('16×20',0),$v('16×23',4),$v('18×21',8),$v('18×22',12),$v('18×24',16),
            $v('20×26',20),$v('22×25',24),$v('22×26',28),$v('23×27',32),$v('24×27',36),
            $v('25×28',40),$v('25×29',44),$v('25×30',46),$v('25×31',50),$v('25×32',54),
        ];

        // กระโปรงจีบรอบ Coolkidz 16×20–25×32 → ฿233-฿278
        $skirtRoundCK = [
            $v('16×20',0),$v('16×23',4),$v('18×21',7),$v('18×22',10),$v('18×24',13),
            $v('20×22',16),$v('20×24',19),$v('20×25',22),$v('22×24',25),$v('22×25',28),
            $v('22×26',31),$v('23×27',34),$v('24×27',37),$v('25×28',40),$v('25×29',42),
            $v('25×30',44),$v('25×31',45),$v('25×32',45),
        ];

        // กระโปรง 6 จีบ BigSave 16×22–25×29 → ฿175 flat
        $skirt6BS = [$vf('16×22'),$vf('16×23'),$vf('18×24'),$vf('18×25'),$vf('20×26'),$vf('23×27'),$vf('25×28'),$vf('25×29')];

        // กระโปรงจีบรอบ BigSave 16×21–18×24 → ฿125 flat
        $skirtRoundBS = [$vf('16×21'),$vf('16×22'),$vf('16×23'),$vf('18×24')];

        // กระโปรงอนุบาลรังดุมเอว ราชพฤกษ์ S-3XL → ฿159-฿209
        $skirtNurseryRP = [$v('S (27")',0),$v('M (29")',0),$v('L (31")',0),$v('XL (32")',50),$v('XXL (34")',50),$v('3XL (36")',50)];

        // กระโปรงอนุบาลรังดุมเอว Coolkidz S-XXL → ฿188-฿215
        $skirtNurseryCK = [$v('S (27")',0),$v('M (29")',0),$v('L (31")',0),$v('XL (32")',27),$v('XXL (34")',27)];

        // กระโปรงอนุบาลรังดุม BigSave 11×20–22×36 → ฿110 flat (หลายขนาด)
        $skirtNurseryBS = [
            $vf('11×20'),$vf('12×21'),$vf('12×22'),$vf('13×23'),$vf('14×24'),
            $vf('15×25'),$vf('16×26'),$vf('17×27'),$vf('18×28'),$vf('19×29'),
            $vf('20×30'),$vf('21×31'),$vf('21×32'),$vf('22×34'),
        ];

        // กระโปรงทรงแคบ ชัยพฤกษ์ 22×24–26×32 → ฿185-฿245
        $skirtSlimCP = [
            $v('22×24',0),$v('23×25',10),$v('24×26',20),$v('25×27',30),
            $v('26×28',40),$v('26×30',50),$v('26×32',60),
        ];

        // กางเกงวอร์มขายาว KGM 3-3XL → ฿195-฿265
        $warmPantsLong = [
            $vf('3'),$vf('4'),$vf('5'),$vf('6'),$vf('7'),$vf('S'),$vf('M'),$vf('L'),
            $v('XL',35),$v('XXL',35),$v('3XL',70),
        ];

        // กางเกงวอร์มขาสั้น KGM 3-XL → ฿100-฿120
        $warmPantsShort = [
            $vf('3'),$vf('4'),$vf('5'),$vf('6'),$vf('7'),$vf('S'),$vf('M'),
            $v('L',10),$v('XL',20),
        ];

        // เสื้อกีฬาโปโล (สีล้วน ป.4-6) 5-3XL → ฿170-฿240
        $sportShirtP46 = [
            $vf('5'),$vf('6'),$vf('7'),$vf('S'),$vf('M'),$vf('L'),$vf('XL'),
            $v('XXL',70),$v('3XL',70),
        ];

        // เสื้อกีฬาโปโล (แถบขาว อนุบาล-ป.3) 3-XL → ฿170 flat
        $sportShirtP13 = [$vf('3'),$vf('4'),$vf('5'),$vf('6'),$vf('7'),$vf('S'),$vf('M'),$vf('L'),$vf('XL')];

        // เข็มขัดนักเรียน / ลูกเสือ 3.5cm ฿70 / 4cm ฿75
        $belt = [$v('3.5cm/32"',0),$v('3.5cm/34"',0),$v('3.5cm/36"',0),$v('4cm/38"',5),$v('4cm/40"',5),$v('4cm/42"',5),$v('4cm/44"',5)];

        // เข็มขัดยุวกาชาด ฿95 flat
        $beltCadet = [$vf('32"'),$vf('36"'),$vf('38"'),$vf('40"'),$vf('42"'),$vf('44"')];

        // ชุดอุปกรณ์ยุวกาชาด size 6,7,8 → ฿139 flat
        $equipCadet = [$vf('6'),$vf('7'),$vf('8')];

        // ชุดอุปกรณ์ลูกเสือสามัญ S-XL → ฿139 flat
        $equipScout = [$vf('S'),$vf('M'),$vf('L'),$vf('XL')];

        // ═══════════════════════════════════════════════════════════════════════
        // ── โรงเรียนอนุบาลศรีสะเกษ ───────────────────────────────────────────
        // ═══════════════════════════════════════════════════════════════════════

        // ── ชุดนักเรียน ระดับอนุบาล ────────────────────────────────────────
        $make(['name'=>'กางเกงอนุบาลรังดุมเอว (อนุบาลศรีสะเกษ) เด็กชาย – สีกรมอ่อน',
            'cat'=>$cat('nursery-si-saket'),'brand'=>$rp,'price'=>159,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2017/05/3-1-510x510.jpg','variants'=>$pantsNurseryRP]);

        $make(['name'=>'กระโปรงอนุบาลรังดุมเอว (อนุบาลศรีสะเกษ) เด็กหญิง – สีกรมอ่อน',
            'cat'=>$cat('nursery-si-saket'),'brand'=>$rp,'price'=>159,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% ผิวสัมผัสนุ่มลื่น',
            'img'=>'2017/05/01-กระโปรงอนุบาลรังดุม-สีกรม.jpg','bestseller'=>true,'variants'=>$skirtNurseryRP]);

        $make(['name'=>'เสื้ออนุบาลปกบัวดุมเอว (อนุบาลศรีสะเกษ) เด็กหญิง – สีขาว',
            'cat'=>$cat('nursery-si-saket'),'brand'=>$rp,'price'=>139,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% ผิวสัมผัสนุ่มลื่น Non-azo dyes',
            'img'=>'2020/04/01.jpg','variants'=>$shirtNurseryRP]);

        // ── ชุดนักเรียน ระดับประถม ─────────────────────────────────────────
        $make(['name'=>'เสื้อเชิ้ตนักเรียนชาย (อนุบาลศรีสะเกษ) – สีขาว',
            'cat'=>$cat('primary-si-saket'),'brand'=>$rp,'price'=>169,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2020/04/เสื้อเชิ้ตนักเรียนชาย.jpg','featured'=>true,'bestseller'=>true,'variants'=>$shirtMaleRP]);

        $make(['name'=>'เสื้อปกบัวโปโลแหลม (อนุบาลศรีสะเกษ) เด็กหญิง – สีขาว',
            'cat'=>$cat('primary-si-saket'),'brand'=>$rp,'price'=>169,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% ผิวสัมผัสนุ่มลื่น Non-azo dyes',
            'img'=>'2020/04/เสื้ออนุบาลปกบัวแหลม.jpg','featured'=>true,'bestseller'=>true,'variants'=>$shirtFemaleRP]);

        $make(['name'=>'กางเกงนักเรียนชาย (อนุบาลศรีสะเกษ) – สีกรมท่า',
            'cat'=>$cat('primary-si-saket'),'brand'=>$rp,'price'=>195,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2019/09/10-กางเกงนักเรียน-สีกรมท่า.jpg','featured'=>true,'bestseller'=>true,'variants'=>$pantsMaleRP]);

        $make(['name'=>'กางเกงนักเรียนชาย (อนุบาลศรีสะเกษ) – สีกากี',
            'cat'=>$cat('primary-si-saket'),'brand'=>$rp,'price'=>195,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2017/05/08-510x510.jpg','variants'=>$pantsMaleRP]);

        $make(['name'=>'กระโปรงจีบ 6 จีบ เด็กหญิง (อนุบาลศรีสะเกษ) – สีกรมอ่อน',
            'cat'=>$cat('primary-si-saket'),'brand'=>$rp,'price'=>195,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% ผิวสัมผัสนุ่มลื่น แห้งเร็ว Non-azo dyes',
            'img'=>'2017/05/06-กระโปรง6จีบ-สีกรมอ่อน.jpg','featured'=>true,'bestseller'=>true,'variants'=>$skirt6RP]);

        $make(['name'=>'โบว์สำเร็จ ป.1-3 สีกรม (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('primary-si-saket'),'price'=>30,
            'desc'=>'โบว์สำเร็จรูปสีกรม สำหรับ ป.1-3',
            'img'=>'2020/04/โบร์สีกรม.jpg','bestseller'=>true,'variants'=>$noSize]);

        $make(['name'=>'โบว์สำเร็จ ป.4-6 สีแดง (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('primary-si-saket'),'price'=>30,
            'desc'=>'โบว์สำเร็จรูปสีแดง สำหรับ ป.4-6',
            'img'=>'2020/04/โบร์แดง.jpg','variants'=>$noSize]);

        // ── ชุดกีฬา ระดับอนุบาล ────────────────────────────────────────────
        $make(['name'=>'กางเกงวอร์มขาสั้น สีขาว KG Sports (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('sport-nur-si-saket'),'brand'=>$kgm,'price'=>100,
            'desc'=>'กางเกงวอร์มขาสั้น สีขาว สำหรับนักเรียนอนุบาล',
            'img'=>'2020/04/กางเกงวอร์มขาสั้น2-1.jpg','variants'=>$warmPantsShort]);

        foreach ([
            ['สีชมพู','เสื้อกีฬาอนุบาล_แถบขาว_สีชมพู.jpg'],
            ['สีทอง', 'เสื้อกีฬาอนุบาล_แถบขาว_สีทอง.jpg'],
            ['สีน้ำเงิน','เสื้อกีฬาอนุบาล_แถบขาว_สีน้ำเงิน.jpg'],
            ['สีฟ้า',  'เสื้อกีฬาอนุบาล_แถบขาว_สีฟ้า.jpg'],
            ['สีม่วง', null],
            ['สีส้ม',  'เสื้อกีฬาอนุบาล_แถบขาว_ส้ม.jpg'],
            ['สีเขียว','เสื้อกีฬาอนุบาล_แถบขาว_สีเขียว.jpg'],
            ['สีเทา',  'เสื้อกีฬาอนุบาล_แถบขาว_สีเทา.jpg'],
            ['สีเหลือง','เสื้อกีฬาอนุบาล_แถบขาว_สีเหลือง.jpg'],
            ['สีแดง',  null],
        ] as [$color, $imgFile]) {
            $entry = ['name'=>"เสื้อกีฬาโปโล แถบขาว {$color} อนุบาล-ป.3 (อนุบาลศรีสะเกษ)",
                'cat'=>$cat('sport-nur-si-saket'),'brand'=>$kgm,'price'=>170,
                'desc'=>"เสื้อกีฬาโปโลแถบขาว {$color} สำหรับนักเรียนระดับอนุบาล-ป.3",
                'variants'=>$sportShirtP13];
            if ($imgFile) $entry['img'] = "2020/04/{$imgFile}";
            $make($entry);
        }

        // ── ชุดกีฬา ระดับประถม ป.1-ป.3 ────────────────────────────────────
        $make(['name'=>'กางเกงวอร์มขายาว สีกรม KG Sports ป.1-ป.3 (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('sport-p13-si-saket'),'brand'=>$kgm,'price'=>195,
            'desc'=>'กางเกงวอร์มขายาว สีกรม มีแถบ KG Sports สำหรับ ป.1-ป.3',
            'img'=>'2020/04/กางเกงวอร์มขายาว.jpg','variants'=>$warmPantsLong]);

        // ── ชุดกีฬา ระดับประถม ป.4-ป.6 ────────────────────────────────────
        foreach ([
            ['สีชมพู','04สีชมพู.jpg'],
            ['สีฟ้า',  '03สีฟ้า.jpg'],
            ['สีม่วง', '06สีม่วง.jpg'],
            ['สีส้ม',  '02สีส้ม.jpg'],
            ['สีเหลือง','01สีเหลือง.jpg'],
            ['สีแดง',  '03สีแดง.jpg'],
        ] as [$color, $imgFile]) {
            $make(['name'=>"เสื้อกีฬาโปโล {$color} ป.4-ป.6 (อนุบาลศรีสะเกษ)",
                'cat'=>$cat('sport-p46-si-saket'),'brand'=>$kgm,'price'=>170,
                'desc'=>"เสื้อกีฬาโปโลสีล้วน {$color} สำหรับ ป.4-ป.6",
                'img'=>"2020/05/{$imgFile}",'variants'=>$sportShirtP46]);
        }

        // ── ชุดลูกเสือ ─────────────────────────────────────────────────────
        $make(['name'=>'เสื้อลูกเสือ ตราราชพฤกษ์ (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('scout-si-saket'),'brand'=>$rp,'price'=>260,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2020/04/เสื้อลูกเสือ2.jpg','variants'=>$shirtScoutRP]);

        $make(['name'=>'กางเกงนักเรียนชาย สีกากี (ชุดลูกเสือ) (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('scout-si-saket'),'brand'=>$rp,'price'=>195,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2017/05/08-510x510.jpg','variants'=>$pantsMaleRP]);

        $make(['name'=>'ชุดอุปกรณ์ลูกเสือสามัญ (อนุบาลศรีสะเกษ) 4 ชิ้น',
            'cat'=>$cat('scout-si-saket'),'price'=>139,
            'desc'=>'หมวกปีก + ผ้าพันคอ + วอกเกิ้ล + อาร์ม รร. (4 ชิ้น)',
            'img'=>'2020/04/รวมเครื่องแบบลูกเสือสามัญ-510x510.jpg','variants'=>$equipScout]);

        $make(['name'=>'ชุดอุปกรณ์ลูกเสือสำรอง (อนุบาลศรีสะเกษ) 5 ชิ้น',
            'cat'=>$cat('scout-si-saket'),'price'=>119,
            'desc'=>'หมวก + ผ้าพันคอ + วอกเกิ้ล + อาร์ม อบ.ศก.หน้าเสือ + หน้าเสือติดอก (5 ชิ้น)',
            'img'=>'2020/04/อุปกรณ์ลูกเสือ.jpg','variants'=>$noSize]);

        $make(['name'=>'เข็มขัดลูกเสือสำรอง+สามัญ (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('scout-si-saket'),'price'=>70,
            'desc'=>'เข็มขัดลูกเสือสำรอง 3.5 cm / ลูกเสือสามัญ 4 cm',
            'img'=>'2020/04/เข็มขัดลูกเสือ.jpg','variants'=>$belt]);

        // ── ชุดยุวกาชาด ────────────────────────────────────────────────────
        $make(['name'=>'เสื้อยุวกาชาด ตราราชพฤกษ์ (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('cadet-si-saket'),'brand'=>$rp,'price'=>260,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2020/04/เสื้อยุวกาชาด.jpg','variants'=>$shirtScoutRP]);

        $make(['name'=>'กระโปรงยุวกาชาด เด็กหญิง (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('cadet-si-saket'),'brand'=>$rp,'price'=>195,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% Non-azo dyes',
            'img'=>'2020/04/กระโปรงยุวกาชาด3.jpg','variants'=>$skirt6RP]);

        $make(['name'=>'ชุดอุปกรณ์ยุวกาชาด (อนุบาลศรีสะเกษ) 5 ชิ้น',
            'cat'=>$cat('cadet-si-saket'),'price'=>139,
            'desc'=>'ผ้าพันคอ + อาร์ม รร. + เข็มติดอก + เข็มติดหมวก + หมวก (5 ชิ้น)',
            'img'=>'2020/04/ชุดอุปกรณ์ยุวกาชาด.jpg','variants'=>$equipCadet]);

        $make(['name'=>'เข็มขัดยุวกาชาด สีดำหัวเงิน (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('cadet-si-saket'),'price'=>95,
            'desc'=>'เข็มขัดยุวกาชาดสีดำหัวเงิน ยาว 32-44 นิ้ว',
            'img'=>'2020/04/เข็มขัดยุวกาชาด.jpg','variants'=>$beltCadet]);

        // ── อุปกรณ์อื่นๆ ────────────────────────────────────────────────────
        $make(['name'=>'กระเป๋าเป้ ประถม สีกรมท่า (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('equip-si-saket'),'price'=>325,
            'desc'=>'กระเป๋าเป้สำหรับนักเรียนประถม สีกรมท่า',
            'img'=>'2020/04/กระเป๋าประถม.jpg','variants'=>$noSize]);

        $make(['name'=>'กระเป๋าเป้ อนุบาล สีชมพูแต่งฟ้า (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('equip-si-saket'),'price'=>325,
            'desc'=>'กระเป๋าเป้สำหรับเด็กอนุบาล สีชมพูแต่งฟ้า',
            'img'=>'2020/04/กระเป๋าเป้อนุบาล.jpg','variants'=>$noSize]);

        $make(['name'=>'เข็มขัดนักเรียน สีดำหัวเงิน (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('equip-si-saket'),'price'=>70,
            'desc'=>'เข็มขัดสีดำหัวเงิน 3.5 cm / 4 cm ยาว 32-44 นิ้ว',
            'img'=>'2020/04/เข็มขัดหัวเงิน.jpg','variants'=>$belt]);

        $make(['name'=>'ที่นอน อนุบาล สีน้ำเงิน (อนุบาลศรีสะเกษ)',
            'cat'=>$cat('equip-si-saket'),'price'=>550,
            'desc'=>'ที่นอน + หมอน + ผ้าปู + ถุง (ชุดครบ 4 ชิ้น)',
            'img'=>'2020/04/ชุดที่นอน.jpg','variants'=>$noSize]);

        // ═══════════════════════════════════════════════════════════════════════
        // ── โรงเรียนอนุบาลวัดพระโต ───────────────────────────────────────────
        // ═══════════════════════════════════════════════════════════════════════

        // ── ชุดนักเรียน ระดับอนุบาล ────────────────────────────────────────
        $make(['name'=>'กางเกงอนุบาลรังดุมเอว (อนุบาลวัดพระโต) เด็กชาย – สีกรมอ่อน',
            'cat'=>$cat('nursery-phra-to'),'brand'=>$rp,'price'=>159,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2017/05/3-1-510x510.jpg','variants'=>$pantsNurseryRP]);

        $make(['name'=>'กระโปรงอนุบาลรังดุมเอว (อนุบาลวัดพระโต) เด็กหญิง – สีกรมอ่อน',
            'cat'=>$cat('nursery-phra-to'),'brand'=>$rp,'price'=>159,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% ผิวสัมผัสนุ่มลื่น',
            'img'=>'2017/05/01-กระโปรงอนุบาลรังดุม-สีกรม.jpg','variants'=>$skirtNurseryRP]);

        $make(['name'=>'เสื้ออนุบาลปกบัวดุมเอว (อนุบาลวัดพระโต) เด็กชาย – สีขาว',
            'cat'=>$cat('nursery-phra-to'),'brand'=>$rp,'price'=>139,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2020/04/เสื้ออนุบาลรังดุมเอว.jpg','variants'=>$shirtNurseryRP]);

        $make(['name'=>'เสื้ออนุบาลปกบัวดุมเอว (อนุบาลวัดพระโต) เด็กหญิง – สีขาว',
            'cat'=>$cat('nursery-phra-to'),'brand'=>$rp,'price'=>139,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% ผิวสัมผัสนุ่มลื่น Non-azo dyes',
            'img'=>'2020/04/เสื้ออนุบาลรังดุมเอว.jpg','variants'=>$shirtNurseryRP]);

        // ── ชุดนักเรียน ระดับประถม ─────────────────────────────────────────
        $make(['name'=>'เสื้อเชิ้ตนักเรียนชาย (อนุบาลวัดพระโต) – สีขาว',
            'cat'=>$cat('primary-phra-to'),'brand'=>$rp,'price'=>169,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2020/04/เสื้อเชิ้ตนักเรียนชาย-1.jpg','featured'=>true,'bestseller'=>true,'variants'=>$shirtMaleRP]);

        $make(['name'=>'เสื้อปกบัวโปโลแหลม (อนุบาลวัดพระโต) เด็กหญิง – สีขาว',
            'cat'=>$cat('primary-phra-to'),'brand'=>$rp,'price'=>169,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% ผิวสัมผัสนุ่มลื่น Non-azo dyes',
            'img'=>'2020/04/เสื้ออนุบาลปกบัวแหลม-1.jpg','featured'=>true,'variants'=>$shirtFemaleRP]);

        $make(['name'=>'กางเกงนักเรียนชาย (อนุบาลวัดพระโต) – สีกรมเข้ม',
            'cat'=>$cat('primary-phra-to'),'brand'=>$rp,'price'=>195,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2019/09/10-กางเกงนักเรียน-สีกรมท่า.jpg','variants'=>$pantsMaleRP]);

        $make(['name'=>'กระโปรงจีบ 6 จีบ เด็กหญิง (อนุบาลวัดพระโต) – สีกรมอ่อน',
            'cat'=>$cat('primary-phra-to'),'brand'=>$rp,'price'=>195,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% ผิวสัมผัสนุ่มลื่น Non-azo dyes',
            'img'=>'2017/05/06-กระโปรง6จีบ-สีกรมอ่อน.jpg','bestseller'=>true,'variants'=>$skirt6RP]);

        $make(['name'=>'เสื้อยุวกาชาด ตราราชพฤกษ์ (อนุบาลวัดพระโต)',
            'cat'=>$cat('primary-phra-to'),'brand'=>$rp,'price'=>260,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2020/04/เสื้อยุวกาชาด.jpg','variants'=>$shirtScoutRP]);

        $make(['name'=>'กระโปรงยุวกาชาด เด็กหญิง (อนุบาลวัดพระโต)',
            'cat'=>$cat('primary-phra-to'),'brand'=>$rp,'price'=>195,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% Non-azo dyes',
            'img'=>'2020/04/กระโปรงยุวกาชาด3.jpg','variants'=>$skirt6RP]);

        $make(['name'=>'โบว์สำเร็จ ป.1-3 สีแดง (อนุบาลวัดพระโต)',
            'cat'=>$cat('primary-phra-to'),'price'=>30,
            'desc'=>'โบว์สำเร็จรูปสีแดง สำหรับ ป.1-3',
            'img'=>'2020/04/โบร์แดง.jpg','variants'=>$noSize]);

        $make(['name'=>'โบว์สำเร็จ ป.4-6 สีเหลือง (อนุบาลวัดพระโต)',
            'cat'=>$cat('primary-phra-to'),'price'=>30,
            'desc'=>'โบว์สำเร็จรูปสีเหลือง สำหรับ ป.4-6',
            'img'=>'2020/04/โบร์เหลือง.jpg','variants'=>$noSize]);

        // ── ชุดกีฬาและอุปกรณ์ ──────────────────────────────────────────────
        $make(['name'=>'กระเป๋าเป้ ประถม (อนุบาลวัดพระโต) – สีกรมท่า',
            'cat'=>$cat('sport-phra-to'),'price'=>325,
            'desc'=>'กระเป๋าเป้นักเรียนประถม สีกรมท่า',
            'img'=>'2020/04/กระเป๋าประถม.jpg','featured'=>true,'variants'=>$noSize]);

        $make(['name'=>'กระเป๋าเป้ มัธยม (อนุบาลวัดพระโต) – สีกรมท่า',
            'cat'=>$cat('sport-phra-to'),'price'=>325,
            'desc'=>'กระเป๋าเป้นักเรียนมัธยม สีกรมท่า',
            'variants'=>$noSize]);

        $make(['name'=>'กระเป๋าเป้ อนุบาล (อนุบาลวัดพระโต) – สีแดง/ขาว',
            'cat'=>$cat('sport-phra-to'),'price'=>250,
            'desc'=>'กระเป๋าเป้อนุบาล สีแดง/ขาว',
            'variants'=>$noSize]);

        $make(['name'=>'ชุดอุปกรณ์ยุวกาชาด (อนุบาลวัดพระโต) 5 ชิ้น',
            'cat'=>$cat('sport-phra-to'),'price'=>139,
            'desc'=>'ผ้าพันคอ + อาร์ม รร. + เข็มติดอก + เข็มติดหมวก + หมวก (5 ชิ้น)',
            'img'=>'2020/04/ยุวกาชาดวัดพระโต.jpg','variants'=>$equipCadet]);

        $make(['name'=>'ชุดอุปกรณ์ลูกเสือสามัญ (อนุบาลวัดพระโต) 4 ชิ้น',
            'cat'=>$cat('sport-phra-to'),'price'=>139,
            'desc'=>'หมวกปีก + ผ้าพันคอ + วอกเกิ้ล + อาร์ม รร. (4 ชิ้น)',
            'img'=>'2020/04/รวมเครื่องแบบลูกเสือสามัญjpg.jpg','variants'=>$equipScout]);

        $make(['name'=>'เข็มขัดนักเรียน สีดำหัวเงิน (อนุบาลวัดพระโต)',
            'cat'=>$cat('sport-phra-to'),'price'=>70,
            'desc'=>'เข็มขัดสีดำหัวเงิน 3.5 cm / 4 cm ยาว 32-44 นิ้ว',
            'img'=>'2020/04/เข็มขัดหัวเงิน.jpg','variants'=>$belt]);

        $make(['name'=>'เข็มขัดยุวกาชาด สีดำหัวเงิน (อนุบาลวัดพระโต)',
            'cat'=>$cat('sport-phra-to'),'price'=>95,
            'desc'=>'เข็มขัดยุวกาชาดสีดำหัวเงิน ยาว 32-44 นิ้ว',
            'img'=>'2020/04/เข็มขัดยุวกาชาด.jpg','variants'=>$beltCadet]);

        $make(['name'=>'เข็มขัดลูกเสือสำรอง+สามัญ (อนุบาลวัดพระโต)',
            'cat'=>$cat('sport-phra-to'),'price'=>70,
            'desc'=>'เข็มขัดลูกเสือ 3.5 cm / 4 cm ยาว 32-44 นิ้ว',
            'img'=>'2020/04/เข็มขัดลูกเสือ.jpg','variants'=>$belt]);

        // ═══════════════════════════════════════════════════════════════════════
        // ── ชุดนักเรียนตราคูลคิดส์ ───────────────────────────────────────────
        // ═══════════════════════════════════════════════════════════════════════
        $catCK = $cat('cat-coolkidz');

        $make(['name'=>'เสื้อเชิ๊ต Coolkidz นักเรียนชาย – สีขาว',
            'cat'=>$catCK,'brand'=>$ck,'price'=>206,'featured'=>true,'bestseller'=>true,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2019/10/7-510x510.jpg','variants'=>$shirtMaleCK]);

        $make(['name'=>'เสื้อปกบัวโปโลกลม Coolkidz เด็กหญิง – สีขาว',
            'cat'=>$catCK,'brand'=>$ck,'price'=>206,'featured'=>true,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2019/10/6-510x510.jpg','variants'=>$shirtFemaleCK]);

        $make(['name'=>'เสื้อปกบัวผ่าตลอด เด็กหญิง Coolkidz – สีขาว',
            'cat'=>$catCK,'brand'=>$ck,'price'=>206,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% ผิวสัมผัสนุ่มลื่น Non-azo dyes',
            'img'=>'2019/10/5.jpg','variants'=>$shirtFemaleCK]);

        $make(['name'=>'เสื้อปกทหารเรือ Coolkidz เด็กหญิง – สีขาว',
            'cat'=>$catCK,'brand'=>$ck,'price'=>224,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2019/10/13-เสื้อปกทหารเรือ-510x509.jpg','variants'=>$shirtFemCKLg]);

        $make(['name'=>'เสื้อเตรียมแขนพอง Coolkidz นักเรียนหญิง – สีขาว',
            'cat'=>$catCK,'brand'=>$ck,'price'=>224,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% ผิวสัมผัสนุ่มลื่น Non-azo dyes',
            'img'=>'2019/10/1-510x510.jpg','variants'=>$shirtFemCKLg]);

        $make(['name'=>'เสื้ออนุบาลปกบัวดุมเอว Coolkidz เด็กหญิง – สีขาว',
            'cat'=>$catCK,'brand'=>$ck,'price'=>170,'new'=>true,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% ผิวสัมผัสนุ่มลื่น Non-azo dyes',
            'img'=>'2019/10/3-510x510.jpg','variants'=>$shirtNurseryCK]);

        $make(['name'=>'เสื้ออนุบาลฮาวายเอวจั๊ม Coolkidz เด็กชาย – สีขาว',
            'cat'=>$catCK,'brand'=>$ck,'price'=>170,
            'desc'=>'ผ้าทีซี (TC) โพลีเอสเตอร์ 65% ฝ้าย 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2019/10/4-510x510.jpg','variants'=>$shirtNurseryBoyCK]);

        $make(['name'=>'กางเกงนักเรียนชาย Coolkidz – สีกรมท่า',
            'cat'=>$catCK,'brand'=>$ck,'price'=>233,'featured'=>true,'bestseller'=>true,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% ด้ายเส้นคู่ Non-azo dyes',
            'img'=>'2019/10/9-510x510.jpg','variants'=>$pantsMaleCK]);

        $make(['name'=>'กางเกงนักเรียนชาย Coolkidz – สีกากี',
            'cat'=>$catCK,'brand'=>$ck,'price'=>233,'featured'=>true,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2019/10/11.jpg','variants'=>$pantsMaleCK]);

        $make(['name'=>'กางเกงนักเรียนชาย Coolkidz – สีดำ',
            'cat'=>$catCK,'brand'=>$ck,'price'=>233,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2019/10/10-510x510.jpg','variants'=>$pantsMaleCK]);

        $make(['name'=>'กระโปรงจีบ 6 จีบ เด็กหญิง Coolkidz – สีกรมเข้ม',
            'cat'=>$catCK,'brand'=>$ck,'price'=>296,'featured'=>true,'bestseller'=>true,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% ด้ายเส้นคู่ แห้งเร็ว Non-azo dyes',
            'img'=>'2019/10/14-510x510.jpg','variants'=>$skirt6CK]);

        $make(['name'=>'กระโปรงจีบรอบ Coolkidz เด็กนักเรียนหญิง – สีกรมอ่อน',
            'cat'=>$catCK,'brand'=>$ck,'price'=>233,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'variants'=>$skirtRoundCK]);

        $make(['name'=>'กระโปรงอนุบาลรังดุมเอว Coolkidz เด็กหญิง – สีกรมอ่อน',
            'cat'=>$catCK,'brand'=>$ck,'price'=>188,'new'=>true,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2019/10/16-510x510.jpg','variants'=>$skirtNurseryCK]);

        $make(['name'=>'กระโปรงอนุบาลรังดุมเอว Coolkidz เด็กหญิง – สีแดง',
            'cat'=>$catCK,'brand'=>$ck,'price'=>188,
            'desc'=>'ผ้าทีอาร์ (TR) โพลีเอสเตอร์ 65% เรยอน 35% แห้งเร็ว Non-azo dyes',
            'img'=>'2019/10/18-510x510.jpg','variants'=>$skirtNurseryCK]);

        // ═══════════════════════════════════════════════════════════════════════
        // ── ชุดนักเรียนตราBigSave ────────────────────────────────────────────
        // ═══════════════════════════════════════════════════════════════════════
        $catBS = $cat('cat-bigsave');

        $make(['name'=>'เสื้อเชิ้ตนักเรียนชาย ตราBigSave – สีขาว',
            'cat'=>$catBS,'brand'=>$bs,'price'=>125,
            'desc'=>'ผ้า TC คุณภาพมาตรฐาน สีสันสดใส ทนทาน คงรูปทรงได้ดี รีดง่าย',
            'img'=>'2020/05/เสื้อเชิ้ต.jpg','variants'=>$shirtMaleBS]);

        $make(['name'=>'เสื้อเตรียมแขนพอง ตราBigSave – สีขาว',
            'cat'=>$catBS,'brand'=>$bs,'price'=>175,
            'desc'=>'ผ้า TC คุณภาพมาตรฐาน ผิวสัมผัสนุ่มลื่น คงรูปทรงได้ดี',
            'img'=>'2020/05/เสื้อเตรียมแขนพอง.jpg','variants'=>$shirtFemBSFlat]);

        $make(['name'=>'เสื้อปกบัวผ่าตลอด ตราBigSave – สีขาว',
            'cat'=>$catBS,'brand'=>$bs,'price'=>125,
            'desc'=>'ผ้า TC คุณภาพมาตรฐาน ราคาประหยัด ไม่เป็นขุยขน',
            'img'=>'2020/05/เสื้ออนุบาลปกบัวผ่าตลอด.jpg','variants'=>$shirtNurseryBSFlat]);

        $make(['name'=>'เสื้อปกทหารเรือ ตราBigSave – สีขาว',
            'cat'=>$catBS,'brand'=>$bs,'price'=>175,
            'desc'=>'ผ้า TC คุณภาพมาตรฐาน ผิวสัมผัสนุ่มลื่น',
            'img'=>'2020/05/เสื้อปกทหารเรือ.jpg','variants'=>$shirtNurseryBSFlat]);

        $make(['name'=>'เสื้ออนุบาลปกบัวดุมเอว ตราBigSave – สีขาว',
            'cat'=>$catBS,'brand'=>$bs,'price'=>110,
            'desc'=>'ผ้า TC ผิวสัมผัสนุ่มลื่น ราคาประหยัด Non-azo dyes',
            'img'=>'2020/05/เสื้ออนุบาลรังดุมเอว-800x800.jpg','variants'=>$shirtNurseryBSSml]);

        $make(['name'=>'เสื้ออนุบาลปกบัวโปโล ตราBigSave – สีขาว',
            'cat'=>$catBS,'brand'=>$bs,'price'=>125,
            'desc'=>'ผ้า TC คุณภาพมาตรฐาน ราคาประหยัด',
            'img'=>'2020/05/เสื้ออนุบาลปกบัวโปโล.jpg','variants'=>$shirtNurseryBSFlat]);

        $make(['name'=>'เสื้ออนุบาลฮาวายดุมเอว ตราBigSave – สีขาว',
            'cat'=>$catBS,'brand'=>$bs,'price'=>110,
            'desc'=>'ผ้า TC ผิวสัมผัสนุ่มลื่น ราคาประหยัด',
            'img'=>'2020/05/ฮาร์วายดุมเอว.jpg','variants'=>$shirtNurseryBSSml]);

        $make(['name'=>'เสื้ออนุบาลฮาวายเอวจั๊ม ตราBigSave – สีขาว',
            'cat'=>$catBS,'brand'=>$bs,'price'=>110,
            'desc'=>'ผ้า TC ผิวสัมผัสนุ่มลื่น ราคาประหยัด',
            'img'=>'2020/05/เสื้อฮาวายเอวจั้ม-1.jpg','variants'=>$shirtNurseryBSSml]);

        $make(['name'=>'กางเกงนักเรียนชาย BigSave – สีกากี',
            'cat'=>$catBS,'brand'=>$bs,'price'=>125,'featured'=>true,
            'desc'=>'ผ้า TC สีสันสดใส ทนทาน คงรูปทรง ไม่เป็นขุยขน รีดง่าย',
            'img'=>'2020/05/กางเกงกากี.jpg','variants'=>$pantsMaleBS]);

        $make(['name'=>'กางเกงอนุบาลรังดุม ตราBigSave',
            'cat'=>$catBS,'brand'=>$bs,'price'=>110,
            'desc'=>'ผ้า TC ผิวสัมผัสนุ่มลื่น ราคาประหยัด',
            'img'=>'2020/05/กางแกงอนุบาลรังดุมเอว.jpg','variants'=>$pantsNurseryBS]);

        $make(['name'=>'กางเกงอนุบาลเอวยาง ตราBigSave',
            'cat'=>$catBS,'brand'=>$bs,'price'=>110,
            'desc'=>'ผ้า TC ผิวสัมผัสนุ่มลื่น ราคาประหยัด เอวยืดหยุ่น',
            'img'=>'2020/05/กางเกงอนุบาลเอวยาง.jpg','variants'=>$pantsNurseryBS]);

        $make(['name'=>'กระโปรง 6 จีบ ตราBigSave',
            'cat'=>$catBS,'brand'=>$bs,'price'=>175,'featured'=>true,
            'desc'=>'ผ้า TC สีสันสดใส ทนทาน คงรูปทรง ไม่เป็นขุยขน รีดง่าย',
            'img'=>'2020/05/กระโปรง6จีบ.jpg','variants'=>$skirt6BS]);

        $make(['name'=>'กระโปรงจีบรอบ ตราBigSave',
            'cat'=>$catBS,'brand'=>$bs,'price'=>125,
            'desc'=>'ผ้า TC สีสันสดใส ทนทาน คงรูปทรง ไม่เป็นขุยขน รีดง่าย',
            'img'=>'2020/05/กระโปรงจีบรอบ.jpg','variants'=>$skirtRoundBS]);

        $make(['name'=>'กระโปรงอนุบาลรังดุม ตราBigSave',
            'cat'=>$catBS,'brand'=>$bs,'price'=>110,
            'desc'=>'ผ้า TC สีสันสดใส ทนทาน ผิวสัมผัสนุ่มลื่น',
            'img'=>'2020/05/กระโปรงรังดุม.jpg','variants'=>$skirtNurseryBS]);

        // ═══════════════════════════════════════════════════════════════════════
        // ── ชุดนักเรียนชัยพฤกษ์ ──────────────────────────────────────────────
        // ═══════════════════════════════════════════════════════════════════════
        $catCP = $cat('cat-chaiyapruek');

        $make(['name'=>'กระโปรงนักเรียนทรงแคบ ผ้ามันดีวาย (สีกรมเข้ม) ชัยพฤกษ์ รุ่นใหม่!!',
            'cat'=>$catCP,'brand'=>$cp,'price'=>185,'new'=>true,'featured'=>true,
            'desc'=>'ผ้ามันดีวาย มีกระเป๋าลับใส่โทรศัพท์ได้ ไม่มีกระเป๋าข้าง ใส่สวย ไม่อ้า สวมใส่สบาย รีดง่าย',
            'img'=>'2022/10/01-800x800.jpg','variants'=>$skirtSlimCP]);

        $make(['name'=>'กระโปรงนักเรียนทรงแคบ ผ้ามันดีวาย (สีดำ) ชัยพฤกษ์ รุ่นใหม่',
            'cat'=>$catCP,'brand'=>$cp,'price'=>215,'new'=>true,
            'desc'=>'ผ้ามันดีวาย มีกระเป๋าลับใส่โทรศัพท์ได้ ไม่มีกระเป๋าข้าง ใส่สวย ไม่อ้า รีดง่าย',
            'img'=>'2023/12/01.jpg','variants'=>$skirtSlimCP]);
    }
}
