# Интеграция DeepSeek V4 Pro Геометрии в Hero-секцию

Ниже представлены обновленные файлы для интеграции. Геометрия позиционирована абсолютно относительно секции `.hero`, а GSAP-скрипт теперь считывает триггеры наведения на ваши кнопки `.magnetic-btn`.

---

## 📄 1. Обновленная верстка секции (Blade / HTML)

Замените вашу текущую секцию этим кодом. Сюда добавлен блок `<div class="hero-ai-graphics">`, содержащий структурированный SVG.

```html
<section class="hero relative overflow-hidden" id="home">
    <div class="hero-light" aria-hidden="true"></div>
    <div class="container relative z-20">
        <div class="hero-content max-w-[50%]">
            <!-- Ограничиваем контент слева, чтобы освободить место справа -->
            <p class="hero-eyebrow">Веб-мастер &bull; Брянск</p>
            <h1 class="hero-title display-xl">
                Создаю сайты,<br />
                которые <span class="gradient-text">работают</span><br />
                на ваш бизнес
            </h1>
            <p class="hero-subtitle body-lg">
                Качество веб-студии без студийных наценок. Разработка landing
                page, корпоративных сайтов и internet-магазинов под ключ с
                официальным договором.
            </p>
            <div class="hero-actions">
                <a href="#work" class="magnetic-btn">
                    <span class="btn btn-primary btn-lg">Смотреть проекты</span>
                </a>
                <a href="#contacts" class="magnetic-btn">
                    <span class="btn btn-secondary btn-lg"
                        >Обсудить проект</span
                    >
                </a>
            </div>
        </div>
    </div>

    <!-- Интерактивная 3D-геометрия ИИ-агента (Занимает правую половину экрана) -->
    <div
        class="hero-ai-graphics absolute right-0 top-0 w-1/2 h-full flex items-center justify-center pointer-events-none z-10 hidden md:flex"
    >
        <svg
            id="deepseek-vector-core"
            class="w-[90%] h-[80%] max-w-[650px] pointer-events-auto opacity-0 transform translate-x-10"
            viewBox="0 0 800 800"
            fill="none"
            xmlns="http://w3.org"
        >
            <defs>
                <linearGradient
                    id="line-grad"
                    x1="0%"
                    y1="0%"
                    x2="100%"
                    y2="100%"
                >
                    <stop offset="0%" stop-color="#2D2D2D" stop-opacity="0.1" />
                    <stop
                        offset="50%"
                        stop-color="#1A1A1A"
                        stop-opacity="0.7"
                    />
                    <stop
                        offset="100%"
                        stop-color="#2D2D2D"
                        stop-opacity="0.1"
                    />
                </linearGradient>
            </defs>

            <!-- Орбитальные круги -->
            <g class="tech-circles">
                <circle
                    cx="400"
                    cy="400"
                    r="320"
                    stroke="url(#line-grad)"
                    stroke-width="1"
                    stroke-dasharray="6 16"
                />
                <circle
                    cx="400"
                    cy="400"
                    r="220"
                    stroke="url(#line-grad)"
                    stroke-width="1.5"
                    stroke-dasharray="50 15 10 15"
                />
                <circle
                    cx="400"
                    cy="400"
                    r="120"
                    stroke="url(#line-grad)"
                    stroke-width="1"
                />
            </g>

            <!-- Нейронные связи (Plexus) -->
            <g class="plexus-lines" stroke="url(#line-grad)" stroke-width="1.5">
                <line x1="400" y1="400" x2="250" y2="220" id="line-text" />
                <line x1="400" y1="400" x2="580" y2="280" id="line-code" />
                <line x1="400" y1="400" x2="520" y2="550" id="line-logic" />
                <line x1="400" y1="400" x2="230" y2="500" id="line-agent" />
            </g>

            <!-- Вычислительные кластеры -->
            <g class="network-nodes">
                <!-- Центральное ядро MoE -->
                <circle
                    cx="400"
                    cy="400"
                    r="22"
                    fill="#111111"
                    stroke="#2D2D2D"
                    stroke-width="2"
                    class="node-main"
                />
                <circle
                    cx="400"
                    cy="400"
                    r="6"
                    fill="#1A1A1A"
                    class="node-pulse"
                />

                <!-- Функциональные узлы -->
                <polygon
                    points="250,220 258,232 242,232"
                    fill="#1A1A1A"
                    class="node-item text-node"
                />
                <rect
                    x="572"
                    y="272"
                    width="16"
                    height="16"
                    rx="3"
                    fill="#2D2D2D"
                    class="node-item code-node"
                />
                <circle
                    cx="520"
                    cy="550"
                    r="7"
                    fill="#111111"
                    stroke="#1A1A1A"
                    stroke-width="2"
                    class="node-item logic-node"
                />
                <rect
                    x="224"
                    y="494"
                    width="12"
                    height="12"
                    transform="rotate(45 230 500)"
                    fill="#2D2D2D"
                    class="node-item agent-node"
                />

                <!-- Точки микро-данных -->
                <circle
                    cx="380"
                    cy="150"
                    r="3"
                    fill="#1A1A1A"
                    class="node-sub"
                />
                <circle
                    cx="520"
                    cy="170"
                    r="3"
                    fill="#2D2D2D"
                    class="node-sub"
                />
                <circle
                    cx="420"
                    cy="650"
                    r="3"
                    fill="#1A1A1A"
                    class="node-sub"
                />
                <circle
                    cx="210"
                    cy="350"
                    r="3"
                    fill="#2D2D2D"
                    class="node-sub"
                />
            </g>
        </svg>
    </div>

    <div class="hero-scroll-indicator" aria-hidden="true">
        <span class="hero-scroll-text">Скролл</span>
        <div class="hero-scroll-line"></div>
    </div>
</section>
```

