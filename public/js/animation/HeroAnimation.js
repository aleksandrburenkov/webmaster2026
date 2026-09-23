export class HeroAnimation {
    constructor() {
        this.hero = document.querySelector(".hero");
        if (!this.hero) return;

        this.canvas = null;
        this.ctx = null;
        this.particles = [];
        this.width = 0;
        this.height = 0;
        this.rafId = null;

        this.init();
    }

    init() {
        this.createCanvas();
        this.createParticles();
        this.animate();
        this.animateContent();
        this.bindResize();
    }

    createCanvas() {
        this.canvas = document.createElement("canvas");
        this.canvas.setAttribute("aria-hidden", "true");
        this.canvas.style.cssText =
            "position:absolute;inset:0;pointer-events:none;z-index:0;";
        this.hero.appendChild(this.canvas);
        this.ctx = this.canvas.getContext("2d");

        const rect = this.hero.getBoundingClientRect();
        this.width = rect.width;
        this.height = rect.height;
        this.canvas.width = this.width;
        this.canvas.height = this.height;
    }

    createParticles() {
        const tags = [
            "<div>",
            "</>",
            "{ }",
            "<>",
            "//",
            "#",
            "*",
            "=>",
            "()",
            "[]",
        ];
        this.particles = [];

        for (let i = 0; i < 20; i++) {
            const isTag = Math.random() > 0.4;
            this.particles.push({
                x: Math.random() * this.width,
                y: Math.random() * this.height,
                size: 2 + Math.random() * 4,
                speedX: (Math.random() - 0.5) * 0.3,
                speedY: (Math.random() - 0.5) * 0.3,
                opacity: 0.05 + Math.random() * 0.12,
                type: isTag ? "tag" : "dot",
                tag: isTag
                    ? tags[Math.floor(Math.random() * tags.length)]
                    : null,
                vx: 0.2 + Math.random() * 0.3,
                vy: 0.2 + Math.random() * 0.3,
            });
        }
    }

    drawGrid() {
        if (!this.ctx) return;
        const ctx = this.ctx;
        const spacing = 80;
        const baseOpacity = 0.3;

        // 1. Динамически получаем значение CSS-переменной (только цифры RGB)
        const accentRgb =
            getComputedStyle(document.documentElement)
                .getPropertyValue("--color-accent-rgb")
                .trim() || "45, 45, 45"; // Резервный цвет, если переменная не задана

        // Применяем чистый RGB-цвет для базовой сетки
        ctx.strokeStyle = `rgba(${accentRgb}, ${baseOpacity})`;
        ctx.lineWidth = 0.3;

        // Create gradient for vertical fading at top and bottom edges
        const fadeHeight = this.height * 0.5; // 15% of height for fade zone

        for (let x = 0; x < this.width; x += spacing) {
            ctx.beginPath();
            ctx.moveTo(x, 0);
            ctx.lineTo(x, this.height);

            // Create vertical gradient for fading at edges
            const gradient = ctx.createLinearGradient(0, 0, 0, this.height);

            // Fully transparent at top edge
            gradient.addColorStop(0, `rgba(${accentRgb}, 0)`);
            // Fade in
            gradient.addColorStop(
                fadeHeight / this.height,
                `rgba(${accentRgb}, ${baseOpacity})`,
            );
            // Full opacity in middle
            gradient.addColorStop(0.5, `rgba(${accentRgb}, ${baseOpacity})`);
            // Fade out
            gradient.addColorStop(
                1 - fadeHeight / this.height,
                `rgba(${accentRgb}, ${baseOpacity})`,
            );
            // Fully transparent at bottom edge
            gradient.addColorStop(1, `rgba(${accentRgb}, 0)`);

            ctx.strokeStyle = gradient;
            ctx.stroke();
        }

        for (let y = 0; y < this.height; y += spacing) {
            ctx.beginPath();
            ctx.moveTo(0, y);
            ctx.lineTo(this.width, y);

            // Calculate opacity based on vertical position for horizontal lines
            let opacity = baseOpacity;
            if (y < fadeHeight) {
                // Fade in from top
                opacity = baseOpacity * (y / fadeHeight);
            } else if (y > this.height - fadeHeight) {
                // Fade out to bottom
                opacity = baseOpacity * ((this.height - y) / fadeHeight);
            }

            ctx.strokeStyle = `rgba(${accentRgb}, ${opacity})`;
            ctx.stroke();
        }
    }

    drawBrackets() {
        if (!this.ctx) return;
        const ctx = this.ctx;
        const accent =
            getComputedStyle(document.documentElement)
                .getPropertyValue("--color-accent")
                .trim() || "#2D2D2D";
        const isDark =
            document.documentElement.getAttribute("data-theme") === "dark";

        ctx.fillStyle = accent;
        ctx.globalAlpha = isDark ? 0.06 : 0.04;

        const bracketSize = 80;
        const positions = [
            { x: this.width - 100, y: this.height * 0.15 },
            { x: 80, y: this.height * 0.4 },
            { x: this.width - 140, y: this.height * 0.65 },
            { x: 120, y: this.height * 0.8 },
        ];

        positions.forEach((pos) => {
            ctx.font = `${bracketSize}px var(--font-mono, monospace)`;
            ctx.save();
            ctx.translate(pos.x, pos.y);
            ctx.fillText("{", 0, 0);
            ctx.restore();
        });

        ctx.globalAlpha = 1;
    }

    drawParticles() {
        if (!this.ctx) return;
        const ctx = this.ctx;
        const accent =
            getComputedStyle(document.documentElement)
                .getPropertyValue("--color-accent")
                .trim() || "#2D2D2D";

        this.particles.forEach((p) => {
            ctx.save();
            ctx.globalAlpha = p.opacity;

            if (p.type === "dot") {
                ctx.fillStyle = accent;
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fill();
            } else if (p.type === "tag" && p.tag) {
                ctx.fillStyle = accent;
                ctx.font = `${11 + p.size}px var(--font-mono, monospace)`;
                ctx.fillText(p.tag, p.x, p.y);
            }

            ctx.restore();
        });
    }

    updateParticles() {
        const buffer = 20;
        this.particles.forEach((p) => {
            p.x += p.vx * 0.3;
            p.y += p.vy * 0.3;

            if (p.x > this.width + buffer) p.x = -buffer;
            if (p.x < -buffer) p.x = this.width + buffer;
            if (p.y > this.height + buffer) p.y = -buffer;
            if (p.y < -buffer) p.y = this.height + buffer;
        });
    }

    animate() {
        const loop = () => {
            if (!this.ctx) return;
            this.ctx.clearRect(0, 0, this.width, this.height);

            this.drawGrid();
            this.drawBrackets();
            this.updateParticles();
            this.drawParticles();

            this.rafId = requestAnimationFrame(loop);
        };

        loop();
    }

    animateContent() {
        const selectors = [
            ".hero-eyebrow",
            ".hero-title",
            ".hero-subtitle",
            ".hero-actions",
        ];
        const elements = [];

        selectors.forEach((sel) => {
            const el = this.hero.querySelector(sel);
            if (el) {
                el.style.animation = "none";
                el.style.opacity = "0";
                elements.push(el);
            }
        });

        if (!elements.length) return;

        gsap.fromTo(
            elements,
            { opacity: 0, y: 40 },
            {
                opacity: 1,
                y: 0,
                duration: 0.8,
                stagger: 0.15,
                ease: "power3.out",
                delay: 0.3,
            },
        );
    }

    bindResize() {
        window.addEventListener("resize", () => {
            if (!this.canvas || !this.hero) return;
            const rect = this.hero.getBoundingClientRect();
            this.width = rect.width;
            this.height = rect.height;
            this.canvas.width = this.width;
            this.canvas.height = this.height;
        });
    }

    destroy() {
        if (this.rafId) {
            cancelAnimationFrame(this.rafId);
        }
        if (this.canvas && this.canvas.parentNode) {
            this.canvas.parentNode.removeChild(this.canvas);
        }
    }
}
