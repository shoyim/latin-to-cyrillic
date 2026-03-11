<?php

namespace Shoyim\LatinToCyrillic;

/**
 * Class Converter
 * 
 * Muallif: Shoyim Obloqulov (github.com/shoyim)
 * Versiya: 1.0.0

 * Dasturdan o‘zbek tilidagi matnlarni yozuv shaklini almashtirishda
 * va yangi lotin alifbosini joriy qilishda foydalanish mumkin!

 * O‘zbek Lotin alifbosida 29 harf va bitta tutuq belgi (’) bor.
 * O‘zbek Krill alifbosida shunga mos, ya’ni 30 ta harf va belgi bor.
 * O‘zbek Yangi lotin alifbosida ham jami 30 ta harf va belgi bor.

 * Harflarni almashtirishda foydalanilgan qoidalar manbalari:
 * 1. https://uz.wikipedia.org/wiki/Vikipediya:O%CA%BBzbek_lotin_alifbosi_qoidalari
 * 2. https://uz.wikipedia.org/wiki/Vikipediya:Imlo_va_grammatika 
 * @package Shoyim\LatinToCyrillic
 */
class Converter
{
    private $LotinAlifbo = "AaBbDdFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvXxYyZz’’";
    private $KrillAlifbo = "АаБбДдФфГгҲҳИиЖжКкЛлМмНнОоПпҚқРрСсТтУуВвХхЙйЗзЪъ";
    private $YangiLotinAlifbo = "AaBbDdFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvXxYyZz’’";

    private function changeApostrophe($text)
    {
        $search = [chr(39), chr(96), chr(699), chr(700), chr(8217)];
        $replace = chr(8216);
        foreach (['O', 'o', 'G', 'g'] as $char) {
            foreach ($search as $s) {
                $text = str_replace($char . $s, $char . $replace, $text);
            }
        }
        return $text;
    }

    public function toLatin($text)
    {
        $result = "";
        $text = $this->changeApostrophe($text);
        $len = mb_strlen($text);
        $isUpper = (mb_strtoupper($text) === $text);

        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1);
            $prevChar = ($i > 0) ? mb_substr($text, $i - 1, 1) : null;

            if ($char === 'Е' || $char === 'е') {
                if ($i === 0 || in_array($prevChar, [' ', 'А', 'E', 'И', 'О', 'У', 'Ў', 'Ъ', '-'])) {
                    if ($char === 'Е') {
                        $result .= $isUpper ? "YE" : "Ye";
                    } else {
                        $result .= "ye";
                    }
                    continue;
                }
            }

            $map = [
                'Ў' => 'O‘', 'ў' => 'o‘', 'Ғ' => 'G‘', 'ғ' => 'g‘',
                'Ш' => ($isUpper ? 'SH' : 'Sh'), 'ш' => 'sh',
                'Ч' => ($isUpper ? 'CH' : 'Ch'), 'ч' => 'ch',
                'Ё' => ($isUpper ? 'YO' : 'Yo'), 'ё' => 'yo',
                'Ю' => ($isUpper ? 'YU' : 'Yu'), 'ю' => 'yu',
                'Я' => ($isUpper ? 'YA' : 'Ya'), 'я' => 'ya',
                'Э' => 'E', 'э' => 'e'
            ];

            if ($char === 'Ц' || $char === 'ц') {
                if ($i > 0 && in_array($prevChar, ['А', 'E', 'И', 'О', 'У', 'Ў', 'а', 'е', 'и', 'о', 'у', 'ў'])) {
                    $result .= ($char === 'Ц') ? ($isUpper ? "TS" : "Ts") : "ts";
                } else {
                    $result .= ($char === 'Ц') ? "S" : "s";
                }
                continue;
            }

            if (isset($map[$char])) {
                $result .= $map[$char];
            } else {
                $pos = mb_strpos($this->KrillAlifbo, $char);
                $result .= ($pos !== false) ? mb_substr($this->LotinAlifbo, $pos, 1) : $char;
            }
        }
        return $result;
    }

    public function toCyrillic($text)
    {
        $text = $this->changeApostrophe($text);
        $isUpper = (mb_strtoupper($text) === $text);

        $complex = [
            'Sh' => 'Ш', 'sh' => 'ш', 'SH' => 'Ш', 'Ch' => 'Ч', 'ch' => 'ч', 'CH' => 'Ч',
            'Yo' => 'Ё', 'yo' => 'ё', 'YO' => 'Ё', 'Yu' => 'Ю', 'yu' => 'yu', 'YU' => 'Ю',
            'Ya' => 'Я', 'ya' => 'ya', 'YA' => 'Я', 'Ts' => 'Ц', 'ts' => 'ц', 'TS' => 'Ц',
            'G‘' => 'Ғ', 'g‘' => 'ғ', 'O‘' => 'Ў', 'o‘' => 'ў',
            'Ō' => 'Ў', 'ō' => 'ў', 'Ḡ' => 'Ғ', 'ḡ' => 'ғ', 'Ş' => 'Ш', 'ş' => 'ш', 'Ç' => 'Ч', 'ç' => 'ч'
        ];

        foreach ($complex as $key => $val) {
            $text = str_replace($key, $val, $text);
        }

        $len = mb_strlen($text);
        $tempResult = "";

        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1);
            $prevChar = ($i > 0) ? mb_substr($text, $i - 1, 1) : null;

            if (($char === 'E' || $char === 'e') && ($i === 0 || $prevChar === ' ' || in_array($prevChar, ['A', 'E', 'I', 'O', 'U', 'a', 'e', 'i', 'o', 'u']))) {
                $tempResult .= ($char === 'E') ? 'Э' : 'э';
            } else {
                $pos = mb_strpos($this->LotinAlifbo, $char);
                $tempResult .= ($pos !== false) ? mb_substr($this->KrillAlifbo, $pos, 1) : $char;
            }
        }
        
        $apostrophes = [chr(39), chr(96), chr(699), chr(700), chr(8216), chr(8217)];
        foreach($apostrophes as $ap) {
            $tempResult = str_replace($ap, ($isUpper ? 'Ъ' : 'ъ'), $tempResult);
        }

        return $tempResult;
    }

    public function toNewLatin($text)
    {
        $text = $this->toLatin($text);
        $map = [
            'O‘' => 'Ō', 'o‘' => 'ō', 'G‘' => 'Ḡ', 'g‘' => 'ḡ',
            'Sh' => 'Ş', 'sh' => 'ş', 'SH' => 'Ş', 'Ch' => 'Ç', 'ch' => 'ç', 'CH' => 'Ç'
        ];
        foreach ($map as $key => $val) {
            $text = str_replace($key, $val, $text);
        }
        return $text;
    }
}