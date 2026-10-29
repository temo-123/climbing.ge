<template>
    <!-- "Crag for today": pick a grade band, sun or shade and family
         friendliness, get the three best-matching outdoor areas with the
         reasons. Data: get_outdoor/get_area_profiles (per-area summary) +
         get_article/get_locale_articles/outdoor (names, links, photos). -->
    <section class="cg-picker d-print-none" :aria-label="$t('guide.crag_picker.title')">
        <button type="button" class="cg-picker__toggle" @click="toggle" :aria-expanded="open">
            <span class="cg-picker__icon"><i class="fa fa-magic" aria-hidden="true"></i></span>
            <span class="cg-picker__head">
                <strong>{{ $t('guide.crag_picker.title') }}</strong>
                <small>{{ $t('guide.crag_picker.lead') }}</small>
            </span>
            <i class="fa ms-auto" :class="open ? 'fa-chevron-up' : 'fa-chevron-down'" aria-hidden="true"></i>
        </button>

        <div v-if="open" class="cg-picker__body">
            <div class="cg-picker__row">
                <span class="cg-picker__label">{{ $t('guide.crag_picker.grade') }}</span>
                <div class="cg-picker__chips">
                    <button v-for="b in bands" :key="b.key" type="button" class="cg-chip" :class="{ 'is-active': grade === b.key }" :aria-pressed="grade === b.key" @click="grade = b.key">{{ b.label }}</button>
                </div>
            </div>
            <div class="cg-picker__row">
                <span class="cg-picker__label">{{ $t('guide.crag_picker.sun') }}</span>
                <div class="cg-picker__chips">
                    <button v-for="o in sun_options" :key="o" type="button" class="cg-chip" :class="{ 'is-active': sun === o }" :aria-pressed="sun === o" @click="sun = o">
                        <i class="fa" :class="sun_icon(o)" aria-hidden="true"></i> {{ $t('guide.crag_picker.sun_' + o) }}
                    </button>
                </div>
            </div>
            <label class="cg-picker__family">
                <input type="checkbox" class="form-check-input" v-model="family" /> {{ $t('guide.crag_picker.family') }}
            </label>
            <p v-if="season_hint" class="cg-picker__hint"><i class="fa fa-lightbulb-o" aria-hidden="true"></i> {{ season_hint }}</p>

            <div v-if="loading" class="cg-picker__results">
                <div v-for="n in 3" :key="n" class="cg-picker__card is-skeleton"></div>
            </div>
            <p v-else-if="!results.length" class="cg-picker__none">{{ $t('guide.crag_picker.none') }}</p>
            <div v-else class="cg-picker__results">
                <router-link v-for="(r, i) in results" :key="r.article_id" :to="r.link" class="cg-picker__card">
                    <span class="cg-picker__rank">{{ i + 1 }}</span>
                    <span class="cg-picker__img" :style="r.image ? { backgroundImage: 'url(' + r.image + ')' } : null"></span>
                    <span class="cg-picker__text">
                        <strong>{{ r.title }}</strong>
                        <span class="cg-picker__why">
                            <span>{{ r.count }} {{ grade === 'any' ? $t('guide.crag_picker.routes') : $t('guide.crag_picker.routes_in', { band: band_label }) }}</span>
                            <span v-for="w in r.why" :key="w">{{ w }}</span>
                        </span>
                    </span>
                </router-link>
            </div>
        </div>
    </section>
</template>

<script>
    import { GRADE_BANDS } from '../../../services/guide/routeFilters.js'

    export default {
        name: 'CragPicker',
        data() {
            const month = new Date().getMonth(); // 0 = January
            return {
                open: false,
                loading: false,
                loaded: false,
                profiles: [],
                areas: {},
                grade: 'mid',
                // summer → shade, winter → sun, otherwise no preference
                sun: [5, 6, 7].includes(month) ? 'shade' : ([11, 0, 1].includes(month) ? 'sun' : 'any'),
                family: false,
                sun_options: ['any', 'sun', 'shade', 'morning_shade', 'afternoon_shade'],
            };
        },
        computed: {
            bands() { return [{ key: 'any', label: this.$t('guide.crag_picker.any') }].concat(GRADE_BANDS.map(b => ({ key: b.key, label: b.label }))); },
            band_label() { const b = GRADE_BANDS.find(x => x.key === this.grade); return b ? b.label : ''; },
            season_hint() {
                const month = new Date().getMonth();
                if ([5, 6, 7].includes(month)) return this.$t('guide.crag_picker.hint_summer');
                if ([11, 0, 1].includes(month)) return this.$t('guide.crag_picker.hint_winter');
                return '';
            },
            results() {
                return this.profiles
                    .filter(p => this.areas[p.article_id])
                    .filter(p => this.sun === 'any' || p[this.sun])
                    .filter(p => !this.family || p.family)
                    .map(p => {
                        const count = this.grade === 'any' ? p.routes : (p.bands[this.grade] || 0);
                        const why = [];
                        if (this.sun !== 'any') why.push(this.$t('guide.crag_picker.sun_' + this.sun));
                        if (p.family) why.push(this.$t('guide.crag_picker.family_short'));
                        if (p.max_walk) why.push(this.$t('guide.crag_picker.walk', { n: p.max_walk }));
                        return Object.assign({ count, why }, this.areas[p.article_id], { article_id: p.article_id });
                    })
                    .filter(r => r.count > 0)
                    .sort((a, b) => b.count - a.count)
                    .slice(0, 3);
            },
        },
        methods: {
            sun_icon(o) {
                return { any: 'fa-adjust', sun: 'fa-sun-o', shade: 'fa-tree', morning_shade: 'fa-moon-o', afternoon_shade: 'fa-cloud' }[o];
            },
            toggle() {
                this.open = !this.open;
                if (this.open && !this.loaded) this.load();
            },
            load() {
                this.loading = true;
                const lang = localStorage.getItem('lang') || 'us';
                const loc = this.$route.params.locale;
                Promise.all([
                    axios.get('/get_outdoor/get_area_profiles'),
                    axios.get('/get_article/get_locale_articles/outdoor/' + lang),
                ]).then(([profiles, articles]) => {
                    this.profiles = profiles.data || [];
                    const areas = {};
                    (articles.data || []).forEach(a => {
                        if (!a || !a.global_data || !a.locale_data) return;
                        areas[a.global_data.id] = {
                            title: a.locale_data.title,
                            link: (loc ? '/' + loc : '') + '/outdoor/' + a.global_data.url_title,
                            image: a.global_data.image ? '/public/images/outdoor_img/' + encodeURIComponent(a.global_data.image) : null,
                        };
                    });
                    this.areas = areas;
                    this.loaded = true;
                }).catch(() => {}).finally(() => { this.loading = false; });
            },
        },
    };
