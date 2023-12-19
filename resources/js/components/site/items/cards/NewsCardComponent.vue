<template>

    <div class="col-md-2 small_news">
        <router-link :to="'news/'+news.global_data.url_title">
            <article class="m-card mm-card--landscape-image mm-card--type-standard mm-card--density-strip" role="article">
                <div class="m-card--media">
                   <router-link :to="'news/'+news.global_data.url_title" class="m-card--image-link" >
                        <div>
                            <picture class="is-loaded">
                                <site-img v-if="news.global_data.image != NULL" :src="'/public/images/news_img/'+news.global_data.image" :img_class="'m-image-hero--image'" :alt='news.locale_data.title'/>
                                <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'m-image-hero--image'" :alt='news.locale_data.title'/>

                            </picture>
                            <div class="m-card--image-aspect-ratio-pusher"></div>
                        </div>
                    </router-link>
                </div>
                <div class="m-card--content">
                    <div class="m-ellipsis m-card--header">
                        <h3 class="m-ellipsis--text m-card--header-text" role="heading" aria-level="2">{{ news.locale_data.title }}</h3>
                    </div>
                    <div class="m-ellipsis">
                        <span class="m-card--body m-ellipsis--text" v-html="news.locale_data.short_description"></span>
                    </div>
                </div>
            </article>
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
            // console.log('====================================');
            // console.log(news);
            // console.log('====================================');
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