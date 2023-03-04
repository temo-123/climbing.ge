<template>
    <img :src="this.image_src" :class="this.img_class" :alt="this.alt" v-watermark='config = option'/>
</template>

<script>
    export default {
        props:[
            'src',
            'alt',
            'img_class',
        ],

        watch: {
            src: function(newVal, oldVal){
                this.image_src = this.src
            },
        },
        data(){
            return {
                content: '',
                option: {
                    // "bottomleft", "bottomright", "topleft", "topright", "center", "fill"
                    mode: "bottomleft",
                    textBaseline: "middle",
                    font: "20px Arial",
                    fillStyle: "crimson",
                    content: 'SHOP.CLIMBING.GE',
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