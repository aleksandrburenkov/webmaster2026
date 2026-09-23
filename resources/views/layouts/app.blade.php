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
                        <a href="https://t.me/webmaster32" class="footer-social-link" target="_blank"
                            rel="noopener noreferrer" aria-label="Telegram">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.18 1.897-.962 6.502-1.359 8.627-.168.9-.5 1.201-.82 1.23-.697.064-1.226-.46-1.901-.903-1.056-.692-1.653-1.123-2.678-1.799-1.185-.781-.417-1.21.258-1.911.177-.184 3.247-2.977 3.307-3.23.007-.032.015-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.139-5.062 3.345-.479.329-.913.489-1.302.481-.428-.009-1.252-.242-1.865-.441-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.831-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635.099-.002.321.023.465.14.121.098.154.229.17.321.016.094.036.307.02.474z" />
                            </svg>
                        </a>
                        <a href="https://vk.com/webmaster32" class="footer-social-link" target="_blank"
                            rel="noopener noreferrer" aria-label="ВКонтакте">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M15.684 0H8.316C3.724 0 0 3.723 0 8.316v7.368C0 20.277 3.724 24 8.316 24h7.368C20.277 24 24 20.277 24 15.684V8.316C24 3.723 20.277 0 15.684 0zm3.693 16.677h-1.879c-.334 0-.438-.264-.928-.866-.633-.764-1.102-1.29-1.492-1.278-.282.009-.386.168-.386.577v1.137c0 .408-.134.654-1.263.654-1.864 0-3.913-1.132-5.357-3.232-2.18-3.07-2.776-5.367-2.776-5.84 0-.256.088-.487.527-.487h1.879c.397 0 .545.176.695.587.742 2.04 1.987 3.818 2.498 3.818.195 0 .28-.088.28-.574V9.193c-.063-1.03-.594-1.118-.594-1.486 0-.175.146-.352.382-.352h2.948c.322 0 .44.176.44.557v2.984c0 .321.143.433.244.433.194 0 .353-.118.705-.578 1.08-1.54 1.854-3.917 1.854-3.917.108-.234.31-.356.6-.356h1.879c.41 0 .498.21.41.506-.17.785-3.504 5.988-3.504 5.988-.284.46-.387.672 0 1.188.297.394 1.279 1.255 1.934 2.02.595.673.665 1.016.665 1.218 0 .28-.297.385-.594.385z" />
                            </svg>
                        </a>
                        <a href="mailto:admin@webmaster32.ru" class="footer-social-link" aria-label="Email">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="4" width="20" height="16" rx="2" />
                                <path d="M22 4L12 13 2 4" />
                            </svg>
                        </a>
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
