<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>

        <td @click="quick_wiev_action(table_info.id)" title="Click for quick wiev" style="cursor: zoom-in;">{{table_info.url_title}}</td>

        <td>|</td>
        <td>{{table_info.published}}</td>
        
        <td>|</td>

        <td>
            <router-link class="btn btn-primary" :to="{ name: 'tourEdit', params: { id: table_info.id } }" v-if="$can('edit', 'tour')"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_tour(table_info.id)" v-if="$can('del', 'tour')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
    </tr>
</template>

<script>
    // import change_user_modal from '../tab_modals/ChangeUserModalComponent.vue'
    export default {
        components: {
            // change_user_modal,
        },
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            // del_tour(id){
            //     if(confirm('Are you sure, you want delite it?')){
            //         axios
            //         .post('/tour/del_tour/'+id, {
            //             _method: 'DELETE'
            //         })
            //         .then(Response => {
            //             this.$emit('restart')
            //         })
            //         .catch(error => console.log(error))
            //     }
            // },
            // show_user_change_modal(){
            //     this.$refs.relation_modal.show_modal()
            // },
            // update_relation(user_id){
            //     if(confirm('Are you sure, you want delite it?')){
            //         axios
            //         .post('/tour/change_user_relation/' + this.table_info.id + '/' + user_id)
            //         .then(Response => {
            //             // this.$emit('restart')
            //         })
            //         .catch(error => console.log(error))
            //     }
            //     this.$refs.relation_modal.close_modal()

            // },
            // quick_wiev_action(tour_id){
            //     alert('it`s tour quick view window ( tour ID - '+tour_id+')')
            // }
        }
    }
</script>

<style>
</style>