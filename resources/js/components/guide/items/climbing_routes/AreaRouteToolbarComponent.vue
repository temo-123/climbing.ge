<template>
    <!-- Route finder above the sectors of an outdoor area: name / grade
         search, the area's grade spread (a clickable bar + band chips),
         classics, hide-climbed and the climber's progress. It only edits
         `tools` (provided by SectorAndRoutesTabComponent); each
         SectorComponent hides its own rows with route_matches(). -->
    <div v-if="routes.length > 0" class="cg-route-tools" role="search">
        <div class="cg-route-tools__head">
            <h3 class="cg-route-tools__title">
                <i class="fa fa-search" aria-hidden="true"></i> {{ $t('guide.route_tools.title') }}
            </h3>
            <span class="cg-route-tools__count" aria-live="polite">
                <template v-if="filtering">{{ $t('guide.route_tools.showing', { n: shown_count, total: routes.length }) }}</template>
                <template v-else>{{ $t('guide.route_tools.total', { n: routes.length }) }}</template>
                <button v-if="filtering" type="button" class="btn btn-link btn-sm p-0 ms-2" @click="clear">{{ $t('guide.route_tools.clear') }}</button>
            </span>
        </div>

        <div v-if="tools.logged_in" class="cg-route-tools__progress">
            <div class="d-flex justify-content-between">
                <span><i class="fa fa-check-circle" aria-hidden="true"></i> {{ $t('guide.route_tools.progress', { n: ticked_count, total: routes.length }) }}</span>
                <strong>{{ progress }}%</strong>
            </div>
            <div class="cg-route-tools__bar"><span :style="{ width: progress + '%' }"></span></div>
        </div>

        <div class="cg-route-tools__search">
            <i class="fa fa-search" aria-hidden="true"></i>
            <input
                type="search"
                class="form-control"
                v-model="tools.query"
                :placeholder="$t('guide.route_tools.search_placeholder')"
                :aria-label="$t('guide.route_tools.search_placeholder')"
                @keydown.esc="tools.query = ''"
            />
            <button v-if="tools.query" type="button" class="cg-route-tools__x" :aria-label="$t('guide.route_tools.clear')" @click="tools.query = ''">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>
        </div>

        <!-- grade spread: one coloured segment per band, sized by its routes -->
        <div class="cg-route-tools__label">{{ $t('guide.route_tools.grade') }}</div>
        <div class="cg-route-tools__spread" role="group" :aria-label="$t('guide.route_tools.grade')">
            <button
                v-for="band in bands"
                :key="band.key"
                type="button"
                class="cg-route-tools__seg"
                :class="['cg-band-bg-' + band.key, { 'is-active': tools.band === band.key, 'is-dim': tools.band !== 'all' && tools.band !== band.key }]"
                :style="{ flexGrow: band.count }"
                :title="band.name + ' · ' + band.label + ' · ' + band.count"
                :aria-label="band.name + ' ' + band.label + ': ' + band.count"
                @click="pick(band.key)"
            >
                <span v-if="band.count * 100 / routes.length >= 9">{{ band.count }}</span>
            </button>
        </div>
        <div class="cg-route-tools__chips">
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
                @click="pick(band.key)"
            >
                {{ band.name }}<template v-if="band.key !== 'project'"> · {{ band.label }}</template> <small>{{ band.count }}</small>
            </button>
        </div>

        <div v-if="classics_count > 0 || (tools.logged_in && ticked_count > 0)" class="cg-route-tools__chips cg-route-tools__toggles">
            <button v-if="classics_count > 0" type="button" class="cg-chip" :class="{ 'is-active': tools.classics }" :aria-pressed="tools.classics" @click="tools.classics = !tools.classics">
                <i class="fa fa-star" aria-hidden="true"></i> {{ $t('guide.route_tools.classics') }} <small>{{ classics_count }}</small>
            </button>
            <button v-if="tools.logged_in && ticked_count > 0" type="button" class="cg-chip" :class="{ 'is-active': tools.hide_ticked }" :aria-pressed="tools.hide_ticked" @click="tools.hide_ticked = !tools.hide_ticked">
                <i class="fa fa-eye-slash" aria-hidden="true"></i> {{ $t('guide.route_tools.hide_climbed') }}
            </button>
        </div>

        <p v-if="filtering && !shown_count" class="cg-route-tools__none">
            <i class="fa fa-info-circle" aria-hidden="true"></i> {{ $t('guide.route_tools.no_match') }}
            <button type="button" class="btn btn-link btn-sm p-0 ms-1" @click="clear">{{ $t('guide.route_tools.clear') }}</button>
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
                    .map(b => ({ key: b.key, label: b.label, name: this.$t('guide.route_tools.bands.' + b.key), count: counts[b.key] || 0 }))
                    .concat([{ key: 'project', label: '', name: this.$t('guide.route_tools.projects'), count: counts.project || 0 }])
                    .filter(b => b.count > 0);
            },
            classics_count() { return this.routes.filter(r => route_stars(r) >= CLASSIC_STARS).length; },
            ticked_count() { return this.routes.filter(r => this.tools.ticked[r.id]).length; },
            progress() { return this.routes.length ? Math.round(this.ticked_count * 100 / this.routes.length) : 0; },
            filtering() { return is_filtering(this.tools); },
            shown_count() { return this.routes.filter(r => route_matches(r, this.tools)).length; },
        },
        methods: {
            pick(key) { this.tools.band = this.tools.band === key ? 'all' : key; },
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
    .cg-route-tools__head { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: .4rem .75rem; margin-bottom: .75rem; }
    .cg-route-tools__count { color: var(--cg-stone); font-size: .88rem; font-weight: 700; }
    .cg-route-tools__title { margin: 0 !important; font-size: 1.15rem; color: var(--cg-ink) !important; }
    .cg-route-tools__title .fa { color: var(--cg-accent); }
    .cg-route-tools__search { position: relative; margin-bottom: .9rem; }
    .cg-route-tools__search > .fa-search { position: absolute; left: .9rem; top: 50%; transform: translateY(-50%); color: var(--cg-muted); pointer-events: none; }
    .cg-route-tools__search input { padding-left: 2.4rem; padding-right: 2.4rem; border-radius: 2rem; }
    .cg-route-tools__search input::-webkit-search-cancel-button { display: none; }
    .cg-route-tools__x { position: absolute; right: .5rem; top: 50%; transform: translateY(-50%); width: 1.8rem; height: 1.8rem; border: 0; border-radius: 50%; background: var(--cg-chalk); color: var(--cg-stone); }
    .cg-route-tools__label { margin-bottom: .35rem; color: var(--cg-muted); font-size: .75rem; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; }
    .cg-route-tools__spread { display: flex; gap: 2px; height: 1.6rem; margin-bottom: .55rem; border-radius: .5rem; overflow: hidden; }
    .cg-route-tools__seg { flex: 1 1 0; min-width: 6px; display: grid; place-items: center; padding: 0; border: 0; color: #fff; font-size: .75rem; font-weight: 800; cursor: pointer; transition: opacity .15s, filter .15s; }
    .cg-route-tools__seg:hover { filter: brightness(1.08); }
    .cg-route-tools__seg.is-dim { opacity: .3; }
    .cg-route-tools__seg.is-active { box-shadow: inset 0 0 0 2px rgba(255, 255, 255, .85); }
    .cg-route-tools__seg:focus-visible { outline: 3px solid rgba(var(--cg-accent-rgb), .5); outline-offset: -3px; }
    .cg-band-bg-easy { background: #2fa36b; }
    .cg-band-bg-mid { background: #1f93b0; }
    .cg-band-bg-hard { background: #e08a1e; }
    .cg-band-bg-expert { background: #c43c3c; }
    .cg-band-bg-project { background: #6c757d; }
    .cg-route-tools__toggles { margin-top: .25rem; padding-top: .6rem; border-top: 1px dashed var(--cg-line); }
    .cg-route-tools__none { margin: .5rem 0 0; padding: .5rem .75rem; border-radius: var(--cg-r); background: var(--cg-chalk); color: var(--cg-stone); font-size: .88rem; font-weight: 700; }
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
    @media (prefers-reduced-motion: reduce) { .cg-route-tools__bar span { transition: none; } }
</style>
