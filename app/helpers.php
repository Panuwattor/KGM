<?php

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Writer\SvgWriter;
use Illuminate\Support\Facades\Storage;

if (! function_exists('media_url')) {
    /**
     * คืน URL ของไฟล์ media จาก disk ที่ตั้งไว้ (config: filesystems.media)
     * รองรับทั้ง local (public) และ Cloudflare R2 โดยอัตโนมัติ
     */
    function media_url(?string $path): string
    {
        if (blank($path)) {
            return '';
        }

        return Storage::disk(config('filesystems.media'))->url($path);
    }
}

if (! function_exists('promptpay_payload')) {
    /**
     * สร้าง EMVCo payload string สำหรับ PromptPay QR ตามมาตรฐาน ธปท.
     * รองรับ Biller ID (15 หลัก), เลขบัตรประชาชน (13 หลัก) และเบอร์โทร
     *
     * @param  string       $target  เลขพร้อมเพย์ (Biller ID / เลขบัตร / เบอร์โทร)
     * @param  float|null   $amount  ยอดเงิน (บาท) — ถ้าระบุจะได้ Dynamic QR
     * @param  string|null  $ref1    Reference 1 (บังคับสำหรับ Bill Payment เช่น เลขออเดอร์)
     * @param  string|null  $ref2    Reference 2 (ถ้ามี)
     */
    function promptpay_payload(string $target, ?float $amount = null, ?string $ref1 = null, ?string $ref2 = null): string
    {
        // ฟังก์ชันย่อย: ประกอบ field = id + length(2 หลัก) + value
        $field = fn (string $id, string $value): string => $id.str_pad((string) mb_strlen($value), 2, '0', STR_PAD_LEFT).$value;

        $target = preg_replace('/[^0-9]/', '', $target);

        // เลือกประเภทบัญชีตามความยาว
        if (mb_strlen($target) >= 15) {
            // Biller ID (นิติบุคคล) — Tag 30 (Bill Payment)
            $merchantTag = '30';
            $aid = 'A000000677010112';
            $acc = $field('00', $aid).$field('01', $target);
            // Reference 1 บังคับตามสเปก — ถ้าไม่ส่งมาใช้ค่า default
            $cleanRef1 = strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $ref1 ?? '')) ?: 'KGM';
            $acc .= $field('02', $cleanRef1);
            if (! blank($ref2)) {
                $acc .= $field('03', strtoupper(preg_replace('/[^a-zA-Z0-9]/', '', $ref2)));
            }
        } else {
            // เบอร์โทร / เลขบัตรประชาชน — Tag 29
            $merchantTag = '29';
            $aid = 'A000000677010111';
            if (mb_strlen($target) >= 13) {
                $acc = $field('00', $aid).$field('02', $target); // เลขบัตรประชาชน
            } else {
                // เบอร์โทร → 0066 + เลข 9 หลัก (ตัด 0 ตัวหน้าออก)
                $phone = '0066'.substr($target, 1);
                $acc = $field('00', $aid).$field('01', $phone);
            }
        }

        // ลำดับ field ต้องเรียงตาม ID ตามมาตรฐาน EMVCo: 00 → 01 → merchant → 53 → 54 → 58
        $payload = $field('00', '01') // Payload Format Indicator
            .$field('01', $amount !== null ? '12' : '11') // 12 = Dynamic (มียอด), 11 = Static
            .$field($merchantTag, $acc)
            .$field('53', '764'); // สกุลเงิน THB

        if ($amount !== null) {
            $payload .= $field('54', number_format($amount, 2, '.', '')); // ยอดเงิน (ต้องอยู่ก่อน 58)
        }

        $payload .= $field('58', 'TH'); // ประเทศ

        // CRC: ต่อท้ายด้วย "6304" แล้วคำนวณ CRC16-CCITT (poly 0x1021, init 0xFFFF)
        $payload .= '63'.'04';
        $payload .= strtoupper(str_pad(dechex(promptpay_crc16($payload)), 4, '0', STR_PAD_LEFT));

        return $payload;
    }
}

if (! function_exists('promptpay_crc16')) {
    /** CRC16-CCITT (XModem) สำหรับ checksum ของ PromptPay payload */
    function promptpay_crc16(string $data): int
    {
        $crc = 0xFFFF;
        for ($i = 0; $i < strlen($data); $i++) {
            $crc ^= ord($data[$i]) << 8;
            for ($j = 0; $j < 8; $j++) {
                $crc = ($crc & 0x8000) ? (($crc << 1) ^ 0x1021) : ($crc << 1);
                $crc &= 0xFFFF;
            }
        }

        return $crc;
    }
}

if (! function_exists('promptpay_qr_svg')) {
    /**
     * คืน data URI ของ QR code (SVG) สำหรับ PromptPay — ใช้ฝังใน <img src="..."> ได้เลย
     * ใช้ SvgWriter จึงไม่ต้องพึ่ง GD/Imagick extension
     */
    function promptpay_qr_svg(string $target, ?float $amount = null, ?string $ref1 = null, int $size = 280): string
    {
        $result = (new Builder(
            writer: new SvgWriter(),
            data: promptpay_payload($target, $amount, $ref1),
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: $size,
            margin: 8,
        ))->build();

        return $result->getDataUri();
    }
}

