<template>
    <div class="opening_img">
        <div class="smal_img" @click="open_image()" >
            <site-img :src="img" :alt="img_alt" :img_class="img_class" />
        </div>

        <div class="big_img">
            <div class="open_img" v-if="open_img" >
                <div class="close_bottom">
                    <bottom @click="close_image()">X</bottom>
                </div>

                <site-img :src="img" :alt="img_alt" :img_class="'gallery_big_img'" />

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props:[
            'img',
            'img_alt',
            'img_class'
        ],
        data: function () {
            return {
                open_img: false
            };
        },
        mounted() {
        },
        methods: {
            open_image(){
                this.open_img = true
                
                document.body.classList.add('body_hiden') // off page scroling
            },
            close_image(){
                this.open_img = false

                document.body.classList.remove('body_hiden') // on page scroling
            },
        }
    }
</script>

<style scoped>
    .close_bottom{
        float: right;
        cursor: pointer; 
        color: #b3b2b2d9;
        font-size: 2em;
        margin-right: 0.4em;
        margin-top: 0.4em;
    }
    .smal_img img{
        height: 100%;
        cursor: zoom-in;
    }
    .open_img{
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 1050;
        background: #000000d9;

        transition: opacity .15s linear;
    }
    .gallery_big_img{
        /* position: absolute;
        width: 80%;
        right: 10%;
        left: 10%; */

        /* position: relative;
        top: 8%;
        width: 80%;
        display: block;
        margin-left: auto;
        margin-right: auto; */
        
        position: relative;
        top: 5%;
        /* width: 80%; */
        height: 100%;
        max-height: 90%;
        display: block;
        margin-left: auto;
        margin-right: auto;
    }
</style>