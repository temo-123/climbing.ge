<template>
    <!-- Article exports (admin, `export › show`): pick a category, search and
         tick articles, then download a PDF (texts, optionally sectors and
         routes), a CSV of the articles or a CSV of every route. The last
         category / language / options are remembered in localStorage. -->
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <breadcrumb />

            <section class="cg-page-head cg-dashboard__hero cg-art-notebook">
                <span class="cg-eyebrow">{{ $t('admin.export.eyebrow') }}</span>
                <h1>{{ $t('admin.export.page_title') }}</h1>
                <p>{{ $t('admin.export.page_lead') }}</p>
            </section>

            <div class="ex-layout">
                <!-- 1. articles -->
                <section class="ex-card ex-pick">
                    <h2 class="ex-step"><span>1</span> {{ $t('admin.export.step_articles') }}</h2>

                    <div class="ex-cats" role="tablist">
                        <button
                            v-for="c in categories"
                            :key="c"
                            type="button"
                            role="tab"
                            class="cg-chip"
                            :class="{ 'is-active': category === c }"
                            :aria-selected="category === c"
                            @click="pick_category(c)"
                        >{{ category_label(c) }}</button>
                    </div>

                    <div v-if="category" class="ex-filters">
                        <input type="search" class="form-control" v-model="query" :placeholder="$t('admin.export.search_placeholder')" />
                        <label class="form-check mb-0">
                            <input type="checkbox" class="form-check-input" v-model="only_published" @change="remember" />
                            <span class="form-check-label">{{ $t('admin.export.only_published') }}</span>
                        </label>
                    </div>

                    <div v-if="loading" class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>
                    <p v-else-if="!category" class="text-muted py-3 mb-0">{{ $t('admin.export.pick_category_hint') }}</p>
                    <p v-else-if="!filtered.length" class="text-muted py-3 mb-0">{{ $t('admin.export.no_articles_available') }}</p>
                    <template v-else>
                        <div class="ex-select-row">
                            <label class="form-check mb-0">
                                <input type="checkbox" class="form-check-input" :checked="all_selected" :indeterminate.prop="some_selected" @change="toggle_all" />
                                <span class="form-check-label fw-bold">{{ $t('admin.export.select_visible', { n: filtered.length }) }}</span>
                            </label>
                            <small class="text-muted">{{ $t('admin.export.selected_count', { n: selected.length }) }}</small>
                        </div>
                        <ul class="ex-list">
                            <li v-for="a in filtered" :key="a.id" :class="{ 'is-on': selected.includes(a.id) }">
                                <label>
                                    <input type="checkbox" class="form-check-input" :value="a.id" v-model="selected" />
                                    <span class="ex-list__text">
                                        <strong>{{ title(a) }}</strong>
                                        <small>
                                            <span :class="a.published ? 'text-success' : 'text-muted'">● {{ a.published ? $t('admin.export.yes_label') : $t('admin.site_info.no_label') }}</span>
                                            <template v-if="a.region"> · {{ a.region }}</template>
                                            <template v-if="a.sectors_count"> · {{ $t('admin.export.sectors_n', { n: a.sectors_count }) }}</template>
                                            · {{ $formatDate(a.updated_at) }}
                                        </small>
                                    </span>
                                </label>
                            </li>
                        </ul>
                    </template>
                </section>

                <!-- 2. format + options, sticky on wide screens -->
                <aside class="ex-card ex-side">
                    <h2 class="ex-step"><span>2</span> {{ $t('admin.export.step_format') }}</h2>
                    <div class="ex-formats">
                        <label v-for="f in formats" :key="f.key" class="ex-format" :class="{ 'is-active': format === f.key, 'is-disabled': f.disabled }">
                            <input type="radio" :value="f.key" v-model="format" :disabled="f.disabled" @change="remember" />
                            <i class="fa" :class="f.icon" aria-hidden="true"></i>
                            <span>
                                <strong>{{ $t('admin.export.formats.' + f.key) }}</strong>
                                <small>{{ $t('admin.export.formats.' + f.key + '_desc') }}</small>
                            </span>
                        </label>
                    </div>

                    <template v-if="format === 'pdf'">
                        <h3 class="ex-sub">{{ $t('admin.export.select_language_label') }}</h3>
                        <div class="cg-segmented w-100">
                            <button v-for="l in locales" :key="l.value" type="button" :class="{ active: locale === l.value }" @click="locale = l.value; remember()">{{ $t(l.label) }}</button>
                        </div>
                        <label v-if="has_sectors" class="form-check mt-3">
                            <input type="checkbox" class="form-check-input" v-model="export_sectors" @change="remember" />
                            <span class="form-check-label">{{ $t('admin.export.export_sectors_routes_label') }}</span>
                        </label>
                        <small v-if="has_sectors" class="text-muted d-block">{{ $t('admin.export.export_sectors_hint') }}</small>
                    </template>

                    <div class="ex-summary">
                        <i class="fa fa-file-o" aria-hidden="true"></i>
                        {{ $t('admin.export.summary', { n: selected.length, format: $t('admin.export.formats.' + format) }) }}
                    </div>
                    <button type="button" class="btn btn-primary btn-lg w-100" :disabled="!selected.length || exporting" @click="run_export">
                        <span v-if="exporting" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                        <i v-else class="fa fa-download" aria-hidden="true"></i>
                        {{ $t('admin.export.download') }}
                    </button>
                    <p v-if="error" class="text-danger small fw-bold mt-2 mb-0">{{ error }}</p>
                    <p v-if="last_export" class="text-success small fw-bold mt-2 mb-0"><i class="fa fa-check" aria-hidden="true"></i> {{ $t('admin.export.done', { name: last_export }) }}</p>
                </aside>
            </div>
        </div>
    </div>
