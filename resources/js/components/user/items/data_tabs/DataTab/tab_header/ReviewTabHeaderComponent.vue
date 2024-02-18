<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        <th>|</th>

        <th>Rewiew ID</th>
        <th>|</th>

        <th v-if="table_name == 'My climbing routes review' || table_name == 'Climbing routes review'">Climbing route</th>
        <th v-if="table_name == 'My products review' || table_name == 'Products review'">Product</th>
        <th>|</th>

        <th>Stars</th>
        <th>|</th>

        <th v-if="table_name == 'My climbing routes review' || table_name == 'My products review'">Edit</th>
        <th v-if="table_name == 'My climbing routes review' || table_name == 'My products review'">|</th>

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