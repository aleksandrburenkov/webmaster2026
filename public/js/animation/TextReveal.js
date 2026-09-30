export class TextReveal {
    constructor() {
        const gsap = window.gsap;
        const ScrollTrigger = window.ScrollTrigger;
        if (!gsap || !ScrollTrigger) return;

        gsap.registerPlugin(ScrollTrigger);

        gsap.utils.toArray(".section-label").forEach((el) => {
            gsap.fromTo(
                el,
                { opacity: 0, y: -20 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.7,
                    ease: "power2.out",
                    scrollTrigger: { trigger: el, start: "top 85%" },
                },
            );
        });

        // gsap.utils.toArray(".reveal-text").forEach((el) => {
        //     gsap.fromTo(
        //         el,
        //         { opacity: 0, y: 30 },
        //         {
        //             opacity: 1,
        //             y: 0,
        //             duration: 0.8,
        //             ease: "power3.out",
        //             scrollTrigger: { trigger: el, start: "top 85%" },
        //         },
        //     );
        // });
    }
}
