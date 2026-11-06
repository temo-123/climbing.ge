<template>
    <div>
        <p v-if="filter !== 'all'" class="seo-hint"><i class="fa fa-lightbulb-o" aria-hidden="true"></i> {{ $t('admin.seo.hint.' + filter) }}</p>

        <div v-if="loading" class="text-muted py-3">{{ $t('admin.seo.loading') }}</div>
        <div v-else-if="!rows.length" class="text-muted py-3">{{ $t('admin.seo.no_pages') }}</div>

        <div v-else class="table-responsive">
            <table class="table table-sm align-middle seo-table">
                <thead>
                    <tr>
                        <th>{{ $t('admin.seo.col.page') }}</th>
                        <th class="text-end">{{ $t('admin.seo.col.clicks') }}</th>
                        <th class="text-end">{{ $t('admin.seo.col.impressions') }}</th>
                        <th class="text-end">{{ $t('admin.seo.col.ctr') }}</th>
                        <th class="text-end">{{ $t('admin.seo.col.position') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="r in rows" :key="r.url_hash">
                        <tr>
                            <td class="seo-table__page">
                                <div class="seo-table__title">
                                    {{ (r.content && r.content.title) || path(r.url) }}
                                    <span class="badge text-bg-light">{{ r.locale === 'ka' ? 'KA' : 'EN' }}</span>
                                    <span v-if="r.content" class="badge text-bg-light">{{ $t('admin.seo.type.' + r.content.type) }}</span>
                                </div>
                                <div class="seo-table__url text-muted">
                                    <a :href="r.url" target="_blank" rel="noopener">{{ url_host(r.url) }}{{ path(r.url) }}</a>
                                    <router-link v-if="edit_link(r)" :to="edit_link(r)" class="ms-2">{{ $t('admin.seo.edit') }}</router-link>
                                </div>
                                <div class="mt-1">
                                    <span v-for="f in r.flags" :key="f" class="badge seo-flag" :class="'seo-flag--' + f">{{ $t('admin.seo.flag.' + f) }}</span>
                                </div>
                            </td>
                            <td class="text-end">
                                {{ number(r.clicks) }}
                                <div class="seo-delta" :class="delta_class(r.clicks - r.prev_clicks)">{{ signed(r.clicks - r.prev_clicks) }}</div>
                            </td>
                            <td class="text-end">{{ number(r.impressions) }}</td>
                            <td class="text-end">
                                {{ r.ctr }}%
                                <div v-if="r.flags.includes('low_ctr')" class="text-muted small">{{ $t('admin.seo.expected', { value: r.expected_ctr }) }}</div>
                            </td>
                            <td class="text-end">
                                {{ r.position }}
                                <div v-if="r.prev_position !== null" class="seo-delta" :class="delta_class(r.prev_position - r.position)">
                                    {{ r.prev_position > r.position ? '▲' : r.prev_position < r.position ? '▼' : '' }} {{ $t('admin.seo.was', { value: r.prev_position }) }}
                                </div>
                            </td>
                            <td class="text-end">
                                <button type="button" class="btn btn-outline-secondary btn-sm" :aria-expanded="open === r.url_hash ? 'true' : 'false'" @click="toggle(r)">
                                    {{ $t('admin.seo.queries') }}
                                </button>
                            </td>
                        </tr>
                        <tr v-if="open === r.url_hash" class="seo-table__queries">
                            <td colspan="6">
                                <div v-if="queries_loading" class="text-muted">{{ $t('admin.seo.loading') }}</div>
                                <div v-else-if="!queries.length" class="text-muted">{{ $t('admin.seo.no_queries') }}</div>
                                <table v-else class="table table-sm mb-0">
                                    <thead><tr>
                                        <th>{{ $t('admin.seo.col.query') }}</th>
                                        <th class="text-end">{{ $t('admin.seo.col.clicks') }}</th>
                                        <th class="text-end">{{ $t('admin.seo.col.impressions') }}</th>
                                        <th class="text-end">{{ $t('admin.seo.col.ctr') }}</th>
                                        <th class="text-end">{{ $t('admin.seo.col.position') }}</th>
                                    </tr></thead>
                                    <tbody>
                                        <tr v-for="q in queries" :key="q.query">
                                            <td>{{ q.query }}</td>
                                            <td class="text-end">{{ number(q.clicks) }}</td>
                                            <td class="text-end">{{ number(q.impressions) }}</td>
                                            <td class="text-end">{{ q.ctr }}%</td>
                                            <td class="text-end">{{ q.position }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="small text-muted mt-1">{{ $t('admin.seo.queries_note') }}</div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    import { can_open_route } from '../../../../../services/ability/canOpen'

    export default {
        props: {
            days: { type: Number, default: 28 },
            filter: { type: String, default: 'all' },   // all | losing | striking | low_ctr
            host: { type: String, default: '' },
        },
        data() {
            return { rows: [], loading: false, open: null, queries: [], queries_loading: false }
        },
        watch: {
            days() { this.load() },
            filter() { this.load() },
            host() { this.load() },
        },
        mounted() { this.load() },
        methods: {
            load() {
                this.loading = true
                this.open = null
                axios.get('set_seo/pages', { params: { days: this.days, filter: this.filter, host: this.host || undefined } })
                    .then((res) => { this.rows = res.data })
                    .catch(() => { this.rows = [] })
                    .finally(() => { this.loading = false })
            },
            toggle(r) {
                if (this.open === r.url_hash) { this.open = null; return }
                this.open = r.url_hash
                this.queries = []
                this.queries_loading = true
                axios.get('set_seo/page_queries/' + r.url_hash)
                    .then((res) => { this.queries = res.data })
                    .finally(() => { this.queries_loading = false })
            },
            edit_link(r) {
                const c = r.content
                if (!c || !c.edit_route) return null
                const to = { name: c.edit_route, params: { id: c.id } }
                return can_open_route(this.$router, this.$ability, to) ? to : null
            },
            url_host(url) { try { return new URL(url).host } catch (e) { return '' } },
            path(url) { try { return decodeURIComponent(new URL(url).pathname) } catch (e) { return url } },
            number(n) { return new Intl.NumberFormat(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB').format(n || 0) },
            signed(n) { return n === 0 ? '±0' : (n > 0 ? '+' : '−') + this.number(Math.abs(n)) },
            delta_class(n) { return n > 0 ? 'is-up' : n < 0 ? 'is-down' : 'is-flat' },
        },
    }
</script>

<style scoped>
    .seo-hint { padding: .6rem .8rem; background: var(--cg-accent-soft); border-radius: var(--cg-r-sm); color: var(--cg-ink-2); }
    .seo-table td, .seo-table th { font-variant-numeric: tabular-nums; }
    .seo-table__page { min-width: 260px; }
    .seo-table__title { font-weight: 600; color: var(--cg-ink); }
    .seo-table__title .badge { font-weight: 500; margin-left: .25rem; }
    .seo-table__url { font-size: .82rem; word-break: break-all; }
    .seo-table__queries > td { background: var(--cg-chalk); }
    .seo-delta { font-size: .78rem; }
    .seo-delta.is-up { color: var(--bs-success-text-emphasis); }
    .seo-delta.is-down { color: var(--bs-danger-text-emphasis); }
    .seo-delta.is-flat { color: var(--cg-muted); }
    .seo-flag { margin-right: .25rem; font-weight: 500; border: 1px solid var(--cg-line); background: var(--cg-surface, #fff); color: var(--cg-ink-2); }
    .seo-flag--losing::before { content: "▼ "; color: var(--bs-danger-text-emphasis); }
    .seo-flag--striking::before { content: "◎ "; color: var(--cg-accent); }
    .seo-flag--low_ctr::before { content: "✎ "; color: var(--cg-accent); }
</style>
