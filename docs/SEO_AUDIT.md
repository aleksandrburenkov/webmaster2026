# SEO / GEO / AI — аудит проекта

Дата аудита: 2026-10-05
Проект: Laravel 12 + Filament 5 (`webmaster-2026`)
Канонический домен (production): `https://webmaster32.ru`

## 1. Что было до изменений

### 1.1. Основной layout

`<head>` формировался в `resources/views/layouts/app.blade.php`. В нём были:

- `<meta charset="UTF-8">`;
- `<meta name="viewport">`;
- жёстко прописанные `meta description`, `meta keywords`, `meta author`;
- `meta theme-color`;
- Open Graph: `og:title`, `og:description`, `og:type`, `og:url`;
- `twitter:card`;
- `<title>` через `@yield('title', ...)`;
- `<link rel="icon" type="image/svg+xml" href="/favicon.svg">`.

Страницы задавали заголовки и описания через секции Blade:

- `@section('title', ...)` — home, portfolio, project, privacy-policy, offer;
- `@section('meta_description', ...)` — project, privacy-policy, offer.

### 1.2. Логотип

Отдельного файла логотипа нет — бренд текстовый: `Webmaster32`.

- `max.svg` в корне проекта — иконка мессенджера MAX, не логотип;
- `public/img/` содержит только `projects/`;
- исходника логотипа для генерации favicon не найдено.

### 1.3. Текущие SEO-теги и служебные файлы

| Артефакт | Состояние до |
| --- | --- |
| `<html lang>` | `ru` (захардкожен) |
| `<meta charset>` | есть (`UTF-8`) |
| Дублирующиеся мета-теги | нет |
| `canonical` | отсутствовал |
| Open Graph | частично (title/description/type/url), без изображения |
| Twitter Card | есть `summary_large_image`, без изображения |
| JSON-LD | отсутствовал |
| `public/favicon.ico` | существовал, но размер 0 байт (пустой) |
| `public/favicon.svg` | отсутствовал, хотя подключался в layout (битая иконка) |
| `public/favicons/` | отсутствовала |
| `public/site.webmanifest` | отсутствовал |
| `public/robots.txt` | был минимальный: `User-agent: *` / `Disallow:` (без Sitemap и правил для AI) |
| `public/sitemap.xml` | отсутствовал |
| `public/ai.txt`, `public/llms.txt` | отсутствовали |
| `public/.htaccess` | стандартный Laravel rewrite, без charset/MIME/кэша/security-блоков |

### 1.4. Инфраструктура

- `APP_URL=http://web26` (локально), production-домен `webmaster32.ru`;
- PHP 8.3, расширение **GD** доступно; ImageMagick (`magick`/`convert`) — **недоступен**;
- публичные маршруты: `/`, `/privacy-policy`, `/offer`, `/portfolio`, `/portfolio/{slug}`;
- админка Filament: `/admin`;
- мультиязычности нет (`APP_LOCALE=ru`);
- контакты управляются через БД (`ContactSetting`): email `admin@webmaster32.ru`, город Брянск.

## 2. Найденные проблемы

1. Битая ссылка на иконку: layout подключал `/favicon.svg`, которого не было.
2. `favicon.ico` был пустым (0 байт).
3. Отсутствовали `canonical`, JSON-LD, `sitemap.xml`, `site.webmanifest`.
4. `robots.txt` не содержал `Sitemap` и правил для AI-краулеров.
5. Не было `ai.txt` / `llms.txt` — не декларировалась политика использования контента для AI.
6. В layout дублировалась логика мета-тегов вместо единого partial.
7. Не было гео-разметки при реальной географической привязке (Брянск).
8. `.htaccess` не задавал charset/MIME для `.webmanifest`, `.ico`, `.svg`, `.xml`, `.txt`.
9. Не было принудительного HTTPS в production.

## 3. Что создано / изменено

Создано:

- `config/seo.php` — единая SEO/GEO/AI-конфигурация;
- `resources/views/partials/seo.blade.php` — единый блок `<head>` (meta, OG, Twitter, GEO, hreflang, favicon, manifest, JSON-LD);
- `app/Console/Commands/GenerateFavicons.php` — генерация иконок на PHP GD (с fallback-монограммой);
- `app/Console/Commands/GenerateSeoFiles.php` — генерация `sitemap.xml`, `robots.txt`, `ai.txt`, `llms.txt` из конфига;
- `app/Seo/SitemapProvider.php` и `app/Seo/Providers/ProjectSitemapProvider.php` — провайдер динамических URL (проекты);
- `public/favicon.ico` (валидный ICO, 4 размера);
- `public/favicons/` — `favicon-16x16.png`, `favicon-32x32.png`, `favicon-48x48.png`, `apple-touch-icon.png`, `icon-192x192.png`, `icon-512x512.png`, `maskable-192x192.png`, `maskable-512x512.png`, `favicon.svg`;
- `public/site.webmanifest`;
- `public/robots.txt` (перегенерирован);
- `public/ai.txt` и `public/.well-known/ai.txt`;
- `public/llms.txt`;
- `public/sitemap.xml`;
- `docs/SEO_AUDIT.md`, `docs/SEO_TODO.md`.

Изменено:

- `resources/views/layouts/app.blade.php` — убраны дублирующиеся мета-теги, подключён `@include('partials.seo')`, `<html lang>` теперь динамический;
- `public/.htaccess` — сохранён стандартный Laravel rewrite, добавлены charset, MIME, кэш и security-заголовки;
- `config/seo.php`, `.env`, `.env.example` — добавлены SEO-переменные;
- `app/Providers/AppServiceProvider.php` — `URL::forceScheme('https')` в production.

## 4. Выполненные команды

```bash
composer dump-autoload
php artisan seo:generate-favicons
php artisan seo:generate-files
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan serve --host=127.0.0.1 --port=8000
```

## 5. Проверки, которые пройдены

| Проверка | Результат |
| --- | --- |
| `/favicon.ico` | 200, `image/vnd.microsoft.icon`, валидный ICO |
| `/site.webmanifest` | 200, `application/manifest+json`, валидный JSON |
| `/robots.txt` | 200, `text/plain; charset=UTF-8` |
| `/ai.txt` | 200, `text/plain; charset=UTF-8` |
| `/llms.txt` | 200, `text/plain; charset=UTF-8` |
| `/sitemap.xml` | 200, `application/xml`, валидный XML (11 URL) |
| `/favicons/*.png`, `/favicons/favicon.svg` | 200 |
| Один `<title>` и один `meta description` на страницах | да |
| `canonical` на всех проверенных страницах | да |
| Open Graph / Twitter Card | да |
| JSON-LD (`WebSite` + `LocalBusiness`) | валиден |
| Кодировка файлов | UTF-8 без BOM |
| Содержимое HTML | корректный UTF-8 (`Александр Буренков`, `Брянск`) |

## 6. Проверки, которые не пройдены / требуют ручных действий

- OG-изображение 1200×630 отсутствует — `SEO_IMAGE` пуст, `og:image` не выводится (см. `docs/SEO_TODO.md`).
- Координаты для GEO не заполнены (`SEO_GEO_POSITION` пуст).
- Полные реквизиты организации (ИНН, ОГРН, телефон, улица, индекс) неизвестны.
- Финальная проверка HTTPS/редиректов и добавление в поисковые системы выполняются вручную на production.
