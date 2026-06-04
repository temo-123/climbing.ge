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

            <!-- ── Hero ── -->
            <div class="h-recent-work">
                <div class="container">

                    <h1 class="index_h2">{{ summit.title }}</h1>
                    <p v-if="summit.ka_title" class="text-muted text-center" style="font-size:1.1rem; margin-top:-10px; margin-bottom:10px">{{ summit.ka_title }}</p>

                    <div class="bar"><i class="fa fa-flag-checkered"></i></div>

                    <!-- Badges -->
                    <div class="text-center" style="margin-bottom:24px">
                        <span v-if="summit.height" class="label label-default summit-badge">
                            <i class="fa fa-arrow-up"></i> {{ summit.height }}m
                        </span>
                        <span v-if="summit.region" class="label label-default summit-badge">
                            <i class="fa fa-map-marker"></i> {{ summit.region.us_name }}
                        </span>
                        <span v-if="summit.latitude && summit.longitude" class="label label-default summit-badge">
                            <i class="fa fa-crosshairs"></i>
                            {{ parseFloat(summit.latitude).toFixed(4) }}°, {{ parseFloat(summit.longitude).toFixed(4) }}°
                        </span>
                        <a v-if="summit.latitude && summit.longitude"
                           :href="mapsUrl" target="_blank" rel="noopener"
                           class="label label-success summit-badge"
                           style="cursor:pointer; text-decoration:none">
                            <i class="fa fa-map"></i> Google Maps
                        </a>
                    </div>

                    <!-- Description -->
                    <div v-if="summit.description" class="row">
                        <div class="col-md-8 col-md-offset-2">
                            <p class="article_list_short_description" style="font-size:15px; line-height:1.8">
                                {{ summit.description }}
                            </p>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="text-center" style="margin: 20px 0 10px">
                        <button @click="openAscentModal" class="btn btn-success btn-lg btn-send main-btn">
                            <i class="fa fa-flag-checkered"></i> {{ $t('summit.summit_page.record_ascent') }}
                        </button>
                    </div>

                </div>
            </div>

            <!-- ── Stats row ── -->
            <div class="h-recent-work" v-if="!loadingAscents">
                <div class="container">
                    <div class="row summit-stats-row">
                        <div class="col-xs-6 col-md-3">
                            <div class="summit-stat-card">
                                <i class="fa fa-flag-checkered summit-stat-icon"></i>
                                <div class="summit-stat-number">{{ allAscents.length }}</div>
                                <div class="summit-stat-label">{{ $t('summit.summit_page.ascents') }}</div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-3">
                            <div class="summit-stat-card">
                                <i class="fa fa-check-circle summit-stat-icon text-success"></i>
                                <div class="summit-stat-number">{{ gpsVerifiedCount }}</div>
                                <div class="summit-stat-label">{{ $t('summit.ascent_page.gps_verified') }}</div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-3">
                            <div class="summit-stat-card">
                                <i class="fa fa-users summit-stat-icon"></i>
                                <div class="summit-stat-number">{{ uniqueClimbersCount }}</div>
                                <div class="summit-stat-label">Climbers</div>
                            </div>
                        </div>
                        <div class="col-xs-6 col-md-3">
                            <div class="summit-stat-card">
                                <i class="fa fa-calendar summit-stat-icon"></i>
                                <div class="summit-stat-number" style="font-size:1rem">{{ peakMonth }}</div>
                                <div class="summit-stat-label">Peak month</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Routes ── -->
            <div class="h-recent-work" v-if="routes.length > 0">
                <div class="container">
                    <h2 class="index_h2" style="font-size:180%">
                        <i class="fa fa-road"></i> Climbing Routes
                    </h2>
                    <div class="row" style="margin-top:20px">
                        <div v-for="r in routes" :key="r.id" class="col-md-4 col-sm-6" style="margin-bottom:16px">
                            <div class="summit-route-card">
                                <span class="summit-route-name">{{ r.name }}</span>
                                <span v-if="r.grade" class="label label-success ml-1">{{ r.grade }}</span>
                                <a v-if="r.url_title" :href="guideRouteUrl(r.url_title)" target="_blank" class="summit-route-link">
                                    <i class="fa fa-external-link"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Monthly activity chart ── -->
            <div class="h-recent-work" v-if="monthlyChartData.length > 0">
                <div class="container">
                    <h2 class="index_h2" style="font-size:180%">
                        <i class="fa fa-bar-chart"></i> Ascent Activity
                    </h2>
                    <div class="summit-chart" style="margin-top:20px">
                        <div v-for="bar in monthlyChartData" :key="bar.label" class="summit-chart-bar-wrap">
                            <div class="summit-chart-bar-outer">
                                <div class="summit-chart-bar-inner" :style="{ height: bar.pct + '%' }">
                                    <span class="summit-chart-count">{{ bar.count }}</span>
                                </div>
                            </div>
                            <div class="summit-chart-label">{{ bar.label }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── Ascents + QR ── -->
            <div class="h-recent-work">
                <div class="container">
                    <div class="row">

                        <!-- Ascent list -->
                        <div class="col-md-8">
                            <h2 class="index_h2" style="font-size:180%">
                                <i class="fa fa-list"></i> {{ $t('summit.summit_page.ascents') }}
                                <span v-if="allAscents.length" class="badge" style="background:#1d7a48">{{ allAscents.length }}</span>
                            </h2>

                            <div v-if="loadingAscents" class="text-center py-4">
                                <i class="fa fa-spinner fa-spin fa-2x"></i>
                            </div>

                            <template v-else-if="allAscents.length > 0">
                                <!-- Year / Month filters -->
                                <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:16px; align-items:center;">
                                    <div>
                                        <select class="form-control" style="min-width:110px" v-model="filterYear" @change="onYearChange">
                                            <option :value="null">All time</option>
                                            <option v-for="y in availableYears" :key="y" :value="y">{{ y }}</option>
                                        </select>
                                    </div>
                                    <div v-if="filterYear !== null">
                                        <select class="form-control" style="min-width:120px" v-model.number="filterMonth">
                                            <option v-for="m in availableMonths" :key="m.value" :value="m.value">{{ m.label }}</option>
                                        </select>
                                    </div>
                                    <span class="text-muted small">{{ filteredAscents.length }} ascent(s)</span>
                                </div>

                                <div v-if="filteredAscents.length === 0" class="text-center py-4 text-muted">
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
                                            <tr v-for="a in filteredAscents" :key="a.id"
                                                style="cursor:pointer"
                                                @click="openAscentDetail(a)">
                                                <td>
                                                    <img v-if="a.photo" :src="'/storage/' + a.photo" class="summit-climber-avatar" />
                                                    <i v-else class="fa fa-user-circle summit-climber-avatar-placeholder"></i>
                                                    <strong>{{ a.name }} {{ a.surname }}</strong>
                                                </td>
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
                            </template>

                            <div v-else class="text-center py-4 text-muted">
                                <i class="fa fa-flag fa-2x mb-2"></i>
                                <p>{{ $t('summit.summit_page.no_ascents') }}</p>
                            </div>
                        </div>

                        <!-- QR + top climbers sidebar -->
                        <div class="col-md-4">
                            <div class="portfolio-img" style="padding:20px; text-align:center">
                                <h4 class="text-muted text-uppercase" style="letter-spacing:.05em; font-size:12px; margin-bottom:16px">
                                    <i class="fa fa-qrcode"></i> {{ $t('summit.summit_page.scan_to_record') }}
                                </h4>
                                <div class="justify-content-center mb-3" style="text-align: center;">
                                    <qrcode-vue :value="ascentUrl" :size="160" level="H" render-as="svg" />
                                </div>
                                <p class="text-muted small">{{ $t('summit.summit_page.scan_instruction') }}</p>
                                <button @click="openAscentModal" class="btn btn-success btn-block btn-send main-btn">
                                    <i class="fa fa-flag"></i> {{ $t('summit.summit_page.record_ascent_short') }}
                                </button>
                            </div>

                            <!-- Top climbers -->
                            <div v-if="topClimbers.length > 0" style="margin-top:20px; padding:16px; background:#f9f9f9; border-radius:8px">
                                <h5 style="margin-top:0; margin-bottom:12px; font-size:13px; text-transform:uppercase; letter-spacing:.05em; color:#666">
                                    <i class="fa fa-trophy" style="color:#f0a500"></i> Top Climbers
                                </h5>
                                <div v-for="(c, i) in topClimbers" :key="i" class="summit-top-climber">
                                    <span class="summit-top-climber-rank">{{ i + 1 }}</span>
                                    <span class="summit-top-climber-name">{{ c.name }}</span>
                                    <span class="summit-top-climber-count">{{ c.count }}×</span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- ── Photo gallery ── -->
            <div class="h-recent-work" v-if="ascentPhotos.length > 0">
                <div class="container">
                    <h2 class="index_h2" style="font-size:180%">
                        <i class="fa fa-camera"></i> Photos from the Summit
                    </h2>
                    <div class="summit-photo-grid" style="margin-top:20px">
                        <div v-for="p in ascentPhotos" :key="p.id" class="summit-photo-item">
                            <img :src="'/storage/' + p.photo" :alt="p.name + ' ' + p.surname" @click="openPhotoLightbox(p)" />
                            <div class="summit-photo-caption">{{ p.name }} · {{ formatDate(p.ascent_date) }}</div>
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

        <!-- Lightbox -->
        <div v-if="lightboxPhoto" class="summit-lightbox" @click="lightboxPhoto = null">
            <div class="summit-lightbox-inner" @click.stop>
                <img :src="'/storage/' + lightboxPhoto.photo" />
                <div class="summit-lightbox-caption">
                    {{ lightboxPhoto.name }} {{ lightboxPhoto.surname }} · {{ formatDate(lightboxPhoto.ascent_date) }}
                </div>
                <button class="summit-lightbox-close" @click="lightboxPhoto = null">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import metaData from '../../items/MetaDataComponent.vue'
import QrcodeVue from 'qrcode.vue'
import MakeAscentModal from '../../items/Modals/MakeAscentModal.vue'
import AscentDetailModal from '../../items/Modals/AscentDetailModal.vue'

const MONTH_NAMES = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']

export default {
    name: 'SummitPage',
    components: { metaData, QrcodeVue, MakeAscentModal, AscentDetailModal },
    data() {
        return {
            summit: null,
            loading: true,
            allAscents: [],
            loadingAscents: false,
            routes: [],
            showAscentModal: false,
            selectedAscent: null,
            showAscentDetail: false,
            filterYear: null,
            filterMonth: null,
            lightboxPhoto: null,
        }
    },
    computed: {
        mapsUrl() {
            if (!this.summit?.latitude || !this.summit?.longitude) return ''
            return `https://www.google.com/maps/dir/?api=1&destination=${this.summit.latitude},${this.summit.longitude}`
        },
        gpsVerifiedCount() {
            return this.allAscents.filter(a => a.is_gps_validated).length
        },
        uniqueClimbersCount() {
            const seen = new Set(this.allAscents.map(a => (a.name + '|' + a.surname).toLowerCase()))
            return seen.size
        },
        peakMonth() {
            if (!this.allAscents.length) return '—'
            const counts = {}
            this.allAscents.forEach(a => {
                if (!a.ascent_date) return
                const m = new Date(a.ascent_date).getMonth()
                if (!isNaN(m)) counts[m] = (counts[m] || 0) + 1
            })
            const peak = Object.entries(counts).sort((a, b) => b[1] - a[1])[0]
            return peak ? MONTH_NAMES[peak[0]] : '—'
        },
        topClimbers() {
            const counts = {}
            this.allAscents.forEach(a => {
                const key = a.name + ' ' + a.surname
                counts[key] = (counts[key] || 0) + 1
            })
            return Object.entries(counts)
                .sort((a, b) => b[1] - a[1])
                .slice(0, 5)
                .map(([name, count]) => ({ name, count }))
        },
        monthlyChartData() {
            if (!this.allAscents.length) return []
            const counts = {}
            this.allAscents.forEach(a => {
                if (!a.ascent_date) return
                const d = new Date(a.ascent_date)
                if (isNaN(d)) return
                const key = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`
                counts[key] = (counts[key] || 0) + 1
            })
            const sorted = Object.entries(counts).sort((a, b) => a[0].localeCompare(b[0]))
            if (sorted.length < 2) return []
            const max = Math.max(...sorted.map(e => e[1]))
            return sorted.map(([key, count]) => {
                const [year, month] = key.split('-')
                return {
                    label: MONTH_NAMES[parseInt(month) - 1] + ' ' + year.slice(2),
                    count,
                    pct: Math.round((count / max) * 100),
                }
            })
        },
        ascentPhotos() {
            return this.allAscents.filter(a => a.photo).slice(0, 12)
        },
        availableYears() {
            const years = [...new Set(
                this.allAscents
                    .filter(a => a.ascent_date)
                    .map(a => new Date(a.ascent_date).getFullYear())
                    .filter(y => !isNaN(y))
            )]
            return years.sort((a, b) => b - a)
        },
        availableMonths() {
            if (!this.filterYear) return []
            const months = [...new Set(
                this.allAscents
                    .filter(a => a.ascent_date && new Date(a.ascent_date).getFullYear() === this.filterYear)
                    .map(a => new Date(a.ascent_date).getMonth() + 1)
            )].sort((a, b) => b - a)
            return months.map(m => ({ value: m, label: MONTH_NAMES[m - 1] }))
        },
        filteredAscents() {
            if (this.filterYear === null) return this.allAscents
            return this.allAscents.filter(a => {
                if (!a.ascent_date) return false
                const d = new Date(a.ascent_date)
                return d.getFullYear() === this.filterYear && (d.getMonth() + 1) === this.filterMonth
            })
        },
        ascentUrl() {
            const ssh = process.env.MIX_APP_SSH || 'http://'
            const summitHost = process.env.MIX_SUMMIT_URL || ''
            const base = ssh + summitHost
            return `${base}/summit/${this.summit?.url_title}?make_ascent=${this.summit?.id}`
        },
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
                    this.fetchRoutes()
                    if ('make_ascent' in this.$route.query) {
                        this.showAscentModal = true
                    }
                })
                .catch(() => { this.summit = null })
                .finally(() => { this.loading = false })
        },
        fetchRoutes() {
            if (!this.summit) return
            axios.get(`summit/routes/${this.summit.id}`)
                .then(r => { this.routes = r.data || [] })
                .catch(() => {})
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
        openPhotoLightbox(photo) {
            this.lightboxPhoto = photo
        },
        fetchAscents() {
            if (!this.summit) return
            this.loadingAscents = true
            axios.get(`summit/ascents/${this.summit.url_title}`)
                .then(r => {
                    this.allAscents = r.data.ascents || []
                    this.setSmartDefaults()
                })
                .catch(() => {})
                .finally(() => { this.loadingAscents = false })
        },
        setSmartDefaults() {
            const valid = this.allAscents.filter(a => a.ascent_date && !isNaN(new Date(a.ascent_date)))
            if (!valid.length) return

            const now = new Date()
            const curYear  = now.getFullYear()
            const curMonth = now.getMonth() + 1

            const hasCurrentMonth = valid.some(a => {
                const d = new Date(a.ascent_date)
                return d.getFullYear() === curYear && (d.getMonth() + 1) === curMonth
            })

            if (hasCurrentMonth) {
                this.filterYear  = curYear
                this.filterMonth = curMonth
                return
            }

            const sorted = valid
                .map(a => new Date(a.ascent_date))
                .sort((a, b) => b - a)
            const nearest = sorted[0]
            this.filterYear  = nearest.getFullYear()
            this.filterMonth = nearest.getMonth() + 1
        },
        onYearChange() {
            const months = this.availableMonths.map(m => m.value)
            if (!months.includes(this.filterMonth)) {
                this.filterMonth = months[0] ?? null
            }
        },
        formatDate(d) {
            if (!d) return '—'
            return new Date(d).toLocaleDateString('en-GB', { year: 'numeric', month: 'short', day: 'numeric' })
        },
        guideRouteUrl(articleUrl) {
            const ssh = process.env.MIX_APP_SSH || 'http://'
            const siteHost = process.env.MIX_GUIDBOOK_URL || process.env.MIX_SITE_URL || ''
            return `${ssh}${siteHost}/outdoor/${articleUrl}`
        },
    }
}
</script>

<style scoped>
.summit-badge {
    font-size: 13px;
    margin: 2px;
    display: inline-block;
}

/* ── Stats ── */
.summit-stats-row {
    margin-bottom: 10px;
}
.summit-stat-card {
    text-align: center;
    padding: 20px 10px;
    background: #f8f8f8;
    border-radius: 8px;
    margin-bottom: 16px;
}
.summit-stat-icon {
    font-size: 24px;
    margin-bottom: 8px;
    display: block;
    color: #1d7a48;
}
.summit-stat-number {
    font-size: 1.8rem;
    font-weight: bold;
    line-height: 1.1;
}
.summit-stat-label {
    font-size: 12px;
    color: #888;
    text-transform: uppercase;
    letter-spacing: .04em;
    margin-top: 4px;
}

/* ── Routes ── */
.summit-route-card {
    background: #f8f8f8;
    border-radius: 6px;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.summit-route-name {
    flex: 1;
    font-weight: 500;
}
.summit-route-link {
    color: #888;
    font-size: 12px;
}

/* ── Activity chart ── */
.summit-chart {
    display: flex;
    align-items: flex-end;
    gap: 6px;
    height: 120px;
    overflow-x: auto;
    padding-bottom: 4px;
}
.summit-chart-bar-wrap {
    display: flex;
    flex-direction: column;
    align-items: center;
    min-width: 36px;
    flex: 1;
}
.summit-chart-bar-outer {
    width: 100%;
    height: 90px;
    display: flex;
    align-items: flex-end;
}
.summit-chart-bar-inner {
    width: 100%;
    background: #1d7a48;
    border-radius: 3px 3px 0 0;
    min-height: 4px;
    display: flex;
    align-items: flex-start;
    justify-content: center;
    transition: height 0.3s;
}
.summit-chart-count {
    font-size: 10px;
    color: #fff;
    padding-top: 2px;
}
.summit-chart-label {
    font-size: 9px;
    color: #888;
    margin-top: 4px;
    text-align: center;
    white-space: nowrap;
}

/* ── Ascent table avatar ── */
.summit-climber-avatar {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 6px;
    vertical-align: middle;
}
.summit-climber-avatar-placeholder {
    font-size: 22px;
    color: #ccc;
    margin-right: 6px;
    vertical-align: middle;
}

/* ── Top climbers ── */
.summit-top-climber {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 5px 0;
    border-bottom: 1px solid #eee;
    font-size: 13px;
}
.summit-top-climber:last-child { border-bottom: none; }
.summit-top-climber-rank {
    width: 20px;
    height: 20px;
    line-height: 20px;
    border-radius: 50%;
    background: #1d7a48;
    color: #fff;
    font-size: 11px;
    text-align: center;
    font-weight: bold;
    flex-shrink: 0;
}
.summit-top-climber-name { flex: 1; }
.summit-top-climber-count { color: #888; font-size: 12px; }

/* ── Photo grid ── */
.summit-photo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 10px;
}
.summit-photo-item {
    border-radius: 6px;
    overflow: hidden;
    position: relative;
    cursor: pointer;
}
.summit-photo-item img {
    width: 100%;
    height: 140px;
    object-fit: cover;
    display: block;
    transition: transform 0.2s;
}
.summit-photo-item:hover img { transform: scale(1.04); }
.summit-photo-caption {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: rgba(0,0,0,0.55);
    color: #fff;
    font-size: 11px;
    padding: 4px 6px;
}

/* ── Lightbox ── */
.summit-lightbox {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.88);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.summit-lightbox-inner {
    position: relative;
    max-width: 90vw;
    max-height: 90vh;
    text-align: center;
}
.summit-lightbox-inner img {
    max-width: 100%;
    max-height: 80vh;
    border-radius: 6px;
    display: block;
    margin: 0 auto;
}
.summit-lightbox-caption {
    color: #fff;
    font-size: 13px;
    margin-top: 10px;
}
.summit-lightbox-close {
    position: absolute;
    top: -12px;
    right: -12px;
    background: #fff;
    border: none;
    border-radius: 50%;
    width: 28px;
    height: 28px;
    cursor: pointer;
    font-size: 14px;
    line-height: 28px;
    padding: 0;
}
</style>
