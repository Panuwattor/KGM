<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class ThaiSlug
{
    private static array $map = [
        // Consonants (RTGS)
        'ก' => 'k',  'ข' => 'kh', 'ค' => 'kh', 'ฆ' => 'kh', 'ง' => 'ng',
        'จ' => 'ch', 'ฉ' => 'ch', 'ช' => 'ch', 'ซ' => 's',  'ฌ' => 'ch',
        'ญ' => 'y',  'ฎ' => 'd',  'ฏ' => 't',  'ฐ' => 'th', 'ฑ' => 'th',
        'ฒ' => 'th', 'ณ' => 'n',  'ด' => 'd',  'ต' => 't',  'ถ' => 'th',
        'ท' => 'th', 'ธ' => 'th', 'น' => 'n',  'บ' => 'b',  'ป' => 'p',
        'ผ' => 'ph', 'ฝ' => 'f',  'พ' => 'ph', 'ฟ' => 'f',  'ภ' => 'ph',
        'ม' => 'm',  'ย' => 'y',  'ร' => 'r',  'ล' => 'l',  'ว' => 'w',
        'ศ' => 's',  'ษ' => 's',  'ส' => 's',  'ห' => 'h',  'ฬ' => 'l',
        'อ' => 'o',  'ฮ' => 'h',
        // Vowels (sara)
        'า' => 'a',  'ิ' => 'i',  'ี' => 'i',  'ึ' => 'ue', 'ื' => 'ue',
        'ุ' => 'u',  'ู' => 'u',  'ั' => 'a',  'เ' => 'e',  'แ' => 'ae',
        'โ' => 'o',  'ใ' => 'ai', 'ไ' => 'ai', 'ำ' => 'am', '็' => '',
        '็' => '',   '่' => '',   '้' => '',   '๊' => '',   '๋' => '',
        '์' => '',   'ํ' => '',   'ๆ' => '',
        // Thai digits
        '๐' => '0',  '๑' => '1',  '๒' => '2',  '๓' => '3',  '๔' => '4',
        '๕' => '5',  '๖' => '6',  '๗' => '7',  '๘' => '8',  '๙' => '9',
    ];

    public static function transliterate(string $text): string
    {
        $result = '';
        foreach (mb_str_split($text) as $char) {
            $result .= self::$map[$char] ?? $char;
        }
        return $result;
    }

    public static function make(string $text): string
    {
        $slug = Str::slug(self::transliterate($text));
        return $slug ?: 'product';
    }
}
