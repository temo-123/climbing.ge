<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>

        <td>{{table_info.name}} {{table_info.surname}}</td>

        <td>|</td>
        <td>user role</td>

        <!-- <td>{{table_info.email}}</td>
        <td>|</td> -->

        <!-- <th style='text-align: center;' v-if="data.table_name == 'Users'">
            <div v-for="user_role in user_roles" :key="user_role.id" v-if='user_role.user_id == table_info.id'>
                <div v-if="data.table_name == 'Roles'">
                    <div v-for="table_info in table_2" :key="table_info.id" v-if='user_role.role_id == table_info.id'>
                        {{ table_info.name }}
                    </div>
                </div>
                <div v-else>
                    {{ user_role.role_id }}
                </div>
            </div>
        </th> -->
        
        <td>|</td>

        <td>
            <button class="btn btn-primary" @click="show_parmission_edit_madel(table_info.id)">Edit roles</button>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_article(table_info.id)">Delete</button>
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