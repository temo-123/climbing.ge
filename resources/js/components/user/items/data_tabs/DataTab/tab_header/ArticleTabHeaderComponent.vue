<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        <th>|</th>

        <th>ID</th>
        <th>|</th>

        <th>Title</th>
        <th>|</th>

        <th>Public</th>

        <th v-if="table_name == 'outdoor'">|</th>
        <th v-if="table_name == 'outdoor'">
            Sectors
        </th>

        <th>|</th>

        <th>Edit</th>
        <th>|</th>

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