export class ThemeManager {
    constructor() {
        this.theme = localStorage.getItem('theme') || 'light';
        this.toggleBtn = document.querySelector('[data-theme-toggle]');
        this.init();
    }

    init() {
        this.applyTheme(this.theme);
        if (this.toggleBtn) {
            this.toggleBtn.addEventListener('click', () => this.toggle());
        }
    }

    applyTheme(theme) {
        this.theme = theme;
        if (theme === 'dark') {
            document.documentElement.setAttribute('data-theme', 'dark');
        } else {
            document.documentElement.removeAttribute('data-theme');
        }
        localStorage.setItem('theme', theme);
    }

    toggle() {
        if (!document.startViewTransition) {
            this.applyTheme(this.theme === 'dark' ? 'light' : 'dark');
            return;
        }

        const newTheme = this.theme === 'dark' ? 'light' : 'dark';

        document.documentElement.style.viewTransitionName = 'theme-transition';
        const transition = document.startViewTransition(() => {
            this.applyTheme(newTheme);
        });

        transition.finished.then(() => {
            document.documentElement.style.viewTransitionName = '';
        });

        this.triggerGlitch();
    }

    triggerGlitch() {
        const brand = document.querySelector('.nav-brand');
        if (!brand) return;

        brand.classList.add('glitch-micro');
        setTimeout(() => {
            brand.classList.remove('glitch-micro');
        }, 200);
    }
}