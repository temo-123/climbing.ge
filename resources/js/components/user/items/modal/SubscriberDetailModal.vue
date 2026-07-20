<template>
    <StackModal
        v-model="is_open"
        :title="$t('admin.notification_analytics.subscriber_detail_title')"
        @close="is_open = false"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div v-if="subscriber" class="subscriber-detail">
            <div class="detail-section">
                <div class="detail-row">
                    <span class="detail-label">{{ $t('common.id') }}</span>
                    <span class="detail-value">{{ subscriber.id }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $t('common.name') }}</span>
                    <span class="detail-value font-weight-bold">{{ subscriber.name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $t('common.email') }}</span>
                    <span class="detail-value">{{ subscriber.email }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label"></span>
                    <span class="detail-value">
                        <span v-if="subscriber.email_verified" style="color:green;">{{ $t('admin.tour.verification_completed') }}</span>
                        <span v-else style="color:red;">{{ $t('admin.tour.verification_failed') }}</span>
                        <button
                            v-if="!subscriber.email_verified && $can('manage', 'user_notification')"
                            class="btn btn-sm btn-outline-success ml-2"
                            :disabled="verifying"
                            @click="verify"
                        >
                            {{ $t('admin.notification_analytics.verify_user_btn') }}
                        </button>
                    </span>
                </div>
            </div>

            <div class="detail-section">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="detail-label mb-0">{{ $t('admin.notification_analytics.col_subscriptions') }}</div>
                    <button
                        v-if="$can('manage', 'user_notification')"
                        class="btn btn-sm btn-outline-primary"
                        :disabled="subscribingAll"
                        @click="subscribeAll"
                    >
                        {{ $t('admin.notification_analytics.subscribe_all_btn') }}
                    </button>
                </div>
                <ul class="preference-list">
                    <li v-for="pref in subscriber.preferences" :key="pref.key" class="preference-item">
                        <i v-if="pref.enabled" class="fa fa-check text-success"></i>
                        <i v-else class="fa fa-times text-muted"></i>
                        <span :class="{ 'text-muted': !pref.enabled }">{{ pref.label }}</span>
                    </li>
                </ul>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    emits: ['refreshed'],
    data() {
        return {
            is_open: false,
            subscriber: null,
            verifying: false,
            subscribingAll: false,
        }
    },
    methods: {
        show_modal(subscriber) {
            this.subscriber = subscriber
            this.is_open = true
        },
        verify() {
            this.verifying = true
            axios.post(`/set_notification_analytics/subscribers/${this.subscriber.id}/verify`)
                .then(() => {
                    this.subscriber.email_verified = true
                    this.$emit('refreshed')
                })
                .catch(error => console.log(error))
                .finally(() => (this.verifying = false))
        },
        subscribeAll() {
            if (!confirm(this.$t('admin.notification_analytics.confirm_subscribe_all'))) return
            this.subscribingAll = true
            axios.post(`/set_notification_analytics/subscribers/${this.subscriber.id}/subscribe_all`)
                .then(response => {
                    this.subscriber.preferences = response.data.preferences
                    this.$emit('refreshed')
                })
                .catch(error => console.log(error))
                .finally(() => (this.subscribingAll = false))
        },
    },
}
</script>

<style scoped>
.subscriber-detail {
    font-size: 14px;
}
.detail-section {
    border-bottom: 1px solid #f0f0f0;
    padding: 10px 0;
}
.detail-section:last-child {
    border-bottom: none;
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
.preference-list {
    list-style: none;
    padding: 0;
    margin: 0;
    columns: 2;
}
.preference-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 3px 0;
}
</style>
