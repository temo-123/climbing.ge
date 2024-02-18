<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        <th>|</th>

        <th>Comment ID</th>
        <th>|</th>
        
        <th v-if="table_name == 'Products comments' || table_name == 'Guide comments'">Comententor</th>
        <th v-if="table_name == 'Products comments' || table_name == 'Guide comments'">|</th>

        <th v-if="table_name == 'Products comments' || table_name == 'Guide comments'">Comententor Email</th>
        <th v-if="table_name == 'Products comments' || table_name == 'Guide comments'">|</th>

        <th>Delite</th>

        <th v-if="table_name == 'Products comments' || table_name == 'Guide comments'">|</th>
        <th v-if="table_name == 'Products comments' || table_name == 'Guide comments'">Hide</th>
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