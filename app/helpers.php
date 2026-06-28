<?php

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
