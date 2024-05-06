<template>
    <tr>
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.global_data.id}}</td>
        <td>|</td>
        <td>{{table_info.global_data.name}} </td>
        <td>|</td>
        <td>
            <!-- <a :href="'../../spot_category/edit/'+table_info.id" class="btn btn-primary" type="submit">Edit</a> -->
            <router-link class="btn btn-primary" :to="{ name: 'mount_massive_edit', params: { id: table_info.global_data.id } }" v-if="$can('del', 'mount_massive')"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_mount( table_info.global_data.id )" v-if="$can('del', 'mount_massive')"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
            del_mount(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/mount/mount/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
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