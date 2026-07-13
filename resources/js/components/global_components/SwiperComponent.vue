<template>
  <div class="swiper" @mouseenter="pauseAutoSlide" @mouseleave="resumeAutoSlide">
    <div
      class="swiper_track"
      v-if="slides.length > 0"
      :style="'transform: translateX(-' + (100 * current_slider_index) + '%)'"
      @touchstart="handleTouchStart"
      @touchend="handleTouchEnd"
    >
      <div
        v-for="(slide, index) in slides"
        :key="slide.id"
        class="head_slider"
        :class="{ active: index === current_slider_index }"
        :aria-label="$t('global.slider.slide_of', { current: index + 1, total: slides.length })"
      >
        <site-img :src="'/public'+image_path_prop+slide.image" :alt="slide.title" :img_class="'slider_img'" />

        <div class="slide_overlay"></div>

        <div class="slide_content" :class="'pos-' + (slide.text_position || 'center')">
          <div class="slide_title" v-if="slide.title">{{ slide.title }}</div>
          <div class="slide_description" v-if="slide.text">{{ slide.text }}</div>
          <a v-if="slide.link" :href="slide.link" class="slide_read_more_btn">{{ $t('shop.tour.more') }}</a>
        </div>
      </div>
    </div>

    <button class="nav_btn prev_btn" @click="prev_slide" :aria-label="$t('global.slider.previous_slide')" v-if="slides.length > 1">
      <i class="fa fa-chevron-left"></i>
    </button>

    <button class="nav_btn next_btn" @click="next_slide" :aria-label="$t('global.slider.next_slide')" v-if="slides.length > 1">
      <i class="fa fa-chevron-right"></i>
    </button>

    <div class="dots" v-if="slides.length > 1">
      <span
        v-for="(slide, index) in slides"
        :key="'dot-' + index"
        :class="['dot', { active: index === current_slider_index }]"
        @click="goToSlide(index)"
        :aria-label="$t('global.slider.go_to_slide', { n: index + 1 })"
      ></span>
    </div>
  </div>
</template>

<script>
  export default {
    data() {
      return {
        slides: [],
        slide_count: 0,
        current_slider_index: 0,
        touchStartX: 0,
        touchEndX: 0,
        autoSlideInterval: null,
      };
    },
    props: ['image_path_prop', 'category_prop'],
    mounted() {
      this.get_slider_images();
    },
    beforeUnmount() {
      this.pauseAutoSlide();
    },
    methods: {
      next_slide() {
        this.current_slider_index = (this.current_slider_index + 1) % this.slide_count;
      },
      prev_slide() {
        this.current_slider_index = (this.current_slider_index - 1 + this.slide_count) % this.slide_count;
      },
      get_slider_images() {
        axios
          .get('get_head_slider/get_slides/' + this.category_prop + '/')
          .then((response) => {
            this.slides = response.data;
            this.slide_count = this.slides.length;
            if (this.slide_count > 1) {
              this.autoSlideInterval = setInterval(this.next_slide, 10000);
            }
          })
          .catch(() => {});
      },
      pauseAutoSlide() {
        if (this.autoSlideInterval) {
          clearInterval(this.autoSlideInterval);
          this.autoSlideInterval = null;
        }
      },
      resumeAutoSlide() {
        if (this.slide_count > 1 && !this.autoSlideInterval) {
          this.autoSlideInterval = setInterval(this.next_slide, 10000);
        }
      },
      handleTouchStart(e) {
        this.touchStartX = e.changedTouches[0].screenX;
      },
      handleTouchEnd(e) {
        this.touchEndX = e.changedTouches[0].screenX;
        const diff = this.touchStartX - this.touchEndX;
        if (Math.abs(diff) > 40) {
          diff > 0 ? this.next_slide() : this.prev_slide();
        }
      },
      goToSlide(index) {
        this.current_slider_index = index;
      },
    },
  };
</script>

<style scoped>
.swiper {
  position: relative;
  width: 100%;
  overflow: hidden;
  background: #111;
  aspect-ratio: 16 / 9;
  max-height: 90vh;
}

