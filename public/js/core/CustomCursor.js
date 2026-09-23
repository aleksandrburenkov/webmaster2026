export class CustomCursor {
    constructor() {
        this.isTouchDevice = window.matchMedia('(pointer: coarse)').matches;
        if (this.isTouchDevice) return;

        this.cursor = document.querySelector('.custom-cursor');
        this.dot = document.querySelector('.custom-cursor-dot');
        if (!this.cursor) return;

        this.mouseX = 0;
        this.mouseY = 0;
        this.cursorX = 0;
        this.cursorY = 0;
        this.dotX = 0;
        this.dotY = 0;
        this.isVisible = false;
        this.isHovering = false;

        this.init();
    }

    init() {
        this.bindEvents();
        this.animate();
    }

    bindEvents() {
        document.addEventListener('mousemove', (e) => {
            this.mouseX = e.clientX;
            this.mouseY = e.clientY;

            if (!this.isVisible) {
                this.isVisible = true;
                this.cursor.classList.add('visible');
                this.cursorX = this.mouseX;
                this.cursorY = this.mouseY;
            }
        });

        document.addEventListener('mouseleave', () => {
            this.isVisible = false;
            this.cursor.classList.remove('visible');
        });

        document.addEventListener('mouseenter', () => {
            this.isVisible = true;
            this.cursor.classList.add('visible');
        });

        document.addEventListener('mousedown', () => {
            this.cursor.classList.add('clicking');
        });

        document.addEventListener('mouseup', () => {
            this.cursor.classList.remove('clicking');
        });

        const interactiveElements = document.querySelectorAll('a, button, .portfolio-card, [data-cursor="link"]');
        interactiveElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                this.cursor.classList.add('hover-link');
            });
            el.addEventListener('mouseleave', () => {
                this.cursor.classList.remove('hover-link');
            });
        });

        const cardElements = document.querySelectorAll('.portfolio-card');
        cardElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                this.cursor.classList.add('hover-card');
            });
            el.addEventListener('mouseleave', () => {
                this.cursor.classList.remove('hover-card');
            });
        });
    }

    animate() {
        const ease = 0.12;
        this.cursorX += (this.mouseX - this.cursorX) * ease;
        this.cursorY += (this.mouseY - this.cursorY) * ease;

        this.dotX += (this.mouseX - this.dotX) * 0.3;
        this.dotY += (this.mouseY - this.dotY) * 0.3;

        if (this.cursor) {
            this.cursor.style.left = `${this.cursorX}px`;
            this.cursor.style.top = `${this.cursorY}px`;
        }

        if (this.dot) {
            this.dot.style.left = `${this.dotX}px`;
            this.dot.style.top = `${this.dotY}px`;
        }

        requestAnimationFrame(() => this.animate());
    }
}