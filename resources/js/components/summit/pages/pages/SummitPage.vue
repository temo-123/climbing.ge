<template>
    <div>
        <div v-if="loading" class="h-recent-work">
            <div class="container text-center py-5">
                <i class="fa fa-spinner fa-spin fa-3x"></i>
            </div>
        </div>

        <div v-else-if="!summit" class="h-recent-work">
            <div class="container text-center py-5">
                <i class="fa fa-exclamation-circle fa-3x text-muted mb-3"></i>
                <h3>{{ $t('summit.summit_page.not_found') }}</h3>
                <router-link to="/summits/list" class="btn btn-default btn-send main-btn">
                    <i class="fa fa-arrow-left"></i> {{ $t('summit.summit_page.back_to_list') }}
                </router-link>
            </div>
        </div>

        <div v-else>
            <!-- Hero section -->
            <div class="h-recent-work">
                <div class="container">

                    <h1 class="index_h2">{{ summit.title }}</h1>
                    <p v-if="summit.ka_title" class="text-muted text-center" style="font-size:1.1rem; margin-top:-10px; margin-bottom:10px">{{ summit.ka_title }}</p>

                    <div class="bar"><i class="fa fa-flag-checkered"></i></div>

                    <!-- Badges row -->
                    <div class="text-center" style="margin-bottom:30px">
                        <span v-if="summit.height" class="label label-default" style="font-size:13px; margin:2px">
                            <i class="fa fa-arrow-up"></i> {{ summit.height }}m
                        </span>
                        <span v-if="summit.region" class="label label-default" style="font-size:13px; margin:2px">
                            <i class="fa fa-map-marker"></i> {{ summit.region.us_name }}
                        </span>
                        <span v-if="summit.latitude && summit.longitude" class="label label-default" style="font-size:13px; margin:2px">
                            <i class="fa fa-crosshairs"></i>
                            {{ parseFloat(summit.latitude).toFixed(4) }}°, {{ parseFloat(summit.longitude).toFixed(4) }}°
                        </span>
                    </div>

                    <!-- Description -->
                    <div v-if="summit.description" class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <p class="article_list_short_description" style="font-size:15px; line-height:1.8">
                                {{ summit.description }}
                            </p>
                        </div>
                    </div>

                    <!-- Make Ascent button -->
                    <div class="text-center" style="margin: 20px 0 40px">
                        <button @click="openAscentModal" class="btn btn-success btn-lg btn-send main-btn">
                            <i class="fa fa-flag-checkered"></i> {{ $t('summit.summit_page.record_ascent') }}
                        </button>
                    </div>

                </div>
            </div>

            <!-- Ascents + QR section -->
            <div class="h-recent-work">
                <div class="container">

                    <div class="row">

                        <!-- Ascent list -->
                        <div class="col-md-8">

                            <h2 class="index_h2" style="font-size:180%">
                                <i class="fa fa-list"></i> {{ $t('summit.summit_page.ascents') }}
                                <span v-if="ascents.length" class="badge" style="background:#1d7a48">{{ ascents.length }}</span>
                            </h2>
                            <!-- <div class="bar" style="margin-top:20px"><i class="fa fa-users"></i></div> -->

                            <div v-if="loadingAscents" class="text-center py-4">
                                <i class="fa fa-spinner fa-spin fa-2x"></i>
                            </div>
                            <div v-else-if="ascents.length === 0" class="text-center py-4 text-muted">
                                <i class="fa fa-flag fa-2x mb-2"></i>
                                <p>{{ $t('summit.summit_page.no_ascents') }}</p>
                            </div>
                            <div v-else class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ $t('summit.summit_page.climber') }}</th>
                                            <th>{{ $t('summit.summit_page.date') }}</th>
                                            <th>{{ $t('summit.summit_page.route') }}</th>
                                            <th>GPS</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="a in ascents" :key="a.id"
                                            style="cursor:pointer"
                                            @click="openAscentDetail(a)">
                                            <td><strong>{{ a.name }} {{ a.surname }}</strong></td>
                                            <td class="text-muted small">{{ formatDate(a.ascent_date) }}</td>
                                            <td>
                                                <span v-if="a.route_name">
                                                    {{ a.route_name }}
                                                    <span v-if="a.route_grade" class="label label-default ml-1">{{ a.route_grade }}</span>
                                                </span>
                                                <span v-else class="text-muted">—</span>
                                            </td>
                                            <td>
                                                <span v-if="a.is_gps_validated" class="label label-success">
                                                    <i class="fa fa-check-circle"></i> {{ $t('summit.ascent_page.gps_verified') }}
                                                </span>
                                                <span v-else class="text-muted">—</span>
                                            </td>
                                            <td class="text-right">
                                                <i class="fa fa-chevron-right text-muted" style="font-size:11px"></i>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- QR code sidebar -->
                        <div class="col-md-4">
                            <div class="portfolio-img" style="padding:20px; text-align:center">
                                <h4 class="text-muted text-uppercase" style="letter-spacing:.05em; font-size:12px; margin-bottom:16px">
                                    <i class="fa fa-qrcode"></i> {{ $t('summit.summit_page.scan_to_record') }}
                                </h4>
                                <div class="d-flex justify-content-center mb-3" style="text-align: center;">
                                    <qrcode-vue :value="ascentUrl" :size="160" level="H" render-as="svg" />
                                </div>
                                <p class="text-muted small">{{ $t('summit.summit_page.scan_instruction') }}</p>
                                <button @click="openAscentModal" class="btn btn-success btn-block btn-send main-btn">
                                    <i class="fa fa-flag"></i> {{ $t('summit.summit_page.record_ascent_short') }}
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <metaData
            v-if="summit"
            :title="summit.title + ' – Summit Climbing Georgia'"
            :description="summit.description || 'Explore summit details and record your ascent in Georgia.'"
            :image="summit.image ? '/storage/' + summit.image : '/public/images/meta_img/mountain.jpg'"
        />

        <make-ascent-modal
            v-model="showAscentModal"
            :summit="summit"
            @submitted="fetchAscents"
        />

        <ascent-detail-modal
            v-model="showAscentDetail"
            :ascent="selectedAscent"
        />
    </div>
