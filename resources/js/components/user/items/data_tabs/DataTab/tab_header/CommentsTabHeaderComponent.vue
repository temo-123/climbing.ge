<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        
        <th>|</th>
        <th>Comment ID</th>

        <th v-if="table_name == 'Guidebook comments'">|</th>
        <th v-if="table_name == 'Guidebook comments'">Published</th>

        <th>|</th>
        <th>Article</th>
        
        <th v-if="table_name == 'Guidebook comments'">|</th>
        <th v-if="table_name == 'Guidebook comments'">Comententor</th>

        <th v-if="table_name == 'Guidebook comments'">|</th>
        <th v-if="table_name == 'Guidebook comments'">Comententor Email</th>

        <th v-if="table_name == 'Guidebook comments'">|</th>
        <th v-if="table_name == 'Guidebook comments'">Delite</th>

        <th v-if="table_name == 'Guidebook comments'">|</th>
        <th v-if="table_name == 'Guidebook comments'">Hide</th>
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