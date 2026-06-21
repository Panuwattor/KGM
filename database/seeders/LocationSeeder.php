<?php

namespace Database\Seeders;

use App\Models\Districts;
use App\Models\Provinces;
use App\Models\Subdistricts;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * นำเข้าข้อมูลจังหวัด → อำเภอ → ตำบล จากไฟล์ SQL ใน public/data_sql
     * Seed ครั้งเดียวพอ ถ้ามีข้อมูลอยู่แล้วจะข้าม (ไม่ seed ซ้ำ)
     */
    public function run(): void
    {
        // มีข้อมูลครบทั้ง 3 ตารางแล้วก็ข้าม
        if (Provinces::count() > 0 && Districts::count() > 0 && Subdistricts::count() > 0) {
            $this->command?->info('Location data already seeded — skipped.');
            return;
        }

        // นำเข้าตามลำดับ จังหวัด → อำเภอ → ตำบล (เพราะมี foreign key)
        foreach (['provinces', 'districts', 'subdistricts'] as $name) {
            $path = public_path("data_sql/{$name}.sql");

            if (! is_file($path)) {
                $this->command?->warn("ไม่พบไฟล์: {$path} — ข้าม {$name}");
                continue;
            }

            DB::unprepared(file_get_contents($path));
            $this->command?->info("Seeded {$name} จาก {$name}.sql");
        }
    }
}
