<template>
    <div v-if="issues.length" class="alert alert-warning mb-3">
        <strong><i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ $t('admin.database.integrity_issues_title') }} ({{ issues.length }})</strong>
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
</template>

<script>
export default {
    name: 'IntegrityIssuesAlertComponent',
    data() {
        return {
            issues: [],
            fixing: null,
        }
    },
    mounted() {
        this.load()
    },
    methods: {
        async load() {
            try {
                const res = await axios.get('/set_database/table_stats')
                this.issues = res.data.issues || []
            } catch (e) {
                // silent - this is a supplementary alert, not the page's primary content
            }
        },
        async fixIssue(issue) {
            if (!confirm(this.$t('admin.database.confirm_delete_orphaned_rows', { count: issue.count, table: issue.table }))) return
            this.fixing = issue.key
            try {
                const res = await axios.post('/set_database/fix_issue', { key: issue.key })
                if (res.data.success) {
                    await this.load()
                    this.$emit('fixed')
                } else {
                    alert(res.data.message || this.$t('admin.database.fix_failed'))
                }
            } catch (e) {
                alert(this.$t('admin.database.request_failed'))
            } finally {
                this.fixing = null
            }
        },
    },
}
</script>
