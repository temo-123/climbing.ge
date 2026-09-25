<template>
  <div
    ref="host"
    class="swiper_host"
    :class="{ is_full: full_width }"
    :style="full_width ? { '--page-w': page_width, marginTop: -top_gap + 'px' } : null"
  >
    <!--
      full_width = true  → big: full-bleed across the whole viewport, flush under the fixed navbar
      full_width = false → small: fits inside its parent (.container / admin preview)
    -->
    <div
      class="swiper"
      :class="{ is_paused: is_paused }"
      :style="{ '--slide-ms': AUTO_SLIDE_MS + 'ms' }"
      @pointerenter="on_pointer_enter"
      @pointerleave="on_pointer_leave"
      @focusin="on_focus_in"
      @focusout="on_focus_out"
      @keydown.left="prev_slide"
      @keydown.right="next_slide"
      tabindex="0"
      role="region"
      aria-roledescription="carousel"
    >
      <div
        class="swiper_track"
        :class="{ dragging: is_dragging }"
        v-if="slides.length > 0"
        :style="{ transform: 'translateX(calc(' + (-100 * current_slider_index) + '% + ' + drag_offset + 'px))' }"
        @touchstart="handleTouchStart"
        @touchmove="handleTouchMove"
        @touchend="handleTouchEnd"
        @touchcancel="handleTouchEnd"
      >
        <div
          v-for="(slide, index) in slides"
          :key="slide.id"
          class="head_slider"
          :class="{ active: index === current_slider_index }"
          :aria-hidden="index !== current_slider_index"
          :aria-label="$t('global.slider.slide_of', { current: index + 1, total: slides.length })"
        >
          <site-img :src="'/public'+image_path_prop+slide.image" :alt="slide.title" :img_class="'slider_img'" />

          <div class="slide_overlay" :class="'shade-' + (slide.text_position || 'center')"></div>

          <div class="slide_content" :class="'pos-' + (slide.text_position || 'center')" v-if="slide.title || slide.text || slide.link">
            <div class="slide_title" v-if="slide.title">{{ slide.title }}</div>
            <div class="slide_description" v-if="slide.text">{{ slide.text }}</div>
            <a
              v-if="slide.link"
              :href="slide.link"
              class="slide_read_more_btn"
              :tabindex="index === current_slider_index ? 0 : -1"
            >
              {{ $t('shop.tour.more') }} <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>

      <template v-if="slides.length > 1">
        <button class="nav_btn prev_btn" @click="prev_slide" :aria-label="$t('global.slider.previous_slide')">
          <i class="fa fa-chevron-left" aria-hidden="true"></i>
        </button>

        <button class="nav_btn next_btn" @click="next_slide" :aria-label="$t('global.slider.next_slide')">
          <i class="fa fa-chevron-right" aria-hidden="true"></i>
        </button>

        <!-- 01 / 05 -->
        <div class="slide_counter" aria-hidden="true">
          <span class="counter_current">{{ pad(current_slider_index + 1) }}</span>
          <span class="counter_sep"></span>
          <span class="counter_total">{{ pad(slide_count) }}</span>
        </div>

        <!-- Progress lines: the active one fills up until the next slide -->
        <div class="progress_bars">
          <button
            v-for="(slide, index) in slides"
            :key="'bar-' + slide.id"
            type="button"
            class="hs_bar"
            :class="{ active: index === current_slider_index, seen: index < current_slider_index }"
            @click="goToSlide(index)"
            :aria-label="$t('global.slider.go_to_slide', { n: index + 1 })"
            :aria-current="index === current_slider_index ? 'true' : null"
          >
            <span class="hs_bar_track">
              <span class="hs_bar_fill" :key="'fill-' + index + '-' + timer_cycle"></span>
            </span>
          </button>
        </div>
      </template>
    </div>
  </div>
</template>

