<template>
    <section class="cg-massif" id="massif" v-if="has_massif" :aria-label="$t('guide.massif.eyebrow') + ': ' + locale.title">

        <!-- header: which massif this route belongs to + quick links -->
        <header class="cg-massif__head">
            <div class="cg-massif__icon" aria-hidden="true">
                <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" stroke-linecap="round">
                    <path d="M2 20 9 8l4 6 3-4 6 10z" />
                    <path d="m7.4 10.8 1.6 1.4 1.5-1.6" />
                </svg>
            </div>

            <div class="cg-massif__titles">
                <span class="cg-massif__eyebrow">{{ $t('guide.massif.eyebrow') }}</span>
                <h2 class="cg-massif__title">{{ locale.title }}</h2>
                <p class="cg-massif__lead" v-if="lead">{{ lead }}</p>
            </div>

            <!-- maps are stored as a bare <iframe> (tag-only), so has_text() can't be used here -->
            <div class="cg-massif__map" v-if="has_map || osm_summits.length">
                <mount-summits-map-modal
                    :mount-id="global.id"
                    :title="locale.title"
                    :map="global.map"
                />
            </div>
        </header>

        <!-- facts: routes in the massif, its summits -->
        <div class="cg-massif__facts" v-if="other_routes > 0 || summits.length">
            <router-link
                v-if="other_routes > 0"
                class="cg-massif__chip cg-massif__chip--link"
                :to="{ name: 'mountaineerings', params: { locale: $route.params.locale }, query: { mount: global.id } }"
            >
                <i class="fa fa-list-ul" aria-hidden="true"></i>
                {{ $t('guide.massif.all_routes', { count: massif.routes_count }) }}
                <i class="fa fa-angle-right" aria-hidden="true"></i>
            </router-link>

            <a
                v-for="summit in summits"
                :key="summit.id"
                class="cg-massif__chip"
                :href="summit_url(summit)"
                target="_blank"
                rel="noopener"
                :title="$t('guide.massif.open_summit')"
            >
                <i class="fa fa-flag" aria-hidden="true"></i>
                {{ summit.title }}<span v-if="summit.height" class="cg-massif__height">{{ summit.height }} m</span>
            </a>
        </div>

        <!-- details: one tab per filled section -->
        <div class="cg-massif__body" v-if="tabs.length">
            <div class="cg-massif__tabs" role="tablist">
                <button
                    v-for="tab in tabs"
                    :key="tab.key"
                    :id="'massif-tab-' + tab.key"
                    type="button"
                    role="tab"
                    class="cg-massif__tab"
                    :class="{ 'is-active': open && active === tab.key }"
                    :aria-selected="open && active === tab.key ? 'true' : 'false'"
                    :aria-controls="'cg-massif-panel-' + tab.key"
                    @click="select(tab.key)"
                >
                    <i class="fa" :class="tab.icon" aria-hidden="true"></i>
                    <span>{{ tab.label }}</span>
                </button>
            </div>

            <div
                v-if="open && current"
                :id="'cg-massif-panel-' + current.key"
                role="tabpanel"
                class="cg-massif__panel"
                :class="{ 'is-clamped': clampable && !expanded }"
            >
                <div v-if="current.key === 'weather'" class="cg-massif__embed" v-html="global.weather"></div>
                <div v-else-if="current.key === 'map'">
                    <!-- both maps exist → switch between them -->
                    <div v-if="has_map && osm_summits.length" class="cg-massif__map-switch" role="group" :aria-label="$t('guide.massif.tab_map')">
                        <button
                            type="button"
                            class="cg-massif__map-btn"
                            :class="{ 'is-active': map_view === 'google' }"
                            :aria-pressed="map_view === 'google' ? 'true' : 'false'"
                            @click="map_view = 'google'"
                        >
                            <i class="fa fa-map" aria-hidden="true"></i> {{ $t('guide.massif.map_google') }}
                        </button>
                        <button
                            type="button"
                            class="cg-massif__map-btn"
                            :class="{ 'is-active': map_view === 'osm' }"
                            :aria-pressed="map_view === 'osm' ? 'true' : 'false'"
                            @click="map_view = 'osm'"
                        >
                            <i class="fa fa-map-o" aria-hidden="true"></i> {{ $t('guide.massif.map_osm') }}
                        </button>
                    </div>
                    <div v-if="shown_map === 'google'" class="cg-massif__map-embed" v-html="global.map"></div>
                    <massif-osm-map v-else-if="shown_map === 'osm'" :summits="osm_summits" :url="summit_url" />
                </div>
                <div v-else class="cg-massif__text article_text_block" v-html="current.html"></div>
            </div>

            <div class="cg-massif__foot">
                <button
                    v-if="open && clampable"
                    type="button"
                    class="cg-massif__more"
                    @click="expanded = !expanded"
                >
                    {{ expanded ? $t('guide.massif.show_less') : $t('guide.massif.show_more') }}
                    <i class="fa" :class="expanded ? 'fa-angle-up' : 'fa-angle-down'" aria-hidden="true"></i>
                </button>
                <button
                    type="button"
                    class="cg-massif__toggle"
                    @click="toggle()"
                >
                    {{ open ? $t('guide.massif.hide') : $t('guide.massif.read_about', { name: locale.title }) }}
                    <i class="fa" :class="open ? 'fa-angle-up' : 'fa-angle-down'" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </section>
