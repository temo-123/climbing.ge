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
                // sportChartData: [
                //     ['Gread', 'Quantyty'],
                //     ['4', 2],
                //     ['5a', 3],
                //     ['5b', 4],
                // ],

                boulderChartOptions: {
                    chart: {
                        title: 'Sport climbing route quantity',
                        description: 'Sport climbing route quantity in this area',
                        subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                    }
                },
                bolderChartData: [],
                // bolderChartData: [
                //     ['Gread', 'Quantyty'],
                //     ['V1', 2],
                //     ['V1+', 3],
                //     ['V2', 2],
                // ],
            };
        },
        mounted() {
            // console.log(this.outdoor_region_article_id);
            this.get_routes_quantiti()
        },
        
        methods: {
            get_routes_quantiti(){
                axios
                .get("../../api/get_routes_quantity/"+this.outdoor_region_article_id)
                .then(response => {
                    this.bolderChartData = response.data.bouldering
                    this.sportChartData = response.data.sport_routes
                })
                .catch(
                    error => console.log(error)
                );
            },
        }
    }
</script>

<style>

</style>