<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;

/**
 * สร้าง slug ที่รองรับภาษาไทย — เก็บตัวอักษรไทยไว้ตามเดิม ไม่ถอดเสียง
 *
 * ต่างจาก Str::slug() ตรงที่ Str::slug() ตัดอักขระที่ไม่ใช่ ASCII ทิ้งทั้งหมด
 * ชื่อไทยล้วนจึงได้ string ว่าง ซึ่งทำให้ route('shop.category', '') โยน
 * UrlGenerationException และพังทุกหน้าที่เรนเดอร์ layout
 */
class Slugger
{
    /**
     * normalize ข้อความเป็น slug — ตัวพิมพ์เล็ก, เว้นวรรค→'-',
     * เก็บตัวอักษร (รวมไทย) / ตัวเลข / สระ-วรรณยุกต์ / '-' / '_'
     */
    public static function normalize(string $input): string
    {
        $slug = mb_strtolower(trim($input));
        $slug = preg_replace('/\s+/u', '-', $slug);
        $slug = preg_replace('/[^\p{L}\p{N}\p{M}_-]+/u', '', $slug);
        $slug = preg_replace('/-+/u', '-', $slug);

        return trim($slug, '-_');
    }

    /**
     * สร้าง slug ที่ไม่ซ้ำกับแถวอื่นในตารางเดียวกัน
     *
     * $fallback ใช้เมื่อ normalize แล้วเหลือว่าง (เช่นชื่อเป็นอีโมจิล้วน)
     * ปล่อยเป็น null ได้ถ้าผู้เรียกจะเติม slug เองภายหลัง (เช่นรอ id หลัง insert)
     */
    public static function unique(
        string $input,
        string $table,
        ?int $ignoreId = null,
        ?string $fallback = null,
    ): ?string {
        $base = self::normalize($input);

        if ($base === '') {
            if ($fallback === null) {
                return null;
            }
            $base = $fallback;
        }

        $slug = $base;
        $counter = 2;

        while (self::exists($table, $slug, $ignoreId)) {
            $slug = $base . '-' . $counter++;
        }

        return $slug;
    }

    /** สร้าง slug ให้ model โดยอิง table ของ model นั้น */
    public static function forModel(Model $model, string $input, ?string $fallback = null): ?string
    {
        return self::unique($input, $model->getTable(), $model->getKey(), $fallback);
    }

    private static function exists(string $table, string $slug, ?int $ignoreId): bool
    {
        return \DB::table($table)
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();
    }
}
