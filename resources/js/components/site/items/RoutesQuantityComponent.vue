<template>
    <div class="col-md-12">
        <div class="row" v-if="sportChartData.length > 1">
            <GChart
                type="ColumnChart"
                :data="sportChartData"
                :options="sportChartOptions"

                :resizeDebounce = "100"
            />
        </div>
        <div class="row" v-if="bolderChartData.length > 1">
            <GChart
                type="ColumnChart"
                :data="bolderChartData"
                :options="boulderChartOptions"

                :resizeDebounce = "100"
            />
        </div>
    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'
    export default {
        props: [
            'outdoor_region_article_id'
        ],
        components: {
            GChart
        },
        data: function () {
            return {
                sportChartOptions: {
                    chart: {
                        title: 'Bouldering route quantity',
                        description: 'Bouldering route quantity in this climbing area',
                        // subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                    }
                },

                sportChartData: [],

                boulderChartOptions: {
                    chart: {
                        title: 'Sport climbing route quantity',
                        description: 'Sport climbing route quantity in this area',
                        subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                    }
                },
                bolderChartData: [],

                id: this.outdoor_region_article_id
            };
        },
        mounted() {
            // console.log(this.outdoor_region_article_id);
            this.get_routes_quantiti()
        },
        watch: {
            '$route' (to, from) {
                this.get_routes_quantiti(),
                window.scrollTo(0,0)
            }
        },
        methods: {
            get_routes_quantiti(){
                this.bolderChartData = []
                this.sportChartData = []
                axios
                .get("../../api/get_routes_quantity/"+this.id)
                .then(response => {
                    this.bolderChartData = response.data.bouldering
                    this.sportChartData = response.data.sport_routes
                })
                .catch(
                    error => console.log(error)
                );
            },

            update(id){
                this.id = id
                this.get_routes_quantiti()
            }
        }
    }
</script>

<style>

</style>