</template>

<script>
import breadcrumb from '../../items/BreadcrumbComponent.vue'

const STORE_KEY = 'cg_export_settings'
// categories that have sectors / routes
const WITH_SECTORS = ['outdoor', 'ice']

export default {
    components: { breadcrumb },
    data() {
        const saved = this.read_settings()
        return {
            categories: [],
            articles: [],
            selected: [],
            category: saved.category || '',
            query: '',
            only_published: !!saved.only_published,
            format: saved.format || 'pdf',
            locale: saved.locale || 'ka',
            export_sectors: !!saved.export_sectors,
            loading: false,
            exporting: false,
            error: '',
            last_export: '',
            locales: [
                { value: 'ka', label: 'admin.export.georgian_option' },
                { value: 'us', label: 'admin.export.english_option' },
                { value: 'all', label: 'admin.export.all_languages_option' },
            ],
        }
    },
    computed: {
        has_sectors() { return WITH_SECTORS.includes(this.category) },
        formats() {
            return [
                { key: 'pdf', icon: 'fa-file-pdf-o' },
                { key: 'articles_csv', icon: 'fa-table' },
                { key: 'routes_csv', icon: 'fa-list-ol', disabled: !this.has_sectors },
            ]
        },
        filtered() {
            const q = this.query.trim().toLowerCase()
            return this.articles.filter(a =>
                (!this.only_published || a.published) &&
                (!q || [a.us_title, a.ka_title, a.url_title, a.region].some(v => String(v || '').toLowerCase().includes(q)))
            )
        },
        all_selected() { return this.filtered.length > 0 && this.filtered.every(a => this.selected.includes(a.id)) },
        some_selected() { return !this.all_selected && this.filtered.some(a => this.selected.includes(a.id)) },
    },
    watch: {
        has_sectors(v) { if (!v && this.format === 'routes_csv') this.format = 'pdf' },
    },
    mounted() {
        axios.get('/set_export/categories')
            .then(r => {
                this.categories = r.data || []
                if (this.category && this.categories.includes(this.category)) this.load_articles()
                else this.category = ''
            })
            .catch(() => { this.error = this.$t('admin.export.export_error') })
    },
    methods: {
        read_settings() {
            try { return JSON.parse(localStorage.getItem(STORE_KEY) || '{}') } catch (e) { return {} }
        },
        remember() {
            const s = { category: this.category, only_published: this.only_published, format: this.format, locale: this.locale, export_sectors: this.export_sectors }
            try { localStorage.setItem(STORE_KEY, JSON.stringify(s)) } catch (e) {}
        },
        category_label(c) {
            const key = 'user.favorites.categories.' + c
            return this.$te(key) ? this.$t(key) : c
        },
        title(a) {
            return (this.$i18n.locale === 'ka' ? a.ka_title || a.us_title : a.us_title || a.ka_title) || a.url_title
        },
        pick_category(c) {
            this.category = c
            this.selected = []
            this.query = ''
            this.remember()
            this.load_articles()
        },
        load_articles() {
            this.loading = true
            this.articles = []
            axios.get('/set_export/articles/' + this.category)
                .then(r => { this.articles = r.data || [] })
                .catch(() => { this.error = this.$t('admin.export.export_error') })
                .finally(() => { this.loading = false })
        },
        toggle_all() {
            const ids = this.filtered.map(a => a.id)
            this.selected = this.all_selected
                ? this.selected.filter(id => !ids.includes(id))
                : Array.from(new Set(this.selected.concat(ids)))
        },
        run_export() {
            this.exporting = true
            this.error = ''
            this.last_export = ''
            const urls = { pdf: '/set_export/articles_pdf', articles_csv: '/set_export/articles_csv', routes_csv: '/set_export/routes_csv' }
            const payload = { article_ids: this.selected, export_sectors: this.has_sectors && this.export_sectors, locale: this.locale }
            axios.post(urls[this.format], payload, { responseType: 'blob' })
                .then(response => {
                    const cd = response.headers['content-disposition'] || ''
                    const m = cd.match(/filename="?([^";]+)"?/)
                    const name = m ? m[1] : (this.format === 'pdf' ? 'articles.pdf' : 'export.csv')
                    const url = window.URL.createObjectURL(new Blob([response.data]))
                    const link = document.createElement('a')
                    link.href = url
                    link.setAttribute('download', name)
                    document.body.appendChild(link)
                    link.click()
                    link.remove()
                    setTimeout(() => window.URL.revokeObjectURL(url), 1000)
                    this.last_export = name
                })
                .catch(() => { this.error = this.$t('admin.export.export_error') })
                .finally(() => { this.exporting = false })
        },
    },
}
</script>

