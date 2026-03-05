t<template>
    <div class="dashboard-container">
        <div class="welcome-section">
            <h1>📊 Welcome to Your Orders Dashboard!</h1>
            <p>Track your order statistics and performance at a glance.</p>
        </div>

        <div class="controls-section">
            <div class="period-selector">
                <label for="period-select">Select Period:</label>
                <select id="period-select" v-model="selectedPeriod" @change="fetchOrderData" class="form-control">
                    <option v-for="period in periods" :key="period.value" :value="period.value">{{ period.label }}</option>
                </select>
            </div>
            <div class="refresh-btn">
                <button class="btn btn-success" @click="fetchOrderData" :disabled="loading">
                    <span v-if="loading">Loading...</span>
                    <span v-else>Refresh</span>
                </button>
            </div>
        </div>

        <div class="charts-section">
            <h3>Period: {{ selectedPeriodLabel }}</h3>
            <h3>Total Orders: {{ week_orders_data.reduce((sum, row) => sum + (typeof row[1] === 'number' ? row[1] : 0), 0) }}</h3>

            <div class="chart-card">
                <h2>📈 Quantity Diagram</h2>
                <GChart
                    v-if="!loading"
                    type="LineChart"
                    :data="week_orders_data"
                    :options="week_orders_options"
                />
                <div v-else class="loading">Loading chart...</div>
            </div>

            <div class="chart-card">
                <h2>💰 Finance Diagram</h2>
                <GChart
                    v-if="!loading"
                    type="LineChart"
                    :data="year_orders_data"
                    :options="year_orders_options"
                />
                <div v-else class="loading">Loading chart...</div>
            </div>
        </div>
    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'
    import axios from 'axios'

    export default {
        components: {
            GChart,
        },
        computed: {
            selectedPeriodLabel() {
                const period = this.periods.find(p => p.value === this.selectedPeriod)
                return period ? period.label : ''
            }
        },
        data(){
            return{
                selectedPeriod: '30days',
                periods: [
                    { value: '30days', label: 'Last 30 Days' },
                    { value: '1month', label: 'Last Month' },
                    { value: '3months', label: 'Last 3 Months' },
                    { value: '6months', label: 'Last 6 Months' },
                    { value: '1year', label: 'Last Year' },
                    { value: '2years', label: 'Last 2 Years' },
                    { value: '3years', label: 'Last 3 Years' },
                    { value: 'all', label: 'All Time' },
                ],
                loading: false,
                week_orders_data: [
                    ['Day', 'Orders', 'Last week'],
                    [13, 37, 80, ],
                    [14, 30, 9, ],
                    [15, 4, 8, ],
                    [16, 7, 40,],
                    [17, 37, 10,],
                    [18, 57, 8, ],
                    [19, 70, 84,],
                ],
                week_orders_options: {
                    title: 'Quantity Orders',
                    hAxis: {title: 'Day'},
                    vAxis: {title: 'Orders'},
                    legend: 'none',
                    colors: ['#4CAF50'],
                },

                year_orders_data: [
                    ['Month', 'Orders', 'Last yare'],
                    ['January',7, 5],
                    ['February',8, 5],
                    ['March',8, 5],
                    ['April',9, 5],
                    ['May',9, 5],
                    ['June',28, 5],
                    ['July',9, 5],
                    ['August',9, 5],
                    ['September',28, 5],
                    ['October',9, 5],
                    ['November',28, 5],
                    ['December',9, 5],
                ],
                year_orders_options: {
                    title: 'Year Orders',
                    hAxis: {title: 'Month'},
                    vAxis: {title: 'Orders'},
                    legend: 'none',
                    colors: ['#2196F3'],
                },
            }
        },
        mounted() {
            this.fetchOrderData();
            this.fetchYearData();
        },
        methods: {
            async fetchOrderData() {
                this.loading = true;
                try {
                    const response = await axios.get(`/order/get_order_statistics/${this.selectedPeriod}`);
                    this.week_orders_data = response.data;
                } catch (error) {
                    console.error('Error fetching order data:', error);
                    // Fallback to sample data
                    this.week_orders_data = [
                        ['Day', 'Orders'],
                        [1, 10],
                        [2, 15],
                        [3, 8],
                        [4, 20],
                        [5, 12],
                        [6, 18],
                        [7, 25],
                    ];
                } finally {
                    this.loading = false;
                }
            },
            async fetchYearData() {
                try {
                    const response = await axios.get('/order/get_order_statistics/1year');
                    const data = response.data;
                    // Map numbers to month names
                    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
                    this.year_orders_data = [['Month', 'Orders']];
                    for (let i = 1; i < data.length; i++) {
                        this.year_orders_data.push([months[i-1] || `Month ${i}`, data[i][1]]);
                    }
                } catch (error) {
                    console.error('Error fetching year data:', error);
                }
            }
        }
    }
</script>

<style scoped>
.dashboard-container {
    padding: 20px;
    width: 100%;
    font-family: Arial, sans-serif;
}

.welcome-section {
    text-align: center;
    margin-bottom: 30px;
    color: #333;
}

.welcome-section h1 {
    font-size: 2.5em;
    margin-bottom: 10px;
}

.welcome-section p {
    font-size: 1.2em;
    color: #666;
}

.controls-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    flex-wrap: wrap;
    gap: 10px;
}

.period-selector, .refresh-btn {
    display: inline-block;
}

.period-selector {
    display: flex;
    align-items: center;
    gap: 10px;
}

.period-selector label {
    font-weight: bold;
}

.form-control {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    min-width: 200px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-success:hover:not(:disabled) {
    background-color: #218838;
}

.btn:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
}

.charts-section {
    display: grid;
    grid-template-columns: 1fr;
    gap: 30px;
}

.chart-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    text-align: center;
}

.chart-card h2 {
    margin-bottom: 20px;
    color: #555;
    font-size: 1.5em;
}

.loading {
    text-align: center;
    padding: 40px;
    color: #999;
    font-style: italic;
}

@media (max-width: 768px) {
    .controls-section {
        flex-direction: column;
        align-items: stretch;
    }

    .period-selector {
        text-align: center;
    }

    .charts-section {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .dashboard-container {
        padding: 10px;
    }
}
</style>
