<template>
    <!-- Guide page (/guide_page, tour_guide › show — role "Guide"): the tours I guide, the
         reservations in my charge and the climbing.ge fees I owe.
         docs/FUNCTIONS_DOCS/shop/tour_guides.md#guide-role--guide-page -->
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row"><div class="col-md-12"><breadcrumb /></div></div>

            <div class="gp">
                <header class="gp__head">
                    <h1>{{ $t('admin.guide_page.title') }}</h1>
                    <p>{{ $t('admin.guide_page.subtitle') }}</p>
                </header>

                <div v-if="loading" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>
                <div v-else-if="error" class="alert alert-danger">{{ error }}</div>

                <template v-else-if="data">
                    <div class="gp__cards">
                        <div class="gp__card is-accent">
                            <span>{{ $t('admin.guide_page.upcoming') }}</span>
                            <strong>{{ data.stats.upcoming }}</strong>
                            <small>{{ $t('admin.guide_page.confirmed_n', { n: data.stats.upcoming_confirmed }) }}</small>
                        </div>
                        <div class="gp__card">
                            <span>{{ $t('admin.guide_page.tours') }}</span>
                            <strong>{{ data.stats.tours }}</strong>
                            <small>{{ $t('admin.guide_page.general_of', { n: data.stats.general_of }) }}</small>
                        </div>
                        <div class="gp__card" :class="{ 'is-warn': data.stats.fees_owed_count }">
                            <span>{{ $t('admin.guide_page.fees_owed') }}</span>
                            <strong>{{ fees_owed }}</strong>
                            <small>{{ $t('admin.guide_page.fees_owed_n', { n: data.stats.fees_owed_count }) }}</small>
                        </div>
                    </div>

                    <section class="gp__panel">
                        <h2><i class="fa fa-compass" aria-hidden="true"></i> {{ $t('admin.guide_page.my_tours') }}</h2>
                        <p v-if="!data.tours.length" class="gp__muted">{{ $t('admin.guide_page.no_tours') }}</p>
                        <div class="gp__tours">
                            <a v-for="t in data.tours" :key="t.id" :href="t.public_url" target="_blank" rel="noopener" class="gp__tour">
                                <strong>{{ tour_title(t) }}</strong>
                                <span v-if="t.is_main" class="gp__badge">★ {{ $t('admin.tour_guides.general') }}</span>
                                <span v-else class="gp__badge is-helper">{{ $t('admin.guide_page.helper') }}</span>
                                <small v-if="!t.published" class="gp__muted">{{ $t('admin.tour.rd_unpublished') }}</small>
                            </a>
                        </div>
                    </section>

                    <section class="gp__panel">
                        <h2><i class="fa fa-calendar" aria-hidden="true"></i> {{ $t('admin.guide_page.my_reservations') }}</h2>
                        <p v-if="!upcoming.length" class="gp__muted">{{ $t('admin.guide_page.no_upcoming') }}</p>
                        <div class="gp__res">
                            <article v-for="r in upcoming" :key="r.id" class="gp__item" :class="{ 'is-wait': !r.verified }">
                                <div class="gp__date">
                                    <strong>{{ day(r.check_in) }}</strong>
                                    <span>{{ month(r.check_in) }}</span>
                                </div>
                                <div class="gp__body">
                                    <strong>{{ r.tour ? tour_title(r.tour) : '#' + r.tour_id }}</strong>
                                    <span>
                                        {{ r.name || '—' }} · {{ $t('admin.tour_dashboard.persons_n', { n: r.persons || 0 }) }}
                                        <template v-if="r.days"> · {{ r.days }} {{ $t('admin.tour_schedule.days_unit') }}</template>
                                        <template v-if="r.tour_language"> · 🗣 {{ $t('shop.tour.lang.' + r.tour_language) }}</template>
                                    </span>
                                    <span v-if="r.verified" class="gp__contact">
                                        <a v-if="r.phone" :href="'tel:' + r.phone"><i class="fa fa-phone" aria-hidden="true"></i> {{ r.phone }}</a>
                                        <a v-if="r.email" :href="'mailto:' + r.email"><i class="fa fa-envelope-o" aria-hidden="true"></i> {{ r.email }}</a>
                                    </span>
                                    <span v-else class="gp__wait"><i class="fa fa-hourglass-half" aria-hidden="true"></i> {{ $t('admin.guide_page.waiting') }}</span>
                                    <em v-if="r.text && r.verified">“{{ r.text }}”</em>
                                </div>
                                <div class="gp__money">
                                    <strong v-if="r.price_total !== null">{{ money(r.price_total, r.price_currency) }}</strong>
                                    <small v-if="r.commission_percent" :class="r.commission_paid ? 'text-success' : 'text-danger'">
                                        {{ $t('admin.tour_fee.fee') }} {{ r.commission_amount !== null ? money(r.commission_amount, r.price_currency) : r.commission_percent + '%' }} · {{ r.commission_paid ? $t('admin.tour_fee.paid') : $t('admin.tour_fee.unpaid') }}
                                    </small>
                                </div>
                            </article>
                        </div>

                        <details v-if="past.length" class="gp__past">
                            <summary>{{ $t('admin.guide_page.past', { n: past.length }) }}</summary>
                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <tbody>
                                        <tr v-for="r in past" :key="r.id">
                                            <td class="text-nowrap">{{ String(r.check_in || '').slice(0, 10) }}</td>
                                            <td>{{ r.tour ? tour_title(r.tour) : '#' + r.tour_id }}</td>
                                            <td>{{ r.name }}</td>
                                            <td>{{ r.completed ? '✓ ' + $t('admin.tour_fee.completed') : '' }}</td>
                                            <td class="text-nowrap">
                                                <span v-if="r.commission_percent" :class="r.commission_paid ? 'text-success' : 'text-danger'">{{ money(r.commission_amount, r.price_currency) }} · {{ r.commission_paid ? $t('admin.tour_fee.paid') : $t('admin.tour_fee.unpaid') }}</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </details>
                    </section>
                </template>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'
    import { money } from '../../../../../services/shop/tourPrice.js'

    export default {
        name: 'GuidePageComponent',
        components: { breadcrumb },
        data() { return { loading: true, error: '', data: null }; },
        computed: {
            locale() { return this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB'; },
            upcoming() { return ((this.data && this.data.reservations) || []).filter(r => r.upcoming); },
            past() { return ((this.data && this.data.reservations) || []).filter(r => !r.upcoming).reverse(); },
            fees_owed() {
                const f = (this.data && this.data.stats.fees_owed) || {};
                const parts = Object.keys(f).map(c => money(f[c], c));
                return parts.length ? parts.join(' + ') : money(0, 'EUR');
            },
        },
        mounted() {
            axios.get('/set_tour/get_guide_panel')
                .then(r => { this.data = r.data; })
                .catch(() => { this.error = this.$t('admin.tour_guides.failed'); })
                .finally(() => { this.loading = false; });
        },
        methods: {
            money(v, c) { return money(v, c); },
            tour_title(t) { return (this.$i18n.locale === 'ka' ? (t.ka_title || t.ka) : (t.us_title || t.us)) || t.us_title || t.us || ''; },
            parse(v) { const m = /^(\d{4})-(\d{2})-(\d{2})/.exec(String(v || '')); return m ? new Date(+m[1], +m[2] - 1, +m[3]) : null; },
            day(v) { const d = this.parse(v); return d ? d.getDate() : '—'; },
            month(v) { const d = this.parse(v); return d ? d.toLocaleDateString(this.locale, { month: 'short', year: 'numeric' }) : ''; },
        },
    };
</script>

<style scoped>
.gp { margin-bottom: 2rem; }
.gp__head h1 { margin: 0 0 .25rem; font-family: var(--cg-font-display); font-size: 2rem; color: var(--cg-ink); }
.gp__head p { color: var(--cg-stone); }
.gp__cards { display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: .75rem; margin-bottom: 1rem; }
.gp__card { display: flex; flex-direction: column; gap: .1rem; padding: .9rem 1rem; border: 1px solid var(--cg-line); border-left: 4px solid var(--cg-line); border-radius: var(--cg-r); background: #fff; }
.gp__card.is-accent { border-left-color: var(--cg-accent); }
.gp__card.is-warn { border-left-color: #d64545; }
.gp__card span { font-size: .75rem; font-weight: 800; letter-spacing: .03em; text-transform: uppercase; color: var(--cg-muted); }
.gp__card strong { font-family: var(--cg-font-display); font-size: 1.8rem; color: var(--cg-ink); }
.gp__card small { color: var(--cg-stone); }
.gp__panel { margin-bottom: 1rem; padding: 1rem 1.1rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r); background: #fff; }
.gp__panel h2 { display: flex; align-items: center; gap: .45rem; margin: 0 0 .75rem; font-size: .85rem; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; color: var(--cg-stone); }
.gp__panel h2 .fa { color: var(--cg-accent-dark); }
.gp__muted { color: var(--cg-muted); }
.gp__tours { display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: .6rem; }
.gp__tour { display: flex; flex-direction: column; gap: .25rem; padding: .7rem .85rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r-sm, .5rem); color: var(--cg-ink-2); text-decoration: none; }
.gp__tour:hover { border-color: var(--cg-accent); }
.gp__tour strong { color: var(--cg-ink); }
.gp__badge { align-self: flex-start; padding: 0 .5rem; border-radius: 999px; background: #ffc233; color: #5a4300; font-size: .72rem; font-weight: 800; }
.gp__badge.is-helper { background: var(--cg-chalk-2); color: var(--cg-stone); }
.gp__res { display: flex; flex-direction: column; gap: .55rem; }
.gp__item { display: grid; grid-template-columns: 64px 1fr auto; gap: .85rem; align-items: center; padding: .7rem .85rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r-sm, .5rem); }
.gp__item.is-wait { background: #fffaeb; }
.gp__date { display: flex; flex-direction: column; align-items: center; padding: .35rem 0; border-radius: .5rem; background: var(--cg-accent-soft); }
.gp__date strong { font-family: var(--cg-font-display); font-size: 1.5rem; line-height: 1; color: var(--cg-ink); }
.gp__date span { font-size: .7rem; font-weight: 700; color: var(--cg-accent-dark); text-transform: uppercase; }
.gp__body { display: flex; flex-direction: column; gap: .15rem; min-width: 0; }
.gp__body strong { color: var(--cg-ink); }
.gp__body > span { font-size: .88rem; color: var(--cg-stone); }
.gp__contact { display: flex; flex-wrap: wrap; gap: .25rem .9rem; }
.gp__contact a { font-weight: 600; }
.gp__wait { color: #8a6100 !important; font-weight: 600; }
.gp__body em { font-size: .85rem; color: var(--cg-stone); }
.gp__money { display: flex; flex-direction: column; align-items: flex-end; text-align: right; }
.gp__money strong { color: var(--cg-ink); }
.gp__past { margin-top: .9rem; }
.gp__past summary { cursor: pointer; font-weight: 700; color: var(--cg-ink-2); }
@media (max-width: 575.98px) {
    .gp__item { grid-template-columns: 52px 1fr; }
    .gp__money { grid-column: 1 / -1; align-items: flex-start; text-align: left; }
}
</style>
