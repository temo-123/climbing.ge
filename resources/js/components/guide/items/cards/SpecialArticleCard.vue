<template>
    <section class="m-component-stack mm-component-stack--is-stacked">
        <div class="m-image-hero mm-image-hero--ssr" v-if='speciales_prop != []'>
            <router-link :to="'special_article/'+speciales_prop.global_data.url_title">
                <div class="m-image-hero--media">
                    <div>
                        <div class="is-loaded">
                            <site-img v-if="speciales_prop.global_data.image" :src="'/public/images/special_img/'+speciales_prop.global_data.image" :img_class="'m-image-hero--image'" :alt='speciales_prop.locale_data.title'/>
                            <site-img v-else :src="'/public/images/site_img/image.png'" :img_class="'m-image-hero--image'" :alt='speciales_prop.locale_data.title'/>
                        </div>
                        <div class="m-image-hero--aspect-ratio-wrapper">
                            <div class="m-image-hero--aspect-ratio-pusher"></div>
                        </div>
                    </div>
                    <div class="m-image-hero--container">
                        <div class="m-image-hero--label mm-image-hero--no-background">
                            <div class="m-image-hero--metadata-label m-image-hero--metadata-label-dangerous">
                                <div>{{ $t('guide.atention_flag') }}</div>
                            </div>
                        </div>
                        <div class="m-image-hero--text-panel">
                            <router-link :to="'special_article/'+speciales_prop.global_data.url_title">
                                <div>
                                    <h2 class="m-ellipsis m-image-hero--header-text mm-image-hero--no-background">
                                        <span class="m-ellipsis--text">{{ speciales_prop.locale_data.title }}</span>
                                    </h2>
                                    <span class="big_speciales_prop_text text_shadow" v-html="speciales_prop.locale_data.short_description"></span>
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
            'speciales_prop',
        ],
        data: function () {
            return {
            };
        },
        components: {
        },
        mounted() {
            // console.log('====================================');
            // console.log(speciales_prop);
            // console.log('====================================');
        },
        methods: {
        }
    }
</script>

<style>
.big_speciales_prop_text{
    /* text-shadow: #abababb5 1px 1px 0; */
    color: #ffffffb5;
    font-size: 1.3em;
}

@media (max-width: 767px){
    .big_speciales_prop_text{
        text-shadow: #abababb5 1px 1px 0;
        display: none;
    }
}
</style>