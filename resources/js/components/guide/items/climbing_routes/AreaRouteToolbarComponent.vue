<template>
    <!-- Route finder above the sectors of an outdoor area: name search, grade
         band chips, classics, hide-climbed and the climber's progress. It only
         edits `tools` (provided by SectorAndRoutesTabComponent); each
         SectorComponent hides its own rows with route_matches(). -->
    <div v-if="routes.length > 0" class="cg-route-tools" role="search">
        <div class="cg-route-tools__head">
            <h3 class="cg-route-tools__title">
                <i class="fa fa-search" aria-hidden="true"></i> {{ $t('guide.route_tools.title') }}
            </h3>
        </div>

        <div v-if="tools.logged_in" class="cg-route-tools__progress">
            <div class="d-flex justify-content-between">
                <span><i class="fa fa-check-circle" aria-hidden="true"></i> {{ $t('guide.route_tools.progress', { n: ticked_count, total: routes.length }) }}</span>
                <strong>{{ progress }}%</strong>
            </div>
            <div class="cg-route-tools__bar"><span :style="{ width: progress + '%' }"></span></div>
        </div>

        <input
            type="search"
            class="form-control cg-route-tools__search"
            v-model="tools.query"
            :placeholder="$t('guide.route_tools.search_placeholder')"
            :aria-label="$t('guide.route_tools.search_placeholder')"
        />

        <div class="cg-route-tools__chips" role="group" :aria-label="$t('guide.route_tools.grade')">
            <button type="button" class="cg-chip" :class="{ 'is-active': tools.band === 'all' }" :aria-pressed="tools.band === 'all'" @click="tools.band = 'all'">
                {{ $t('guide.route_tools.all') }} <small>{{ routes.length }}</small>
            </button>
            <button
                v-for="band in bands"
                :key="band.key"
                type="button"
                class="cg-chip"
                :class="['cg-band-' + band.key, { 'is-active': tools.band === band.key }]"
                :aria-pressed="tools.band === band.key"
                @click="tools.band = tools.band === band.key ? 'all' : band.key"
            >
                {{ band.label }} <small>{{ band.count }}</small>
            </button>
        </div>

        <div class="cg-route-tools__chips">
            <button v-if="classics_count > 0" type="button" class="cg-chip" :class="{ 'is-active': tools.classics }" :aria-pressed="tools.classics" @click="tools.classics = !tools.classics">
                <i class="fa fa-star" aria-hidden="true"></i> {{ $t('guide.route_tools.classics') }} <small>{{ classics_count }}</small>
            </button>
            <button v-if="tools.logged_in && ticked_count > 0" type="button" class="cg-chip" :class="{ 'is-active': tools.hide_ticked }" :aria-pressed="tools.hide_ticked" @click="tools.hide_ticked = !tools.hide_ticked">
                <i class="fa fa-eye-slash" aria-hidden="true"></i> {{ $t('guide.route_tools.hide_climbed') }}
            </button>
        </div>

        <p v-if="filtering" class="cg-route-tools__result" aria-live="polite">
            {{ $t('guide.route_tools.showing', { n: shown_count, total: routes.length }) }}
            <button type="button" class="btn btn-link btn-sm p-0 ms-2" @click="clear">{{ $t('guide.route_tools.clear') }}</button>
        </p>
    </div>
</template>

