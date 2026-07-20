<template>
    <div>
        <!-- Condensed home-page summary: one line + redirect, no breakdown -->
        <div v-if="summary && totalCount > 0" class="alert alert-danger d-flex align-items-center justify-content-between flex-wrap gap-2">
            <span><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ $t('admin.dashboards.relation_issues_summary_prefix', { count: totalCount }) }}</span>
            <router-link :to="{ name: 'guideDashboardPage' }" class="btn btn-sm btn-danger">{{ $t('admin.dashboards.fix_now_btn') }}</router-link>
        </div>

        <!-- Full breakdown: one big alert box per component instance, matching the /multimedia database page style -->
        <div v-else-if="issues.length" class="alert alert-warning mb-3">
            <strong><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ title }} ({{ totalCount }})</strong>
            <div v-for="issue in issues" :key="issue.key" class="d-flex align-items-center mt-2 gap-2">
                <span class="badge bg-secondary">{{ issue.locale.toUpperCase() }}</span>
                <span class="text-muted small">{{ entity_label(issue.table_name) }}</span>
                <span class="badge badge-danger">{{ issue.count }}</span>
                <button class="btn btn-sm btn-warning ms-auto" @click="show_errors(issue)">
                    <i class="fa fa-eye"></i> {{ $t('admin.dashboards.view_btn') }}
                </button>
                <button class="btn btn-sm btn-danger" @click="fix_issue(issue)">
                    <i class="fa fa-trash"></i> {{ $t('admin.dashboards.fix_delete_btn') }}
                </button>
            </div>
        </div>

        <!-- Per-entity sections always render a status, so an entity never just silently disappears -->
        <div v-else-if="!loading && tables && tables.length === 1" class="alert alert-success mb-3 py-2 d-flex align-items-center gap-2">
            <i class="fa fa-check-circle" aria-hidden="true"></i>
            <span>{{ $t('admin.dashboards.no_entity_issues_msg', { entity: entity_label(tables[0]) }) }}</span>
        </div>

        <!-- Error rows modal -->
        <div v-if="error_modal_open" class="entity-error-modal-backdrop" @click.self="error_modal_open = false">
            <div class="entity-error-modal">
                <div class="entity-error-modal-header">
                    <strong>{{ $t('admin.dashboards.orphaned_entity_title', { locale: error_modal_locale.toUpperCase(), entity: entity_label(error_modal_table), count: error_rows.length }) }}</strong>
                    <button class="btn btn-sm btn-light" @click="error_modal_open = false"><i class="fa fa-times"></i></button>
                </div>
                <div class="entity-error-modal-body">
                    <div v-if="error_modal_loading" class="text-center py-3">
                        <i class="fa fa-spinner fa-spin fa-2x"></i>
                    </div>
                    <table v-else class="table table-condensed table-hover mb-0">
                        <thead>
                            <tr>
                                <th>{{ $t('common.id') }}</th>
                                <th>{{ $t('common.title') }}</th>
                                <th>{{ $t('admin.dashboards.col_locale') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in error_rows" :key="row.id">
                                <td class="text-muted small">{{ row.id }}</td>
                                <td>{{ row.title }}</td>
                                <td><span class="badge bg-secondary">{{ row.locale }}</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'EntityLocaleErrorsComponent',
    props: {
        // Restrict to specific entity tables (e.g. ['products']). Omit/null to include all.
        tables: { type: Array, default: null },
        // Condensed single-line alert + redirect, instead of the full per-issue breakdown.
        summary: { type: Boolean, default: false },
    },
    data() {
        return {
            loading: true,
            all_issues: [],
            error_modal_open: false,
            error_modal_locale: '',
            error_modal_table: '',
            error_modal_loading: false,
            error_rows: [],
        }
    },
    computed: {
        issues() {
            if (!this.tables) return this.all_issues
            return this.all_issues.filter(issue => this.tables.includes(issue.table_name))
        },
        totalCount() {
            return this.issues.reduce((sum, issue) => sum + issue.count, 0)
        },
        title() {
            if (this.tables && this.tables.length === 1) {
                return this.$t('admin.dashboards.entity_issues_title', { entity: this.entity_label(this.tables[0]) })
            }
            return this.$t('admin.dashboards.other_content_issues_title')
        },
    },
    mounted() {
        this.get_issues()
    },
    methods: {
        entity_label(table) {
            return this.$t('admin.dashboards.entity_name_' + table)
        },
        get_issues() {
            this.loading = true
            axios
                .get('set_database/table_stats')
                .then(response => {
                    this.all_issues = (response.data.issues || [])
                        .filter(issue => issue.key.startsWith('orphan_translation_'))
                        .map(issue => {
                            const m = issue.key.match(/^orphan_translation_(.+)_(us|ka)$/)
                            return { ...issue, table_name: m[1], locale: m[2] }
                        })
                })
                .catch(() => {})
                .finally(() => { this.loading = false })
        },
        show_errors(issue) {
            this.error_modal_locale = issue.locale
            this.error_modal_table = issue.table_name
            this.error_modal_open = true
            this.error_modal_loading = true
            this.error_rows = []
            axios.get('set_database/issue_rows/' + issue.key)
                .then(r => { this.error_rows = r.data })
                .catch(() => {})
                .finally(() => { this.error_modal_loading = false })
        },
        fix_issue(issue) {
            if (!window.confirm(this.$t('admin.dashboards.confirm_delete_conflicting_items'))) return
            axios
                .post('set_database/fix_issue', { key: issue.key })
                .then(response => {
                    this.$bus.$emit('toast', {
                        type: 'success',
                        title: this.$t('admin.dashboards.entity_errors_fixed_title'),
                        message: this.$t('admin.dashboards.deleted_orphaned_entity_msg', { count: response.data.affected }),
                    })
                    this.get_issues()
                })
                .catch(() => {
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        title: this.$t('admin.dashboards.dashboard_toast_title'),
                        message: this.$t('admin.dashboards.failed_fix_entity_errors'),
                    })
                })
        },
    },
}
</script>

<style scoped>
.entity-error-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.entity-error-modal {
    background: #fff;
    border-radius: 6px;
    width: 90vw;
    max-width: 560px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 32px rgba(0,0,0,.25);
}
.entity-error-modal-header {
    padding: 14px 18px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.entity-error-modal-body {
    overflow-y: auto;
    padding: 0;
}
</style>
