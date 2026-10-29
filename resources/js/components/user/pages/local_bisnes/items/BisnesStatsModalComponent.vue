<template>
    <!-- Statistics of one recommended service, opened from the admin
         "Local businesses" table (chart icon). Data: set_bisnes/get_bisnes_stats
         (Redis counters, BusinessStatsService). -->
    <StackModal
        v-model="is_show_modal"
        :title="$t('admin.local_business.stats.title') + (stats && stats.bisnes ? ' — ' + stats.bisnes.url_title : '')"
        :modal-class="{ 'modal-xl': true }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        :saveButton="{ visible: false }"
        @close="is_show_modal = false"
    >
        <div class="bs-wrap">
            <div class="bs-toolbar">
                <div class="cg-segmented" role="group" :aria-label="$t('admin.local_business.stats.period')">
                    <button v-for="d in periods" :key="d" type="button" :class="{ active: days === d }" @click="set_days(d)">
                        {{ $t('admin.local_business.stats.last_days', { n: d }) }}
                    </button>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary" :disabled="loading" @click="load"><i class="fa fa-refresh" :class="{ 'fa-spin': loading }" aria-hidden="true"></i></button>
            </div>

            <div v-if="loading && !stats" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>
            <div v-else-if="error" class="alert alert-danger">{{ error }}</div>

            <template v-else-if="stats">
                <div class="bs-cards">
                    <div v-for="m in metrics" :key="m.key" class="bs-card" :class="'bs-card--' + m.key">
                        <i class="fa" :class="m.icon" aria-hidden="true"></i>
                        <strong>{{ stats.totals[m.key] }}</strong>
                        <span>{{ $t('admin.local_business.stats.metric.' + m.key) }}</span>
                        <small>{{ $t('admin.local_business.stats.unique', { n: stats.totals[m.key + '_uniq'] }) }} · {{ $t('admin.local_business.stats.all_time', { n: stats.all_time[m.key] }) }}</small>
                    </div>
                    <div class="bs-card bs-card--ctr">
                        <i class="fa fa-percent" aria-hidden="true"></i>
                        <strong>{{ ctr }}%</strong>
                        <span>{{ $t('admin.local_business.stats.ctr') }}</span>
                        <small>{{ $t('admin.local_business.stats.ctr_hint') }}</small>
                    </div>
                </div>

                <div class="bs-chart-card">
                    <div class="bs-chart-head">
                        <h5>{{ $t('admin.local_business.stats.per_day') }}</h5>
                        <div class="bs-legend">
                            <button v-for="m in metrics" :key="m.key" type="button" :class="['bs-legend__item', 'bs-card--' + m.key, { 'is-off': hidden.includes(m.key) }]" @click="toggle(m.key)">
                                <span></span>{{ $t('admin.local_business.stats.metric.' + m.key) }}
                            </button>
                        </div>
                    </div>
                    <div class="bs-chart" role="img" :aria-label="$t('admin.local_business.stats.per_day')">
                        <div v-for="(date, i) in stats.dates" :key="date" class="bs-chart__day" :title="day_title(i)">
                            <div class="bs-chart__bars">
                                <span
                                    v-for="m in visible_metrics"
                                    :key="m.key"
                                    :class="'bs-card--' + m.key"
                                    :style="{ height: (stats.series[m.key][i] * 100 / chart_max) + '%' }"
                                ></span>
                            </div>
                            <small v-if="show_label(i)">{{ short_date(date) }}</small>
                        </div>
                    </div>
                </div>

                <div class="bs-table-card">
                    <h5>{{ $t('admin.local_business.stats.by_article') }}</h5>
                    <p v-if="!stats.by_article.length" class="text-muted mb-0">{{ $t('admin.local_business.stats.no_data') }}</p>
                    <table v-else class="table table-sm align-middle mb-0">
                        <thead>
                            <tr><th>{{ $t('common.title') }}</th><th>{{ $t('admin.local_business.stats.category') }}</th><th class="text-end">{{ $t('admin.local_business.stats.metric.impression') }}</th></tr>
                        </thead>
                        <tbody>
                            <tr v-for="a in stats.by_article" :key="a.article_id">
                                <td><a v-if="a.url_title" :href="article_url(a)" target="_blank" rel="noopener">{{ a.title }}</a><span v-else>{{ a.title }}</span></td>
                                <td>{{ a.category || '—' }}</td>
                                <td class="text-end fw-bold">{{ a.count }}</td>
                            </tr>
                        </tbody>
                    </table>
                    <small class="text-muted d-block mt-2">{{ $t('admin.local_business.stats.by_article_hint') }}</small>
                </div>

                <p v-if="stats.source !== 'redis'" class="small text-warning mt-2 mb-0"><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ $t('admin.local_business.stats.fallback') }}</p>
            </template>
        </div>
    </StackModal>
</template>

