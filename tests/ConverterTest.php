<?php

namespace Shoyim\LatinToCyrillic\Tests;

use PHPUnit\Framework\TestCase;
use Shoyim\LatinToCyrillic\Converter;

class ConverterTest extends TestCase
{
    /** @var Converter */
    private $converter;

    protected function setUp(): void
    {
        $this->converter = new Converter();
    }

    // ---- Kirill -> Lotin ----

    public function testKirillMatnLotinga()
    {
        $this->assertSame("O‘zbekiston", $this->converter->toLatin("Ўзбекистон"));
        $this->assertSame("maktab", $this->converter->toLatin("мактаб"));
        $this->assertSame("shahar", $this->converter->toLatin("шаҳар"));
        $this->assertSame("choy", $this->converter->toLatin("чой"));
        $this->assertSame("yomg‘ir", $this->converter->toLatin("ёмғир"));
    }

    public function testEHarfiYeQoidasi()
    {
        // So'z boshida va unlidan keyin -> "ye"
        $this->assertSame("Yerkin", $this->converter->toLatin("Еркин"));
        $this->assertSame("poyezd", $this->converter->toLatin("поезд"));
        // Undoshdan keyin -> "e"
        $this->assertSame("bel", $this->converter->toLatin("бел"));
    }

    public function testTsVaSQoidasi()
    {
        // Unlidan keyingi "ц" -> "ts"
        $this->assertSame("funksiya", $this->converter->toLatin("функция"));
        $this->assertSame("litsey", $this->converter->toLatin("лицей"));
        // So'z boshidagi "ц" -> "s"
        $this->assertSame("sirk", $this->converter->toLatin("цирк"));
    }

    public function testKattaHarflarSaqlanadi()
    {
        $this->assertSame("SHAX", $this->converter->toLatin("ШАХ"));
        $this->assertSame("Shax", $this->converter->toLatin("Шах"));
    }

    // ---- Lotin -> Kirill ----

    public function testLotinMatnKirillga()
    {
        $this->assertSame("Ўзбекистон", $this->converter->toCyrillic("O‘zbekiston"));
        $this->assertSame("мактаб", $this->converter->toCyrillic("maktab"));
        $this->assertSame("шаҳар", $this->converter->toCyrillic("shahar"));
        $this->assertSame("чой", $this->converter->toCyrillic("choy"));
    }

    public function testLotinEVaEExlashuvi()
    {
        // So'z boshidagi "e" -> "э"
        $this->assertSame("Экран", $this->converter->toCyrillic("Ekran"));
        // "ye" -> "е"
        $this->assertSame("Еркин", $this->converter->toCyrillic("Yerkin"));
    }

    public function testApostrofTutuqBelgisiga()
    {
        $this->assertSame("маъно", $this->converter->toCyrillic("ma'no"));
    }

    // ---- Yangi lotin (2026 qonuni): Oʻ->Ö, Gʻ->Ğ, Sh->Ş, Ch->Ç ----

    public function testYangiLotinKirilldan()
    {
        $this->assertSame("Özbekiston", $this->converter->toNewLatin("Ўзбекистон"));
    }

    public function testYangiLotinLotindan()
    {
        $this->assertSame("Şahar", $this->converter->toNewLatin("Shahar"));
        $this->assertSame("Çoy", $this->converter->toNewLatin("Choy"));
        $this->assertSame("öğil", $this->converter->toNewLatin("o‘g‘il"));
    }

    public function testYangiLotinKattaHarf()
    {
        $this->assertSame("ŞAHAR", $this->converter->toNewLatin("ШАҲАР"));
    }

    public function testYangiLotindanOrqaga()
    {
        $this->assertSame("O‘zbekiston", $this->converter->fromNewLatin("Özbekiston"));
        $this->assertSame("Shahar", $this->converter->fromNewLatin("Şahar"));
        $this->assertSame("Choy", $this->converter->fromNewLatin("Çoy"));
    }

    public function testYangiLotinKirillga()
    {
        $this->assertSame("Ўзбекистон", $this->converter->toCyrillic("Özbekiston"));
        $this->assertSame("Шаҳар", $this->converter->toCyrillic("Şahar"));
    }

    // ---- Apostrof turlarini tanish (ilgari chr() xatosi bor edi) ----

    public function testBarchaApostrofVariantlari()
    {
        $variantlar = [
            "o'g'il",   // to'g'ri tirnoq
            "o`g`il",   // teskari tirnoq
            "oʻgʻil",   // U+02BB
            "oʼgʼil",   // U+02BC
            "o‘g‘il",   // U+2018
            "o’g’il",   // U+2019
        ];
        foreach ($variantlar as $kirish) {
            $this->assertSame("öğil", $this->converter->toNewLatin($kirish), "Kirish: {$kirish}");
        }
    }

    // ---- Ikki tomonlama (round-trip) ----

    public function testRoundTripKirillLotinKirill()
    {
        $matn = "Ўзбекистон мактаби";
        $this->assertSame($matn, $this->converter->toCyrillic($this->converter->toLatin($matn)));
    }
}
