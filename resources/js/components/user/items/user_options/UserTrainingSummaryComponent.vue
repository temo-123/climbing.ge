<template>
    <div class="col-md-12 training-summary">
        <div v-if="loading" class="text-center text-muted py-4">
            {{ $t('user.training_summary.loading') }}
        </div>

        <template v-else-if="summary && summary.hasTrainingData">
            <div class="row mb-4">
                <div class="col-sm-4 mb-2">
                    <div class="card text-center training-summary__stat">
                        <div class="card-body">
                            <div class="training-summary__stat-value">{{ summary.totalSessions }}</div>
                            <div class="text-muted small">{{ $t('user.training_summary.total_sessions_label') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 mb-2">
                    <div class="card text-center training-summary__stat">
                        <div class="card-body">
                            <div class="training-summary__stat-value text-success">{{ summary.successCount }}</div>
                            <div class="text-muted small">{{ $t('user.training_summary.success_count_label') }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 mb-2">
                    <div class="card text-center training-summary__stat">
                        <div class="card-body">
                            <div class="training-summary__stat-value">{{ summary.customWorkoutsCount }}</div>
                            <div class="text-muted small">{{ $t('user.training_summary.custom_workouts_label') }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4" v-if="summary.chartHistory && summary.chartHistory.length">
                <div class="card-header">
                    <strong>{{ $t('user.training_summary.chart_title') }}</strong>
                </div>
                <div class="card-body">
                    <trainingProgressChart
                        :history="summary.chartHistory"
                        :success-label="$t('user.training_summary.status_success')"
                        :failed-label="$t('user.training_summary.status_failed')"
                    />
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header">
                    <strong>{{ $t('user.training_summary.active_plan_title') }}</strong>
                </div>
                <div class="card-body">
                    <template v-if="summary.activePlan">
                        <div class="d-flex align-items-start flex-wrap training-summary__plan">
                            <div class="training-summary__plan-emoji" v-if="planDetail && planDetail.emoji">{{ planDetail.emoji }}</div>
                            <div>
                                <div class="fw-bold">{{ summary.activePlan.name || summary.activePlan.planId }}</div>
                                <div class="text-muted small" v-if="summary.activePlan.startDate">
                                    {{ $t('user.training_summary.active_plan_started', { date: summary.activePlan.startDate }) }}
                                </div>
                                <p class="mb-1 mt-2" v-if="planDetail && planDetail.tagline">{{ planDetail.tagline }}</p>
                                <div class="training-summary__plan-meta text-muted small" v-if="planDetail">
                                    <span v-if="planDetail.level">{{ planDetail.level }}</span>
                                    <span v-if="planDetail.daysPerWeek"> · {{ $t('user.training_summary.days_per_week', { count: planDetail.daysPerWeek }) }}</span>
                                    <span v-if="planDetail.weeks"> · {{ $t('user.training_summary.weeks_count', { count: planDetail.weeks }) }}</span>
                                </div>
                            </div>
                        </div>
                    </template>
                    <p v-else class="text-muted mb-0">{{ $t('user.training_summary.no_active_plan') }}</p>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <strong>{{ $t('user.training_summary.recent_history_title') }}</strong>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush" v-if="summary.recentHistory.length">
                        <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap"
                            v-for="item in summary.recentHistory" :key="item.date">
                            <div>
                                <div class="fw-bold">{{ item.workoutName }}</div>
                                <div class="text-muted small">
                                    {{ item.date }} — {{ $t('user.training_summary.reps_sets', { reps: item.repsCompleted, sets: item.setsCompleted }) }}
                                </div>
                            </div>
                            <span class="badge" :class="item.status === 'success' ? 'bg-success' : 'bg-danger'">
                                {{ item.status === 'success' ? $t('user.training_summary.status_success') : $t('user.training_summary.status_failed') }}
                            </span>
                        </li>
                    </ul>
                    <p v-else class="text-muted p-3 mb-0">{{ $t('user.training_summary.no_history') }}</p>
                </div>
            </div>
        </template>

        <div v-else class="training-summary__promo text-center py-5">
            <h4 class="mb-3">{{ $t('user.training_summary.promo_title') }}</h4>
            <p class="text-muted mx-auto training-summary__promo-text">{{ $t('user.training_summary.promo_text') }}</p>

            <div class="training-summary__store-badges" v-if="appStoreLink || googlePlayLink">
                <a v-if="appStoreLink" :href="appStoreLink" target="_blank" rel="noopener">
                    <img src="/images/training_promo/app-store-badge.webp" :alt="$t('user.training_summary.app_store_alt')">
                </a>
                <a v-if="googlePlayLink" :href="googlePlayLink" target="_blank" rel="noopener">
                    <img src="/images/training_promo/google-play-badge.webp" :alt="$t('user.training_summary.google_play_alt')">
                </a>
            </div>
        </div>
    </div>
</template>

<script>
    import trainingProgressChart from './TrainingProgressChartComponent.vue'

    export default {
        components: {
            trainingProgressChart,
        },
        data() {
            return {
                loading: true,
                summary: null,
                planDetail: null,
                appStoreLink: '',
                googlePlayLink: '',
            }
        },
        mounted() {
            this.get_training_summary()
            this.get_apps_links()
        },
        methods: {
            get_training_summary() {
                this.loading = true
                axios
                    .get('user_training/summary')
                    .then(response => {
                        this.summary = response.data
                        if (this.summary.activePlan && this.summary.activePlan.planId) {
                            this.get_plan_detail(this.summary.activePlan.planId)
                        }
                    })
                    .catch(error => {
                        console.log('Error fetching training summary:', error)
                        this.$bus.$emit('toast', {
                            type: 'danger',
                            title: this.$t('user.training_summary.load_error'),
                            message: this.$t('user.training_summary.load_error'),
                        })
                    })
                    .finally(() => {
                        this.loading = false
                    })
            },
            get_plan_detail(planId) {
                axios
                    .get('get_training_plan/get_plan_data/' + planId)
                    .then(response => {
                        this.planDetail = response.data
                    })
                    .catch(error => {
                        console.log('Error fetching active plan detail:', error)
                    })
            },
            get_apps_links() {
                axios
                    .get('get_apps_links/get_all')
                    .then(response => {
                        const links = response.data || []
                        const appStore = links.find(l => l.keyword === 'training_app_store')
                        const googlePlay = links.find(l => l.keyword === 'training_play_store')
                        this.appStoreLink = appStore ? appStore.link : ''
                        this.googlePlayLink = googlePlay ? googlePlay.link : ''
                    })
                    .catch(error => {
                        console.log('Error fetching app links:', error)
                    })
            }
        }
    }
</script>

<style scoped>
    .training-summary__stat-value {
        font-size: 2rem;
        font-weight: bold;
        line-height: 1.2;
    }

    .training-summary__plan {
        gap: 1rem;
    }

    .training-summary__plan-emoji {
        font-size: 2.5rem;
        line-height: 1;
    }

    .training-summary__promo-text {
        max-width: 480px;
    }

    .training-summary__store-badges {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }

    .training-summary__store-badges img {
        height: 56px;
        width: auto;
    }
</style>
