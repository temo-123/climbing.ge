<template>
    <div>
        <ol class="breadcrumb" style="padding: 0; font-size: 75%;">
        <li><a href="#">index</a></li>
        <li><a href="#">next page</a></li>
        <!-- <li class="active">{{$active}}</li> -->
        <li class="active">article</li>
    </ol>
    </div>
</template>

<script>
    export default {
        props: [
            // "securities",
        ],
        data: {
            // slides: 7,
            // slide_num: 1
        },
        components: {
            // 'carousel-3d': Carousel3d.Carousel3d,
            // 'slide': Carousel3d.Slide
        },
        mounted() {
            // console.log(this.slide_num);
        },
        methods: {
            // slide_num: function(){
            //     for (let securities = 0; securities < array.length; securities++) {
            //         const element = array[securities];
            //         return element;
            //     }
            // }
        }
    }
</script>
