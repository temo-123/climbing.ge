<template>
    <stack-modal
        :show="show_statistic_modal"
        title="General Info Statistics"
        size="xl"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: false }"
        @close="close_modal()"
    >
        <div v-if="loading" class="text-center" style="padding: 40px 0;">
            <i class="fa fa-spinner fa-spin fa-2x"></i>
            <p style="margin-top:10px; color:#888;">Loading statistics...</p>
        </div>

        <div v-else-if="error" class="alert alert-danger">{{ error }}</div>

        <div v-else>
            <!-- Summary tiles -->
            <div class="row" style="margin-bottom: 24px;">
                <div class="col-xs-6 col-sm-3">
                    <div class="stat-tile">
                        <div class="stat-tile-label">Today</div>
                        <div class="stat-tile-value">{{ stats.today }}</div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="stat-tile" style="border-color:#9b59b6;">
                        <div class="stat-tile-label">This Week</div>
                        <div class="stat-tile-value" style="color:#9b59b6;">{{ stats.this_week }}</div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="stat-tile" style="border-color:#27ae60;">
                        <div class="stat-tile-label">This Month</div>
                        <div class="stat-tile-value" style="color:#27ae60;">{{ stats.this_month }}</div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-3">
                    <div class="stat-tile" style="border-color:#f39c12;">
                        <div class="stat-tile-label">This Year</div>
                        <div class="stat-tile-value" style="color:#f39c12;">{{ stats.this_year }}</div>
                    </div>
                </div>
            </div>

            <!-- Last 30 days chart -->
            <h5 style="margin-bottom: 14px; font-weight: 600;">Last 30 days — daily clicks</h5>

            <GChart
                v-if="chart_data.length > 1"
                type="ColumnChart"
                :data="chart_data"
                :options="chart_options"
                style="width:100%; height:340px;"
            />
            <p v-else style="color:#aaa; text-align:center; padding: 30px 0;">No data yet.</p>
        </div>
    </stack-modal>
</template>

<script>
    import { GChart } from 'vue-google-charts';

    export default {
        components: { GChart },

        data() {
            return {
                show_statistic_modal: false,
                general_info_id: null,
                loading: false,
                error: null,
                stats: {
                    today: 0,
                    this_week: 0,
                    this_month: 0,
                    this_year: 0,
                    last_30_days: {},
                },
                chart_options: {
                    title: 'Click Activity — Last 30 Days',
                    titleTextStyle: { fontSize: 14, bold: true, color: '#333' },
                    hAxis: {
                        title: 'Date',
                        textStyle: { fontSize: 11 },
                        slantedText: true,
                        slantedTextAngle: 45,
                    },
                    vAxis: {
                        title: 'Clicks',
                        minValue: 0,
                        format: '0',
                        textStyle: { fontSize: 12 },
                    },
                    legend: { position: 'none' },
                    colors: ['#337ab7'],
                    bar: { groupWidth: '70%' },
                    chartArea: { left: 60, top: 50, right: 20, bottom: 80, width: '100%' },
                    animation: { startup: true, duration: 600, easing: 'out' },
                    tooltip: { isHtml: false },
                    backgroundColor: '#fff',
                },
            }
        },

        computed: {
            chart_data() {
                const rows = Object.entries(this.stats.last_30_days || {})
                    .map(([date, count]) => [date, count]);
                if (!rows.length) return [];
                return [['Date', 'Clicks'], ...rows];
            },
        },

        methods: {
            show_modal(id) {
                this.general_info_id = id;
                this.show_statistic_modal = true;
                this.load_stats();
            },

            close_modal() {
                this.show_statistic_modal = false;
                this.general_info_id = null;
                this.error = null;
                this.stats = { today: 0, this_week: 0, this_month: 0, this_year: 0, last_30_days: {} };
                this.$emit('update');
            },

            load_stats() {
                this.loading = true;
                this.error = null;
                axios.get('set_general_info/get_action_stats', {
                    params: { block_id: this.general_info_id, action_type: 'show_more_click' }
                })
                .then(res => { this.stats = res.data.data; })
                .catch(() => { this.error = 'Failed to load statistics.'; })
                .finally(() => { this.loading = false; });
            },
        },
    }
</script>

<style scoped>
.stat-tile {
    background: #fff;
    border: 2px solid #337ab7;
    border-radius: 8px;
    padding: 16px 12px;
    text-align: center;
    margin-bottom: 12px;
}
.stat-tile-label {
    font-size: 12px;
    text-transform: uppercase;
    color: #888;
    letter-spacing: 0.05em;
    margin-bottom: 6px;
}
.stat-tile-value {
    font-size: 32px;
    font-weight: 700;
    color: #337ab7;
    line-height: 1;
}
</style>
