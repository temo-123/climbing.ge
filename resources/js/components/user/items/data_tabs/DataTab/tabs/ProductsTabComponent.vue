<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.global_product.id}}</td>
        <td>|</td>
        <td>{{table_info.locale_product.title}} </td>
        <td>|</td>
        <td style='text-align: center;'>{{table_info.global_product.published}}</td>
        <td>|</td>
        <td>
            <a :href="'#'" class="btn btn-primary" type="submit">+</a>
        </td>
        <td>|</td>
        <td>
            <a :href="table_1_edit_url+table_info.id" class="btn btn-primary" type="submit">Edit</a>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_products(table_info.global_product.id)">Delete</button>
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
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            del_products(id){
            //     if(confirm('Are you sure, you want delite it?')){
            //         axios
            //         .post('../../api/MTPPitch/'+id, {
            //             id: id,
            //             _method: 'DELETE'
            //         })
            //         .then(Response => {
            //             this.update(this.tab_num)
            //         })
            //         .catch(error => console.log(error))
            //     }
            },
        }
    }
</script>

<style>

</style>