<script>
    import { GRADE_BANDS, band_of, route_stars, CLASSIC_STARS, is_filtering, route_matches, flat_routes } from '../../../../services/guide/routeFilters.js'

    export default {
        name: 'AreaRouteToolbar',
        props: {
            climbing_area: { type: Array, default: () => [] },
            tools: { type: Object, required: true },
        },
        computed: {
            routes() { return flat_routes(this.climbing_area); },
            bands() {
                const counts = {};
                this.routes.forEach(r => { const b = band_of(r); counts[b] = (counts[b] || 0) + 1; });
                return GRADE_BANDS
                    .map(b => ({ key: b.key, label: b.label, count: counts[b.key] || 0 }))
                    .concat([{ key: 'project', label: this.$t('guide.route_tools.projects'), count: counts.project || 0 }])
                    .filter(b => b.count > 0);
            },
            classics_count() { return this.routes.filter(r => route_stars(r) >= CLASSIC_STARS).length; },
            ticked_count() { return this.routes.filter(r => this.tools.ticked[r.id]).length; },
            progress() { return this.routes.length ? Math.round(this.ticked_count * 100 / this.routes.length) : 0; },
            filtering() { return is_filtering(this.tools); },
            shown_count() { return this.routes.filter(r => route_matches(r, this.tools)).length; },
        },
        methods: {
            clear() {
                this.tools.band = 'all';
                this.tools.classics = false;
                this.tools.hide_ticked = false;
                this.tools.query = '';
            },
        },
    };
</script>

<style scoped>
    .cg-route-tools {
        margin-bottom: 1.25rem;
        padding: 1rem 1.1rem;
        background: #fff;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        box-shadow: var(--cg-shadow-sm);
    }
    .cg-route-tools__head { display: flex; align-items: center; justify-content: space-between; gap: .75rem; margin-bottom: .75rem; }
    .cg-route-tools__title { margin: 0 !important; font-size: 1.15rem; color: var(--cg-ink) !important; }
    .cg-route-tools__title .fa { color: var(--cg-accent); }
    .cg-route-tools__search { margin-bottom: .7rem; }
    .cg-route-tools__chips { display: flex; flex-wrap: wrap; gap: .4rem; margin-bottom: .5rem; }
    .cg-route-tools__chips .cg-chip { cursor: pointer; transition: background .15s, color .15s, border-color .15s; }
    .cg-route-tools__chips .cg-chip small { opacity: .65; font-weight: 800; }
    .cg-route-tools__chips .cg-chip:hover { border-color: var(--cg-accent); }
    .cg-route-tools__chips .cg-chip.is-active { background: var(--cg-accent); border-color: var(--cg-accent); color: #fff; }
    .cg-route-tools__chips .cg-chip.is-active small { opacity: .85; }
    .cg-route-tools__chips .fa-star { color: #e0a800; }
    .cg-route-tools__chips .is-active .fa-star { color: #fff; }
    .cg-route-tools__chips .cg-chip:focus-visible { outline: 3px solid rgba(var(--cg-accent-rgb), .45); outline-offset: 2px; }
    /* grade band dots, easy → expert */
    .cg-band-easy::before, .cg-band-mid::before, .cg-band-hard::before, .cg-band-expert::before, .cg-band-project::before {
        content: ""; width: .55rem; height: .55rem; border-radius: 50%; background: currentColor;
    }
    .cg-band-easy::before { background: #2fa36b; }
    .cg-band-mid::before { background: #1f93b0; }
    .cg-band-hard::before { background: #e08a1e; }
    .cg-band-expert::before { background: #c43c3c; }
    .cg-band-project::before { background: #6c757d; }
    .cg-chip.is-active::before { box-shadow: 0 0 0 2px #fff; }
    .cg-route-tools__progress { margin-bottom: .8rem; font-size: .88rem; font-weight: 700; color: var(--cg-ink-2); }
    .cg-route-tools__progress .fa { color: #2fa36b; }
    .cg-route-tools__bar { height: .5rem; margin-top: .3rem; border-radius: 1rem; background: var(--cg-chalk-2); overflow: hidden; }
    .cg-route-tools__bar span { display: block; height: 100%; background: linear-gradient(90deg, #2fa36b, var(--cg-accent)); border-radius: inherit; transition: width .4s var(--cg-ease); }
    .cg-route-tools__result { margin: .25rem 0 0; font-size: .85rem; font-weight: 700; color: var(--cg-stone); }
    @media (prefers-reduced-motion: reduce) { .cg-route-tools__bar span { transition: none; } }
</style>
