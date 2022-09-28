<template>
    <section class="m-component-stack mm-component-stack--is-stacked">
        <div class="m-image-hero mm-image-hero--ssr">
            <!-- <div href="#"> -->
            <router-link :to="'news/'+news[0][0].url_title">
                <div class="m-image-hero--media">
                    <div>
                        <div class="is-loaded">
                            <!-- <source type="image/webp" srcset="#">
                            <source srcset="#"> -->
                            <!-- <img class="m-image-hero--image" :alt="news[0][0].title" decoding="async" :src="'images/news_img/'+news.image" > -->

                            <site-img v-if="news[0][0].image" :src="'images/news_img/'+news[0][0].image" :img_class="'m-image-hero--image'" :alt="news[0][0][0][0].title"/>
                            <site-img v-else :src="'../../../public/images/site_img/image.png'" :img_class="'m-image-hero--image'" :alt="news[0][0][0][0].title"/>
                        </div>
                        <div class="m-image-hero--aspect-ratio-wrapper">
                            <div class="m-image-hero--aspect-ratio-pusher"></div>
                        </div>
                    </div>
                    <!-- <div class="m-image-hero--overlay"></div> -->
                    <div class="m-image-hero--container">
                        <div class="m-image-hero--label mm-image-hero--no-background">
                            <div class="m-image-hero--metadata-label">
                                <div>News</div>
                            </div>
                        </div>
                        <div class="m-image-hero--text-panel">
                            <router-link :to="'news/'+news[0][0].url_title">
                            <!-- <a href="#" onclick="return phoenixTrackClickEvent(this, event);" phx-track-id="Title"> -->
                                <div>
                                    <h2 class="m-ellipsis m-image-hero--header-text mm-image-hero--no-background">
                                        <span class="m-ellipsis--text">{{ news[0][0][0][0].title }}</span>
                                    </h2>
                                    <span style="text-shadow: #abababb5 1px 1px 0;" v-html="news[0][0][0][0].short_description"></span>
                                </div>
                            <!-- </a> -->
                            </router-link>
                        </div>
                    </div>
                </div>
            <!-- </div> -->
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
        },
        methods: {
        }
    }
</script>