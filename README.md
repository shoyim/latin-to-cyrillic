# latin-to-cyrillic

O'zbek tilidagi matnni **Lotin**, **Kirill** va **yangi lotin** alifbolari o'rtasida o'giradigan kichik PHP kutubxonasi. Laravel bilan avtomatik integratsiya qilinadi, lekin oddiy PHP loyihalarida ham ishlatsa bo'ladi.

[![Tests](https://github.com/shoyim/latin-to-cyrillic/actions/workflows/tests.yml/badge.svg)](https://github.com/shoyim/latin-to-cyrillic/actions/workflows/tests.yml)
[![Packagist](https://img.shields.io/packagist/v/shoyim/latin-to-cyrillic.svg)](https://packagist.org/packages/shoyim/latin-to-cyrillic)
[![License](https://img.shields.io/packagist/l/shoyim/latin-to-cyrillic.svg)](LICENSE)

## Yangi alifbo (2026)

2026-yil 21-oktabrda imzolangan qonunga ko'ra lotin yozuvidagi to'rtta harfning shakli o'zgardi:

| Eski lotin | Yangi lotin | Kirill |
| :--------: | :---------: | :----: |
| O' o'      | **Ö ö**     | Ў ў    |
| G' g'      | **Ğ ğ**     | Ғ ғ    |
| Sh sh      | **Ş ş**     | Ш ш    |
| Ch ch      | **Ç ç**     | Ч ч    |

`ng` endi alohida harf hisoblanmaydi, tutuq belgisi (ʼ) esa saqlab qolindi. Yangi alifbo 28 ta harf va 1 ta tutuq belgisidan iborat. `toNewLatin()` metodi aynan shu o'zgarishlarni qo'llaydi.

## O'rnatish

```bash
composer require shoyim/latin-to-cyrillic
```

Talablar: PHP 7.0+ va `mbstring` kengaytmasi.

## Ishlatish

### Oddiy PHP

```php
use Shoyim\LatinToCyrillic\Converter;

$c = new Converter();

$c->toLatin("Ўзбекистон");        // "O'zbekiston"
$c->toCyrillic("O'zbekiston");    // "Ўзбекистон"
$c->toNewLatin("Ўзбекистон");     // "Özbekiston"
$c->fromNewLatin("Özbekiston");   // "O'zbekiston"
```

### Laravel

Paket o'zini avtomatik ro'yxatdan o'tkazadi (package discovery), hech narsa sozlash shart emas. Facade orqali:

```php
use Shoyim\LatinToCyrillic\Facades\LatinCyrillic;

LatinCyrillic::toCyrillic("Maktab");                  // "Мактаб"
LatinCyrillic::toNewLatin("Chiroyli bog'");           // "Çiroyli boğ"
```

## Metodlar

| Metod                  | Vazifasi                                                        |
| ---------------------- | --------------------------------------------------------------- |
| `toLatin($text)`       | Kirill matnini amaldagi lotinga o'giradi                        |
| `toCyrillic($text)`    | Lotin (yoki yangi lotin) matnini kirillga o'giradi              |
| `toNewLatin($text)`    | Matnni 2026-yilgi yangi lotin alifbosiga (Ö, Ğ, Ş, Ç) o'giradi  |
| `fromNewLatin($text)`  | Yangi lotin harflarini amaldagi lotinga qaytaradi               |

## Nozik jihatlar

- **Apostroflar.** `oʻ` va `gʻ` dagi barcha apostrof ko'rinishlari — to'g'ri (`'`), teskari (`` ` ``), `ʻ` (U+02BB), `ʼ` (U+02BC), `'` (U+2018), `'` (U+2019) — avtomatik taniladi.
- **"e" harfi.** Kirill `е` so'z boshida va unlidan keyin `ye`, boshqa holatlarda `e` bo'ladi (`поезд → poyezd`, `бел → bel`).
- **"ts" / "s".** Kirill `ц` unlidan keyin `ts`, aks holda `s` bo'ladi (`функция → funksiya`, `цирк → sirk`).
- **Katta-kichik harf** matnda qanday bo'lsa, shunday saqlanadi.

## Testlar

```bash
composer install
composer test
```

## Qoidalar manbasi

- [Oʻzbek-lotin alifbosini takomillashtirishga doir qonun imzolandi — Kun.uz](https://kun.uz/news/2026/10/21/ozbek-lotin-alifbosini-takomillashtirishga-doir-qonun-prezident-tomonidan-imzolandi)
- [O'zbek alifbosi takomillashtirildi — Anhor.uz](https://anhor.uz/uzl/ichkaridan-nazar/alifbo-tarixiy-xato-tuzatildi)
- [Og'riqli harflar. 30 yilga cho'zilgan alifbo islohoti — Gazeta.uz](https://www.gazeta.uz/oz/2024/02/14/uzbek-latin-alphabet/)

## Litsenziya

[MIT](LICENSE)
