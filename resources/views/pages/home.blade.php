@extends('layouts.app')

@section('title', 'Webmaster32 | Александр Буренков — Разработка сайтов под ключ')

@section('content')
<section class="hero" id="home">
    <div class="hero-light" aria-hidden="true"></div>
    <div class="container">
        <div class="hero-content">
            <p class="hero-eyebrow">Веб-мастер &bull; Брянск</p>
            <h1 class="hero-title display-xl">
                Создаю сайты,<br>
                которые <span class="gradient-text">работают</span><br>
                на ваш бизнес
            </h1>
            <p class="hero-subtitle body-lg">
                Качество веб-студии без студийных наценок. Разработка landing page, корпоративных сайтов и интернет-магазинов под ключ с официальным договором.
            </p>
            <div class="hero-actions">
                <a href="#work" class="magnetic-btn">
                    <span class="btn btn-primary btn-lg">Смотреть проекты</span>
                </a>
                <a href="#contacts" class="magnetic-btn">
                    <span class="btn btn-secondary btn-lg">Обсудить проект</span>
                </a>
            </div>
        </div>
    </div>
    <div class="hero-scroll-indicator" aria-hidden="true">
        <span class="hero-scroll-text">Скролл</span>
        <div class="hero-scroll-line"></div>
    </div>
</section>

<section class="section" id="work" data-scroll-color="{{ $featuredProjects->first()?->accent_theme_color ?? '#2D2D2D' }}">
    <div class="container">
        <div class="section-header">
            <p class="section-label">Портфолио</p>
            <h2 class="display-md reveal">Избранные проекты</h2>
        </div>
    </div>

    @if($featuredProjects->count() > 0)
    <div class="portfolio-track-wrapper">
        <div class="portfolio-sticky">
            <div class="portfolio-track portfolio-scroll-driven">
                @foreach($featuredProjects as $project)
                    @include('partials.portfolio-card', ['project' => $project])
                @endforeach
            </div>
        </div>
    </div>
    @else
    <div class="container">
        <div class="grid-3">
            @foreach($allProjects as $project)
                @include('partials.portfolio-card', ['project' => $project])
            @endforeach
        </div>
    </div>
    @endif
</section>

<section class="section section-bg" id="about" data-scroll-color="#666666">
    <div class="container container-narrow">
        <div class="section-header">
            <p class="section-label">Обо мне</p>
            <h2 class="display-md reveal">Александр Буренков</h2>
        </div>
        <div class="reveal" style="display:flex; flex-direction:column; gap:var(--space-lg);">
            <p class="body-lg">
                Частный веб-мастер из Брянска с фокусом на результат. Разрабатываю сайты, которые не просто красиво выглядят, а приводят клиентов и увеличивают продажи.
            </p>
            <p class="body-base">
                В моём подходе нет шаблонных решений. Каждый проект начинается с глубокого анализа вашей ниши и конкурентов. Я проектирую структуру, которая учитывает путь пользователя от первого касания до целевого действия.
            </p>
            <div class="grid-2" style="margin-top: var(--space-xl);">
                <div>
                    <p class="label" style="margin-bottom: var(--space-xs);">Ключевой принцип</p>
                    <p class="body-base" style="color: var(--color-text);">Качество веб-студии без студийных наценок. Официальный договор, прозрачные сроки, никаких скрытых предоплат.</p>
                </div>
                <div>
                    <p class="label" style="margin-bottom: var(--space-xs);">Стек технологий</p>
                    <p class="body-base" style="color: var(--color-text);">Laravel, Filament PHP, Vanilla JS, CSS Scroll-Driven Animations. Современные стандарты производительности и доступности.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section" id="contacts" data-scroll-color="#1A1A1A">
    <div class="container container-narrow">
        <div class="section-header" style="text-align:center;">
            <p class="section-label">Контакты</p>
            <h2 class="display-md reveal">Обсудим ваш проект</h2>
            <p class="body-lg reveal" style="margin-top: var(--space-md);">
                Расскажите о задаче — я подготовлю персональное предложение с чёткими сроками и бюджетом.
            </p>
        </div>
        <div class="reveal" style="display:flex; flex-direction:column; gap: var(--space-lg); align-items:center;">
            <div style="display:flex; gap: var(--space-xl); flex-wrap:wrap; justify-content:center; margin-bottom: var(--space-xl);">
                <a href="https://t.me/webmaster32" class="magnetic-btn" target="_blank" rel="noopener noreferrer">
                    <span class="btn btn-primary btn-lg">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.562 8.161c-.18 1.897-.962 6.502-1.359 8.627-.168.9-.5 1.201-.82 1.23-.697.064-1.226-.46-1.901-.903-1.056-.692-1.653-1.123-2.678-1.799-1.185-.781-.417-1.21.258-1.911.177-.184 3.247-2.977 3.307-3.23.007-.032.015-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.139-5.062 3.345-.479.329-.913.489-1.302.481-.428-.009-1.252-.242-1.865-.441-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.831-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635.099-.002.321.023.465.14.121.098.154.229.17.321.016.094.036.307.02.474z"/></svg>
                        Telegram
                    </span>
                </a>
                <a href="mailto:admin@webmaster32.ru" class="magnetic-btn">
                    <span class="btn btn-secondary btn-lg">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 4L12 13 2 4"/></svg>
                        Email
                    </span>
                </a>
            </div>
            <div style="display:flex; flex-direction:column; gap: var(--space-sm); align-items:center;">
                <p class="body-sm">Или напишите мне напрямую:</p>
                <a href="mailto:admin@webmaster32.ru" class="body-base" style="color: var(--color-text); font-weight: 500;">admin@webmaster32.ru</a>
                <a href="https://webmaster32.ru" class="body-sm" target="_blank" rel="noopener">webmaster32.ru</a>
            </div>
        </div>
    </div>
</section>
@endsection