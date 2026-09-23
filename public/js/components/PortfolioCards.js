export class PortfolioCards {
    constructor() {
        this.cards = document.querySelectorAll('.portfolio-card');
        this.rail = document.querySelector('.progress-rail-thumb');
        if (!this.cards.length) return;

        this.init();
    }

    init() {
        this.cards.forEach(card => {
            card.addEventListener('mouseenter', () => this.handleEnter(card));
            card.addEventListener('mouseleave', () => this.handleLeave(card));
        });
    }

    handleEnter(card) {
        const accentColor = card.dataset.scrollColor;
        if (accentColor && this.rail) {
            this.rail.style.transition = 'background-color 300ms cubic-bezier(0.4, 0, 0.2, 1)';
            this.rail.style.backgroundColor = accentColor;
        }
    }

    handleLeave(card) {
        if (this.rail) {
            this.rail.style.backgroundColor = '';
        }
    }
}