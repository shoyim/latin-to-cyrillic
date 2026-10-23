<?php

namespace Shoyim\LatinToCyrillic;

/**
 * O'zbek tili uchun transliteratsiya klassi.
 *
 * Lotin, Kirill va isloh qilingan ("yangi") lotin alifbolari o'rtasida
 * matnni o'giradi. Yangi lotin harflari 2026-yil 21-oktabrda imzolangan
 * qonunga muvofiq keltirilgan: Oʻ→Ö, Gʻ→Ğ, Sh→Ş, Ch→Ç.
 *
 * Muallif: Shoyim Obloqulov (github.com/shoyim)
 *
 * @package Shoyim\LatinToCyrillic
 */
class Converter
{
    /** Pozitsiya bo'yicha mos keladigan asosiy lotin harflari. */
    private $LotinAlifbo = "AaBbDdFfGgHhIiJjKkLlMmNnOoPpQqRrSsTtUuVvXxYyZz‘‘";

    /** Pozitsiya bo'yicha mos keladigan asosiy kirill harflari. */
    private $KrillAlifbo = "АаБбДдФфГгҲҳИиЖжКкЛлМмНнОоПпҚқРрСсТтУуВвХхЙйЗзЪъ";

    /**
     * Qo'shaloq (oʻ, gʻ) belgisi uchun ishlatiladigan yagona apostrof — ‘ (U+2018).
     * Matndagi barcha apostrof ko'rinishlari shu belgiga keltiriladi.
     */
    private $apostrofBelgisi = "‘";

    /** Kirill unlilari — "е" harfini "ye" yoki "e" ga o'girishda kerak bo'ladi. */
    private $kirillUnlilar = ['А', 'Е', 'Ё', 'И', 'О', 'У', 'Э', 'Ю', 'Я', 'Ў'];

    /**
     * Oʻ va Gʻ dagi turli xil apostroflarni bitta ‘ (U+2018) belgisiga keltiradi.
     * To'g'ri (') va teskari (`) tirnoq, ʻ (U+02BB), ʼ (U+02BC), ‘ (U+2018), ’ (U+2019)
     * — barchasi qo'llab-quvvatlanadi.
     */
    private function changeApostrophe($text)
    {
        $variantlar = ["'", "`", "ʻ", "ʼ", "‘", "’", "ʹ", "´"];
        foreach (['O', 'o', 'G', 'g'] as $harf) {
            foreach ($variantlar as $belgi) {
                if ($belgi === $this->apostrofBelgisi) {
                    continue;
                }
                $text = str_replace($harf . $belgi, $harf . $this->apostrofBelgisi, $text);
            }
        }
        return $text;
    }

    /**
     * Kirill matnini lotinga o'giradi.
     */
    public function toLatin($text)
    {
        $result = "";
        $text = $this->changeApostrophe($text);
        $len = mb_strlen($text);
        $isAllUpper = (mb_strtoupper($text, 'UTF-8') === $text);

        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1);
            $prevChar = ($i > 0) ? mb_substr($text, $i - 1, 1) : null;

            // "Е" harfi: so'z boshida va unlidan keyin — "Ye", aks holda — "e".
            if ($char === 'Е' || $char === 'е') {
                $prevUpper = ($prevChar !== null) ? mb_strtoupper($prevChar, 'UTF-8') : null;
                $yeHolati = ($i === 0)
                    || in_array($prevUpper, [' ', '-', 'Ъ', 'Ь'], true)
                    || in_array($prevUpper, $this->kirillUnlilar, true);
                if ($yeHolati) {
                    if ($char === 'Е') {
                        $result .= $isAllUpper ? "YE" : "Ye";
                    } else {
                        $result .= "ye";
                    }
                    continue;
                }
                // Unlidan keyin bo'lmasa, oddiy "e" sifatida davom etadi.
                $result .= ($char === 'Е') ? 'E' : 'e';
                continue;
            }

            $map = [
                'Ў' => 'O‘', 'ў' => 'o‘', 'Ғ' => 'G‘', 'ғ' => 'g‘',
                'Ш' => ($isAllUpper ? 'SH' : 'Sh'), 'ш' => 'sh',
                'Ч' => ($isAllUpper ? 'CH' : 'Ch'), 'ч' => 'ch',
                'Ё' => ($isAllUpper ? 'YO' : 'Yo'), 'ё' => 'yo',
                'Ю' => ($isAllUpper ? 'YU' : 'Yu'), 'ю' => 'yu',
                'Я' => ($isAllUpper ? 'YA' : 'Ya'), 'я' => 'ya',
                'Э' => 'E', 'э' => 'e',
            ];

