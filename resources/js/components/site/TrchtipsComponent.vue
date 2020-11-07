<template>
    <div id="carousel3d">
        <carousel-3d :perspective="0" :space="200" :display="5" :controls-visible="true" :controls-prev-html="'❬'" :controls-next-html="'❭'" :controls-width="30" :controls-height="60" :clickable="true" :autoplay="true" :autoplay-timeout="5000">
            <div v-for="security in this.securities" :key="security.id">
                <slide :index="0">
                    <span class="title">Web Development</span>
                    <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
                </slide>
            </div>
            <div>
                <slide :index="0">
                    <span class="title">Web Development</span>
                    <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
                </slide>
            </div>            
            <div>
                <slide :index="0">
                    <span class="title">Web Development</span>
                    <a href="https://www.youtube.com/channel/UCXTfDJ60DBmA932Du6B1ydg">Click Here</a>
                </slide>
            </div>
        </carousel-3d>
    </div>
</template>

<script>
    export default {
        props: [
            "securities",
        ],
        data: {
            slides: 7,
            slide_num: 1
        },
        components: {
            'carousel-3d': Carousel3d.Carousel3d,
            'slide': Carousel3d.Slide
        },
        mounted() {
            console.log(this.slide_num);
        },
        methods: {
            slide_num: function(){
                for (let securities = 0; securities < array.length; securities++) {
                    const element = array[securities];
                    return element;
                }
            }
        }
    }
</script>

<style scoped>
    #carousel3d .carousel-3d-slide {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        text-align: center;
        background-color: #fff;
        padding: 10px;
        -webkit-transition: all .4s;
        transition: all .4s;
    }
    #carousel3d .carousel-3d-slide.current {
        background-color: #333;
        color: #fff;
    }
    #carousel3d .carousel-3d-slide.current span {
        font-size: 20px;
        font-weight: 500;
    }
</style>