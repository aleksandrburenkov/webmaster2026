export class FooterAnimation {
    constructor() {
        this.footer = document.querySelector(".footer");
        if (!this.footer) return;

        this.canvas = null;
        this.ctx = null;
        this.particles = [];
        this.lineSliders = [];
        this.width = 0;
        this.height = 0;
        this.rafId = null;
        this.spacing = 80;

        this.init();
    }

    init() {
        this.createCanvas();
        this.createParticles();
        this.createInitialSliders();
        this.animate();
        this.bindResize();
    }

    createCanvas() {
        this.canvas = document.createElement("canvas");
        this.canvas.setAttribute("aria-hidden", "true");
        this.canvas.style.cssText =
            "position:absolute;inset:0;pointer-events:none;z-index:0;";
        this.footer.appendChild(this.canvas);
        this.ctx = this.canvas.getContext("2d");

        const rect = this.footer.getBoundingClientRect();
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

        for (let i = 0; i < 12; i++) {
            const isTag = Math.random() > 0.4;
            this.particles.push({
                x: Math.random() * this.width,
                y: Math.random() * this.height,
                size: 1.5 + Math.random() * 3,
                speedX: (Math.random() - 0.5) * 0.3,
                speedY: (Math.random() - 0.5) * 0.3,
                opacity: 0.03 + Math.random() * 0.08,
                type: isTag ? "tag" : "dot",
                tag: isTag
                    ? tags[Math.floor(Math.random() * tags.length)]
                    : null,
                vx: 0.15 + Math.random() * 0.25,
                vy: 0.15 + Math.random() * 0.25,
            });
        }
    }

    createInitialSliders() {
        for (let i = 0; i < 6; i++) {
            this.spawnSlider(true);
        }
    }

    spawnSlider(randomProgress = false) {
        const isHorizontal = Math.random() > 0.5;
        const speed = 0.8 + Math.random() * 1.5;
        const length = 30 + Math.random() * 50;

        if (isHorizontal) {
            const linesCount = Math.floor(this.height / this.spacing);
            const targetY =
                Math.floor(Math.random() * linesCount) * this.spacing;
            this.lineSliders.push({
                axis: "horizontal",
                coordinate: targetY,
                pos: randomProgress ? Math.random() * this.width : -length,
                speed: speed,
                length: length,
                maxPos: this.width,
            });
        } else {
            const linesCount = Math.floor(this.width / this.spacing);
            const targetX =
                Math.floor(Math.random() * linesCount) * this.spacing;
            this.lineSliders.push({
                axis: "vertical",
                coordinate: targetX,
                pos: randomProgress ? Math.random() * this.height : -length,
                speed: speed,
                length: length,
                maxPos: this.height,
            });
        }
    }

    drawGrid() {
        if (!this.ctx) return;
        const ctx = this.ctx;
        const spacing = this.spacing;
        const baseOpacity = 0.2;

        const accentRgb =
            getComputedStyle(document.documentElement)
                .getPropertyValue("--color-accent-rgb")
                .trim() || "45, 45, 45";

        ctx.strokeStyle = `rgba(${accentRgb}, ${baseOpacity})`;
        ctx.lineWidth = 0.25;

        const fadeHeight = this.height * 0.5;

        for (let x = 0; x < this.width; x += spacing) {
            ctx.beginPath();
            ctx.moveTo(x, 0);
            ctx.lineTo(x, this.height);

            const gradient = ctx.createLinearGradient(0, 0, 0, this.height);
            gradient.addColorStop(0, `rgba(${accentRgb}, 0)`);
            gradient.addColorStop(
                fadeHeight / this.height,
                `rgba(${accentRgb}, ${baseOpacity})`,
            );
            gradient.addColorStop(0.5, `rgba(${accentRgb}, ${baseOpacity})`);
            gradient.addColorStop(
                1 - fadeHeight / this.height,
                `rgba(${accentRgb}, ${baseOpacity})`,
            );
            gradient.addColorStop(1, `rgba(${accentRgb}, 0)`);

            ctx.strokeStyle = gradient;
            ctx.stroke();
        }

        for (let y = 0; y < this.height; y += spacing) {
            ctx.beginPath();
            ctx.moveTo(0, y);
            ctx.lineTo(this.width, y);

            let opacity = baseOpacity;
            if (y < fadeHeight) {
                opacity = baseOpacity * (y / fadeHeight);
            } else if (y > this.height - fadeHeight) {
                opacity = baseOpacity * ((this.height - y) / fadeHeight);
            }

            ctx.strokeStyle = `rgba(${accentRgb}, ${opacity})`;
            ctx.stroke();
        }
    }

    updateAndDrawSliders() {
        if (!this.ctx) return;
        const ctx = this.ctx;
        const accentRgb =
            getComputedStyle(document.documentElement)
                .getPropertyValue("--color-accent-rgb")
                .trim() || "45, 45, 45";
        const fadeHeight = this.height * 0.5;

        if (this.lineSliders.length < 10 && Math.random() < 0.03) {
            this.spawnSlider(false);
        }

        ctx.lineWidth = 0.6;

        this.lineSliders.forEach((slider) => {
            slider.pos += slider.speed;

            let sliderOpacity = 0.25;

            if (slider.axis === "horizontal") {
                if (slider.coordinate < fadeHeight) {
                    sliderOpacity *= slider.coordinate / fadeHeight;
                } else if (slider.coordinate > this.height - fadeHeight) {
                    sliderOpacity *=
                        (this.height - slider.coordinate) / fadeHeight;
                }

                ctx.strokeStyle = `rgba(${accentRgb}, ${sliderOpacity})`;
                ctx.beginPath();
                ctx.moveTo(slider.pos, slider.coordinate);
                ctx.lineTo(slider.pos + slider.length, slider.coordinate);
                ctx.stroke();
            } else {
                ctx.beginPath();
                ctx.moveTo(slider.coordinate, slider.pos);
                ctx.lineTo(slider.coordinate, slider.pos + slider.length);

                const grad = ctx.createLinearGradient(
                    0,
                    slider.pos,
                    0,
                    slider.pos + slider.length,
                );
                const getFadeOp = (y) => {
                    let op = sliderOpacity;
                    if (y < fadeHeight) op *= y / fadeHeight;
                    else if (y > this.height - fadeHeight)
                        op *= (this.height - y) / fadeHeight;
                    return Math.max(0, op);
                };

                grad.addColorStop(
                    0,
                    `rgba(${accentRgb}, ${getFadeOp(slider.pos)})`,
                );
                grad.addColorStop(
                    1,
                    `rgba(${accentRgb}, ${getFadeOp(slider.pos + slider.length)})`,
                );

                ctx.strokeStyle = grad;
                ctx.stroke();
            }
        });

        this.lineSliders = this.lineSliders.filter(
            (slider) => slider.pos < slider.maxPos,
        );
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
        ctx.globalAlpha = isDark ? 0.04 : 0.03;

        const bracketSize = 60;
        const positions = [
            { x: this.width - 60, y: this.height * 0.2 },
            { x: 40, y: this.height * 0.5 },
            { x: this.width - 80, y: this.height * 0.75 },
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
                ctx.font = `${10 + p.size}px var(--font-mono, monospace)`;
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
            this.updateAndDrawSliders();
            this.drawBrackets();
            this.updateParticles();
            this.drawParticles();

            this.rafId = requestAnimationFrame(loop);
        };

        loop();
    }

    bindResize() {
        window.addEventListener("resize", () => {
            if (!this.canvas || !this.footer) return;
            const rect = this.footer.getBoundingClientRect();
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