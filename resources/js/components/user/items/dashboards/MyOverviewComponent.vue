<template>
    <!-- User panel home: the climber's own overview (get_dashboard/get_my_overview).
         Profile checklist (until complete), numbers that link to their pages,
         recent ascents, upcoming interested events and the last order. -->
    <div class="mo">
        <section v-if="data && missing.length" class="mo-card mo-profile">
            <div class="mo-profile__head">
                <div>
                    <h2>{{ $t('user.overview.profile_title') }}</h2>
                    <p>{{ $t('user.overview.profile_lead', { done: done_count, total: checklist.length }) }}</p>
                </div>
                <div class="mo-ring" :style="{ '--p': Math.round(done_count * 100 / checklist.length) }" role="img" :aria-label="done_count + ' / ' + checklist.length">
                    <span>{{ Math.round(done_count * 100 / checklist.length) }}%</span>
                </div>
            </div>
            <ul class="mo-profile__list">
                <li v-for="item in checklist" :key="item.key" :class="{ 'is-done': data.profile[item.key] }">
                    <i class="fa" :class="data.profile[item.key] ? 'fa-check-circle' : 'fa-circle-o'" aria-hidden="true"></i>
                    <component :is="item.external ? 'a' : 'router-link'" v-bind="link_props(item)">{{ $t('user.overview.profile_items.' + item.key) }}</component>
                </li>
            </ul>
        </section>

        <h2 class="cg-section-title cg-section-title--left cg-dashboard__title">{{ $t('user.overview.my_climbing') }}</h2>
        <div class="mo-stats">
            <template v-if="data">
                <router-link v-for="s in stats" :key="s.key" :to="s.to" class="mo-stat">
                    <i class="fa" :class="s.icon" aria-hidden="true"></i>
                    <strong>{{ data.counts[s.key] }}</strong>
                    <span>{{ $t('user.overview.stats.' + s.key) }}</span>
                    <small v-if="s.key === 'route_ascents' && data.counts.routes_this_year">{{ $t('user.overview.this_year', { n: data.counts.routes_this_year }) }}</small>
                </router-link>
            </template>
            <div v-else v-for="n in 6" :key="n" class="mo-stat is-skeleton"></div>
        </div>

        <div class="mo-cols" v-if="data">
            <section class="mo-card">
                <div class="mo-card__head">
                    <h3><i class="fa fa-check-square-o" aria-hidden="true"></i> {{ $t('user.overview.recent_ascents') }}</h3>
                    <router-link :to="{ name: 'myRouteAscents' }">{{ $t('user.overview.see_all') }}</router-link>
                </div>
                <ul v-if="data.recent_ascents.length" class="mo-list">
                    <li v-for="a in data.recent_ascents" :key="a.id">
                        <span class="cg-grade">{{ a.grade }}</span>
                        <span class="mo-list__text">
                            <strong>{{ a.route_name }}</strong>
                            <small>{{ a.sector_name }} · {{ $t('guide.route_ascent.style.' + a.ascent_style) }} · {{ $timeAgo(a.ascent_date) }}</small>
                        </span>
                    </li>
                </ul>
                <div v-else class="mo-empty">
                    <p>{{ $t('user.overview.no_ascents') }}</p>
                    <a class="btn btn-sm btn-hold" :href="guide_url('/outdoor')" target="_blank" rel="noopener"><i class="fa fa-map-o" aria-hidden="true"></i> {{ $t('user.overview.find_routes') }}</a>
                </div>
            </section>

            <section class="mo-card">
                <div class="mo-card__head">
                    <h3><i class="fa fa-calendar" aria-hidden="true"></i> {{ $t('user.overview.coming_up') }}</h3>
                    <router-link :to="{ name: 'interestedEvents' }">{{ $t('user.overview.see_all') }}</router-link>
                </div>
                <ul v-if="data.upcoming_events.length" class="mo-list">
                    <li v-for="e in data.upcoming_events" :key="e.id">
                        <span class="mo-date">
                            <strong>{{ day(e.start_data) }}</strong>
                            <small>{{ month(e.start_data) }}</small>
                        </span>
                        <span class="mo-list__text">
                            <a :href="guide_url('/event/' + e.url_title)" target="_blank" rel="noopener"><strong>{{ e.title || e.url_title }}</strong></a>
                            <small>{{ $timeAgo(e.start_data) }}</small>
                        </span>
                    </li>
                </ul>
                <div v-else class="mo-empty">
                    <p>{{ $t('user.overview.no_events') }}</p>
                    <a class="btn btn-sm btn-outline-secondary" :href="guide_url('/events')" target="_blank" rel="noopener">{{ $t('user.overview.browse_events') }}</a>
                </div>

                <div v-if="data.last_order" class="mo-order">
                    <i class="fa fa-truck" aria-hidden="true"></i>
                    <span>{{ $t('user.overview.last_order', { id: data.last_order.id }) }} — <strong>{{ data.last_order.status || $t('user.overview.order_received') }}</strong></span>
                    <router-link :to="{ name: 'userPurchases' }">{{ $t('user.overview.see_all') }}</router-link>
                </div>
                <div class="mo-order">
                    <i class="fa fa-handshake-o" aria-hidden="true"></i>
                    <span v-if="data.counts.partner_posts">{{ $t('user.overview.partner_posts', { n: data.counts.partner_posts }) }}</span>
                    <span v-else>{{ $t('user.overview.no_partner_posts') }}</span>
                    <a :href="guide_url('/partners')" target="_blank" rel="noopener">{{ $t('user.overview.open_board') }}</a>
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'MyOverview',
        data() {
            return {
                data: null,
                checklist: [
                    { key: 'verified', to: { name: 'options' } },
                    { key: 'photo', to: { name: 'options' } },
                    { key: 'bio', to: { name: 'options' } },
                    { key: 'location', to: { name: 'options' } },
                    { key: 'social', to: { name: 'options' } },
                    { key: 'preferences', to: { name: 'options' } },
                    { key: 'first_ascent', external: '/outdoor' },
                ],
                stats: [
                    { key: 'route_ascents', icon: 'fa-check-square-o', to: { name: 'myRouteAscents' } },
                    { key: 'mtp_ascents', icon: 'fa-sort-amount-asc', to: { name: 'myMtpAscents' } },
                    { key: 'summit_ascents', icon: 'fa-flag', to: { name: 'myAscents' } },
                    { key: 'reviews', icon: 'fa-star', to: { name: 'myRoutesReview' } },
                    { key: 'favorites', icon: 'fa-heart', to: { name: 'favoriteOutdoorArea' } },
                    { key: 'orders', icon: 'fa-shopping-bag', to: { name: 'userPurchases' } },
                ],
            };
        },
        computed: {
            done_count() { return this.data ? this.checklist.filter(i => this.data.profile[i.key]).length : 0; },
            missing() { return this.data ? this.checklist.filter(i => !this.data.profile[i.key]) : []; },
        },
        mounted() {
            axios.get('/get_dashboard/get_my_overview').then(r => { this.data = r.data; }).catch(() => {});
        },
        methods: {
            guide_url(path) {
                const prefix = this.$i18n.locale === 'ka' ? '/ka' : '';
                return (process.env.MIX_APP_SSH || 'http://') + process.env.MIX_SITE_URL + prefix + path;
            },
            link_props(item) {
                return item.external ? { href: this.guide_url(item.external), target: '_blank', rel: 'noopener' } : { to: item.to };
            },
            fmt(d, opts) {
                const date = new Date(String(d).replace(' ', 'T'));
                return isNaN(date) ? '' : new Intl.DateTimeFormat(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', opts).format(date);
            },
            day(d) { return this.fmt(d, { day: 'numeric' }); },
            month(d) { return this.fmt(d, { month: 'short' }); },
        },
    };