            // "Ц": unlidan keyin "ts", aks holda "s".
            if ($char === 'Ц' || $char === 'ц') {
                $prevLower = ($prevChar !== null) ? mb_strtolower($prevChar, 'UTF-8') : null;
                if ($i > 0 && in_array($prevLower, ['а', 'е', 'и', 'о', 'у', 'ў', 'э'], true)) {
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

    /**
     * Lotin (yoki yangi lotin) matnini kirillga o'giradi.
     */
    public function toCyrillic($text)
    {
        $text = $this->changeApostrophe($text);
        $isAllUpper = (mb_strtoupper($text, 'UTF-8') === $text);

        $complex = [
            // Qo'shaloq lotin harflari
            'Sh' => 'Ш', 'sh' => 'ш', 'SH' => 'Ш',
            'Ch' => 'Ч', 'ch' => 'ч', 'CH' => 'Ч',
            'Yo' => 'Ё', 'yo' => 'ё', 'YO' => 'Ё',
            'Yu' => 'Ю', 'yu' => 'ю', 'YU' => 'Ю',
            'Ya' => 'Я', 'ya' => 'я', 'YA' => 'Я',
            'Ye' => 'Е', 'ye' => 'е', 'YE' => 'Е',
            'Ts' => 'Ц', 'ts' => 'ц', 'TS' => 'Ц',
            'G‘' => 'Ғ', 'g‘' => 'ғ', 'O‘' => 'Ў', 'o‘' => 'ў',
            // Yangi lotin (2026 qonuni)
            'Ö' => 'Ў', 'ö' => 'ў', 'Ğ' => 'Ғ', 'ğ' => 'ғ',
            'Ş' => 'Ш', 'ş' => 'ш', 'Ç' => 'Ч', 'ç' => 'ч',
            // Eski loyihalardagi belgilar ham qabul qilinadi (moslashuvchanlik uchun)
            'Ō' => 'Ў', 'ō' => 'ў', 'Ŏ' => 'Ў', 'ŏ' => 'ў',
            'Ḡ' => 'Ғ', 'ḡ' => 'ғ', 'Ğ' => 'Ғ',
        ];

        foreach ($complex as $key => $val) {
            $text = str_replace($key, $val, $text);
        }

        $len = mb_strlen($text);
        $tempResult = "";

        for ($i = 0; $i < $len; $i++) {
            $char = mb_substr($text, $i, 1);
            $prevChar = ($i > 0) ? mb_substr($text, $i - 1, 1) : null;

            // "e": so'z boshida yoki unlidan keyin — "э", undoshdan keyin — "е".
            if (mb_strtolower($char, 'UTF-8') === 'e') {
                $eBoshida = ($i === 0 || $prevChar === ' ' || $prevChar === '-'
                    || in_array(mb_strtolower($prevChar, 'UTF-8'), ['a', 'e', 'i', 'o', 'u'], true));
                if ($eBoshida) {
                    $tempResult .= ($char === 'E') ? 'Э' : 'э';
                } else {
                    $tempResult .= ($char === 'E') ? 'Е' : 'е';
                }
            } else {
                $pos = mb_strpos($this->LotinAlifbo, $char);
                $tempResult .= ($pos !== false) ? mb_substr($this->KrillAlifbo, $pos, 1) : $char;
            }
        }

        // Qolgan apostroflar tutuq belgisi (ъ) sifatida qaraladi.
        $apostroflar = ["'", "`", "ʻ", "ʼ", "‘", "’"];
        foreach ($apostroflar as $ap) {
            $tempResult = str_replace($ap, ($isAllUpper ? 'Ъ' : 'ъ'), $tempResult);
        }

        return $tempResult;
    }

    /**
     * Matnni 2026-yilgi qonun bo'yicha yangi lotin alifbosiga o'giradi:
     * Oʻ→Ö, Gʻ→Ğ, Sh→Ş, Ch→Ç. Kirill va amaldagi lotin matni qabul qilinadi.
     */
    public function toNewLatin($text)
    {
        $text = $this->toLatin($text);
        $map = [
            'O‘' => 'Ö', 'o‘' => 'ö', 'G‘' => 'Ğ', 'g‘' => 'ğ',
            'SH' => 'Ş', 'Sh' => 'Ş', 'sh' => 'ş',
            'CH' => 'Ç', 'Ch' => 'Ç', 'ch' => 'ç',
        ];
        foreach ($map as $key => $val) {
            $text = str_replace($key, $val, $text);
        }
        return $text;
    }

    /**
     * Yangi lotin harflarini (Ö, Ğ, Ş, Ç) amaldagi lotinga qaytaradi:
     * Ö→Oʻ, Ğ→Gʻ, Ş→Sh, Ç→Ch.
     */
    public function fromNewLatin($text)
    {
        $map = [
            'Ö' => 'O‘', 'ö' => 'o‘', 'Ğ' => 'G‘', 'ğ' => 'g‘',
            'Ş' => 'Sh', 'ş' => 'sh', 'Ç' => 'Ch', 'ç' => 'ch',
        ];
        foreach ($map as $key => $val) {
            $text = str_replace($key, $val, $text);
        }
        return $text;
    }
}
