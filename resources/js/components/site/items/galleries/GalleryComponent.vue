<template>
    <div class="row similar_articles" v-if="this.db_images.length > 0">
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
          'article_id',
      ],
      data: function () {
        return {
          db_images: [],
          id: this.article_id,
          images_path: '/public/images/gallery_img/'
        };
      },
      mounted() {
          this.get_article_images();
      },
      methods: {
          get_article_images() {
              axios
              .get('../../api/gallery_image/' + this.id)
              .then(response => {
                  this.db_images = response.data
              })
              .catch(error =>{
              })
          },
      },
    }
  </script> 