</template>

<script>
    const SSH = process.env.MIX_APP_SSH || 'http://';
    const SUMMIT_BASE = SSH + (process.env.MIX_SUMMIT_URL || '');
    import { defineAsyncComponent } from 'vue'
    // Leaflet is only downloaded when the OpenStreetMap view is shown
    const MassifOsmMap = defineAsyncComponent(() => import('./MountMassifOsmMapComponent.vue'))

    export default {
        name: 'MountMassifCardComponent',
        components: { MassifOsmMap },
        props: {
            // { locale_data, global_data, routes_count, summits } from get_mount/on_page
            massif: { type: [Object, Array, String], default: null },
        },
        data() {
            return {
                // the details panel is always open when the page loads
                open: true,
                active: 'about',
                expanded: true,   // long texts start fully open; "Show less" shortens them
                // Map tab: 'google' = the pasted embed, 'osm' = summits on OpenStreetMap
                map_view: 'google',
            };
        },
        computed: {
            has_massif() {
                return !!(this.massif && this.massif.locale_data && this.massif.global_data && this.massif.locale_data.title);
            },
            locale() { return (this.massif && this.massif.locale_data) || {}; },
            global() { return (this.massif && this.massif.global_data) || {}; },
            summits() { return (this.massif && Array.isArray(this.massif.summits)) ? this.massif.summits : []; },
            // other routes besides the one being viewed
            other_routes() {
                const n = Number(this.massif && this.massif.routes_count) || 0;
                return n > 1 ? n - 1 : 0;
            },
            // one plain-text line under the title: the short description,
            // or the start of the main text when there is none
            lead() {
                const short = this.plain(this.locale.short_description);
                if (short && short.length > 3) return short;
                const text = this.plain(this.locale.text);
                if (!text) return '';
                return text.length > 180 ? text.slice(0, 177).replace(/\s+\S*$/, '') + '…' : text;
            },
            tabs() {
                const t = [];
                if (this.has_text(this.locale.text))      t.push({ key: 'about',   icon: 'fa-info-circle', label: this.$t('guide.massif.tab_about'),   html: this.locale.text });
                if (this.has_text(this.locale.best_time)) t.push({ key: 'season',  icon: 'fa-calendar',    label: this.$t('guide.massif.tab_season'),  html: this.locale.best_time });
                if (this.has_text(this.locale.how_get))   t.push({ key: 'getting', icon: 'fa-car',         label: this.$t('guide.massif.tab_getting'), html: this.locale.how_get });
                if (this.has_map || this.osm_summits.length) t.push({ key: 'map',     icon: 'fa-map-marker',  label: this.$t('guide.massif.tab_map'),     html: '' });
                if (this.has_text(this.locale.what_need)) t.push({ key: 'gear',    icon: 'fa-suitcase',    label: this.$t('guide.massif.tab_gear'),    html: this.locale.what_need });
                if (this.has_text(this.global.weather))   t.push({ key: 'weather', icon: 'fa-cloud',       label: this.$t('guide.massif.tab_weather'), html: '' });
                return t;
            },
            // the admin pastes a map embed (iframe) — tag-only, so has_text can't tell
            has_map() {
                return !!this.global.map && /<iframe|<img/i.test(String(this.global.map));
            },
            // summits the OpenStreetMap view can place
            osm_summits() {
                return this.summits.filter(s => s.latitude != null && s.longitude != null
                    && !isNaN(Number(s.latitude)) && !isNaN(Number(s.longitude)));
            },
            // which map the tab draws: the chosen one if it exists, else whichever exists
            shown_map() {
                if (this.map_view === 'osm' && this.osm_summits.length) return 'osm';
                if (this.has_map) return 'google';
                return this.osm_summits.length ? 'osm' : null;
            },
            current() {
                return this.tabs.find(t => t.key === this.active) || this.tabs[0] || null;
            },
            // long texts are cut to a preview with "Show more"; forecasts never
            clampable() {
                return !!(this.current && !['weather', 'map'].includes(this.current.key) && this.plain(this.current.html).length > 600);
            },
        },
        watch: {
            massif() {
                this.active = 'about';
                this.expanded = true;
                this.open = true;
                this.map_view = 'google';
            },
        },
        methods: {
            has_text(value) {
                return value != null && String(value).replace(/<[^>]*>|&nbsp;/g, '').trim() !== '';
            },
            plain(html) {
                if (!html) return '';
                const div = document.createElement('div');
                div.innerHTML = String(html);
                return (div.textContent || '').replace(/\s+/g, ' ').trim();
            },
            select(key) {
                if (this.open && this.current && this.current.key === key) {
                    this.set_open(false);
                    return;
                }
                this.active = key;
                this.expanded = true;
                this.set_open(true);
            },
            toggle() {
                if (!this.open && this.current) this.active = this.current.key;
                this.expanded = true;
                this.set_open(!this.open);
            },
            set_open(value) {
                this.open = value;
            },
            summit_url(summit) {
                return SUMMIT_BASE + '/summit/' + summit.url_title;
            },
        },
    }
