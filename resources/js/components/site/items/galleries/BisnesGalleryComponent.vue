<template>
    <div class="row similar_articles" v-if="this.images.length > 0">
      <div class="container">
          <h2 id="gallery">{{ $t('gallery')}}</h2>
      </div>

      <div class="container-fluid">

        <div class="container">
          <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-3 gallery_images" v-for="image in images" :key="image.id" @click="open_image(image)">
              <site-img :src="'/public/images/suport_local_bisnes_img/'+image.image" :alt="image.title" :img_class="'gallery_img'"/>
            </div>
          </div>
        </div>

        <div class="open_img" v-if="open_img" >
          <div class="close_bottom">
            <bottom @click="close_image(image)">X</bottom>
          </div>

          <site-img :src="'/public/images/suport_local_bisnes_img/'+active_img.image" :alt="active_img.image" :img_class="'gallery_big_img'" />

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
  </div>
</template>

<script>
  export default {
    props:[
        'images',
    ],
    data: function () {
      return {
        // images: this.images,
        active_img: [],
        open_img: false
      };
    },
    mounted() {
        // this.get_article_images();
    },
    methods: {
        // get_article_images() {
        //     axios
        //     .get('../api/gallery_image/' + this.article_id)
        //     .then(response => {
        //         this.images = response.data
        //     })
        //     .catch(error =>{
        //     })
        // },
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
  }
</script> 

<style scoped>
.close_bottom{
  float: right;
}
.close_bottom{
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
  margin-top: 1%;
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
  position: absolute;
  width: 80%;
  right: 10%;
  left: 10%;
  top: 8%
}
</style>