if (! function_exists('promptpay_qr_png')) {
    /**
     * คืน data URI ของ QR code (PNG) — เหมาะกับปุ่มดาวน์โหลด/บันทึกลงอัลบั้ม
     * เพื่อเอาไปสแกนจากแกลเลอรีในแอปธนาคารได้ (ต้องมี GD extension)
     */
    function promptpay_qr_png(string $target, ?float $amount = null, ?string $ref1 = null, int $size = 600): string
    {
        return 'data:image/png;base64,'.base64_encode(promptpay_qr_png_raw($target, $amount, $ref1, $size));
    }
}

if (! function_exists('promptpay_qr_png_raw')) {
    /** คืน binary PNG ของ QR (มีโลโก้ Thai QR ตรงกลาง) — ใช้ภายในสำหรับประกอบรูป */
    function promptpay_qr_png_raw(string $target, ?float $amount = null, ?string $ref1 = null, int $size = 600): string
    {
        $logo = public_path('images/icon-thaiqr.png');

        $result = (new Builder(
            writer: new PngWriter(),
            data: promptpay_payload($target, $amount, $ref1),
            // ใช้ High เพื่อให้ยังสแกนได้แม้มีโลโก้บังตรงกลาง
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: $size,
            margin: 16,
            logoPath: is_file($logo) ? $logo : null,
            logoResizeToWidth: (int) round($size * 0.22),
            logoPunchoutBackground: true,
        ))->build();

        return $result->getString();
    }
}

if (! function_exists('promptpay_qr_poster')) {
    /**
     * ประกอบรูปแบบ "ใบพร้อมเพย์" สำหรับดาวน์โหลด:
     * โลโก้ PromptPay + QR (มีไอคอน Thai QR) + ชื่อร้าน + Biller ID  → คืน data URI (PNG)
     */
    function promptpay_qr_poster(string $target, ?float $amount = null, ?string $ref1 = null): string
    {
        $fontReg  = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf');
        $fontBold = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf');

        // ถ้าไม่มีฟอนต์/GD ไม่พร้อม ใช้ QR ธรรมดาแทน
        if (! is_file($fontReg) || ! function_exists('imagettftext')) {
            return promptpay_qr_png($target, $amount, $ref1);
        }

        $W       = 760;
        $pad     = 48;
        $logoW   = 380;
        $qrSize  = 520;

        // โหลดรูปประกอบ
        $qrImg   = imagecreatefromstring(promptpay_qr_png_raw($target, $amount, $ref1, $qrSize));
        $logoSrc = public_path('images/promt-pay-logo.jpg');
        $logoImg = is_file($logoSrc) ? imagecreatefromjpeg($logoSrc) : null;
        $logoH   = 0;
        if ($logoImg) {
            $logoH = (int) round(imagesy($logoImg) * ($logoW / imagesx($logoImg)));
        }

        // คำนวณความสูงรวม
        $name     = config('payment.promptpay_name_en') ?: $target;
        $billerId = preg_replace('/[^0-9]/', '', $target);
        $y        = $pad;
        $blockGap = 28;

        $hLogo   = $logoImg ? $logoH + $blockGap : 0;
        $hName   = 40;
        $hBiller = 34;
        $hAmount = $amount !== null ? 40 : 0;
        $H       = $pad + $hLogo + $qrSize + 24 + $hName + 8 + $hBiller + ($amount !== null ? 16 + $hAmount : 0) + $pad;

        // สร้างผืนภาพพื้นขาว + กรอบน้ำเงิน
        $canvas = imagecreatetruecolor($W, $H);
        $white  = imagecolorallocate($canvas, 255, 255, 255);
        $navy   = imagecolorallocate($canvas, 19, 58, 102);
        $gray   = imagecolorallocate($canvas, 90, 90, 90);
        imagefilledrectangle($canvas, 0, 0, $W, $H, $white);
        imagesetthickness($canvas, 6);
        imagerectangle($canvas, 10, 10, $W - 11, $H - 11, $navy);

        $centerX = fn (string $text, float $fontSize, string $font): int => (int) round(($W - (imagettfbbox($fontSize, 0, $font, $text)[2] - imagettfbbox($fontSize, 0, $font, $text)[0])) / 2);

        // โลโก้ PromptPay
        if ($logoImg) {
            imagecopyresampled($canvas, $logoImg, (int) (($W - $logoW) / 2), $y, 0, 0, $logoW, $logoH, imagesx($logoImg), imagesy($logoImg));
            $y += $logoH + $blockGap;
        }

        // QR
        imagecopyresampled($canvas, $qrImg, (int) (($W - $qrSize) / 2), $y, 0, 0, $qrSize, $qrSize, imagesx($qrImg), imagesy($qrImg));
        $y += $qrSize + 24;

        // ชื่อร้าน (ตัวหนา)
        imagettftext($canvas, 26, 0, $centerX($name, 26, $fontBold), $y + 26, $navy, $fontBold, $name);
        $y += $hName + 8;

        // Biller ID
        $billerText = 'Biller ID : '.$billerId;
        imagettftext($canvas, 20, 0, $centerX($billerText, 20, $fontReg), $y + 22, $gray, $fontReg, $billerText);
        $y += $hBiller;

        // ยอดเงิน (ถ้ามี)
        if ($amount !== null) {
            $amountText = 'Amount : '.number_format($amount, 2).' THB';
            $y += 16;
            imagettftext($canvas, 24, 0, $centerX($amountText, 24, $fontBold), $y + 26, $navy, $fontBold, $amountText);
        }

        ob_start();
        imagepng($canvas);
        $png = ob_get_clean();

        imagedestroy($canvas);
        imagedestroy($qrImg);
        if ($logoImg) {
            imagedestroy($logoImg);
        }

        return 'data:image/png;base64,'.base64_encode($png);
    }
}
