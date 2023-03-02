<template>
    <img :src="this.image_src"  :class="this.img_class" :alt="this.alt" v-watermark='config = option'/>
</template>

<script>
    //https://www.vuescript.com/custom-watermarks/
    export default {
        props:[
            'src',
            'alt',
            'img_class',
        ],
        data(){
            return {
                content: '',
                option: {
                    // "bottomleft", "bottomright", "topleft", "topright", "center", "fill"
                    mode: "bottomright",
                    textBaseline: "middle",
                    font: "20px Arial",
                    fillStyle: "crimson",
                    content: 'CLIMBING.GE',
                    rotate: 30
                },

                image_src: '',

                publicPath: window.location.protocol + '//' + window.location.hostname
            }
        },
        mounted() {
            this.image_src = this.publicPath  + this.src
        }
    }
</script>