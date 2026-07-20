<template>
    <StackModal
        v-model="is_open"
        :title="$t('admin.notification_analytics.guest_follower_detail_title')"
        @close="is_open = false"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div v-if="follower" class="follower-detail">
            <div class="detail-section">
                <div class="detail-row">
                    <span class="detail-label">{{ $t('common.id') }}</span>
                    <span class="detail-value">{{ follower.id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $t('common.email') }}</span>
                    <span class="detail-value font-weight-bold">{{ follower.email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $t('admin.notification_analytics.col_service') }}</span>
                    <span class="detail-value">
                        <span class="badge badge-secondary">{{ follower.service }}</span>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $t('admin.notification_analytics.col_followed_at') }}</span>
                    <span class="detail-value text-muted">{{ follower.created_at }}</span>
                </div>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_open: false,
            follower: null,
        }
    },
    methods: {
        show_modal(follower) {
            this.follower = follower
            this.is_open = true
        },
    },
}
</script>

<style scoped>
.follower-detail {
    font-size: 14px;
}
.detail-section {
    padding: 10px 0;
}
.detail-row {
    display: flex;
    align-items: baseline;
    margin-bottom: 5px;
}
.detail-label {
    color: #868e96;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    min-width: 110px;
    flex-shrink: 0;
}
.detail-value {
    color: #212529;
}
</style>
