<?php

use App\Seo\Providers\ProjectSitemapProvider;

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
        // Мультиязычности в проекте нет. При добавлении языков заполнить:
        // 'ru' => '/',
        // 'en' => '/en',
    ],

    'image' => env('SEO_IMAGE', ''),
    'theme_color' => env('SEO_THEME_COLOR', '#F9F7F2'),
    'background_color' => env('SEO_BACKGROUND_COLOR', '#F9F7F2'),

    'robots' => [
        'index' => env('SEO_INDEX', true),
        'follow' => env('SEO_FOLLOW', true),
    ],

    'geo' => [
        // Включать только если у сайта есть реальная географическая привязка.
        'enabled' => env('SEO_GEO_ENABLED', false),
        'region' => env('SEO_GEO_REGION', ''), // Например: RU-BRY
        'placename' => env('SEO_GEO_PLACENAME', ''), // Например: Брянск
        'position' => env('SEO_GEO_POSITION', ''), // Например: 53.2436;34.3639
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
            // Соцсети управляются через БД (ContactSetting). При необходимости
            // продублировать постоянные ссылки здесь:
            // 'https://vk.com/example',
        ],
    ],

    'ai' => [
        // Разрешить ли AI-краулерам доступ к публичному контенту.
        'allowed' => env('SEO_AI_ALLOWED', true),

        // Разрешено ли использование контента для обучения моделей.
        'training_allowed' => env('SEO_AI_TRAINING_ALLOWED', false),

        'contact' => env('SEO_AI_CONTACT', ''),
        'policy_url' => env('SEO_AI_POLICY_URL', '/ai.txt'),
    ],

    'llms' => [
        'summary' => env('SEO_LLMS_SUMMARY', env('SEO_DESCRIPTION', '')),

        'links' => [
            'Homepage' => '/',
            'Portfolio' => '/portfolio',
            'Promotion' => '/promotion',
            'Privacy policy' => '/privacy-policy',
            'Terms' => '/offer',
        ],

        'sections' => [
            [
                'name' => 'Портфолио',
                'path' => '/portfolio',
                'description' => 'Избранные проекты: landing page, корпоративные сайты, интернет-магазины.',
            ],
            [
                'name' => 'Продвижение',
                'path' => '/promotion',
                'description' => 'Комплексное SEO-продвижение и GEO-оптимизация под AI-поиск (ChatGPT, Perplexity, Google AI Overviews).',
            ],
            [
                'name' => 'Обо мне',
                'path' => '/#about',
                'description' => 'Александр Буренков — частный веб-мастер из Брянска.',
            ],
            [
                'name' => 'Контакты',
                'path' => '/#contacts',
                'description' => 'Способы связи: email, мессенджеры и соцсети.',
            ],
            [
                'name' => 'Политика конфиденциальности',
                'path' => '/privacy-policy',
                'description' => 'Порядок обработки и защиты персональных данных.',
            ],
            [
                'name' => 'Договор оферты',
                'path' => '/offer',
                'description' => 'Условия оказания услуг по разработке сайтов.',
            ],
        ],

        'facts' => [
            'Город/регион: Брянск, Брянская область, Россия.',
            'Язык контента: русский.',
            'Кодировка: UTF-8.',
            'Основной формат: HTML с JSON-LD разметкой.',
        ],
    ],

    'sitemap' => [
        // Статические страницы, которые точно должны попасть в sitemap.
        'urls' => [
            [
                'loc' => '/',
                'priority' => '1.0',
                'changefreq' => 'daily',
            ],
            [
                'loc' => '/portfolio',
                'priority' => '0.9',
                'changefreq' => 'weekly',
            ],
            [
                'loc' => '/promotion',
                'priority' => '0.9',
                'changefreq' => 'weekly',
            ],
            [
                'loc' => '/privacy-policy',
                'priority' => '0.3',
                'changefreq' => 'yearly',
            ],
            [
                'loc' => '/offer',
                'priority' => '0.3',
                'changefreq' => 'yearly',
            ],
        ],

        // Провайдеры динамических URL (статьи, страницы, товары, категории).
        // Каждый класс должен реализовывать App\Seo\SitemapProvider.
        'providers' => [
            ProjectSitemapProvider::class,
        ],
    ],
];
