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
            <!-- <a :href="'../../spot_category/edit/'+table_info.id" class="btn btn-primary" type="submit">Edit</a> -->
            <router-link class="btn btn-primary" :to="{ name: 'spot_category_edit', params: { id: table_info.id } }"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_region(table_info.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
                    axios
                    .post('../../api/outdoor/del_spot/'+id, {
                        id: id,
                        _method: 'delete'
                    })
                    .then(Response => {
                        // this.update(this.tab_num)

                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>