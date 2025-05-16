<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>

        <td>{{table_info.id}}</td>

        <td @click="quick_wiev_action(table_info.id)" title="Click for quick wiev" style="cursor: zoom-in;">{{table_info.persons}}</td>

        <td>{{table_info.check_in}}</td>

        <td>{{table_info.name}}</td>
        
        <td>{{table_info.email}}</td>

        <td>{{table_info.verificate}}</td>
        
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
            del_tour(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/tour/reservation/del_reservation/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            quick_wiev_action(tour_id){
                alert('it`s tour resrvation quick view window ( tour ID - '+tour_id+') comming sun')
            }
        }
    }
</script>

<style>
</style>