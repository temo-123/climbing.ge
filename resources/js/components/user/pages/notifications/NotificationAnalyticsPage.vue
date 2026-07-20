<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-12 d-flex align-items-center flex-wrap" style="gap:10px;">
                    <label class="mb-0 font-weight-bold">{{ $t('admin.dashboards.period_label') }}</label>
                    <select class="form-control d-inline-block w-auto" v-model="selectedPeriod" @change="fetchVolume">
                        <option v-for="p in periods" :key="p.value" :value="p.value">{{ p.label }}</option>
                    </select>
                    <button class="btn btn-success" @click="fetchStatistics" :disabled="loading">
                        <i class="fa fa-refresh"></i> {{ loading ? $t('admin.dashboards.loading_ellipsis_dots') : $t('common.refresh') }}
                    </button>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-2 col-sm-4 mb-2">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="text-muted small">{{ $t('admin.notification_analytics.stat_total_sent') }}</div>
                            <div class="h3 mb-0">{{ summary.total_sent ?? '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 mb-2">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="text-muted small">{{ $t('admin.notification_analytics.stat_last_7_days') }}</div>
                            <div class="h3 mb-0">{{ summary.sent_last_7_days ?? '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2 col-sm-4 mb-2">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="text-muted small">{{ $t('admin.notification_analytics.stat_last_30_days') }}</div>
                            <div class="h3 mb-0">{{ summary.sent_last_30_days ?? '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="text-muted small">{{ $t('admin.notification_analytics.stat_users_notified') }}</div>
                            <div class="h3 mb-0">{{ summary.distinct_users_notified ?? '—' }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-2">
                    <div class="card h-100">
                        <div class="card-body text-center">
                            <div class="text-muted small">{{ $t('admin.notification_analytics.stat_preferences_set') }}</div>
                            <div class="h3 mb-0">{{ summary.users_with_preferences_set ?? '—' }} / {{ summary.total_users ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-7 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">{{ $t('admin.notification_analytics.volume_chart_title') }}</h6>
                            <div v-if="loading" class="text-center py-5 text-muted">{{ $t('admin.dashboards.loading_ellipsis_dots') }}</div>
                            <GChart
                                v-else
                                type="LineChart"
                                :data="volume_data"
                                :options="volume_options"
                                style="width:100%;height:280px;"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-5 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">{{ $t('admin.notification_analytics.breakdown_by_key_title') }}</h6>
                            <div v-if="loading" class="text-center py-5 text-muted">{{ $t('admin.dashboards.loading_ellipsis_dots') }}</div>
                            <GChart
                                v-else
                                type="PieChart"
                                :data="key_breakdown_data"
                                :options="pie_options"
                                style="width:100%;height:280px;"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-5 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">{{ $t('admin.notification_analytics.breakdown_by_content_type_title') }}</h6>
                            <div v-if="loading" class="text-center py-5 text-muted">{{ $t('admin.dashboards.loading_ellipsis_dots') }}</div>
                            <GChart
                                v-else
                                type="PieChart"
                                :data="content_type_breakdown_data"
                                :options="pie_options"
                                style="width:100%;height:280px;"
                            />
                        </div>
                    </div>
                </div>
                <div class="col-md-7 mb-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">{{ $t('admin.notification_analytics.adoption_chart_title') }}</h6>
                            <div v-if="loading" class="text-center py-5 text-muted">{{ $t('admin.dashboards.loading_ellipsis_dots') }}</div>
                            <GChart
                                v-else
                                type="BarChart"
                                :data="adoption_chart_data"
                                :options="adoption_options"
                                style="width:100%;height:420px;"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <div v-if="loading_recent" class="text-center py-4 text-muted">{{ $t('admin.dashboards.loading_ellipsis_dots') }}</div>
                            <tabsComponent
                                v-else
                                :table_data="activity_tab_data"
                                @update="fetchActivityTabs"
                                @del_guest_follower="del_guest_follower"
                                @show_subscriber_detail="show_subscriber_detail"
                                @show_guest_follower_detail="show_guest_follower_detail"
                                @retry_failed_job="retry_failed_job"
                                @del_failed_job="del_failed_job"
                                @retry_all_failed_jobs="retry_all_failed_jobs"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <SubscriberDetailModal ref="subscriber_detail_modal" @refreshed="fetchRegisteredSubscribers" />
        <GuestFollowerDetailModal ref="guest_follower_detail_modal" />
    </div>
</template>

<script>
import { GChart } from 'vue-google-charts'
import breadcrumb from '../../items/BreadcrumbComponent.vue'
import tabsComponent from '../../items/data_table/TabsComponent.vue'
import SubscriberDetailModal from '../../items/modal/SubscriberDetailModal.vue'
import GuestFollowerDetailModal from '../../items/modal/GuestFollowerDetailModal.vue'

export default {
    components: { GChart, breadcrumb, tabsComponent, SubscriberDetailModal, GuestFollowerDetailModal },
    data() {
        return {
            selectedPeriod: '30days',
            periods: [
                { value: '30days', label: this.$t('admin.dashboards.period_last_30_days') },
                { value: '1month', label: this.$t('admin.dashboards.period_last_month') },
                { value: '3months', label: this.$t('admin.dashboards.period_last_3_months') },
                { value: '6months', label: this.$t('admin.dashboards.period_last_6_months') },
                { value: '1year', label: this.$t('admin.dashboards.period_last_year') },
                { value: 'all', label: this.$t('admin.dashboards.period_all_time') },
            ],
            loading: false,
            loading_recent: false,
            summary: {},
            volume_data: [['Date', 'Notifications']],
            key_breakdown_data: [['Type', 'Count']],
            content_type_breakdown_data: [['Content Type', 'Count']],
            adoption: [],
            recent: [],
            registered_subscribers: [],
            guest_followers: [],
            failed_jobs: [],
            volume_options: {
                legend: 'none',
                colors: ['#4CAF50'],
                hAxis: { title: this.$t('admin.dashboards.chart_period_axis') },
                vAxis: { title: this.$t('admin.notification_analytics.chart_notifications_col'), minValue: 0 },
                chartArea: { width: '80%', height: '65%' },
                curveType: 'function',
            },
            pie_options: {
                colors: ['#4CAF50', '#2196F3', '#FF9800', '#9C27B0', '#F44336', '#00BCD4', '#8BC34A', '#795548', '#607D8B', '#E91E63'],
                chartArea: { width: '90%', height: '80%' },
            },
            adoption_options: {
                legend: 'none',
                colors: ['#2196F3'],
                hAxis: { title: this.$t('admin.notification_analytics.chart_users_col'), minValue: 0 },
                chartArea: { width: '60%', height: '85%' },
            },
        }
    },
    computed: {
        adoption_chart_data() {
            const header = [this.$t('admin.notification_analytics.col_preference'), this.$t('admin.notification_analytics.chart_users_col')]
            if (!this.adoption.length) return [header]
            return [header, ...this.adoption.map(a => [a.label, a.enabled_count])]
        },
        recent_tab_data() {
            return {
                id: 1,
                table_name: this.$t('admin.notification_analytics.recent_activity_title'),
                tab_data: {
                    data: this.recent.map((r, idx) => ({ id: idx + 1, ...r })),
                    tab: {
                        head: [
                            this.$t('admin.notification_analytics.col_user'),
                            this.$t('admin.notification_analytics.col_type'),
                            this.$t('admin.notification_analytics.col_content_type'),
                            this.$t('admin.notification_analytics.col_content_id'),
                            this.$t('admin.notification_analytics.col_sent_at'),
                        ],
                        body: [
                            ['data', ['user_email']],
                            ['data', ['notification_key']],
                            ['data', ['content_type']],
                            ['data', ['notifiable_id']],
                            ['data', ['sent_at']],
                        ],
                        perm: [['no'], ['no'], ['no'], ['no'], ['no']]
                    }
                }
            }
        },
        registered_subscribers_tab_data() {
            return {
                id: 2,
                table_name: this.$t('admin.notification_analytics.registered_subscribers_title'),
                tab_data: {
                    data: this.registered_subscribers,
                    tab: {
                        head: [
                            this.$t('common.id'),
                            this.$t('common.name'),
                            this.$t('common.email'),
                            this.$t('admin.notification_analytics.col_details'),
                        ],
                        body: [
                            ['data', ['id']],
                            ['data', ['name']],
                            ['data', ['email']],
                            ['action_fun_id', 'show_subscriber_detail', 'btn btn-info', '<i aria-hidden="true" class="fa fa-eye"></i>'],
                        ],
                        perm: [['no'], ['no'], ['no'], ['no']]
                    }
                }
            }
        },
        guest_followers_tab_data() {
            return {
                id: 3,
                table_name: this.$t('admin.notification_analytics.guest_followers_title'),
                tab_data: {
                    data: this.guest_followers,
                    tab: {
                        head: [
                            this.$t('common.id'),
                            this.$t('common.email'),
                            this.$t('admin.notification_analytics.col_service'),
                            this.$t('admin.notification_analytics.col_followed_at'),
                            this.$t('admin.notification_analytics.col_details'),
                            this.$t('common.delete'),
                        ],
                        body: [
                            ['data', ['id']],
                            ['data', ['email']],
                            ['data', ['service']],
                            ['data', ['created_at']],
                            ['action_fun_id', 'show_guest_follower_detail', 'btn btn-info', '<i aria-hidden="true" class="fa fa-eye"></i>'],
                            ['action_fun_id', 'del_guest_follower', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                        ],
                        perm: [['no'], ['no'], ['no'], ['no'], ['no'], ['user_notification', 'del']]
                    }
                }
            }
        },
        failed_jobs_tab_data() {
            return {
                id: 4,
                table_name: this.$t('admin.notification_analytics.failed_jobs_title'),
                add_action: {
                    action: 'fun',
                    link: 'retry_all_failed_jobs',
                    class: 'btn btn-warning',
                    btn_title: this.$t('admin.notification_analytics.retry_all_btn'),
                },
                tab_data: {
                    data: this.failed_jobs,
                    tab: {
                        head: [
                            this.$t('common.id'),
                            this.$t('admin.notification_analytics.col_email'),
                            this.$t('admin.notification_analytics.col_subject'),
                            this.$t('admin.notification_analytics.col_error'),
                            this.$t('admin.notification_analytics.col_failed_at'),
                            this.$t('admin.notification_analytics.retry_btn'),
                            this.$t('common.delete'),
                        ],
                        body: [
                            ['data', ['id']],
                            ['data', ['email']],
                            ['data', ['subject']],
                            ['data', ['error']],
                            ['data', ['failed_at']],
                            ['action_fun_id', 'retry_failed_job', 'btn btn-success', '<i aria-hidden="true" class="fa fa-refresh"></i>'],
                            ['action_fun_id', 'del_failed_job', 'btn btn-danger', '<i aria-hidden="true" class="fa fa-trash"></i>'],
                        ],
                        perm: [['no'], ['no'], ['no'], ['no'], ['no'], ['user_notification', 'manage'], ['user_notification', 'manage']]
                    }
                }
            }
        },
        activity_tab_data() {
            return [this.recent_tab_data, this.registered_subscribers_tab_data, this.guest_followers_tab_data, this.failed_jobs_tab_data]
        },
    },
    mounted() {
        this.fetchAll()
    },
    methods: {
        async fetchAll() {
            // fetchActivityTabs() already refreshes statistics too, so this
            // covers the whole page without double-fetching the summary/charts.
            await this.fetchActivityTabs()
        },
        async fetchStatistics() {
            this.loading = true
            try {
                await Promise.all([
                    this.fetchSummary(),
                    this.fetchVolume(),
                    this.fetchKeyBreakdown(),
                    this.fetchContentTypeBreakdown(),
                    this.fetchAdoption(),
                ])
            } finally {
                this.loading = false
            }
        },
        async fetchSummary() {
            const res = await axios.get('/set_notification_analytics/summary')
            this.summary = res.data
        },
        async fetchVolume() {
            const res = await axios.get(`/set_notification_analytics/volume_over_time/${this.selectedPeriod}`)
            this.volume_data = res.data && res.data.length > 1 ? res.data : [['Date', 'Notifications'], ['—', 0]]
        },
        async fetchKeyBreakdown() {
            const res = await axios.get('/set_notification_analytics/breakdown_by_key')
            this.key_breakdown_data = res.data && res.data.length > 1 ? res.data : [['Type', 'Count'], ['—', 0]]
        },
        async fetchContentTypeBreakdown() {
            const res = await axios.get('/set_notification_analytics/breakdown_by_content_type')
            this.content_type_breakdown_data = res.data && res.data.length > 1 ? res.data : [['Content Type', 'Count'], ['—', 0]]
        },
        async fetchAdoption() {
            const res = await axios.get('/set_notification_analytics/preference_adoption')
            this.adoption = res.data
        },
        async fetchRecent() {
            const res = await axios.get('/set_notification_analytics/recent')
            this.recent = res.data
        },
        async fetchRegisteredSubscribers() {
            const res = await axios.get('/set_notification_analytics/registered_subscribers')
            this.registered_subscribers = res.data
        },
        async fetchActivityTabs() {
            this.loading_recent = true
            try {
                await Promise.all([
                    this.fetchRecent(),
                    this.fetchRegisteredSubscribers(),
                    this.fetchGuestFollowers(),
                    this.fetchFailedJobs(),
                    this.fetchStatistics(),
                ])
            } finally {
                this.loading_recent = false
            }
        },
        async fetchGuestFollowers() {
            const res = await axios.get('/set_notification_analytics/guest_followers')
            this.guest_followers = res.data
        },
        async fetchFailedJobs() {
            const res = await axios.get('/set_notification_analytics/failed_jobs')
            this.failed_jobs = res.data
        },
        retry_failed_job(id) {
            axios
                .post(`/set_notification_analytics/failed_jobs/${id}/retry`)
                .then(() => this.fetchFailedJobs())
                .catch(error => console.log(error))
        },
        retry_all_failed_jobs() {
            if (!confirm(this.$t('admin.notification_analytics.confirm_retry_all'))) return
            axios
                .post('/set_notification_analytics/failed_jobs/retry_all')
                .then(() => this.fetchFailedJobs())
                .catch(error => console.log(error))
        },
        del_failed_job(id) {
            if (confirm(this.$t('admin.common.confirm_delete'))) {
                axios
                    .delete(`/set_notification_analytics/failed_jobs/${id}`)
                    .then(() => this.fetchFailedJobs())
                    .catch(error => console.log(error))
            }
        },
        del_guest_follower(id) {
            if (confirm(this.$t('admin.common.confirm_delete'))) {
                axios
                    .delete(`/set_notification_analytics/guest_followers/${id}`)
                    .then(() => this.fetchGuestFollowers())
                    .catch(error => console.log(error))
            }
        },
        show_subscriber_detail(id) {
            const subscriber = this.registered_subscribers.find(s => s.id == id)
            if (subscriber) this.$refs.subscriber_detail_modal.show_modal(subscriber)
        },
        show_guest_follower_detail(id) {
            const follower = this.guest_followers.find(f => f.id == id)
            if (follower) this.$refs.guest_follower_detail_modal.show_modal(follower)
        },
    },
}
</script>

<style scoped>
</style>
