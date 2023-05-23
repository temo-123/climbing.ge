<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>
        <td>{{table_info.title}} </td>
        <td>|</td>
        <td>
            <router-link :to="{name: 'sectorLocalImagesListEdit', params: { id: table_info.id } }" v-if="$can('edit', 'sector_local_images')"> 
                <button class="btn btn-primary " ><i class="fa fa-pencil" aria-hidden="true"></i></button>
            </router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_image(table_info.id)" v-if="$can('del', 'sector_local_images')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
    </tr>
</template>

<script>
    export default {
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            del_image(id){
                if(confirm('Are you sure, you want delite itt?')){
                    axios
                    .post('../../api/sector_local_images/'+id, {
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