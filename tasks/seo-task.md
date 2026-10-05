# SEO/GEO task for AI code agent in a Laravel project

Ты — AI-агент, работающий напрямую в редакторе кода с проектом на Laravel. Твоя задача: выполнить аудит и внедрить полноценную базу для органического SEO, GEO / local SEO и Generative Engine Optimization (GEO для LLM/AI). Не выдумывай факты о компании, адресах, телефонах, координатах и контенте. Если данных нет — создай конфигурационные заготовки и отметь их в `docs/SEO_TODO.md`.

> Важно: в задаче может быть опечатка `favicon.iso`. Правильно — `favicon.ico`.

---

## 1. Правила работы агента

1. Работай только в текущем проекте.
2. Перед изменением файлов читай их текущее содержимое.
3. Не удаляй существующую логику без необходимости.
4. Если файл уже существует — сделай резервную копию или аккуратно смержь изменения.
5. Все текстовые файлы сохраняй в кодировке **UTF-8 without BOM**, окончания строк — желательно `LF`.
6. Не добавляй в публичные файлы секреты, токены, пароли, внутренние пути сервера.
7. Если не можешь сгенерировать бинарные изображения автоматически — создай понятный TODO и не коммить фейковые файлы.
8. После всех изменений очисть кэш:
    ```bash
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan cache:clear
    ```
9. Если используешь Laravel Sail:
    ```bash
    ./vendor/bin/sail artisan ...
    ```

---

## 2. Обязательные артефакты после выполнения

После работы должны существовать и корректно работать:

```text
public/favicon.ico
public/favicons/
public/favicons/apple-touch-icon.png
public/favicons/favicon-16x16.png
public/favicons/favicon-32x32.png
public/favicons/favicon-48x48.png
public/favicons/icon-192x192.png
public/favicons/icon-512x512.png
public/favicons/maskable-192x192.png
public/favicons/maskable-512x512.png
public/site.webmanifest
public/robots.txt
public/.htaccess
public/ai.txt
public/llms.txt
public/sitemap.xml
resources/views/partials/seo.blade.php
config/seo.php
docs/SEO_AUDIT.md
docs/SEO_TODO.md
```

Опционально, если применимо:

```text
app/Console/Commands/GenerateFavicons.php
app/Console/Commands/GenerateSeoFiles.php
public/.well-known/ai.txt
public/images/og-image.png
```

---

## 3. Предварительный аудит проекта

### 3.1. Найди основной layout

Ищи файлы:

```bash
grep -R "layouts" resources/views
grep -R "<head>" resources/views
grep -R "app.blade.php" resources/views
```

Типичные места:

```text
resources/views/layouts/app.blade.php
resources/views/components/layout.blade.php
resources/views/base.blade.php
```

Если используется Livewire, Inertia, Blade components или отдельные page layouts — определи, где именно формируется `<head>`.

### 3.2. Найди логотип

Ищи логотип в шаблонах и публичных папках:

```bash
grep -Ri "logo" resources/views
grep -Ri "logo" public
ls public/images
ls public/img
ls public/assets
```

Нужно найти максимально качественный исходник:

- `.svg`
- `.png`
- `.jpg`
- `.webp`

Если логотип подключается через `asset(...)`, `url(...)`, `Vite::asset(...)` или хранится в `public/storage` — найди реальный файл.

### 3.3. Проверь текущие SEO-теги

Найди:

```bash
grep -R "<title" resources/views
grep -R "meta name=\"description\"" resources/views
grep -R "og:" resources/views
grep -R "canonical" resources/views
grep -R "robots" resources/views
grep -R "application/ld+json" resources/views
```

Занеси результаты в `docs/SEO_AUDIT.md`:

- какой у проекта `<html lang>`;
- есть ли `<meta charset="utf-8">`;
- есть ли дублирующиеся мета-теги;
- есть ли `canonical`;
- есть ли Open Graph / Twitter Card;
- есть ли JSON-LD;
- есть ли `favicon.ico`;
- есть ли `robots.txt`;
- есть ли `sitemap.xml`;
- есть ли `manifest`;
- какой `APP_URL` в `.env`;
- какие маршруты публичные;
- есть ли мультиязычность.

---

## 4. Настрой базовую SEO/GEO-конфигурацию

Создай файл:

