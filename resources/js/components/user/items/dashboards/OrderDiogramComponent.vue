<template>
    <div class="order-dashboard">

        <div class="order-controls mb-4 d-flex align-items-center flex-wrap" style="gap:10px;">
            <label class="mb-0 font-weight-bold">Period:</label>
            <select class="form-control d-inline-block w-auto" v-model="selectedPeriod" @change="fetchAll">
                <option v-for="p in periods" :key="p.value" :value="p.value">{{ p.label }}</option>
            </select>
            <button class="btn btn-sm btn-outline-secondary" @click="fetchAll" :disabled="loading">
                <i class="fa fa-refresh"></i> {{ loading ? 'Loading…' : 'Refresh' }}
            </button>
            <span v-if="!loading && total_orders !== null" class="text-muted small ml-3">
                Total orders: <strong>{{ total_orders }}</strong>
            </span>
        </div>

        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h6 class="card-title">Quantity Diagram</h6>
                        <div v-if="loading" class="text-center py-5 text-muted">Loading…</div>
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
                        <h6 class="card-title">Finance Diagram (Revenue)</h6>
                        <div v-if="loading" class="text-center py-5 text-muted">Loading…</div>
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
                    { value: '30days',  label: 'Last 30 Days' },
                    { value: '1month',  label: 'Last Month' },
                    { value: '3months', label: 'Last 3 Months' },
                    { value: '6months', label: 'Last 6 Months' },
                    { value: '1year',   label: 'Last Year' },
                    { value: '2years',  label: 'Last 2 Years' },
                    { value: '3years',  label: 'Last 3 Years' },
                    { value: 'all',     label: 'All Time' },
                ],
                loading: false,
                qty_data: [['Date', 'Orders']],
                fin_data: [['Date', 'Revenue']],
                qty_options: {
                    legend: 'none',
                    colors: ['#4CAF50'],
                    hAxis: { title: 'Period' },
                    vAxis: { title: 'Orders', minValue: 0 },
                    chartArea: { width: '80%', height: '70%' },
                    curveType: 'function',
                },
                fin_options: {
                    legend: 'none',
                    colors: ['#2196F3'],
                    hAxis: { title: 'Period' },
                    vAxis: { title: 'Revenue (GEL)', minValue: 0 },
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
                    this.qty_data = qtyRes.data && qtyRes.data.length > 1 ? qtyRes.data : [['Date', 'Orders'], ['—', 0]]
                    this.fin_data = finRes.data && finRes.data.length > 1 ? finRes.data : [['Date', 'Revenue'], ['—', 0]]
                } catch (e) {
                    console.error('Order statistics error:', e)
                    this.qty_data = [['Date', 'Orders'], ['—', 0]]
                    this.fin_data = [['Date', 'Revenue'], ['—', 0]]
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
