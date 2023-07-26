<template>
    <tr :class='row_action(table_info.published_data)'>
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
            <router-link class="btn btn-primary" :to="{ name: 'localBisnesEdit', params: { id: table_info.id } }" v-if="$can('edit', 'local_bisnes')"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        
        <td>
            <button type="submit" class="btn btn-danger" @click="del_bisnes(table_info.id)" v-if="$can('del', 'local_bisnes')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
    </tr>
</template>

<script>
    import moment from "moment"; // https://www.npmjs.com/package/vue-moment
    export default {
        components: {
            moment
        },
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            row_action(data){
                    let end_day = Number(moment(data).format("D"))
                    let end_month = Number(moment(data).format("MM"))
                    let end_year = Number(moment(data).format("YYYY"))

                    if( new Date().getDate() > end_day && 
                        new Date().getMonth() >= end_month && 
                        new Date().getFullYear() >= end_year
                    ){
                        return 'completed_event'
                    }
                    if( new Date().getDate() > end_day && 
                        new Date().getMonth() == end_month && 
                        new Date().getFullYear() == end_year
                    ){
                        return 'completed_event'
                    }
                    else if( 
                        new Date().getDate() == end_day && 
                        new Date().getMonth() >= end_month && 
                        new Date().getFullYear() >= end_year
                    ){
                        return 'completed_event'
                    }
                    else if(
                        new Date().getMonth() > end_month && 
                        new Date().getFullYear() > end_year
                    ){
                        return 'completed_event'
                    }
                    else if(
                        new Date().getMonth() > end_month
                    ){
                        return 'completed_event'
                    }
                    else if(
                        new Date().getFullYear() > end_year
                    ){
                        return 'completed_event'
                    }
            },
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
.completed_event td {
    background-color: #fb9b9b;
}
</style>