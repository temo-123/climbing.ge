<template>
  <div>
    <div class="row" v-if="db_images.length > 0">
      <h3>{{__ ('gallery')}}</h3>
      <div>
        <gallery :images="db_images" :index="db_gallery_index" @close="db_gallery_index = null"></gallery>
        <div
          class="image index_gallery_image"
          v-for="(dbImage, dbImageIndex) in db_images"
          :key="dbImageIndex"
          @click="db_gallery_index = dbImageIndex"
          :style="{ backgroundImage: 'url(' + dbImage + ')', width: '25%', height: '10em' }"
        ></div>
      </div>
    </div>
  </div>
</template>

<script>
  // https://vuejsexamples.com/vuejs-responsive-and-customizable-image-and-video-gallery-2/
  import VueGallery from 'vue-gallery';
  
  export default {
    components: {
      'gallery': VueGallery
    },
    props:[
        'article_id',
        // 'image_url',
    ],
    data: function () {
      return {
        db_images: [],
        images_for_gallery: [],
        db_gallery_index: null
      };
    },
    mounted() {
        this.get_article_images();
    },
    methods: {
        get_article_images() {
            axios
            .get('../api/gallery_image/' + this.article_id, {
            })
            .then(response => {
                this.db_images = response.data
            })
            .catch(error =>{
            })
        }
    }
  }
</script>