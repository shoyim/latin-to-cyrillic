<?php

namespace Shoyim\LatinToCyrillic;

/**
 * Class Converter
 * * Muallif: Shoyim Obloqulov (github.com/shoyim)
 * Versiya: 1.1.0
 * * Dasturdan o‘zbek tilidagi matnlarni yozuv shaklini almashtirishda
 * va yangi lotin alifbosini joriy qilishda foydalanish mumkin!
 * * O‘zbek Lotin alifbosida 29 harf va bitta tutuq belgi (’) bor.
 * O‘zbek Krill alifbosida shunga mos, ya’ni 30 ta harf va belgi bor.
 * O‘zbek Yangi lotin alifbosida ham jami 30 ta harf va belgi bor.
 * * Harflarni almashtirishda foydalanilgan qoidalar manbalari:
 * 1. https://uz.wikipedia.org/wiki/Vikipediya:O%CA%BBzbek_lotin_alifbosi_qoidalari
 * 2. https://uz.wikipedia.org/wiki/Vikipediya:Imlo_va_grammatika 
 * @package Shoyim\LatinToCyrillic
 */
class Converter
{
    private $latinMap = "AaBbDdFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvXxYyZz";
    private $cyrillMap = "АаБбДдФфГгҲҳИиЖжКкЛлМмНнОоПпҚқРрСсТтУуВвХхЙйЗз";

    private function normalizeApostrophes($text)
    {
        $apostrophes = [chr(39), chr(96), chr(699), chr(700), chr(8216), chr(8217), '’', '‘', '`', "'"];
        return str_replace($apostrophes, "‘", $text);
    }

    public function toLatin($text)
    {
        $text = $this->normalizeApostrophes($text);
        
        $map = [
            'Ў' => 'O‘', 'ў' => 'o‘', 'Ғ' => 'G‘', 'ғ' => 'g‘',
            'Ш' => 'Sh', 'ш' => 'sh', 'Ч' => 'Ch', 'ч' => 'ch',
            'Ё' => 'Yo', 'ё' => 'yo', 'Ю' => 'Yu', 'ю' => 'yu',
            'Я' => 'Ya', 'я' => 'ya', 'Ц' => 'Ts', 'ц' => 'ts', 'Э' => 'E', 'э' => 'e'
        ];

        $text = preg_replace_callback('/(?<=^|[\s\sАЕИОУЎЪ-])Е/u', fn($m) => 'Ye', $text);
        $text = preg_replace_callback('/(?<=^|[\s\sАЕИОУЎЪ-])е/u', fn($m) => 'ye', $text);
        $text = str_replace(['Е', 'е'], ['E', 'e'], $text);

        foreach ($map as $cyr => $lat) {
            $text = mb_ereg_replace($cyr, $lat, $text);
        }

        $result = "";
        for ($i = 0; $i < mb_strlen($text); $i++) {
            $char = mb_substr($text, $i, 1);
            $pos = mb_strpos($this->cyrillMap, $char);
            $result .= ($pos !== false) ? mb_substr($this->latinMap, $pos, 1) : $char;
        }

        return $result;
    }

    public function toCyrillic($text)
    {
        $text = $this->normalizeApostrophes($text);
        
        $complex = [
            'O‘' => 'Ў', 'o‘' => 'ў', 'G‘' => 'Ғ', 'g‘' => 'ғ',
            'Sh' => 'Ш', 'sh' => 'ш', 'SH' => 'Ш', 'Ch' => 'Ч', 'ch' => 'ч', 'CH' => 'Ч',
            'Yo' => 'Ё', 'yo' => 'ё', 'YO' => 'Ё', 'Yu' => 'Ю', 'yu' => 'ю', 'YU' => 'Ю',
            'Ya' => 'Я', 'ya' => 'я', 'YA' => 'Я', 'Ts' => 'Ц', 'ts' => 'ц', 'TS' => 'Ц'
        ];

        foreach ($complex as $key => $val) {
            $text = str_replace($key, $val, $text);
        }

        $text = preg_replace_callback('/(?<=^|[\s\sAEIOUaeiou])E/u', fn($m) => 'Э', $text);
        $text = preg_replace_callback('/(?<=^|[\s\sAEIOUaeiou])e/u', fn($m) => 'э', $text);

        $len = mb_strlen($text);
        $result = "";
        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1);
            $pos = mb_strpos($this->latinMap, $char);
            if ($pos !== false) {
                $result .= mb_substr($this->cyrillMap, $pos, 1);
            } elseif ($char === '‘' || $char === '’') {
                $result .= 'ъ';
            } else {
                $result .= $char;
            }
        }

        return $result;
    }

    public function toNewLatin($text)
    {
        $text = $this->toLatin($text);
        $map = [
            'O‘' => 'Õ', 'o‘' => 'õ', 'G‘' => 'Ğ', 'g‘' => 'ğ',
            'Sh' => 'Ş', 'sh' => 'ş', 'Ch' => 'Ç', 'ch' => 'ç'
        ];
        return str_replace(array_keys($map), array_values($map), $text);
    }
}