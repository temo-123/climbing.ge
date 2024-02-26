<template>
    <tr>
        <td style='text-align: center;'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.comenter.id}}</td>
        <td>|</td>

        <td>{{table_info.comenter.email}}</td>

        <td>|</td>
        <td>{{table_info.comenter.confirmed}}</td>

        <td>|</td>
        <td>{{table_info.article_comments}}</td>

        <td>|</td>
        <td>{{table_info.product_comments}}</td>
        
        <td>|</td>
        <td>
            <button type="submit" class="btn btn-danger" @click="del_article(table_info.id)" v-if="$can('del', 'article')"><i class="fa fa-trash" aria-hidden="true"></i></button>
        </td>
    </tr>
</template>

<script>
    export default {
        components: {
            // StackModal,
        },
        props: [
            'table_info',
        ],
        mountid(){
            // console.log(this.table_info)
        },
        methods: {
            del_article(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .delete("non_registered_commenter/del_non_registered_commenter/"+id)
                    .then(response => {;
                        this.$emit('restart')
                    })
                    .catch(
                        error => console.log(error)
                    );
                    
                }
            },
        }
    }
</script>

<style>
</style>