<script>
  const AUTO_SLIDE_MS = 8000;

  export default {
    props: {
      image_path_prop: { type: String, default: '' },
      category_prop: { type: String, default: '' },
      // true = big full-screen-width slider, false = small slider inside its parent
      full_width: { type: Boolean, default: false },
      // Optional ready-made slides (admin preview) — when given, nothing is fetched
      slides_prop: { type: Array, default: null },
    },
    data() {
      return {
        AUTO_SLIDE_MS,
        slides: [],
        slide_count: 0,
        current_slider_index: 0,
        autoSlideTimer: null,
        timer_cycle: 0,     // bumped on every timer restart so the progress-line animation restarts in sync
        is_paused: false,
        is_hovered: false,
        page_width: '100vw',
        top_gap: 0,

        touch_start_x: 0,
        touch_start_y: 0,
        touch_axis: null,   // 'x' | 'y' | null — decided on the first move so vertical page scroll never gets blocked
        drag_offset: 0,
        is_dragging: false,
      };
    },
    watch: {
      slides_prop: {
        handler(val) {
          if (val) this.set_slides(val);
        },
        deep: true,
      },
      full_width() {
        this.$nextTick(this.update_page_width);
      },
      // Any slide change (auto, arrows, bars, swipe, keys) gives the new slide its full time
      current_slider_index() {
        this.restart_timer();
      },
    },
    mounted() {
      if (this.slides_prop) {
        this.set_slides(this.slides_prop);
      } else {
        this.get_slider_images();
      }
      this.update_page_width();
      window.addEventListener('resize', this.update_page_width);
    },
    beforeUnmount() {
      this.clear_timer();
      window.removeEventListener('resize', this.update_page_width);
    },
    methods: {
      pad(n) {
        return String(n).padStart(2, '0');
      },
      // Viewport width without the vertical scrollbar — plain 100vw includes it and causes a horizontal scroll
      update_page_width() {
        if (!this.full_width) {
          this.top_gap = 0;
          return;
        }
        this.page_width = document.documentElement.clientWidth + 'px';
        this.update_top_gap();
      },
      // Pull the slider up flush under the fixed navbar, closing the gap left by .top_menu_margin
      update_top_gap() {
        const host = this.$refs.host;
        if (!this.full_width || !host) return;
        const nav = document.querySelector('.navbar-fixed-top');
        if (!nav) return;
        const natural_top = host.getBoundingClientRect().top + window.scrollY + this.top_gap;
        this.top_gap = Math.max(0, Math.round(natural_top - nav.offsetHeight));
      },
      set_slides(slides) {
        this.slides = slides;
        this.slide_count = slides.length;
        if (this.current_slider_index >= this.slide_count) this.current_slider_index = 0;
        this.restart_timer();
        this.$nextTick(this.update_top_gap);
      },
      get_slider_images() {
        axios
          .get('get_head_slider/get_slides/' + this.category_prop + '/')
          .then((response) => this.set_slides(response.data))
          .catch(() => {});
      },
      next_slide() {
        if (!this.slide_count) return;
        this.current_slider_index = (this.current_slider_index + 1) % this.slide_count;
      },
      prev_slide() {
        if (!this.slide_count) return;
        this.current_slider_index = (this.current_slider_index - 1 + this.slide_count) % this.slide_count;
      },
      goToSlide(index) {
        this.current_slider_index = index;
      },

      // --- Autoplay: one timeout per slide, so the progress line always matches ---
      clear_timer() {
        if (this.autoSlideTimer) {
          clearTimeout(this.autoSlideTimer);
          this.autoSlideTimer = null;
        }
      },
      restart_timer() {
        this.clear_timer();
        this.timer_cycle++;
        if (!this.is_paused && this.slide_count > 1) {
          this.autoSlideTimer = setTimeout(this.next_slide, AUTO_SLIDE_MS);
        }
      },
      // Mouse hover pauses; touch is ignored here (browsers fire emulated mouse events after a tap,
      // which would otherwise pause autoplay forever on phones) — touch has its own pause in handleTouchStart
      on_pointer_enter(e) {
        if (e.pointerType !== 'mouse') return;
        this.is_hovered = true;
        this.pauseAutoSlide();
      },
      on_pointer_leave(e) {
        if (e.pointerType !== 'mouse') return;
        this.is_hovered = false;
        this.resumeAutoSlide();
      },
      // Keyboard focus inside the slider pauses it (a tap/click focus doesn't)
      on_focus_in(e) {
        if (e.target.matches && e.target.matches(':focus-visible')) this.pauseAutoSlide();
      },
      on_focus_out(e) {
        const focus_left = !this.$refs.host || !this.$refs.host.contains(e.relatedTarget);
        if (focus_left && !this.is_hovered) this.resumeAutoSlide();
      },
      pauseAutoSlide() {
        this.is_paused = true;
        this.clear_timer();
      },
      resumeAutoSlide() {
        if (!this.is_paused) return;
        this.is_paused = false;
        this.restart_timer();
      },

      // Touch: the track follows the finger, then snaps to the next/prev slide or back
      handleTouchStart(e) {
        const t = e.changedTouches[0];
        this.touch_start_x = t.clientX;
        this.touch_start_y = t.clientY;
        this.touch_axis = null;
        this.drag_offset = 0;
        this.pauseAutoSlide();
      },
      handleTouchMove(e) {
        const t = e.changedTouches[0];
        const dx = t.clientX - this.touch_start_x;
        const dy = t.clientY - this.touch_start_y;

        if (!this.touch_axis) {
          if (Math.abs(dx) < 6 && Math.abs(dy) < 6) return;
          this.touch_axis = Math.abs(dx) > Math.abs(dy) ? 'x' : 'y';
        }
        if (this.touch_axis !== 'x' || this.slide_count < 2) return;

        this.is_dragging = true;
        // Rubber-band resistance past the first / last slide
        const at_edge = (dx > 0 && this.current_slider_index === 0)
          || (dx < 0 && this.current_slider_index === this.slide_count - 1);
        this.drag_offset = at_edge ? dx / 3 : dx;
      },
      handleTouchEnd() {
        if (this.is_dragging) {
          const width = (this.$refs.host && this.$refs.host.offsetWidth) || 1;
          const threshold = Math.min(80, width * 0.18);
          if (this.drag_offset <= -threshold) this.next_slide();
          else if (this.drag_offset >= threshold) this.prev_slide();
        }
        this.is_dragging = false;
        this.drag_offset = 0;
        this.touch_axis = null;
        this.resumeAutoSlide();
      },
    },
  };
