<template>
  <div class="swiper">
    <div class="swiper_sizing" :style="'margin-left: -' + (100*current_slider_index) + '%; '">
      <div v-for="(slide, index) in images_prop" :key="slide.id" class="head_slider" >
        <site-img :src="'/public/images/gallery_img/'+slide.image" :alt="slide.title" :img_class="'slider_img'" />
        <div class="slide_title text_shadow">{{ slide.title }}</div>
        <div class="slide_description text_shadow">{{ slide.text }}</div>
      </div>
    </div>

    <div class="prev_slide_bottom" @click="prev_slide">
      <i class="fa fa-chevron-left" aria-hidden="true"></i>
    </div>

    <div class="next_slide_bottom" @click="next_slide">
      <i class="fa fa-chevron-right" aria-hidden="true"></i>
    </div>
  </div>
</template>

<script>
  // import axios from 'axios';

  export default {
    props: [
      'images_prop'
    ],
    data() {
      return {
        // slides: [],
        slide_count: 0,
        current_slider_index: 0,
      };
    },
    mounted() {
        setInterval(this.next_slide, 10000); // Change slide every 10 seconds

        // this.slides = this.images_prop
    },
    watch: {
      // '$route' (to, from) {
      //   this.slides = this.images_prop
      // },
      // images_prop: function(newVal, oldVal){
      //     this.slides = this.images_prop
      // },
    },
    methods: {
      next_slide() {
        this.current_slider_index++;
        if (this.current_slider_index >= this.slide_count) {
          this.current_slider_index = 0; // Reset index when reaching the end of the slides
        }
      },
      prev_slide() {
        if (this.current_slider_index > 0) {
          this.current_slider_index--;
        } else {
          this.current_slider_index = this.slide_count - 1; // Go to the last slide when reaching the beginning of the slides
        }
      },
    },
  };
</script>

<style scoped>
  .swiper {
    max-width: 100%;
    overflow: hidden;
    position: relative;
    /* padding-right: 1%; /*Add padding to the right side*/
  }

  .swiper_sizing {
    display: flex;
    transition: margin-left 0.5s ease-in-out;
    justify-content: center;
    align-items: center;
    flex-direction: row;
  }
  .slide_title {
    position: absolute;
    top: 75%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 3rem;
    color: #fff;
    /* text-shadow: 3px 3px 3px rgb(0 0 0); */
  }
  .slide_description {
    position: absolute;
    top: 85%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 2rem;
    color: #fff;
    /* text-shadow: 3px 3px 3px rgb(0 0 0); */
  }
  @media(max-width: 756px){
    .slide_title {
      position: absolute;
      top: 60%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 3rem;
      color: #fff;
      /* text-shadow: 3px 3px 3px rgb(0 0 0); */
      text-align: center;
    }
    .slide_description {
      position: absolute;
      top: 85%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-size: 2rem;
      color: #fff;
      /* text-shadow: 3px 3px 3px rgb(0 0 0); */
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
  }

  .slider_img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
</style>
