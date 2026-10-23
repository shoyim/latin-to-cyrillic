# Changelog

## 1.3.0 — 2026-10-23

### O'zgargan

- Yangi lotin alifbosi 2026-yil 21-oktabrdagi qonunga moslashtirildi:
  `Oʻ→Ö`, `Gʻ→Ğ` (ilgari `Ō`, `Ḡ` ishlatilardi), `Sh→Ş`, `Ch→Ç`.
- `toCyrillic()` endi yangi lotin harflarini (`Ö Ğ Ş Ç`) ham qabul qiladi.
  Eski loyihalardagi `Ō Ḡ Ŏ` belgilar ham moslashuvchanlik uchun tushuniladi.
- PHP talabi `>=7.0` ga kengaytirildi (ilgari `^8.0`).

### Tuzatilgan

- Apostrof normalizatsiyasidagi xato: `ʻ` (U+02BB), `ʼ` (U+02BC), `'` (U+2019)
  belgilar `chr()` orqali noto'g'ri solishtirilar edi va tanilmasdi. Endi to'g'ri taniladi.
- `toCyrillic()` da undoshdan keyingi `e` lotin harfi kirillga o'girilmay qolib ketardi.
- Kirill `е → ye` qoidasi lotin `E` o'rniga kirill unlilari bilan solishtiriladigan bo'ldi.
- `toCyrillic()` ga `ye → е` o'girishi qo'shildi (ilgari `йе` chiqib qolardi).

### Qo'shilgan

- `fromNewLatin()` metodi — yangi lotindan amaldagi lotinga qaytaradi.
- PHPUnit test to'plami va GitHub Actions CI (PHP 7.4–8.3).
