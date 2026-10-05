import { ThemeManager } from './core/ThemeManager.js';
import { ProgressRail } from './core/ProgressRail.js';
import { CustomCursor } from './core/CustomCursor.js';
import { MagneticButton } from './interaction/MagneticButton.js';
import { ExitIntent } from './interaction/ExitIntent.js';
import { PortfolioCards } from './components/PortfolioCards.js';
import { Navigation } from './navigation/Navigation.js';
import { MobileMenu } from './navigation/MobileMenu.js';
import { SmoothScroll } from './navigation/SmoothScroll.js';
import { HeroAnimation } from './animation/HeroAnimation.js';
import { FooterAnimation } from './animation/FooterAnimation.js';
import { TextReveal } from './animation/TextReveal.js';
import { SectionGridSpotlight } from './animation/SectionGridSpotlight.js?v=6';
import { ScrollToTop } from './components/ScrollToTop.js';
import { CookieConsent } from './components/CookieConsent.js';

class App {
    constructor() {
        this.modules = {};
        this.init();
    }

    init() {
        document.addEventListener('DOMContentLoaded', () => {
            this.modules.theme = new ThemeManager();
            this.modules.navigation = new Navigation();
            this.modules.mobileMenu = new MobileMenu();
            this.modules.progressRail = new ProgressRail();
            this.modules.customCursor = new CustomCursor();
            this.modules.magneticButton = new MagneticButton();
            this.modules.exitIntent = new ExitIntent();
            this.modules.portfolioCards = new PortfolioCards();

            this.initHeroLight();
            this.modules.smoothScroll = new SmoothScroll();
            this.modules.heroAnimation = new HeroAnimation();
            this.modules.footerAnimation = new FooterAnimation();
            this.modules.textReveal = new TextReveal();
            this.modules.sectionGridSpotlight = new SectionGridSpotlight();
            this.modules.scrollToTop = new ScrollToTop();
            this.modules.cookieConsent = new CookieConsent();
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
}

new App();