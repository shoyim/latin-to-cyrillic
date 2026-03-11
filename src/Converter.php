<?php

namespace Shoyim\LatinToCyrillic;

/**
 * Class Converter
 * Muallif: Shoyim Obloqulov (github.com/shoyim)
 * Versiya: 1.1.0
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
        $replace = '‘';
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
        $isAllUpper = (mb_strtoupper($text, 'UTF-8') === $text);

        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1);
            $prevChar = ($i > 0) ? mb_substr($text, $i - 1, 1) : null;

            if ($char === 'Е' || $char === 'е') {
                if ($i === 0 || in_array($prevChar, [' ', 'А', 'E', 'И', 'О', 'У', 'Ў', 'Ъ', '-'])) {
                    $result .= ($char === 'Е') ? ($isAllUpper ? "YE" : "Ye") : "ye";
                    continue;
                }
            }

            $map = [
                'Ў' => 'O‘', 'ў' => 'o‘', 'Ғ' => 'G‘', 'ғ' => 'g‘',
                'Ш' => ($isAllUpper ? 'SH' : 'Sh'), 'ш' => 'sh',
                'Ч' => ($isAllUpper ? 'CH' : 'Ch'), 'ч' => 'ch',
                'Ё' => ($isAllUpper ? 'YO' : 'Yo'), 'ё' => 'yo',
                'Ю' => ($isAllUpper ? 'YU' : 'Yu'), 'ю' => 'yu',
                'Я' => ($isAllUpper ? 'YA' : 'Ya'), 'я' => 'ya',
                'Э' => ($char === 'Э' ? 'E' : 'e'), 'э' => 'e'
            ];

            if ($char === 'Ц' || $char === 'ц') {
                if ($i > 0 && in_array(mb_strtolower($prevChar), ['а', 'е', 'и', 'о', 'у', 'ў'])) {
                    $result .= ($char === 'Ц') ? ($isAllUpper ? "TS" : "Ts") : "ts";
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
        $isAllUpper = (mb_strtoupper($text, 'UTF-8') === $text);

        $complex = [
            'Sh' => 'Ш', 'sh' => 'ш', 'SH' => 'Ш', 'Ch' => 'Ч', 'ch' => 'ч', 'CH' => 'Ч',
            'Yo' => 'Ё', 'yo' => 'ё', 'YO' => 'Ё', 'Yu' => 'Ю', 'yu' => 'ю', 'YU' => 'Ю',
            'Ya' => 'Я', 'ya' => 'я', 'YA' => 'Я', 'Ts' => 'Ц', 'ts' => 'ц', 'TS' => 'Ц',
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

            if (mb_strtolower($char) === 'e' && ($i === 0 || $prevChar === ' ' || in_array(mb_strtolower($prevChar), ['a', 'e', 'i', 'o', 'u']))) {
                $tempResult .= ($char === 'E') ? 'Э' : 'э';
            } else {
                $pos = mb_strpos($this->LotinAlifbo, $char);
                $tempResult .= ($pos !== false) ? mb_substr($this->KrillAlifbo, $pos, 1) : $char;
            }
        }

        $apostrophes = [chr(39), chr(96), chr(699), chr(700), '‘', '’'];
        foreach ($apostrophes as $ap) {
            $tempResult = str_replace($ap, ($isAllUpper ? 'Ъ' : 'ъ'), $tempResult);
        }

        return $tempResult;
    }

    public function toNewLatin($text)
    {
        $text = $this->toLatin($text);
        $map = [
            'O‘' => 'Ō', 'o‘' => 'ō', 'G‘' => 'Ḡ', 'g‘' => 'ḡ',
            'Sh' => 'Ş', 'sh' => 'ş', 'Ch' => 'Ç', 'ch' => 'ç'
        ];
        foreach ($map as $key => $val) {
            $text = str_replace($key, $val, $text);
        }
        return $text;
    }
}