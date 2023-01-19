<template>
    <tr>
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>

        <td>{{table_info.url_title}}</td>

        <td>|</td>
        
        <td v-if="!table_info.public_totaly">{{table_info.published_data}}</td>
        <td v-else>Total</td>

        <td>|</td>
        <td>
            <router-link class="btn btn-primary" :to="{ name: 'localBisnesEdit', params: { id: table_info.id } }" ><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        
        <td>
            <button type="submit" class="btn btn-danger" @click="del_bisnes(table_info.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
            del_bisnes(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/bisnes/del_local_bisnes/'+id, {
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