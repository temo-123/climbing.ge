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
            <router-link class="btn btn-primary" :to="{ name: 'productOptionsControl', params: { product_id: table_info.global_product.id } }">-></router-link>
        </td>
        <td>|</td>
        <td>
            <router-link class="btn btn-primary" :to="{ name: 'productEdit', params: { id: table_info.global_product.id } }"><i class="fa fa-pencil" aria-hidden="true"></i></router-link>
        </td>
        
        <td>|</td>
        <td>
            <button type="button" class="btn btn-danger" @click="del_products(table_info.global_product.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
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
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/product/'+id, {
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