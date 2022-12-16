<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>

        <td @click="quick_wiev_action(table_info.id)" title="Click for quick wiev" style="cursor: zoom-in;">{{table_info.url_title}}</td>

        <td  v-if="table_name != 'Users' && table_name != 'Orders'">|</td>
        <td style='text-align: center;' v-if="table_name != 'Sector' && table_name != 'Users' && table_name != 'Orders'">{{table_info.published}}</td>

        <th style='text-align: center;' v-if="table_name == 'Users'">
            <!-- <div v-for="user_role in user_roles" :key="user_role.id" v-if='user_role.user_id == table_info.id'>
                <div v-if="table_name == 'Roles'">
                    <div v-for="table_info in table_2" :key="table_info.id" v-if='user_role.role_id == table_info.id'>
                        {{ table_info.name }}
                    </div>
                </div>
                <div v-else>
                    {{ user_role.role_id }}
                </div>
            </div> -->
        </th>
        
        <td>|</td>

        <td v-if="table_name == 'Users'">
            <button class="btn btn-primary" @click="show_parmission_edit_madel(table_info.id)">Edit roles</button>
        </td>
        <td v-if="table_name == 'Orders'">
            <button class="btn btn-primary" @click="show_order_status_edit_madel(table_info.id)">Edit Status</button>
        </td>
        <td v-else>
            <router-link class="btn btn-primary" :to="{ name: 'articleEdit', params: { id: table_info.id } }">Edit</router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_article(table_info.id)">Delete</button>
        </td>
    </tr>
</template>

<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
        },
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            del_article(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/article/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            quick_wiev_action(article_id){
                alert('it`s article quick view window ( article ID - '+article_id+')')
            }
        }
    }
</script>

<style>
</style>