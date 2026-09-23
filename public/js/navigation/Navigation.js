export class Navigation {
    constructor() {
        this.nav = document.querySelector('.nav');
        if (!this.nav) return;

        this.lastScrollY = 0;
        this.scrollThreshold = 50;

        this.init();
    }

    init() {
        let ticking = false;

        window.addEventListener('scroll', () => {
            if (!ticking) {
                requestAnimationFrame(() => {
                    this.update();
                    ticking = false;
                });
                ticking = true;
            }
        }, { passive: true });
    }

    update() {
        const scrollY = window.scrollY;

        if (scrollY > this.scrollThreshold) {
            this.nav.classList.add('scrolled');
        } else {
            this.nav.classList.remove('scrolled');
        }

        this.lastScrollY = scrollY;
    }
}