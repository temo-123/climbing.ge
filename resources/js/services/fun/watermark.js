// Image watermark (see sass/components/_watermark.scss). Nothing is baked
// into the image files: the mark is a CSS overlay on boxes with .cg-wm, and a
// copy with the same mark drawn in is made only when someone downloads.
//
// install_watermark() (once, from app.js):
//   - hides the mark on small boxes (thumbnails, avatars): one shared
//     ResizeObserver sets data-wm-off when a box is under 180×120 px;
//   - images put in texts with the editor (…/editor_img/…, old
//     …/uploads/editor/…) get a .cg-wm wrapper and open in the site's image
//     viewer on click (GlobalImageViewer, event "cg-open-image");
//   - right-click / long-press on a watermarked photo shows "Download image"
//     instead of the browser's "Save image as" (and it can't be dragged out).
//
// It only adds attributes / wrappers inside v-html text (never inside DOM
// that Vue renders from a template), so Vue's own patching is untouched.

const LABELS = {
    guide: 'climbing.ge', user: 'climbing.ge', shop: 'shop.climbing.ge',
    blog: 'blog.climbing.ge', summit: 'summit.climbing.ge', films: 'films.climbing.ge',
};
const MIN_W = 180, MIN_H = 120;
const RICH_IMG = 'img[src*="/editor_img/"], img[src*="/uploads/editor/"]';
const RICH_SCOPE = '.article_text_block, .cg-ev-body, [class*="post-body"], [class*="post_text"], [class*="description"], article, section, main';

export function watermark_label() {
    const service = (document.body && document.body.dataset.service) || 'guide';
    return LABELS[service] || LABELS.guide;
}

// ---------------------------------------------------------------- small boxes
let resizeObserver = null;
function watch_size(el) {
    if (!resizeObserver || el.__cgWm) return;
    el.__cgWm = true;
    resizeObserver.observe(el);
}
function on_resize(entries) {
    entries.forEach(({ target, contentRect }) => {
        const small = contentRect.width < MIN_W || contentRect.height < MIN_H;
        if (small) target.setAttribute('data-wm-off', '');
        else target.removeAttribute('data-wm-off');
    });
}

// ---------------------------------------------------------------- editor images
function wrap_rich_image(img) {
    if (img.__cgRich || img.closest('.ql-editor, .ql-container, [contenteditable="true"]')) return;
    img.__cgRich = true;
    img.classList.add('cg-rich-img');
    if (img.closest('a') || img.closest('.cg-wm')) return;     // a linked image keeps its link
    const wrap = document.createElement('span');
    wrap.className = 'cg-wm cg-wm--inline';
    img.parentNode.insertBefore(wrap, img);
    wrap.appendChild(img);
    watch_size(wrap);
}

function scan(root) {
    if (!root || root.nodeType !== 1) return;
    if (root.matches('.cg-wm')) watch_size(root);
    root.querySelectorAll('.cg-wm').forEach(watch_size);
    if (root.matches(RICH_IMG)) wrap_rich_image(root);
    root.querySelectorAll(RICH_IMG).forEach(wrap_rich_image);
}

function open_rich_image(img) {
    const scope = img.closest(RICH_SCOPE) || document.body;
    const imgs = Array.from(scope.querySelectorAll('img.cg-rich-img')).filter(i => !i.closest('a'));
    const gallery = imgs.map(i => ({ src: i.currentSrc || i.src, alt: i.alt || '' }));
    window.dispatchEvent(new CustomEvent('cg-open-image', {
        detail: { src: img.currentSrc || img.src, alt: img.alt || '', gallery, index: Math.max(0, imgs.indexOf(img)) },
    }));
}

// ---------------------------------------------------------------- download menu
let menu = null;
function close_menu() {
    if (!menu) return;
    menu.remove();
    menu = null;
    document.removeEventListener('pointerdown', on_outside, true);
    window.removeEventListener('scroll', close_menu, true);
    window.removeEventListener('keydown', on_key, true);
}
function on_outside(e) { if (menu && !menu.contains(e.target)) close_menu(); }
function on_key(e) { if (e.key === 'Escape') close_menu(); }