```php
// config/seo.php

<?php

return [
    'enabled' => env('SEO_ENABLED', true),

    'site_name' => env('SEO_SITE_NAME', env('APP_NAME', 'Site')),
    'default_title' => env('SEO_TITLE', env('APP_NAME', 'Site')),
    'title_suffix' => env('SEO_TITLE_SUFFIX', ''),
    'description' => env('SEO_DESCRIPTION', ''),

    // Должен совпадать с каноническим доменом сайта.
    'url' => rtrim(env('SEO_URL', env('APP_URL', 'http://localhost')), '/'),

    'locale' => env('SEO_LOCALE', 'ru_RU'),
    'locales' => [
        // Пример:
        // 'ru' => '/',
        // 'en' => '/en',
    ],

    'image' => env('SEO_IMAGE', '/images/og-image.png'),
    'theme_color' => env('SEO_THEME_COLOR', '#ffffff'),
    'background_color' => env('SEO_BACKGROUND_COLOR', '#ffffff'),

    'robots' => [
        'index' => env('SEO_INDEX', true),
        'follow' => env('SEO_FOLLOW', true),
    ],

    'geo' => [
        // Включать только если у сайта есть реальная географическая привязка.
        'enabled' => env('SEO_GEO_ENABLED', false),
        'region' => env('SEO_GEO_REGION', ''), // Например: RU
        'placename' => env('SEO_GEO_PLACENAME', ''), // Например: Москва
        'position' => env('SEO_GEO_POSITION', ''), // Например: 55.7558;37.6173
    ],

    'organization' => [
        'name' => env('SEO_ORG_NAME', env('APP_NAME', '')),
        'legal_name' => env('SEO_ORG_LEGAL_NAME', ''),
        'logo' => env('SEO_ORG_LOGO', '/favicon.ico'),
        'phone' => env('SEO_ORG_PHONE', ''),
        'email' => env('SEO_ORG_EMAIL', ''),
        'vat_id' => env('SEO_ORG_VAT_ID', ''),
        'tax_id' => env('SEO_ORG_TAX_ID', ''),

        'address' => [
            'street' => env('SEO_ORG_STREET', ''),
            'locality' => env('SEO_ORG_LOCALITY', ''),
            'region' => env('SEO_ORG_REGION', ''),
            'postal_code' => env('SEO_ORG_POSTAL_CODE', ''),
            'country' => env('SEO_ORG_COUNTRY', ''),
        ],

        'social' => [
            // Пример:
            // 'https://t.me/example',
            // 'https://vk.com/example',
        ],
    ],

    'ai' => [
        // Разрешить ли AI-краулерам доступ к публичному контенту.
        'allowed' => env('SEO_AI_ALLOWED', true),

        // Разрешено ли использование контента для обучения моделей.
        // Если нет — явно пропиши это в ai.txt и юридических страницах.
        'training_allowed' => env('SEO_AI_TRAINING_ALLOWED', false),

        'contact' => env('SEO_AI_CONTACT', ''),
        'policy_url' => env('SEO_AI_POLICY_URL', '/ai.txt'),
    ],

    'sitemap' => [
        // Статические страницы, которые точно должны попасть в sitemap.
        'urls' => [
            [
                'loc' => '/',
                'priority' => '1.0',
                'changefreq' => 'daily',
            ],
        ],

        // Сюда агент может добавить модели: статьи, страницы, товары, категории.
        'providers' => [],
    ],
];
```

Добавь в `.env.example` и, при необходимости, в локальный `.env`:

```dotenv
SEO_ENABLED=true
SEO_SITE_NAME="Название сайта"
SEO_TITLE="Название сайта"
SEO_TITLE_SUFFIX=""
SEO_DESCRIPTION="Короткое описание сайта для поиска и социальных сетей."
SEO_URL="https://example.com"
SEO_LOCALE="ru_RU"
SEO_IMAGE="/images/og-image.png"
SEO_THEME_COLOR="#ffffff"
SEO_BACKGROUND_COLOR="#ffffff"

SEO_GEO_ENABLED=false
SEO_GEO_REGION=""
SEO_GEO_PLACENAME=""
SEO_GEO_POSITION=""

SEO_ORG_NAME=""
SEO_ORG_PHONE=""
SEO_ORG_EMAIL=""
SEO_ORG_STREET=""
SEO_ORG_LOCALITY=""
SEO_ORG_REGION=""
SEO_ORG_POSTAL_CODE=""
SEO_ORG_COUNTRY=""

SEO_AI_ALLOWED=true
SEO_AI_TRAINING_ALLOWED=false
SEO_AI_CONTACT=""
```

---

## 5. HTML head: meta, Open Graph, Twitter, GEO, favicon, manifest

### 5.1. Создай partial

Создай:

```blade
resources/views/partials/seo.blade.php
```

Пример:

