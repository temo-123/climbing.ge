<template>
    <!-- .cg-wm: the site watermark over the photo (hidden on small boxes) -->
    <div id="image" class="cg-wm">
        <!-- lazy + async decode: off-screen images (long lists, sliders) load
             as they come near the viewport and never block the main thread -->
        <img :src="this.image_src" class="opening_image_resolution" :class="this.img_class " :alt="this.alt" :loading="this.loading" decoding="async" />

        <!-- <div id="watermark"></div> -->
    </div>
</template>

<script>
    export default {
        props: {
            src: null,
            alt: null,
            img_class: null,
            // 'eager' for an above-the-fold hero
            loading: { type: String, default: 'lazy' },
        },
        computed: {
            // the URL is there on the first render (no empty src, no second
            // render after mount), so the download starts right away
            image_src() {
                return this.src ? this.publicPath + this.src : ''
            },
        },
        data(){
            return {
                content: '',

                publicPath: window.location.protocol + '//' + window.location.hostname
            }
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