function t(key, fallback) {
    try { const v = window.$cgT && window.$cgT(key); return v && v !== key ? v : fallback; } catch (e) { return fallback; }
}

function show_menu(img, x, y) {
    close_menu();
    menu = document.createElement('div');
    menu.className = 'cg-wm-menu';
    menu.setAttribute('role', 'menu');
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.setAttribute('role', 'menuitem');
    btn.innerHTML = '<i class="fa fa-download" aria-hidden="true"></i>';
    btn.appendChild(document.createTextNode(' ' + t('global.watermark.download', 'Download image')));
    btn.addEventListener('click', () => { close_menu(); download_with_watermark(img); });
    menu.appendChild(btn);
    // a photo that is also a link (product cards…) keeps "open in a new tab"
    const link = img.closest('a[href]');
    if (link) {
        const open = document.createElement('button');
        open.type = 'button';
        open.setAttribute('role', 'menuitem');
        open.innerHTML = '<i class="fa fa-external-link" aria-hidden="true"></i>';
        open.appendChild(document.createTextNode(' ' + t('global.watermark.open_new_tab', 'Open in a new tab')));
        open.addEventListener('click', () => { close_menu(); window.open(link.href, '_blank', 'noopener'); });
        menu.appendChild(open);
    }
    document.body.appendChild(menu);
    const r = menu.getBoundingClientRect();
    menu.style.left = Math.min(x, window.innerWidth - r.width - 8) + 'px';
    menu.style.top = Math.min(y, window.innerHeight - r.height - 8) + 'px';
    btn.focus();
    document.addEventListener('pointerdown', on_outside, true);
    window.addEventListener('scroll', close_menu, true);
    window.addEventListener('keydown', on_key, true);
}

// a photo (<img>) or a drawn topo (<canvas>) inside a watermark box
function watermarked_img(target) {
    if (!target || (target.tagName !== 'IMG' && target.tagName !== 'CANVAS')) return null;
    if (document.body && document.body.dataset.service === 'user') return null;   // admin / user panel
    const box = target.closest('.cg-wm');
    return box && !box.hasAttribute('data-wm-off') ? target : null;
}

// ---------------------------------------------------------------- the download
// The photo at full size with the mark drawn exactly like the CSS overlay
// (same proportions, corner and look).
export async function download_with_watermark(img) {
    // a topo: the photo with the route drawing on top (canvas overlay, or a
    // canvas the photo is painted into)
    const box = img.closest && img.closest('.cg-wm');
    if (img.tagName === 'CANVAS' || (box && box.querySelector('canvas'))) return download_composite(box || img.parentNode, img);
    const src = img.currentSrc || img.src;
    try {
        const response = await fetch(src, { credentials: 'same-origin' });
        if (!response.ok) throw new Error('HTTP ' + response.status);
        const blob = await response.blob();
        const bitmap = await createImageBitmap(blob);
        const W = bitmap.width, H = bitmap.height;
        const canvas = document.createElement('canvas');
        canvas.width = W;
        canvas.height = H;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(bitmap, 0, 0);
        if (bitmap.close) bitmap.close();
        draw_mark(ctx, W, H, mark_ratio(img));

        const png = /png|webp|gif/i.test(blob.type);
        const type = png ? 'image/png' : 'image/jpeg';
        const out = await new Promise(resolve => canvas.toBlob(resolve, type, 0.92));
        const base = decodeURIComponent((src.split('?')[0].split('/').pop() || 'image').replace(/\.[a-z0-9]+$/i, ''));
        save_blob(out, base + '_' + watermark_label() + (png ? '.png' : '.jpg'));
    } catch (e) {
        if (window.$bus) window.$bus.$emit('toast', { type: 'danger', message: t('global.watermark.download_error', "Couldn't download this image.") });
    }
}

