<template>
    <div class="col-md-12" >

        <h2>Week Orders</h2>
        <GChart
            type="LineChart"
            :data="week_orders_data"
            :options="week_orders_options"
        />

        <h2>Year Orders</h2>
        <GChart
            type="LineChart"
            :data="year_orders_data"
            :options="year_orders_options"
        />

    </div>
</template>

<script>
    import { GChart } from 'vue-google-charts'
    export default {
        components: {
            GChart,
        },
        data(){
            return{
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
                    title: 'Week Orders',
                    hAxis: {title: 'Day'},
                    vAxis: {title: 'Orders'},
                    legend: 'none',
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
                },
            }
        }
    }
</script>

<style>

</style>