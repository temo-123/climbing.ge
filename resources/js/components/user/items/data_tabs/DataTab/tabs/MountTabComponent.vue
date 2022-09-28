<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.global_mount.id}}</td>
        <td>|</td>
        
        <td>{{table_info.locale_mount.title}} </td>

        <td >|</td>

        <td style='text-align: center;' >{{table_info.global_mount.published}}</td>
        
        <td>|</td>

        <td>
            <a class="btn btn-primary" type="submit">Edit</a>
        </td>
        
        <td>|</td>
        <td>
            <a class="btn btn-danger" type="submit">Delete</a>
        </td>
    </tr>
</template>

<script>
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