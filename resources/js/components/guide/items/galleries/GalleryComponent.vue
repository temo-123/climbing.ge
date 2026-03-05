<template>
    <div class="row" v-if="this.db_images.length > 0">
      <div class="container">
          <h2 id="gallery">{{ $t('guide.article.title.gallery')}}</h2>
      </div>

      <galleryComponrnt :images_prop="this.db_images" :image_path_prop="this.images_path"/>

  </div>
</template>

<script>
    import galleryComponrnt from "../../../global_components/GalleryImagesComponent.vue";
    export default {
      components: {
        galleryComponrnt,
      },
      props:[
          'images_prop',
      ],
      data: function () {
        return {
          db_images: [],
          images_path: '/public/images/article_gallery_img/'
        };
      },
      watch: {
        // '$route' (to, from) {
        //   this.get_article_images();
        // },
        images_prop: function(){
            this.update()
          },
      },
      mounted() {
          this.update()
      },
      methods: {
          update(){
            this.db_images = this.images_prop
          },
      },
    }
  </script> 
