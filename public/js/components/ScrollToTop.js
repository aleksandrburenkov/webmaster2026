export class ScrollToTop {
    constructor() {
        this.button = document.getElementById('scrollToTop');
        this.threshold = 400;
        this.duration = 600;
        this.idleDelay = 3000;
        this.animationFrame = null;
        this.idleTimeout = null;

        if (!this.button) return;
        this.init();
    }

    init() {
        window.addEventListener('scroll', () => this.onScroll(), { passive: true });
        this.button.addEventListener('click', () => this.scrollToTop());
        this.onScroll();
    }

    onScroll() {
        if (window.scrollY > this.threshold) {
            this.button.classList.add('is-visible');
            this.resetIdleTimer();
        } else {
            this.button.classList.remove('is-visible');
            this.clearIdleTimer();
        }
    }

    resetIdleTimer() {
        this.clearIdleTimer();
        this.idleTimeout = setTimeout(() => {
            this.button.classList.remove('is-visible');
            this.idleTimeout = null;
        }, this.idleDelay);
    }

    clearIdleTimer() {
        if (this.idleTimeout) {
            clearTimeout(this.idleTimeout);
            this.idleTimeout = null;
        }
    }

    easeInOutQuad(t) {
        return t < 0.5 ? 2 * t * t : 1 - Math.pow(-2 * t + 2, 2) / 2;
    }

    scrollToTop() {
        if (this.animationFrame) {
            cancelAnimationFrame(this.animationFrame);
        }

        const startY = window.scrollY;
        if (startY <= 0) return;

        const startTime = performance.now();

        const step = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / this.duration, 1);
            const eased = this.easeInOutQuad(progress);

            window.scrollTo(0, startY * (1 - eased));

            if (progress < 1) {
                this.animationFrame = requestAnimationFrame(step);
            } else {
                this.animationFrame = null;
            }
        };

        this.animationFrame = requestAnimationFrame(step);
    }
}
