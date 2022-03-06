<template>
    <div class="container-fluid">
        <gallery :images="db_images" :index="db_gallery_index" @close="db_gallery_index = null"></gallery>
        <div
            class="image col-md-4 index_gallery_image "
            v-for="(dbImage, dbImageIndex) in db_images"
            :key="dbImageIndex"
            @click="db_gallery_index = dbImageIndex"
            :style="{ backgroundImage: 'url(' + dbImage + ')', width: '25%', height: '8em' }"
        ></div>
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
        // this.get_index_gallery_image();
    },
    methods: {
        // get_index_gallery_image() {
        //     axios
        //     .get('../get_index_gallery_image/', {
        //     })
        //     .then(response => {
        //       console.log('response.data');
        //       console.log(response.data);
        //         this.db_images = response.data
        //     })
        //     .catch(error =>{
        //     })
        // }
    },
    created(){
          axios
            .get('../api/gallery_image', {
            })
            .then(response => {
              // console.log('response.data');
              // console.log(response.data);
              this.db_images = response.data
            })
            .catch(error =>{
            })
    }
  }
</script> 