</script>

<style scoped>
.cg-massif {
    margin: 0 0 1.75rem;
    background: var(--cg-surface);
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r-lg);
    box-shadow: var(--cg-shadow-sm);
    overflow: hidden;
}

/* header */
.cg-massif__head {
    display: flex;
    align-items: flex-start;
    gap: .9rem;
    padding: 1.1rem 1.25rem .9rem;
    background: linear-gradient(180deg, var(--cg-accent-soft), var(--cg-surface));
}
.cg-massif__icon {
    flex: 0 0 auto;
    display: grid;
    place-items: center;
    width: 46px;
    height: 46px;
    border-radius: var(--cg-r);
    background: var(--cg-accent);
    color: var(--cg-accent-contrast);
}
.cg-massif__titles { flex: 1 1 auto; min-width: 0; }
.cg-massif__eyebrow {
    display: block;
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .08em;
    text-transform: uppercase;
    color: var(--cg-accent-dark);
}
.cg-massif__title {
    margin: .1rem 0 0;
    font-family: var(--cg-font-display);
    font-size: 1.45rem;
    font-weight: 700;
    line-height: 1.2;
    color: var(--cg-ink);
    overflow-wrap: anywhere;
}
.cg-massif__lead {
    margin: .35rem 0 0;
    font-size: .95rem;
    line-height: 1.5;
    color: var(--cg-stone);
}
.cg-massif__map { flex: 0 0 auto; }

/* facts */
.cg-massif__facts {
    display: flex;
    flex-wrap: wrap;
    gap: .5rem;
    padding: 0 1.25rem 1rem;
}
.cg-massif__chip {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .35rem .75rem;
    border: 1px solid var(--cg-line);
    border-radius: 999px;
    background: var(--cg-chalk);
    color: var(--cg-ink);
    font-size: .85rem;
    font-weight: 600;
    text-decoration: none;
    transition: border-color .15s var(--cg-ease), background .15s var(--cg-ease);
}
.cg-massif__chip:hover { border-color: var(--cg-accent); color: var(--cg-ink); }
.cg-massif__chip .fa { color: var(--cg-accent); }
.cg-massif__chip--link {
    background: var(--cg-accent);
    border-color: var(--cg-accent);
    color: var(--cg-accent-contrast);
}
.cg-massif__chip--link .fa { color: inherit; }
.cg-massif__chip--link:hover { background: var(--cg-accent-dark); border-color: var(--cg-accent-dark); color: var(--cg-accent-contrast); }
.cg-massif__height {
    margin-left: .15rem;
    font-weight: 500;
    color: var(--cg-muted);
}

