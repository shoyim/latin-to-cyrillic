# O'zbek tili uchun Lotin-Kirill-YangiLotin transliterator

Ushbu paket o'zbek tilidagi matnlarni Lotin, Kirill va Yangi Lotin (isloh qilingan) alifbolari o'rtasida o'girish uchun mo'ljallangan. Paket Laravel freymvorki uchun Facade va ServiceProvider bilan to'liq integratsiya qilingan.

## Xususiyatlari

* **Lotin -> Kirill** o'girish.
* **Kirill -> Lotin** o'girish (E, Ts, Ye harflari qoidalariga amal qilingan).
* **Yangi Lotin** (Ō, Ḡ, Ş, Ç) alifbosiga o'girish.
* Barcha turdagi apostroflarni (`, ', ʻ, ʼ, ‘, ’) to'g'ri tanish va qayta ishlash.
* Katta va kichik harflarni (Case-sensitivity) saqlab qolish.

## O'rnatish
```bash
composer require shoyim/latin-to-cyrillic
```
## Yangilash
```bash
composer update shoyim/latin-to-cyrillic
```

## Ishlatish
```php
use Shoyim\LatinToCyrillic\Facades\LatinCyrillic;

// Kirilldan Lotinga
echo LatinCyrillic::toLatin("O'zbekiston"); // Ўзбекистон
echo LatinCyrillic::toLatin("Ўзбекиiston"); // O'zbekiston

// Lotindan Kirillga
echo LatinCyrillic::toCyrillic("Maktab"); // Мактаб

// Yangi Lotinga
echo LatinCyrillic::toNewLatin("O'g'il bolalar shaxmat o'ynashdi"); 
```

## Qoidalar manbasi
* [O'zbek lotin alifbosi qoidalari](https://uz.wikipedia.org/wiki/Vikipediya:O%CA%BBzbek_lotin_alifbosi_qoidalari)
* [Yangi Lotin yozuvi loyihasi](https://regulation.gov.uz/oz/d/31596)