</script>

<style scoped>
/* The host is the size container — layout below reacts to the slider's own width
   (via @container), so it's correct inside a narrow admin preview too, not just on real phones. */
.swiper_host {
  --sl-accent: #ffc60b;          /* $accent-yellow — "hero slider highlight" */
  --sl-primary: #279fbb;         /* $primary */
  --sl-primary-hover: #1e8da8;   /* $primary-hover */
  --sl-panel: rgba(12, 22, 28, 0.55);
  --sl-ease: cubic-bezier(0.22, 0.61, 0.36, 1);

  width: 100%;
  container-type: inline-size;
  container-name: head-slider;
}

/* Big mode: break out of the parent .container to the full viewport width */
.swiper_host.is_full {
  width: var(--page-w, 100vw);
  margin-left: calc(50% - var(--page-w, 100vw) / 2);
}

.swiper {
  position: relative;
  width: 100%;
  overflow: hidden;
  background: #0d1417;
  aspect-ratio: 16 / 9;
  max-height: 90vh;
  outline: none;
  touch-action: pan-y;
  -webkit-user-select: none;
  user-select: none;
}

.swiper:focus-visible {
  box-shadow: inset 0 0 0 3px var(--sl-accent);
}

.swiper_track {
  display: flex;
  width: 100%;
  height: 100%;
  transition: transform 0.8s var(--sl-ease);
  will-change: transform;
}

