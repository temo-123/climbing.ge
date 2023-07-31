<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>
        <td>|</td>

        <td>{{table_info.name}}</td>

        <td>|</td>

        <td v-if="table_info.or_grade"> {{table_info.grade + ' / ' +  table_info.or_grade }} </td>
        <td v-else>{{table_info.grade}}</td>

        <td>|</td>
        <td>
            <router-link class="btn btn-primary" :to="{ name: 'iceRouteEdit', params: { id: table_info.id } }" v-if="$can('edit', 'ice_route')"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        
        <td>
            <button type="submit" class="btn btn-danger" @click="del_ice_route(table_info.id)" v-if="$can('del', 'ice_route')"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
            del_ice_route(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/ice_routes/del_route/'+id, {
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