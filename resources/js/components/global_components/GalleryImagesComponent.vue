<template>
    <div class="container-fluid">

        <div class="container">
            <div class="row">
                <div class="col-xs-4 col-sm-4 col-md-3 gallery_images" v-for="image in db_images" :key="image.id" @click="open_image(image)">
                    <site-img :src="path + image.image" :alt="image.title" :img_class="'gallery_img'"/>
                </div>
            </div>
        </div>

        <div class="open_img" v-if="open_img" >
            <div class="close_bottom">
                <span @click="close_image()">X</span>
            </div>

            <!-- <site-img :src="path + active_img.image" :alt="active_img.image" :img_class="'gallery_big_img'" /> -->
            <img :src="path + active_img.image" :alt="active_img.image" :img_class="'gallery_big_img'" style="
                                                                                                    max-width: 96%;
                                                                                                    max-height: 80%;
                                                                                                    position: absolute;
                                                                                                    top: 50%;
                                                                                                    left: 50%;
                                                                                                    transform: translate(-50%, -50%);
                                                                                                    "/>

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
          active_index: 0,
          open_img: false
        };
      },
      mounted() {
          this.path = this.image_path_prop
          this.db_images = this.images_prop
      },
    watch: {
      '$route' (to, from) {
          this.path = this.image_path_prop
          this.db_images = this.images_prop
      },
      images_prop: function(newVal, oldVal){
          this.path = this.image_path_prop
          this.db_images = this.images_prop
      },
      image_path_prop: function(newVal, oldVal){
          this.path = this.image_path_prop
          this.db_images = this.images_prop
      },
    },
      methods: {
          open_image(db_img){
              this.active_img = db_img
              this.active_index = this.db_images.indexOf(db_img) // set the index of the active image
              this.open_img = true
              
              document.body.classList.add('body_hiden') // off page scroling

              this.add_image_keybord_actions()
          },
          add_image_keybord_actions(){
              let that = this;
              document.addEventListener('keydown', function (evt) {
                if (evt.keyCode === 27) {
                  that.close_image();
                } else if (evt.keyCode === 37) {
                  that.previes_image();
                } else if (evt.keyCode === 39) {
                  that.next_image();
                }
              }, { once: true });
          },
          close_image(){
            this.active_img = []
            this.open_img = false
  
            document.body.classList.remove('body_hiden') // on page scroling
          },
          previes_image() {
            if (this.active_index === 0) {
              this.active_index = this.db_images.length - 1;
            } else {
              this.active_index--;
            }
            this.active_img = this.db_images[this.active_index];

            this.add_image_keybord_actions()
          },
          next_image() {
            if (this.active_index === this.db_images.length - 1) {
              this.active_index = 0;
            } else {
              this.active_index++;
            }
            this.active_img = this.db_images[this.active_index];
            
            this.add_image_keybord_actions()
          },
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
      padding-top: 15px;
    }
    /* .image_moving{
    
    } */
    .previes_img_bottom{
      float: left;
    }
    .next_img_bottom{
      float: right;
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

  .gallery_big_img {
    position: fixed;
    top: 50%;
    left: 50%;

    transform: translate(-50%, -50%);
    width: 90%;
    max-width: 90%;
    max-height: 90%;
    display: block;
  }

  .image_moving {
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      transform: translateY(-50%);
      display: flex;
      justify-content: space-between;
      width: 100%;
      z-index: 1;
  }

  .previes_img_bottom, .next_img_bottom {
      background-color: transparent;
      border: none;
      cursor: pointer;
      font-size: 3.5em;
      color: #b3b2b2d9;
  }

  .previes_img_bottom:hover, .next_img_bottom:hover {
      color: #ffffff;
  }
  </style>