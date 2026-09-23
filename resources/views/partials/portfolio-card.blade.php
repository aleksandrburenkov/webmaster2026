@props(['project'])

<div class="portfolio-card"
     data-scroll-color="{{ $project->accent_theme_color }}"
     style="--card-accent: {{ $project->accent_theme_color ?? 'var(--color-accent)' }};"
     role="article"
     aria-label="Проект: {{ $project->title }}">
    <div class="portfolio-card-glow" aria-hidden="true"></div>
    <div class="portfolio-card-accent" aria-hidden="true"></div>
    <div class="portfolio-card-image">
        @if($project->thumbnail)
            <img src="{{ asset('storage/' . $project->thumbnail) }}" alt="{{ $project->title }}" loading="lazy" width="600" height="375">
        @else
            <div class="flex-center" style="height:100%; background: var(--color-bg-alt);">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="var(--color-text-muted)" stroke-width="1" opacity="0.3">
                    <rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/>
                </svg>
            </div>
        @endif
    </div>
    <div class="portfolio-card-body">
        <span class="portfolio-card-category">{{ $project->category_label ?? $project->category }}</span>
        <h3 class="portfolio-card-title">{{ $project->title }}</h3>
        <div class="portfolio-card-metadata-row">
            @if($project->budget)
            <div class="portfolio-card-meta card-metadata">
                <span class="portfolio-card-meta-label">Бюджет</span>
                <span class="portfolio-card-meta-value">{{ number_format($project->budget, 0, ',', ' ') }} ₽</span>
            </div>
            @endif
            @if($project->duration)
            <div class="portfolio-card-meta card-metadata">
                <span class="portfolio-card-meta-label">Срок</span>
                <span class="portfolio-card-meta-value">{{ $project->duration }} дн.</span>
            </div>
            @endif
            @if($project->year)
            <div class="portfolio-card-meta card-metadata">
                <span class="portfolio-card-meta-label">Год</span>
                <span class="portfolio-card-meta-value">{{ $project->year }}</span>
            </div>
            @endif
        </div>
    </div>
</div>