```blade
@php
    $seo = array_merge([
        'title' => config('seo.default_title'),
        'description' => config('seo.description'),
        'canonical' => request()->url(),
        'robots' => null,
        'type' => 'website',
        'image' => config('seo.image'),
        'schema' => [],
    ], $seo ?? []);

    $seoTitle = trim($seo['title']);

    if (config('seo.title_suffix') && !str_contains($seoTitle, config('seo.title_suffix'))) {
        $seoTitle = trim($seoTitle . ' | ' . config('seo.title_suffix'));
    }

    $seoDescription = trim((string) $seo['description']);

    $seoImage = null;
    if (!empty($seo['image'])) {
        $seoImage = preg_match('#^https?://#i', $seo['image'])
            ? $seo['image']
            : asset($seo['image']);
    }

    $robotsDirectives = [];

    if ($seo['robots']) {
        $robotsDirectives[] = $seo['robots'];
    } elseif (config('seo.robots.index') === false || config('seo.robots.follow') === false) {
        $robotsDirectives[] = config('seo.robots.index') ? 'index' : 'noindex';
        $robotsDirectives[] = config('seo.robots.follow') ? 'follow' : 'nofollow';
    }

    $schemaGraph = [];

    $schemaGraph[] = [
        '@type' => 'WebSite',
        'name' => config('seo.site_name'),
        'url' => config('seo.url'),
        'inLanguage' => str_replace('_', '-', config('app.locale', 'ru')),
    ];

    if (config('seo.organization.name')) {
        $organization = [
            '@type' => config('seo.geo.enabled') ? 'LocalBusiness' : 'Organization',
            'name' => config('seo.organization.name'),
            'url' => config('seo.url'),
        ];

        if (config('seo.organization.legal_name')) {
            $organization['legalName'] = config('seo.organization.legal_name');
        }

        if (config('seo.organization.logo')) {
            $organization['logo'] = asset(config('seo.organization.logo'));
        }

        if (config('seo.organization.phone')) {
            $organization['telephone'] = config('seo.organization.phone');
        }

        if (config('seo.organization.email')) {
            $organization['email'] = config('seo.organization.email');
        }

        if (config('seo.organization.social')) {
            $organization['sameAs'] = array_values(config('seo.organization.social'));
        }

        $address = array_filter([
            'streetAddress' => config('seo.organization.address.street'),
            'addressLocality' => config('seo.organization.address.locality'),
            'addressRegion' => config('seo.organization.address.region'),
            'postalCode' => config('seo.organization.address.postal_code'),
            'addressCountry' => config('seo.organization.address.country'),
        ]);

        if ($address) {
            $organization['address'] = array_merge(['@type' => 'PostalAddress'], $address);
        }

        if (config('seo.geo.enabled') && config('seo.geo.position')) {
            $position = str_replace(';', ',', config('seo.geo.position'));
            [$lat, $lon] = array_map('trim', explode(',', $position) + [null, null]);

            if ($lat && $lon) {
                $organization['geo'] = [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $lat,
                    'longitude' => $lon,
                ];
            }
        }

        $schemaGraph[] = $organization;
    }

    foreach (($seo['schema'] ?? []) as $extraSchema) {
        $schemaGraph[] = $extraSchema;
    }

    $schemaGraph = array_values(array_filter($schemaGraph));
@endphp

@if(config('seo.enabled'))
    <title>{{ $seoTitle }}</title>

    @if($seoDescription)
        <meta name="description" content="{{ $seoDescription }}">
    @endif

    @if(!empty($robotsDirectives))
        <meta name="robots" content="{{ implode(',', $robotsDirectives) }}">
    @endif

    <link rel="canonical" href="{{ $seo['canonical'] }}">

    {{-- Open Graph --}}
    <meta property="og:site_name" content="{{ config('seo.site_name') }}">
    <meta property="og:type" content="{{ $seo['type'] }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seo['canonical'] }}">
    <meta property="og:locale" content="{{ config('seo.locale') }}">

    @if($seoImage)
        <meta property="og:image" content="{{ $seoImage }}">
        <meta property="og:image:alt" content="{{ $seoTitle }}">
    @endif

    {{-- Twitter --}}
    <meta name="twitter:card" content="{{ $seoImage ? 'summary_large_image' : 'summary' }}">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">
    @if($seoImage)
        <meta name="twitter:image" content="{{ $seoImage }}">
    @endif

    {{-- GEO meta only for real geographic projects --}}
    @if(config('seo.geo.enabled'))
        @if(config('seo.geo.region'))
            <meta name="geo.region" content="{{ config('seo.geo.region') }}">
        @endif

        @if(config('seo.geo.placename'))
            <meta name="geo.placename" content="{{ config('seo.geo.placename') }}">
        @endif

        @if(config('seo.geo.position'))
            <meta name="geo.position" content="{{ config('seo.geo.position') }}">
            <meta name="ICBM" content="{{ str_replace(';', ',', config('seo.geo.position')) }}">
        @endif
    @endif

    {{-- Multilingual hreflang --}}
    @foreach(config('seo.locales', []) as $locale => $path)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ url($path) }}">
    @endforeach

    @if(config('seo.locales'))
        <link rel="alternate" hreflang="x-default" href="{{ url('/') }}">
    @endif

    {{-- Favicons and manifest --}}
    <link rel="icon" href="{{ asset('favicon.ico') }}" sizes="any">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicons/favicon-16x16.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicons/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicons/favicon-48x48.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicons/apple-touch-icon.png') }}">

    @if(file_exists(public_path('favicons/favicon.svg')))
        <link rel="icon" type="image/svg+xml" href="{{ asset('favicons/favicon.svg') }}">
    @endif

    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="{{ config('seo.theme_color') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="{{ config('seo.site_name') }}">

    {{-- JSON-LD --}}
    @if(count($schemaGraph))
        <script type="application/ld+json">{!! json_encode([
            '@context' => 'https://schema.org',
            '@graph' => $schemaGraph,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}</script>
    @endif
@endif
```

### 5.2. Подключи partial в layout

В основном шаблоне внутри `<head>` добавь:

```blade
@include('partials.seo')
```

Если в шаблоне уже есть:

```blade
<title>...</title>
<meta name="description" content="...">
```

убери дублирующиеся теги или перенеси их в систему `$seo`, чтобы не было двух `<title>` и двух `description`.

### 5.3. Обнови `<html lang>`

Найди тег `<html>` и сделай:

```blade
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
```

Если есть мультиязычность, используй реальную локаль страницы.

---

## 6. Favicon из логотипа

### 6.1. Где размещать

Для Laravel браузерный корень — это `public/`.

Поэтому основной файл должен быть:

```text
public/favicon.ico
```

Если сервер по ошибке смотрит не в `public/`, а в корень проекта, лучше исправить конфигурацию веб-сервера. В качестве временного компромисса можно создать копию:

```text
favicon.ico
```

но основной рабочий файл — `public/favicon.ico`.

### 6.2. Создай папку для иконок

```text
public/favicons/
```

В ней должны быть:

```text
favicon-16x16.png
favicon-32x32.png
favicon-48x48.png
apple-touch-icon.png
icon-192x192.png
icon-512x512.png
maskable-192x192.png
maskable-512x512.png
favicon.svg
```

Если исходный логотип — SVG, аккуратно скопируй или адаптируй его в:

```text
public/favicons/favicon.svg
```

Убедись, что у него есть корректный `viewBox`, нет внешних битых ссылок и он нормально смотрится в маленьком размере.

### 6.3. Генерация через ImageMagick, если доступен

Проверь:

```bash
magick -version
```

или:

```bash
convert -version
```

Если ImageMagick есть, используй логотип как исходник. Пример для `magick`:

```bash
mkdir -p public/favicons

magick public/images/logo.png \
  -background transparent \
  -gravity center \
  -resize 512x512 \
  -extent 512x512 \
  public/favicons/base-512.png

magick public/favicons/base-512.png \
  -define icon:auto-resize=16,32,48,64 \
  public/favicon.ico

magick public/favicons/base-512.png -resize 16x16 public/favicons/favicon-16x16.png
magick public/favicons/base-512.png -resize 32x32 public/favicons/favicon-32x32.png
magick public/favicons/base-512.png -resize 48x48 public/favicons/favicon-48x48.png
magick public/favicons/base-512.png -resize 180x180 public/favicons/apple-touch-icon.png
magick public/favicons/base-512.png -resize 192x192 public/favicons/icon-192x192.png
magick public/favicons/base-512.png -resize 512x512 public/favicons/icon-512x512.png
magick public/favicons/base-512.png -resize 192x192 public/favicons/maskable-192x192.png
magick public/favicons/base-512.png -resize 512x512 public/favicons/maskable-512x512.png
```

