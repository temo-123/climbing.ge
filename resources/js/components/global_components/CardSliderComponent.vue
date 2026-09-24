<template>
    <div
        v-if="items.length > 0"
        class="card_slider"
        :class="[
            'card_slider--arrows-' + (isMobile ? (arrows === 'none' ? 'none' : 'bottom') : arrows),
            { 'card_slider--scrollable': hasOverflow },
        ]"
        :style="{ '--cs-per-view': perView, '--cs-gap': gap + 'px', '--cs-arrows-top': arrowsTop }"
        @mouseenter="pause"
        @mouseleave="resume"
        @focusin="pause"
        @focusout="resume"
    >
        <button
            v-if="showSideArrows"
            v-show="hasOverflow && (loop || !atStart)"
            type="button"
            class="card_slider_arrow card_slider_arrow--prev"
            :aria-label="$t('common.previous')"
            @click="prev"
        >
            <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </button>

        <!-- native scroll + scroll-snap: finger swipe, trackpad and keyboard
             scrolling all work without hijacking the page's vertical scroll -->
        <div
            class="card_slider_track"
            ref="track"
            @scroll.passive="onScroll"
            @touchstart.passive="pauseForTouch"
        >
            <div
                v-for="(item, index) in items"
                :key="keyFor(item, index)"
                class="card_slider_slide"
                :class="slideClass"
            >
                <slot :item="item" :index="index"></slot>
            </div>
        </div>

        <button
            v-if="showSideArrows"
            v-show="hasOverflow && (loop || !atEnd)"
            type="button"
            class="card_slider_arrow card_slider_arrow--next"
            :aria-label="$t('common.next')"
            @click="next"
        >
            <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </button>

        <!-- phones: a card fills the whole width, so side arrows would always
             cover it — put them in a small bar under the cards instead -->
        <div class="card_slider_bar" v-if="isMobile && arrows !== 'none' && hasOverflow">
            <button
                type="button"
                class="card_slider_bar_btn"
                :aria-label="$t('common.previous')"
                :disabled="!loop && atStart"
                @click="prev"
            >
                <i class="fa fa-chevron-left" aria-hidden="true"></i>
            </button>
            <span class="card_slider_counter">{{ position + 1 }} / {{ pageCount }}</span>
            <button
                type="button"
                class="card_slider_bar_btn"
                :aria-label="$t('common.next')"
                :disabled="!loop && atEnd"
                @click="next"
            >
                <i class="fa fa-chevron-right" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</template>

