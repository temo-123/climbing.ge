<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        <th>|</th>

        <th>User ID</th>
        <th>|</th>

        <th v-if="table_name == 'My climbing routes review' || table_name == 'Climbing routes review'">Product stars</th>
        <th v-if="table_name == 'My climbing routes review' || table_name == 'Climbing routes review'">|</th>

        <th v-if="table_name == 'My climbing routes review' || table_name == 'Climbing routes review'">Route stars</th>
        <th v-if="table_name == 'My climbing routes review' || table_name == 'Climbing routes review'">|</th>

        <th>Delite</th>
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