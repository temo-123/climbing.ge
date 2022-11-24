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
            <!-- <a :href="table_1_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a> -->

            <router-link :to="{name: 'sectorLocalImagesListEdit', params: { id: table_info.id } }"> 
                <button class="btn btn-primary " >Edit</button>
            </router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_image(table_info.id)">Delete</button>
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
                        // this.update(this.tab_num)
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>