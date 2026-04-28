<template>
    <div class="row">
        <left-menu />

        <div class="db-page-container">

            <div class="db-header">
                <h1><i class="fa fa-database"></i> Database</h1>
                <a :href="phpmyadmin_url" target="_blank" class="btn btn-phpmyadmin">
                    <i class="fa fa-external-link"></i> Go to phpMyAdmin
                </a>
            </div>

            <!-- Integrity issues -->
            <div v-if="issues.length" class="issues-section">
                <h3 class="issues-title"><i class="fa fa-exclamation-triangle"></i> Integrity Issues ({{ issues.length }})</h3>
                <div v-for="issue in issues" :key="issue.key" class="issue-row">
                    <div class="issue-info">
                        <span class="issue-table">{{ issue.table }}</span>
                        <span class="issue-label">{{ issue.label }}</span>
                        <span class="issue-count">{{ issue.count }} row{{ issue.count !== 1 ? 's' : '' }}</span>
                    </div>
                    <button
                        v-if="issue.fixable"
                        class="btn btn-fix"
                        :disabled="fixing === issue.key"
                        @click="fixIssue(issue)"
                    >
                        <i class="fa fa-wrench"></i>
                        {{ fixing === issue.key ? 'Fixing…' : 'Fix' }}
                    </button>
                    <span v-else class="badge-manual">Manual fix needed</span>
                </div>
            </div>

            <div v-if="loading" class="db-loading">
                <i class="fa fa-spinner fa-spin fa-2x"></i>
                <p>Loading table stats…</p>
            </div>

            <div v-else-if="error" class="db-error">
                <i class="fa fa-exclamation-circle"></i> {{ error }}
                <button class="btn btn-sm btn-default" @click="load">Retry</button>
            </div>

            <div v-else class="db-tables-wrap">
                <div class="db-summary">
                    <span><strong>{{ tables.length }}</strong> tables</span>
                    <span><strong>{{ totalRows.toLocaleString() }}</strong> total rows</span>
                    <span><strong>{{ formatBytes(totalSize) }}</strong> total size</span>
                </div>

                <input
                    v-model="search"
                    class="db-search"
                    placeholder="Filter tables…"
                    type="text"
                />

                <table class="db-table">
                    <thead>
                        <tr>
                            <th @click="sort('name')" class="sortable">
                                Table <i :class="sortIcon('name')"></i>
                            </th>
                            <th @click="sort('row_count')" class="sortable text-right">
                                Rows <i :class="sortIcon('row_count')"></i>
                            </th>
                            <th @click="sort('size_bytes')" class="sortable text-right">
                                Size <i :class="sortIcon('size_bytes')"></i>
                            </th>
                            <th>Engine</th>
                            <th>Issues</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="t in filteredTables"
                            :key="t.name"
                            :class="{ 'has-issue': tableHasIssue(t.name) }"
                        >
                            <td class="table-name">{{ t.name }}</td>
                            <td class="text-right">{{ (t.row_count ?? 0).toLocaleString() }}</td>
                            <td class="text-right">{{ formatBytes(t.size_bytes) }}</td>
                            <td>{{ t.ENGINE }}</td>
                            <td>
                                <span
                                    v-for="issue in issuesForTable(t.name)"
                                    :key="issue.key"
                                    class="issue-badge"
                                >
                                    {{ issue.count }} {{ issue.label }}
                                </span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            tables:      [],
            issues:      [],
            loading:     true,
            error:       null,
            fixing:      null,
            search:      '',
            sortBy:      'name',
            sortDir:     'asc',
            phpmyadmin_url: process.env.MIX_PHPMYADMIN_URL || 'http://localhost/phpmyadmin',
        }
    },

    computed: {
        filteredTables() {
            let rows = this.tables
            if (this.search.trim()) {
                const q = this.search.toLowerCase()
                rows = rows.filter(t => t.name.toLowerCase().includes(q))
            }
            return [...rows].sort((a, b) => {
                const av = a[this.sortBy] ?? 0
                const bv = b[this.sortBy] ?? 0
                const cmp = typeof av === 'string' ? av.localeCompare(bv) : av - bv
                return this.sortDir === 'asc' ? cmp : -cmp
            })
        },
        totalRows() {
            return this.tables.reduce((s, t) => s + (t.row_count ?? 0), 0)
        },
        totalSize() {
            return this.tables.reduce((s, t) => s + (t.size_bytes ?? 0), 0)
        },
    },

    mounted() {
        this.load()
    },

    methods: {
        async load() {
            this.loading = true
            this.error   = null
            try {
                const res    = await axios.get('/set_database/table_stats')
                this.tables  = res.data.tables
                this.issues  = res.data.issues
            } catch (e) {
                this.error = 'Failed to load database stats.'
            } finally {
                this.loading = false
            }
        },

        async fixIssue(issue) {
            if (!confirm(`Delete ${issue.count} orphaned row(s) from "${issue.table}"?`)) return
            this.fixing = issue.key
            try {
                const res = await axios.post('/set_database/fix_issue', { key: issue.key })
                if (res.data.success) {
                    await this.load()
                } else {
                    alert(res.data.message || 'Fix failed.')
                }
            } catch (e) {
                alert('Request failed.')
            } finally {
                this.fixing = null
            }
        },

        tableHasIssue(name) {
            return this.issues.some(i => i.table === name)
        },

        issuesForTable(name) {
            return this.issues.filter(i => i.table === name)
        },

        sort(col) {
            if (this.sortBy === col) {
                this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc'
            } else {
                this.sortBy  = col
                this.sortDir = col === 'name' ? 'asc' : 'desc'
            }
        },

        sortIcon(col) {
            if (this.sortBy !== col) return 'fa fa-sort text-muted'
            return this.sortDir === 'asc' ? 'fa fa-sort-asc' : 'fa fa-sort-desc'
        },

        formatBytes(bytes) {
            if (!bytes) return '0 B'
            const k = 1024
            const sizes = ['B', 'KB', 'MB', 'GB']
            const i = Math.floor(Math.log(bytes) / Math.log(k))
            return parseFloat((bytes / Math.pow(k, i)).toFixed(1)) + ' ' + sizes[i]
        },
    },
}
</script>

