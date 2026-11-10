<template>
    <div>
        <!-- .cg-section = one section block; in the "cards" view (services/guide/articleView.js) each becomes a card -->
        <div v-if="has_text(locale.text)" class="cg-section cg-section--description">
            <span v-html="locale.text" id="description" class="article_text_block"></span>
        </div>

        <!-- Best time for climbing block -->
        <div v-if="has_best_time" class="cg-section">
            <h2 id="best_time_to_climb">{{ $t('guide.article.title.best time')}}</h2>

            <generalInfo :global_info_prop="general.best_time" :locale_data_prop="locale.best_time"/>
        </div>

        <!-- Weather forecast is optional — shown only when the admin added one -->
        <div v-if="has_weather" class="weather_widget_card">
            <div class="weather_widget_header">
                <i class="fa fa-cloud"></i> {{ $t('guide.weather_forecast') }}
            </div>
            <div class="weather_widget_body" v-html="this.article_prop.global_data.weather"></div>
        </div>

        <!-- addres -->
        <div v-if="has_text(locale.address)" class="cg-section">
            <h2 id="address">{{ $t('guide.article.title.address')}}</h2>
            <span v-html="locale.address" class="article_text_block"></span>
        </div>
        
        <!-- how get + map -->
        <div v-if="has_text(locale.how_get) || has_map" class="cg-section">
            <h2 id="how_to_get_there">{{ $t('guide.article.title.how get')}}</h2>

            <div v-if="has_text(locale.how_get)">
                <span v-html="locale.how_get" class="article_text_block"></span>
            </div>

            <!-- map -->
            <div v-if="has_map">
                <div class="article_map">
                    <span v-html="this.article_prop.global_data.map"></span>
                </div>
            </div>
        </div>

        <!-- indoor gyms: opening hours -->
        <IndoorHours v-if="article_prop.global_data.category == 'indoor'" :gym="article_prop.global_data" class="cg-section" />

        <!-- price (indoor "No information about prices" replaces it) -->
        <div v-if="no_price_info" class="cg-section">
            <h2 id="price">{{ $t('common.price')}}</h2>
            <p class="text-muted">{{ $t('guide.article.no_info_price') }}</p>
        </div>
        <div v-else-if="has_text(locale.price_text)" class="cg-section">
            <h2 id="price">{{ $t('common.price')}}</h2>
            <span v-html="locale.price_text" class="article_text_block"></span>
        </div>

        <!-- what need -->
        <div v-if="has_block(locale.what_need, general.what_need_info) || default_gear.length" class="cg-section">
            <h2 id="what_need">{{ $t('guide.article.title.what need')}}</h2>

            <!-- the text as written, and on outdoor / ice / mountaineering pages the
                 same gear as a packing checklist under it -->
            <generalInfo :global_info_prop="general.what_need_info" :locale_data_prop="locale.what_need"/>
            <gearChecklist
                v-if="gear_checklist"
                :text_fallback="false"
                :global_info_prop="general.what_need_info"
                :locale_data_prop="locale.what_need"
                :article_id="article_prop.global_data.id"
                :default_items="default_gear"
                :title="locale.title"
            />

        </div>

        <!-- info -->
        <div v-if="has_block(locale.info, general.info_block)" class="cg-section">
            <h2 id="info">{{ $t('guide.article.title.info')}}</h2>

            <generalInfo :global_info_prop="general.info_block" :locale_data_prop="locale.info"/>

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
            // locale / general_info can be missing (business pages, partial API rows)
            locale() {
                return this.article_prop.locale_data || {}
            },
            general() {
                return this.article_prop.general_info || {}
            },
            has_best_time() {
                return this.has_block(this.locale.best_time, this.general.best_time)
            },
            has_map() {
                return this.has_text(this.article_prop.global_data.map)
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
            // Optional editor texts: null / whitespace / tag-only ("<p><br></p>") count as empty
            has_text(value) {
                return value != null && String(value).replace(/<[^>]*>|&nbsp;/g, '').trim() !== ''
            },
            // An attached general-info block (API: object with block_action, or [] when none)
            has_general(block) {
                return !!block && !Array.isArray(block) && !!block.block_action && this.has_text(block.text)
            },
            has_block(text, block) {
                return this.has_text(text) || this.has_general(block)
            },
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