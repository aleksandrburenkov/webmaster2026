export class SmoothScroll {
    constructor() {
        this.nav = document.querySelector('.nav');
        this.offsetGap = 12;
        this.minDuration = 450;
        this.maxDuration = 900;
        this.reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
        this.storageKey = 'smoothScrollTarget';
        this.animationFrame = null;

        this.init();
    }

    init() {
        document.addEventListener('click', (e) => this.onClick(e));
        this.restorePendingTarget();
    }

    onClick(e) {
        if (e.defaultPrevented || e.button !== 0) return;
        if (e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

        const link = e.target && e.target.closest ? e.target.closest('a[href]') : null;
        if (!link || link.target === '_blank') return;

        const rawHref = link.getAttribute('href') || '';
        if (!rawHref || rawHref === '#' || /^(mailto|tel|javascript):/i.test(rawHref)) return;

        let url;
        try {
            url = new URL(link.href, window.location.href);
        } catch {
            return;
        }

        if (url.origin !== window.location.origin) return;
        if (!url.hash || url.hash === '#') return;

        const targetId = decodeURIComponent(url.hash.slice(1));
        const target = document.getElementById(targetId);
        const samePage =
            url.pathname === window.location.pathname &&
            url.search === window.location.search;

        if (samePage) {
            if (!target) return;
            e.preventDefault();
            this.updateHash(url.hash);
            this.scrollToTarget(target);
            return;
        }

        e.preventDefault();
        this.rememberTarget(url.hash);
        window.location.href = url.origin + url.pathname + url.search;
    }

    scrollToTarget(target) {
        if (document.body.classList.contains('mobile-menu-open')) {
            this.waitForMenuClose(() => this.performScroll(target));
        } else {
            this.performScroll(target);
        }
    }

    performScroll(target) {
        const destination = this.targetY(target);

        if (this.reducedMotion.matches) {
            window.scrollTo(0, destination);
            return;
        }

        this.animateTo(destination);
    }

    targetY(target) {
        const navHeight = this.nav ? this.nav.getBoundingClientRect().height : 0;
        const absoluteTop = target.getBoundingClientRect().top + window.scrollY;
        return Math.max(absoluteTop - navHeight - this.offsetGap, 0);
    }

    animateTo(destination) {
        if (this.animationFrame) {
            cancelAnimationFrame(this.animationFrame);
            this.animationFrame = null;
        }

        const startY = window.scrollY;
        const distance = destination - startY;
        if (Math.abs(distance) < 1) return;

        const duration = Math.min(
            this.maxDuration,
            Math.max(this.minDuration, Math.abs(distance) * 0.5)
        );
        const startTime = performance.now();

        const step = (now) => {
            const progress = Math.min((now - startTime) / duration, 1);
            const eased = this.easeInOutCubic(progress);

            window.scrollTo(0, startY + distance * eased);

            if (progress < 1) {
                this.animationFrame = requestAnimationFrame(step);
            } else {
                this.animationFrame = null;
            }
        };

        this.animationFrame = requestAnimationFrame(step);
    }

    easeInOutCubic(t) {
        return t < 0.5 ? 4 * t * t * t : 1 - Math.pow(-2 * t + 2, 3) / 2;
    }

    updateHash(hash) {
        if (!hash || !history.replaceState) return;
        if (window.location.hash === hash) return;
        history.replaceState(null, '', hash);
    }

    rememberTarget(hash) {
        try {
            sessionStorage.setItem(this.storageKey, hash);
        } catch {
            /* sessionStorage может быть недоступен — переход произойдёт обычно */
        }
    }

    restorePendingTarget() {
        let hash = null;
        try {
            hash = sessionStorage.getItem(this.storageKey);
            sessionStorage.removeItem(this.storageKey);
        } catch {
            return;
        }
        if (!hash) return;

        const targetId = decodeURIComponent(hash.replace(/^#/, ''));
        const target = targetId ? document.getElementById(targetId) : null;
        if (!target) return;

        this.whenReady(() => {
            this.updateHash(hash);
            this.scrollToTarget(target);
        });
    }

    whenReady(callback) {
        const run = () => {
            const start = () => {
                if (document.body.classList.contains('mobile-menu-open')) {
                    this.waitForMenuClose(callback);
                } else {
                    requestAnimationFrame(() => requestAnimationFrame(callback));
                }
            };

            if (document.fonts && document.fonts.ready) {
                document.fonts.ready.then(start).catch(start);
            } else {
                start();
            }
        };

        if (document.readyState === 'complete') {
            run();
        } else {
            window.addEventListener('load', run, { once: true });
        }
    }

    waitForMenuClose(callback) {
        const menu = document.querySelector('.nav-links');
        let finished = false;

        const finish = () => {
            if (finished) return;
            finished = true;
            if (menu) menu.removeEventListener('transitionend', onEnd);
            requestAnimationFrame(() => requestAnimationFrame(callback));
        };

        const onEnd = (e) => {
            if (!menu || e.target === menu) finish();
        };

        if (menu) menu.addEventListener('transitionend', onEnd);
        setTimeout(finish, 520);
    }
}
