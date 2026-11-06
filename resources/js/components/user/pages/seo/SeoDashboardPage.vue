<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

            <div class="seo-dash">
                <header class="seo-dash__head">
                    <h1 class="h3 mb-1">{{ $t('admin.seo.title') }}</h1>
                    <p class="text-muted mb-0">{{ $t('admin.seo.subtitle') }}</p>
                </header>

                <!-- connection / sync status -->
                <div v-if="status" class="seo-dash__status">
                    <template v-if="status.configured">
                        <span v-if="status.last_sync && status.last_sync.ok" class="text-muted">
                            <i class="fa fa-check-circle text-success" aria-hidden="true"></i>
                            {{ $t('admin.seo.synced', { when: datetime(status.last_sync.at), to: date(status.last_sync.to) }) }}
                        </span>
                        <span v-else-if="status.last_sync" class="text-danger">
                            <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ $t('admin.seo.sync_failed') }}: {{ status.last_sync.error }}
                        </span>
                        <span v-else class="text-muted">{{ $t('admin.seo.never_synced') }}</span>
                        <button v-if="status.can_sync" type="button" class="btn btn-outline-secondary btn-sm ms-2" :disabled="syncing" @click="sync">
                            <i class="fa fa-refresh" :class="{ 'fa-spin': syncing }" aria-hidden="true"></i> {{ $t('admin.seo.sync_now') }}
                        </button>
                    </template>
                </div>

                <!-- not connected yet: how to set it up (the content list works anyway) -->
                <div v-if="status && !status.configured" class="seo-dash__setup">
                    <h2 class="h5">{{ $t('admin.seo.setup.title') }}</h2>
                    <ol>
                        <li v-for="n in 5" :key="n">{{ $t('admin.seo.setup.step_' + n) }}</li>
                    </ol>
                    <p class="text-muted small mb-0">{{ $t('admin.seo.setup.docs') }} <code>docs/FUNCTIONS_DOCS/user/seo_dashboard.md</code></p>
                </div>

                <!-- tabs + period -->
                <div class="seo-dash__bar">
                    <div class="btn-group" role="tablist">
                        <button v-for="t in tabs" :key="t" type="button" role="tab" class="btn btn-sm"
                            :class="tab === t ? 'btn-primary' : 'btn-outline-secondary'" :aria-selected="tab === t ? 'true' : 'false'" @click="set_tab(t)">
                            {{ $t('admin.seo.tab.' + t) }}
                        </button>
                    </div>
                    <div v-if="tab !== 'quality'" class="seo-dash__filters">
                        <select v-if="tab !== 'overview' && overview && overview.services" v-model="host" class="form-select form-select-sm" :aria-label="$t('admin.seo.service')">
                            <option value="">{{ $t('admin.seo.all_services') }}</option>
                            <option v-for="s in overview.services" :key="s.host" :value="s.host">{{ s.host }}</option>
                        </select>
                        <select v-model.number="days" class="form-select form-select-sm" :aria-label="$t('admin.seo.period')">
                            <option :value="7">{{ $t('admin.seo.last_days', { n: 7 }) }}</option>
                            <option :value="28">{{ $t('admin.seo.last_days', { n: 28 }) }}</option>
                            <option :value="90">{{ $t('admin.seo.last_days', { n: 90 }) }}</option>
                        </select>
                    </div>
                </div>

                <!-- Overview -->
                <section v-if="tab === 'overview'">
                    <div v-if="!overview" class="text-muted py-3">{{ $t('admin.seo.loading') }}</div>
                    <div v-else-if="!overview.has_data" class="text-muted py-3">{{ $t('admin.seo.no_data') }}</div>
                    <template v-else>
                        <p class="text-muted small">{{ $t('admin.seo.compared', { from: date(overview.window[0]), to: date(overview.window[1]), pfrom: date(overview.window[2]), pto: date(overview.window[3]) }) }}</p>
                        <div class="seo-tiles">
                            <div v-for="k in kpis" :key="k.key" class="seo-tile">
                                <div class="seo-tile__label">{{ $t('admin.seo.kpi.' + k.key) }}</div>
                                <div class="seo-tile__value">{{ k.format(overview.current[k.key]) }}</div>
                                <div class="seo-tile__delta" :class="trend_class(k)">
                                    <span aria-hidden="true">{{ arrow(k) }}</span> {{ delta_text(k) }} <span class="text-muted">{{ $t('admin.seo.vs_previous') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="seo-card">
                            <seo-trend-chart :points="overview.daily" value_key="clicks" :value_label="$t('admin.seo.kpi.clicks').toLowerCase()"
                                secondary_key="impressions" :secondary_label="$t('admin.seo.kpi.impressions').toLowerCase()" :title="$t('admin.seo.chart.clicks_per_day')" />
                        </div>

                        <div class="row g-3">
                            <div class="col-lg-7">
                                <div class="seo-card">
                                    <h2 class="h6">{{ $t('admin.seo.by_service') }}</h2>
                                    <table class="table table-sm mb-0 seo-num">
                                        <thead><tr><th>{{ $t('admin.seo.service') }}</th><th class="text-end">{{ $t('admin.seo.col.clicks') }}</th><th class="text-end">{{ $t('admin.seo.col.impressions') }}</th><th class="text-end">{{ $t('admin.seo.col.position') }}</th></tr></thead>
                                        <tbody>
                                            <tr v-for="s in overview.services" :key="s.host">
                                                <td><a href="#" @click.prevent="host = s.host; set_tab('pages')">{{ s.host }}</a></td>
                                                <td class="text-end">{{ number(s.clicks) }}</td>
                                                <td class="text-end">{{ number(s.impressions) }}</td>
                                                <td class="text-end">{{ s.position }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-lg-5">
                                <div class="seo-card">
                                    <h2 class="h6">{{ $t('admin.seo.by_language') }}</h2>
                                    <table class="table table-sm mb-0 seo-num">
                                        <thead><tr><th>{{ $t('admin.seo.language') }}</th><th class="text-end">{{ $t('admin.seo.col.clicks') }}</th><th class="text-end">{{ $t('admin.seo.col.impressions') }}</th></tr></thead>
                                        <tbody>
                                            <tr v-for="(v, l) in overview.languages" :key="l">
                                                <td>{{ l === 'ka' ? 'ქართული (/ka)' : 'English' }}</td>
                                                <td class="text-end">{{ number(v.clicks) }}</td>
                                                <td class="text-end">{{ number(v.impressions) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </template>
                </section>

                <!-- Opportunities: what to fix first -->
                <section v-if="tab === 'opportunities'">
                    <div class="btn-group mb-3" role="group" :aria-label="$t('admin.seo.tab.opportunities')">
                        <button v-for="f in ['striking', 'low_ctr', 'losing']" :key="f" type="button" class="btn btn-sm"
                            :class="opportunity === f ? 'btn-primary' : 'btn-outline-secondary'" @click="opportunity = f">
                            {{ $t('admin.seo.flag.' + f) }}
                        </button>
                    </div>
                    <seo-pages-table :days="days" :filter="opportunity" :host="host" />
                </section>

                <section v-if="tab === 'pages'">
                    <seo-pages-table :days="days" filter="all" :host="host" />
                </section>

                <section v-if="tab === 'quality'">
                    <content-quality-list />
                </section>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../items/BreadcrumbComponent.vue'
    import SeoTrendChart from './assets/SeoTrendChart.vue'
    import SeoPagesTable from './assets/SeoPagesTable.vue'
    import ContentQualityList from './assets/ContentQualityList.vue'

    const TABS = ['overview', 'opportunities', 'pages', 'quality']

    export default {
        components: { breadcrumb, SeoTrendChart, SeoPagesTable, ContentQualityList },
        data() {
            const tab = TABS.includes(this.$route.query.tab) ? this.$route.query.tab : 'overview'
            return {
                tabs: TABS, tab, days: 28, host: '', opportunity: 'striking',
                status: null, overview: null, syncing: false,
            }
        },
        computed: {
            // up is good for everything except position (lower = better)
            kpis() {
                return [
                    { key: 'clicks', format: (v) => this.number(v), up_good: true },
                    { key: 'impressions', format: (v) => this.number(v), up_good: true },
                    { key: 'ctr', format: (v) => v + '%', up_good: true, unit: ' pp' },
                    { key: 'position', format: (v) => v, up_good: false },
                ]
            },
        },
        watch: {
            days() { this.load_overview() },
        },
        mounted() {
            this.load_status()
            this.load_overview()
        },
        methods: {
            set_tab(t) {
                this.tab = t
                this.$router.replace({ query: { ...this.$route.query, tab: t } })
            },
            load_status() {
                axios.get('set_seo/status').then((res) => { this.status = res.data })
            },
            load_overview() {
                this.overview = null
                axios.get('set_seo/overview', { params: { days: this.days } })
                    .then((res) => { this.overview = res.data })
                    .catch(() => { this.overview = { has_data: false } })
            },
            sync() {
                this.syncing = true
                axios.post('set_seo/sync')
                    .then(() => { this.load_overview() })
                    .catch(() => {})
                    .finally(() => { this.syncing = false; this.load_status() })
            },
            change(k) {
                const c = this.overview.current[k.key], p = this.overview.previous[k.key]
                return Math.round((c - p) * 100) / 100
            },
            trend_class(k) {
                const d = this.change(k)
                if (!d) return 'is-flat'
                return (d > 0) === k.up_good ? 'is-good' : 'is-bad'
            },
            arrow(k) { const d = this.change(k); return d > 0 ? '▲' : d < 0 ? '▼' : '•' },
            delta_text(k) {
                const d = this.change(k), p = this.overview.previous[k.key]
                if (k.key === 'position' || k.key === 'ctr') return (d > 0 ? '+' : d < 0 ? '−' : '±') + Math.abs(d) + (k.unit || '')
                if (!p) return d ? '+' + this.number(d) : '±0'
                return (d > 0 ? '+' : d < 0 ? '−' : '±') + Math.abs(Math.round(d / p * 100)) + '%'
            },
            locale() { return this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB' },
            number(n) { return new Intl.NumberFormat(this.locale()).format(n || 0) },
            date(d) { return d ? new Intl.DateTimeFormat(this.locale(), { day: 'numeric', month: 'short', year: 'numeric' }).format(new Date(d)) : '' },
            datetime(d) { return d ? new Intl.DateTimeFormat(this.locale(), { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' }).format(new Date(d)) : '' },
        },
    }
</script>

<style scoped>
    .seo-dash { padding: 0 0 2rem; }
    .seo-dash__head { margin-bottom: .75rem; }
    .seo-dash__status { margin-bottom: 1rem; font-size: .9rem; }
    .seo-dash__setup { padding: 1rem 1.25rem; margin-bottom: 1rem; background: var(--cg-accent-soft); border-radius: var(--cg-r); }
    .seo-dash__setup ol { margin-bottom: .5rem; padding-left: 1.25rem; }
    .seo-dash__bar { display: flex; flex-wrap: wrap; gap: .75rem; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
    .seo-dash__filters { display: flex; gap: .5rem; }
    .seo-dash__filters .form-select { width: auto; }
    .seo-tiles { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: .75rem; margin-bottom: 1rem; }
    .seo-tile, .seo-card { padding: 1rem; background: var(--cg-surface, #fff); border: 1px solid var(--cg-line); border-radius: var(--cg-r); }
    .seo-card { margin-bottom: 1rem; }
    .seo-tile__label { font-size: .85rem; color: var(--cg-muted); }
    .seo-tile__value { font-size: 1.75rem; font-weight: 600; color: var(--cg-ink); line-height: 1.2; }
    .seo-tile__delta { font-size: .82rem; }
    .seo-tile__delta.is-good { color: var(--bs-success-text-emphasis); }
    .seo-tile__delta.is-bad { color: var(--bs-danger-text-emphasis); }
    .seo-tile__delta.is-flat { color: var(--cg-muted); }
    .seo-num td, .seo-num th { font-variant-numeric: tabular-nums; }
</style>