.swiper_track.dragging {
  transition: none;
}

.head_slider {
  flex: 0 0 100%;
  width: 100%;
  height: 100%;
  position: relative;
  overflow: hidden;
}

.head_slider :deep(#image) {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
}

.head_slider :deep(.slider_img) {
  position: absolute;
  inset: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  display: block;
  transform: scale(1.001);
  -webkit-user-drag: none;
  pointer-events: none;
}

/* Gentle, slow zoom — calmer on the eyes than a strong Ken Burns */
.head_slider.active :deep(.slider_img) {
  animation: slide_ken_burns 14s linear forwards;
}

@keyframes slide_ken_burns {
  from { transform: scale(1.001); }
  to { transform: scale(1.06); }
}

/* --- Shading: always a soft bottom fade (for the controls), plus extra shade on the text's side --- */
.slide_overlay {
  position: absolute;
  inset: 0;
  pointer-events: none;
  background:
    linear-gradient(to top, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.15) 28%, rgba(0,0,0,0) 50%),
    linear-gradient(to bottom, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0) 18%);
}

.slide_overlay.shade-left-top,
.slide_overlay.shade-left-bottom {
  background:
    linear-gradient(to right, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0) 55%),
    linear-gradient(to top, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 45%),
    linear-gradient(to bottom, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0) 18%);
}

.slide_overlay.shade-right-top,
.slide_overlay.shade-right-bottom {
  background:
    linear-gradient(to left, rgba(0,0,0,0.45) 0%, rgba(0,0,0,0) 55%),
    linear-gradient(to top, rgba(0,0,0,0.5) 0%, rgba(0,0,0,0) 45%),
    linear-gradient(to bottom, rgba(0,0,0,0.25) 0%, rgba(0,0,0,0) 18%);
}

/* --- Text panel --- */
.slide_content {
  position: absolute;
  z-index: 2;
  padding: 22px 26px 24px;
  background: var(--sl-panel);
  backdrop-filter: blur(10px) saturate(120%);
  -webkit-backdrop-filter: blur(10px) saturate(120%);
  border: 1px solid rgba(255, 255, 255, 0.14);
  border-radius: 14px;
  box-shadow: 0 10px 40px rgba(0, 0, 0, 0.25);
  max-width: min(640px, 52%);
}

.pos-center       { bottom: 14%; left: 50%; transform: translateX(-50%); text-align: center; }
.pos-left-top     { top: 10%; left: 6%; text-align: left; }
.pos-right-top    { top: 10%; right: 6%; text-align: left; }
.pos-left-bottom  { bottom: 14%; left: 6%; text-align: left; }
.pos-right-bottom { bottom: 14%; right: 6%; text-align: left; }

.slide_title {
  color: #fff;
  font-size: clamp(24px, 3.4cqi, 46.4px);
  font-weight: 800;
  letter-spacing: 0.2px;
  line-height: 1.15;
  text-wrap: balance;
  text-shadow: 0 2px 10px rgba(0,0,0,0.35);
}

.slide_description {
  color: rgba(255,255,255,0.92);
  font-size: clamp(16px, 1.5cqi, 20px);
  line-height: 1.55;
  margin-top: 10px;
  text-wrap: pretty;
}

.slide_read_more_btn {
  display: inline-flex;
  align-items: center;
  gap: 10px;
  margin-top: 18px;
  padding: 10px 22px;
  border: 1px solid rgba(255, 255, 255, 0.7);
  border-radius: 4px;
  background: transparent;
  color: #fff;
  font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
  font-size: 13.6px;
  font-weight: 600;
  letter-spacing: 1.5px;
  text-transform: uppercase;
  text-decoration: none;
  transition: background 0.25s, color 0.25s, border-color 0.25s;
}

