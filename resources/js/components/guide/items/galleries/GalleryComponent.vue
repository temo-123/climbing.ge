<template>
    <div class="row" v-if="this.db_images.length > 0">
      <div class="container">
          <h2 id="gallery">{{ $t('guide.article.title.gallery')}}</h2>
      </div>

      <articleImageGallery :images="db_images" :image_path="images_path"/>

  </div>
</template>

<script>
    import articleImageGallery from "./ArticleImageGalleryComponent.vue";
    export default {
      components: {
        articleImageGallery,
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
