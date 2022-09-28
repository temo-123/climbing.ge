<template>

                                <tr>
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>
                                    <td>{{table_info.us_name}} </td>
                                    <td>|</td>
                                    <td>
                                        <a :href="table_1_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_region(table_info.id)">Delete</button>
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
            del_region(id){
                if(confirm('Are you sure, you want delite it?')){
                    // axios
                    // .post('../../api/article/'+id, {
                    //     id: id,
                    //     _method: 'DELETE'
                    // })
                    // .then(Response => {
                    //     this.update(this.tab_num)
                    // })
                    // .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>