.slide_read_more_btn .fa {
  transition: transform 0.25s;
}

.slide_read_more_btn:hover,
.slide_read_more_btn:focus-visible {
  background: #fff;
  border-color: #fff;
  color: #111;
  text-decoration: none;
}

.slide_read_more_btn:hover .fa {
  transform: translateX(4px);
}

.slide_read_more_btn:focus-visible {
  outline: 2px solid #fff;
  outline-offset: 3px;
}

/* Text fades up after the slide arrives (staggered) */
.slide_content > * {
  opacity: 0;
  transform: translateY(14px);
}

.head_slider.active .slide_content > * {
  animation: content_in 0.7s var(--sl-ease) forwards;
}

.head_slider.active .slide_content > :nth-child(1) { animation-delay: 0.35s; }
.head_slider.active .slide_content > :nth-child(2) { animation-delay: 0.48s; }
.head_slider.active .slide_content > :nth-child(3) { animation-delay: 0.6s; }

@keyframes content_in {
  to { opacity: 1; transform: translateY(0); }
}

/* --- Arrows: quiet until you hover the slider --- */
.nav_btn {
  position: absolute;
  top: 50%;
  z-index: 10;
  width: 52px;
  height: 52px;
  padding: 0;
  border-radius: 50%;
  border: 1px solid rgba(255,255,255,0.35);
  background: rgba(10, 18, 22, 0.35);
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
  color: #fff;
  font-size: 16px;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transform: translateY(-50%) scale(0.9);
  transition: opacity 0.3s, transform 0.3s, background 0.2s, border-color 0.2s;
}

.swiper:hover .nav_btn,
.swiper:focus-within .nav_btn {
  opacity: 1;
  transform: translateY(-50%) scale(1);
}

.nav_btn:hover {
  background: var(--sl-primary);
  border-color: var(--sl-primary);
}

.nav_btn:focus-visible {
  outline: 3px solid var(--sl-accent);
  outline-offset: 2px;
}

.prev_btn { left: 24px; }
.next_btn { right: 24px; }

/* Touch screens have no hover — keep the arrows visible but subtle */
@media (hover: none) {
  .nav_btn {
    opacity: 0.85;
    transform: translateY(-50%) scale(1);
  }
}

/* --- Counter 01 / 05 --- */
.slide_counter {
  position: absolute;
  left: 32px;
  bottom: 22px;
  z-index: 10;
  display: flex;
  align-items: center;
  gap: 10px;
  color: rgba(255,255,255,0.7);
  font-size: 13.6px;
  font-weight: 600;
  letter-spacing: 1px;
  font-variant-numeric: tabular-nums;
}

.counter_current {
  color: #fff;
  font-size: 21.6px;
  font-weight: 800;
}

.counter_sep {
  width: 26px;
  height: 1px;
  background: rgba(255,255,255,0.55);
}

/* --- Progress lines --- */
.progress_bars {
  position: absolute;
  left: 50%;
  bottom: 18px;
  transform: translateX(-50%);
  z-index: 10;
  display: flex;
  gap: 4px;
}

.hs_bar {
  padding: 12px 0;            /* tall invisible tap area around a thin line */
  border: 0;
  background: none;
  cursor: pointer;
}

.hs_bar:focus-visible {
  outline: 2px solid var(--sl-accent);
  outline-offset: 2px;
  border-radius: 4px;
}

.hs_bar_track {
  display: block;
  position: relative;
  width: 40px;
  height: 3px;
  border-radius: 3px;
  background: rgba(255,255,255,0.35);
  overflow: hidden;
  transition: width 0.4s var(--sl-ease), background 0.2s;
}

.hs_bar:hover .hs_bar_track {
  background: rgba(255,255,255,0.6);
}

.hs_bar.active .hs_bar_track {
  width: 64px;
}

.hs_bar_fill {
  position: absolute;
  inset: 0;
  border-radius: inherit;
  background: #fff;
  transform-origin: left center;
  transform: scaleX(0);
}

