<template>
    <!-- Guidebook dashboard → Overview: "how big is the guidebook and is it healthy".
         Headline numbers, routes by type, translation coverage, content by category,
         events, community and one data-health check — guidebook data only (users:
         Users list → Dashboard tab).
         Data: GET set_site_data/site_data_counts. Links only when can_open_route allows.
         docs/FUNCTIONS_DOCS/guide/guide_dashboard.md -->
    <div class="gdo">
        <div class="gdo-toolbar">
            <div>
                <h2 class="gdo-toolbar__title">{{ $t('admin.guide_dash.glance_title') }}</h2>
                <small v-if="last_updated" class="text-muted">{{ $t('admin.dashboards.updated_prefix') }} {{ last_updated }}</small>
            </div>
            <button type="button" class="btn btn-light btn-sm" @click="get_site_counts" :disabled="loading">
                <i class="fa fa-refresh" :class="{ 'fa-spin': loading }" aria-hidden="true"></i> {{ $t('common.refresh') }}
            </button>
        </div>

        <integrity_issues_alert />
        <admin_notifications_list />

        <div v-if="loading && !loaded_once" class="row g-3">
            <div v-for="n in 4" :key="n" class="col-6 col-lg-3"><div class="gdo-kpi gdo-kpi--skeleton"></div></div>
        </div>

        <template v-else>
            <!-- headline numbers -->
            <div class="row g-3 mb-4">
                <div v-for="k in kpis" :key="k.key" class="col-6 col-lg-3">
                    <component :is="k.to ? 'router-link' : 'div'" :to="k.to" class="gdo-kpi" :class="'gdo-kpi--' + k.tone">
                        <span class="gdo-kpi__icon"><i class="fa" :class="k.icon" aria-hidden="true"></i></span>
                        <span class="gdo-kpi__num">{{ fmt(k.value) }}</span>
                        <span class="gdo-kpi__label">{{ k.label }}</span>
                        <span v-if="k.sub" class="gdo-kpi__sub">{{ k.sub }}</span>
                    </component>
                </div>
            </div>

            <div class="row g-4">
                <!-- routes by type -->
                <div class="col-lg-6">
                    <section class="cg-panel gdo-panel">
                        <header class="gdo-panel__head">
                            <h3><i class="fa fa-bar-chart" aria-hidden="true"></i> {{ $t('admin.guide_dash.routes_by_type') }}</h3>
                            <router-link v-if="link('routeAndSectorList')" :to="link('routeAndSectorList')" class="gdo-more">{{ $t('admin.guide_dash.open') }} <i class="fa fa-angle-right" aria-hidden="true"></i></router-link>
                        </header>
                        <p class="gdo-hint">{{ $t('admin.guide_dash.routes_hint') }}</p>
                        <div class="gdo-bars">
                            <div v-for="b in route_types" :key="b.key" class="gdo-bar">
                                <span class="gdo-bar__label">{{ b.label }}</span>
                                <span class="gdo-bar__track"><span class="gdo-bar__fill" :style="{ width: b.pct + '%', background: b.color }"></span></span>
                                <span class="gdo-bar__num">{{ fmt(b.value) }}</span>
                            </div>
                        </div>
                        <p class="gdo-foot">{{ $t('admin.guide_dash.pitches', { n: fmt(counts.mtp_pitch_count) }) }}</p>
                    </section>
                </div>

                <!-- translations -->
                <div class="col-lg-6">
                    <section class="cg-panel gdo-panel">
                        <header class="gdo-panel__head">
                            <h3><i class="fa fa-language" aria-hidden="true"></i> {{ $t('admin.guide_dash.translations') }}</h3>
                        </header>
                        <p class="gdo-hint">{{ $t('admin.guide_dash.translations_hint', { n: fmt(counts.global_articles_count) }) }}</p>
                        <div v-for="l in locales" :key="l.key" class="gdo-lang">
                            <div class="gdo-lang__row">
                                <span class="badge text-bg-light">{{ l.code }}</span>
                                <span class="gdo-lang__name">{{ l.label }}</span>
                                <strong class="ms-auto">{{ l.pct }}%</strong>
                            </div>
                            <div class="progress" role="progressbar" :aria-valuenow="l.pct" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar" :class="l.pct >= 100 ? 'bg-success' : (l.pct >= 80 ? 'bg-warning' : 'bg-danger')" :style="{ width: Math.min(100, l.pct) + '%' }"></div>
                            </div>
                            <small class="text-muted">
                                {{ l.missing > 0 ? $t('admin.guide_dash.missing', { n: fmt(l.missing) }) : $t('admin.guide_dash.all_translated') }}
                            </small>
                        </div>
                    </section>
                </div>

                <!-- content by category -->
                <div class="col-lg-6">
                    <section class="cg-panel gdo-panel">
                        <header class="gdo-panel__head">
                            <h3><i class="fa fa-th-large" aria-hidden="true"></i> {{ $t('admin.guide_dash.content') }}</h3>
                        </header>
                        <ul class="gdo-list">
                            <li v-for="r in content_rows" :key="r.key">
                                <component :is="r.to ? 'router-link' : 'div'" :to="r.to" class="gdo-list__row">
                                    <i class="fa gdo-list__icon" :class="r.icon" aria-hidden="true"></i>
                                    <span class="gdo-list__label">{{ r.label }}</span>
                                    <strong class="gdo-list__num">{{ fmt(r.value) }}</strong>
                                    <i v-if="r.to" class="fa fa-angle-right gdo-list__go" aria-hidden="true"></i>
                                </component>
                            </li>
                        </ul>
                    </section>
                </div>

                <div class="col-lg-6 d-flex flex-column gap-4">
                    <!-- events -->
                    <section class="cg-panel gdo-panel">
                        <header class="gdo-panel__head">
                            <h3><i class="fa fa-calendar" aria-hidden="true"></i> {{ $t('admin.dashboards.events_competitions_section') }}</h3>
                            <router-link v-if="link('eventList')" :to="link('eventList')" class="gdo-more">{{ $t('admin.guide_dash.open') }} <i class="fa fa-angle-right" aria-hidden="true"></i></router-link>
                        </header>
                        <div class="gdo-pairs">
                            <div class="gdo-pair">
                                <span class="gdo-pair__label">{{ $t('admin.guide_dash.events') }}</span>
                                <span><strong class="text-success">{{ fmt(counts.active_events_count) }}</strong> {{ $t('admin.guide_dash.upcoming') }}</span>
                                <span class="text-muted">{{ fmt(counts.completed_events_count) }} {{ $t('admin.guide_dash.past') }}</span>
                            </div>
                            <div class="gdo-pair">
                                <span class="gdo-pair__label">{{ $t('admin.guide_dash.competitions') }}</span>
                                <span><strong class="text-success">{{ fmt(counts.active_comprtitions_count) }}</strong> {{ $t('admin.guide_dash.upcoming') }}</span>
                                <span class="text-muted">{{ fmt(counts.completed_comprtitions_count) }} {{ $t('admin.guide_dash.past') }}</span>
                            </div>
                        </div>
                    </section>

                    <!-- community -->
                    <section class="cg-panel gdo-panel">
                        <header class="gdo-panel__head">
                            <h3><i class="fa fa-comments-o" aria-hidden="true"></i> {{ $t('admin.guide_dash.community') }}</h3>
                            <router-link v-if="link('commentsAndReviewsList')" :to="link('commentsAndReviewsList')" class="gdo-more">{{ $t('admin.guide_dash.open') }} <i class="fa fa-angle-right" aria-hidden="true"></i></router-link>
                        </header>
                        <div v-if="Number(counts.article_comment_complaint_count) > 0" class="gdo-flag">
                            <i class="fa fa-flag" aria-hidden="true"></i>
                            <span>{{ $t('admin.guide_dash.complaints', { n: fmt(counts.article_comment_complaint_count) }) }}</span>
                        </div>
                        <ul class="gdo-list gdo-list--compact">
                            <li v-for="r in community_rows" :key="r.key">
                                <component :is="r.to ? 'router-link' : 'div'" :to="r.to" class="gdo-list__row">
                                    <i class="fa gdo-list__icon" :class="r.icon" aria-hidden="true"></i>
                                    <span class="gdo-list__label">{{ r.label }}</span>
                                    <strong class="gdo-list__num">{{ fmt(r.value) }}</strong>
                                    <i v-if="r.to" class="fa fa-angle-right gdo-list__go" aria-hidden="true"></i>
                                </component>
                            </li>
                        </ul>
                    </section>
                </div>
            </div>

            <!-- data health: one check for every content type (shows only when something is wrong) -->
            <section class="gdo-health">
                <h3 class="gdo-health__title"><i class="fa fa-stethoscope" aria-hidden="true"></i> {{ $t('admin.guide_dash.health') }}</h3>
                <p class="gdo-hint mb-2">{{ $t('admin.guide_dash.health_hint') }}</p>
                <entity_locale_errors :tables="['articles', 'mounts']" @checked="n => health_issues = n" />
                <div v-if="health_issues === 0" class="gdo-ok">
                    <i class="fa fa-check-circle" aria-hidden="true"></i> {{ $t('admin.guide_dash.health_ok') }}
                </div>
            </section>
        </template>
    </div>
