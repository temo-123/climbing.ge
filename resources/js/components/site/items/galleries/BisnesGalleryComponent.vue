<template>
    <div class="row similar_articles" v-if="this.images.length > 0">
      <div class="container">
          <h2 id="gallery">{{ $t('gallery')}}</h2>
      </div>

      <galleryComponrnt :images_prop="this.images" :image_path_prop="this.images_path"/>

  </div>
</template>

<script>
    import galleryComponrnt from "../../../global_components/GalleryImagesComponent.vue";
    export default {
      props:[
          'images',
      ],

      components: {
        galleryComponrnt,
      },

      data: function () {
        return {
          images_path: '/public/images/suport_local_bisnes_img/'
        };
      },

      mounted() {
          // 
      },

      methods: {
          // 
      },
    }
</script> 