</script>

<style scoped>
    .mo { display: grid; gap: 1rem; margin-bottom: 1.5rem; }
    .mo-card { padding: 1.1rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
    .mo-card__head { display: flex; align-items: baseline; justify-content: space-between; gap: .5rem; margin-bottom: .6rem; }
    .mo-card__head h3 { margin: 0; font-size: 1.1rem; }
    .mo-card__head h3 .fa { color: var(--cg-accent); }
    .mo-card__head a { font-weight: 700; font-size: .85rem; }

    .mo-profile { border-left: 5px solid var(--cg-hold); }
    .mo-profile__head { display: flex; justify-content: space-between; align-items: center; gap: 1rem; }
    .mo-profile__head h2 { margin: 0; font-size: 1.25rem; }
    .mo-profile__head p { margin: .2rem 0 0; color: var(--cg-stone); }
    .mo-ring { --p: 0; flex: 0 0 4.2rem; height: 4.2rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: conic-gradient(var(--cg-accent) calc(var(--p) * 1%), var(--cg-chalk-2) 0); }
    .mo-ring span { width: 3.3rem; height: 3.3rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #fff; font-weight: 800; font-size: .95rem; }
    .mo-profile__list { display: flex; flex-wrap: wrap; gap: .4rem .75rem; margin: .8rem 0 0; padding: 0; list-style: none; }
    .mo-profile__list li { display: inline-flex; align-items: center; gap: .35rem; font-weight: 700; font-size: .9rem; }
    .mo-profile__list li .fa { color: var(--cg-muted); }
    .mo-profile__list li.is-done { color: var(--cg-muted); text-decoration: line-through; }
    .mo-profile__list li.is-done .fa { color: #2fa36b; }
    .mo-profile__list li.is-done a { color: inherit; pointer-events: none; }

    .mo-stats { display: grid; grid-template-columns: repeat(auto-fill, minmax(9.5rem, 1fr)); gap: .75rem; }
    .mo-stat { display: flex; flex-direction: column; gap: .1rem; min-height: 6.5rem; padding: .9rem 1rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); color: var(--cg-ink-2); text-decoration: none; transition: border-color .15s, transform .15s; }
    .mo-stat:hover { border-color: var(--cg-accent); transform: translateY(-2px); color: var(--cg-ink-2); }
    .mo-stat .fa { color: var(--cg-accent); font-size: 1.1rem; }
    .mo-stat strong { font-family: var(--cg-font-display); font-size: 2rem; line-height: 1.1; color: var(--cg-ink); }
    .mo-stat span { font-weight: 700; font-size: .88rem; }
    .mo-stat small { color: #2fa36b; font-weight: 700; }
    .mo-stat.is-skeleton { background: linear-gradient(90deg, var(--cg-chalk-2) 25%, var(--cg-chalk) 50%, var(--cg-chalk-2) 75%); background-size: 200% 100%; animation: mo-shimmer 1.2s linear infinite; }
    @keyframes mo-shimmer { to { background-position: -200% 0; } }

    .mo-cols { display: grid; gap: 1rem; }
    @media (min-width: 992px) { .mo-cols { grid-template-columns: 1fr 1fr; } }
    .mo-list { margin: 0; padding: 0; list-style: none; }
    .mo-list li { display: flex; align-items: center; gap: .75rem; padding: .55rem 0; border-top: 1px dashed var(--cg-line); }
    .mo-list li:first-child { border-top: 0; }
    .mo-list__text { display: flex; flex-direction: column; min-width: 0; }
    .mo-list__text strong { color: var(--cg-ink); }
    .mo-list__text small { color: var(--cg-muted); font-weight: 600; }
    .mo-list .cg-grade { flex: 0 0 auto; }
    .mo-date { flex: 0 0 3rem; display: flex; flex-direction: column; align-items: center; padding: .25rem; border-radius: var(--cg-r); background: var(--cg-accent-soft); color: var(--cg-accent-dark); line-height: 1.1; }
    .mo-date strong { font-family: var(--cg-font-display); font-size: 1.4rem; }
    .mo-date small { font-weight: 800; text-transform: uppercase; font-size: .7rem; }
    .mo-empty { padding: .75rem 0; color: var(--cg-stone); }
    .mo-empty p { margin-bottom: .5rem; }
    .mo-order { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem; margin-top: .75rem; padding: .55rem .75rem; border-radius: var(--cg-r); background: var(--cg-chalk); font-size: .9rem; }
    .mo-order .fa { color: var(--cg-accent); }
    .mo-order a { margin-left: auto; font-weight: 700; }
    @media (prefers-reduced-motion: reduce) { .mo-stat { transition: none; } .mo-stat.is-skeleton { animation: none; } }
</style>
