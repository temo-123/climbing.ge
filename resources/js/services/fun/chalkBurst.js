// Chalk-dust puff on click — a small climbing touch for the main call-to-action
// buttons and tiles. Pure DOM, no dependencies; skipped for reduced-motion users.

const TARGETS = '.btn-primary, .btn-hold, .btn-success, .cg-quick, .cg-discipline, .cg-service-tile, .slide_read_more_btn';
const PARTICLES = 12;

function puff(x, y) {
    const layer = document.createElement('div');
    layer.className = 'cg-chalk';
    layer.style.left = x + 'px';
    layer.style.top = y + 'px';

    for (let i = 0; i < PARTICLES; i++) {
        const dot = document.createElement('span');
        const angle = (Math.PI * 2 * i) / PARTICLES + Math.random() * 0.5;
        const distance = 18 + Math.random() * 26;
        dot.style.setProperty('--dx', Math.cos(angle) * distance + 'px');
        dot.style.setProperty('--dy', Math.sin(angle) * distance - 10 + 'px');
        dot.style.setProperty('--s', (0.5 + Math.random() * 0.9).toFixed(2));
        dot.style.animationDelay = Math.random() * 60 + 'ms';
        layer.appendChild(dot);
    }

    document.body.appendChild(layer);
    setTimeout(() => layer.remove(), 900);
}

export function installChalkBurst() {
    if (typeof window === 'undefined' || window.__cgChalk) return;
    window.__cgChalk = true;

    const reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)');

    document.addEventListener('click', (event) => {
        if (reduced && reduced.matches) return;
        const target = event.target.closest && event.target.closest(TARGETS);
        if (!target || target.disabled) return;
        // keyboard clicks report 0,0 — puff from the element's centre instead
        let { clientX: x, clientY: y } = event;
        if (!x && !y) {
            const rect = target.getBoundingClientRect();
            x = rect.left + rect.width / 2;
            y = rect.top + rect.height / 2;
        }
        puff(x, y);
    }, { passive: true, capture: true }); // capture: still fires inside @click.stop areas
}
