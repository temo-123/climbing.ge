<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>

        <td>{{table_info.name}}</td>
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
            // del_route(id){
            //     if(confirm('Are you sure, you want delite it?')){
            //         axios
            //         .post('../../api/route/'+id, {
            //             id: id,
            //             _method: 'DELETE'
            //         })
            //         .then(Response => {
            //             this.update(this.tab_num)
            //         })
            //         .catch(error => console.log(error))
            //     }
            // },
        }
    }
</script>

<style>

</style>