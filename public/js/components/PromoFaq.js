export class PromoFaq {
    constructor() {
        this.items = Array.from(document.querySelectorAll('.promo-faq-item'));
        if (!this.items.length) return;

        this.init();
    }

    init() {
        this.items.forEach((item) => {
            const summary = item.querySelector('summary');
            const answer = item.querySelector('.promo-faq-answer');
            if (!summary || !answer) return;

            summary.addEventListener('click', (event) => {
                event.preventDefault();

                if (item.open) {
                    this.close(item);
                    return;
                }

                this.closeAll(item);
                this.open(item);
            });
        });
    }

    open(item) {
        const answer = item.querySelector('.promo-faq-answer');
        this.clearHandler(answer);

        item.open = true;
        answer.style.height = '0px';
        void answer.offsetHeight;
        answer.style.height = `${answer.scrollHeight}px`;

        answer._faqHandler = (event) => {
            if (event.propertyName !== 'height') return;

            answer.style.height = '';
            this.clearHandler(answer);
        };

        answer.addEventListener('transitionend', answer._faqHandler);
    }

    close(item) {
        const answer = item.querySelector('.promo-faq-answer');
        this.clearHandler(answer);

        answer.style.height = `${answer.scrollHeight}px`;
        void answer.offsetHeight;
        answer.style.height = '0px';

        answer._faqHandler = (event) => {
            if (event.propertyName !== 'height') return;

            item.open = false;
            answer.style.height = '';
            this.clearHandler(answer);
        };

        answer.addEventListener('transitionend', answer._faqHandler);
    }

    closeAll(except) {
        this.items.forEach((item) => {
            if (item !== except && item.open) {
                this.close(item);
            }
        });
    }

    clearHandler(answer) {
        if (!answer._faqHandler) return;

        answer.removeEventListener('transitionend', answer._faqHandler);
        answer._faqHandler = null;
    }
}
