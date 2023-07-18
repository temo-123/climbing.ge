<template>
    <tr :class='row_action(table_info.end_data)'>
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
        <td v-if="table_info.category == 'competition'">
            <router-link class="btn btn-primary" :to="{ name: 'competitionEdit', params: { id: table_info.id } }" v-if="$can('edit', 'event')"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        <td v-else-if="table_info.category == 'event'">
            <router-link class="btn btn-primary" :to="{ name: 'eventEdit', params: { id: table_info.id } }" v-if="$can('edit', 'event')"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="button" class="btn btn-danger" @click="del_event(table_info.id)" v-if="$can('del', 'event')"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
            //
        },
        data(){
            return {
                // 
            }
        },
        methods: {
            row_action(end_data){
                let end_houre = Number(moment(end_data).format("H"))

                if(end_houre != 0){
                    let end_day = Number(moment(end_data).format("D"))
                    let end_month = Number(moment(end_data).format("MM"))
                    let end_year = Number(moment(end_data).format("YYYY"))

                    // console.log('====================================');
                    // console.log(new Date().getDate() == end_day);
                    // console.log(new Date().getMonth() >= end_month);
                    // console.log(new Date().getFullYear() >= end_year);
                    // console.log('====================================');

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
                }
            },
            del_event(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/event/del_event/'+id, {
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