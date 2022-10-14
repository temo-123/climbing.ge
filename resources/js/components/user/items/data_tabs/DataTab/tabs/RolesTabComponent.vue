<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>

        <td>{{table_info.name}}</td>

        <td>|</td>
        
        <td>{{table_info.description}}</td>

        <td>|</td>
        <td>
            <router-link class="btn btn-primary" :to="{ name: 'routeEdit', params: { id: table_info.id } }" >Edit</router-link>
        </td>
        
        <td>|</td>
        
        <td>
            <button type="submit" class="btn btn-danger" @click="del_route(table_info.id)">Delete</button>
        </td>
    </tr>
</template>

<script>
    export default {
        components: {
            // StackModal,
        },
        props: [
            'table_info',
        ],
    }
</script>

<style>

</style>