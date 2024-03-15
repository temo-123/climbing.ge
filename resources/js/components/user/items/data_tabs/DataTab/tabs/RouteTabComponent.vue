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
            <router-link class="btn btn-primary" :to="{ name: 'routeEdit', params: { id: table_info.id } }" v-if="$can('edit', 'route')"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        
        <td>
            <button type="submit" class="btn btn-danger" @click="del_route(table_info.id)" v-if="$can('del', 'route')"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
            del_route(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/route/del_route/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        // this.update(this.tab_num)

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