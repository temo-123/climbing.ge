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
                    mode: "bottomright",
                    textBaseline: "middle",
                    font: "20px Arial",
                    fillStyle: "crimson",
                    content: 'CLIMBING.GE',
                    rotate: 30
                }
            }
        },
        mounted() {
            // 
        }
    }
</script>