<template>
    <!-- "Working hours" tab of an indoor gym article: opening hours per
         weekday, saved on their own (set_gym_hours/{article_id} →
         article_working_hours, ArticleWorkingHoursController). Off = the same
         hours every day (the open / close times in the Global info tab).
         The site shows them on the gym page, the gym cards and the
         "open now" filter (services/guide/gymHours.js). -->
    <div class="wh-tab">
        <h4 class="form-section-title"><i class="fa fa-clock-o" aria-hidden="true"></i> {{ $t('admin.articles.working_hours.title') }}</h4>
        <p class="text-muted">{{ $t('admin.articles.working_hours.lead') }}</p>

        <div v-if="loading" class="text-center p-3"><i class="fa fa-spinner fa-spin" aria-hidden="true"></i></div>

        <template v-else>
            <div v-if="work_status" class="alert alert-warning py-2">
                <i class="fa fa-exclamation-triangle" aria-hidden="true"></i> {{ $t('admin.articles.working_hours.status_note') }}
            </div>

            <div v-if="!week" class="alert alert-info py-2">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
                <template v-if="open_time && closed_time">{{ $t('admin.articles.working_hours.same_every_day', { open: open_time, close: closed_time }) }}</template>
                <template v-else>{{ $t('admin.articles.working_hours.no_hours') }}</template>
            </div>

            <weeklyHoursInput
                v-model="week"
                :default_open="open_time"
                :default_close="closed_time"
            />

            <div class="d-flex align-items-center gap-2 mt-3">
                <button type="button" class="btn btn-primary" :disabled="saving || !dirty" @click="save">
                    <span v-if="saving" class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>
                    <i v-else class="fa fa-save" aria-hidden="true"></i> {{ $t('admin.articles.working_hours.save') }}
                </button>
                <small v-if="dirty" class="text-muted">{{ $t('admin.articles.working_hours.unsaved') }}</small>
            </div>
        </template>
    </div>
</template>

<script>
    import weeklyHoursInput from '../../../../items/form/parts/WeeklyHoursInputComponent.vue'
    import { WEEK_DAYS } from '../../../../../../services/guide/gymHours.js'

    export default {
        name: 'WorkingHoursForm',
        components: { weeklyHoursInput },
        props: {
            article_id: { type: [Number, String], required: true },
        },
        data() {
            return {
                loading: true,
                saving: false,
                week: null,            // { mon: { open, close } | { closed: true }, … } | null
                saved_json: 'null',
                open_time: null,
                closed_time: null,
                work_status: null,
            };
        },
        computed: {
            dirty() { return JSON.stringify(this.week) !== this.saved_json; },
        },
        mounted() { this.load(); },
        methods: {
            apply(data) {
                this.week = data.week || null;
                this.saved_json = JSON.stringify(this.week);
                this.open_time = data.open_time || null;
                this.closed_time = data.closed_time || null;
                if ('work_status' in data) this.work_status = data.work_status;
            },
            load() {
                this.loading = true;
                axios.get('/set_gym_hours/' + this.article_id)
                    .then(r => this.apply(r.data))
                    .catch(() => {})
                    .finally(() => { this.loading = false; });
            },
            // week object → the 7 rows the API takes (an empty list removes the per-day hours)
            save() {
                const days = this.week
                    ? WEEK_DAYS.map((d, i) => {
                        const x = this.week[d] || {};
                        return { weekday: i + 1, open: x.open || null, close: x.close || null, closed: !!x.closed };
                    })
                    : [];
                this.saving = true;
                axios.post('/set_gym_hours/' + this.article_id, { days })
                    .then(r => {
                        this.apply(r.data);
                        this.$bus.$emit('toast', { type: 'success', message: this.$t('admin.articles.working_hours.saved') });
                    })
                    .catch(e => {
                        const msg = e.response && e.response.data && e.response.data.message;
                        this.$bus.$emit('toast', { type: 'danger', message: msg || this.$t('admin.articles.working_hours.error') });
                    })
                    .finally(() => { this.saving = false; });
            },
        },
    };
</script>

<style scoped>
    .wh-tab { padding: 1rem 0; }
    .wh-tab .form-section-title .fa { color: var(--cg-accent); }
</style>