</template>

<script>
import entity_locale_errors from './EntityLocaleErrorsComponent.vue'
import integrity_issues_alert from './IntegrityIssuesAlertComponent.vue'
import admin_notifications_list from '../notificatione/AdminNotificationsListComponent.vue'
import { can_open_route } from '../../../../services/ability/canOpen.js'

export default {
    components: { entity_locale_errors, integrity_issues_alert, admin_notifications_list },
    data() {
        return {
            counts: {},
            loading: false,
            loaded_once: false,
            last_updated: '',
            health_issues: null,   // null = still checking
        }
    },
    computed: {
        kpis() {
            const c = this.counts
            const t = (k) => this.$t('admin.dashboards.' + k)
            return [
                { key: 'spots', icon: 'fa-map-marker', tone: 'accent', value: c.outdoor_climbing, label: this.$t('admin.guide_dash.outdoor_spots'),
                  sub: this.$t('admin.guide_dash.in_regions', { n: this.fmt(c.region) }), to: this.link('articlelist', { article_category: 'outdoor' }) },
                { key: 'sectors', icon: 'fa-th', tone: 'teal', value: c.sectors_count, label: this.$t('common.sectors'), to: this.link('routeAndSectorList') },
                { key: 'routes', icon: 'fa-line-chart', tone: 'rope', value: (Number(c.routes_count) || 0) + (Number(c.mtp_count) || 0), label: t('routes_label'),
                  sub: this.$t('admin.guide_dash.incl_mtp', { n: this.fmt(c.mtp_count) }), to: this.link('routeAndSectorList') },
                { key: 'articles', icon: 'fa-file-text-o', tone: 'slate', value: c.global_articles_count, label: t('total_articles_label'),
                  sub: 'EN ' + this.fmt(c.us_articles_count) + ' · KA ' + this.fmt(c.ka_articles_count) },
            ]
        },
        route_types() {
            const c = this.counts
            const t = (k) => this.$t('admin.dashboards.' + k)
            const rows = [
                { key: 'sport', label: t('sport_climbing_label'), value: c.sport_climbing_routes_count, color: 'var(--cg-accent)' },
                { key: 'boulder', label: t('boulder_label'), value: c.bouldering_routes_count, color: '#18a06f' },
                { key: 'top_rope', label: t('top_rope_label'), value: c.top_rope_routes_count, color: '#1f93b0' },
                { key: 'trad', label: t('trad_climbing_label'), value: c.tred_routes_count, color: '#e07b2e' },
                { key: 'mtp', label: t('multi_pitch_label'), value: c.mtp_count, color: '#6c45c4' },
                { key: 'aid', label: t('aid_label'), value: c.aid_routes_count, color: '#56646e' },
            ]
            const max = Math.max(1, ...rows.map(r => Number(r.value) || 0))
            return rows.map(r => ({ ...r, pct: Math.round(100 * (Number(r.value) || 0) / max) }))
        },
        locales() {
            const total = Number(this.counts.global_articles_count) || 0
            const row = (key, code, label, n) => {
                n = Number(n) || 0
                return { key, code, label, pct: total ? Math.round(100 * n / total) : 0, missing: Math.max(0, total - n) }
            }
            return [
                row('us', 'EN', this.$t('admin.guide_dash.english'), this.counts.us_articles_count),
                row('ka', 'KA', this.$t('admin.guide_dash.georgian'), this.counts.ka_articles_count),
            ]
        },
        content_rows() {
            const c = this.counts
            const t = (k) => this.$t('admin.dashboards.' + k)
            const cat = (k) => this.link('articlelist', { article_category: k })
            return [
                { key: 'outdoor', icon: 'fa-tree', label: this.$t('admin.guide_dash.outdoor_spots'), value: c.outdoor_climbing, to: cat('outdoor') },
                { key: 'indoor', icon: 'fa-building', label: t('indoor_gyms_label'), value: c.indoor_gyms, to: cat('indoor') },
                { key: 'ice', icon: 'fa-snowflake-o', label: t('ice_climbing_label'), value: c.ice_climbing, to: cat('ice') },
                { key: 'mount', icon: 'fa-area-chart', label: t('mountaineering_routes_label'), value: c.mountaineering_route, to: cat('mount_route') },
                { key: 'massifs', icon: 'fa-globe', label: t('mount_massifs_label'), value: c.mount_masives, to: cat('mount_route') },
                { key: 'other', icon: 'fa-bicycle', label: t('other_activities_label'), value: c.other_antyvity, to: cat('other') },
                { key: 'news', icon: 'fa-newspaper-o', label: t('news_label'), value: c.news, to: cat('news') },
                { key: 'tech', icon: 'fa-shield', label: t('techtip_label'), value: c.techtip, to: cat('tech_tip') },
            ]
        },
        community_rows() {
            const c = this.counts
            const t = (k) => this.$t('admin.dashboards.' + k)
            return [
                { key: 'comments', icon: 'fa-comment-o', label: t('article_comments_label'), value: c.article_comments_count, to: this.link('commentsAndReviewsList') },
                { key: 'gallery', icon: 'fa-picture-o', label: t('gallery_images_section'), value: c.gallery_images, to: this.link('multimedia') },
                { key: 'slider', icon: 'fa-image', label: t('index_header_slider_images_label'), value: c.index_header_images, to: this.link('multimedia') },
                { key: 'followers', icon: 'fa-bell-o', label: t('guide_followers_label'), value: c.guid_follovers },
            ]
        },
    },
    mounted() {
        this.get_site_counts()
    },
    methods: {
        fmt(v) {
            if (v === null || v === undefined || v === '') return '0'
            if (typeof v === 'string' && isNaN(Number(v))) return v
            return new Intl.NumberFormat(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB').format(Number(v) || 0)
        },
        // route object only when this user may open it (otherwise the guard bounces home)
        link(name, params) {
            const to = params ? { name, params } : { name }
            return can_open_route(this.$router, this.$ability, to) ? to : null
        },
        get_site_counts() {
            this.loading = true
            axios
                .get('set_site_data/site_data_counts')
                .then(response => {
                    this.counts = response.data || {}
                    this.last_updated = new Date().toLocaleTimeString(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB', { hour: '2-digit', minute: '2-digit' })
                })
                .catch(() => {
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        title: this.$t('admin.dashboards.dashboard_toast_title'),
                        message: this.$t('admin.dashboards.failed_load_dashboard_data'),
                    })
                })
                .finally(() => {
                    this.loading = false
                    this.loaded_once = true
                })
        },
    }
}
</script>

