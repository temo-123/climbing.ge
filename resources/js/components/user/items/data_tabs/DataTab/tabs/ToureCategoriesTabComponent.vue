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
        <td @click="show_edit_model(table_info.id)">
            <button class="btn btn-primary " ><i class="fa fa-pencil" aria-hidden="true"></i></button>
        </td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_categories(table_info.id)"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>

        <editToureCategoryModal 
            ref="show_toure_category_edit_modal"
            @restart="editing_update"
        />
    </tr>
</template>

<script>
    import editToureCategoryModal from "../tab_modals/edit/EditToureCategoryModal"
    export default {
        components:{
            editToureCategoryModal,
        },
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            show_edit_model(category_id){
                this.$refs.show_toure_category_edit_modal.open_modal(category_id)
            },
            editing_update(){
                this.$emit('restart')
            },
            del_categories(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('/toure/category/del_category/'+id, {
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