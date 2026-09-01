<template>
    <div class="row" v-if="this.db_images.length > 0">
      <div class="container">
          <h2 id="gallery">{{ $t('guide.article.title.gallery')}}</h2>
      </div>

      <div class="sector-images-wrap">
        <openImg
            v-for="(image, index) in db_images"
            :key="image.id"
            :img="images_path + image.image"
            :img_alt="image.title || $t('global.gallery_image_alt')"
            :img_class="'sector_images sector_images_' + db_images.length"
            :gallery="gallery"
            :gallery_index="index"
        />
      </div>

  </div>
</template>

<script>
    import openImg from "../ImageOpenComponent.vue";
    export default {
      components: {
        openImg,
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
      computed: {
        // Every gallery image as one list, so the lightbox can page through them
        // instead of forcing a close/reopen per photo — same pattern as
        // SectorComponent's sector_gallery.
        gallery() {
          return this.db_images.map(image => ({
            src: this.images_path + image.image,
            alt: image.title || this.$t('global.gallery_image_alt'),
          }));
        },
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
