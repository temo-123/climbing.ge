<template>
    <span>
        <div class="smal_img" @click="open_image()" >
            <img :src="img" :alt="img_alt" :class="img_class + ' cursor_pointer'" />
        </div>

        <div class="big_img">
            <div class="open_img" v-if="open_img" >
                <div class="close_bottom cursor_zoom_out" @click="close_image()"> X </div>

                <img :src="img" :alt="img_alt" :class="'big_img_position cursor_zoom_in zoom'" style="
                                                                                                        max-width: 96%;
                                                                                                        max-height: 80%;
                                                                                                        position: absolute;
                                                                                                        top: 50%;
                                                                                                        left: 50%;
                                                                                                        transform: translate(-50%, -50%);
                                                                                                        "/>

            </div>
        </div>
    </span>
</template>

<script>
    export default {
        props:[
            'img',
            'img_alt',
            'img_class',
        ],
        
        data: function () {
            return {
                open_img: false,
                active_url: window.location.href,
            };
        },
        mounted() {
            this.back_closing(this)
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
            back_closing(th){
                window.onpopstate = function(event) {
                    
                    if(th.open_img){
                        th.close_image()
                    }
                };
            }
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

    @media (max-width: 756px) {
        .close_bottom{
            position: absolute;
            left: 50%;
            right: 0;
            cursor: pointer;
            color: #b3b2b2d9;
            font-size: 2em;
            margin-right: 0.4em;
            margin-top: 0.4em;
        }
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
    .big_img_position{
        max-width: 96%;
        max-height: 80%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }
</style>