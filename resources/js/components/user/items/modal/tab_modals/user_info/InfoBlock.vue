<template>
    <div class="info-block">
        <div class="info-block__head">
            <span class="info-block__title">
                <i v-if="icon" :class="'fa ' + icon" aria-hidden="true"></i> {{ title }}
            </span>
            <span class="badge rounded-pill" :class="count ? 'text-bg-primary' : 'text-bg-light'">{{ count }}</span>
        </div>

        <div v-if="!items.length" class="info-block__empty">{{ $t('admin.user_info.empty') }}</div>

        <template v-else>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0 info-block__table">
                    <thead>
                        <tr>
                            <th v-for="col in columns" :key="col.key">{{ col.label }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, i) in items" :key="item.id ?? i">
                            <td v-for="col in columns" :key="col.key" :class="'info-block__cell--' + (col.fmt || 'plain')">
                                <template v-if="col.fmt === 'date'">{{ format_date(item[col.key]) }}</template>
                                <template v-else-if="col.fmt === 'bool'">
                                    <i v-if="is_true(item[col.key])" class="fa fa-check text-success"></i>
                                    <i v-else class="fa fa-times text-muted"></i>
                                </template>
                                <template v-else-if="col.fmt === 'stars'">
                                    <span v-if="item[col.key]" class="info-block__stars">{{ '★'.repeat(Math.min(+item[col.key], 5)) }}</span>
                                    <span v-else>—</span>
                                </template>
                                <template v-else-if="col.fmt === 'state'">
                                    <span class="badge" :class="state(item).cls">{{ state(item).label }}</span>
                                </template>
                                <template v-else-if="col.fmt === 'person'">
                                    <button type="button" class="btn btn-link btn-sm p-0 text-start" @click="$emit('open_user', item.id ?? item.other_id)">
                                        {{ item.name }} {{ item.surname }}
                                    </button>
                                </template>
                                <template v-else-if="col.fmt === 'text'">
                                    <span class="info-block__text" :title="item[col.key]">{{ item[col.key] || '—' }}</span>
                                </template>
                                <template v-else>{{ col.value ? col.value(item) : display(item[col.key]) }}</template>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="count > items.length" class="info-block__more">
                {{ $t('admin.user_info.showing_latest', { shown: items.length, total: count }) }}
            </div>
        </template>
    </div>
</template>

<script>
// One "related data" list in the admin user-info modal: a title with the real
// total, then the latest rows the backend sent (AdminUserOverviewService::block).
// columns: [{ key, label, fmt?: 'date'|'bool'|'stars'|'state'|'person'|'text', value?: item => string }]
export default {
    emits: ['open_user'],
    props: {
        title: { type: String, required: true },
        icon: { type: String, default: '' },
        block: { type: [Object, Array], default: null },
        columns: { type: Array, required: true },
    },
    computed: {
        items() {
            if (Array.isArray(this.block)) return this.block
            return this.block?.items || []
        },
        count() {
            if (Array.isArray(this.block)) return this.block.length
            return this.block?.count ?? 0
        },
    },
    methods: {
        is_true(v) {
            return v === true || v === 1 || v === '1'
        },
        display(v) {
            return v === null || v === undefined || v === '' ? '—' : v
        },
        format_date(value) {
            if (!value) return '—'
            return String(value).replace('T', ' ').substring(0, 16)
        },
        // published / admin_hidden / deleted_at → one status badge
        state(item) {
            if (item.deleted_at) return { cls: 'text-bg-dark', label: this.$t('admin.user_info.st_deleted') }
            if (this.is_true(item.admin_hidden)) return { cls: 'text-bg-danger', label: this.$t('admin.user_info.st_hidden') }
            if (item.published !== undefined && !this.is_true(item.published)) return { cls: 'text-bg-secondary', label: this.$t('admin.user_info.st_draft') }
            return { cls: 'text-bg-success', label: this.$t('admin.user_info.st_published') }
        },
    },
}
</script>

<style scoped>
.info-block {
    border: 1px solid var(--bs-border-color);
    border-radius: var(--bs-border-radius);
    margin-bottom: 1rem;
    overflow: hidden;
}
.info-block__head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: .5rem .75rem;
    background: var(--bs-tertiary-bg);
    border-bottom: 1px solid var(--bs-border-color);
}
.info-block__title {
    font-weight: 600;
}
.info-block__empty {
    padding: .6rem .75rem;
    color: var(--bs-secondary-color);
    font-size: .875rem;
}
.info-block__table th {
    font-size: .75rem;
    text-transform: uppercase;
    color: var(--bs-secondary-color);
    font-weight: 600;
    white-space: nowrap;
}
.info-block__table td,
.info-block__table th {
    padding-left: .75rem;
    padding-right: .75rem;
}
.info-block__cell--date {
    white-space: nowrap;
}
.info-block__text {
    display: inline-block;
    max-width: 22rem;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: bottom;
}
.info-block__stars {
    color: var(--bs-warning);
    letter-spacing: 1px;
}
.info-block__more {
    padding: .4rem .75rem;
    font-size: .8rem;
    color: var(--bs-secondary-color);
    border-top: 1px solid var(--bs-border-color);
}
</style>
