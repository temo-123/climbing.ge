<template>
    <div>
        <span v-html="this.article_prop.locale_data.text" id="description" class="article_text_block" v-if="this.article_prop.locale_data.text != null"></span>

        <!-- Best time for climbing block -->
        <div>
            <div v-if="this.article_prop.global_data.weather || this.article_prop.general_info.best_time.length != 0">
                <div v-if="(this.article_prop.global_data.weather == null && this.article_prop.locale_data.best_time != null)">
                    <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                    <generalInfo :global_info_prop="article_prop.general_info.best_time" :locale_data_prop="article_prop.locale_data.best_time"/>
                </div>  
                <div v-else-if="this.article_prop.global_data.weather != null && this.article_prop.locale_data.best_time != null">
                    <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

                    <generalInfo :global_info_prop="article_prop.general_info.best_time" :locale_data_prop="article_prop.locale_data.best_time"/>

                    <div class="weather_widget_card">
                        <div class="weather_widget_header">
                            <i class="fa fa-cloud"></i> {{ $t('guide.weather_forecast') }}
                        </div>
                        <div class="weather_widget_body" v-html="this.article_prop.global_data.weather"></div>
                    </div>
                </div>
            </div>
            <div v-else-if="this.article_prop.global_data.weather || this.article_prop.general_info.best_time.length != 0 || this.article_prop.global_data.weather != null">
                <div class="weather_widget_card">
                    <div class="weather_widget_header">
                        <i class="fa fa-cloud"></i> {{ $t('guide.weather_forecast') }}
                    </div>
                    <div class="weather_widget_body" v-html="this.article_prop.global_data.weather"></div>
                </div>
            </div>
        </div>

        <!-- addres -->
        <div v-if="this.article_prop.locale_data.address != null">
            <h2 id="address">{{ $t('guide.article.title.address')}}</h2>
            <span v-html="this.article_prop.locale_data.address" class="article_text_block"></span>
        </div>
        
        <!-- how get -->
        <h2 v-if="(this.article_prop.locale_data.how_get != null && this.article_prop.locale_data.how_get != '') || (this.article_prop.global_data.map != null && this.article_prop.global_data.map != '')" id="how_to_get_there">{{ $t('guide.article.title.how get')}}</h2>

        <div v-if="this.article_prop.locale_data.how_get != null && this.article_prop.locale_data.how_get != ''">
            <!-- <h2 id="how_to_get_there">{{ $t('guide.article.title.how get')}}</h2> -->
            <span v-html="this.article_prop.locale_data.how_get" class="article_text_block"></span>
        </div>

        <!-- map -->
        <div v-if="this.article_prop.global_data.map != null">
            <div class="article_map">
                <span v-html="this.article_prop.global_data.map"></span>
            </div>
        </div>

        <!-- price -->
        <div v-if="this.article_prop.locale_data.prices_text != null">
            <h2 id="price">{{ $t('common.price')}}</h2>
            <span v-html="this.article_prop.locale_data.prices_text" class="article_text_block"></span>
        </div>

        <!-- what need -->
        <div v-if="this.article_prop.locale_data.what_need || this.article_prop.general_info.what_need_info.length != 0">
            <h2 id="what_need">{{ $t('guide.article.title.what need')}}</h2>

            <generalInfo :global_info_prop="article_prop.general_info.what_need_info" :locale_data_prop="article_prop.locale_data.what_need"/>

        </div>

        <!-- info -->
        <div v-if="this.article_prop.locale_data.info || this.article_prop.general_info.info_block.length != 0">
            <h2 id="info">{{ $t('guide.article.title.info')}}</h2>

            <generalInfo :global_info_prop="article_prop.general_info.info_block" :locale_data_prop="article_prop.locale_data.info"/>

        </div>

        <Donation v-if="!!article_prop.global_data.show_donation" position="left" :autoShow="false"/>

        <LocalBisnesListComponentComponent />

    </div>
</template>

<script>
    import LocalBisnesListComponentComponent from '../LocaleBisnesListComponent.vue';
    import generalInfo from './GeneralInfoComponent'
    import Donation from '../DonationComponent.vue'

    export default {
        props: [
            'article_prop'
        ],
        data: function () {
            return {
                // local_businesses: [],
            }
        },
        components: {
            generalInfo,
            LocalBisnesListComponentComponent,
            Donation,
        },
        mounted() {
            // this.get_local_bisnes_for_article()
        },
        methods: {
            // get_local_bisnes_for_article(){
            //     axios
            //     .get('/get_bisnes/get_local_bisnes_for_article/' + this.$route.params.url_title + '/' + localStorage.getItem('lang'))
            //     .then(response => {
            //         this.local_businesses = response.data
            //     })
            //     .catch(error =>{
            //     })
            // },
        }
    }
</script>

<style>
    /* images, videos and maps pasted in the editor keep their fixed width — stop them overflowing on phones */
    .article_text_block img,
    .article_map img {
        max-width: 100%;
        height: auto;
    }
    .article_text_block iframe,
    .article_map iframe {
        max-width: 100%;
    }
    .article_map iframe {
        border: 0;
        border-radius: 10px;
    }

    .weather_widget_card {
        display: inline-block;
        max-width: 100%;
        margin: 10px 0 24px;
        border: 1px solid #e6e6e6;
        border-radius: 10px;
        background: #fff;
        overflow: hidden;
    }
    .weather_widget_header {
        padding: 10px 16px;
        border-bottom: 1px solid #eee;
        background: #f8f9fa;
        font-size: 0.95rem;
        font-weight: 600;
        color: #444;
    }
    .weather_widget_header .fa {
        margin-right: 6px;
        color: #2E86C1;
    }
    .weather_widget_body {
        padding: 8px 12px 6px;
        text-align: center;
    }
    /* meteoblue iframe comes from the DB with a fixed inline width */
    .weather_widget_body iframe {
        display: block;
        max-width: 100%;
        margin: 0 auto;
    }
    /* keep the required meteoblue attribution link, but as a small caption */
    .weather_widget_body a {
        display: block;
        margin-top: 2px;
        font-family: inherit;
        font-size: 0.75rem;
        color: #999;
        text-align: right;
        text-decoration: none;
    }
    .weather_widget_body a:hover {
        color: #2E86C1;
    }
</style>