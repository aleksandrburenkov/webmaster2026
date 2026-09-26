@extends('layouts.app')

@section('title', 'Webmaster32 | Александр Буренков — Разработка сайтов под ключ')

@section('content')
    <section class="hero relative overflow-hidden" id="home">
        <div class="hero-light" aria-hidden="true"></div>
        <div class="container relative z-20">
            <div class="hero-content max-w-[50%]">
                <p class="hero-eyebrow">Веб-мастер &bull; Брянск</p>
                <h1 class="hero-title display-xl">
                    Создаю сайты,<br />
                    которые <span class="gradient-text">работают</span><br />
                    на ваш бизнес
                </h1>
                <p class="hero-subtitle body-lg">
                    Качество веб-студии без студийных наценок. Разработка landing
                    page, корпоративных сайтов и internet-магазинов под ключ с
                    официальным договором.
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

        <div
            class="hero-ai-graphics absolute right-0 top-0 w-1/2 h-full flex items-center justify-center pointer-events-none z-10 hidden md:flex">
            <svg id="web-anim-vector-core"
                class="w-[90%] h-[80%] max-w-[650px] pointer-events-auto opacity-0 transform translate-x-10"
                viewBox="0 0 800 800" fill="none" xmlns="http://w3.org">
                <defs>
                    <linearGradient id="line-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#2D2D2D" stop-opacity="0.1" />
                        <stop offset="50%" stop-color="#1A1A1A" stop-opacity="0.7" />
                        <stop offset="100%" stop-color="#2D2D2D" stop-opacity="0.1" />
                    </linearGradient>
                </defs>

                <g class="tech-circles">
                    <circle cx="400" cy="400" r="320" stroke="url(#line-grad)" stroke-width="1"
                        stroke-dasharray="6 16" />
                    <circle cx="400" cy="400" r="220" stroke="url(#line-grad)" stroke-width="1.5"
                        stroke-dasharray="50 15 10 15" />
                    <circle cx="400" cy="400" r="120" stroke="url(#line-grad)" stroke-width="1" />
                </g>

                <g class="plexus-lines" stroke="url(#line-grad)" stroke-width="1.5">
                    <line x1="400" y1="400" x2="250" y2="220" id="line-text" />
                    <line x1="400" y1="400" x2="580" y2="280" id="line-code" />
                    <line x1="400" y1="400" x2="520" y2="550" id="line-logic" />
                    <line x1="400" y1="400" x2="230" y2="500" id="line-agent" />
                </g>

                <g class="network-nodes">
                    <circle cx="400" cy="400" r="22" fill="#111111" stroke="#2D2D2D" stroke-width="2"
                        class="node-main" />
                    <circle cx="400" cy="400" r="6" fill="#1A1A1A" class="node-pulse" />

                    <polygon points="250,220 258,232 242,232" fill="#1A1A1A" class="node-item text-node" />
                    <rect x="572" y="272" width="16" height="16" rx="3" fill="#2D2D2D"
                        class="node-item code-node" />
                    <circle cx="520" cy="550" r="7" fill="#111111" stroke="#1A1A1A" stroke-width="2"
                        class="node-item logic-node" />
                    <rect x="224" y="494" width="12" height="12" transform="rotate(45 230 500)" fill="#2D2D2D"
                        class="node-item agent-node" />

                    <circle cx="380" cy="150" r="3" fill="#1A1A1A" class="node-sub" />
                    <circle cx="520" cy="170" r="3" fill="#2D2D2D" class="node-sub" />
                    <circle cx="420" cy="650" r="3" fill="#1A1A1A" class="node-sub" />
                    <circle cx="210" cy="350" r="3" fill="#2D2D2D" class="node-sub" />
                </g>
            </svg>
        </div>

        <div class="hero-scroll-indicator" aria-hidden="true">
            <span class="hero-scroll-text">Скролл</span>
            <div class="hero-scroll-line"></div>
        </div>
    </section>

    <section class="section" id="work"
        data-scroll-color="{{ $featuredProjects->first()?->accent_theme_color ?? '#2D2D2D' }}">
        <div class="container">
            <div class="section-header">
                <p class="section-label">Портфолио</p>
                <h2 class="display-md reveal">Избранные проекты</h2>
            </div>
        </div>

        @if ($featuredProjects->count() > 0)
            <div class="container">
                <div class="portfolio-track-wrapper">
                    <div class="portfolio-sticky">
                        <div class="grid-2 portfolio-scroll-driven">
                            {{-- <div class="portfolio-track portfolio-scroll-driven"> --}}
                            @foreach ($featuredProjects as $project)
                                @include('partials.portfolio-card', ['project' => $project])
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="container">
                <div class="grid-3">
                    @foreach ($allProjects as $project)
                        @include('partials.portfolio-card', ['project' => $project])
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <section class="section section-bg" id="about" data-scroll-color="#666666">
        <div class="container ">
            <div class="section-header">
                <p class="section-label">Обо мне</p>
                <h2 class="display-md reveal">Александр Буренков</h2>
            </div>
            <div class="reveal" style="display:flex; flex-direction:column; gap:var(--space-lg);">
                <p class="body-lg">
                    Частный веб-мастер из Брянска с фокусом на результат. Разрабатываю сайты, которые не просто красиво
                    выглядят, а приводят клиентов и увеличивают продажи.
                </p>
                <p class="body-base">
                    В моём подходе нет шаблонных решений. Каждый проект начинается с глубокого анализа вашей ниши и
                    конкурентов. Я проектирую структуру, которая учитывает путь пользователя от первого касания до целевого
                    действия.
                </p>
                <div class="grid-2" style="margin-top: var(--space-xl);">
                    <div>
                        <p class="label" style="margin-bottom: var(--space-xs);">Ключевой принцип</p>
                        <p class="body-base" style="color: var(--color-text);">Качество веб-студии без студийных наценок.
                            Официальный договор, прозрачные сроки, никаких скрытых предоплат.</p>
                    </div>
                    <div>
                        <p class="label" style="margin-bottom: var(--space-xs);">Стек технологий</p>
                        <p class="body-base" style="color: var(--color-text);">Laravel, Filament PHP, Vanilla JS, CSS
                            Scroll-Driven Animations. Современные стандарты производительности и доступности.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section" id="contacts" data-scroll-color="#1A1A1A">
        <div class="container ">
            <div class="section-header" style="text-align:center;">
                <p class="section-label">Контакты</p>
                <h2 class="display-md reveal">Обсудим ваш проект</h2>
                <p class="body-lg reveal" style="margin-top: var(--space-md);">
                    Расскажите о задаче — я подготовлю персональное предложение с чёткими сроками и бюджетом.
                </p>
            </div>
            <div class="reveal" style="display:flex; flex-direction:column; gap: var(--space-lg); align-items:center;">
                <div
                    style="display:flex; gap: var(--space-xl); flex-wrap:wrap; justify-content:center; margin-bottom: var(--space-xl);">
                    @foreach ($contactsSettings as $contact)
                        @if ($contact['has_icon'])
                            <a href="{{ $contact['url'] }}" class="magnetic-btn" {!! $contact['custom_attrs'] !!}>
                                <span class="btn btn-primary btn-lg">
                                    {!! $contact['icon_html'] !!}
                                    <span>{{ $contact['label'] }}</span>
                                </span>
                            </a>
                        @endif
                    @endforeach
                </div>
                <div style="display:flex; flex-direction:column; gap: var(--space-sm); align-items:center;">
                    <p class="body-sm">Или напишите мне напрямую:</p>
                    @foreach ($contactsSettings as $contact)
                        @if (!$contact['has_icon'])
                            <a href="{{ $contact['url'] }}" class="body-base"
                                style="color: var(--color-text); font-weight: 500;" {!! $contact['custom_attrs'] !!}>
                                {{ $contact['label'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @vite(['resources/js/app.js'])
@endsection
