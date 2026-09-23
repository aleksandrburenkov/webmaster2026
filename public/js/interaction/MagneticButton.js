export class MagneticButton {
    constructor() {
        this.isTouchDevice = window.matchMedia('(pointer: coarse)').matches;
        if (this.isTouchDevice) return;

        this.buttons = document.querySelectorAll('.magnetic-btn');
        if (!this.buttons.length) return;

        this.init();
    }

    init() {
        this.buttons.forEach(btn => {
            btn.addEventListener('mousemove', (e) => this.handleMove(e, btn));
            btn.addEventListener('mouseleave', () => this.handleLeave(btn));
        });
    }

    handleMove(e, btn) {
        const rect = btn.getBoundingClientRect();
        const x = e.clientX - rect.left - rect.width / 2;
        const y = e.clientY - rect.top - rect.height / 2;

        const strength = 0.3;
        const moveX = x * strength;
        const moveY = y * strength;

        btn.style.transform = `translate(${moveX}px, ${moveY}px)`;

        const inner = btn.querySelector('.btn');
        if (inner) {
            inner.style.transform = `translate(${moveX * 0.5}px, ${moveY * 0.5}px)`;
        }
    }

    handleLeave(btn) {
        btn.style.transform = 'translate(0, 0)';
        const inner = btn.querySelector('.btn');
        if (inner) {
            inner.style.transform = 'translate(0, 0)';
        }
    }
}