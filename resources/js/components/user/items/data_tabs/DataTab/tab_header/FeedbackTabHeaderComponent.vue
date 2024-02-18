<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        <th>|</th>

        <th>Comment ID</th>
        <th>|</th>
        
        <th>Product</th>
        <th>|</th>

        <th>Comententor Email</th>
        <th>|</th>

        <th>Stars</th>
        <th>|</th>

        <th>Delite</th>

        <th v-if="table_name == 'Product feedbacks'">|</th>
        <th v-if="table_name == 'Product feedbacks'">Hide</th>
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