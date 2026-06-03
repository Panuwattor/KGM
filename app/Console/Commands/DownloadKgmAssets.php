<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DownloadKgmAssets extends Command
{
    protected $signature = 'kgm:download-assets {--force : Re-download even if file exists}';
    protected $description = 'Download all KGM store images to public/kgm-assets/ before seeding.';

    private string $base = 'https://store.kgm.co.th/wp-content/uploads';

    /** paths relative to $base, will be stored under public/kgm-assets/products/{path} */
    private array $productImages = [
        '2017/05/06-กระโปรง6จีบ-สีกรมอ่อน.jpg',
        '2017/05/01-กระโปรงอนุบาลรังดุม-สีกรม.jpg',
        '2017/05/08-510x510.jpg',
        '2017/05/3-1-510x510.jpg',
        '2019/09/10-กางเกงนักเรียน-สีกรมท่า.jpg',
        '2019/10/1-510x510.jpg',
        '2019/10/3-510x510.jpg',
        '2019/10/4-510x510.jpg',
        '2019/10/5.jpg',
        '2019/10/6-510x510.jpg',
        '2019/10/7-510x510.jpg',
        '2019/10/9-510x510.jpg',
        '2019/10/10-510x510.jpg',
        '2019/10/11.jpg',
        '2019/10/13-เสื้อปกทหารเรือ-510x509.jpg',
        '2019/10/14-510x510.jpg',
        '2019/10/16-510x510.jpg',
        '2019/10/18-510x510.jpg',
        '2020/04/01.jpg',
        '2020/04/01สีเหลือง.jpg',
        '2020/04/กระเป๋าประถม.jpg',
        '2020/04/กระเป๋าเป้อนุบาล.jpg',
        '2020/04/กระโปรงยุวกาชาด3.jpg',
        '2020/04/กางเกงวอร์มขายาว.jpg',
        '2020/04/กางเกงวอร์มขาสั้น2-1.jpg',
        '2020/04/ชุดที่นอน.jpg',
        '2020/04/ชุดอุปกรณ์ยุวกาชาด.jpg',
        '2020/04/ยุวกาชาดวัดพระโต.jpg',
        '2020/04/รวมเครื่องแบบลูกเสือสามัญ-510x510.jpg',
        '2020/04/รวมเครื่องแบบลูกเสือสามัญjpg.jpg',
        '2020/04/อุปกรณ์ลูกเสือ.jpg',
        '2020/04/เข็มขัดยุวกาชาด.jpg',
        '2020/04/เข็มขัดลูกเสือ.jpg',
        '2020/04/เข็มขัดหัวเงิน.jpg',
        '2020/04/เสื้ออนุบาลปกบัวแหลม.jpg',
        '2020/04/เสื้ออนุบาลปกบัวแหลม-1.jpg',
        '2020/04/เสื้ออนุบาลรังดุมเอว.jpg',
        '2020/04/เสื้อเชิ้ตนักเรียนชาย.jpg',
        '2020/04/เสื้อเชิ้ตนักเรียนชาย-1.jpg',
        '2020/04/เสื้อยุวกาชาด.jpg',
        '2020/04/เสื้อลูกเสือ2.jpg',
        '2020/04/เสื้อกีฬาอนุบาล_แถบขาว_สีชมพู.jpg',
        '2020/04/เสื้อกีฬาอนุบาล_แถบขาว_สีทอง.jpg',
        '2020/04/เสื้อกีฬาอนุบาล_แถบขาว_สีน้ำเงิน.jpg',
        '2020/04/เสื้อกีฬาอนุบาล_แถบขาว_สีฟ้า.jpg',
        '2020/04/เสื้อกีฬาอนุบาล_แถบขาว_ส้ม.jpg',
        '2020/04/เสื้อกีฬาอนุบาล_แถบขาว_สีเขียว.jpg',
        '2020/04/เสื้อกีฬาอนุบาล_แถบขาว_สีเทา.jpg',
        '2020/04/เสื้อกีฬาอนุบาล_แถบขาว_สีเหลือง.jpg',
        '2020/04/โบร์สีกรม.jpg',
        '2020/04/โบร์แดง.jpg',
        '2020/04/โบร์เหลือง.jpg',
        '2020/05/02สีส้ม.jpg',
        '2020/05/03สีฟ้า.jpg',
        '2020/05/03สีแดง.jpg',
        '2020/05/04สีชมพู.jpg',
        '2020/05/06สีม่วง.jpg',
        '2020/05/กระโปรง6จีบ.jpg',
        '2020/05/กระโปรงจีบรอบ.jpg',
        '2020/05/กระโปรงรังดุม.jpg',
        '2020/05/กางเกงกากี.jpg',
        '2020/05/กางแกงอนุบาลรังดุมเอว.jpg',
        '2020/05/กางเกงอนุบาลเอวยาง.jpg',
        '2020/05/เสื้อปกทหารเรือ.jpg',
        '2020/05/เสื้ออนุบาลปกบัวผ่าตลอด.jpg',
        '2020/05/เสื้ออนุบาลรังดุมเอว-800x800.jpg',
        '2020/05/เสื้ออนุบาลปกบัวโปโล.jpg',
        '2020/05/ฮาร์วายดุมเอว.jpg',
        '2020/05/เสื้อฮาวายเอวจั้ม-1.jpg',
        '2020/05/เสื้อเชิ้ต.jpg',
        '2020/05/เสื้อเตรียมแขนพอง.jpg',
        '2022/10/01-800x800.jpg',
        '2022/10/02-510x510.jpg',
        '2022/10/03-510x510.jpg',
        '2022/10/04-510x510.jpg',
        '2022/10/05-510x510.jpg',
        '2023/12/01.jpg',
        '2023/12/02.jpg',
        '2023/12/03.jpg',
        '2023/12/04.jpg',
        '2023/12/05.jpg',
        '2023/12/06.jpg',
    ];

    public function handle(): int
    {
        $force      = (bool) $this->option('force');
        $downloaded = 0;
        $skipped    = 0;
        $failed     = 0;

        $this->info('Downloading product images from store.kgm.co.th ...');
        $bar = $this->output->createProgressBar(count($this->productImages));
        $bar->start();

        foreach ($this->productImages as $relativePath) {
            $destPath = public_path("kgm-assets/products/{$relativePath}");
            $destDir  = dirname($destPath);

            if (!is_dir($destDir)) {
                mkdir($destDir, 0755, true);
            }

            if (!$force && file_exists($destPath)) {
                $skipped++;
                $bar->advance();
                continue;
            }

            // Encode each segment separately so slashes are preserved
            $encoded = implode('/', array_map('rawurlencode', explode('/', $relativePath)));
            $url     = "{$this->base}/{$encoded}";

            try {
                $ctx     = stream_context_create(['http' => ['timeout' => 20, 'ignore_errors' => true]]);
                $content = @file_get_contents($url, false, $ctx);

                $ok = $content !== false
                    && isset($http_response_header)
                    && str_contains($http_response_header[0], '200');

                if ($ok) {
                    file_put_contents($destPath, $content);
                    $downloaded++;
                } else {
                    $failed++;
                }
            } catch (\Throwable) {
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Downloaded : {$downloaded}");
        $this->warn("Failed/404 : {$failed}  (skipped — no effect on seeder)");
        $this->line("Already existed: {$skipped}");
        $this->newLine();
        $this->info('Next step: php artisan storage:link && php artisan migrate:fresh --seed');

        return self::SUCCESS;
    }
}