Если логотип — SVG:

```bash
magick public/images/logo.svg \
  -background transparent \
  -resize 512x512 \
  public/favicons/base-512.png
```

### 6.4. Fallback: artisan-команда на PHP GD

Если ImageMagick недоступен, создай команду:

```text
app/Console/Commands/GenerateFavicons.php
```

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateFavicons extends Command
{
    protected $signature = 'seo:generate-favicons {logo? : Путь к логотипу относительно корня проекта или public/}';

    protected $description = 'Generate favicon.ico and device icons from logo using PHP GD';

    public function handle(): int
    {
        if (!extension_loaded('gd')) {
            $this->error('PHP GD extension is required. Install php-gd or use ImageMagick.');
            return self::FAILURE;
        }

        $logo = $this->resolveLogo($this->argument('logo'));

        if (!$logo) {
            $this->error('Logo not found. Pass path explicitly: php artisan seo:generate-favicons public/images/logo.png');
            return self::FAILURE;
        }

        if (strtolower(pathinfo($logo, PATHINFO_EXTENSION)) === 'svg') {
            $this->error('SVG cannot be processed by GD directly. Convert it to PNG first, e.g. with ImageMagick.');
            return self::FAILURE;
        }

        $source = $this->createImage($logo);

        if (!$source) {
            $this->error("Cannot read image: {$logo}");
            return self::FAILURE;
        }

        $square = $this->makeSquare($source);
        imagedestroy($source);

        if (!is_dir(public_path('favicons'))) {
            mkdir(public_path('favicons'), 0755, true);
        }

        $icoFrames = [];

        foreach ([16, 32, 48, 64] as $size) {
            $icoFrames[$size] = $this->png($square, $size);
        }

        file_put_contents(public_path('favicon.ico'), $this->ico($icoFrames));

        $files = [
            'favicon-16x16.png' => 16,
            'favicon-32x32.png' => 32,
            'favicon-48x48.png' => 48,
            'apple-touch-icon.png' => 180,
            'icon-192x192.png' => 192,
            'icon-512x512.png' => 512,
            'maskable-192x192.png' => 192,
            'maskable-512x512.png' => 512,
        ];

        foreach ($files as $name => $size) {
            file_put_contents(
                public_path("favicons/{$name}"),
                $this->png($square, $size)
            );
        }

        imagedestroy($square);

        $this->info('Favicons generated in public/favicon.ico and public/favicons/.');

        return self::SUCCESS;
    }

    private function resolveLogo(?string $logo): ?string
    {
        if ($logo) {
            foreach ([base_path($logo), public_path($logo), $logo] as $path) {
                if (is_string($path) && is_file($path)) {
                    return $path;
                }
            }
        }

        $candidates = [
            public_path('images/logo.png'),
            public_path('img/logo.png'),
            public_path('assets/img/logo.png'),
            public_path('favicon-source.png'),
        ];

        foreach ($candidates as $candidate) {
            if (is_file($candidate)) {
                return $candidate;
            }
        }

        return null;
    }

    private function createImage(string $path): ?\GdImage
    {
        $contents = @file_get_contents($path);

        if ($contents === false) {
            return null;
        }

        $image = @imagecreatefromstring($contents);

        return $image === false ? null : $image;
    }

    private function makeSquare(\GdImage $source): \GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);
        $size = 512;

        $canvas = imagecreatetruecolor($size, $size);

        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);

        $transparent = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
        imagefill($canvas, 0, 0, $transparent);

        $scale = min($size / $width, $size / $height) * 0.92;

        $newWidth = (int) round($width * $scale);
        $newHeight = (int) round($height * $scale);

        $x = (int) round(($size - $newWidth) / 2);
        $y = (int) round(($size - $newHeight) / 2);

        imagecopyresampled(
            $canvas,
            $source,
            $x,
            $y,
            0,
            0,
            $newWidth,
            $newHeight,
            $width,
            $height
        );

        return $canvas;
    }

    private function png(\GdImage $square, int $size): string
    {
        $image = imagecreatetruecolor($size, $size);

        imagealphablending($image, false);
        imagesavealpha($image, true);

        $transparent = imagecolorallocatealpha($image, 0, 0, 0, 127);
        imagefill($image, 0, 0, $transparent);

        imagecopyresampled(
            $image,
            $square,
            0,
            0,
            0,
            0,
            $size,
            $size,
            imagesx($square),
            imagesy($square)
        );

        ob_start();
        imagepng($image, null, 9);
        imagedestroy($image);

        return (string) ob_get_clean();
    }

    private function ico(array $frames): string
    {
        $count = count($frames);

        $header = pack('vvv', 0, 1, $count);
        $entries = '';
        $data = '';

        $offset = 6 + $count * 16;

        foreach ($frames as $size => $png) {
            $byte = $size >= 256 ? 0 : $size;

            $entries .= pack(
                'CCCCvvVV',
                $byte,
                $byte,
                0,
                0,
                1,
                32,
                strlen($png),
                $offset
            );

            $data .= $png;
            $offset += strlen($png);
        }

        return $header . $entries . $data;
    }
}
```

Запуск:

```bash
php artisan seo:generate-favicons public/images/logo.png
```

Если логотип лежит в `public/`, можно:

```bash
php artisan seo:generate-favicons images/logo.png
```

---

## 7. Manifest для устройств

Создай:

```text
public/site.webmanifest
```

Шаблон:

```json
{
    "name": "Название сайта",
    "short_name": "Сайт",
    "description": "Короткое описание сайта.",
    "id": "/",
    "start_url": "/",
    "scope": "/",
    "display": "standalone",
    "orientation": "portrait-primary",
    "lang": "ru",
    "dir": "ltr",
    "background_color": "#ffffff",
    "theme_color": "#ffffff",
    "icons": [
        {
            "src": "/favicons/icon-192x192.png",
            "sizes": "192x192",
            "type": "image/png",
            "purpose": "any"
        },
        {
            "src": "/favicons/icon-512x512.png",
            "sizes": "512x512",
            "type": "image/png",
            "purpose": "any"
        },
        {
            "src": "/favicons/maskable-192x192.png",
            "sizes": "192x192",
            "type": "image/png",
            "purpose": "maskable"
        },
        {
            "src": "/favicons/maskable-512x512.png",
            "sizes": "512x512",
            "type": "image/png",
            "purpose": "maskable"
        }
    ]
}
```

Замени:

- `name`;
- `short_name`;
- `description`;
- `background_color`;
- `theme_color`.

Проверь, что в `<head>` есть:

```blade
<link rel="manifest" href="{{ asset('site.webmanifest') }}">
```

---

## 8. robots.txt

Создай:

```text
public/robots.txt
```

Базовый шаблон:

```text
User-agent: *
Allow: /

