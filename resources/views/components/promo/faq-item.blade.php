@props(['question', 'answer'])

<details class="promo-faq-item">
    <summary>
        <span>{{ $question }}</span>
        <span class="promo-faq-toggle" aria-hidden="true"></span>
    </summary>
    <div class="promo-faq-answer">
        <p>{{ $answer }}</p>
    </div>
</details>
