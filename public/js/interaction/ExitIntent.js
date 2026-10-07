export class ExitIntent {
    constructor() {
        this.isTouchDevice = window.matchMedia("(pointer: coarse)").matches;
        if (this.isTouchDevice) return;

        this.overlay = document.querySelector(".exit-intent-overlay");
        if (!this.overlay) return;

        this.closeBtns = this.overlay.querySelectorAll(".exit-intent-close");
        this.linkBtns = this.overlay.querySelectorAll(".exit-intent-link");

        this.hasShown = sessionStorage.getItem("exit_intent_shown") === "true";
        this.isActive = false;

        this.init();
    }

    init() {
        if (this.hasShown) return;

        document.addEventListener("mouseleave", (e) => {
            if (this.isActive || this.hasShown) return;
            if (e.clientY <= 0) {
                this.show();
            }
        });

        if (this.closeBtns && this.closeBtns.length > 0) {
            this.closeBtns.forEach((btn) => {
                btn.addEventListener("click", (e) => {
                    e.preventDefault();
                    this.hide();
                });
            });
        }

        if (this.linkBtns && this.linkBtns.length > 0) {
            this.linkBtns.forEach((link) => {
                link.addEventListener("click", () => {
                    this.hide();
                });
            });
        }

        this.overlay.addEventListener("click", (e) => {
            if (e.target === this.overlay) {
                this.hide();
            }
        });

        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape" && this.isActive) {
                this.hide();
            }
        });
    }

    show() {
        this.isActive = true;
        this.overlay.classList.add("active");
        document.body.style.overflow = "hidden";
        sessionStorage.setItem("exit_intent_shown", "true");
        this.hasShown = true;
    }

    hide() {
        this.isActive = false;
        this.overlay.classList.remove("active");
        document.body.style.overflow = "";
    }
}
