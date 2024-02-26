<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        
        <th>|</th>
        <th>Comment ID</th>

        <th v-if="table_name == 'Product feedbacks'">|</th>
        <th v-if="table_name == 'Product feedbacks'">Published</th>

        <th>|</th>
        <th>Product</th>
        
        <th v-if="table_name == 'Product feedbacks'">|</th>
        <th v-if="table_name == 'Product feedbacks'">Comententor</th>

        <th>|</th>
        <th>Stars</th>

        <th v-if="table_name == 'Product feedbacks'">|</th>
        <th v-if="table_name == 'Product feedbacks'">Comententor Email</th>

        <th v-if="table_name == 'Product feedbacks'">|</th>
        <th v-if="table_name == 'Product feedbacks'">Delite</th>

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