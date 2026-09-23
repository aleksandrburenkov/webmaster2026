import { gsap } from "gsap";

document.addEventListener("DOMContentLoaded", () => {
    const svgCore = document.querySelector("#web-anim-vector-core");
    if (!svgCore) return;

    let motionSpeed = { value: 1 };

    gsap.to(svgCore, { opacity: 1, x: 0, duration: 1.8, ease: "power3.out" });

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

    const pulseTimeline = gsap.to(".node-pulse", {
        scale: 2.8,
        opacity: 0,
        transformOrigin: "50% 50%",
        duration: 2.5,
        repeat: -1,
        ease: "sine.out",
    });

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

    const actionButtons = document.querySelectorAll(".magnetic-btn");
    actionButtons.forEach((btn) => {
        btn.addEventListener("mouseenter", () => {
            gsap.to([rot1, rot2, rot3, pulseTimeline], {
                timeScale: 3,
                duration: 0.5,
            });

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

    const updateGradientForTheme = (isDark) => {
        const gradient = document.querySelector("#line-grad");
        if (!gradient) return;
        const stops = gradient.querySelectorAll("stop");
        if (stops.length < 3) return;
        if (isDark) {
            stops[0].setAttribute("stop-color", "#D2D2D2");
            stops[0].setAttribute("stop-opacity", "0.15");
            stops[1].setAttribute("stop-color", "#E5E5E5");
            stops[1].setAttribute("stop-opacity", "0.4");
            stops[2].setAttribute("stop-color", "#D2D2D2");
            stops[2].setAttribute("stop-opacity", "0.15");
        } else {
            stops[0].setAttribute("stop-color", "#2D2D2D");
            stops[0].setAttribute("stop-opacity", "0.1");
            stops[1].setAttribute("stop-color", "#1A1A1A");
            stops[1].setAttribute("stop-opacity", "0.7");
            stops[2].setAttribute("stop-color", "#2D2D2D");
            stops[2].setAttribute("stop-opacity", "0.1");
        }
    };

    const themeObserver = new MutationObserver((mutations) => {
        for (const mutation of mutations) {
            if (mutation.type === "attributes" && mutation.attributeName === "data-theme") {
                const isDark = document.documentElement.getAttribute("data-theme") === "dark";
                updateGradientForTheme(isDark);
            }
        }
    });

    themeObserver.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ["data-theme"],
    });

    const isDarkInitial = document.documentElement.getAttribute("data-theme") === "dark";
    updateGradientForTheme(isDarkInitial);
});
