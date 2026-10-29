// Site-wide switch for the article-page animations (navbar wand button,
// user default "animations"). Off = those animations are not shown at all:
// the climbing scenes beside the article menu, the mobile scene banner and
// the rappel climber on long pages. The 404 / empty-state scenes and the
// bolter are not affected. Stored per device in localStorage (cg_motion), so
// it holds after reloads and page changes; until the visitor chooses it
// follows the OS "reduce motion" setting. Changes fire `cg-motion-change`.
import { reactive } from 'vue';

const KEY = 'cg_motion';

function os_reduced() {
    return !!(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches);
}

function read() {
    try {
        const v = localStorage.getItem(KEY);
        if (v === 'on') return true;
        if (v === 'off') return false;
    } catch (e) {}
    return !os_reduced();
}

export const motion = reactive({ enabled: read() });

function apply() {
    document.documentElement.classList.toggle('cg-motion-off', !motion.enabled);
}

export function motion_enabled() {
    return motion.enabled;
}

export function set_motion(on) {
    motion.enabled = !!on;
    try { localStorage.setItem(KEY, on ? 'on' : 'off'); } catch (e) {}
    apply();
    window.dispatchEvent(new CustomEvent('cg-motion-change', { detail: { enabled: motion.enabled } }));
}

export function install_motion() {
    if (window.__cgMotion) return;
    window.__cgMotion = true;
    // OS "reduce motion" only — sliders, smooth scrolling, the 404 scene, chalk puffs
    window.cgReducedMotion = os_reduced;
    // another tab changed the switch
    window.addEventListener('storage', e => { if (e.key === KEY) { motion.enabled = read(); apply(); } });
    apply();
}
