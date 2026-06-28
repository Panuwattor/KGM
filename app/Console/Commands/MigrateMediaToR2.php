<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MigrateMediaToR2 extends Command
{
    protected $signature = 'media:migrate
        {--from=public : disk ต้นทาง (เครื่อง)}
        {--to=r2 : disk ปลายทาง (Cloudflare R2)}
        {--overwrite : อัปทับไฟล์ที่มีอยู่แล้วบนปลายทาง}
        {--execute : ลงมือย้ายจริง (ถ้าไม่ใส่จะเป็น dry-run เฉยๆ)}';

    protected $description = 'ย้ายรูป/ไฟล์อัปโหลดจาก disk เครื่องขึ้น Cloudflare R2 (default = dry-run)';

    public function handle(): int
    {
        $from = $this->option('from');
        $to = $this->option('to');
        $overwrite = (bool) $this->option('overwrite');
        $execute = (bool) $this->option('execute');

        $source = Storage::disk($from);
        $target = Storage::disk($to);

        // เช็คการเชื่อมต่อปลายทางก่อน (เขียน-อ่าน-ลบไฟล์ทดสอบ)
        $this->info("ตรวจการเชื่อมต่อ disk '{$to}' ...");
        try {
            $probe = 'healthcheck/'.uniqid('ping_').'.txt';
            $target->put($probe, 'ok');
            $target->delete($probe);
            $this->info("เชื่อมต่อ '{$to}' สำเร็จ ✓");
        } catch (\Throwable $e) {
            $this->error("เชื่อมต่อ '{$to}' ไม่ได้: ".$e->getMessage());

            return self::FAILURE;
        }

        $files = $source->allFiles();
        $total = count($files);

        if ($total === 0) {
            $this->warn("ไม่พบไฟล์ใน disk '{$from}'");

            return self::SUCCESS;
        }

        if (! $execute) {
            $this->warn("** โหมด DRY-RUN ** (ยังไม่อัปจริง — ใส่ --execute เพื่อย้ายจริง)");
        }

        $this->info("พบ {$total} ไฟล์ใน '{$from}' → จะย้ายไป '{$to}'");

        $copied = $skipped = $failed = 0;
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($files as $path) {
            // ข้ามไฟล์ระบบ/healthcheck
            if (str_starts_with($path, 'healthcheck/')) {
                $bar->advance();

                continue;
            }

            if (! $overwrite && $target->exists($path)) {
                $skipped++;
                $bar->advance();

                continue;
            }

            if ($execute) {
                try {
                    $stream = $source->readStream($path);
                    $target->writeStream($path, $stream);
                    if (is_resource($stream)) {
                        fclose($stream);
                    }
                    $copied++;
                } catch (\Throwable $e) {
                    $failed++;
                    $this->newLine();
                    $this->error("ล้มเหลว: {$path} — ".$e->getMessage());
                }
            } else {
                $copied++; // นับว่าจะย้าย (dry-run)
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $verb = $execute ? 'ย้ายแล้ว' : 'จะย้าย';
        $this->info("สรุป: {$verb} {$copied} | ข้าม(มีอยู่แล้ว) {$skipped} | ล้มเหลว {$failed}");

        if (! $execute) {
            $this->newLine();
            $this->line("รันจริงด้วย:  php artisan media:migrate --execute");
        }

        return $failed > 0 ? self::FAILURE : self::SUCCESS;
    }
}
