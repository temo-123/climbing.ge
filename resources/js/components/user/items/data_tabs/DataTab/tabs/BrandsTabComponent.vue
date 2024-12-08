<template>
    <tr>
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>

        <td>|</td>
        <td>{{ table_info.us_brand.title }}</td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-primary" @click="open_modal(table_info.id)" v-if="$can('edit', 'product_brand')"><i class="fa fa-pencil" aria-hidden="true"></i></button>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_product_brand(table_info.id)" v-if="$can('del', 'product_brand')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>

        <EditProductBrandModal ref="add_product_brand" @restart="update"/>
    </tr>
</template>

<script>
    import { update } from "lodash";
import EditProductBrandModal from "../tab_modals/edit/EditProductBrandModal.vue";

    export default {
        components: {
            EditProductBrandModal
        },
        data(){
            return {
                is_tag_control_modal: false,

            }
        },
        props: [
            'table_info',
        ],
        mounted(){
            // 
        },
        methods: {
            del_product_brand(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/brand/del_brand/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },

            open_modal(id){
                this.$refs.add_product_brand.open_modal(id)
            },

            update(){
                this.$emit('restart')
            }
        }
    }
</script>

<style>
    .row_deanger td{
        background-color: #df8d8d;
    }
    .row_worning td {
        background-color: #dfad8d;
    }
</style>