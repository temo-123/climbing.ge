<template>
  <div class="container">
    <span v-if="db_images.length > 0">
      <h2 class='index_h2'>{{ $t('title gallery') }}</h2>
      <div class="bar" style="margin-bottom: 5%;"><i class="fa fa-picture-o" aria-hidden="true"></i></div>

      <h3> <span v-html="this.$siteData.index_gallery_description"></span></h3>

      <div class="container-fluid">
          <!-- <gallery :images="db_images" :index="db_gallery_index" @close="db_gallery_index = null" v-watermark='config = option'></gallery>
          <div
              class="gallery col-md-4 index_gallery_image"
              :style="{ backgroundImage: 'url(' + dbImage + ')', width: '25%', height: '8em' }"

              v-for="(dbImage, dbImageIndex) in db_images"
              :key="dbImageIndex"

              @click="db_gallery_index = dbImageIndex"
              
              v-watermark='config = option'
          >
          </div> -->

          <div class="container">
            <div class="row">
              <div class="col-xs-6 col-sm-6 col-md-3 gallery_images" v-for="image in db_images" :key="image.id" @click="open_image(image)">
                <site-img :src="'../images/gallery_img/'+image.image" :alt="image.title" :img_class="'gallery_img'"/>
              </div>
            </div>
          </div>

          <div class="open_img" v-if="open_img" >
            <div class="close_bottom">
              <bottom @click="close_image(image)">X</bottom>
            </div>

            <site-img :src="'../images/gallery_img/'+active_img.image" :alt="active_img.image" :img_class="'gallery_big_img'" />

            <div class="image_moving">
              <div class="previes_img_bottom"  @click="previes_image()">
                  <i class="fa fa-chevron-left" aria-hidden="true"></i>
              </div>

              <div class="next_img_bottom"  @click="next_image()">
                  <i class="fa fa-chevron-right" aria-hidden="true"></i>
              </div>
            </div>

          </div>
      </div>
    </span>
  </div>
</template>

<script>
    // https://vuejsexamples.com/vuejs-responsive-and-customizable-image-and-video-gallery-2/
  // import VueGallery from 'vue-gallery';
  // const VueGallery = require('vue-gallery');
  
  export default {
    // components: {
    //   'gallery': VueGallery
    // },
    props:[
        'article_id',
        // 'image_url',
    ],
    data: function () {
      return {
        db_images: [],
        active_img: [],
        open_img: false
      };
    },
    mounted() {
        this.get_index_gallery_image();
    },
    methods: {
        get_index_gallery_image() {
            axios
            .get('../api/gallery_image/', {
            })
            .then(response => {
                this.db_images = response.data
            })
            .catch(error =>{
            })
        },
        open_image(db_img){
          this.active_img = db_img
          this.open_img = true
          
          document.body.classList.add('body_hiden') // off page scroling
        },
        close_image(){
          this.active_img = []
          this.open_img = false

          document.body.classList.remove('body_hiden') // on page scroling
        },
        previes_image(){
          console.log('previes');
        },
        next_image(){
          console.log('next');
        }
    },
    // created(){
    //       axios
    //         .get('../api/gallery_image', {
    //         })
    //         .then(response => {
    //           // console.log('response.data');
    //           // console.log(response.data);
    //           this.db_images = response.data
    //         })
    //         .catch(error =>{
    //         })
    // }
  }
</script>

<style scoped>
.close_bottom{
  float: right;
  cursor: pointer; 
  color: #b3b2b2d9;
  font-size: 2em;
  margin-right: 0.4em;
  margin-top: 0.4em;
}
.gallery_img{
  max-width: 100%;
}
.gallery_images{
  cursor: pointer;
}
/* .image_moving{

} */
.previes_img_bottom{
  float: left;
  margin-left: 0.1em;
}
.next_img_bottom{
  float: right;
  margin-right: -0.6em;
}
.previes_img_bottom, .next_img_bottom{
  cursor: pointer; 
  color: #b3b2b2d9;
  font-size: 3.5em;
  margin-top: 7em;
}
.open_img{
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 1050;
    background: #000000d9;

    transition: opacity .15s linear;
}
.gallery_big_img{
  position: relative;
  top: 5%;
  width: 80%;
  max-height: 90%;
  display: block;
  margin-left: auto;
  margin-right: auto;
}
</style>