</script>

<style scoped>
    .cg-picker { margin: 0 0 1.5rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); overflow: hidden; }
    .cg-picker__toggle { display: flex; align-items: center; gap: .85rem; width: 100%; padding: .9rem 1.1rem; border: 0; background: none; text-align: left; color: var(--cg-ink); }
    .cg-picker__toggle:focus-visible { outline: 3px solid rgba(var(--cg-accent-rgb), .45); outline-offset: -3px; }
    .cg-picker__icon { flex: 0 0 2.6rem; height: 2.6rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--cg-hold); font-size: 1.15rem; }
    .cg-picker__head { display: flex; flex-direction: column; line-height: 1.25; }
    .cg-picker__head strong { font-family: var(--cg-font-display); font-size: 1.3rem; }
    .cg-picker__head small { color: var(--cg-stone); font-weight: 600; }
    .cg-picker__body { padding: 0 1.1rem 1.1rem; border-top: 1px dashed var(--cg-line); }
    .cg-picker__row { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem 1rem; margin-top: .85rem; }
    .cg-picker__label { flex: 0 0 6.5rem; font-weight: 800; font-size: .85rem; color: var(--cg-ink-2); }
    .cg-picker__chips { display: flex; flex-wrap: wrap; gap: .4rem; }
    .cg-picker__chips .cg-chip { cursor: pointer; }
    .cg-picker__chips .cg-chip.is-active { background: var(--cg-accent); border-color: var(--cg-accent); color: #fff; }
    .cg-picker__family { display: flex; align-items: center; gap: .5rem; margin-top: .85rem; font-weight: 700; font-size: .9rem; cursor: pointer; }
    .cg-picker__hint { margin: .75rem 0 0; font-size: .85rem; color: var(--cg-stone); font-weight: 600; }
    .cg-picker__hint .fa { color: #e0a800; }
    .cg-picker__none { margin: 1rem 0 0; font-weight: 700; color: var(--cg-stone); }
    .cg-picker__results { display: grid; gap: .75rem; margin-top: 1rem; }
    @media (min-width: 768px) { .cg-picker__results { grid-template-columns: repeat(3, 1fr); } }
    .cg-picker__card { position: relative; display: flex; gap: .7rem; align-items: center; min-height: 5rem; padding: .6rem; border: 1px solid var(--cg-line); border-radius: var(--cg-r); color: var(--cg-ink); text-decoration: none; transition: border-color .15s, transform .15s; }
    .cg-picker__card:hover { border-color: var(--cg-accent); transform: translateY(-2px); }
    .cg-picker__card.is-skeleton { background: linear-gradient(90deg, var(--cg-chalk-2) 25%, var(--cg-chalk) 50%, var(--cg-chalk-2) 75%); background-size: 200% 100%; animation: cg-shimmer 1.2s linear infinite; }
    @keyframes cg-shimmer { to { background-position: -200% 0; } }
    .cg-picker__rank { position: absolute; top: -.5rem; left: -.5rem; width: 1.6rem; height: 1.6rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--cg-ink); color: #fff; font-weight: 800; font-size: .8rem; }
    .cg-picker__img { flex: 0 0 4rem; height: 4rem; border-radius: calc(var(--cg-r) - 2px); background: var(--cg-chalk-2) center / cover no-repeat; }
    .cg-picker__text { display: flex; flex-direction: column; gap: .25rem; min-width: 0; }
    .cg-picker__text strong { font-size: .95rem; line-height: 1.2; }
    .cg-picker__why { display: flex; flex-wrap: wrap; gap: .25rem; }
    .cg-picker__why span { padding: .05rem .45rem; border-radius: 1rem; background: var(--cg-accent-soft); color: var(--cg-accent-dark); font-size: .72rem; font-weight: 700; }
    @media (prefers-reduced-motion: reduce) { .cg-picker__card { transition: none; } .cg-picker__card:hover { transform: none; } .cg-picker__card.is-skeleton { animation: none; } }
</style>