/* tabs */
.cg-massif__body { border-top: 1px solid var(--cg-line); }
.cg-massif__tabs {
    display: flex;
    gap: .35rem;
    padding: .75rem 1.25rem 0;
    overflow-x: auto;
    scrollbar-width: none;
}
.cg-massif__tabs::-webkit-scrollbar { display: none; }
.cg-massif__tab {
    flex: 0 0 auto;
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .45rem .85rem;
    border: 1px solid transparent;
    border-radius: var(--cg-r-sm);
    background: transparent;
    color: var(--cg-stone);
    font-size: .9rem;
    font-weight: 600;
    cursor: pointer;
    transition: background .15s var(--cg-ease), color .15s var(--cg-ease);
}
.cg-massif__tab:hover { background: var(--cg-chalk); color: var(--cg-ink); }
.cg-massif__tab.is-active {
    background: var(--cg-accent-soft);
    border-color: rgba(var(--cg-accent-rgb), .35);
    color: var(--cg-accent-dark);
}
.cg-massif__tab:focus-visible,
.cg-massif__toggle:focus-visible,
.cg-massif__more:focus-visible {
    outline: 2px solid var(--cg-accent);
    outline-offset: 2px;
}

/* panel */
.cg-massif__panel {
    position: relative;
    margin: .75rem 1.25rem 0;
    padding: 1rem 1.1rem;
    border-radius: var(--cg-r);
    background: var(--cg-chalk);
}
.cg-massif__panel.is-clamped {
    max-height: 260px;
    overflow: hidden;
}
.cg-massif__panel.is-clamped::after {
    content: "";
    position: absolute;
    left: 0;
    right: 0;
    bottom: 0;
    height: 90px;
    background: linear-gradient(180deg, rgba(255, 255, 255, 0), var(--cg-chalk));
    pointer-events: none;
}
.cg-massif__text { font-size: .95rem; line-height: 1.65; color: var(--cg-ink); }
.cg-massif__text :deep(p:last-child) { margin-bottom: 0; }
.cg-massif__text :deep(img) { max-width: 100%; height: auto; border-radius: var(--cg-r-sm); }
.cg-massif__embed { text-align: center; }
.cg-massif__embed :deep(iframe) { max-width: 100%; border: 0; border-radius: var(--cg-r-sm); }
/* map tab: Google / OpenStreetMap switch */
.cg-massif__map-switch {
    display: inline-flex;
    gap: .25rem;
    margin-bottom: .75rem;
    padding: .2rem;
    border-radius: 999px;
    background: var(--cg-chalk);
}
.cg-massif__map-btn {
    border: 0;
    padding: .35rem .85rem;
    border-radius: 999px;
    background: transparent;
    font-size: .85rem;
    font-weight: 700;
    color: var(--cg-stone);
    cursor: pointer;
}
.cg-massif__map-btn:hover { color: var(--cg-ink); }
.cg-massif__map-btn.is-active {
    background: #fff;
    color: var(--cg-accent-dark);
    box-shadow: var(--cg-shadow-sm);
}
.cg-massif__map-btn:focus-visible { outline: 2px solid var(--cg-accent); outline-offset: 2px; }
.cg-massif__map-btn .fa { margin-right: .25rem; }
/* map tab: the pasted embed fills the card width */
.cg-massif__map-embed :deep(iframe) {
    display: block;
    width: 100% !important;
    height: 380px !important;
    border: 0;
    border-radius: var(--cg-r-sm);
}
@media (max-width: 575.98px) {
    .cg-massif__map-embed :deep(iframe) { height: 280px !important; }
}

/* footer buttons */
.cg-massif__foot {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
    align-items: center;
    gap: .5rem;
    padding: .6rem 1.25rem .9rem;
}
.cg-massif__more,
.cg-massif__toggle {
    display: inline-flex;
    align-items: center;
    gap: .35rem;
    padding: .35rem .2rem;
    border: 0;
    background: none;
    color: var(--cg-accent-dark);
    font-size: .9rem;
    font-weight: 700;
    cursor: pointer;
}
.cg-massif__toggle { margin-left: auto; }
.cg-massif__more:hover,
.cg-massif__toggle:hover { text-decoration: underline; }

@media (max-width: 575.98px) {
    .cg-massif__head { flex-wrap: wrap; padding: 1rem 1rem .8rem; }
    .cg-massif__icon { width: 40px; height: 40px; }
    .cg-massif__title { font-size: 1.25rem; }
    .cg-massif__map { width: 100%; }
    .cg-massif__facts { padding: 0 1rem .9rem; }
    .cg-massif__tabs { padding: .65rem 1rem 0; }
    .cg-massif__panel { margin: .65rem 1rem 0; padding: .85rem .9rem; }
    .cg-massif__foot { padding: .55rem 1rem .8rem; }
}
</style>
