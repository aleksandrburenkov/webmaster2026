import { ThemeManager } from './core/ThemeManager.js';
import { ProgressRail } from './core/ProgressRail.js';
import { CustomCursor } from './core/CustomCursor.js';
import { MagneticButton } from './interaction/MagneticButton.js';
import { ExitIntent } from './interaction/ExitIntent.js';
import { PortfolioCards } from './components/PortfolioCards.js';
import { Navigation } from './navigation/Navigation.js';
import { HeroAnimation } from './animation/HeroAnimation.js';

class App {
    constructor() {
        this.modules = {};
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.modules.theme = new ThemeManager();
            this.modules.navigation = new Navigation();
            this.modules.progressRail = new ProgressRail();
            this.modules.customCursor = new CustomCursor();
            this.modules.magneticButton = new MagneticButton();
            this.modules.exitIntent = new ExitIntent();
            this.modules.portfolioCards = new PortfolioCards();

            this.initHeroLight();
            this.initSmoothAnchors();
            this.modules.heroAnimation = new HeroAnimation();
        });
    }

    initHeroLight() {
        const heroLight = document.querySelector('.hero-light');
        const hero = document.querySelector('.hero');
        if (!heroLight || !hero) return;

        const isTouchDevice = window.matchMedia('(pointer: coarse)').matches;
        if (isTouchDevice) {
            heroLight.style.display = 'none';
            return;
        }

        hero.addEventListener('mousemove', (e) => {
            const rect = hero.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width) * 100;
            const y = ((e.clientY - rect.top) / rect.height) * 100;
            heroLight.style.setProperty('--light-x', `${x}%`);
            heroLight.style.setProperty('--light-y', `${y}%`);
            heroLight.classList.add('active');
        });

        hero.addEventListener('mouseleave', () => {
            heroLight.classList.remove('active');
        });
    }

    initSmoothAnchors() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', (e) => {
                const targetId = anchor.getAttribute('href');
                if (targetId === '#') return;

                const target = document.querySelector(targetId);
                if (!target) return;

                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    }
}

new App();