/**
 * SectionGridSpotlight — интерактивная фоновая сетка на всех .section.
 *
 * Клетки скрыты по умолчанию и проявляются в радиальном «пятне»:
 *  — на десктопе пятно появляется под курсором мыши;
 *  — на тач-устройствах — в точке касания пальцем.
 *
 * Показ/скрытие реализовано CSS-классом .is-spot-active (обычный opacity
 * с переходом). GSAP (если подключён) используется только для плавного
 * следования пятна через quickTo; при его отсутствии координаты пишутся
 * напрямую. При prefers-reduced-motion инерция и CSS-переходы отключаются,
 * но сам эффект остаётся (статичное проявление).
 */
export class SectionGridSpotlight {
    constructor() {
        this.gsap = window.gsap;
        this.sections = Array.from(document.querySelectorAll(".section"));

        // Нет секций — нечего делать.
        if (!this.sections.length) return;

        // Режим уменьшенного движения НЕ отключает эффект полностью:
        // пятно работает без инерции (GSAP) и без CSS-переходов.
        this.reducedMotion = window.matchMedia(
            "(prefers-reduced-motion: reduce)",
        ).matches;

        // Интерактивные элементы: на них пятно не показываем.
        this.INTERACTIVE =
            "a, button, input, textarea, select, label, [data-interactive], [data-no-spot]";

        this.contexts = this.sections.map((section) =>
            this.createContext(section),
        );
        this.bind();
    }

    /** Создаёт состояние и хелперы плавного следования для одной секции. */
    createContext(section) {
        const ctx = { section, shown: false };

        // Плавное следование: GSAP quickTo по прокси-объекту,
        // в onUpdate пишем CSS-переменные позиции пятна.
        // При reduced-motion инерцию не включаем — координаты пишутся напрямую.
        if (this.gsap && !this.reducedMotion) {
            try {
                const pos = (ctx.pos = { x: 0, y: 0 });
                const write = () => {
                    section.style.setProperty("--spot-x", pos.x + "px");
                    section.style.setProperty("--spot-y", pos.y + "px");
                };
                ctx.xTo = this.gsap.quickTo(pos, "x", {
                    duration: 0.4,
                    ease: "power3",
                    onUpdate: write,
                });
                ctx.yTo = this.gsap.quickTo(pos, "y", {
                    duration: 0.4,
                    ease: "power3",
                    onUpdate: write,
                });
            } catch (e) {
                ctx.pos = null; // при ошибке — прямое выставление координат
            }
        }

        return ctx;
    }

    /** Навешивает пассивные pointer-обработчики на каждую секцию. */
    bind() {
        this.contexts.forEach((ctx) => {
            const s = ctx.section;

            // passive: true + без preventDefault — нативный скролл не блокируется.
            s.addEventListener("pointerenter", (e) => this.onEnter(ctx, e), {
                passive: true,
            });
            s.addEventListener("pointermove", (e) => this.onMove(ctx, e), {
                passive: true,
            });
            s.addEventListener("pointerleave", () => this.hide(ctx), {
                passive: true,
            });
            s.addEventListener("pointerdown", (e) => this.onDown(ctx, e), {
                passive: true,
            });
            s.addEventListener("pointerup", (e) => this.onUp(ctx, e), {
                passive: true,
            });
            s.addEventListener("pointercancel", () => this.hide(ctx), {
                passive: true,
            });
        });
    }

    /** Проверяет, находится ли событие на интерактивном элементе. */
    isInteractive(target) {
        return !!(target && target.closest && target.closest(this.INTERACTIVE));
    }

    /** Координаты события относительно секции. */
    getCoords(ctx, e) {
        const rect = ctx.section.getBoundingClientRect();
        return { x: e.clientX - rect.left, y: e.clientY - rect.top };
    }

    onEnter(ctx, e) {
        if (this.isInteractive(e.target)) return this.hide(ctx);
        this.move(ctx, e, true);
    }

    onMove(ctx, e) {
        if (this.isInteractive(e.target)) return this.hide(ctx);
        this.move(ctx, e, false);
    }

    onDown(ctx, e) {
        // Для мыши показ уже произошёл на pointerenter.
        if (e.pointerType === "mouse") return;
        if (this.isInteractive(e.target)) return this.hide(ctx);
        this.move(ctx, e, true);
    }

    onUp(ctx, e) {
        // Отпускание пальца — прячем пятно. Мышь не трогаем (у неё pointerup после клика).
        if (e.pointerType !== "mouse") this.hide(ctx);
    }

    /** Мгновенно ставит пятно в точку (без инерции). */
    setPos(ctx, x, y) {
        if (ctx.pos) {
            ctx.pos.x = x;
            ctx.pos.y = y;
        }
        ctx.section.style.setProperty("--spot-x", x + "px");
        ctx.section.style.setProperty("--spot-y", y + "px");
    }

    /** Плавно ведёт пятно к точке (через GSAP quickTo или напрямую). */
    follow(ctx, x, y) {
        if (ctx.xTo) {
            ctx.xTo(x);
            ctx.yTo(y);
        } else {
            this.setPos(ctx, x, y);
        }
    }

    /** Обновляет позицию пятна и показывает его. immediate — без инерции. */
    move(ctx, e, immediate) {
        const { x, y } = this.getCoords(ctx, e);

        if (!ctx.shown || immediate) {
            // Мгновенно ставим пятно в точку входа, чтобы не было «доезда» из (0,0).
            this.setPos(ctx, x, y);
        } else {
            this.follow(ctx, x, y);
        }

        this.show(ctx);
    }

    /** Показ пятна: CSS-переход по opacity (и рост радиуса). */
    show(ctx) {
        if (ctx.shown) return;
        ctx.shown = true;
        ctx.section.classList.add("is-spot-active");
    }

    /** Скрытие пятна. */
    hide(ctx) {
        if (!ctx.shown) return;
        ctx.shown = false;
        ctx.section.classList.remove("is-spot-active");
    }
}
