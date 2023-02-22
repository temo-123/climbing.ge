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
        
        <td>|</td>

        <td>
            <router-link class="btn btn-primary" :to="{ name: 'articleEdit', params: { id: table_info.id } }" v-if="$can('edit', 'article')"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_article(table_info.id)" v-if="$can('del', 'article')"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
                    .post('../../api/article/del_article/'+id, {
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