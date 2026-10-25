<template>
    <!-- Phones / tablets (< 1200px, where the right menu and its side scene
         are hidden): the same climbing scene, climbing a small "wall" card
         with the article's numbers and a jump button. Hidden when the navbar
         animation switch is off. The bolter variant is the static SVG image,
         as on desktop. -->
    <section
        v-if="show"
        class="cg-mscene d-xl-none"
        :style="{ paddingLeft: S + 'px', paddingTop: top + 'px', paddingBottom: bottom + 'px' }"
        aria-hidden="false"
    >
        <div ref="wall" class="cg-mscene__wall" :class="'is-' + variant" :style="{ minHeight: wall_height + 'px' }">
            <img v-if="variant === 'bolter'" class="cg-card-bolter" src="/images/scenes/bolter-solo.svg" alt="" aria-hidden="true" loading="lazy" decoding="async" />
            <menu-side-scene v-else-if="size.w" :variant="variant" :width="size.w" :height="size.h" />

            <div class="cg-mscene__text">
                <strong>{{ title }}</strong>
                <span v-for="line in lines" :key="line" class="cg-mscene__line">{{ line }}</span>
                <a v-if="target" :href="'#' + target" class="btn btn-sm btn-hold mt-2" @click.prevent="jump">
                    {{ button || $t('guide.mobile_scene.jump') }} <i class="fa fa-arrow-down" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>
</template>

<script>
    import { defineAsyncComponent } from 'vue'
    import { motion } from '../../../services/motion.js'

    const MenuSideScene = defineAsyncComponent(() => import('./navbars/MenuSideSceneComponent.vue'))

    // room the scene needs around the wall (same numbers as MenuSideSceneComponent)
    const S = 130

    export default {
        name: 'MobileScene',
        components: { MenuSideScene },
        props: {
            // lead | ice | indoor | safety | slackline | summit | bolter
            variant: { type: String, default: null },
            title: { type: String, default: '' },
            lines: { type: Array, default: () => [] },
            // element id the button scrolls to
            target: { type: String, default: null },
            button: { type: String, default: '' },
        },
        data() {
            return { motion, S, size: { w: 0, h: 0 }, narrow: window.innerWidth < 1200 };
        },
        computed: {
            show() { return !!this.variant && this.narrow && this.motion.enabled; },
            top() { return this.variant === 'summit' ? 168 : (this.variant === 'bolter' ? 8 : 16); },
            bottom() { return this.variant === 'slackline' ? 132 : 14; },
            // climbing scenes need height for three quickdraws and the
            // fall / clip moves; the others look right on a short card
            wall_height() {
                return { lead: 310, ice: 310, indoor: 310, safety: 310, summit: 250, slackline: 200, bolter: 240 }[this.variant] || 240;
            },
        },
        watch: {
            show(v) { if (v) this.$nextTick(this.observe); },
        },
        mounted() {
            this.onResize = () => { this.narrow = window.innerWidth < 1200; };
            window.addEventListener('resize', this.onResize, { passive: true });
            this.observe();
        },
        beforeUnmount() {
            window.removeEventListener('resize', this.onResize);
            if (this.ro) this.ro.disconnect();
        },
        methods: {
            // the scene is drawn for the wall's real size
            observe() {
                const wall = this.$refs.wall;
                if (!wall) return;
                if (this.ro) this.ro.disconnect();
                const measure = () => {
                    const r = wall.getBoundingClientRect();
                    this.size = { w: Math.round(r.width), h: Math.round(r.height) };
                };
                if (window.ResizeObserver) {
                    this.ro = new ResizeObserver(measure);
                    this.ro.observe(wall);
                }
                measure();
            },
            jump() {
                const el = document.getElementById(this.target);
                if (!el) return;
                const nav = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--cg-nav-h')) || 64;
                window.scrollTo({ top: el.getBoundingClientRect().top + window.scrollY - nav - 12, behavior: window.cgReducedMotion() ? 'auto' : 'smooth' });
            },
        },
    };
</script>

<style scoped>
    .cg-mscene { position: relative; margin: 1rem 0 1.5rem; overflow: hidden; }
    .cg-mscene__wall {
        position: relative;
        min-height: 240px;
        display: flex;
        align-items: flex-end;
        padding: 1rem;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        background: #fff;
        box-shadow: var(--cg-shadow-sm);
    }
    /* the card is the "rock": tint it like the scene's terrain */
    .cg-mscene__wall.is-ice { background: linear-gradient(180deg, #f2fafd, #fff); }
    .cg-mscene__wall.is-indoor { background: linear-gradient(180deg, #eef4fb, #fff); }
    .cg-mscene__wall.is-summit { background: linear-gradient(180deg, #f4f1ea, #fff); }
    .cg-mscene__wall { min-width: 0; overflow-wrap: anywhere; }
    .cg-mscene__text { position: relative; z-index: 3; display: flex; flex-direction: column; align-items: flex-start; gap: .15rem; min-width: 0; width: 100%; }
    .cg-mscene__text .btn { max-width: 100%; white-space: normal; text-align: left; }
    .cg-mscene__text strong { font-family: var(--cg-font-display); font-size: 1.25rem; line-height: 1.15; color: var(--cg-ink); overflow-wrap: anywhere; }
    .cg-mscene__line { color: var(--cg-stone); font-weight: 700; font-size: .88rem; }
    /* the bolter image hangs on the wall's left edge, like on the desktop card */
    .cg-mscene :deep(.cg-card-bolter) { top: 0; }
</style>
