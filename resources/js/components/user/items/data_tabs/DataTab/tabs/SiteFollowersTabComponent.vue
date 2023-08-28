<template>
    <tr>
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>
        <td>{{table_info.email}} </td>
        <td>|</td>
        <td :style='"text-align: center;"'>{{table_info.service}}</td>
        
        <td>|</td>
        <td>
            <button type="button" class="btn btn-danger" @click="del_follower(table_info.id)" v-if="$can('del', 'site_folloers')"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
            del_follower(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/follow/del_follower/'+id, {
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