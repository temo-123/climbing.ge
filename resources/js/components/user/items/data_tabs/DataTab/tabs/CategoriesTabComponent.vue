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
        <td>
            <!-- <a :href="table_1_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a> -->

            <router-link :to="{name: 'productCategoryEdit', params: { id: table_info.id } }"> 
                <button class="btn btn-primary " ><i class="fa fa-pencil" aria-hidden="true"></i></button>
            </router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_categories(table_info.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
            del_categories(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/product_category/'+id, {
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