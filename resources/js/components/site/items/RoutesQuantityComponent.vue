<template>
    <GChart
        type="ColumnChart"
        :data="chartData"
        :options="chartOptions"
    />
</template>

<script>
    import { GChart } from 'vue-google-charts'
    export default {
        components: {
            GChart
        },
        data: function () {
            return {
                outdoors: [],

                // chartData: [],
                chartData: [
                    ['Gread', 'Quantyty'],
                    ['4', 2],
                    ['5a', 3],
                    ['5b', 4],
                    ['5c', 5],
                    ['6a', 2],
                    ['6a+', 2],
                    ['6b', 4],
                    ['6b+', 4],
                    ['6c', 5],
                    ['6c+', 5],
                    ['7a', 3],
                    ['7a+', 3],
                    ['7b', 4],
                    ['7b+', 4],
                    ['7c', 5],
                    ['7c+', 5],
                    ['8a', 2],
                    ['8a+', 2],
                    ['8b', 4],
                    ['8b+', 4],
                    ['8c', 5],
                    ['8c+', 5],
                    ['9a', 3],
                    ['9a+', 3],
                    ['9b', 4],
                    ['9b+', 4],
                    ['9c', 5],
                    ['9c+', 5],
                ],
                chartOptions: {
                    chart: {
                        title: 'Company Performance',
                        // subtitle: 'Sales, Expenses, and Profit: 2014-2017',
                    }
                }
            };
        },
        mounted() {
            // 
        },
        methods: {
            get_routes_quantiti(){
                // if (4) {
                    
                // }
            }
        }
    }
</script>

<style>

</style>