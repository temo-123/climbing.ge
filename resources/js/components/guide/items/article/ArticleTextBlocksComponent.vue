<template>
    <div>
        <span v-html="this.article_prop.locale_data.text" id="description" class="article_text_block" v-if="this.article_prop.locale_data.text != null"></span>

        <!-- Best time for climbing block -->
        <div v-if="has_best_time">
            <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

            <generalInfo :global_info_prop="article_prop.general_info.best_time" :locale_data_prop="article_prop.locale_data.best_time"/>
        </div>

        <!-- Weather forecast is optional — shown only when the admin added one -->
        <div v-if="has_weather" class="weather_widget_card">
            <div class="weather_widget_header">
                <i class="fa fa-cloud"></i> {{ $t('guide.weather_forecast') }}
            </div>
            <div class="weather_widget_body" v-html="this.article_prop.global_data.weather"></div>
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

        <!-- indoor gyms: opening hours -->
        <IndoorHours v-if="article_prop.global_data.category == 'indoor'" :gym="article_prop.global_data" />

        <!-- price (indoor "No information about prices" replaces it) -->
        <div v-if="no_price_info">
            <h2 id="price">{{ $t('common.price')}}</h2>
            <p class="text-muted">{{ $t('guide.article.no_info_price') }}</p>
        </div>
        <div v-else-if="this.article_prop.locale_data.prices_text != null">
            <h2 id="price">{{ $t('common.price')}}</h2>
            <span v-html="this.article_prop.locale_data.prices_text" class="article_text_block"></span>
        </div>

        <!-- what need -->
        <div v-if="this.article_prop.locale_data.what_need || this.article_prop.general_info.what_need_info.length != 0 || default_gear.length">
            <h2 id="what_need">{{ $t('guide.article.title.what need')}}</h2>

            <!-- the text as written, and on outdoor / ice / mountaineering pages the
                 same gear as a packing checklist under it -->
            <generalInfo :global_info_prop="article_prop.general_info.what_need_info" :locale_data_prop="article_prop.locale_data.what_need"/>
            <gearChecklist
                v-if="gear_checklist"
                :text_fallback="false"
                :global_info_prop="article_prop.general_info.what_need_info"
                :locale_data_prop="article_prop.locale_data.what_need"
                :article_id="article_prop.global_data.id"
                :default_items="default_gear"
                :title="article_prop.locale_data.title"
            />

        </div>

        <!-- info -->
        <div v-if="this.article_prop.locale_data.info || this.article_prop.general_info.info_block.length != 0">
            <h2 id="info">{{ $t('guide.article.title.info')}}</h2>

            <generalInfo :global_info_prop="article_prop.general_info.info_block" :locale_data_prop="article_prop.locale_data.info"/>

        </div>

        <Donation v-if="!!article_prop.global_data.show_donation" position="left" :autoShow="false"/>

        <LocalBisnesListComponentComponent :article_id="article_prop.global_data && article_prop.global_data.id" />

    </div>
</template>

<script>
    import LocalBisnesListComponentComponent from '../LocaleBisnesListComponent.vue';
    import generalInfo from './GeneralInfoComponent'
    import gearChecklist from './GearChecklistComponent.vue'
    import Donation from '../DonationComponent.vue'
    import IndoorHours from '../IndoorHoursComponent.vue'

    export default {
        props: {
            article_prop: null,
            // checklist used when "What You Need" has no list (mountaineering: the grade's gear)
            default_gear: { type: Array, default: () => [] },
        },
        data: function () {
            return {
                // local_businesses: [],
            }
        },
        components: {
            generalInfo,
            gearChecklist,
            LocalBisnesListComponentComponent,
            Donation,
            IndoorHours,
        },
        computed: {
            gear_checklist() {
                return ['outdoor', 'ice', 'mount_route'].includes(this.article_prop.global_data && this.article_prop.global_data.category)
            },
            has_best_time() {
                return this.article_prop.locale_data.best_time != null
                    || (this.article_prop.general_info?.best_time || []).length > 0
            },
            no_price_info() {
                return this.article_prop.global_data.category == 'indoor' && this.article_prop.global_data.price_status === 'no_info'
            },
            // Empty / whitespace-only / tag-only values don't count as a forecast
            has_weather() {
                const weather = this.article_prop.global_data.weather
                return !!weather && String(weather).replace(/<[^>]*>|&nbsp;/g, '').trim() !== ''
            },
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
        border-bottom: 1px solid var(--cg-line);
        background: var(--cg-chalk);
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
        color: var(--cg-muted);
        text-align: right;
        text-decoration: none;
    }
    .weather_widget_body a:hover {
        color: #2E86C1;
    }
</style>