.hs_bar.seen .hs_bar_fill {
  transform: scaleX(1);
  background: rgba(255,255,255,0.75);
}

.hs_bar.active .hs_bar_fill {
  background: var(--sl-accent);
  animation: bar_progress var(--slide-ms) linear forwards;
}

/* Hovering / touching holds the slide — show the line full so it's clear nothing is running */
.swiper.is_paused .hs_bar.active .hs_bar_fill {
  animation: none;
  transform: scaleX(1);
}

@keyframes bar_progress {
  from { transform: scaleX(0); }
  to { transform: scaleX(1); }
}

/* Tablet */
@container head-slider (max-width: 991px) {
  .slide_content { max-width: 66%; }
  .slide_counter { display: none; }
}

/* Phone — portrait image, text panel across the bottom, progress lines under it */
/* Phone — photo-first: no boxed panel, text sits directly on a deep bottom fade,
   height capped so the slider never fills more than ~3/4 of the visible screen */
@container head-slider (max-width: 768px) {
  .swiper {
    aspect-ratio: 4 / 5;
    max-height: 75vh;
    max-height: 75svh;
    min-height: 340px;
  }

  .nav_btn { display: none; }

  .slide_overlay,
  .slide_overlay[class*="shade-"] {
    background:
      linear-gradient(to top, rgba(0,0,0,0.82) 0%, rgba(0,0,0,0.55) 26%, rgba(0,0,0,0.15) 52%, rgba(0,0,0,0) 66%),
      linear-gradient(to bottom, rgba(0,0,0,0.3) 0%, rgba(0,0,0,0) 14%);
  }

  .slide_content,
  .pos-center,
  .pos-left-top,
  .pos-right-top,
  .pos-left-bottom,
  .pos-right-bottom {
    top: auto;
    bottom: 46px;
    left: 20px;
    right: 20px;
    max-width: none;
    transform: none;
    text-align: left;
    padding: 0;
    background: none;
    border: 0;
    border-radius: 0;
    box-shadow: none;
    backdrop-filter: none;
    -webkit-backdrop-filter: none;
  }

  .slide_title {
    font-size: clamp(21.6px, 6.8cqi, 30.4px);
    line-height: 1.2;
    text-shadow: 0 2px 12px rgba(0,0,0,0.55);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .slide_description {
    font-size: clamp(14.7px, 3.9cqi, 16.3px);
    line-height: 1.45;
    margin-top: 6px;
    color: rgba(255,255,255,0.88);
    text-shadow: 0 1px 8px rgba(0,0,0,0.6);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
  }

  .slide_read_more_btn {
    margin-top: 14px;
    padding: 9px 16px;
    font-size: 12.5px;
    letter-spacing: 1.2px;
    /* no hover on touch — a faint fill so it still reads as a button */
    background: rgba(255,255,255,0.08);
  }

  .progress_bars {
    left: 20px;
    right: 20px;
    bottom: 8px;
    transform: none;
    justify-content: flex-start;
  }

  .hs_bar {
    flex: 1 1 0;
    max-width: 56px;
  }

  .hs_bar_track,
  .hs_bar.active .hs_bar_track {
    width: 100%;
  }
}

/* Phone turned sideways: a tall 4:5 slide would be taller than the screen — go wide instead */
@media (orientation: landscape) and (max-height: 500px) {
  .swiper {
    aspect-ratio: 16 / 9;
    min-height: 0;
    max-height: 85vh;
    max-height: 85svh;
  }
}

@media (prefers-reduced-motion: reduce) {
  .swiper_track { transition: none; }
  .head_slider.active :deep(.slider_img) { animation: none; }
  .head_slider.active .slide_content > * { animation: none; opacity: 1; transform: none; }
  .hs_bar.active .hs_bar_fill { animation: none; transform: scaleX(1); }
}
</style>
