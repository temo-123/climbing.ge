<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        <th>|</th>

        <th>ID</th>
        <th>|</th>

        <th>Email</th>
        <th>|</th>

        <th>Confirmed</th>
        <th>|</th>

        <th>Article comment count</th>
        <th>|</th>

        <th>Product comment count</th>
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