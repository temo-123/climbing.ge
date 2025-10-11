<template>
    <div id="image">
        <img :src="this.image_src" class="opening_image_resolution" :class="this.img_class " :alt="this.alt" />

        <!-- <div id="watermark"></div> -->
    </div>
</template>

<script>
    export default {
        props:[
            'src',
            'alt',
            'img_class',
        ],
        watch: {
            src: function(newVal, oldVal) { 
                this.image_src = this.src
            },
        },
        data(){
            return {
                content: '',

                image_src: '',

                publicPath: window.location.protocol + '//' + window.location.hostname
            }
        },
        mounted() {
            this.image_src = this.publicPath  + this.src
        }
    }
</script>

<style scss>
#watermark{
    background: url("/images/header logo.png?fea6f86127b293ea8ea3713c2847dc21") no-repeat;
    background-repeat: no-repeat;
    background-size: 80% 80%;

    width: 22%;
    height: 40px;

    /* position: relative;
    left: 82%;
    bottom: 6%; */

    position: absolute;
    left: 75%;
    /* bottom: 10%; */

}
/* #image{
   width: 100%;
   height: 100%;
   position: relative;
   top: 0;
   left: 0;
} */
</style>