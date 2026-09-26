<!DOCTYPE html>
<html lang="ru" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', 'Александр Буренков — частный веб-мастер. Разработка сайтов под ключ: Landing, корпоративные сайты, интернет-магазины. Качество веб-студии без студийных наценок.')">
    <meta name="keywords"
        content="веб-мастер, разработка сайтов, Брянск, webmaster32, landing page, корпоративный сайт, интернет-магазин">
    <meta name="author" content="Александр Буренков">
    <meta name="theme-color" content="#F9F7F2">
    <meta property="og:title" content="@yield('title', 'Webmaster32 | Александр Буренков')">
    <meta property="og:description" content="@yield('meta_description', 'Разработка сайтов под ключ. Качество веб-студии без студийных наценок.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <title>@yield('title', 'Webmaster32 | Александр Буренков')</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <link rel="stylesheet" href="/css/main.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js" defer></script>
</head>

<body>
    <div class="custom-cursor" aria-hidden="true"></div>
    <div class="custom-cursor-dot" aria-hidden="true"></div>

    <div class="progress-rail" aria-hidden="true">
        <div class="progress-rail-track">
            <div class="progress-rail-thumb"></div>
        </div>
    </div>

    <nav class="nav" role="navigation" aria-label="Главная навигация">
        <div class="container">
            <div class="nav-inner">
                <a href="/" class="nav-brand" aria-label="Webmaster32 — на главную">
                    Webmaster<span>32</span>
                </a>
                <div class="nav-links">
                    <a href="{{ url('/#work') }}" class="nav-link">Проекты</a>
                    <a href="{{ url('/#about') }}" class="nav-link">Обо мне</a>
                    <a href="{{ route('portfolio.index') }}" class="nav-link">Портфолио</a>
                    <a href="{{ url('/#contacts') }}" class="nav-link">Контакты</a>
                </div>

                <div class="nav-actions">
                    <button class="theme-toggle" data-theme-toggle aria-label="Переключить тему оформления">
                        <svg class="icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5" />
                            <path
                                d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                        </svg>
                        <svg class="icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
                        </svg>
                    </button>
                    <button class="mobile-menu-btn" aria-label="Меню" data-mobile-menu>
                        <svg width="20" height="14" viewBox="0 0 20 14" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <line x1="0" y1="1" x2="20" y2="1" />
                            <line x1="0" y1="7" x2="20" y2="7" />
                            <line x1="0" y1="13" x2="20" y2="13" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer" role="contentinfo">
        <div class="container">
            <div class="footer-grid">
                <div>
                    <div class="footer-brand">Webmaster<span
                            style="color: var(--color-text-muted); font-weight: 400;">32</span></div>
                    <p class="footer-description body-sm">Качество веб-студии без студийных наценок. Разработка сайтов
                        под ключ в Брянске и по всей России.</p>
<div class="footer-social">
                        @foreach ($footerSettings as $contact)
                            <a href="{{ $contact['url'] }}" class="footer-social-link" {!! $contact['custom_attrs'] !!}
                                aria-label="{{ $contact['label'] }}">
                                {!! $contact['icon_html'] !!}
                            </a>
                        @endforeach
                    </div>
                </div>
                <div>
                    <div class="footer-col-title">Услуги</div>
                    <div class="footer-col-links">
                        <a href="/#work" class="footer-col-link">Landing Page</a>
                        <a href="/#work" class="footer-col-link">Корпоративные сайты</a>
                        <a href="/#work" class="footer-col-link">Интернет-магазины</a>
                        <a href="/#work" class="footer-col-link">Доработка проектов</a>
                    </div>
                </div>
                <div>
                    <div class="footer-col-title">Навигация</div>
                    <div class="footer-col-links">
                        <a href="/#work" class="footer-col-link">Портфолио</a>
                        <a href="/#about" class="footer-col-link">Обо мне</a>
                        <a href="/#contacts" class="footer-col-link">Контакты</a>
                    </div>
                </div>
                <div>
                    <div class="footer-col-title">Контакты</div>
                    <div class="footer-col-links">
                        <span class="footer-col-link">г. Брянск</span>
                        <a href="mailto:admin@webmaster32.ru" class="footer-col-link">admin@webmaster32.ru</a>
                        <a href="https://webmaster32.ru" class="footer-col-link" target="_blank"
                            rel="noopener">webmaster32.ru</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="footer-copyright">&copy; {{ date('Y') }} Александр Буренков. Все права защищены.</p>
                <div class="footer-legal">
                    <a href="#" class="footer-legal-link">Политика конфиденциальности</a>
                    <a href="#" class="footer-legal-link">Договор-оферта</a>
                </div>
            </div>
        </div>
    </footer>

    <div class="exit-intent-overlay" role="dialog" aria-modal="true" aria-labelledby="exit-intent-title">
        <div class="exit-intent-popup">
            <button class="exit-intent-close" aria-label="Закрыть">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <line x1="2" y1="2" x2="14" y2="14" />
                    <line x1="14" y1="2" x2="2" y2="14" />
                </svg>
            </button>
            <div class="exit-intent-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2">
                    <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                </svg>
            </div>
            <h3 id="exit-intent-title" class="heading-md" style="text-align:center;">Специальное предложение</h3>
            <p class="body-base exit-intent-text">Оставьте заявку сегодня и получите бесплатный аудит вашего текущего
                сайта + персональную скидку на первый проект.</p>
            <div class="exit-intent-actions">
                <a href="#contacts" class="btn btn-primary btn-lg">Получить предложение</a>
                <button class="btn btn-ghost exit-intent-close">Нет, спасибо</button>
            </div>
        </div>
    </div>

    <script type="module" src="/js/main.js"></script>
</body>

</html>
