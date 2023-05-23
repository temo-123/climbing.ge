<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        <th>|</th>
        <th>ID tab header</th>
        <th >
            |
        </th>

        <th>
            Status
        </th>

        <th >
            |
        </th>
        <th v-if="table_name == 'Orders'">
            Edit order status
        </th>
        <th v-else-if="table_name == 'My orders'">
            Show order status
        </th>
    </tr>
</template>

<script>
    export default {
        props: [
            'table_name',
        ],
    }
</script>

<style>

</style>