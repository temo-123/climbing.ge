<template>
    <section class="m-component-stack mm-component-stack--is-stacked">
        <div class="m-image-hero mm-image-hero--ssr">
            <router-link :to="'news/'+news.global_data.url_title">
                <div class="m-image-hero--media">
                    <div>
                        <div class="is-loaded">
                            <site-img v-if="news.global_data.image" :src="'/public/images/news_img/'+news.global_data.image" :img_class="'m-image-hero--image'" :alt='news.locale_data.title'/>
                            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'m-image-hero--image'" :alt='news.locale_data.title'/>
                        </div>
                        <div class="m-image-hero--aspect-ratio-wrapper">
                            <div class="m-image-hero--aspect-ratio-pusher"></div>
                        </div>
                    </div>
                    <div class="m-image-hero--container">
                        <div class="m-image-hero--label mm-image-hero--no-background">
                            <div class="m-image-hero--metadata-label">
                                <div>News</div>
                            </div>
                        </div>
                        <div class="m-image-hero--text-panel">
                            <router-link :to="'news/'+news.global_data.url_title">
                                <div>
                                    <h2 class="m-ellipsis m-image-hero--header-text mm-image-hero--no-background">
                                        <span class="m-ellipsis--text">{{ news.locale_data.title }}</span>
                                    </h2>
                                    <span class="big_news_text text_shadow" v-html="news.locale_data.short_description"></span>
                                </div>
                            </router-link>
                        </div>
                    </div>
                </div>
            </router-link>
        </div>
    </section>
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
.big_news_text{
    /* text-shadow: #abababb5 1px 1px 0; */
    color: #ffffffb5;
    font-size: 1.3em;
}

@media (max-width: 767px){
    .big_news_text{
        text-shadow: #abababb5 1px 1px 0;
        display: none;
    }
}
</style>