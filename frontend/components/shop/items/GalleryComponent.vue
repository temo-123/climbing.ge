<template>
    <div class="row similar_articles galery_margin_bottom" v-if="this.db_images.length > 0">
      <div class="container text-center">
          <h2 id="gallery">{{ $t('shop.title.gallery')}}</h2>
      </div>

      <galleryComponrnt :images_prop="this.db_images" :image_path_prop="this.images_path"/>
     
  </div>
</template>

<script>
  import galleryComponrnt from "../../global_components/GalleryImagesComponent.vue";
  export default {
    props:[
        'images_prop',
        'folder_path_prop'
    ],

    components: {
      galleryComponrnt,
    },

    data: function () {
      return {
        db_images: [],
        images_path: ''
      };
    },
    mounted() {
        this.db_images = this.images_prop
        this.images_path = this.folder_path_prop
    },
    watch: {
      '$route' (to, from) {
        this.db_images = this.images_prop
        this.images_path = this.folder_path_prop
      },
      images_prop: function(newVal, oldVal){
          this.db_images = this.images_prop
      },
      folder_path_prop: function(newVal, oldVal){
          this.images_path = this.folder_path_prop
      },
    },
    methods: {
      //
    },
  }
</script> 

<style scoped>
.galery_margin_bottom{
  margin-bottom: 15%;
}
</style>