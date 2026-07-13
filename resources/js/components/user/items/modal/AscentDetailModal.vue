<template>
    <StackModal
        v-model="is_open"
        :title="$t('user.ascents.detail_modal_title')"
        @close="is_open = false"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div v-if="ascent" class="ascent-detail">

            <div class="detail-section">
                <div class="detail-row">
                    <span class="detail-label">{{ $t('common.summit') }}</span>
                    <span class="detail-value font-weight-bold">
                        {{ ascent.summit ? ascent.summit.title : '—' }}
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ $t('common.date') }}</span>
                    <span class="detail-value">
                        {{ ascent.ascent_date ? formatDate(ascent.ascent_date) : '—' }}
                    </span>
                </div>
                <div class="detail-row" v-if="ascent.ascent_time">
                    <span class="detail-label">{{ $t('user.ascents.time') }}</span>
                    <span class="detail-value">{{ ascent.ascent_time }}</span>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-row">
                    <span class="detail-label">{{ $t('common.route') }}</span>
                    <span class="detail-value">
                        <span v-if="ascent.route_name">{{ ascent.route_name }}</span>
                        <span v-else class="text-muted">—</span>
                    </span>
                </div>
                <div class="detail-row" v-if="ascent.route_grade">
                    <span class="detail-label">{{ $t('common.grade') }}</span>
                    <span class="detail-value">
                        <span class="badge badge-secondary">{{ ascent.route_grade }}</span>
                    </span>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-row">
                    <span class="detail-label">{{ $t('user.ascents.climber') }}</span>
                    <span class="detail-value">{{ ascent.name }} {{ ascent.surname }}</span>
                </div>
                <div class="detail-row" v-if="ascent.email">
                    <span class="detail-label">{{ $t('common.email') }}</span>
                    <span class="detail-value text-muted">{{ ascent.email }}</span>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-row">
                    <span class="detail-label">{{ $t('user.ascents.gps_verified') }}</span>
                    <span class="detail-value">
                        <span v-if="ascent.is_gps_validated" class="badge badge-success">
                            <i class="fa fa-check"></i> {{ $t('user.ascents.verified') }}
                        </span>
                        <span v-else class="badge badge-light text-muted">{{ $t('user.ascents.not_verified') }}</span>
                    </span>
                </div>
                <div class="detail-row" v-if="ascent.user_latitude && ascent.user_longitude">
                    <span class="detail-label">{{ $t('user.ascents.coordinates') }}</span>
                    <span class="detail-value text-muted small">
                        {{ ascent.user_latitude }}, {{ ascent.user_longitude }}
                    </span>
                </div>
            </div>

            <div class="detail-section" v-if="ascent.comment">
                <div class="detail-label mb-1">{{ $t('user.ascents.comment') }}</div>
                <div class="detail-comment">{{ ascent.comment }}</div>
            </div>

            <div class="detail-section" v-if="ascent.photo">
                <div class="detail-label mb-2">{{ $t('user.ascents.photo') }}</div>
                <img
                    :src="'/public/images/summit_ascents_img/' + ascent.photo"
                    class="img-fluid rounded ascent-photo"
                    :alt="$t('user.ascents.photo_alt')"
                />
            </div>

        </div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_open: false,
            ascent: null,
        }
    },
    methods: {
        show_modal(ascent) {
            this.ascent = ascent
            this.is_open = true
        },
        formatDate(dateStr) {
            if (!dateStr) return ''
            return new Date(dateStr).toLocaleDateString()
        },
    },
}
</script>

<style scoped>
.ascent-detail {
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

.detail-comment {
    background: #f8f9fa;
    border-radius: 6px;
    padding: 10px 12px;
    color: #495057;
    font-size: 13px;
    line-height: 1.5;
}

.ascent-photo {
    max-height: 280px;
    object-fit: cover;
    width: 100%;
}
</style>
