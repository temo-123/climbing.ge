<template>
    <tr :class="action_color">
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
            this.end_day = moment(this.event.global_event.end_data).format("D")
            this.end_month = moment(this.event.global_event.end_data).format("MMM")
        },
        data(){
            return {
                action_color: '',

                end_day: 0,
                end_month: 0
            }
        },
        watch: {
            table_info: function(){
                this.row_action()
            },
        },
        methods: {
            row_action(){
                if( new Date().getDate() >= this.end_day && new Date().getMonth() >= this.end_month){
                    this.table_info.end_data
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