<style scoped>
.db-page-container {
    flex: 1;
    padding: 24px;
    max-width: 1200px;
}

.db-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
}

.db-header h1 {
    margin: 0;
    font-size: 1.8em;
    color: #333;
}

.btn-phpmyadmin {
    background: #f39c12;
    color: white;
    padding: 8px 18px;
    border-radius: 6px;
    text-decoration: none;
    font-size: 0.9em;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.15s;
}

.btn-phpmyadmin:hover {
    background: #e08e0b;
    color: white;
    text-decoration: none;
}

/* Issues */
.issues-section {
    background: #fff3cd;
    border: 1px solid #ffc107;
    border-radius: 8px;
    padding: 16px;
    margin-bottom: 24px;
}

.issues-title {
    margin: 0 0 12px;
    font-size: 1em;
    color: #856404;
}

.issue-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 8px 0;
    border-bottom: 1px solid #ffe69c;
}

.issue-row:last-child {
    border-bottom: none;
}

.issue-info {
    flex: 1;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}

.issue-table {
    font-family: monospace;
    font-size: 0.85em;
    background: #856404;
    color: white;
    padding: 2px 8px;
    border-radius: 4px;
}

.issue-label {
    color: #555;
    font-size: 0.9em;
}

.issue-count {
    font-weight: 700;
    color: #dc3545;
    font-size: 0.9em;
}

.btn-fix {
    background: #dc3545;
    color: white;
    border: none;
    padding: 5px 14px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 0.85em;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    white-space: nowrap;
}

.btn-fix:disabled {
    opacity: 0.6;
    cursor: default;
}

.btn-fix:hover:not(:disabled) {
    background: #c82333;
}

.badge-manual {
    font-size: 0.78em;
    color: #856404;
    background: #ffe69c;
    padding: 2px 8px;
    border-radius: 10px;
    white-space: nowrap;
}

/* Summary */
.db-summary {
    display: flex;
    gap: 24px;
    margin-bottom: 14px;
    font-size: 0.95em;
    color: #555;
}

/* Search */
.db-search {
    width: 280px;
    padding: 7px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.9em;
    margin-bottom: 12px;
    display: block;
}

/* Table */
.db-tables-wrap {
    background: #fff;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    padding: 20px;
}

.db-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.88em;
}

.db-table th {
    background: #f1f3f5;
    padding: 10px 12px;
    text-align: left;
    font-weight: 600;
    border-bottom: 2px solid #dee2e6;
    color: #495057;
}

.db-table th.sortable {
    cursor: pointer;
    user-select: none;
}

.db-table th.sortable:hover {
    background: #e9ecef;
}

.db-table td {
    padding: 8px 12px;
    border-bottom: 1px solid #f1f3f5;
    vertical-align: middle;
}

.db-table tr:last-child td {
    border-bottom: none;
}

.db-table tr.has-issue td {
    background: #fff5f5;
    color: #c0392b;
}

.db-table tr.has-issue .table-name {
    font-weight: 700;
}

.text-right {
    text-align: right;
}

.text-muted {
    opacity: 0.4;
}

.table-name {
    font-family: monospace;
    font-size: 0.95em;
}

.issue-badge {
    display: inline-block;
    background: #dc3545;
    color: white;
    font-size: 0.75em;
    padding: 2px 7px;
    border-radius: 10px;
    margin-right: 4px;
}

/* Loading / error */
.db-loading, .db-error {
    text-align: center;
    padding: 60px 20px;
    color: #888;
}

.db-loading i, .db-error i {
    display: block;
    font-size: 2em;
    margin-bottom: 12px;
}

.db-error i {
    color: #dc3545;
}
</style>
