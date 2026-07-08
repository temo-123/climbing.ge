<template>
    <div class="donation-block">

        <div class="donation-controls mb-3 d-flex align-items-center gap-2 flex-wrap">
            <label class="mb-0 font-weight-bold">{{ $t('admin.dashboards.period_label') }}</label>
            <select class="form-control d-inline-block w-auto" v-model="selectedPeriod" @change="applyPeriod">
                <option value="30days">{{ $t('admin.dashboards.period_last_30_days') }}</option>
                <option value="3months">{{ $t('admin.dashboards.period_last_3_months') }}</option>
                <option value="6months">{{ $t('admin.dashboards.period_last_6_months') }}</option>
                <option value="1year">{{ $t('admin.dashboards.period_last_year') }}</option>
                <option value="all">{{ $t('admin.dashboards.period_all_time') }}</option>
            </select>
            <button class="btn btn-sm btn-outline-secondary" @click="load">
                <i class="fa fa-refresh"></i> {{ $t('common.refresh') }}
            </button>
        </div>

        <div class="row mb-4" v-if="!loading && donations.length">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-2">
                        <h6 class="card-title text-center">{{ $t('admin.dashboards.donation_list.donations_count_by_month_title') }}</h6>
                        <GChart type="ColumnChart" :data="count_chart_data" :options="count_chart_options" style="width:100%;height:220px;" />
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body p-2">
                        <h6 class="card-title text-center">{{ $t('admin.dashboards.donation_list.donations_amount_by_month_title') }}</h6>
                        <GChart type="ColumnChart" :data="amount_chart_data" :options="amount_chart_options" style="width:100%;height:220px;" />
                    </div>
                </div>
            </div>
        </div>

        <div v-if="loading" class="text-center py-4">
            <skeleton-loader viewBox="0 0 500 150" primaryColor="#f3f3f3" secondaryColor="#7427bb75">
                <rect x="0" y="0" rx="2" ry="2" width="100%" height="25" />
                <rect x="0" y="45" rx="3" ry="3" width="100%" height="10" />
                <rect x="0" y="60" rx="3" ry="3" width="100%" height="10" />
                <rect x="0" y="75" rx="3" ry="3" width="100%" height="10" />
            </skeleton-loader>
        </div>

        <div v-else>
            <div class="d-flex justify-content-between align-items-center mb-2">
                <div class="text-muted small">
                    {{ $t('admin.dashboards.donation_list.showing_donations_label', { count: filtered_donations.length }) }} <strong>{{ total_amount }}</strong>
                </div>
            </div>

            <div v-if="filtered_donations.length === 0" class="text-center text-muted py-4">{{ $t('admin.dashboards.donation_list.no_donations_found') }}</div>

            <div v-else class="table-responsive">
                <table class="table table-hover table-sm">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ $t('common.name') }}</th>
                            <th>{{ $t('common.email') }}</th>
                            <th>{{ $t('admin.dashboards.donation_list.col_amount') }}</th>
                            <th>{{ $t('admin.orders.status_label') }}</th>
                            <th>{{ $t('admin.dashboards.donation_list.col_user') }}</th>
                            <th>{{ $t('common.date') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="d in filtered_donations" :key="d.id">
                            <td>{{ d.id }}</td>
                            <td>{{ d.name }} {{ d.surname }}</td>
                            <td>{{ d.email }}</td>
                            <td><strong>{{ d.amount }} {{ d.currency }}</strong></td>
                            <td>
                                <span class="badge" :class="status_badge(d.status)">{{ d.status }}</span>
                            </td>
                            <td>{{ d.user ? d.user.name : '—' }}</td>
                            <td>{{ d.created_at ? d.created_at.substring(0, 10) : '—' }}</td>
                            <td class="text-nowrap">
                                <button v-if="$can('edit', 'donation')" class="btn btn-xs btn-outline-primary mr-1" @click="show_status_modal(d)" :title="$t('admin.task.update_status_tooltip')">
                                    <i class="fa fa-edit"></i>
                                </button>
                                <button v-if="$can('del', 'donation')" class="btn btn-xs btn-outline-danger" @click="del_donation(d.id)" :title="$t('common.delete')">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <StackModal
            v-model="is_status_modal"
            :title="$t('admin.dashboards.donation_list.update_donation_status_title')"
            @close="is_status_modal = false"
            @save="save_status"
            :saveButton="{ visible: true, title: $t('common.save'), btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        >
            <div v-if="editing_donation">
                <p><strong>#{{ editing_donation.id }}</strong> — {{ editing_donation.name }} {{ editing_donation.surname }}</p>
                <p>{{ $t('admin.dashboards.donation_list.amount_prefix') }} <strong>{{ editing_donation.amount }} {{ editing_donation.currency }}</strong></p>
                <select class="form-control" v-model="new_status">
                    <option value="pending">{{ $t('admin.dashboards.donation_list.status_pending') }}</option>
                    <option value="processing">{{ $t('admin.dashboards.donation_list.status_processing') }}</option>
                    <option value="paid">{{ $t('admin.dashboards.donation_list.status_paid') }}</option>
                    <option value="failed">{{ $t('admin.dashboards.donation_list.status_failed') }}</option>
                    <option value="declined">{{ $t('admin.dashboards.donation_list.status_declined') }}</option>
                    <option value="expired">{{ $t('admin.dashboards.donation_list.status_expired') }}</option>
                </select>
            </div>
        </StackModal>
    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'

    export default {
        components: { GChart },
        data() {
            return {
                donations: [],
                filtered_donations: [],
                loading: false,
                selectedPeriod: 'all',
                is_status_modal: false,
                editing_donation: null,
                new_status: 'pending',
            }
        },
        computed: {
            total_amount() {
                const sum = this.filtered_donations
                    .filter(d => d.status === 'paid')
                    .reduce((acc, d) => acc + (parseFloat(d.amount) || 0), 0)
                return sum.toFixed(2) + ' GEL'
            },
            count_chart_data() {
                const map = this.build_month_map(d => 1)
                const header = [['Month', 'Count']]
                return header.concat(Object.entries(map).map(([k, v]) => [k, v]))
            },
            amount_chart_data() {
                const map = this.build_month_map(d => parseFloat(d.amount) || 0)
                const header = [['Month', 'Amount']]
                return header.concat(Object.entries(map).map(([k, v]) => [k, parseFloat(v.toFixed(2))]))
            },
            count_chart_options() {
                return {
                    legend: 'none',
                    colors: ['#6f42c1'],
                    hAxis: { slantedText: true, slantedTextAngle: 45 },
                    vAxis: { minValue: 0 },
                    chartArea: { width: '85%', height: '65%' },
                }
            },
            amount_chart_options() {
                return {
                    legend: 'none',
                    colors: ['#28a745'],
                    hAxis: { slantedText: true, slantedTextAngle: 45 },
                    vAxis: { minValue: 0 },
                    chartArea: { width: '85%', height: '65%' },
                }
            },
        },
        mounted() {
            this.load()
        },
        methods: {
            load() {
                this.loading = true
                axios.get('set_donation_admin/get_all_donations')
                    .then(r => {
                        this.donations = r.data
                        this.applyPeriod()
                    })
                    .catch(e => console.error(e))
                    .finally(() => { this.loading = false })
            },
            applyPeriod() {
                const now = new Date()
                let cutoff = null
                if (this.selectedPeriod === '30days') cutoff = new Date(now - 30 * 86400000)
                else if (this.selectedPeriod === '3months') cutoff = new Date(now.getFullYear(), now.getMonth() - 3, now.getDate())
                else if (this.selectedPeriod === '6months') cutoff = new Date(now.getFullYear(), now.getMonth() - 6, now.getDate())
                else if (this.selectedPeriod === '1year') cutoff = new Date(now.getFullYear() - 1, now.getMonth(), now.getDate())

                this.filtered_donations = cutoff
                    ? this.donations.filter(d => d.created_at && new Date(d.created_at) >= cutoff)
                    : [...this.donations]
            },
            build_month_map(valueFn) {
                const map = {}
                for (const d of this.filtered_donations) {
                    if (!d.created_at) continue
                    const dt = new Date(d.created_at)
                    const key = dt.toLocaleString('en', { month: 'short', year: '2-digit' })
                    map[key] = (map[key] || 0) + valueFn(d)
                }
                return map
            },
            status_badge(status) {
                const map = {
                    paid: 'badge-success', pending: 'badge-warning', processing: 'badge-info',
                    failed: 'badge-danger', declined: 'badge-danger', expired: 'badge-secondary',
                }
                return map[status] || 'badge-secondary'
            },
            show_status_modal(d) {
                this.editing_donation = d
                this.new_status = d.status
                this.is_status_modal = true
            },
            save_status() {
                if (!this.editing_donation) return
                axios.post(`set_donation_admin/update_status/${this.editing_donation.id}`, { status: this.new_status })
                    .then(() => {
                        this.is_status_modal = false
                        this.load()
                    })
                    .catch(e => console.error(e))
            },
            del_donation(id) {
                if (!confirm(this.$t('admin.dashboards.donation_list.confirm_delete_donation'))) return
                axios.delete(`set_donation_admin/del_donation/${id}`)
                    .then(() => this.load())
                    .catch(e => console.error(e))
            },
        },
    }
</script>

<style scoped>
.donation-block { width: 100%; }
.btn-xs { padding: 2px 6px; font-size: 0.75rem; }
.gap-2 { gap: 0.5rem; }
</style>
