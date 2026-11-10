// How article sections are shown: 'plain' (default, the text flows) or
// 'cards' (each section in its own card). Switched from the right menu and
// the phone "On this page" sheet. Stored per device in localStorage
// (cg_article_view) and applied as <html data-article-view="…">, which the
// CSS in sass/components/_article.scss reads (.cg-section blocks).
import { reactive } from 'vue';

const KEY = 'cg_article_view';
const VIEWS = ['plain', 'cards'];

function read() {
    try {
        const v = localStorage.getItem(KEY);
        if (VIEWS.includes(v)) return v;
    } catch (e) {}
    return 'plain';
}

export const article_view = reactive({ mode: read() });

function apply() {
    document.documentElement.setAttribute('data-article-view', article_view.mode);
}

export function set_article_view(mode) {
    if (!VIEWS.includes(mode)) return;
    article_view.mode = mode;
    try { localStorage.setItem(KEY, mode); } catch (e) {}
    apply();
    // card padding changes the width of charts / canvases inside the sections;
    // they only re-measure on window resize (Google Charts, route canvases)
    requestAnimationFrame(() => window.dispatchEvent(new Event('resize')));
}

apply();