<script>
    const PATHS = { outdoor: '/outdoor/', ice: '/ice/', indoor: '/indoor/', mount_route: '/mountaineering/', other: '/other/', tech_tip: '/tech_tip/' };

    export default {
        name: 'BisnesStatsModal',
        data() {
            return {
                is_show_modal: false,
                bisnes_id: null,
                days: 30,
                periods: [7, 30, 90, 365],
                stats: null,
                loading: false,
                error: '',
                hidden: [],
                metrics: [
                    { key: 'impression', icon: 'fa-eye' },
                    { key: 'view', icon: 'fa-hand-pointer-o' },
                    { key: 'click', icon: 'fa-external-link' },
                    { key: 'photo', icon: 'fa-picture-o' },
                ],
            };
        },
        computed: {
            visible_metrics() { return this.metrics.filter(m => !this.hidden.includes(m.key)); },
            chart_max() {
                if (!this.stats) return 1;
                return Math.max(1, ...this.visible_metrics.flatMap(m => this.stats.series[m.key]));
            },
            // opened ÷ shown
            ctr() {
                if (!this.stats || !this.stats.totals.impression) return 0;
                return Math.round(this.stats.totals.view * 1000 / this.stats.totals.impression) / 10;
            },
        },
        methods: {
            show(bisnes_id) {
                this.bisnes_id = bisnes_id;
                this.stats = null;
                this.is_show_modal = true;
                this.load();
            },
            set_days(d) { this.days = d; this.load(); },
            load() {
                this.loading = true;
                this.error = '';
                axios.get('/set_bisnes/get_bisnes_stats/' + this.bisnes_id, { params: { days: this.days } })
                    .then(r => { this.stats = r.data; })
                    .catch(e => { this.error = (e.response && e.response.data && e.response.data.message) || this.$t('admin.local_business.stats.error'); })
                    .finally(() => { this.loading = false; });
            },
            toggle(key) {
                this.hidden = this.hidden.includes(key) ? this.hidden.filter(k => k !== key) : this.hidden.concat([key]);
            },
            short_date(d) {
                const date = new Date(d + 'T12:00:00');
                return new Intl.DateTimeFormat(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', { day: 'numeric', month: 'short' }).format(date);
            },
            show_label(i) {
                const n = this.stats.dates.length;
                const step = n <= 10 ? 1 : n <= 31 ? 5 : n <= 90 ? 15 : 60;
                return i % step === 0 || i === n - 1;
            },
            day_title(i) {
                return this.short_date(this.stats.dates[i]) + ' — ' + this.metrics.map(m => this.$t('admin.local_business.stats.metric.' + m.key) + ': ' + this.stats.series[m.key][i]).join(', ');
            },
            article_url(a) {
                return (process.env.MIX_APP_SSH || 'http://') + process.env.MIX_SITE_URL + (PATHS[a.category] || '/outdoor/') + a.url_title;
            },
        },
    };
</script>

<style scoped>
    .bs-wrap { display: grid; gap: 1rem; }
    .bs-toolbar { display: flex; align-items: center; justify-content: space-between; gap: .75rem; flex-wrap: wrap; }
    .bs-cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(10rem, 1fr)); gap: .75rem; }
    .bs-card { display: flex; flex-direction: column; gap: .1rem; padding: .85rem 1rem; border-radius: var(--cg-r); background: #fff; border: 1px solid var(--cg-line); border-top: 4px solid currentColor; }
    .bs-card .fa { font-size: 1.1rem; }
    .bs-card strong { font-family: var(--cg-font-display); font-size: 2rem; line-height: 1.1; color: var(--cg-ink); }
    .bs-card span { font-weight: 800; color: var(--cg-ink-2); font-size: .9rem; }
    .bs-card small { color: var(--cg-muted); font-weight: 600; }
    .bs-card--impression { color: #1f93b0; }
    .bs-card--view { color: #7427bb; }
    .bs-card--click { color: #e0632a; }
    .bs-card--photo { color: #18a06f; }
    .bs-card--ctr { color: #e0a800; }
    .bs-chart-card, .bs-table-card { padding: 1rem; border-radius: var(--cg-r); background: #fff; border: 1px solid var(--cg-line); }
    .bs-chart-head { display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: .5rem; margin-bottom: .75rem; }
    .bs-chart-head h5, .bs-table-card h5 { margin: 0; font-weight: 800; }
    .bs-legend { display: flex; flex-wrap: wrap; gap: .35rem; }
    .bs-legend__item { display: inline-flex; align-items: center; gap: .35rem; padding: .2rem .6rem; border: 1px solid var(--cg-line); border-radius: 2rem; background: #fff; font-size: .8rem; font-weight: 700; color: var(--cg-ink-2); }
    .bs-legend__item span { width: .65rem; height: .65rem; border-radius: 50%; background: currentColor; }
    .bs-legend__item.bs-card--impression span { background: #1f93b0; } .bs-legend__item.bs-card--view span { background: #7427bb; }
    .bs-legend__item.bs-card--click span { background: #e0632a; } .bs-legend__item.bs-card--photo span { background: #18a06f; }
    .bs-legend__item { color: var(--cg-ink-2) !important; }
    .bs-legend__item.is-off { opacity: .45; text-decoration: line-through; }
    .bs-chart { display: flex; align-items: stretch; gap: 2px; height: 14rem; overflow-x: auto; }
    .bs-chart__day { flex: 1 0 .55rem; display: flex; flex-direction: column; min-width: .55rem; }
    .bs-chart__bars { flex: 1; display: flex; align-items: flex-end; gap: 1px; border-bottom: 1px solid var(--cg-line); }
    .bs-chart__bars span { flex: 1; min-height: 0; border-radius: 2px 2px 0 0; background: currentColor; }
    .bs-chart__day small { margin-top: .25rem; color: var(--cg-muted); font-size: .65rem; white-space: nowrap; }
    .bs-chart__day:hover .bs-chart__bars { background: var(--cg-chalk); }
</style>
