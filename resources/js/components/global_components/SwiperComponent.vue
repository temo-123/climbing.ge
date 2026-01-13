<template>
  <div class="swiper" @mouseenter="pauseAutoSlide" @mouseleave="resumeAutoSlide">
    <div class="swiper_sizing" v-if="slides.length > 0" :style="'margin-left: ' + (-100 * current_slider_index) + '%; '" @touchstart="handleTouchStart" @touchend="handleTouchEnd">
      <div v-for="(slide, index) in slides" :key="slide.id" class="head_slider" :aria-label="'Slide ' + (index + 1) + ' of ' + slides.length">
        <site-img :src="'/public'+image_path_prop+slide.image" :alt="slide.title" :img_class="'slider_img'" />

        <div class="slide_title text_shadow">{{ slide.title }}</div>
        <div class="slide_description text_shadow">{{ slide.text }}</div>

        <div class="slide_read_more text_shadow" v-if="slide.link">
          <a :href="slide.link">
            Read more
          </a>
        </div>
      </div>
    </div>

    <div class="prev_slide_bottom" @click="prev_slide" aria-label="Previous slide">
      <i class="fa fa-chevron-left" aria-hidden="true"></i>
    </div>

    <div class="next_slide_bottom" @click="next_slide" aria-label="Next slide">
      <i class="fa fa-chevron-right" aria-hidden="true"></i>
    </div>

    <div class="dots" v-if="slides.length > 1">
      <span v-for="(slide, index) in slides" :key="'dot-' + index" :class="['dot', { active: index === current_slider_index }]" @click="goToSlide(index)" :aria-label="'Go to slide ' + (index + 1)"></span>
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
    props: [
        'image_path_prop',
        'category_prop'
    ],
    mounted() {
      this.get_slider_images();
    },
    methods: {
      next_slide() {
        if (this.slide_count > 1) {
          this.current_slider_index++;
          if (this.current_slider_index >= this.slide_count) {
            this.current_slider_index = 0; // Reset index when reaching the end of the slides
          }
        }
      },
      prev_slide() {
        if (this.slide_count > 1) {
          if (this.current_slider_index > 0) {
            this.current_slider_index--;
          } else {
            this.current_slider_index = this.slide_count - 1; // Go to the last slide when reaching the beginning of the slides
          }
        }
      },
      get_slider_images() {
        axios
        .get('/get_head_slider/get_slides/'+this.category_prop+'/')
        .then((response) => {
          this.slides = response.data;
          this.slide_count = this.slides.length;

          if (this.slide_count > 1) {
            this.autoSlideInterval = setInterval(this.next_slide, 10000); // Change slide every 10 seconds
          }
        })
        .catch((error) => {
          // console.log(error);
        });
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
        this.handleSwipe();
      },
      handleSwipe() {
        if (this.touchEndX < this.touchStartX) {
          this.next_slide();
        }
        if (this.touchEndX > this.touchStartX) {
          this.prev_slide();
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
    max-width: 100%;
    overflow: hidden;
    position: relative;
  }

  .swiper_sizing {
    display: flex;
    transition: margin-left 0.5s ease-in-out;
    align-items: center;
    flex-direction: row;
    margin-left: 0%;
    width: 100%;
    height: 500px;
  }
  .slide_title {
      color: #f2f2f2;
      font-size: 3rem;
      padding: 8px 12px;
      position: absolute;
      bottom: 35%;
      width: 100%;
      text-align: center;
  }
  .slide_description {
      color: #f2f2f2;
      font-size: 2rem;
      padding: 8px 12px;
      position: absolute;
      bottom: 25%;
      width: 100%;
      text-align: center;
  }
  .slide_read_more {
      color: #f2f2f2;
      font-size: 1.5rem;
      padding: 8px 12px;
      position: absolute;
      bottom: 10%;
      width: 100%;
      text-align: center;
  } 

  @media (max-width: 1230px) {
    .swiper_sizing {
      height: auto;
    }
    .slide_title {
        color: #f2f2f2;
        font-size: 3rem;
        padding: 8px 12px;
        position: absolute;
        bottom: 28%;
        width: 100%;
        text-align: center;
    }
    .slide_description {
        color: #f2f2f2;
        font-size: 1.5rem;
        padding: 8px 12px;
        position: absolute;
        bottom: 18%;
        width: 100%;
        text-align: center;
    }
    .slide_read_more {
        color: #f2f2f2;
        font-size: 1.5rem;
        padding: 8px 12px;
        position: absolute;
        bottom: 5%;
        width: 100%;
        text-align: center;
    } 
  }

  .prev_slide_bottom {
    left: 1%;
  }

  .next_slide_bottom {
    right: 1%;
  }

  .next_slide_bottom,
  .prev_slide_bottom {
    margin-top: -27.5%;
    font-size: 300%;
    cursor: pointer;
    text-shadow: #d0d0d0 1px 1px 1px;
    position: absolute;
  }

  .head_slider {
    flex-shrink: 0;
    width: 100%;
  }

  .slider_img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .dots {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    display: flex;
    gap: 10px;
  }

  .dot {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.5);
    cursor: pointer;
    transition: background-color 0.3s;
  }

  .dot.active {
    background-color: #fff;
  }

  .dot:hover {
    background-color: rgba(255, 255, 255, 0.8);
  }
</style>
