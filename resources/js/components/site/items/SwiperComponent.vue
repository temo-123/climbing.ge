<template>
  <div class="swiper">
    <div class="swiper_sizing"  :style="'margin-left:' + '-' + (-100*carrent_slider_index) + '%;'">
      <div  v-for="slide in slides" :key="slide.id" class="head_slider">

          <!-- <div class="slider_text text_position_left_top" v-if="slide.slide_text_position == 1">
            <p class="slide_title">{{slide.title}}</p>
            <p>{{slide.text}}</p>
          </div>
          <div class="slider_text text_position_right_top" v-if="slide.slide_text_position == 2">
            <p class="slide_title">{{slide.title}}</p>
            <p>{{slide.text}}</p>
          </div>
          <div class="slider_text text_position_left_botom"  v-if="slide.slide_text_position == 3">
            <p class="slide_title">{{slide.title}}</p>
            <p>{{slide.text}}</p>
          </div>
          <div class="slider_text text_position_right_botom"  v-if="slide.slide_text_position == 4">
            <p class="slide_title">{{slide.title}}</p>
            <p>{{slide.text}}</p>
          </div> -->

          <site-img :src="'/public/images/gallery_img/'+slide.image" :alt="slide.title" :img_class="'slider_img'" />
          <!-- <site-img :src="slide.image" :alt="slide.title" :img_class="'slider_img'" /> -->

      </div>
    </div>

    <!-- <div class="previes_slide_bottom" @click="previes_image">
        <i class="fa fa-chevron-left" aria-hidden="true"></i>
    </div>

    <div class="next_slide_bottom" @click="next_image">
        <i class="fa fa-chevron-right" aria-hidden="true"></i>
    </div> -->

  </div>
</template>

<script>
  export default {
      data() {
          return {
              slides: {},

              carrent_slider_index: 0,
          };
      },
      mounted() {
          this.get_index_header_images()
      },
      methods: {
          next_image(){
              this.carrent_slider_index++
          },
          previes_image(){
            if(this.carrent_slider_index > 0){
              this.carrent_slider_index--
            }
          },
          
          get_index_header_images(){
            axios
            .get('../api/swiper')
            .then(response => {
                this.slides = response.data
                // this.lastNews = response.data[0]
                // this.get_news()
            })
            .catch(error =>{
            })
        },
      }
    }
</script>

<style scoped>
/* .head_slider{
  white-space: normal;
  background-size: cover;
  flex-shrink: 0;
  display: block;
  width: 100%;
  position: relative;
} */
/* .head_slide{

} */
.swiper{
  max-width: 100%; 
  overflow: hidden;
}
/* .swiper_sizing{
  display: flex;
} */
.previes_slide_bottom{
    left: 1%;
}
.next_slide_bottom{
    right: 1%;
}
.next_slide_bottom, .previes_slide_bottom{
    margin-top: -18%;
    font-size: 300%;
    cursor: pointer;
    text-shadow: #d0d0d0 1px 1px 1px;
    position: absolute;
}
.slider_img{
  width: 100%
}
</style>