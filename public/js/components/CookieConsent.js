export class CookieConsent {
    constructor() {
        this.cookieName = "cookie_consent";
        this.maxAge = 30 * 24 * 60 * 60;
        this.showDelay = 1000;
        this.removeDelay = 500;

        this.banner = document.querySelector(".cookie-consent");
        if (!this.banner) return;

        this.acceptBtn = this.banner.querySelector("[data-cookie-accept]");

        if (this.hasConsent()) {
            this.banner.remove();
            return;
        }

        this.init();
    }

    hasConsent() {
        return document.cookie
            .split(";")
            .some((item) => item.trim() === `${this.cookieName}=true`);
    }

    setConsent() {
        document.cookie = `${this.cookieName}=true; path=/; max-age=${this.maxAge}; SameSite=Lax`;
    }

    init() {
        const reveal = () => {
            window.setTimeout(() => {
                this.banner.classList.add("is-visible");
            }, this.showDelay);
        };

        if (document.readyState === "complete") {
            reveal();
        } else {
            window.addEventListener("load", reveal, { once: true });
        }

        if (this.acceptBtn) {
            this.acceptBtn.addEventListener("click", () => this.accept());
        }
    }

    accept() {
        this.setConsent();
        this.banner.classList.remove("is-visible");

        window.setTimeout(() => {
            this.banner.remove();
        }, this.removeDelay);
    }
}
