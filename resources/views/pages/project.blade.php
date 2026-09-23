@extends('layouts.app')

@section('title', $project->title . ' | Webmaster32')
@section('meta_description', $project->short_description ?? $project->title)

@section('content')
<section class="section" style="padding-top: 120px;">
    <div class="container container-narrow">
        <div class="reveal" style="margin-bottom: var(--space-2xl);">
            <p class="section-label">{{ $project->category_label ?? $project->category }}</p>
            <h1 class="display-md">{{ $project->title }}</h1>
        </div>

        @if($project->thumbnail)
        <div class="reveal-scale" style="margin-bottom: var(--space-3xl); border-radius: var(--radius-lg); overflow: hidden;">
            <img src="{{ asset('storage/' . $project->thumbnail) }}"
                 alt="{{ $project->title }}"
                 style="width:100%; aspect-ratio: 16/9; object-fit: cover;"
                 loading="lazy">
        </div>
        @endif

        <div class="reveal" style="display: grid; grid-template-columns: 1fr 300px; gap: var(--space-3xl); margin-bottom: var(--space-3xl);">
            <div>
                @if($project->description)
                <div class="body-lg" style="margin-bottom: var(--space-2xl);">
                    {!! $project->description !!}
                </div>
                @endif

                @if($project->services_list && count($project->services_list) > 0)
                <div style="margin-bottom: var(--space-2xl);">
                    <h3 class="heading-md" style="margin-bottom: var(--space-lg);">Что было сделано</h3>
                    <div style="display: flex; flex-wrap: wrap; gap: var(--space-sm);">
                        @foreach($project->services_list as $service)
                            <span style="padding: 6px 16px; border: 1px solid var(--color-border); border-radius: var(--radius-full); font-size: var(--text-sm); color: var(--color-text-secondary);">
                                {{ is_array($service) ? $service['service'] : $service }}
                            </span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>

            <div>
                <div style="background: var(--color-surface); border: 1px solid var(--color-border-light); border-radius: var(--radius-lg); padding: var(--space-xl); display: flex; flex-direction: column; gap: var(--space-lg); position: sticky; top: 100px;">
                    @if($project->client)
                    <div>
                        <p class="caption" style="margin-bottom: var(--space-xs);">Клиент</p>
                        <p class="body-base" style="color: var(--color-text);">{{ $project->client }}</p>
                    </div>
                    @endif

                    @if($project->year)
                    <div>
                        <p class="caption" style="margin-bottom: var(--space-xs);">Год</p>
                        <p class="body-base" style="color: var(--color-text);">{{ $project->year }}</p>
                    </div>
                    @endif

                    @if($project->budget)
                    <div>
                        <p class="caption" style="margin-bottom: var(--space-xs);">Бюджет</p>
                        <p class="body-base" style="color: var(--color-text); font-family: var(--font-mono);">{{ number_format($project->budget, 0, ',', ' ') }} ₽</p>
                    </div>
                    @endif

                    @if($project->duration)
                    <div>
                        <p class="caption" style="margin-bottom: var(--space-xs);">Срок реализации</p>
                        <p class="body-base" style="color: var(--color-text);">{{ $project->duration }} дней</p>
                    </div>
                    @endif

                    @if($project->accent_theme_color)
                    <div>
                        <p class="caption" style="margin-bottom: var(--space-xs);">Фирменный цвет</p>
                        <div style="display:flex; align-items:center; gap: var(--space-sm);">
                            <span style="width: 20px; height: 20px; border-radius: 50%; background: {{ $project->accent_theme_color }}; border: 1px solid var(--color-border);"></span>
                            <span class="label">{{ $project->accent_theme_color }}</span>
                        </div>
                    </div>
                    @endif

                    <a href="/#contacts" class="btn btn-primary" style="width:100%; justify-content:center; margin-top: var(--space-md);">Обсудить похожий проект</a>
                </div>
            </div>
        </div>

        @if($project->gallery && count($project->gallery) > 0)
        <div class="reveal" style="margin-bottom: var(--space-3xl);">
            <h3 class="heading-md" style="margin-bottom: var(--space-xl);">Галерея</h3>
            <div class="grid-2">
                @foreach($project->gallery as $image)
                <div style="border-radius: var(--radius-md); overflow: hidden;">
                    <img src="{{ asset('storage/' . (is_array($image) ? $image['image'] : $image)) }}"
                         alt="{{ is_array($image) ? ($image['alt'] ?? '') : '' }}"
                         style="width:100%; aspect-ratio: 16/10; object-fit: cover;"
                         loading="lazy">
                </div>
                @endforeach
            </div>
        </div>
        @endif

        @if($project->metrics && count($project->metrics) > 0)
        <div class="reveal" style="margin-bottom: var(--space-3xl);">
            <h3 class="heading-md" style="margin-bottom: var(--space-xl);">Ключевые метрики</h3>
            <div class="grid-3">
                @foreach($project->metrics as $key => $value)
                <div style="text-align:center; padding: var(--space-xl); background: var(--color-surface); border: 1px solid var(--color-border-light); border-radius: var(--radius-md);">
                    <p class="display-md" style="color: {{ $project->accent_theme_color ?? 'var(--color-accent)' }}; margin-bottom: var(--space-xs);">{{ $value }}</p>
                    <p class="caption">{{ $key }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

@if($relatedProjects->count() > 0)
<section class="section section-bg">
    <div class="container">
        <div class="section-header">
            <p class="section-label">Похожие проекты</p>
            <h2 class="display-md reveal">Другие работы</h2>
        </div>
        <div class="grid-3">
            @foreach($relatedProjects as $related)
                @include('partials.portfolio-card', ['project' => $related])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection