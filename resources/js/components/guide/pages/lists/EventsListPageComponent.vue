<template>
    <!-- Events & competitions: every published event (get_event_on_site_list),
         filtered client-side — when (upcoming / past / all), type
         (competition / festival), "only mine" (interested) and a search —
         all kept in the URL query. Upcoming: "happening now" first, then one
         group per month; past: one group per year, newest first. -->
    <div class="cg-events">
        <section class="cg-page-head cg-page-head--list cg-art-trophy">
            <span class="cg-page-head__icon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
            <div class="cg-page-head__text">
                <h1>{{ $t('guide.title.events') }}</h1>
                <div class="cg-page-head__lead">{{ this.$siteData.data.event_description }}</div>
            </div>
        </section>

        <div class="cg-events__bar">
            <div class="cg-segmented" role="tablist" :aria-label="$t('guide.events.when')">
                <button v-for="w in whens" :key="w" type="button" role="tab" :class="{ active: when === w }" :aria-selected="when === w" @click="set('when', w)">
                    {{ $t('guide.events.when_' + w) }} <small>{{ counts[w] }}</small>
                </button>
            </div>
            <div class="cg-events__chips">
                <button v-for="c in categories" :key="c" type="button" class="cg-chip" :class="{ 'is-active': cat === c }" :aria-pressed="cat === c" @click="set('cat', c)">
                    <i v-if="c !== 'all'" class="fa" :class="c === 'competition' ? 'fa-trophy' : 'fa-music'" aria-hidden="true"></i>
                    {{ $t('guide.events.cat_' + c) }}
                </button>
                <button v-if="logged_in" type="button" class="cg-chip" :class="{ 'is-active': mine }" :aria-pressed="mine" @click="set('mine', !mine)">
                    <i class="fa fa-heart" aria-hidden="true"></i> {{ $t('guide.events.only_mine') }}
                </button>
            </div>
            <input type="search" class="form-control cg-events__search" v-model="query" :placeholder="$t('guide.events.search')" :aria-label="$t('guide.events.search')" />
        </div>

        <div v-if="loading" class="row g-4">
            <div v-for="n in 3" :key="n" class="col-md-6 col-lg-4"><div class="cg-events__skeleton"></div></div>
        </div>

        <!-- the timeline: competitions on the left of the line, festivals on
             the right; a hexagon marker and a date flag at the line for each
             event, a label on the line for each month / year group -->
        <template v-else-if="groups.length">
            <div class="cg-tl-legend d-none d-md-flex" aria-hidden="true">
                <span><i class="fa fa-trophy" aria-hidden="true"></i> {{ $t('guide.events.cat_competition') }}</span>
                <span>{{ $t('guide.events.cat_event') }} <i class="fa fa-music" aria-hidden="true"></i></span>
            </div>
            <ol class="cg-tl">
                <template v-for="group in groups" :key="group.key">
                    <li class="cg-tl__group" :class="{ 'is-live': group.key === 'live' }">
                        <h2>
                            <i v-if="group.key === 'live'" class="fa fa-circle" aria-hidden="true"></i> {{ group.label }}
                            <small>{{ group.items.length }}</small>
                        </h2>
                    </li>
                    <li
                        v-for="e in group.items"
                        :key="e.global_event.id"
                        class="cg-tl__item"
                        :class="[e.global_event.category === 'competition' ? 'is-left' : 'is-right', 'is-' + status_of(e)]"
                    >
                        <span class="cg-tl__hexa" aria-hidden="true"></span>
                        <span class="cg-tl__flag">
                            <strong>{{ flag_date(e) }}</strong>
                            <small v-if="flag_time(e)"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ flag_time(e) }}</small>
                        </span>
                        <div class="cg-tl__card"><eventCard :event="e" /></div>
                    </li>
                </template>
            </ol>
        </template>

        <div v-else class="cg-events__empty">
            <emptyPageComponent />
            <p v-if="when === 'upcoming' && counts.past" class="text-center">
                <button type="button" class="btn btn-outline-secondary btn-sm" @click="set('when', 'past')">{{ $t('guide.events.see_past') }}</button>
            </p>
        </div>

        <metaData
            :title="$t('guide.meta.events')"
            :description="this.$siteData.data.event_description"
            :image="'/public/images/meta_img/competition.jpg'"
        />
    </div>
</template>

<script>
import metaData from '../../items/MetaDataComponent'
import emptyPageComponent from '../../../global_components/EmptyPageComponent'
import eventCard from '../../items/cards/EventCardComponent.vue'
import { to_date, event_status, fmt, intl_locale, has_time } from '../../../../services/guide/eventDates.js'
import { interested, load_interested } from '../../../../services/guide/interested.js'

export default {
    components: { metaData, eventCard, emptyPageComponent },
    data() {
        return {
            events: [],
            loading: true,
            when: 'upcoming',
            cat: 'all',
            mine: false,
            query: '',
            whens: ['upcoming', 'past', 'all'],
            categories: ['all', 'competition', 'event'],
            logged_in: !!localStorage.getItem('auth_token'),
            interested,
        }
    },
    computed: {
        with_status() {
            return this.events.map(e => ({ e, s: event_status(e.global_event) }))
        },
        counts() {
            const past = this.with_status.filter(x => x.s.key === 'finished').length
            return { upcoming: this.with_status.length - past, past, all: this.with_status.length }
        },
        filtered() {
            const q = this.query.trim().toLowerCase()
            return this.with_status.filter(({ e, s }) =>
                (this.when === 'all' || (this.when === 'past') === (s.key === 'finished')) &&
                (this.cat === 'all' || e.global_event.category === this.cat) &&
                (!this.mine || this.interested.ids.has(Number(e.global_event.id))) &&
                (!q || [e.locale_event && e.locale_event.title, e.global_event.url_title].some(v => String(v || '').toLowerCase().includes(q)))
            )
        },
        groups() {
            const locale = intl_locale(this.$i18n)
            const out = []
            const add = (key, label, item) => {
                let g = out.find(x => x.key === key)
                if (!g) { g = { key, label, items: [] }; out.push(g) }
                g.items.push(item)
            }
            const upcoming = this.filtered.filter(x => x.s.key !== 'finished')
            const past = this.filtered.filter(x => x.s.key === 'finished')
            upcoming.filter(x => x.s.key === 'live').forEach(x => add('live', this.$t('guide.events.happening_now'), x.e))
            upcoming.filter(x => x.s.key !== 'live')
                .sort((a, b) => to_date(a.e.global_event.start_data) - to_date(b.e.global_event.start_data))
                .forEach(x => {
                    const d = to_date(x.e.global_event.start_data)
                    add('m' + d.getFullYear() + '-' + d.getMonth(), fmt(d, locale, { month: 'long', year: 'numeric' }), x.e)
                })
            past.sort((a, b) => to_date(b.e.global_event.start_data) - to_date(a.e.global_event.start_data))
                .forEach(x => {
                    const y = to_date(x.e.global_event.start_data).getFullYear()
                    add('y' + y, this.$t('guide.events.past_year', { year: y }), x.e)
                })
            return out
        },
    },
    watch: {
        '$route.query'() { this.from_url() },
        query() { this.to_url() },
    },
    mounted() {
        this.from_url()
        this.get_events()
        load_interested()
    },
    methods: {
        from_url() {
            const q = this.$route.query
            this.when = this.whens.includes(q.when) ? q.when : 'upcoming'
            this.cat = this.categories.includes(q.cat) ? q.cat : 'all'
            this.mine = q.mine === '1'
            if (typeof q.q === 'string' && q.q !== this.query) this.query = q.q
        },
        to_url() {
            const query = {}
            if (this.when !== 'upcoming') query.when = this.when
            if (this.cat !== 'all') query.cat = this.cat
            if (this.mine) query.mine = '1'
            if (this.query.trim()) query.q = this.query.trim()
            this.$router.replace({ query }).catch(() => {})
        },
        status_of(e) { return event_status(e.global_event).key },
        flag_date(e) {
            return fmt(to_date(e.global_event.start_data), intl_locale(this.$i18n), { weekday: 'short', day: 'numeric', month: 'short', year: 'numeric' })
        },
        flag_time(e) {
            return has_time(e.global_event) ? fmt(to_date(e.global_event.start_data), intl_locale(this.$i18n), { hour: '2-digit', minute: '2-digit', hour12: false }) : ''
        },
        set(key, value) {
            this[key] = value
            this.to_url()
        },
        get_events() {
            this.loading = true
            axios.get('/get_event/get_event_on_site_list/' + localStorage.getItem('lang'))
                .then(r => { this.events = Array.isArray(r.data) ? r.data : [] })
                .catch(() => { this.events = [] })
                .finally(() => { this.loading = false })
        },
    },
}
</script>

<style scoped>
.cg-events__bar { display: flex; flex-wrap: wrap; align-items: center; gap: .75rem 1rem; margin-bottom: 1.5rem; }
.cg-events__bar .cg-segmented small { margin-left: .3rem; opacity: .65; font-weight: 800; }
.cg-events__chips { display: flex; flex-wrap: wrap; gap: .4rem; }
.cg-events__chips .cg-chip { cursor: pointer; }
.cg-events__chips .cg-chip.is-active { background: var(--cg-accent); border-color: var(--cg-accent); color: #fff; }
.cg-events__chips .fa-heart { color: var(--cg-rope, #e0632a); }
.cg-events__chips .is-active .fa { color: #fff; }
.cg-events__search { flex: 1 1 14rem; max-width: 22rem; margin-left: auto; }
/* ---- timeline ---------------------------------------------------- */
.cg-tl-legend { justify-content: space-between; max-width: 1040px; margin: 0 auto .5rem; color: var(--cg-stone); font-weight: 800; font-size: .85rem; letter-spacing: .04em; text-transform: uppercase; }
.cg-tl-legend .fa-trophy { color: #e0a800; }
.cg-tl-legend .fa-music { color: #2a9d8f; }
.cg-tl { position: relative; max-width: 1040px; margin: 0 auto 2rem; padding: 0; list-style: none; }
/* the line */
.cg-tl::before { content: ""; position: absolute; top: 0; bottom: 0; left: 50%; width: 4px; margin-left: -2px; border-radius: 4px; background: linear-gradient(180deg, rgba(var(--cg-accent-rgb), 0), var(--cg-accent) 3%, var(--cg-accent) 97%, rgba(var(--cg-accent-rgb), 0)); }
.cg-tl > li { position: relative; }
/* group label sitting on the line */
.cg-tl__group { display: flex; justify-content: center; margin: 1.5rem 0 1.25rem; }
.cg-tl__group h2 { position: relative; z-index: 1; display: inline-flex; align-items: baseline; gap: .45rem; margin: 0 !important; padding: .4rem 1.1rem; border-radius: 2rem; background: var(--cg-ink); color: #fff !important; font-size: 1.2rem !important; text-transform: capitalize; box-shadow: var(--cg-shadow-sm); }
.cg-tl__group h2 small { opacity: .65; font-size: .8rem; }
.cg-tl__group.is-live h2 { background: #13865d; }
.cg-tl__group.is-live .fa { font-size: .6rem; align-self: center; animation: cg-ev-live 1.4s ease-in-out infinite; }
/* one event: card on its side, marker + date flag at the line */
.cg-tl__item { display: grid; grid-template-columns: 1fr 4rem 1fr; align-items: start; margin-bottom: 1.75rem; }
.cg-tl__card { grid-row: 1; }
.cg-tl .cg-tl__card :deep(.cg-ev__img) { aspect-ratio: 16 / 9; }
.cg-tl__item.is-left .cg-tl__card { grid-column: 1; }
.cg-tl__item.is-right .cg-tl__card { grid-column: 3; }
.cg-tl__hexa { grid-row: 1; grid-column: 2; justify-self: center; position: relative; z-index: 1; width: 1.6rem; height: 1.8rem; margin-top: 1.4rem; background: var(--cg-accent); clip-path: polygon(50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%, 0 25%); box-shadow: 0 0 0 4px var(--cg-chalk); }
.cg-tl__hexa::after { content: ""; position: absolute; inset: 5px; background: #fff; clip-path: inherit; }
.cg-tl__item.is-left .cg-tl__hexa { background: #e0a800; }
.cg-tl__item.is-right .cg-tl__hexa { background: #2a9d8f; }
.cg-tl__item.is-live .cg-tl__hexa { background: #13865d; animation: cg-tl-pulse 1.6s ease-in-out infinite; }
.cg-tl__item.is-live .cg-tl__hexa::after { background: #13865d; }
.cg-tl__item.is-finished .cg-tl__hexa { background: var(--cg-muted); }
@keyframes cg-tl-pulse { 50% { transform: scale(1.18); } }
/* date flag on the other side of the line, pointing at the marker */
.cg-tl__flag { grid-row: 1; position: relative; display: inline-flex; flex-direction: column; margin-top: 1.25rem; padding: .45rem .85rem; border-radius: .6rem; background: #fff; border: 1px solid var(--cg-line); box-shadow: var(--cg-shadow-sm); line-height: 1.2; white-space: nowrap; }
.cg-tl__flag strong { color: var(--cg-ink); font-size: .95rem; text-transform: capitalize; }
.cg-tl__flag small { color: var(--cg-stone); font-weight: 700; }
.cg-tl__flag::before { content: ""; position: absolute; top: .9rem; width: .7rem; height: .7rem; background: #fff; border: 1px solid var(--cg-line); transform: rotate(45deg); }
.cg-tl__item.is-left .cg-tl__flag { grid-column: 3; justify-self: start; }
.cg-tl__item.is-left .cg-tl__flag::before { left: -.4rem; border-top: 0; border-right: 0; }
.cg-tl__item.is-right .cg-tl__flag { grid-column: 1; justify-self: end; }
.cg-tl__item.is-right .cg-tl__flag::before { right: -.4rem; border-bottom: 0; border-left: 0; }
.cg-tl__item.is-finished .cg-tl__flag { opacity: .75; }
/* phones: line on the left, flag above the card */
@media (max-width: 767.98px) {
    .cg-tl::before { left: 1rem; }
    .cg-tl__group { justify-content: flex-start; padding-left: 2.25rem; }
    .cg-tl__item { grid-template-columns: 2rem 1fr; column-gap: .25rem; margin-bottom: 1.5rem; }
    .cg-tl__hexa { grid-column: 1 !important; grid-row: 1; margin-top: .35rem; }
    .cg-tl__item .cg-tl__flag { grid-column: 2 !important; grid-row: 1; justify-self: start !important; margin: 0 0 .6rem; }
    .cg-tl__item .cg-tl__flag::before { left: -.4rem !important; right: auto !important; top: .7rem; border: 1px solid var(--cg-line) !important; border-top: 0 !important; border-right: 0 !important; }
    .cg-tl__item .cg-tl__card { grid-column: 2 !important; grid-row: 2; }
}
@keyframes cg-ev-live { 50% { opacity: .3; } }
.cg-events__skeleton { height: 24rem; border-radius: var(--cg-r-lg); background: linear-gradient(90deg, var(--cg-chalk-2) 25%, var(--cg-chalk) 50%, var(--cg-chalk-2) 75%); background-size: 200% 100%; animation: cg-ev-shimmer 1.2s linear infinite; }
@keyframes cg-ev-shimmer { to { background-position: -200% 0; } }
@media (max-width: 767.98px) { .cg-events__search { max-width: none; margin-left: 0; } }
@media (prefers-reduced-motion: reduce) { .cg-events__skeleton, .cg-tl__group.is-live .fa, .cg-tl__item.is-live .cg-tl__hexa { animation: none; } }
</style>