<style scoped>
.gdo-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .5rem;
    margin: 1.75rem 0 1rem;
}
.gdo-toolbar__title { font-size: 1.25rem; font-weight: 700; margin: 0; color: var(--cg-ink); }

/* headline numbers */
.gdo-kpi {
    position: relative;
    display: flex;
    flex-direction: column;
    height: 100%;
    min-height: 8.5rem;
    padding: 1rem 1.1rem;
    border: 1px solid var(--cg-line);
    border-radius: var(--cg-r-lg);
    background: #fff;
    box-shadow: var(--cg-shadow-sm);
    color: var(--cg-ink);
    text-decoration: none;
    overflow: hidden;
    transition: transform .2s ease, box-shadow .2s ease;
}
a.gdo-kpi:hover { transform: translateY(-2px); box-shadow: var(--cg-shadow); color: var(--cg-ink); }
.gdo-kpi::before { content: ''; position: absolute; inset: 0 auto 0 0; width: 4px; background: var(--tone); }
.gdo-kpi--accent { --tone: var(--cg-accent); }
.gdo-kpi--teal { --tone: #1f93b0; }
.gdo-kpi--rope { --tone: var(--cg-rope); }
.gdo-kpi--slate { --tone: #56646e; }
.gdo-kpi__icon {
    display: inline-flex; align-items: center; justify-content: center;
    width: 2.2rem; height: 2.2rem; margin-bottom: .5rem;
    border-radius: 50%;
    background: color-mix(in srgb, var(--tone) 14%, #fff);
    color: var(--tone);
}
.gdo-kpi__num { font-family: var(--cg-font-display); font-size: 2.2rem; font-weight: 700; line-height: 1; }
.gdo-kpi__label { margin-top: .25rem; font-weight: 700; font-size: .9rem; }
.gdo-kpi__sub { font-size: .8rem; color: var(--cg-stone); }
.gdo-kpi--skeleton { background: var(--cg-chalk-2); box-shadow: none; animation: gdo-pulse 1.2s ease-in-out infinite; }
@keyframes gdo-pulse { 50% { opacity: .55; } }

/* panels */
.gdo-panel { height: 100%; }
.gdo-panel__head { display: flex; align-items: center; justify-content: space-between; gap: .5rem; margin-bottom: .35rem; }
.gdo-panel__head h3 { margin: 0; font-size: 1.05rem; font-weight: 700; color: var(--cg-ink); }
.gdo-panel__head h3 .fa { color: var(--cg-accent); margin-right: .3rem; }
.gdo-more { font-size: .85rem; font-weight: 700; color: var(--cg-accent-dark); text-decoration: none; white-space: nowrap; }
.gdo-more:hover { text-decoration: underline; }
.gdo-hint { font-size: .85rem; color: var(--cg-stone); margin: 0 0 .9rem; }
.gdo-foot { font-size: .8rem; color: var(--cg-muted); margin: .75rem 0 0; }

.gdo-bars { display: grid; gap: .55rem; }
.gdo-bar { display: grid; grid-template-columns: 8.5rem 1fr 3.5rem; align-items: center; gap: .6rem; font-size: .9rem; }
.gdo-bar__label { color: var(--cg-ink-2); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.gdo-bar__track { height: .6rem; border-radius: 1rem; background: var(--cg-chalk-2); overflow: hidden; }
.gdo-bar__fill { display: block; height: 100%; border-radius: 1rem; transition: width .5s var(--cg-ease); }
.gdo-bar__num { text-align: right; font-weight: 700; font-variant-numeric: tabular-nums; }

.gdo-lang { margin-bottom: 1rem; }
.gdo-lang:last-child { margin-bottom: 0; }
.gdo-lang__row { display: flex; align-items: center; gap: .5rem; margin-bottom: .3rem; }
.gdo-lang__name { font-weight: 600; }
.gdo-lang .progress { height: .55rem; margin-bottom: .2rem; }

.gdo-list { list-style: none; margin: 0; padding: 0; }
.gdo-list__row {
    display: flex; align-items: center; gap: .7rem;
    padding: .55rem .4rem;
    border-bottom: 1px solid var(--cg-line);
    color: var(--cg-ink);
    text-decoration: none;
    border-radius: var(--cg-r);
}
.gdo-list li:last-child .gdo-list__row { border-bottom: 0; }
a.gdo-list__row:hover { background: var(--cg-accent-soft); color: var(--cg-accent-dark); }
.gdo-list__icon { width: 1.2rem; text-align: center; color: var(--cg-muted); }
.gdo-list__label { flex: 1; min-width: 0; }
.gdo-list__num { font-variant-numeric: tabular-nums; }
.gdo-list__go { color: var(--cg-muted); }
.gdo-list--compact .gdo-list__row { padding: .4rem .4rem; }

.gdo-pairs { display: grid; grid-template-columns: 1fr 1fr; gap: .75rem; }
.gdo-pair { display: flex; flex-direction: column; gap: .1rem; padding: .7rem .85rem; border-radius: var(--cg-r); background: var(--cg-chalk); }
.gdo-pair__label { font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .03em; color: var(--cg-muted); }

.gdo-flag {
    display: flex; align-items: center; gap: .5rem;
    margin-bottom: .6rem; padding: .5rem .75rem;
    border-radius: var(--cg-r);
    background: #fdecea; color: #b02a37; font-weight: 700; font-size: .9rem;
}

.gdo-health { margin-top: 2rem; }
.gdo-health__title { font-size: 1.05rem; font-weight: 700; margin: 0 0 .2rem; }
.gdo-health__title .fa { color: var(--cg-accent); margin-right: .3rem; }

.gdo-ok {
    display: flex; align-items: center; gap: .5rem;
    padding: .6rem .85rem;
    border-radius: var(--cg-r);
    background: #e8f6ef; color: #15784f; font-weight: 600; font-size: .9rem;
}


@media (max-width: 575.98px) {
    .gdo-kpi { min-height: 7.5rem; padding: .85rem; }
    .gdo-kpi__num { font-size: 1.8rem; }
    .gdo-bar { grid-template-columns: 6.5rem 1fr 3rem; font-size: .85rem; }
    .gdo-pairs { grid-template-columns: 1fr; }
}
@media (prefers-reduced-motion: reduce) {
    .gdo-kpi, .gdo-bar__fill { transition: none; }
    .gdo-kpi--skeleton { animation: none; }
}
</style>
