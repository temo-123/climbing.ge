<template>
  <div class="container">
    <span v-if="db_images.length > 0">
      <h2 class='index_h2'>{{ $t('guide.title.gallery') }}</h2>
      <div class="bar" style="margin-bottom: 5%;"><i class="fa fa-picture-o" aria-hidden="true"></i></div>

      <h3 class="article_list_short_description"> {{this.$siteData.index_gallery_description}} </h3>

      <galleryComponrnt :images_prop="this.db_images" :image_path_prop="this.images_path"/>

    </span>
  </div>
</template>

<script>
    import galleryComponrnt from "../../../global_components/GalleryImagesComponent.vue";
    export default {
      components: {
        galleryComponrnt,
      },
      props:[
          // 'article_id',
      ],
      data: function () {
        return {
          db_images: [],
          images_path: '/public/images/article_gallery_img/',
        };
      },
      mounted() {
          this.get_index_gallery_image();
      },
      methods: {
          get_index_gallery_image() {
              axios
              .get('/gallery_image/get_index_gallery/', {
              })
              .then(response => {
                  this.db_images = response.data
              })
              .catch(error =>{
              })
          },
      },
    }
</script>