</template>

<script>
import metaData from '../../items/MetaDataComponent.vue'
import QrcodeVue from 'qrcode.vue'
import MakeAscentModal from '../../items/Modals/MakeAscentModal.vue'
import AscentDetailModal from '../../items/Modals/AscentDetailModal.vue'

export default {
    name: 'SummitPage',
    components: { metaData, QrcodeVue, MakeAscentModal, AscentDetailModal },
    data() {
        return {
            summit: null,
            loading: true,
            ascents: [],
            loadingAscents: false,
            showAscentModal: false,
            selectedAscent: null,
            showAscentDetail: false,
        }
    },
    computed: {
        ascentUrl() {
            if (!this.summit) return ''
            const base = process.env.MIX_APP_SSH
                ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_SUMMIT_URL || '').replace(/^\/|\/$/g, '')
                : window.location.origin
            return `${base}/summit/${this.summit.url_title}?make_ascent=${this.summit.id}`
        }
    },
    watch: {
        '$route.query.make_ascent'(val) {
            if (val !== undefined && this.summit) {
                this.showAscentModal = true
            }
        },
        showAscentModal(val) {
            if (!val && 'make_ascent' in this.$route.query) {
                this.$router.replace({ query: {} })
            }
        },
    },
    mounted() {
        this.fetchSummit()
    },
    methods: {
        fetchSummit() {
            this.loading = true
            axios.get(`summit/show/${this.$route.params.url_title}`)
                .then(r => {
                    this.summit = r.data
                    this.fetchAscents()
                    if ('make_ascent' in this.$route.query) {
                        this.showAscentModal = true
                    }
                })
                .catch(() => { this.summit = null })
                .finally(() => { this.loading = false })
        },
        openAscentModal() {
            this.showAscentModal = true
            if (!('make_ascent' in this.$route.query)) {
                this.$router.replace({ query: { ...this.$route.query, make_ascent: this.summit.id } })
            }
        },
        openAscentDetail(ascent) {
            this.selectedAscent = ascent
            this.showAscentDetail = true
        },
        fetchAscents() {
            if (!this.summit) return
            this.loadingAscents = true
            axios.get(`summit/ascents/${this.summit.url_title}`)
                .then(r => { this.ascents = r.data.ascents || [] })
                .catch(() => {})
                .finally(() => { this.loadingAscents = false })
        },
        formatDate(d) {
            if (!d) return '—'
            return new Date(d).toLocaleDateString('en-GB', { year: 'numeric', month: 'short', day: 'numeric' })
        },
        guideRouteUrl(articleUrl) {
            const base = process.env.MIX_APP_SSH
                ? (process.env.MIX_APP_SSH || '').replace(/\/$/, '') + '/' + (process.env.MIX_SITE_URL || '').replace(/^\/|\/$/g, '')
                : window.location.origin
            return `${base}/outdoor/${articleUrl}`
        },
    }
}
</script>
