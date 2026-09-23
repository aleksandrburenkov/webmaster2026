export class ProgressRail {
    constructor() {
        this.rail = document.querySelector('.progress-rail');
        this.thumb = this.rail?.querySelector('.progress-rail-thumb');
        this.sections = [];
        this.isTouchDevice = window.matchMedia('(pointer: coarse)').matches;
        this.lastScrollY = 0;
        this.lastTime = 0;
        this.velocity = 0;
        this.stretchTimeout = null;

        if (!this.rail || this.isTouchDevice) return;
        this.init();
    }

    init() {
        this.collectSections();
        this.bindEvents();
        this.update();
    }

    collectSections() {
        this.sections = Array.from(document.querySelectorAll('[data-scroll-color]')).map(section => ({
            el: section,
            color: section.dataset.scrollColor || null,
            top: 0,
            bottom: 0,
        }));
    }

    bindEvents() {
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

        window.addEventListener('resize', () => {
            this.collectSections();
            this.update();
        });
    }

    update() {
        const scrollTop = window.scrollY;
        const docHeight = document.documentElement.scrollHeight - window.innerHeight;
        const progress = docHeight > 0 ? Math.min(scrollTop / docHeight, 1) : 0;

        const now = performance.now();
        const dt = now - this.lastTime;
        if (dt > 0) {
            const dy = Math.abs(scrollTop - this.lastScrollY);
            this.velocity = dy / dt;
        }
        this.lastScrollY = scrollTop;
        this.lastTime = now;

        const heightPercent = progress * 100;
        this.thumb.style.height = `${heightPercent}%`;

        const stretchThreshold = 1.5;
        if (this.velocity > stretchThreshold) {
            this.thumb.classList.add('stretching');
            clearTimeout(this.stretchTimeout);
            this.stretchTimeout = setTimeout(() => {
                this.thumb.classList.remove('stretching');
            }, 400);
        }

        this.updateSectionColor(scrollTop);
    }

    updateSectionColor(scrollTop) {
        const viewportMid = scrollTop + window.innerHeight / 2;
        let activeColor = null;

        for (const section of this.sections) {
            const rect = section.el.getBoundingClientRect();
            section.top = rect.top + scrollTop;
            section.bottom = rect.bottom + scrollTop;

            if (viewportMid >= section.top && viewportMid <= section.bottom && section.color) {
                activeColor = section.color;
                break;
            }
        }

        if (activeColor) {
            this.thumb.style.backgroundColor = activeColor;
        } else {
            this.thumb.style.backgroundColor = '';
        }
    }
}