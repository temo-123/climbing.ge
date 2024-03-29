<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info[0].id}}</td>
        <td>|</td>

        <td @click="quick_wiev_action(table_info[0].id)" title="Click for quick wiev" style="cursor: zoom-in;">{{table_info[0].url_title}}</td>

        <td>|</td>
        <td>{{table_info[0].published}}</td>
        
        <td>|</td>

        <td>
            <button class="btn btn-primary" @click="show_user_change_modal()"><i class="fa fa-user" aria-hidden="true"></i></button>
        </td>
        <td>|</td>

        <td>
            <router-link class="btn btn-primary" :to="{ name: 'tourEdit', params: { id: table_info[0].id } }" v-if="$can('edit', 'tour')"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_tour(table_info[0].id)" v-if="$can('del', 'tour')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>

        <change_user_modal
            :active_user_prop="table_info['user']"
            ref="user_relation_modal"
            @update_relation="update_relation"
        />
    </tr>
</template>

<script>
    import change_user_modal from '../tab_modals/ChangeUserModalComponent.vue'
    export default {
        components: {
            change_user_modal,
        },
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            del_tour(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/tour/del_tour/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            show_user_change_modal(){
                this.$refs.user_relation_modal.show_modal()
            },
            update_relation(user_id){
                if(confirm('Are you sure, you want change user?')){
                    let data
                    if(this.table_info['user'].length != 0){
                        data = {
                            "tour_id": this.table_info[0].id,
                            "new_user_id": user_id,
                            "old_user_id": this.table_info['user'][0].id
                        }
                    }
                    else{
                        data = {
                            "tour_id": this.table_info[0].id,
                            "new_user_id": user_id,
                            "old_user_id": []
                        }
                    }
                    axios
                    .post('/tour/change_user_relation/',{
                        data: data
                    })
                    .then(Response => {
                        this.$emit('restart')
                        this.$refs.user_relation_modal.close_modal()
                    })
                    .catch(error => console.log(error))
                }

            },
            quick_wiev_action(tour_id){
                alert('it`s tour quick view window ( tour ID - '+tour_id+')')
            }
        }
    }
</script>

<style>
</style>