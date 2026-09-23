@extends('layouts.app')

@section('title', 'Портфолио | Webmaster32')

@section('content')
<section class="hero" style="min-height: 50vh; padding-top: 120px;">
    <div class="container">
        <div class="hero-content">
            <p class="hero-eyebrow">Портфолио</p>
            <h1 class="display-lg">Все проекты</h1>
            <p class="body-lg hero-subtitle">Каждый проект — это отдельная визуальная система, разработанная под конкретные бизнес-задачи.</p>
        </div>
    </div>
</section>

<section class="section" style="padding-top: 0;">
    <div class="container">
        @if($projects->count() > 0)
        <div class="grid-3">
            @foreach($projects as $project)
                @include('partials.portfolio-card', ['project' => $project])
            @endforeach
        </div>
        <div style="margin-top: var(--space-3xl);">
            {{ $projects->links() }}
        </div>
        @else
        <div style="text-align:center; padding: var(--space-4xl) 0;">
            <p class="body-lg">Проекты скоро появятся. Следите за обновлениями.</p>
        </div>
        @endif
    </div>
</section>
@endsection