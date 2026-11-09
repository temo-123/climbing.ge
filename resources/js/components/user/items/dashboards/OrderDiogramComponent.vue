<template>
    <div class="order-dash">

        <!-- Filters: one row above the charts -->
        <div class="order-dash__filters">
            <div class="btn-group flex-wrap" role="group" :aria-label="$t('admin.dashboards.period_label')">
                <button
                    v-for="p in periods" :key="p.value"
                    type="button"
                    class="btn btn-sm"
                    :class="selectedPeriod === p.value ? 'btn-primary' : 'btn-outline-secondary'"
                    :disabled="loading"
                    @click="setPeriod(p.value)"
                >{{ p.label }}</button>
            </div>
            <button class="btn btn-sm btn-outline-secondary ms-auto" @click="fetchAll" :disabled="loading" :title="$t('common.refresh')">
                <i class="fa fa-refresh" :class="{ 'fa-spin': loading }"></i>
            </button>
        </div>

        <div v-if="error" class="alert alert-danger py-2">
            {{ $t('admin.dashboards.stats_load_failed') }}
            <button class="btn btn-sm btn-outline-danger ms-2" @click="fetchAll">{{ $t('common.retry') }}</button>
        </div>

        <template v-else-if="data">
            <!-- Headline numbers -->
            <div class="order-dash__tiles" :class="{ 'is-loading': loading }">
                <div v-for="t in tiles" :key="t.key" class="order-dash__tile">
                    <div class="order-dash__tile-label">{{ t.label }}</div>
                    <div class="order-dash__tile-value">{{ t.value }}</div>
                    <div class="order-dash__tile-sub">
                        <span v-if="t.change !== null" class="order-dash__change" :class="t.change > 0 ? 'is-up' : (t.change < 0 ? 'is-down' : '')">
                            <i :class="'fa ' + (t.change > 0 ? 'fa-arrow-up' : (t.change < 0 ? 'fa-arrow-down' : 'fa-minus'))" aria-hidden="true"></i>
                            {{ t.change === Infinity ? $t('admin.dashboards.change_new') : Math.abs(t.change) + '%' }}
                        </span>
                        <span>{{ t.sub }}</span>
                    </div>
                </div>
            </div>

            <div class="order-dash__note">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                {{ $t('admin.dashboards.counted_note') }}
                <template v-if="s.awaiting || s.failed || s.cancelled">
                    {{ $t('admin.dashboards.excluded_note', { awaiting: s.awaiting, failed: s.failed + s.cancelled }) }}
                </template>
            </div>

            <div v-if="!s.orders" class="order-dash__empty">
                <i class="fa fa-bar-chart" aria-hidden="true"></i>
                {{ $t('admin.dashboards.no_orders_in_period') }}
            </div>

            <div v-else class="row g-3" :class="{ 'is-loading': loading }">
                <div class="col-lg-6">
                    <div class="order-dash__card">
                        <div class="order-dash__card-head">
                            <h6 class="mb-0">{{ $t('admin.dashboards.quantity_diagram_title') }}</h6>
                            <span class="small text-muted">{{ granularity_label }}</span>
                        </div>
                        <GChart type="ColumnChart" :data="orders_chart" :options="orders_options" class="order-dash__chart" />
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="order-dash__card">
                        <div class="order-dash__card-head">
                            <h6 class="mb-0">{{ $t('admin.dashboards.finance_diagram_title') }}</h6>
                            <span class="small text-muted">{{ granularity_label }}</span>
                        </div>
                        <GChart type="ColumnChart" :data="revenue_chart" :options="revenue_options" class="order-dash__chart" />
                    </div>
                </div>
            </div>

            <!-- Table view (same numbers, for reading exact values) -->
            <div v-if="s.orders" class="mt-2">
                <button type="button" class="btn btn-link btn-sm px-0" @click="show_table = !show_table">
                    <i :class="'fa ' + (show_table ? 'fa-chevron-up' : 'fa-table')"></i>
                    {{ show_table ? $t('admin.dashboards.hide_table') : $t('admin.dashboards.show_table') }}
                </button>
                <div v-if="show_table" class="table-responsive order-dash__table">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>{{ $t('admin.dashboards.chart_period_axis') }}</th>
                                <th class="text-end">{{ $t('admin.dashboards.series_website') }}</th>
                                <th class="text-end">{{ $t('admin.dashboards.series_manual') }}</th>
                                <th class="text-end">{{ $t('admin.dashboards.chart_orders_col') }}</th>
                                <th class="text-end">{{ $t('admin.dashboards.items_sold') }}</th>
                                <th class="text-end">{{ $t('admin.dashboards.chart_revenue_col') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="b in nonEmptyBuckets" :key="b.key">
                                <td>{{ bucket_label(b, true) }}</td>
                                <td class="text-end">{{ b.website }}</td>
                                <td class="text-end">{{ b.manual }}</td>
                                <td class="text-end fw-semibold">{{ b.orders }}</td>
                                <td class="text-end">{{ b.items }}</td>
                                <td class="text-end fw-semibold">{{ money(b.revenue) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </template>

        <div v-else class="order-dash__empty">
            <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
            {{ $t('admin.dashboards.loading_ellipsis_dots') }}
        </div>
    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'

    // Validated categorical pair (dataviz palette slots 1–2, light + dark pass).
    const SERIES = { light: ['#2a78d6', '#eb6834'], dark: ['#3987e5', '#d95926'] }

    // Shop dashboard: orders + revenue per day / week / month.
    // Data: GET get_order/get_order_dashboard/{period} (OrderStatisticsService) —
    // zero-filled buckets, real order totals, unconfirmed / failed / cancelled left out.
    export default {
        components: { GChart },
        data() {
            return {
                selectedPeriod: '7days',
                periods: [
                    { value: 'today',   label: this.$t('admin.dashboards.period_today') },
                    { value: '7days',   label: this.$t('admin.dashboards.period_last_7_days') },
                    { value: '30days',  label: this.$t('admin.dashboards.period_last_30_days') },
                    { value: '3months', label: this.$t('admin.dashboards.period_last_3_months') },
                    { value: '6months', label: this.$t('admin.dashboards.period_last_6_months') },
                    { value: '1year',   label: this.$t('admin.dashboards.period_last_year') },
                    { value: '2years',  label: this.$t('admin.dashboards.period_last_2_years') },
                    { value: 'all',     label: this.$t('admin.dashboards.period_all_time') },
                ],
                loading: false,
                error: false,
                data: null,
                show_table: false,
                seq: 0,
            }
        },
        computed: {
            s() { return this.data?.summary || {} },
            dark() {
                const t = document.documentElement.getAttribute('data-bs-theme') || document.body.getAttribute('data-bs-theme')
                return t === 'dark'
            },
            colors() { return this.dark ? SERIES.dark : SERIES.light },
            text_color() {
                return getComputedStyle(document.body).getPropertyValue('--bs-secondary-color').trim() || '#52514e'
            },
            grid_color() {
                return getComputedStyle(document.body).getPropertyValue('--bs-border-color').trim() || '#e5e5e5'
            },
            locale() { return this.$i18n?.locale === 'ka' ? 'ka-GE' : 'en-GB' },
            granularity_label() {
                return this.$t('admin.dashboards.per_' + (this.data?.granularity || 'day'))
            },
            nonEmptyBuckets() {
                return (this.data?.buckets || []).filter(b => b.orders > 0).slice().reverse()
            },
            tiles() {
                const s = this.s, prev = s.previous
                const change = (now, before) => {
                    if (!prev) return null
                    if (!before) return now ? Infinity : 0
                    return Math.round((now - before) / before * 100)
                }
                const vs = prev ? this.$t('admin.dashboards.vs_previous') : ''
                return [
                    { key: 'orders', label: this.$t('admin.dashboards.chart_orders_col'), value: s.orders ?? 0,
                      change: change(s.orders, prev?.orders), sub: this.$t('admin.dashboards.orders_split', { website: s.website ?? 0, manual: s.manual ?? 0 }) },
                    { key: 'revenue', label: this.$t('admin.dashboards.chart_revenue_col'), value: this.money(s.revenue),
                      change: change(s.revenue, prev?.revenue), sub: vs },
                    { key: 'avg', label: this.$t('admin.dashboards.avg_order'), value: this.money(s.avg_order), change: null, sub: '' },
                    { key: 'items', label: this.$t('admin.dashboards.items_sold'), value: s.items ?? 0, change: null, sub: '' },
                ]
            },
            orders_chart() {
                const rows = (this.data?.buckets || []).map(b => [this.bucket_label(b), b.website, b.manual])
                return [[this.$t('admin.dashboards.chart_period_axis'), this.$t('admin.dashboards.series_website'), this.$t('admin.dashboards.series_manual')], ...rows]
            },
            revenue_chart() {
                const cur = this.data?.currency || '₾'
                const rows = (this.data?.buckets || []).map(b => [
                    this.bucket_label(b),
                    { v: b.revenue, f: b.revenue.toFixed(2) + ' ' + cur },
                ])
                return [[this.$t('admin.dashboards.chart_period_axis'), this.$t('admin.dashboards.chart_revenue_col')], ...rows]
            },
            base_options() {
                const text = { color: this.text_color, fontSize: 11 }
                return {
                    backgroundColor: 'transparent',
                    chartArea: { left: 48, right: 12, top: 28, bottom: 40, width: '100%', height: '100%' },
                    bar: { groupWidth: '72%' },
                    focusTarget: 'category',      // hover a period → one tooltip with all its values
                    tooltip: { textStyle: { fontSize: 12 } },
                    hAxis: { textStyle: text, slantedText: false, maxAlternation: 1, showTextEvery: this.label_step },
                    vAxis: {
                        textStyle: text, minValue: 0, viewWindow: { min: 0 },
                        gridlines: { color: this.grid_color, count: 4 }, minorGridlines: { count: 0 },
                        baselineColor: this.grid_color,
                    },
                    animation: { duration: 250, easing: 'out', startup: true },
                }
            },
            orders_options() {
                return {
                    ...this.base_options,
                    isStacked: true,
                    colors: this.colors,
                    legend: { position: 'top', alignment: 'end', textStyle: { color: this.text_color, fontSize: 12 } },
                    vAxis: { ...this.base_options.vAxis, format: '#' },
                }
            },
            revenue_options() {
                return {
                    ...this.base_options,
                    colors: [this.colors[0]],
                    legend: 'none',
                    vAxis: { ...this.base_options.vAxis, format: 'short' },
                }
            },
            // Thin out x labels so they never collide (~8 visible).
            label_step() {
                const n = this.data?.buckets?.length || 1
                return Math.max(1, Math.ceil(n / 8))
            },
        },
        mounted() {
            this.fetchAll()
        },
        methods: {
            setPeriod(p) {
                if (p === this.selectedPeriod) return
                this.selectedPeriod = p
                this.fetchAll()
            },
            fetchAll() {
                const seq = ++this.seq
                this.loading = true
                this.error = false
                axios.get('get_order/get_order_dashboard/' + this.selectedPeriod)
                    .then(r => { if (seq === this.seq) this.data = r.data })
                    .catch(e => {
                        console.error('Order statistics error:', e)
                        if (seq === this.seq) this.error = true
                    })
                    .finally(() => { if (seq === this.seq) this.loading = false })
            },
            bucket_label(b, long = false) {
                const g = this.data?.granularity
                if (g === 'hour') {
                    const t = new Date(b.from)
                    return new Intl.DateTimeFormat(this.locale, { hour: '2-digit', minute: '2-digit', hour12: false }).format(t)
                }
                const d = new Date(b.from + 'T00:00:00')
                if (g === 'month') {
                    return new Intl.DateTimeFormat(this.locale, { month: long ? 'long' : 'short', year: 'numeric' }).format(d)
                }
                const day = new Intl.DateTimeFormat(this.locale, { day: 'numeric', month: 'short', ...(long ? { year: 'numeric' } : {}) }).format(d)
                return g === 'week' ? this.$t('admin.dashboards.week_of', { date: day }) : day
            },
            money(v) {
                const n = Number(v) || 0
                return n.toLocaleString(this.locale, { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' ' + (this.data?.currency || '₾')
            },
        },
    }
</script>

<style scoped>
.order-dash { width: 100%; }
.order-dash__filters {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: .5rem;
    margin-bottom: 1rem;
}
.order-dash__tiles {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
    gap: .75rem;
    margin-bottom: .5rem;
}
.order-dash__tile {
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius-lg);
    background: var(--bs-body-bg);
    padding: .85rem 1rem;
}
.order-dash__tile-label {
    font-size: .78rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: .03em;
    color: var(--bs-secondary-color);
}
.order-dash__tile-value {
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1.2;
    color: var(--bs-emphasis-color);
    margin: .2rem 0;
}
.order-dash__tile-sub {
    font-size: .8rem;
    color: var(--bs-secondary-color);
    display: flex;
    gap: .4rem;
    flex-wrap: wrap;
}
.order-dash__change { font-weight: 600; }
.order-dash__change.is-up { color: var(--bs-success-text-emphasis); }
.order-dash__change.is-down { color: var(--bs-danger-text-emphasis); }
.order-dash__note {
    font-size: .8rem;
    color: var(--bs-secondary-color);
    margin-bottom: 1rem;
}
.order-dash__card {
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius-lg);
    background: var(--bs-body-bg);
    padding: .85rem 1rem .5rem;
    height: 100%;
}
.order-dash__card-head {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    gap: .5rem;
}
.order-dash__chart {
    width: 100%;
    height: 280px;
}
.order-dash__empty {
    border: 1px dashed var(--bs-border-color);
    border-radius: var(--bs-border-radius-lg);
    padding: 2.5rem 1rem;
    text-align: center;
    color: var(--bs-secondary-color);
}
.order-dash__table {
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
}
.order-dash__table th {
    font-size: .75rem;
    text-transform: uppercase;
    color: var(--bs-secondary-color);
    white-space: nowrap;
}
.is-loading {
    opacity: .55;
    transition: opacity .15s;
}
</style>
