<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        <th>|</th>

        <th>Rewiew ID</th>
        <th>|</th>

        <th>Climbing route</th>
        <th>|</th>

        <th>Stars</th>
        <th>|</th>

        <th>Edit</th>

        <!-- <th v-if="table_name == 'Climbing routes review'">|</th> -->
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