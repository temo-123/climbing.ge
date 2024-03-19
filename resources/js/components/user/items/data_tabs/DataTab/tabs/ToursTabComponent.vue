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
            quick_wiev_action(tour_id){
                alert('it`s tour quick view window ( tour ID - '+tour_id+')')
            }
        }
    }
</script>

<style>
</style>