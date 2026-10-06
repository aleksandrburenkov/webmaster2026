@props(['number', 'title'])

<div class="promo-step reveal-smooth">
    <span class="promo-step-marker" aria-hidden="true">{{ $number }}</span>
    <div>
        <h3 class="heading-md promo-step-title">{{ $title }}</h3>
        <p class="body-base">{{ $slot }}</p>
    </div>
</div>
