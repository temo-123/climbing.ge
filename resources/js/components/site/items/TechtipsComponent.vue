<template>
    <div id="carousel3d">
      <carousel-3d :perspective="0" :space="200" :display="5" :controls-visible="true" :controls-prev-html="'❬'" :controls-next-html="'❭'" :controls-width="30" :controls-height="60" :clickable="true" :autoplay="true" :autoplay-timeout="5000">
        <!-- <slide :index="0">
          <span class="title">Web Development</span>
        <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
        </slide>
        <slide :index="1">
          <span class="title">Web Design</span>
          <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
        </slide>
        <slide :index="2">
          <span class="title">You know</span>
          <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
        </slide>
        <slide :index="3">
          <span class="title">You know</span>
          <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
        </slide>
        <slide :index="4">
          <span class="title">You know</span>
          <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
        </slide>
        <slide :index="5">
          <span class="title">You know</span>
          <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
        </slide>
        <slide :index="6">
          <span class="title">You know</span>
          <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
        </slide> -->
        <slide :index="0">
          <span class="title">You know my tesyt</span>
          <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
        </slide>
        <slide :index="1" v-for="techtip in techtips" :key="techtip.id">
          <span class="title">{{ techtip[0][0].title }}</span>
          <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
        </slide>
      </carousel-3d> 
    </div>
</template>

<script>
    import { Carousel3d, Slide } from 'vue-carousel-3d';

export default {
  components: {
    Carousel3d,
    Slide,
  },
  data() {
      return {
          techtips: [],
          slider_index: 0,
      }
  },
  mounted() { 
      this.get_techtips()
  },
  methods: {
    get_techtips(){
        axios
        .get('../api/techtip')
        .then(response => {
            this.techtips = response.data
        })
        .catch(error =>{
        })
    }
  }

};
</script>

<style scoped>
    #carousel3d .carousel-3d-slide {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        text-align: center;
        background-color: #fff;
        padding: 10px;
        -webkit-transition: all .4s;
        transition: all .4s;
    }
    #carousel3d .carousel-3d-slide.current {
        background-color: #333;
        color: #fff;
    }
    #carousel3d .carousel-3d-slide.current span {
        font-size: 20px;
        font-weight: 500;
    }
</style>