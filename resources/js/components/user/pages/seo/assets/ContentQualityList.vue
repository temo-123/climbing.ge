<template>
    <div>
        <p class="text-muted">{{ $t('admin.seo.quality.intro') }}</p>
        <p v-if="data && !data.has_traffic" class="seo-hint">{{ $t('admin.seo.quality.no_traffic') }}</p>

        <div v-if="loading" class="text-muted py-3">{{ $t('admin.seo.loading') }}</div>
        <template v-else-if="data">
            <!-- issue counts — click to filter -->
            <div class="seo-issue-chips mb-3" role="group" :aria-label="$t('admin.seo.quality.filter_issue')">
                <button type="button" class="btn btn-sm" :class="issue === '' ? 'btn-primary' : 'btn-outline-secondary'" @click="issue = ''">
                    {{ $t('admin.seo.quality.all') }} <span class="badge text-bg-light">{{ data.rows.length }}</span>
                </button>
                <button v-for="(count, code) in data.summary" :key="code" type="button" class="btn btn-sm"
                    :class="issue === code ? 'btn-primary' : 'btn-outline-secondary'" @click="issue = code" :title="$t('admin.seo.issue_fix.' + code)">
                    {{ $t('admin.seo.issue.' + code) }} <span class="badge text-bg-light">{{ count }}</span>
                </button>
            </div>

            <div class="row g-2 mb-3">
                <div class="col-sm-4">
                    <select v-model="type" class="form-select form-select-sm" :aria-label="$t('admin.seo.quality.filter_type')">
                        <option value="">{{ $t('admin.seo.quality.all_types') }}</option>
                        <option v-for="t in types" :key="t" :value="t">{{ $t('admin.seo.type.' + t) }}</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <select v-model="lang" class="form-select form-select-sm" :aria-label="$t('admin.seo.quality.filter_lang')">
                        <option value="">{{ $t('admin.seo.quality.both_langs') }}</option>
                        <option value="en">English</option>
                        <option value="ka">ქართული</option>
                    </select>
                </div>
            </div>

            <p v-if="issue" class="seo-hint"><i class="fa fa-lightbulb-o" aria-hidden="true"></i> {{ $t('admin.seo.issue_fix.' + issue) }}</p>

            <div v-if="!filtered.length" class="text-muted py-3">{{ $t('admin.seo.quality.none') }}</div>
            <div v-else class="table-responsive">
                <table class="table table-sm align-middle seo-table">
                    <thead>
                        <tr>
                            <th>{{ $t('admin.seo.col.content') }}</th>
                            <th>{{ $t('admin.seo.col.issues') }}</th>
                            <th class="text-end">{{ $t('admin.seo.col.impressions') }}</th>
                            <th class="text-end">{{ $t('admin.seo.col.clicks') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="r in page_rows" :key="r.type + r.id">
                            <td class="seo-table__page">
                                <div class="seo-table__title">
                                    {{ (r.content && r.content.title) || ('#' + r.id) }}
                                    <span class="badge text-bg-light">{{ $t('admin.seo.type.' + r.type) }}</span>
                                </div>
                                <div class="seo-table__url">
                                    <a v-if="r.content && r.content.url" :href="r.content.url" target="_blank" rel="noopener">{{ $t('admin.seo.open_page') }}</a>
                                    <router-link v-if="edit_link(r)" :to="edit_link(r)" class="ms-2">{{ $t('admin.seo.edit') }}</router-link>
                                </div>
                            </td>
                            <td>
                                <span v-for="(i, n) in visible_issues(r)" :key="n" class="badge seo-issue" :title="$t('admin.seo.issue_fix.' + i.code)">
                                    {{ $t('admin.seo.issue.' + i.code) }}<template v-if="i.locale"> · {{ i.locale.toUpperCase() }}</template><template v-if="i.value !== undefined"> ({{ i.value }}{{ i.min ? ' / ' + i.min : '' }})</template>
                                </span>
                            </td>
                            <td class="text-end">{{ number(r.impressions) }}</td>
                            <td class="text-end">{{ number(r.clicks) }}</td>
                        </tr>
                    </tbody>
                </table>
                <button v-if="filtered.length > shown" type="button" class="btn btn-outline-secondary btn-sm" @click="shown += 50">
                    {{ $t('admin.seo.show_more', { count: filtered.length - shown }) }}
                </button>
            </div>
        </template>
    </div>
</template>

<script>
    import { can_open_route } from '../../../../../services/ability/canOpen'

    export default {
        data() {
            return { data: null, loading: false, issue: '', type: '', lang: '', shown: 50 }
        },
        computed: {
            types() { return [...new Set((this.data ? this.data.rows : []).map((r) => r.type))] },
            filtered() {
                if (!this.data) return []
                return this.data.rows.filter((r) => (!this.type || r.type === this.type) && this.visible_issues(r).length
                    && (!this.issue || this.visible_issues(r).some((i) => i.code === this.issue)))
            },
            page_rows() { return this.filtered.slice(0, this.shown) },
        },
        watch: {
            issue() { this.shown = 50 }, type() { this.shown = 50 }, lang() { this.shown = 50 },
        },
        mounted() { this.load() },
        methods: {
            load() {
                this.loading = true
                axios.get('set_seo/content_quality')
                    .then((res) => { this.data = res.data })
                    .finally(() => { this.loading = false })
            },
            visible_issues(r) { return r.issues.filter((i) => !this.lang || !i.locale || i.locale === this.lang) },
            edit_link(r) {
                const c = r.content
                if (!c || !c.edit_route) return null
                const to = { name: c.edit_route, params: { id: c.id } }
                return can_open_route(this.$router, this.$ability, to) ? to : null
            },
            number(n) { return new Intl.NumberFormat(this.$i18n.locale === 'ka' ? 'ka-GE' : 'en-GB').format(n || 0) },
        },
    }
</script>

<style scoped>
    .seo-hint { padding: .6rem .8rem; background: var(--cg-accent-soft); border-radius: var(--cg-r-sm); color: var(--cg-ink-2); }
    .seo-issue-chips { display: flex; flex-wrap: wrap; gap: .4rem; }
    .seo-table td, .seo-table th { font-variant-numeric: tabular-nums; }
    .seo-table__page { min-width: 240px; }
    .seo-table__title { font-weight: 600; color: var(--cg-ink); }
    .seo-table__title .badge { font-weight: 500; margin-left: .25rem; }
    .seo-table__url { font-size: .82rem; }
    .seo-issue { margin: 0 .25rem .25rem 0; font-weight: 500; border: 1px solid var(--cg-line); background: var(--cg-surface, #fff); color: var(--cg-ink-2); white-space: normal; text-align: left; }
</style>