<script>
    /*
     * Shared horizontal card slider.
     *
     *   <card-slider :items="services" item-key="id" :desktop="3" :mobile="1" :autoplay="5000">
     *       <template #default="{ item }">
     *           <ServiceItem :service_data="item" />
     *       </template>
     *   </card-slider>
     *
     * Card markup/classes are entirely the caller's — the slider only lays out
     * the slides, moves one card per arrow click, and handles autoplay.
     */
    export default {
        name: 'CardSlider',
        props: {
            items: {
                type: Array,
                default: () => [],
            },
            // key for each slide: property name, dotted path ("global_data.id") or function(item, index)
            itemKey: {
                type: [String, Function],
                default: 'id',
            },

            // cards visible at once
            desktop: { type: Number, default: 3 },
            tablet:  { type: Number, default: null },   // optional middle step, falls back to desktop
            mobile:  { type: Number, default: 1 },
            mobileBreakpoint: { type: Number, default: 768 },  // width < this => mobile
            tabletBreakpoint: { type: Number, default: 992 },  // width < this => tablet (only if `tablet` set)

            gap: { type: Number, default: 16 },       // px between cards

            autoplay: { type: Number, default: 0 },   // ms between auto steps, 0 = off
            loop: { type: Boolean, default: false },  // arrows wrap around at the ends (autoplay always wraps)

            // desktop: 'outside' = beside the cards, 'inside' = over the cards, 'none'.
            // Phones always get a prev / counter / next bar under the cards (unless 'none').
            arrows: {
                type: String,
                default: 'outside',
                validator: v => ['outside', 'inside', 'none'].includes(v),
            },
            arrowsTop: { type: String, default: '50%' },  // desktop side arrows' vertical position, e.g. '103px' to centre on a card photo

            slideClass: { type: [String, Array, Object], default: '' },
        },
        data() {
            return {
                viewportWidth: window.innerWidth,
                atStart: true,
                atEnd: false,
                hasOverflow: false,
                position: 0,

                autoplayTimer: null,
                paused: false,
                touchResumeTimer: null,
                resizeFrame: null,
            }
        },
        computed: {
            isMobile() {
                return this.viewportWidth < this.mobileBreakpoint
            },
            showSideArrows() {
                return this.arrows !== 'none' && !this.isMobile
            },
            // number of distinct scroll positions ("2 / 5" counter)
            pageCount() {
                return Math.max(1, this.items.length - this.perView + 1)
            },
            perView() {
                if (this.isMobile) return this.mobile
                if (this.tablet && this.viewportWidth < this.tabletBreakpoint) return this.tablet
                return this.desktop
            },
        },
        watch: {
            items() {
                this.$nextTick(() => {
                    if (this.$refs.track) this.$refs.track.scrollLeft = 0
                    this.onScroll()
                    this.startAutoplay()
                })
            },
            perView() {
                this.$nextTick(this.onScroll)
            },
        },
        mounted() {
            window.addEventListener('resize', this.onResize)
            this.$nextTick(() => {
                this.onScroll()
                this.startAutoplay()
            })
        },
        beforeUnmount() {
            window.removeEventListener('resize', this.onResize)
            this.stopAutoplay()
            clearTimeout(this.touchResumeTimer)
            cancelAnimationFrame(this.resizeFrame)
        },
        methods: {
            keyFor(item, index) {
                if (typeof this.itemKey === 'function') return this.itemKey(item, index)
                const value = this.itemKey.split('.').reduce((obj, part) => (obj == null ? obj : obj[part]), item)
                return value ?? index
            },

            // one card width + gap
            stepSize() {
                const slide = this.$refs.track && this.$refs.track.firstElementChild
                return slide ? slide.offsetWidth + this.gap : 0
            },
            scrollBehavior() {
                return window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'
            },

            next() {
                const track = this.$refs.track
                if (!track) return
                if (this.atEnd) {
                    if (this.loop) track.scrollTo({ left: 0, behavior: this.scrollBehavior() })
                    return
                }
                track.scrollBy({ left: this.stepSize(), behavior: this.scrollBehavior() })
            },
            prev() {
                const track = this.$refs.track
                if (!track) return
                if (this.atStart) {
                    if (this.loop) track.scrollTo({ left: track.scrollWidth, behavior: this.scrollBehavior() })
                    return
                }
                track.scrollBy({ left: -this.stepSize(), behavior: this.scrollBehavior() })
            },

            onScroll() {
                const track = this.$refs.track
                if (!track) return
                const maxScroll = track.scrollWidth - track.clientWidth
                this.hasOverflow = maxScroll > 2
                this.atStart = track.scrollLeft <= 2
                this.atEnd = track.scrollLeft >= maxScroll - 2

                const step = this.stepSize()
                const position = this.atEnd ? this.pageCount - 1 : (step ? Math.round(track.scrollLeft / step) : 0)
                this.position = Math.min(Math.max(position, 0), this.pageCount - 1)
            },
            onResize() {
                cancelAnimationFrame(this.resizeFrame)
                this.resizeFrame = requestAnimationFrame(() => {
                    this.viewportWidth = window.innerWidth
                    this.onScroll()
                })
            },

            // autoplay always wraps back to the first card at the end
            startAutoplay() {
                this.stopAutoplay()
                if (!this.autoplay || this.items.length < 2) return
                if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return

                this.autoplayTimer = setInterval(() => {
                    if (this.paused || document.hidden || !this.hasOverflow) return
                    const track = this.$refs.track
                    if (!track) return
                    if (this.atEnd) track.scrollTo({ left: 0, behavior: this.scrollBehavior() })
                    else track.scrollBy({ left: this.stepSize(), behavior: this.scrollBehavior() })
                }, this.autoplay)
            },
            stopAutoplay() {
                clearInterval(this.autoplayTimer)
                this.autoplayTimer = null
            },
            pause() {
                this.paused = true
            },
            resume() {
                this.paused = false
            },
            // touch devices get no mouseleave — resume a while after the last touch
            pauseForTouch() {
                this.paused = true
                clearTimeout(this.touchResumeTimer)
                this.touchResumeTimer = setTimeout(() => (this.paused = false), 8000)
            },
        },
    }
