<template>
    <!-- Phones only: the right "page menu" is hidden under 768px, so long
         article pages get a floating "On this page" pill that opens a bottom
         sheet with the page's sections (and sectors, nested). Sections are
         read from the DOM — any h2[id] in `root`, or an element with an id
         whose first heading is an h2 — so it works on every article page
         without each page listing its own menu. -->
    <div class="cg-onpage d-print-none" v-if="items.length >= min_items">
        <button type="button" class="cg-onpage__pill" @click="open" :aria-expanded="is_open" aria-haspopup="dialog">
            <i class="fa fa-list-ul" aria-hidden="true"></i> {{ $t('global.on_this_page') }}
        </button>

        <transition name="cg-sheet">
            <div v-if="is_open" class="cg-onpage__backdrop" @click.self="close">
                <div class="cg-onpage__sheet" role="dialog" aria-modal="true" :aria-label="$t('global.on_this_page')" @keydown.esc="close">
                    <div class="cg-onpage__grip" aria-hidden="true"></div>
                    <div class="cg-onpage__head">
                        <strong>{{ $t('global.on_this_page') }}</strong>
                        <button type="button" class="cg-onpage__close" ref="close_btn" @click="close" :aria-label="$t('global.close')">
                            <i class="fa fa-times" aria-hidden="true"></i>
                        </button>
                    </div>
                    <ul class="cg-onpage__list">
                        <li v-for="item in items" :key="item.id" :class="['is-level-' + item.level, { 'is-current': item.id === current }]">
                            <a :href="'#' + item.id" @click.prevent="go(item)">{{ item.label }}</a>
                        </li>
                    </ul>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
    export default {
        name: 'OnThisPage',
        props: {
            root: { type: String, default: '.blog-main' },
            min_items: { type: Number, default: 3 },
        },
        data() {
            return { items: [], is_open: false, current: null, timers: [] };
        },
        watch: {
            '$route.path'() { this.items = []; this.schedule_scan(); },
        },
        mounted() { this.schedule_scan(); },
        beforeUnmount() { this.timers.forEach(clearTimeout); document.body.classList.remove('cg-sheet-open'); },
        methods: {
            // article content and sectors load asynchronously — look a few times
            schedule_scan() {
                this.timers.forEach(clearTimeout);
                this.timers = [1200, 3000, 6000].map(ms => setTimeout(this.scan, ms));
            },
            scan() {
                const root = document.querySelector(this.root);
                if (!root) { this.items = []; return; }
                const seen = new Set();
                const items = [];
                root.querySelectorAll('[id]').forEach(el => {
                    let heading = null;
                    if (el.tagName === 'H2') heading = el;
                    else {
                        // a section wrapper: its own first h2, not one inside a nested [id]
                        const first = el.querySelector('h2');
                        if (first && !first.id && first.parentElement.closest('[id]') === el) heading = first;
                    }
                    if (!heading || seen.has(el.id) || !el.offsetParent) return;
                    const level = /^sector-/.test(el.id) ? 2 : 1;
                    let label = heading.textContent.replace(/\s+/g, ' ').trim();
                    // "Sector name — Kovida" → "Kovida" inside the nested list
                    if (level === 2 && label.includes('—')) label = label.split('—').slice(1).join('—').trim() || label;
                    if (!label) return;
                    seen.add(el.id);
                    items.push({ id: el.id, label, level });
                });
                this.items = items;
            },
            open() {
                this.scan();
                const nav = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--cg-nav-h')) || 64;
                let current = null;
                this.items.forEach(i => {
                    const el = document.getElementById(i.id);
                    if (el && el.getBoundingClientRect().top - nav - 40 <= 0) current = i.id;
                });
                this.current = current;
                this.is_open = true;
                document.body.classList.add('cg-sheet-open');
                this.$nextTick(() => this.$refs.close_btn && this.$refs.close_btn.focus());
            },
            close() {
                this.is_open = false;
                document.body.classList.remove('cg-sheet-open');
            },
            go(item) {
                this.close();
                const el = document.getElementById(item.id);
                if (!el) return;
                const nav = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--cg-nav-h')) || 64;
                const reduce = window.cgReducedMotion();
                window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - nav - 12, behavior: reduce ? 'auto' : 'smooth' });
            },
        },
    };
</script>

<style scoped>
    .cg-onpage { display: none; }
    @media (max-width: 767.98px) { .cg-onpage { display: block; } }

    .cg-onpage__pill {
        position: fixed;
        left: 16px;
        bottom: calc(16px + env(safe-area-inset-bottom, 0px));
        z-index: 1030;
        display: inline-flex;
        align-items: center;
        gap: .45rem;
        padding: .6rem 1rem;
        border: 0;
        border-radius: 2rem;
        background: var(--cg-ink);
        color: #fff;
        font-size: .88rem;
        font-weight: 800;
        box-shadow: 0 6px 18px rgba(0, 0, 0, .25);
    }
    .cg-onpage__pill .fa { color: var(--cg-hold); }
    .cg-onpage__pill:focus-visible { outline: 3px solid var(--cg-hold); outline-offset: 2px; }

    .cg-onpage__backdrop {
        position: fixed;
        inset: 0;
        z-index: 1055;
        display: flex;
        align-items: flex-end;
        background: rgba(15, 20, 25, .45);
    }
    .cg-onpage__sheet {
        width: 100%;
        max-height: 75vh;
        overflow-y: auto;
        padding: .5rem 1rem calc(1rem + env(safe-area-inset-bottom, 0px));
        border-radius: 1.1rem 1.1rem 0 0;
        background: #fff;
        box-shadow: 0 -8px 30px rgba(0, 0, 0, .2);
    }
    .cg-onpage__grip { width: 2.6rem; height: .3rem; margin: .2rem auto .6rem; border-radius: 1rem; background: var(--cg-line); }
    .cg-onpage__head { display: flex; align-items: center; justify-content: space-between; margin-bottom: .4rem; font-size: 1.05rem; }
    .cg-onpage__close { border: 0; background: var(--cg-chalk); width: 2.2rem; height: 2.2rem; border-radius: 50%; color: var(--cg-ink-2); }
    .cg-onpage__list { list-style: none; margin: 0; padding: 0; }
    .cg-onpage__list li a {
        display: block;
        padding: .65rem .75rem;
        border-radius: var(--cg-r);
        color: var(--cg-ink);
        font-weight: 700;
        text-decoration: none;
    }
    .cg-onpage__list li.is-level-2 a { padding-left: 1.75rem; font-weight: 600; color: var(--cg-ink-2); font-size: .93rem; }
    .cg-onpage__list li a:active, .cg-onpage__list li a:hover { background: var(--cg-chalk); }
    .cg-onpage__list li.is-current a { background: var(--cg-accent-soft); color: var(--cg-accent-dark); }

    .cg-sheet-enter-active, .cg-sheet-leave-active { transition: opacity .2s ease; }
    .cg-sheet-enter-active .cg-onpage__sheet, .cg-sheet-leave-active .cg-onpage__sheet { transition: transform .25s var(--cg-ease, ease); }
    .cg-sheet-enter-from, .cg-sheet-leave-to { opacity: 0; }
    .cg-sheet-enter-from .cg-onpage__sheet, .cg-sheet-leave-to .cg-onpage__sheet { transform: translateY(100%); }
    @media (prefers-reduced-motion: reduce) {
        .cg-sheet-enter-active, .cg-sheet-leave-active, .cg-sheet-enter-active .cg-onpage__sheet, .cg-sheet-leave-active .cg-onpage__sheet { transition: none; }
    }
</style>
