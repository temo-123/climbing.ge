<template>
    <img :src="this.src"  :class="this.img_class" :alt="this.alt" v-watermark='config = option'/>
</template>

<script>
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
                    mode: "center",
                    textBaseline: "middle",
                    font: "25px Arial",
                    fillStyle: "grey",
                    content: 'FORUM.CLIMBING.GE',
                    rotate: 45
                }
            }
        },
        mounted() {
            // 
        }
    }
</script>