<style scoped>
.ex-layout { display: grid; gap: 1rem; align-items: start; }
@media (min-width: 992px) { .ex-layout { grid-template-columns: minmax(0, 1fr) 21rem; } .ex-side { position: sticky; top: calc(var(--cg-nav-h, 64px) + 1rem); } }
.ex-card { padding: 1.1rem; background: #fff; border: 1px solid var(--cg-line); border-radius: var(--cg-r-lg); box-shadow: var(--cg-shadow-sm); }
.ex-step { display: flex; align-items: center; gap: .6rem; margin: 0 0 .9rem; font-size: 1.15rem; }
.ex-step span { width: 1.8rem; height: 1.8rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; background: var(--cg-accent); color: #fff; font-size: .95rem; }
.ex-sub { margin: 1rem 0 .4rem; font-size: .9rem; font-weight: 800; }
.ex-cats { display: flex; flex-wrap: wrap; gap: .4rem; margin-bottom: .8rem; }
.ex-cats .cg-chip { cursor: pointer; }
.ex-cats .cg-chip.is-active { background: var(--cg-accent); border-color: var(--cg-accent); color: #fff; }
.ex-filters { display: flex; flex-wrap: wrap; align-items: center; gap: .5rem 1rem; margin-bottom: .75rem; }
.ex-filters .form-control { flex: 1 1 16rem; }
.ex-select-row { display: flex; justify-content: space-between; align-items: center; gap: .5rem; padding: .5rem .25rem; border-bottom: 1px solid var(--cg-line); }
.ex-list { max-height: 60vh; overflow-y: auto; margin: 0; padding: 0; list-style: none; }
.ex-list li { border-bottom: 1px solid var(--cg-chalk-2); }
.ex-list li.is-on { background: var(--cg-accent-soft); }
.ex-list label { display: flex !important; align-items: flex-start; gap: .65rem; margin: 0 !important; padding: .55rem .35rem; cursor: pointer; font-weight: 400 !important; }
.ex-list input { margin-top: .25rem; flex: 0 0 auto; }
.ex-list__text { display: flex; flex-direction: column; min-width: 0; }
.ex-list__text strong { color: var(--cg-ink); }
.ex-list__text small { color: var(--cg-muted); font-weight: 600; }
.ex-formats { display: grid; gap: .5rem; }
.ex-format { position: relative; display: flex !important; align-items: center; gap: .7rem; margin: 0 !important; padding: .65rem .8rem; border: 2px solid var(--cg-line); border-radius: var(--cg-r); cursor: pointer; }
.ex-format input { position: absolute; opacity: 0; }
.ex-format .fa { width: 1.4rem; font-size: 1.3rem; color: var(--cg-accent); text-align: center; }
.ex-format span { display: flex; flex-direction: column; line-height: 1.25; }
.ex-format small { color: var(--cg-muted); font-weight: 600; }
.ex-format.is-active { border-color: var(--cg-accent); background: var(--cg-accent-soft); }
.ex-format.is-disabled { opacity: .45; cursor: not-allowed; }
.ex-format:focus-within { outline: 3px solid rgba(var(--cg-accent-rgb), .35); outline-offset: 2px; }
.ex-side .cg-segmented { flex-wrap: wrap; }
.ex-side .cg-segmented > button { flex: 1 1 auto; }
.ex-format span { min-width: 0; overflow-wrap: anywhere; }
.ex-summary { margin: 1.1rem 0 .6rem; padding: .6rem .75rem; border-radius: var(--cg-r); background: var(--cg-chalk); font-weight: 700; font-size: .9rem; }
</style>
