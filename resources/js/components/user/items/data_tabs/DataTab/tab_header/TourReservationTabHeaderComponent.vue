<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>

        <th>ID</th>

        <th>Persons</th>

        <th>Check In</th>

        <th>Name</th>

        <th>Email</th>

        <th>Verificate</th>

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