Disallow: /admin
Disallow: /nova
Disallow: /filament
Disallow: /horizon
Disallow: /telescope
Disallow: /login
Disallow: /logout
Disallow: /register
Disallow: /password
Disallow: /api/
Disallow: /profile
Disallow: /account
Disallow: /cart
Disallow: /checkout
Disallow: /search
Disallow: /*utm_
Disallow: /*gclid=
Disallow: /*yclid=
Disallow: /*fbclid=
Disallow: /*?*sort=
Disallow: /*?*filter=

Allow: /favicon.ico
Allow: /favicons/
Allow: /site.webmanifest
Allow: /build/
Allow: /css/
Allow: /js/
Allow: /images/
Allow: /storage/

User-agent: GPTBot
Allow: /

User-agent: ClaudeBot
Allow: /

User-agent: Google-Extended
Allow: /

User-agent: PerplexityBot
Allow: /

User-agent: Bingbot
Allow: /

Sitemap: https://example.com/sitemap.xml
```

Замени:

```text
https://example.com/sitemap.xml
```

на реальный канонический домен из `APP_URL` или `SEO_URL`.

Важно:

- не блокируй CSS/JS, если они нужны для рендера страниц;
- не блокируй изображения, которые важны для SEO, если они доступны публично;
- не включай внутренние параметры, токены, личные пути;
- если AI-доступ запрещен, замени `Allow: /` для AI-агентов на `Disallow: /`.

---

## 9. ai.txt

Создай:

```text
public/ai.txt
```

Формат — человекочитаемый и машиночитаемый текст. Пример:

```text
# AI Policy

name: Название сайта
url: https://example.com
description: Короткое описание сайта и его назначения.
contact: contact@example.com
robots: https://example.com/robots.txt
llms: https://example.com/llms.txt
sitemap: https://example.com/sitemap.xml

ai_crawling_allowed: true
ai_answer_generation_allowed: true
ai_training_allowed: false

preferred_attribution: При использовании материалов указывайте название сайта и активную ссылку на первоисточник.

restricted_paths:
  - /admin
  - /login
  - /profile
  - /cart
  - /checkout

notes:
  - Публичный контент разрешено индексировать и использовать для формирования ответов.
  - Использование контента для обучения моделей без отдельного согласия не разрешено.
```

Если у проекта есть юридическая страница об использовании контента, добавь ссылку:

```text
policy: https://example.com/legal/ai-policy
```

Опционально создай также:

```text
public/.well-known/ai.txt
```

с тем же содержимым.

---

## 10. llms.txt

Создай:

```text
public/llms.txt
```

Это файл для LLM/AI-агентов. Он должен быть полезным, коротким и не спамным.

Шаблон:

```markdown
# Название сайта

> Короткое и точное описание сайта: чем занимается проект, для кого он и какую проблему решает.

## Official links

- Homepage: https://example.com/
- Sitemap: https://example.com/sitemap.xml
- Robots: https://example.com/robots.txt
- Contact: https://example.com/contacts
- Privacy policy: https://example.com/privacy
- Terms: https://example.com/terms

## Main sections

- Раздел 1: https://example.com/section-1 — краткое назначение раздела.
- Раздел 2: https://example.com/section-2 — краткое назначение раздела.
- Блог: https://example.com/blog — статьи, новости, экспертные материалы.
- Контакты: https://example.com/contacts — адрес, телефон, график работы, форма обратной связи.

## Facts

- Официальное название: Название компании.
- Город/регион: если применимо.
- Язык контента: русский.
- Кодировка: UTF-8.
- Основный формат: HTML с JSON-LD разметкой.

## AI usage policy

- Public pages may be crawled and summarized.
- Answers may cite this site with canonical URLs.
- Do not use content for model training without separate permission.
- For removal or copyright requests contact: contact@example.com.

## Citation preference

При цитировании используйте канонический URL страницы и сохраняйте оригинальный заголовок.
```

Заполни `Main sections` на основе реальных маршрутов проекта.

Не добавляй:

- переспам ключами;
- скрытые ссылки;
- нерелевантные страницы;
- ложные факты.

---

## 11. sitemap.xml

Создай:

```text
public/sitemap.xml
```

Минимальный шаблон:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
  <url>
    <loc>https://example.com/</loc>
    <changefreq>daily</changefreq>
    <priority>1.0</priority>
  </url>
  <url>
    <loc>https://example.com/contacts</loc>
    <changefreq>monthly</changefreq>
    <priority>0.8</priority>
  </url>
</urlset>
```

Требования:

- только существующие публичные страницы;
- только канонические URL;
- без страниц входа, регистрации, админки, API, личных кабинетов;
- без битых 404;
- без дублей;
- кодировка `UTF-8`;
- дата в формате `YYYY-MM-DD`, если добавляешь `lastmod`.

Если сайт мультиязычный, добавь `xhtml:link`:

```xml
<url>
  <loc>https://example.com/</loc>
  <xhtml:link rel="alternate" hreflang="ru" href="https://example.com/"/>
  <xhtml:link rel="alternate" hreflang="en" href="https://example.com/en"/>
  <xhtml:link rel="alternate" hreflang="x-default" href="https://example.com/"/>
</urlset>
```

Если URL больше 50 000 или файл получается более 50 MB, создай индекс:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <sitemap>
    <loc>https://example.com/sitemap-pages.xml</loc>
  </sitemap>
  <sitemap>
    <loc>https://example.com/sitemap-posts.xml</loc>
  </sitemap>
</sitemapindex>
```

А основной файл назови:

```text
public/sitemap.xml
```

и сделай его индексом.

---

## 12. Генерация sitemap, robots, ai.txt и llms.txt из конфига

Чтобы файлы не устаревали, создай команду:

```text
app/Console/Commands/GenerateSeoFiles.php
```

Команда должна:

1. Читать `config/seo.php`.
2. Собирать URL из `config('seo.sitemap.urls')`.
3. При наличии провайдеров собирать страницы из БД: статьи, страницы, товары, категории.
4. Убирать дубли.
5. Приводить все URL к абсолютному виду через `config('seo.url')`.
6. Убирать хвостовые слеши, кроме главной.
7. Записывать файлы в `public/` в UTF-8 без BOM.

Пример сигнатуры:

```php
protected $signature = 'seo:generate-files';
```

Запуск:

```bash
php artisan seo:generate-files
```

Если команда не регистрируется автоматически, подключи её в:

- `app/Console/Kernel.php` для старых версий;
- `bootstrap/app.php` или `routes/console.php` для Laravel 11+.

---

## 13. .htaccess

Создай или аккуратно обнови:

```text
public/.htaccess
```

В Laravel уже может быть стандартный `.htaccess`. Его нельзя ломать. Сначала сохрани текущий, затем добавь нужные блоки.

Пример:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# UTF-8 for text files
AddDefaultCharset UTF-8
AddCharset UTF-8 .html .htm .css .js .json .xml .txt .svg .webmanifest .rss

# Correct MIME types
AddType image/x-icon .ico
AddType image/svg+xml .svg .svgz
AddType application/manifest+json .webmanifest
AddType application/xml .xml .rss .atom
AddType text/plain .txt .md

# Basic caching for static SEO assets
<IfModule mod_expires.c>
    ExpiresActive On

    ExpiresByType image/x-icon "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"

    ExpiresByType application/manifest+json "access plus 1 day"
    ExpiresByType application/xml "access plus 1 hour"
    ExpiresByType text/plain "access plus 1 hour"
</IfModule>

# Security headers
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set X-Frame-Options "SAMEORIGIN"
</IfModule>

# Deny sensitive files if they somehow appear in public
<FilesMatch "^\.env.*$">
    Require all denied
</FilesMatch>

<FilesMatch "^\.htaccess$">
    Require all denied
</FilesMatch>

<FilesMatch "^\.htpasswd$">
    Require all denied
</FilesMatch>
```

Важно:

- `.htaccess` должен быть в кодировке `UTF-8 without BOM`, но комментарии лучше писать на английском или вообще убрать;
- не используй кириллицу внутри `.htaccess`;
- если сервер работает на Nginx, `.htaccess` не применяется. В этом случае создай заметку в `docs/SEO_TODO.md`, что эквивалентные настройки нужно перенести в конфиг Nginx.

---

## 14. HTTPS и канонический домен

Проверь `.env`:

```dotenv
APP_URL="https://example.com"
SEO_URL="https://example.com"
```

Если сайт в production работает по HTTPS, добавь в `AppServiceProvider`:

```php
use Illuminate\Support\Facades\URL;

public function boot(): void
{
    if (app()->environment('production')) {
        URL::forceScheme('https');
    }
}
```

Если сайт находится за балансировщиком или Cloudflare, убедись, что настроен `TrustProxies`:

```text
app/Http/Middleware/TrustProxies.php
```

Иначе `request()->secure()` и генерация абсолютных ссылок могут работать некорректно.

Если нужно выбрать один канонический домен, например `https://example.com` вместо `https://www.example.com`, настрой 301-редирект. Лучше делать это через веб-сервер или отдельный middleware, а не хардкодить в шаблонах.

---

## 15. Кодировка и «кракозябры»

Все текстовые файлы должны открываться корректно.

Проверь:

- `public/robots.txt`
- `public/ai.txt`
- `public/llms.txt`
- `public/sitemap.xml`
- `public/site.webmanifest`
- `public/.htaccess`
- Blade-шаблоны
- `config/seo.php`

Требования:

- кодировка: `UTF-8`;
- без BOM;
- в HTML должен быть:
    ```html
    <meta charset="utf-8" />
    ```
- в XML должен быть:
    ```xml
    <?xml version="1.0" encoding="UTF-8"?>
    ```
- `.htaccess` не должен содержать BOM;
- в `.htaccess` лучше не использовать не-ASCII символы;
- для статических файлов в `.htaccess` уже заданы `AddDefaultCharset UTF-8` и `AddCharset UTF-8`.

Если файл открывается «кракозябрами»:

1. Открой файл в редакторе.
2. Определи текущую кодировку.
3. Пересохрани как `UTF-8 without BOM`.
4. Проверь через:
    ```bash
    curl -I http://localhost:8000/robots.txt
    curl -s http://localhost:8000/robots.txt
    ```
5. Если сервер отдаёт неправильный `Content-Type`, исправь `.htaccess` или настройку веб-сервера.

Для динамических ответов в Laravel всегда задавай заголовок:

```php
return response($content, 200, [
    'Content-Type' => 'text/plain; charset=UTF-8',
]);
```

Для XML:

```php
return response($xml, 200, [
    'Content-Type' => 'application/xml; charset=UTF-8',
]);
```

Для manifest:

```php
return response($json, 200, [
    'Content-Type' => 'application/manifest+json; charset=UTF-8',
]);
```

---

## 16. Проверка файлов в браузере и через curl

Если локально запущен сервер:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

Проверь заголовки:

```bash
curl -I http://127.0.0.1:8000/
curl -I http://127.0.0.1:8000/favicon.ico
curl -I http://127.0.0.1:8000/site.webmanifest
curl -I http://127.0.0.1:8000/robots.txt
curl -I http://127.0.0.1:8000/ai.txt
curl -I http://127.0.0.1:8000/llms.txt
curl -I http://127.0.0.1:8000/sitemap.xml
```

Проверь содержимое:

```bash
curl -s http://127.0.0.1:8000/robots.txt
curl -s http://127.0.0.1:8000/ai.txt
curl -s http://127.0.0.1:8000/llms.txt
curl -s http://127.0.0.1:8000/site.webmanifest
```

Проверь валидность `sitemap.xml`:

```bash
curl -s http://127.0.0.1:8000/sitemap.xml | xmllint --noout -
```

Проверь валидность manifest:

```bash
curl -s http://127.0.0.1:8000/site.webmanifest | jq .
```

Ожидаемые ответы:

| Файл                | Ожидаемый статус | Ожидаемый Content-Type                                     |
| ------------------- | ---------------: | ---------------------------------------------------------- |
| `/favicon.ico`      |              200 | `image/x-icon`, `image/vnd.microsoft.icon` или `image/png` |
| `/site.webmanifest` |              200 | `application/manifest+json`                                |
| `/robots.txt`       |              200 | `text/plain`                                               |
| `/ai.txt`           |              200 | `text/plain`                                               |
| `/llms.txt`         |              200 | `text/plain` или `text/markdown`                           |
| `/sitemap.xml`      |              200 | `application/xml` или `text/xml`                           |

Если `.htaccess` открывается в браузере как текст — это нежелательно. Он должен быть недоступен извне. В шаблоне выше есть запрет для `.htaccess`.

---

## 17. Дополнительная SEO-проверка страниц

Проверь и при необходимости поправь:

1. Уникальные `<title>` для каждой страницы.
2. Уникальные `meta description`.
3. Один `<h1>` на страницу.
4. Логичная иерархия `h1`–`h3`.
5. Альтернативный текст у изображений:
    ```blade
    <img src="..." alt="...">
    ```
6. `title` у ссылок только там, где он реально нужен.
7. Внутренняя перелинковка.
8. Хлебные крошки на внутренних страницах.
9. Страница контактов с адресом, телефоном и графиком работы, если это локальный бизнес.
10. Отсутствие битых ссылок.
11. Отсутствие дублей страниц.
12. Корректные 404 и 500 страницы.
13. Служебные страницы не индексируются.

Для служебных страниц передавай:

```php
$seo = [
    'robots' => 'noindex,nofollow',
];
```

Например:

- `/login`
- `/register`
- `/password/*`
- `/profile`
- `/cart`
- `/checkout`
- `/search`
- админ-панель.

---

## 18. Структурированные данные JSON-LD

Минимально добавь:

### 18.1. WebSite

```json
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Название сайта",
    "url": "https://example.com"
}
```

### 18.2. Organization или LocalBusiness

Если организация:

```json
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Название организации",
    "url": "https://example.com",
    "logo": "https://example.com/favicon.ico",
    "telephone": "+70000000000",
    "email": "info@example.com",
    "sameAs": ["https://vk.com/example", "https://t.me/example"]
}
```

Если локальный бизнес:

```json
{
    "@context": "https://schema.org",
    "@type": "LocalBusiness",
    "name": "Название компании",
    "url": "https://example.com",
    "telephone": "+70000000000",
    "email": "info@example.com",
    "address": {
        "@type": "PostalAddress",
        "streetAddress": "Улица, дом",
        "addressLocality": "Город",
        "postalCode": "000000",
        "addressCountry": "RU"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": 55.7558,
        "longitude": 37.6173
    }
}
```

Не добавляй:

- несуществующие адреса;
- случайные координаты;
- чужие телефоны;
- фейковые отзывы;
- несуществующие рейтинги.

---

## 19. Хлебные крошки

Для внутренних страниц добавь BreadcrumbList.

Пример JSON-LD:

```json
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Главная",
            "item": "https://example.com/"
        },
        {
            "@type": "ListItem",
            "position": 2,
            "name": "Раздел",
            "item": "https://example.com/section"
        }
    ]
}
```

В контроллере или компоненте страницы можно передавать:

```php
$seo['schema'][] = [
    '@type' => 'BreadcrumbList',
    'itemListElement' => [
        [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Главная',
            'item' => url('/'),
        ],
        [
            '@type' => 'ListItem',
            'position' => 2,
            'name' => 'Текущая страница',
            'item' => url()->current(),
        ],
    ],
];
```

---

## 20. Для блога, новостей и статей

Если есть статьи, добавь:

1. `Article` или `BlogPosting` JSON-LD.
2. `datePublished`.
3. `dateModified`.
4. `author`.
5. `headline`.
6. `description`.
7. `image`.
8. `mainEntityOfPage`.

Пример:

```json
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "Заголовок статьи",
    "description": "Краткое описание статьи",
    "image": "https://example.com/images/article.jpg",
    "datePublished": "2026-01-01",
    "dateModified": "2026-01-02",
    "author": {
        "@type": "Person",
        "name": "Имя автора"
    },
    "publisher": {
        "@type": "Organization",
        "name": "Название сайта",
        "logo": {
            "@type": "ImageObject",
            "url": "https://example.com/favicon.ico"
        }
    },
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "https://example.com/article-url"
    }
}
```

Для блога обязательно добавь статьи в `sitemap.xml`.

---

## 21. Для товаров и услуг

Если проект — интернет-магазин или каталог:

1. Добавь `Product` только там, где есть реальные товары.
2. Добавь `Offer`, цену, валюту, доступность.
3. Не выдумывай отзывы и рейтинги.
4. Добавь категории в sitemap.
5. Не индексируй технические страницы корзины и чекаута.
6. Используй канонические страницы для фильтров.

---

## 22. Мультиязычность

Если есть несколько языков:

1. Настрой отдельные маршруты или префиксы.
2. Добавь `hreflang` в HTML.
3. Добавь `hreflang` в sitemap.
4. Укажи `x-default`.
5. У каждой языковой версии должен быть свой canonical.
6. Не смешивай языки на одной странице без необходимости.

Пример:

```blade
<link rel="alternate" hreflang="ru" href="https://example.com/">
<link rel="alternate" hreflang="en" href="https://example.com/en">
<link rel="alternate" hreflang="x-default" href="https://example.com/">
```

---

## 23. Generative Engine Optimization / AI SEO

Чтобы сайт был лучше понятен LLM и AI-поиску:

1. Дай каждой странице ясный `<title>` и `description`.
2. Используй один `<h1>`.
3. Пиши короткие ответы на частые вопросы в контенте.
4. Добавляй списки, таблицы, определения, где это уместно.
5. Используй JSON-LD.
6. Делай стабильные канонические URL.
7. Не прячь важный контент за сложным JavaScript.
8. Не используй клоакинг.
9. Обнови `llms.txt` и `ai.txt`.
10. Укажи политику использования контента для AI.

---

## 24. Отчеты агента

Создай:

```text
docs/SEO_AUDIT.md
```

В нем укажи:

- что было до изменений;
- какие файлы найдены;
- какие страницы проверены;
- какие проблемы обнаружены;
- какие файлы созданы;
- какие команды выполнены;
- какие проверки пройдены;
- какие проверки не пройдены и почему.

Создай:

```text
docs/SEO_TODO.md
```

В нем укажи:

- что нужно заполнить вручную;
- какие данные отсутствуют: адрес, телефон, координаты, описание, соцсети;
- где нужно заменить placeholder;
- нужно ли создать OG-изображение 1200x630;
- нужно ли настроить HTTPS;
- нужно ли настроить редирект на канонический домен;
- нужно ли добавить сайт в Google Search Console и Яндекс Вебмастер;
- нужно ли отправить `sitemap.xml`;
- нужно ли настроить Nginx вместо `.htaccess`.

---

## 25. Итоговый acceptance checklist

Проверь, что выполнено:

- [ ] В `<head>` есть `<meta charset="utf-8">`.
- [ ] `<html lang>` установлен корректно.
- [ ] У страниц есть уникальные `<title>`.
- [ ] У страниц есть `meta description`.
- [ ] Есть `canonical`.
- [ ] Есть Open Graph.
- [ ] Есть Twitter Card.
- [ ] Нет дублирующихся мета-тегов.
- [ ] Есть `robots`-директивы для служебных страниц.
- [ ] Есть JSON-LD `WebSite`.
- [ ] При наличии данных есть JSON-LD `Organization` или `LocalBusiness`.
- [ ] GEO-меты добавлены только при наличии реальной географии.
- [ ] `public/favicon.ico` существует.
- [ ] `/favicon.ico` открывается и возвращает 200.
- [ ] Иконки лежат в `public/favicons/`.
- [ ] `site.webmanifest` существует и валиден.
- [ ] В `<head>` подключен `manifest`.
- [ ] `robots.txt` доступен по `/robots.txt`.
- [ ] `robots.txt` содержит `Sitemap` с абсолютным URL.
- [ ] `ai.txt` доступен по `/ai.txt`.
- [ ] `llms.txt` доступен по `/llms.txt`.
- [ ] `sitemap.xml` доступен по `/sitemap.xml`.
- [ ] `sitemap.xml` валиден как XML.
- [ ] Все текстовые файлы открываются без «кракозябр».
- [ ] Файлы сохранены в UTF-8 без BOM.
- [ ] `.htaccess` не содержит BOM.
- [ ] В `.htaccess` сохранён стандартный Laravel rewrite.
- [ ] MIME-типы для `.ico`, `.svg`, `.webmanifest`, `.xml`, `.txt` настроены.
- [ ] Служебные файлы не содержат секретов.
- [ ] В `docs/SEO_AUDIT.md` есть отчет.
- [ ] В `docs/SEO_TODO.md` есть список ручных действий.

---

## 26. Команды, которые агент должен попытаться выполнить

Если есть доступ к терминалу:

```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

Если создана команда генерации favicon:

```bash
php artisan seo:generate-favicons
```

или с явным логотипом:

```bash
php artisan seo:generate-favicons public/images/logo.png
```

Если создана команда генерации SEO-файлов:

```bash
php artisan seo:generate-files
```

Проверка:

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

В отдельном терминале:

```bash
curl -I http://127.0.0.1:8000/favicon.ico
curl -I http://127.0.0.1:8000/robots.txt
curl -I http://127.0.0.1:8000/ai.txt
curl -I http://127.0.0.1:8000/llms.txt
curl -I http://127.0.0.1:8000/site.webmanifest
curl -I http://127.0.0.1:8000/sitemap.xml
```

---

## 27. Финальное указание агенту

Выполни задачу поэтапно:

1. аудит;
2. конфигурация;
3. meta/head;
4. favicon;
5. manifest;
6. robots.txt;
7. ai.txt;
8. llms.txt;
9. sitemap.xml;
10. .htaccess;
11. проверка кодировки;
12. отчеты.

Не завершай задачу, пока не проверишь, что:

- `/favicon.ico` открывается;
- `/robots.txt` открывается без ошибок;
- `/llms.txt` открывается без ошибок;
- `/ai.txt` открывается без ошибок;
- `/sitemap.xml` открывается и является валидным XML;
- `/site.webmanifest` открывается и является валидным JSON;
- HTML-страница содержит корректные SEO-теги;
- в консоли браузера нет ошибок из-за битых иконок или манифеста;
- в `docs/SEO_TODO.md` отмечены все данные, которые нужно заполнить вручную.
