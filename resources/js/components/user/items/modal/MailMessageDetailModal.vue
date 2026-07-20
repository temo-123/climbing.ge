<template>
    <StackModal
        v-model="is_open"
        :title="$t('admin.dashboards.mail_detail_title')"
        @close="is_open = false"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div v-if="loading" class="text-center py-4">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
        </div>
        <div v-else-if="message" class="mail-detail">
            <div class="detail-row">
                <span class="detail-label">{{ $t('admin.dashboards.col_subject') }}</span>
                <span class="detail-value font-weight-bold">{{ message.subject || '—' }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ $t('admin.dashboards.col_from') }}</span>
                <span class="detail-value">{{ message.from }}</span>
            </div>
            <div class="detail-row" v-if="message.to">
                <span class="detail-label">{{ $t('common.email') }}</span>
                <span class="detail-value">{{ message.to }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">{{ $t('admin.dashboards.col_date') }}</span>
                <span class="detail-value">{{ message.date }}</span>
            </div>
            <div class="mail-detail-body" v-html="message.body"></div>
        </div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_open: false,
            loading: false,
            message: null,
        }
    },
    methods: {
        show_modal(folder, uid) {
            this.is_open = true
            this.loading = true
            this.message = null
            axios.get('mail/message/' + uid, { params: { folder } })
                .then(r => { this.message = r.data })
                .catch(() => {})
                .finally(() => { this.loading = false })
        },
    },
}
</script>

<style scoped>
.mail-detail {
    font-size: 14px;
}
.detail-row {
    display: flex;
    align-items: baseline;
    margin-bottom: 5px;
    gap: 8px;
}
.detail-label {
    color: #868e96;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    min-width: 70px;
    flex-shrink: 0;
}
.detail-value {
    color: #212529;
}
.mail-detail-body {
    margin-top: 12px;
    padding-top: 12px;
    border-top: 1px solid #f0f0f0;
    max-height: 50vh;
    overflow-y: auto;
}
</style>
