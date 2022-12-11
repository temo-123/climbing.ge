<template>
    <tr>
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>
        <td>{{table_info.url_title}} </td>
        <td>|</td>
        <td :style='"text-align: center;"'>{{table_info.published}}</td>
        <td>|</td>
        <td>
            <router-link class="btn btn-primary" :to="{ name: 'serviceEdit', params: { id: table_info.id } }">Edit</router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="button" class="btn btn-danger" @click="del_service(table_info.id)">Delete</button>
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
            del_service(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/service/del_service/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.update(this.tab_num)
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>