---

## ⚡ 2. Сценарий интерактивной кинетики (GSAP в `app.js`)

Этот скрипт не просто вращает геометрию, но и заставляет всю структуру сжиматься и ускоряться в 3 раза при наведении курсора на ваши кнопки, создавая мощный UI-отклик.

```javascript
import { gsap } from "gsap";

document.addEventListener("DOMContentLoaded", () => {
    const svgCore = document.querySelector("#deepseek-vector-core");
    if (!svgCore) return;

    // Ссылка на глобальный таймлайн для управления скоростью триггеров
    let motionSpeed = { value: 1 };

    // 1. Анимация появления (Въезд справа + проявление)
    gsap.to(svgCore, { opacity: 1, x: 0, duration: 1.8, ease: "power3.out" });

    // 2. Вращение орбитальных кругов
    const circles = svgCore.querySelectorAll(".tech-circles circle");
    const rot1 = gsap.to(circles[0], {
        rotation: 360,
        transformOrigin: "50% 50%",
        duration: 60,
        repeat: -1,
        ease: "none",
    });
    const rot2 = gsap.to(circles[1], {
        rotation: -360,
        transformOrigin: "50% 50%",
        duration: 40,
        repeat: -1,
        ease: "none",
    });
    const rot3 = gsap.to(circles[2], {
        rotation: 180,
        transformOrigin: "50% 50%",
        duration: 25,
        repeat: -1,
        ease: "none",
    });

    // 3. Базовая пульсация ядра DeepSeek
    const pulseTimeline = gsap.to(".node-pulse", {
        scale: 2.8,
        opacity: 0,
        transformOrigin: "50% 50%",
        duration: 2.5,
        repeat: -1,
        ease: "sine.out",
    });

    // 4. Хаотичное покачивание узлов-экспертов
    const nodes = svgCore.querySelectorAll(".node-item, .node-sub");
    nodes.forEach((node, index) => {
        gsap.to(node, {
            x: "random(-14, 14)",
            y: "random(-14, 14)",
            rotation: "random(-15, 15)",
            transformOrigin: "50% 50%",
            duration: `random(3.5, 6)`,
            repeat: -1,
            yoyo: true,
            ease: "sine.inOut",
            delay: index * 0.1,
        });
    });

    // 5. Динамический рендеринг линий связей (Ticker)
    const lines = [
        {
            el: svgCore.querySelector("#line-text"),
            node: ".text-node",
            baseX: 250,
            baseY: 220,
        },
        {
            el: svgCore.querySelector("#line-code"),
            node: ".code-node",
            baseX: 580,
            baseY: 280,
        },
        {
            el: svgCore.querySelector("#line-logic"),
            node: ".logic-node",
            baseX: 520,
            baseY: 550,
        },
        {
            el: svgCore.querySelector("#line-agent"),
            node: ".agent-node",
            baseX: 230,
            baseY: 500,
        },
    ];

    gsap.ticker.add(() => {
        lines.forEach((line) => {
            if (!line.el) return;
            const targetNode = svgCore.querySelector(line.node);
            if (!targetNode) return;

            const currentX =
                line.baseX + (gsap.getProperty(targetNode, "x") || 0);
            const currentY =
                line.baseY + (gsap.getProperty(targetNode, "y") || 0);

            line.el.setAttribute("x2", currentX);
            line.el.setAttribute("y2", currentY);
        });
    });

    // 6. Мышь: Легкий параллакс всей группы геометрии
    window.addEventListener("mousemove", (e) => {
        const normX = e.clientX / window.innerWidth - 0.5;
        const normY = e.clientY / window.innerHeight - 0.5;

        gsap.to(".network-nodes, .plexus-lines", {
            x: normX * 40,
            y: normY * 40,
            duration: 1,
            ease: "power1.out",
            overwrite: "auto",
        });

        gsap.to(".tech-circles", {
            x: normX * 20,
            y: normY * 20,
            duration: 1.4,
            ease: "power1.out",
            overwrite: "auto",
        });
    });

    // 7. Интерактивные триггеры наведения на ваши кнопки .magnetic-btn
    const actionButtons = document.querySelectorAll(".magnetic-btn");
    actionButtons.forEach((btn) => {
        btn.addEventListener("mouseenter", () => {
            // Ускоряем вращение орбит и пульсацию ядра (ИИ активирован)
            gsap.to([rot1, rot2, rot3, pulseTimeline], {
                timeScale: 3,
                duration: 0.5,
            });

            // Сжимаем связи ближе к центру, концентрируя энергию
            gsap.to(".network-nodes", {
                scale: 0.85,
                transformOrigin: "50% 50%",
                duration: 0.6,
                ease: "back.out(1.7)",
            });
            gsap.to(".plexus-lines", {
                scale: 0.9,
                transformOrigin: "50% 50%",
                duration: 0.6,
                ease: "back.out(1.7)",
            });
        });

        btn.addEventListener("mouseleave", () => {
            // Возвращаем базовую скорость и масштаб в исходное состояние
            gsap.to([rot1, rot2, rot3, pulseTimeline], {
                timeScale: 1,
                duration: 0.8,
            });
            gsap.to(".network-nodes, .plexus-lines", {
                scale: 1,
                transformOrigin: "50% 50%",
                duration: 0.8,
                ease: "power2.out",
            });
        });
    });
});
```
