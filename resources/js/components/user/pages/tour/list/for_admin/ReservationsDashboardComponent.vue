<template>
    <!-- Tour reservations dashboard (above the reservations table): stat cards,
         a month calendar of reserved trips, reservations per month, top tours.
         Data: set_tour/get_reservation/get_reservation_stats.
         docs/FUNCTIONS_DOCS/shop/tour_reservations_dashboard.md -->
    <div class="cg-rdb">
        <div v-if="loading" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>

        <template v-else-if="stats">
            <!-- stat cards -->
            <div class="cg-rdb__cards">
                <div class="cg-rdb__card is-accent">
                    <span class="cg-rdb__label"><i class="fa fa-calendar" aria-hidden="true"></i> {{ $t('admin.tour_dashboard.upcoming') }}</span>
                    <strong>{{ stats.totals.upcoming }}</strong>
                    <span class="cg-rdb__sub">{{ $t('admin.tour_dashboard.persons_n', { n: stats.totals.persons_upcoming }) }}</span>
                </div>
                <div class="cg-rdb__card is-warn">
                    <span class="cg-rdb__label"><i class="fa fa-hourglass-half" aria-hidden="true"></i> {{ $t('admin.tour_dashboard.waiting') }}</span>
                    <strong>{{ stats.totals.waiting }}</strong>
                    <span class="cg-rdb__sub">{{ $t('admin.tour_dashboard.waiting_sub') }}</span>
                </div>
                <div class="cg-rdb__card is-ok">
                    <span class="cg-rdb__label"><i class="fa fa-check-circle" aria-hidden="true"></i> {{ $t('admin.tour_dashboard.confirmed') }}</span>
                    <strong>{{ stats.totals.confirmed }}</strong>
                    <span class="cg-rdb__sub">{{ $t('admin.tour_dashboard.rate', { n: stats.confirm_rate }) }}</span>
                </div>
                <div class="cg-rdb__card">
                    <span class="cg-rdb__label"><i class="fa fa-eur" aria-hidden="true"></i> {{ $t('admin.tour_dashboard.revenue') }}</span>
                    <strong>{{ money_line('upcoming') }}</strong>
                    <span class="cg-rdb__sub">{{ $t('admin.tour_dashboard.this_year', { sum: money_line('this_year') }) }}</span>
                </div>
                <div class="cg-rdb__card">
                    <span class="cg-rdb__label"><i class="fa fa-bolt" aria-hidden="true"></i> {{ $t('admin.tour_dashboard.new_7') }}</span>
                    <strong>{{ stats.totals.new_7_days }}</strong>
                    <span class="cg-rdb__sub">{{ $t('admin.tour_dashboard.total', { n: stats.totals.all }) }}</span>
                </div>
            </div>

            <div class="cg-rdb__grid">
                <!-- calendar of reserved trips -->
                <section class="cg-rdb__panel cg-rdb__cal">
                    <header class="cg-rdb__cal-head">
                        <h3><i class="fa fa-calendar" aria-hidden="true"></i> {{ $t('admin.tour_dashboard.calendar') }}</h3>
                        <div class="cg-rdb__cal-nav">
                            <button type="button" class="btn btn-sm btn-outline-secondary" :aria-label="$t('shop.tour.schedule.prev')" @click="shift(-1)"><i class="fa fa-chevron-left" aria-hidden="true"></i></button>
                            <strong>{{ month_title }}</strong>
                            <button type="button" class="btn btn-sm btn-outline-secondary" :aria-label="$t('shop.tour.schedule.next')" @click="shift(1)"><i class="fa fa-chevron-right" aria-hidden="true"></i></button>
                            <button type="button" class="btn btn-sm btn-link" @click="go_today">{{ $t('admin.tour_dashboard.today') }}</button>
                        </div>
                    </header>
                    <div class="cg-rdb__wd">
                        <span v-for="w in weekday_heads" :key="w">{{ w }}</span>
                    </div>
                    <div class="cg-rdb__days">
                        <div v-for="cell in cells" :key="cell.key" class="cg-rdb__day" :class="{ 'is-other': !cell.in_month, 'is-today': cell.today }">
                            <span class="cg-rdb__num">{{ cell.day }}</span>
                            <button
                                v-for="ev in cell.events.slice(0, 3)"
                                :key="ev.id"
                                type="button"
                                class="cg-rdb__ev"
                                :class="[ev.confirmed ? 'is-ok' : 'is-warn', { 'is-cont': !ev.first }]"
                                :title="ev_title(ev)"
                                @click="$emit('open', ev.id)"
                            >
                                <template v-if="ev.first">{{ ev.name || ('#' + ev.id) }} · {{ ev.persons }}</template>
                                <template v-else>&nbsp;</template>
                            </button>
                            <span v-if="cell.events.length > 3" class="cg-rdb__more">+{{ cell.events.length - 3 }}</span>
                        </div>
                    </div>
                    <div class="cg-rdb__legend">
                        <span><i class="is-ok"></i> {{ $t('admin.tour_dashboard.confirmed') }}</span>
                        <span><i class="is-warn"></i> {{ $t('admin.tour_dashboard.waiting') }}</span>
                        <span class="cg-rdb__hint">{{ $t('admin.tour_dashboard.cal_hint') }}</span>
                    </div>
                </section>

                <div class="cg-rdb__side">
                    <!-- reservations per month (by check-in) -->
                    <section class="cg-rdb__panel">
                        <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ $t('admin.tour_dashboard.per_month') }}</h3>
                        <GChart type="ColumnChart" :data="chart_data" :options="chart_options" />
                    </section>

                    <!-- top tours -->
                    <section class="cg-rdb__panel">
                        <h3><i class="fa fa-trophy" aria-hidden="true"></i> {{ $t('admin.tour_dashboard.top_tours') }}</h3>
                        <ol v-if="stats.top_tours.length" class="cg-rdb__top">
                            <li v-for="t in stats.top_tours" :key="t.tour_id">
                                <span class="cg-rdb__top-name">{{ tour_name(t.tour_id) }}</span>
                                <span class="cg-rdb__top-bar"><i :style="{ width: (t.count / stats.top_tours[0].count * 100) + '%' }"></i></span>
                                <span class="cg-rdb__top-num">{{ t.count }} <small>({{ t.confirmed }} ✓)</small></span>
                            </li>
                        </ol>
                        <p v-else class="text-muted mb-0">{{ $t('admin.tour_dashboard.no_data') }}</p>
                    </section>
                </div>
            </div>
        </template>
    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'
    import { money } from '../../../../../../services/shop/tourPrice.js'

    const pad = n => String(n).padStart(2, '0');
    const ymd = d => `${d.getFullYear()}-${pad(d.getMonth() + 1)}-${pad(d.getDate())}`;
    const parse = s => { const m = /^(\d{4})-(\d{2})-(\d{2})/.exec(s || ''); return m ? new Date(+m[1], +m[2] - 1, +m[3]) : null; };

    export default {
        name: 'ReservationsDashboardComponent',
        components: { GChart },
        emits: ['open'],
        data() {
            const t = new Date();
            return { loading: true, stats: null, view: new Date(t.getFullYear(), t.getMonth(), 1) };
        },
        computed: {
            locale() { return this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'; },
            month_title() { return this.view.toLocaleDateString(this.locale, { month: 'long', year: 'numeric' }); },
            weekday_heads() { return [1, 2, 3, 4, 5, 6, 0].map(d => new Date(2024, 0, 7 + d).toLocaleDateString(this.locale, { weekday: 'short' })); },
            // every day of every trip → { 'YYYY-MM-DD': [event…] }
            by_day() {
                const map = {};
                ((this.stats && this.stats.calendar) || []).forEach(r => {
                    const start = parse(r.start);
                    if (!start) return;
                    for (let i = 0; i < r.days; i++) {
                        const d = new Date(start.getFullYear(), start.getMonth(), start.getDate() + i);
                        (map[ymd(d)] = map[ymd(d)] || []).push({ ...r, first: i === 0 || d.getDay() === 1, day_n: i + 1 });
                    }
                });
                Object.values(map).forEach(list => list.sort((a, b) => a.start < b.start ? -1 : a.start > b.start ? 1 : a.id - b.id));
                return map;
            },
            // 6 weeks, Monday first
            cells() {
                const first = this.view;
                const start = new Date(first.getFullYear(), first.getMonth(), 1 - ((first.getDay() + 6) % 7));
                const today = ymd(new Date());
                return Array.from({ length: 42 }, (_, i) => {
                    const d = new Date(start.getFullYear(), start.getMonth(), start.getDate() + i);
                    const key = ymd(d);
                    return { key, day: d.getDate(), in_month: d.getMonth() === first.getMonth(), today: key === today, events: this.by_day[key] || [] };
                });
            },
            chart_data() {
                const head = [this.$t('admin.tour_dashboard.month'), this.$t('admin.tour_dashboard.confirmed'), this.$t('admin.tour_dashboard.waiting')];
                const rows = ((this.stats && this.stats.months) || []).map(m => {
                    const d = parse(m.month + '-01');
                    return [d.toLocaleDateString(this.locale, { month: 'short', year: '2-digit' }), m.confirmed, m.waiting];
                });
                return [head, ...rows];
            },
            chart_options() {
                return {
                    isStacked: true,
                    height: 230,
                    legend: { position: 'bottom' },
                    colors: ['#2fa36b', '#e0a100'],
                    chartArea: { left: 30, right: 10, top: 10, bottom: 50 },
                    vAxis: { minValue: 0, format: '0', gridlines: { color: '#eee' } },
                    hAxis: { textStyle: { fontSize: 10 } },
                    bar: { groupWidth: '70%' },
                };
            },
        },
        mounted() { this.load(); },
        methods: {
            load() {
                this.loading = true;
                axios.get('/set_tour/get_reservation/get_reservation_stats')
                    .then(r => { this.stats = r.data; })
                    .catch(e => console.log(e))
                    .finally(() => { this.loading = false; });
            },
            shift(n) { this.view = new Date(this.view.getFullYear(), this.view.getMonth() + n, 1); },
            go_today() { const t = new Date(); this.view = new Date(t.getFullYear(), t.getMonth(), 1); },
            tour_name(id) {
                const t = this.stats && this.stats.tours && this.stats.tours[id];
                if (!t) return '#' + id;
                return (this.$i18n.locale === 'ka' ? t.ka : t.us) || t.us;
            },
            ev_title(ev) {
                return `${ev.name || '#' + ev.id} — ${this.tour_name(ev.tour_id)} · ${this.$t('admin.tour_dashboard.persons_n', { n: ev.persons })} · ${this.$t('admin.tour_dashboard.day_of', { n: ev.day_n, total: ev.days })} · ${ev.confirmed ? this.$t('admin.tour_dashboard.confirmed') : this.$t('admin.tour_dashboard.waiting')}`;
            },
            money_line(key) {
                const m = (this.stats && this.stats.money) || {};
                const parts = Object.keys(m).map(cur => money(m[cur][key], cur)).filter(Boolean);
                return parts.length ? parts.join(' + ') : money(0, 'EUR');
            },
        },
    };
</script>

<style scoped>
.cg-rdb { margin-bottom: 1.5rem; }
.cg-rdb__cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: .75rem; margin-bottom: 1rem; }
.cg-rdb__card {
    display: flex;
    flex-direction: column;
    gap: .15rem;
    padding: .9rem 1rem;
    border: 1px solid var(--cg-line);
    border-left: 4px solid var(--cg-line);
    border-radius: var(--cg-r);
    background: var(--cg-surface, #fff);
}
.cg-rdb__card.is-accent { border-left-color: var(--cg-accent); }
.cg-rdb__card.is-warn { border-left-color: #e0a100; }
.cg-rdb__card.is-ok { border-left-color: #2fa36b; }
.cg-rdb__label { font-size: .75rem; font-weight: 800; letter-spacing: .03em; text-transform: uppercase; color: var(--cg-muted); }
.cg-rdb__label .fa { margin-right: .2rem; }
.cg-rdb__card strong { font-family: var(--cg-font-display); font-size: 1.8rem; line-height: 1.1; color: var(--cg-ink); }
.cg-rdb__sub { font-size: .82rem; color: var(--cg-stone); }

.cg-rdb__grid { display: grid; grid-template-columns: minmax(0, 2fr) minmax(280px, 1fr); gap: 1rem; align-items: start; }
.cg-rdb__side { display: flex; flex-direction: column; gap: 1rem; }
.cg-rdb__panel { padding: 1rem 1.1rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r); background: var(--cg-surface, #fff); }
.cg-rdb__panel h3 { display: flex; align-items: center; gap: .45rem; margin: 0 0 .75rem; font-size: .82rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; color: var(--cg-stone); }
.cg-rdb__panel h3 .fa { color: var(--cg-accent-dark); }

.cg-rdb__cal-head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .5rem; margin-bottom: .5rem; }
.cg-rdb__cal-head h3 { margin: 0; }
.cg-rdb__cal-nav { display: flex; align-items: center; gap: .4rem; }
.cg-rdb__cal-nav strong { min-width: 9.5rem; text-align: center; text-transform: capitalize; color: var(--cg-ink); }
.cg-rdb__wd, .cg-rdb__days { display: grid; grid-template-columns: repeat(7, minmax(0, 1fr)); }
.cg-rdb__wd span { padding: .25rem; font-size: .72rem; font-weight: 800; text-align: center; text-transform: uppercase; color: var(--cg-muted); }
.cg-rdb__days { border-top: 1px solid var(--cg-line); border-left: 1px solid var(--cg-line); }
.cg-rdb__day {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-height: 86px;
    padding: .25rem 0 .3rem;
    border-right: 1px solid var(--cg-line);
    border-bottom: 1px solid var(--cg-line);
    overflow: hidden;
}
.cg-rdb__day.is-other { background: var(--cg-chalk); }
.cg-rdb__day.is-other .cg-rdb__num { color: #b9c0c6; }
.cg-rdb__num { padding: 0 .35rem; font-size: .78rem; font-weight: 700; color: var(--cg-stone); }
.cg-rdb__day.is-today .cg-rdb__num { align-self: flex-start; margin-left: .2rem; padding: 0 .4rem; border-radius: 999px; background: var(--cg-accent); color: #fff; }
.cg-rdb__ev {
    display: block;
    width: 100%;
    min-height: 1.15rem;
    padding: 0 .35rem;
    border: 0;
    font-size: .72rem;
    font-weight: 700;
    line-height: 1.15rem;
    text-align: left;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    cursor: pointer;
}
.cg-rdb__ev.is-ok { background: #d8f0e3; color: #1f7a4d; box-shadow: inset 3px 0 0 #2fa36b; }
.cg-rdb__ev.is-warn { background: #fff0c7; color: #8a6100; box-shadow: inset 3px 0 0 #e0a100; }
.cg-rdb__ev.is-cont { box-shadow: none; }
.cg-rdb__ev:hover { filter: brightness(.95); }
.cg-rdb__more { padding: 0 .35rem; font-size: .7rem; font-weight: 700; color: var(--cg-muted); }
.cg-rdb__legend { display: flex; flex-wrap: wrap; gap: .4rem 1rem; margin-top: .6rem; font-size: .78rem; color: var(--cg-muted); }
.cg-rdb__legend i { display: inline-block; width: .8rem; height: .8rem; margin-right: .25rem; border-radius: .2rem; vertical-align: -1px; }
.cg-rdb__legend .is-ok { background: #2fa36b; }
.cg-rdb__legend .is-warn { background: #e0a100; }
.cg-rdb__hint { margin-left: auto; }

.cg-rdb__top { margin: 0; padding: 0; list-style: none; counter-reset: top; }
.cg-rdb__top li { display: grid; grid-template-columns: 1fr auto; gap: .2rem .75rem; padding: .45rem 0; border-bottom: 1px solid var(--cg-line); }
.cg-rdb__top li:last-child { border-bottom: 0; }
.cg-rdb__top-name { font-weight: 700; color: var(--cg-ink); }
.cg-rdb__top-num { font-weight: 800; color: var(--cg-ink-2); }
.cg-rdb__top-num small { font-weight: 600; color: var(--cg-muted); }
.cg-rdb__top-bar { grid-column: 1 / -1; height: 5px; border-radius: 999px; background: var(--cg-chalk-2); overflow: hidden; }
.cg-rdb__top-bar i { display: block; height: 100%; background: var(--cg-accent); }

@media (max-width: 991.98px) {
    .cg-rdb__grid { grid-template-columns: 1fr; }
}
@media (max-width: 575.98px) {
    .cg-rdb__day { min-height: 56px; }
    .cg-rdb__ev { font-size: 0; min-height: .45rem; line-height: .45rem; padding: 0; }
    .cg-rdb__hint { margin-left: 0; }
}
</style>
