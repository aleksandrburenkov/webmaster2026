export class MobileMenu {
    constructor() {
        this.btn = document.querySelector("[data-mobile-menu]");
        this.menu = document.querySelector(".nav-links");
        if (!this.btn || !this.menu) return;

        this.breakpoint = window.matchMedia("(max-width: 1024px)");
        this.isOpen = false;
        this.closeTimer = null;

        this.init();
    }

    init() {
        this.btn.addEventListener("click", () => this.toggle());

        this.menu.querySelectorAll(".nav-link").forEach((link) => {
            link.addEventListener("click", () => this.close());
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && this.isOpen) {
                this.close(true);
            }
        });

        this.menu.addEventListener("transitionend", (e) => {
            if (e.target === this.menu && e.propertyName === "transform") {
                this.handleTransitionEnd();
            }
        });

        // Открытие возможно только по клику. При возврате к десктопной
        // ширине принудительно сбрасываем состояние, чтобы меню не мешало.
        this.breakpoint.addEventListener("change", (e) => {
            if (!e.matches) {
                this.reset();
            }
        });
    }

    toggle() {
        if (this.isOpen) {
            this.close();
        } else {
            this.open();
        }
    }

    open() {
        if (this.isOpen || !this.breakpoint.matches) return;

        this.clearTimer();
        this.isOpen = true;
        this.menu.classList.remove("is-closing");
        this.menu.classList.add("is-open");
        this.btn.classList.add("is-active");
        this.btn.setAttribute("aria-expanded", "true");
        document.body.classList.add("mobile-menu-open");
    }

    close(restoreFocus = false) {
        if (!this.isOpen) return;

        this.clearTimer();
        this.isOpen = false;
        this.menu.classList.remove("is-open");
        this.menu.classList.add("is-closing");
        this.btn.classList.remove("is-active");
        this.btn.setAttribute("aria-expanded", "false");

        // Страховка, если transitionend не сработает.
        this.closeTimer = setTimeout(() => this.finishClose(), 420);

        if (restoreFocus) {
            this.btn.focus({ preventScroll: true });
        }
    }

    handleTransitionEnd() {
        if (this.menu.classList.contains("is-closing")) {
            this.finishClose();
        }
    }

    finishClose() {
        this.clearTimer();
        if (!this.menu.classList.contains("is-closing")) return;

        this.menu.classList.add("no-transition");
        this.menu.classList.remove("is-closing");
        void this.menu.offsetWidth; // сброс позиции влево без анимации
        this.menu.classList.remove("no-transition");

        document.body.classList.remove("mobile-menu-open");
    }

    reset() {
        this.clearTimer();
        this.isOpen = false;
        this.menu.classList.remove("is-open", "is-closing", "no-transition");
        this.btn.classList.remove("is-active");
        this.btn.setAttribute("aria-expanded", "false");
        document.body.classList.remove("mobile-menu-open");
    }

    clearTimer() {
        if (this.closeTimer) {
            clearTimeout(this.closeTimer);
            this.closeTimer = null;
        }
    }
}
