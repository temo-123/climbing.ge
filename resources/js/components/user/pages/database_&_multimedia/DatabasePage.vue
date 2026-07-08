<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <li class="nav-item">
                            <a class="nav-link" :class="{ active: page_tab === 'multimedia' }" href="#" @click.prevent="page_tab = 'multimedia'">
                                <i class="fa fa-images"></i> {{ $t('admin.database.multimedia_tab') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ active: page_tab === 'database' }" href="#" @click.prevent="switch_to_db">
                                <i class="fa fa-database"></i> {{ $t('admin.database.database_tab') }}
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Multimedia tab -->
            <div v-show="page_tab === 'multimedia'">
                <ArticleImagesPage />
            </div>

            <!-- Database tab -->
            <div v-show="page_tab === 'database'">

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="db-summary">
                        <span class="mr-3"><strong>{{ tables.length }}</strong> {{ $t('admin.database.tables_suffix') }}</span>
                        <span class="mr-3"><strong>{{ totalRows.toLocaleString() }}</strong> {{ $t('admin.database.total_rows_suffix') }}</span>
                        <span><strong>{{ formatBytes(totalSize) }}</strong> {{ $t('admin.database.total_size_suffix') }}</span>
                    </div>
                    <a :href="phpmyadmin_url" target="_blank" class="btn btn-warning">
                        <i class="fa fa-external-link"></i> phpMyAdmin
                    </a>
                </div>

                <div v-if="issues.length" class="alert alert-warning mb-3">
                    <strong><i class="fa fa-exclamation-triangle"></i> {{ $t('admin.database.integrity_issues_title') }} ({{ issues.length }})</strong>
                    <div v-for="issue in issues" :key="issue.key" class="d-flex align-items-center mt-2 gap-2">
                        <code>{{ issue.table }}</code>
                        <span class="text-muted small">{{ issue.label }}</span>
                        <span class="badge badge-danger">{{ issue.count }} {{ issue.count !== 1 ? $t('admin.database.row_plural') : $t('admin.database.row_singular') }}</span>
                        <button
                            v-if="issue.fixable"
                            class="btn btn-sm btn-danger ml-auto"
                            :disabled="fixing === issue.key"
                            @click="fixIssue(issue)"
                        >
                            <i class="fa fa-wrench"></i>
                            {{ fixing === issue.key ? $t('admin.database.fixing_ellipsis') : $t('admin.database.fix_btn') }}
                        </button>
                        <span v-else class="badge badge-secondary ml-auto">{{ $t('admin.database.manual_fix_needed') }}</span>
                    </div>
                </div>

                <div v-if="loading" class="text-center py-4">
                    <i class="fa fa-spinner fa-spin fa-2x"></i>
                    <p>{{ $t('admin.database.loading_table_stats') }}</p>
                </div>
                <div v-else-if="error" class="alert alert-danger">
                    {{ error }}
                    <button class="btn btn-sm btn-default ml-2" @click="load">{{ $t('admin.database.retry_btn') }}</button>
                </div>
                <div v-else>
                    <tabsComponent :table_data="db_tab_data" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import breadcrumb from '../../items/BreadcrumbComponent.vue'
import tabsComponent from '../../items/data_table/TabsComponent.vue'
import ArticleImagesPage from './multimedia/ArticleImagesPage.vue'

export default {
    components: { breadcrumb, tabsComponent, ArticleImagesPage },
    data() {
        return {
            page_tab: 'multimedia',
            tables: [],
            issues: [],
            loading: false,
            error: null,
            fixing: null,
            phpmyadmin_url: process.env.MIX_PHPMYADMIN_URL,
        }
    },
    computed: {
        totalRows() {
            return this.tables.reduce((s, t) => s + (t.row_count ?? 0), 0)
        },
        totalSize() {
            return this.tables.reduce((s, t) => s + (t.size_bytes ?? 0), 0)
        },
        db_tab_data() {
            if (this.loading) return []
            return [{
                id: 1,
                table_name: this.$t('admin.database.db_tables_table'),
                tab_data: {
                    data: this.tables.map((t, idx) => ({
                        id: idx + 1,
                        ...t,
                        size_formatted: this.formatBytes(t.size_bytes),
                        issue_count: this.issues.filter(iss => iss.table === t.name).length || 0,
                    })),
                    tab: {
                        head: [this.$t('admin.database.col_table'), this.$t('admin.database.col_rows'), this.$t('admin.database.col_size'), this.$t('admin.database.col_engine'), this.$t('admin.database.col_issues')],
                        body: [
                            ['data', ['name']],
                            ['data', ['row_count']],
                            ['data', ['size_formatted']],
                            ['data', ['ENGINE']],
                            ['data', ['issue_count']],
                        ],
                        perm: [['no'], ['no'], ['no'], ['no'], ['no']]
                    }
                }
            }]
        }
    },
    methods: {
        switch_to_db() {
            this.page_tab = 'database'
            if (!this.tables.length && !this.loading) {
                this.load()
            }
        },
        async load() {
            this.loading = true
            this.error = null
            try {
                const res = await axios.get('/set_database/table_stats')
                this.tables = res.data.tables
                this.issues = res.data.issues
            } catch (e) {
                this.error = this.$t('admin.database.failed_load_db_stats')
            } finally {
                this.loading = false
            }
        },
        async fixIssue(issue) {
            if (!confirm(this.$t('admin.database.confirm_delete_orphaned_rows', { count: issue.count, table: issue.table }))) return
            this.fixing = issue.key
            try {
                const res = await axios.post('/set_database/fix_issue', { key: issue.key })
                if (res.data.success) {
                    await this.load()
                } else {
                    alert(res.data.message || this.$t('admin.database.fix_failed'))
                }
            } catch (e) {
                alert(this.$t('admin.database.request_failed'))
            } finally {
                this.fixing = null
            }
        },
        formatBytes(bytes) {
            if (!bytes) return '0 B'
            const k = 1024
            const sizes = ['B', 'KB', 'MB', 'GB']
            const i = Math.floor(Math.log(bytes) / Math.log(k))
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i]
        },
    }
}
</script>

<style scoped>
.db-summary {
    font-size: 0.9em;
    color: #555;
}
</style>
