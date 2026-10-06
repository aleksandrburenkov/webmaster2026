@props(['title', 'index' => null])

<article class="promo-card reveal-smooth">
    @if (isset($icon) || $index)
        <div class="promo-card-head">
            @isset($icon)
                <span class="promo-card-icon" aria-hidden="true">{{ $icon }}</span>
            @endisset
            @if ($index)
                <span class="promo-card-index" aria-hidden="true">{{ $index }}</span>
            @endif
        </div>
    @endif

    <h3 class="promo-card-title">{{ $title }}</h3>

    <div class="body-base promo-card-text">{{ $slot }}</div>

    @isset($list)
        <ul class="promo-card-list">{{ $list }}</ul>
    @endisset
</article>
