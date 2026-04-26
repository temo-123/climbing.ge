<template>
    <div>
        <div class="h-recent-work">
            <div class="container">

                <h1 class="index_h2">{{ $t('summit.title.summit_list') }}</h1>

                <div class="bar"><i class="fa fa-flag-checkered"></i></div>

                <h3 class="article_list_short_description">
                    {{ $t('summit.summit_map_description') }}
                </h3>

                <div v-if="loading" class="text-center py-5">
                    <i class="fa fa-spinner fa-spin fa-3x"></i>
                </div>

                <div v-else-if="summits.length === 0" class="text-center py-5">
                    <p class="text-muted">{{ $t('summit.no_summits') }}</p>
                </div>

                <div v-else class="row article_card_container">
                    <div v-for="summit in summits" :key="summit.id" class="food col-md-4">
                        <div class="portfolio-img view view-first">

                            <!-- Mountain SVG illustration -->
                            <svg viewBox="0 0 400 200" xmlns="http://www.w3.org/2000/svg" class="summit-card-svg">
                                <defs>
                                    <linearGradient :id="'sky-' + summit.id" x1="0" y1="0" x2="0" y2="1">
                                        <stop offset="0%" stop-color="#0d1f33"/>
                                        <stop offset="100%" stop-color="#1a4a6b"/>
                                    </linearGradient>
                                </defs>
                                <rect width="400" height="200" :fill="'url(#sky-' + summit.id + ')'"/>
                                <circle cx="30"  cy="20" r="1.5" fill="white" opacity="0.8"/>
                                <circle cx="100" cy="14" r="1"   fill="white" opacity="0.7"/>
                                <circle cx="200" cy="10" r="1.5" fill="white" opacity="0.9"/>
                                <circle cx="300" cy="16" r="1"   fill="white" opacity="0.6"/>
                                <circle cx="370" cy="12" r="1.5" fill="white" opacity="0.8"/>
                                <circle cx="370" cy="28" r="12" fill="#f5e6a3" opacity="0.8"/>
                                <circle cx="377" cy="22" r="9"  fill="#1a4a6b" opacity="0.9"/>
                                <polygon points="0,200 100,90 200,200"   fill="#0c3320" opacity="0.65"/>
                                <polygon points="200,200 310,70 400,140 400,200" fill="#0c3320" opacity="0.65"/>
                                <polygon points="0,200 130,50 270,200"   fill="#145a32"/>
                                <polygon points="120,200 260,30 400,200" fill="#1d7a48"/>
                                <polygon points="240,75 260,30 280,75 270,60 250,60" fill="white" opacity="0.95"/>
                                <line x1="260" y1="42" x2="250" y2="58" stroke="white" stroke-width="1.5" opacity="0.5"/>
                                <line x1="260" y1="46" x2="270" y2="58" stroke="white" stroke-width="1.5" opacity="0.4"/>
                                <text v-if="summit.height" :x="376" y="186" text-anchor="end" fill="white" font-size="14" font-weight="bold" opacity="0.9">{{ summit.height }}m</text>
                            </svg>

                            <!-- Hover overlay -->
                            <div class="mask">
                                <router-link :to="'/summit/' + summit.url_title" class="info">
                                    <i class="fa fa-arrow-right"></i>
                                </router-link>
                            </div>

                            <!-- Card details -->
                            <div class="isotope-item-dtls">
                                <h3>{{ summit.title }}</h3>
                                <div class="article_size">
                                    <span v-if="summit.ka_title" class="text-muted small d-block mb-1">{{ summit.ka_title }}</span>
                                    <span v-if="summit.mount_route_name">
                                        <i class="fa fa-flag text-success"></i> {{ summit.mount_route_name }}
                                    </span>
                                    <span v-if="summit.height" class="ml-2">
                                        <i class="fa fa-arrow-up text-success"></i> {{ summit.height }}m
                                    </span>
                                </div>
                                <div class="summit-card-actions mt-2">
                                    <router-link :to="'/summit/' + summit.url_title" class="btn btn-default btn-sm btn-send main-btn">
                                        <i class="fa fa-info-circle"></i> Details
                                    </router-link>
                                    <router-link :to="'/make_ascent/' + summit.id" class="btn btn-success btn-sm ml-1">
                                        <i class="fa fa-flag-checkered"></i> Ascent
                                    </router-link>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>

        <metaData
            :title="$t('summit.title.summit_list') + ' – Summit Climbing Georgia'"
            :description="$t('summit.summit_map_description')"
            :image="'/public/images/meta_img/mountain.jpg'"
        />
    </div>
</template>

<script>
import metaData from '../../items/MetaDataComponent.vue'

export default {
    name: 'SummitListPage',
    components: { metaData },
    data() {
        return {
            summits: [],
            loading: false,
        }
    },
    mounted() {
        this.loadSummits()
    },
    methods: {
        loadSummits() {
            this.loading = true
            axios.get('summit/list')
                .then(r => { this.summits = r.data })
                .catch(() => {})
                .finally(() => { this.loading = false })
        },
    }
}
</script>

<style scoped>
.summit-card-svg {
    width: 100%;
    height: 180px;
    display: block;
}
.summit-card-actions {
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 4px;
}
</style>
