<template>
    <tr>
                                    <td style='text-align: center;'>
                                        <input type="checkbox">
                                    </td>
                                    <td>|</td>
                                    <td>{{table_info.id}}</td>
                                    <td>|</td>
                                    <td>{{table_info.us_name}} </td>
                                    <td>|</td>
                                    <td>{{table_info.public}} </td>
                                    <td>|</td>
                                    <td>
                                        <a :href="table_1_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a>
                                    </td>
                                    
                                    <td>|</td>
                                    <td>
                                        <button type="submit" class="btn btn-danger" @click="del_topic(table_info.id)">Delete</button>
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
            del_topic(id){
                if(confirm('Are you sure, you want delite it?')){
                    // axios
                    // .post('../../api/MTPPitch/'+id, {
                    //     id: id,
                    //     _method: 'DELETE'
                    // })
                    // .then(Response => {
                        // this.$emit('restart')
                    // })
                    // .catch(error => console.log(error))
                }
            },
        }
    }
</script>

<style>

</style>