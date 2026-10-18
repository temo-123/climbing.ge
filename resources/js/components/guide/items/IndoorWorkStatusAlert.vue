<template>
    <div v-if="config" :class="['alert', config.class, 'indoor_work_status_alert']" role="alert">
        <i :class="['fa', config.icon, 'indoor_work_status_icon']" aria-hidden="true"></i>
        <div>
            <strong>{{ $t('guide.article.work_status.' + status) }}</strong>
            <div class="indoor_work_status_text">{{ $t('guide.article.work_status_text.' + status) }}</div>
        </div>
    </div>
</template>

<script>
    // Indoor gym status (articles.work_status) — overrides the normal working hours
    const STATUSES = {
        temporarily_closed:   { class: 'alert-danger',  icon: 'fa-ban' },
        sport_session_closed: { class: 'alert-warning', icon: 'fa-trophy' },
        coming_soon:          { class: 'alert-info',    icon: 'fa-clock-o' },
    }

    export default {
        name: 'IndoorWorkStatusAlert',
        props: {
            status: {
                type: String,
                default: null,
            },
        },
        computed: {
            config() {
                return STATUSES[this.status] || null
            },
        },
    }
</script>

<style>
    .indoor_work_status_alert {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 10px 0 15px;
        text-align: left;
    }
    .indoor_work_status_icon {
        font-size: 2em;
        flex-shrink: 0;
    }
    .indoor_work_status_text {
        font-size: 0.95em;
        margin-top: 2px;
    }
</style>
