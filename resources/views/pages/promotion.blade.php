@extends('layouts.app')

@section('content')
    <section class="hero promo-hero" id="promo-top">
        <div class="hero-light" aria-hidden="true"></div>
        <div class="container">
            {{-- <nav class="promo-breadcrumbs" aria-label="Хлебные крошки">
                <a href="{{ route('home') }}">Главная</a>
                <span class="promo-breadcrumbs-sep" aria-hidden="true">/</span>
                <span aria-current="page">Продвижение</span>
            </nav> --}}

            <div class="hero-content promo-hero-inner">
                <p class="hero-eyebrow">Продвижение &bull; SEO &amp; GEO</p>
                <h1 class="hero-title display-xl promo-hero-title">
                    Больше трафика из поиска
                    и <span class="gradient-text">AI-ответов</span>
                </h1>
                <p class="hero-subtitle body-lg promo-hero-subtitle">
                    Комплексное продвижение сайтов и оптимизация под ИИ (GEO): техническое SEO, проработка семантики,
                    контент-маркетинг, управление ссылочным профилем и вывод сайта в ответы генеративных сетей — Яндекс
                    Нейро, ChatGPT и Perplexity.
                </p>
                <div class="hero-actions promo-hero-actions">
                    <a href="#promo-contacts" class="magnetic-btn">
                        <span class="btn btn-primary btn-lg">Получить аудит</span>
                    </a>
                    <a href="#promo-services" class="magnetic-btn">
                        <span class="btn btn-secondary btn-lg">Что входит</span>
                    </a>
                </div>
                <p class="body-sm promo-hero-note">
                    Бесплатный экспресс-аудит: технические ошибки, точки роста и прогноз по трафику.
                </p>
            </div>

            <div class="promo-metrics">
                <div>
                    <p class="promo-metric-value">&times;2&ndash;4</p>
                    <p class="body-sm">рост органического трафика за 6&ndash;12 месяцев</p>
                </div>
                <div>
                    <p class="promo-metric-value">Top-10</p>
                    <p class="body-sm">целевая видимость по коммерческим запросам</p>
                </div>
                <div>
                    <p class="promo-metric-value">+30%</p>
                    <p class="body-sm">к шансу цитирования в AI-ответах</p>
                </div>
                <div>
                    <p class="promo-metric-value">2&ndash;4 нед.</p>
                    <p class="body-sm">до первых технических улучшений</p>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="promo-services" data-scroll-color="#2D2D2D">
        <div class="container">
            <div class="section-header">
                <p class="section-label">Комплексный подход</p>
                <h2 class="display-md text-mb reveal-smooth">Что входит в продвижение</h2>
                <p class="body-lg reveal-text">
                    Продвижение работает только как система. Отдельные приёмы дают точечный эффект — устойчивый рост
                    обеспечивает связка техники, контента, ссылок и аналитики.
                </p>
            </div>

            <div class="grid-3">
                <x-promo.service-card title="Техническое SEO" index="01">
                    <x-slot:icon>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <line x1="4" y1="21" x2="4" y2="14" />
                            <line x1="4" y1="10" x2="4" y2="3" />
                            <line x1="12" y1="21" x2="12" y2="12" />
                            <line x1="12" y1="8" x2="12" y2="3" />
                            <line x1="20" y1="21" x2="20" y2="16" />
                            <line x1="20" y1="12" x2="20" y2="3" />
                            <line x1="1" y1="14" x2="7" y2="14" />
                            <line x1="9" y1="8" x2="15" y2="8" />
                            <line x1="17" y1="16" x2="23" y2="16" />
                        </svg>
                    </x-slot:icon>
                    <p>
                        Приводим сайт в порядок на уровне кода и инфраструктуры: скорость, индексация, разметка и
                        корректная структура — фундамент, без которого контент и ссылки работают вполсилы.
                    </p>
                    <x-slot:list>
                        <li>Core Web Vitals: LCP, CLS, INP</li>
                        <li>Индексация, robots.txt и sitemap.xml</li>
                        <li>Канонизация, дубли и пагинация</li>
                        <li>Schema.org и семантическая вёрстка</li>
                    </x-slot:list>
                </x-promo.service-card>

                <x-promo.service-card title="Семантика и контент" index="02">
                    <x-slot:icon>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <line x1="8" y1="13" x2="16" y2="13" />
                            <line x1="8" y1="17" x2="13" y2="17" />
                        </svg>
                    </x-slot:icon>
                    <p>
                        Собираем семантическое ядро и превращаем его в структуру сайта и контент-план. Работаем с
                        интентами и сущностями (Entity SEO), усиливая экспертность и доверие по принципам E-E-A-T.
                    </p>
                    <x-slot:list>
                        <li>Кластеризация запросов и интентов</li>
                        <li>Контент-план и оптимизация страниц</li>
                        <li>Entity SEO и внутренняя перелинковка</li>
                        <li>E-E-A-T: опыт, экспертность, авторитет</li>
                    </x-slot:list>
                </x-promo.service-card>

                <x-promo.service-card title="Линкбилдинг и репутация" index="03">
                    <x-slot:icon>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71" />
                            <path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71" />
                        </svg>
                    </x-slot:icon>
                    <p>
                        Строим естественный ссылочный профиль без рискованных массовых покупок. Для GEO особенно ценны
                        авторитетные упоминания бренда, которые AI-системы используют как источник фактов.
                    </p>
                    <x-slot:list>
                        <li>Отраслевые каталоги и справочники</li>
                        <li>Цифровой PR и крауд-маркетинг</li>
                        <li>Мониторинг упоминаний бренда</li>
                        <li>Работа с отзывами и репутацией</li>
                    </x-slot:list>
                </x-promo.service-card>
            </div>
        </div>
    </section>

    <section class="section section-bg" id="promo-geo" data-scroll-color="#666666">
        <div class="container">
            <div class="section-header">
                <p class="section-label">GEO</p>
                <h2 class="display-md text-mb  reveal-smooth">Оптимизация под AI-поиск</h2>
                <p class="body-lg reveal-text">
                    Generative Engine Optimization — это работа над тем, чтобы ваш сайт цитировали в сгенерированных
                    ответах. Здесь решают не ключевые слова, а фактология, структура и авторитет источника.
                </p>
            </div>

            <div class="promo-geo-layout">
                <div class="reveal-smooth">
                    <ul class="promo-check-list">
                        <li>
                            <strong>Прямые ответы.</strong> Каждый блок закрывает конкретный вопрос пользователя ёмкой
                            формулировкой, которую можно процитировать без потери смысла.
                        </li>
                        <li>
                            <strong>Факты и цифры.</strong> Даты, метрики и сравнения — то, что генеративные модели
                            предпочитают брать из первоисточника.
                        </li>
                        <li>
                            <strong>Сущности и связи.</strong> Бренд, услуги, локации и термины заданы однозначно и
                            связаны между собой (Entity SEO).
                        </li>
                        <li>
                            <strong>Машинночитаемая структура.</strong> Schema.org, семантические заголовки, списки и
                            таблицы упрощают извлечение данных.
                        </li>
                        <li>
                            <strong>Нулевой клик.</strong> Мы готовим сайт к Zero-Click Searches: даже без перехода
                            пользователь видит ваш бренд в ответе.
                        </li>
                    </ul>
                </div>

                <div class="promo-geo-panel reveal-scale">
                    <p class="promo-geo-panel-title">Где и что оптимизируем</p>
                    <table class="promo-table">
                        <caption>Приоритеты для ключевых генеративных движков</caption>
                        <thead>
                            <tr>
                                <th scope="col">Движок</th>
                                <th scope="col">Фокус оптимизации</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><strong>Яндекс Нейро</strong></td>
                                <td>Локальное SEO, коммерческие факторы, ИКС, микроразметка</td>
                            </tr>
                            <tr>
                                <td><strong>ChatGPT Search</strong></td>
                                <td>Факты и цифры, экспертный контент, структура, доступ для GPTBot</td>
                            </tr>
                            <tr>
                                <td><strong>Perplexity</strong></td>
                                <td>Свежие инфоповоды, цитируемость бренда, авторитет домена</td>
                            </tr>
                            <tr>
                                <td><strong>GigaChat / YandexGPT</strong></td>
                                <td>Интеграция в Алису/Салют, каталоги экосистем, отзывы в РФ</td>
                            </tr>
                        </tbody>


                    </table>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="promo-process" data-scroll-color="#1A1A1A">
        <div class="container">
            <div class="section-header">
                <p class="section-label">Этапы работы</p>
                <h2 class="display-md reveal-smooth">Как мы придём к результату</h2>
            </div>

            <div class="promo-timeline">
                <x-promo.step number="01" title="Аудит и аналитика">
                    Провожу технический аудит, анализ конкурентов и спроса, оцениваю текущие позиции и точки роста.
                    На выходе — понятный план с приоритетами и прогнозом.
                </x-promo.step>

                <x-promo.step number="02" title="Семантика и стратегия">
                    Формирую семантическое ядро, кластеризую запросы по интентам и проектирую структуру сайта под
                    пользователя и поисковые системы.
                </x-promo.step>

                <x-promo.step number="03" title="Техническая оптимизация и контент">
                    Устраняю технические ошибки, ускоряю загрузку, внедряю разметку и готовлю контент под каждый кластер
                    запросов.
                </x-promo.step>

                <x-promo.step number="04" title="GEO-оптимизация">
                    Структурирую информацию под цитирование: прямые ответы, факты, таблицы, JSON-LD и связку сущностей
                    для AI-ассистентов.
                </x-promo.step>

                <x-promo.step number="05" title="Ссылки, репутация и аналитика">
                    Наращиваю естественный ссылочный профиль, отслеживаю позиции, трафик и конверсии, корректирую
                    стратегию по данным.
                </x-promo.step>
            </div>
        </div>
    </section>

    <section class="section section-bg" id="promo-faq">
        <div class="container">
            <div class="section-header">
                <p class="section-label">FAQ</p>
                <h2 class="display-md reveal-smooth">Частые вопросы о продвижении</h2>
            </div>

            <div class="promo-faq">
                @foreach ($faq as $item)
                    <x-promo.faq-item :question="$item['question']" :answer="$item['answer']" />
                @endforeach
            </div>
        </div>
    </section>

    <section class="section" id="promo-contacts">
        <div class="container">
            <div class="promo-cta reveal-scale">
                <p class="section-label">Старт</p>
                <h2 class="display-md promo-cta-title">Получите бесплатный экспресс-аудит</h2>
                <p class="body-lg promo-cta-text">
                    Пришлю разбор технических ошибок, оценку видимости в поиске и AI-ответах, а также план первых шагов
                    с прогнозом по трафику и срокам.
                </p>

                <div class="promo-cta-actions">
                    <a href="{{ url('/#contacts') }}" class="magnetic-btn">
                        <span class="btn btn-primary btn-lg">Обсудить продвижение</span>
                    </a>
                    <a href="mailto:admin@webmaster32.ru" class="magnetic-btn">
                        <span class="btn btn-secondary btn-lg">Написать на email</span>
                    </a>
                </div>

                @if (!empty($contactsSettings))
                    <div class="promo-cta-contacts">
                        @foreach ($contactsSettings as $contact)
                            <a href="{{ $contact['url'] }}" class="promo-cta-contact" {!! $contact['custom_attrs'] !!}>
                                {{ $contact['label'] }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