.swiper_track {
  display: flex;
  width: 100%;
  height: 100%;
  transition: transform 0.6s cubic-bezier(0.25, 0.46, 0.45, 0.94);
  will-change: transform;
}

.head_slider {
  flex: 0 0 100%;
  width: 100%;
  height: 100%;
  position: relative;
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
  transform: scale(1);
}

.head_slider.active :deep(.slider_img) {
  animation: slide_ken_burns 10s ease-in-out forwards;
}

@keyframes slide_ken_burns {
  from { transform: scale(1); }
  to { transform: scale(1.12); }
}

.slide_overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(
    to bottom,
    rgba(0,0,0,0.05) 0%,
    rgba(0,0,0,0.15) 40%,
    rgba(0,0,0,0.65) 100%
  );
}

.slide_content {
  position: absolute;
  padding: 14px 18px;
  background: rgba(0, 0, 0, 0.48);
  backdrop-filter: blur(6px);
  -webkit-backdrop-filter: blur(6px);
  border-radius: 8px;
  max-width: 58%;
}

.pos-center   { bottom: 12%; left: 50%; transform: translateX(-50%); text-align: center; }
.pos-left-top    { top: 8%; left: 5%; text-align: left; }
.pos-right-top   { top: 8%; right: 5%; text-align: right; }
.pos-left-bottom  { bottom: 8%; left: 5%; text-align: left; }
.pos-right-bottom { bottom: 8%; right: 5%; text-align: right; }

.slide_title {
  color: #fff;
  font-size: clamp(1.6rem, 4vw, 3rem);
  font-weight: 700;
  letter-spacing: 0.5px;
  text-shadow: 0 2px 12px rgba(0,0,0,0.5);
  margin-bottom: 0.5rem;
  line-height: 1.2;
}

.slide_description {
  color: rgba(255,255,255,0.9);
  font-size: clamp(1rem, 2vw, 1.5rem);
  text-shadow: 0 1px 8px rgba(0,0,0,0.5);
  margin-bottom: 1.2rem;
  line-height: 1.4;
}

.slide_read_more_btn {
  display: inline-block;
  padding: 10px 28px;
  border: 2px solid #fff;
  color: #fff;
  font-size: 1rem;
  font-weight: 600;
  letter-spacing: 1px;
  text-decoration: none;
  text-transform: uppercase;
  border-radius: 2px;
  transition: background 0.25s, color 0.25s;
  backdrop-filter: blur(4px);
  background: rgba(255,255,255,0.1);
}

.slide_read_more_btn:hover {
  background: #fff;
  color: #222;
  text-decoration: none;
}

/* Nav arrows */
.nav_btn {
  position: absolute;
  top: 50%;
  transform: translateY(-50%);
  width: 46px;
  height: 46px;
  border-radius: 50%;
  background: rgba(255,255,255,0.15);
  border: 2px solid rgba(255,255,255,0.5);
  color: #fff;
  font-size: 1.1rem;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background 0.2s, border-color 0.2s;
  backdrop-filter: blur(4px);
  z-index: 10;
}

.nav_btn:hover {
  background: rgba(255,255,255,0.35);
  border-color: #fff;
}

.prev_btn { left: 20px; }
.next_btn { right: 20px; }

/* Dots */
.dots {
  position: absolute;
  bottom: 18px;
  left: 50%;
  transform: translateX(-50%);
  display: flex;
  gap: 8px;
  z-index: 10;
}

.dot {
  width: 8px;
  height: 8px;
  border-radius: 50%;
  background: rgba(255,255,255,0.45);
  cursor: pointer;
  transition: background 0.3s, transform 0.3s;
}

.dot.active {
  background: #fff;
  transform: scale(1.4);
}

.dot:hover {
  background: rgba(255,255,255,0.8);
}

@media (max-width: 768px) {
  .swiper {
    width: 100%;
    aspect-ratio: 3 / 4;
    max-height: none;
  }

  .nav_btn {
    width: 36px;
    height: 36px;
    font-size: 0.9rem;
  }

  .prev_btn { left: 10px; }
  .next_btn { right: 10px; }

  .slide_content {
    max-width: 80%;
  }
}
</style>
