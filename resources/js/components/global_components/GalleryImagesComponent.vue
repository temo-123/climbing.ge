<template>
    <div class="container-fluid">

        <div class="container">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-3 gallery_images" v-for="image in db_images" :key="image.id" @click="open_image(image)">
                    <site-img :src="path + image.image" :alt="image.title" :img_class="'gallery_img'"/>
                </div>
            </div>
        </div>

        <div class="open_img" v-if="open_img" >
            <div class="close_bottom">
                <span @click="close_image()">X</span>
            </div>

            <site-img :src="path + active_img.image" :alt="active_img.image" :img_class="'gallery_big_img'" />

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
  </template>
  
  <script>
    export default {
      // components: {
      //   'gallery': VueGallery
      // },
      props:[
          'images_prop',
          'image_path_prop'
      ],
      data: function () {
        return {
          path: '',
          db_images: [],
          active_img: [],
          open_img: false
        };
      },
      mounted() {
          this.path = this.image_path_prop
          this.db_images = this.images_prop
      },
      methods: {
          open_image(db_img){
            this.active_img = db_img
            this.open_img = true
            
            document.body.classList.add('body_hiden') // off page scroling
  
            let that = this;
            document.addEventListener('keyup', function (evt) {
                if (evt.keyCode === 27) {
                  that.close_image();
                }
            }, { once: true });
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