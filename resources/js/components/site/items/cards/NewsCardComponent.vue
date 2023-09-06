<template>

    <!-- <div class="col-md-2" slide-id="ci0258d58230002521" data-pos="183"> -->
    <div class="col-md-2 small_news">
        <!-- <div href="#"> -->
        <router-link :to="'news/'+news.url_title">
            <article class="m-card mm-card--landscape-image mm-card--type-standard mm-card--density-strip" role="article">
                <div class="m-card--media">
                    <!-- <a class="m-card--image-link" onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Image" href="#"> -->
                    <router-link :to="'news/'+news.url_title" class="m-card--image-link" >
                        <div>
                            <picture class="is-loaded">
                                <!-- <source type="image/webp" sizes="320px" :src="'images/news_img/'+news.image"> -->
                                <!-- <source sizes="320px" :src="'images/news_img/'+news.image"> -->
                                <!-- <img class="m-card--image-element" sizes="320px" :src="'images/news_img/'+news.image" :alt="news[0][0].title"> -->
                                
                                <site-img v-if="news.image != NULL" :src="'/public/images/news_img/'+news.image" :img_class="'m-image-hero--image'" :alt='news[0][0].title'/>
                                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'m-image-hero--image'" :alt='news[0][0].title'/>

                            </picture>
                            <div class="m-card--image-aspect-ratio-pusher"></div>
                        </div>
                    </router-link>
                    <!-- </a> -->
                </div>
                <div class="m-card--content">
                    <div class="m-ellipsis m-card--header">
                        <!-- <a onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Title" href="#"> -->
                            <h3 class="m-ellipsis--text m-card--header-text" role="heading" aria-level="2">{{ news[0][0].title }}</h3>
                        <!-- </a> -->
                    </div>
                    <div class="m-ellipsis">
                        <!-- <p class="m-card--body m-ellipsis--text">{{ news[0][0].short_description }}</p> -->
                        <span class="m-card--body m-ellipsis--text" v-html="news[0][0].short_description"></span>
                    </div>
                </div>
            </article>
        <!-- </div> -->
        </router-link>
    </div>   
  
</template>

<script>
    export default {
        props: [
            'news',
        ],
        data: function () {
            return {
            };
        },
        components: {
        },
        mounted() {
        },
        methods: {
        }
    }
</script>

<style>
.small_news{
    padding-right: 5px;
    padding-left: 5px;
}
</style>