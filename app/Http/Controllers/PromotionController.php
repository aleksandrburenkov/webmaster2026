<?php

namespace App\Http\Controllers;

class PromotionController extends Controller
{
    public function index()
    {
        $faq = $this->faq();

        $seo = [
            'title' => 'Продвижение сайтов: SEO и GEO-оптимизация — Webmaster32',
            'description' => 'Комплексное продвижение сайтов в Брянске и по России: техническое SEO, семантика, контент-маркетинг, линкбилдинг и GEO-оптимизация под AI-поиск (ChatGPT, Perplexity, Google AI Overviews).',
            'type' => 'website',
            'schema' => [
                $this->serviceSchema(),
                $this->faqSchema($faq),
                $this->breadcrumbSchema(),
            ],
        ];

        return view('pages.promotion', compact('faq', 'seo'));
    }

    /**
     * @return array<int, array{question: string, answer: string}>
     */
    private function faq(): array
    {
        return [
            [
                'question' => 'Как оптимизировать сайт под AI-поиск (GEO)?',
                'answer' => 'GEO (Generative Engine Optimization) — это оптимизация контента под генеративные поисковики: ChatGPT, Perplexity, Google AI Overviews и Яндекс Нейро. Мы структурируем информацию блоками, добавляем факты, списки, таблицы и JSON-LD-разметку, обеспечиваем прямые ответы на вопросы пользователей и выстраиваем сущности (entities), чтобы AI-системы могли цитировать ваш сайт как первоисточник.',
            ],
            [
                'question' => 'Что такое Entity SEO?',
                'answer' => 'Entity SEO — это продвижение не по отдельным ключевым фразам, а через связанные сущности: бренд, услуги, локации, персоны и тематические понятия. Поисковые системы и LLM строят граф знаний, поэтому мы связываем страницы между собой, используем корректную микроразметку и единый именованный контекст. Это повышает шансы попасть в ответы и обычного поиска, и AI-ассистентов.',
            ],
            [
                'question' => 'Чем GEO отличается от классического SEO?',
                'answer' => 'Классическое SEO оптимизирует сайт под ранжирование в списке ссылок: ключевые слова, ссылки, поведенческие факторы. GEO оптимизирует под цитирование в сгенерированном ответе: важны фактическая точность, чёткая структура, авторитетность (E-E-A-T) и машинночитаемая разметка. На практике оба направления работают вместе и усиливают друг друга.',
            ],
            [
                'question' => 'Что входит в техническое SEO?',
                'answer' => 'Аудит индексации, работа с robots.txt и sitemap.xml, корректная канонизация и hreflang, устранение дублей, ускорение Core Web Vitals (LCP, CLS, INP), адаптивность, семантическая вёрстка, микроразметка Schema.org и внутренняя перелинковка. Техническая база — фундамент: без неё любой контент и ссылки работают вполсилы.',
            ],
            [
                'question' => 'Сколько стоит продвижение сайта?',
                'answer' => 'Стоимость зависит от конкуренции в нише, текущего состояния сайта и объёма работ. Базовый аудит и техническая оптимизация обычно стартуют от 25 000 ₽, комплексное продвижение с контентом и линкбилдингом рассчитывается индивидуально. После бесплатного экспресс-аудита я называю точную смету и сроки без скрытых платежей.',
            ],
            [
                'question' => 'Через сколько времени будет результат?',
                'answer' => 'Первые технические улучшения видны в течение 2–4 недель: индексация, скорость, исправление ошибок. Рост позиций и трафика по средне- и высококонкурентным запросам занимает от 3 до 6 месяцев. GEO-эффект — попадание в AI-ответы — обычно проявляется быстрее, так как генеративные движки активно сканируют структурированный контент.',
            ],
            [
                'question' => 'Нужен ли линкбилдинг в 2026 году?',
                'answer' => 'Да, но качественный. Массовые покупные ссылки давно не работают и несут риски. Мы строим естественный ссылочный профиль: отраслевые каталоги, экспертные упоминания, цифровой PR и крауд-маркетинг. Для GEO особенно ценны упоминания бренда в авторитетных источниках, которые LLM использует как источник фактов.',
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function serviceSchema(): array
    {
        $orgName = (string) config('seo.organization.name', config('seo.site_name'));
        $orgUrl = (string) config('seo.url');

        return [
            '@type' => 'Service',
            'name' => 'Комплексное продвижение сайтов: SEO и GEO',
            'serviceType' => 'Search Engine Optimization',
            'description' => 'Комплексное продвижение сайтов: техническое SEO, семантическое ядро, контент-маркетинг, линкбилдинг и GEO-оптимизация под AI-поиск и генеративные движки.',
            'url' => route('promotion.index'),
            'provider' => array_filter([
                '@type' => 'Organization',
                'name' => $orgName,
                'url' => $orgUrl,
            ]),
            'areaServed' => [
                '@type' => 'Country',
                'name' => 'Россия',
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Услуги продвижения',
                'itemListElement' => array_map(fn (string $name) => [
                    '@type' => 'Offer',
                    'itemOffered' => [
                        '@type' => 'Service',
                        'name' => $name,
                    ],
                ], [
                    'Техническое SEO и Core Web Vitals',
                    'Семантика и контент-маркетинг',
                    'Линкбилдинг и работа с репутацией',
                    'GEO-оптимизация под AI-поиск',
                ]),
            ],
        ];
    }

    /**
     * @param  array<int, array{question: string, answer: string}>  $faq
     * @return array<string, mixed>
     */
    private function faqSchema(array $faq): array
    {
        return [
            '@type' => 'FAQPage',
            'mainEntity' => array_map(fn (array $item) => [
                '@type' => 'Question',
                'name' => $item['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $item['answer'],
                ],
            ], $faq),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function breadcrumbSchema(): array
    {
        $base = rtrim((string) config('seo.url'), '/');

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Главная',
                    'item' => $base.'/',
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Продвижение',
                    'item' => route('promotion.index'),
                ],
            ],
        ];
    }
}
