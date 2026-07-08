<template>
    <div class="order-dashboard">

        <div class="order-controls mb-4 d-flex align-items-center flex-wrap" style="gap:10px;">
            <label class="mb-0 font-weight-bold">{{ $t('admin.dashboards.period_label') }}</label>
            <select class="form-control d-inline-block w-auto" v-model="selectedPeriod" @change="fetchAll">
                <option v-for="p in periods" :key="p.value" :value="p.value">{{ p.label }}</option>
            </select>
            <button class="btn btn-sm btn-outline-secondary" @click="fetchAll" :disabled="loading">
                <i class="fa fa-refresh"></i> {{ loading ? $t('admin.dashboards.loading_ellipsis_dots') : $t('common.refresh') }}
            </button>
            <span v-if="!loading && total_orders !== null" class="text-muted small ml-3">
                {{ $t('admin.dashboards.total_orders_label') }} <strong>{{ total_orders }}</strong>
            </span>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">{{ $t('admin.dashboards.quantity_diagram_title') }}</h6>
                        <div v-if="loading" class="text-center py-5 text-muted">{{ $t('admin.dashboards.loading_ellipsis_dots') }}</div>
                        <GChart
                            v-else
                            type="LineChart"
                            :data="qty_data"
                            :options="qty_options"
                            style="width:100%;height:260px;"
                        />
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">{{ $t('admin.dashboards.finance_diagram_title') }}</h6>
                        <div v-if="loading" class="text-center py-5 text-muted">{{ $t('admin.dashboards.loading_ellipsis_dots') }}</div>
                        <GChart
                            v-else
                            type="LineChart"
                            :data="fin_data"
                            :options="fin_options"
                            style="width:100%;height:260px;"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'

    export default {
        components: { GChart },
        data() {
            return {
                selectedPeriod: '30days',
                periods: [
                    { value: '30days',  label: this.$t('admin.dashboards.period_last_30_days') },
                    { value: '1month',  label: this.$t('admin.dashboards.period_last_month') },
                    { value: '3months', label: this.$t('admin.dashboards.period_last_3_months') },
                    { value: '6months', label: this.$t('admin.dashboards.period_last_6_months') },
                    { value: '1year',   label: this.$t('admin.dashboards.period_last_year') },
                    { value: '2years',  label: this.$t('admin.dashboards.period_last_2_years') },
                    { value: '3years',  label: this.$t('admin.dashboards.period_last_3_years') },
                    { value: 'all',     label: this.$t('admin.dashboards.period_all_time') },
                ],
                loading: false,
                qty_data: [[this.$t('admin.dashboards.chart_date_col'), this.$t('admin.dashboards.chart_orders_col')]],
                fin_data: [[this.$t('admin.dashboards.chart_date_col'), this.$t('admin.dashboards.chart_revenue_col')]],
                qty_options: {
                    legend: 'none',
                    colors: ['#4CAF50'],
                    hAxis: { title: this.$t('admin.dashboards.chart_period_axis') },
                    vAxis: { title: this.$t('admin.dashboards.chart_orders_col'), minValue: 0 },
                    chartArea: { width: '80%', height: '70%' },
                    curveType: 'function',
                },
                fin_options: {
                    legend: 'none',
                    colors: ['#2196F3'],
                    hAxis: { title: this.$t('admin.dashboards.chart_period_axis') },
                    vAxis: { title: this.$t('admin.dashboards.chart_revenue_gel_axis'), minValue: 0 },
                    chartArea: { width: '80%', height: '70%' },
                    curveType: 'function',
                },
            }
        },
        computed: {
            total_orders() {
                if (!this.qty_data || this.qty_data.length < 2) return null
                return this.qty_data.slice(1).reduce((s, r) => s + (typeof r[1] === 'number' ? r[1] : 0), 0)
            },
        },
        mounted() {
            this.fetchAll()
        },
        methods: {
            async fetchAll() {
                this.loading = true
                try {
                    const [qtyRes, finRes] = await Promise.all([
                        axios.get(`get_order/get_order_statistics/${this.selectedPeriod}`),
                        axios.get(`get_order/get_order_finance_statistics/${this.selectedPeriod}`),
                    ])
                    this.qty_data = qtyRes.data && qtyRes.data.length > 1 ? qtyRes.data : [[this.$t('admin.dashboards.chart_date_col'), this.$t('admin.dashboards.chart_orders_col')], ['—', 0]]
                    this.fin_data = finRes.data && finRes.data.length > 1 ? finRes.data : [[this.$t('admin.dashboards.chart_date_col'), this.$t('admin.dashboards.chart_revenue_col')], ['—', 0]]
                } catch (e) {
                    console.error('Order statistics error:', e)
                    this.qty_data = [[this.$t('admin.dashboards.chart_date_col'), this.$t('admin.dashboards.chart_orders_col')], ['—', 0]]
                    this.fin_data = [[this.$t('admin.dashboards.chart_date_col'), this.$t('admin.dashboards.chart_revenue_col')], ['—', 0]]
                } finally {
                    this.loading = false
                }
            },
        },
    }
</script>

<style scoped>
.order-dashboard { width: 100%; }
</style>
