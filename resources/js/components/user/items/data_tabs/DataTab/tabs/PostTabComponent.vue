<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.post.id}}</td>
        <td>|</td>
        <td>{{table_info.user.name}} </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_post(table_info.id)">Delete</button>
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
            del_post(id){
                if(confirm('Are you sure, you want delite it?')){
                    // axios
                    // .post('../../api/MTPPitch/'+id, {
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