// photo (if any) + every canvas drawn over it, at the photo's full size
// (mark: false = a clean copy — admin topo downloads)
export async function download_composite(box, target, { mark = true } = {}) {
    try {
        const photo = box.querySelector('img');
        const canvases = Array.from(box.querySelectorAll('canvas'));
        let W, H;
        if (photo && photo.naturalWidth) { W = photo.naturalWidth; H = photo.naturalHeight; }
        else { const c = canvases[0] || target; W = c.width; H = c.height; }
        const out = document.createElement('canvas');
        out.width = W;
        out.height = H;
        const ctx = out.getContext('2d');
        if (photo && photo.naturalWidth) ctx.drawImage(photo, 0, 0, W, H);
        canvases.forEach(c => { if (c.width && c.height) ctx.drawImage(c, 0, 0, W, H); });
        if (mark) draw_mark(ctx, W, H, mark_ratio(target));
        const blob = await new Promise(resolve => out.toBlob(resolve, 'image/jpeg', 0.92));
        const name = photo ? decodeURIComponent(((photo.currentSrc || photo.src).split('?')[0].split('/').pop() || 'topo').replace(/\.[a-z0-9]+$/i, '')) : 'topo';
        save_blob(blob, name + (mark ? '_' + watermark_label() : '_topo') + '.jpg');
    } catch (e) {
        if (window.$bus) window.$bus.$emit('toast', { type: 'danger', message: t('global.watermark.download_error', "Couldn't download this image.") });
    }
}

function save_blob(blob, filename) {
    const a = document.createElement('a');
    a.href = URL.createObjectURL(blob);
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    a.remove();
    setTimeout(() => URL.revokeObjectURL(a.href), 4000);
}

// mark width as a share of the photo width: what the overlay uses on screen
// (clamp(96px, 34%, 260px) of the box), 34 % when unknown
function mark_ratio(img) {
    const box = img && img.closest('.cg-wm');
    const w = box ? box.getBoundingClientRect().width : 0;
    if (!w) return 0.34;
    return Math.min(260, Math.max(96, w * 0.34)) / w;
}

function draw_mark(ctx, W, H, ratio) {
    const markW = W * ratio;
    const s = markW / 320;                                   // the SVG is 320 × 40
    const x = W - W * 0.025 - markW;
    const y = H - H * 0.035 - 40 * s;
    ctx.save();
    ctx.translate(x, y);
    ctx.scale(s, s);
    ctx.globalAlpha = 0.78;
    ctx.lineJoin = 'round';
    ctx.lineWidth = 3;
    ctx.strokeStyle = 'rgba(0,0,0,.45)';
    ctx.fillStyle = '#fff';
    ctx.font = '800 24px system-ui, -apple-system, "Segoe UI", Roboto, Helvetica, Arial, sans-serif';
    ctx.textAlign = 'right';
    ctx.textBaseline = 'alphabetic';
    const label = watermark_label();
    ctx.strokeText(label, 278, 29);
    ctx.fillText(label, 278, 29);
    const peak = new Path2D('M284 32L294 14L300 22L306 10L318 32Z');
    ctx.stroke(peak);
    ctx.fill(peak);
    ctx.restore();
}

// ---------------------------------------------------------------- install
export function install_watermark() {
    if (typeof window === 'undefined' || window.__cgWatermark) return;
    window.__cgWatermark = true;

    if ('ResizeObserver' in window) resizeObserver = new ResizeObserver(on_resize);

    // new boxes / texts as the SPA renders (batched to one pass per frame)
    let pending = new Set(), raf = 0;
    const flush = () => { raf = 0; const nodes = pending; pending = new Set(); nodes.forEach(scan); };
    const start = () => {
        scan(document.body);
        new MutationObserver(records => {
            records.forEach(r => r.addedNodes.forEach(n => { if (n.nodeType === 1) pending.add(n); }));
            if (pending.size && !raf) raf = requestAnimationFrame(flush);
        }).observe(document.body, { childList: true, subtree: true });
    };
    if (document.body) start(); else document.addEventListener('DOMContentLoaded', start, { once: true });

    document.addEventListener('contextmenu', (e) => {
        const img = watermarked_img(e.target);
        if (!img) return;
        e.preventDefault();
        show_menu(img, e.clientX, e.clientY);
    }, true);

    document.addEventListener('dragstart', (e) => {
        if (watermarked_img(e.target)) e.preventDefault();
    }, true);

    document.addEventListener('click', (e) => {
        const img = e.target && e.target.tagName === 'IMG' && e.target.classList.contains('cg-rich-img') ? e.target : null;
        if (!img || img.closest('a')) return;
        e.preventDefault();
        open_rich_image(img);
    });
}