</script>

<style scoped>
    .card_slider {
        position: relative;
    }
    .card_slider--arrows-outside.card_slider--scrollable {
        padding: 0 56px;
    }

    .card_slider_track {
        display: flex;
        gap: var(--cs-gap);
        overflow-x: auto;
        overscroll-behavior-x: contain;
        scroll-snap-type: x mandatory;
        scrollbar-width: none;
        -webkit-overflow-scrolling: touch;
        /* room for card shadows / hover lift, which overflow would otherwise clip */
        padding: 8px 4px 14px;
        scroll-padding: 0 4px;
    }
    .card_slider_track::-webkit-scrollbar {
        display: none;
    }

    .card_slider_slide {
        flex: 0 0 calc((100% - (var(--cs-per-view) - 1) * var(--cs-gap)) / var(--cs-per-view));
        min-width: 0;
        scroll-snap-align: start;
    }

    .card_slider_arrow {
        position: absolute;
        top: var(--cs-arrows-top);
        z-index: 5;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        padding: 0;
        border: none;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.96);
        color: #7c7cfd;
        font-size: 1.05rem;
        cursor: pointer;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.18);
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        -webkit-tap-highlight-color: transparent;
    }
    .card_slider_arrow:active {
        transform: translateY(-50%) scale(0.92);
    }
    .card_slider_arrow:focus-visible {
        outline: 2px solid #7c7cfd;
        outline-offset: 2px;
    }
    /* hover only on real pointers — on phones :hover sticks after a tap */
    @media (hover: hover) {
        .card_slider_arrow:hover {
            background: #7c7cfd;
            color: #fff;
            box-shadow: 0 4px 16px rgba(124, 124, 253, 0.4);
        }
    }

    .card_slider--arrows-outside .card_slider_arrow--prev { left: 0; }
    .card_slider--arrows-outside .card_slider_arrow--next { right: 0; }
    .card_slider--arrows-inside .card_slider_arrow--prev { left: 10px; }
    .card_slider--arrows-inside .card_slider_arrow--next { right: 10px; }

    /* phones: prev / counter / next bar under the cards */
    .card_slider_bar {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;
        margin-top: 2px;
    }
    .card_slider_bar_btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        padding: 0;
        border: 1px solid #e0e0ea;
        border-radius: 50%;
        background: #fff;
        color: #7c7cfd;
        font-size: 0.85rem;
        cursor: pointer;
        -webkit-tap-highlight-color: transparent;
        transition: opacity 0.2s;
    }
    .card_slider_bar_btn:active:not(:disabled) {
        background: #7c7cfd;
        border-color: #7c7cfd;
        color: #fff;
    }
    .card_slider_bar_btn:disabled {
        opacity: 0.35;
        cursor: default;
    }
    .card_slider_counter {
        min-width: 44px;
        text-align: center;
        color: #888;
        font-size: 0.85rem;
        font-weight: 600;
    }
</style>
