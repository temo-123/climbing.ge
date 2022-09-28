<template>
    <tr>
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>


                                    <td v-if="data.table_name == 'Multi-pitchs'">
                                        <a @click="show_mtp_model(table_info.id)" href="#">{{table_info.name}}</a>
                                    </td>
                                    <td v-else>{{table_info.title}} </td>

                                    <td v-if="data.table_name != 'Multi-pitchs'">|</td>
                                    <td style='text-align: center;' v-if="data.table_name != 'Multi-pitchs'">{{table_info.published}}</td>
                                    
                                    <td>|</td>
                                    <td>
                                        <router-link class="btn btn-primary" :to="{ name: 'MTPEdit', params: { id: table_info.id } }" >Edit</router-link>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_multi_pitch(table_info.id)">Delete</button>
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
            // 'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            del_multi_pitch(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/MTP/'+id, {
                        id: id,
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.update(this.tab_num)
                    })
                    .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>