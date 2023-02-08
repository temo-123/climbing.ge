<template>
    <tr>
        <th style="text-align: center">
            <input type="checkbox" class="all" />
        </th>
        <th>|</th>
        <th>ID</th>
         <th v-if="  data.table_name != 'Orders' &&
                    data.table_name != 'My orders'
        ">|</th>

        <th v-if="  data.table_name != 'Orders' &&
                    data.table_name != 'My orders'
        ">
            Name
        </th>

        <td
            v-if="
                data.table_name != 'Routes' &&
                data.table_name != 'Multi-pitchs' &&
                data.table_name != 'Regions' &&
                data.table_name != 'Pitches' &&
                data.table_name != 'Users' &&
                data.table_name != 'Categories' &&
                data.table_name != 'Orders' &&
                data.table_name != 'My orders' &&
                data.table_name != 'My comments' &&
                data.table_name != 'Comments' &&
                data.table_name != 'Parmissions'
            "
        >
            |
        </td>

        <th
            style="text-align: center"
            v-if="
                data.table_name != 'Routes' &&
                data.table_name != 'Multi-pitchs' &&
                data.table_name != 'Regions' &&
                data.table_name != 'Pitches' &&
                data.table_name != 'Users' &&
                data.table_name != 'Categories' &&
                data.table_name != 'Orders' &&
                data.table_name != 'My orders' &&
                data.table_name != 'Roles' &&
                data.table_name != 'My comments' &&
                data.table_name != 'Comments' &&
                data.table_name != 'Parmissions'
            "
        >
            Public
        </th>

        <td v-if="data.table_name == 'Roles'">
            Description
        </td>

        <!-- <td v-if="data.table_name == 'Users'">|</td>
        <th
            style="text-align: center"
            v-if="data.table_name == 'Users'"
        >
            Role
        </th> -->

        <!-- <th
            v-if="
                data.table_name == 'Routes' ||
                data.table_name == 'Pitches'
            "
        >
            |
        </th>
        <th
            style="text-align: center"
            v-if="
                data.table_name == 'Routes' ||
                data.table_name == 'Pitches'
            "
        >
            Grade
        </th> -->

        <!-- <th v-if="data.table_name == 'Users'">
            |
        </th> -->

        <th>
            |
        </th>

        <th v-if="data.table_name != 'Parmissions' &&
                data.table_name != 'My comments' &&
                data.table_name != 'Comments'&&
                data.table_name != 'My orders' &&
                data.table_name != 'Orders'
        ">
            Edit
        </th>
        <th v-if="data.table_name == 'Comments'
        ">
            User
        </th>

        <th v-if="data.table_name == 'Users'">
            |
        </th>
        <th v-if="data.table_name == 'Users'">
            Ban
        </th>

        <th v-if="data.table_name != 'Parmissions'&&
                data.table_name != 'My comments'&&
                data.table_name != 'My orders' &&
                data.table_name != 'Orders'
        ">
            |
        </th>

        <th v-if="data.table_name != 'Parmissions'&&
                data.table_name != 'My orders' &&
                data.table_name != 'Orders'
        ">
            Delite
        </th>

        <!-- <th v-if="data.table_name == 'My orders' ||
                data.table_name == 'Orders'
        ">
            Status
        </th>

        <th v-if="data.table_name == 'Orders' ||
                data.table_name == 'My orders'">
            |
        </th> -->

        <!-- <th v-if="data.table_name == 'Orders' && location == 'all_orders'">
            Edit order status
        </th>
        <th v-else-if="data.table_name == 'My orders' && location == 'my_orders'">
            Show order status
        </th> -->
    </tr>
</template>

<script>
    export default {
        props: [
            "data"
        ],
        data(){
            return{
                location: ''
            }
        },
        mounted(){
            var path = window.location.pathname;
            this.location = path.split("/").pop();
        }
    }
</script>

<style>

</style>