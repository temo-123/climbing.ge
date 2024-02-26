<template>
    <tr :class="danger_color">
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>

        <td @click="get_action_feedback(table_info.feedback.id, 'show')" class="cursor_zoom_in">{{table_info.feedback.id}}</td>
        <td>|</td>

        <td v-if="feedbacks_tab_name == 'Product feedbacks'" >{{table_info.global_product.published}}</td>
        <td v-if="feedbacks_tab_name == 'Product feedbacks'" >|</td>

        <td @click="go_to_product_page(table_info.global_product.url_title)" class="cursor_pointer">{{table_info.locale_product.title}}</td>

        <td v-if="feedbacks_tab_name == 'Product feedbacks'" >|</td>
        <td v-if="feedbacks_tab_name == 'Product feedbacks'" @click="get_action_feedback(table_info.feedback.id, 'show')" :style="'cursor: zoom-in'">{{table_info.feedback.name}} {{ table_info.feedback.surname }}</td>
        
        <td>|</td>
        <td>{{table_info.feedback.stars }}</td>

        <td v-if="feedbacks_tab_name == 'Product feedbacks'">|</td>

        <td v-if="feedbacks_tab_name == 'Product feedbacks'">{{table_info.feedback.email}}</td>
        <td v-if="feedbacks_tab_name == 'Product feedbacks'">|</td>

        <td v-if="feedbacks_tab_name == 'Product feedbacks'">
            <button type="submit" class="btn btn-danger" @click="del_feedback(table_info.feedback.id)">Delete</button>
        </td>

        <td v-if="feedbacks_tab_name == 'Product feedbacks'">|</td>
        <td v-if="feedbacks_tab_name == 'Product feedbacks'">
            <button type="submit" class="btn btn-warning" @click="get_action_feedback(table_info.feedback.id, 'hide')">Hide</button>
        </td>

        <comment_show_modal ref="show_comment_modal" @restart="reset"/>
        <comment_hide_modal ref="hide_comment_modal" @restart="reset"/>
    </tr>
</template>

<script>
    import starsReiting from '../../../../../global_components/StarReitingShowComponent.vue'
    import comment_show_modal from '../../../modal/comments/CommentShowModal.vue'
    import comment_hide_modal from '../../../modal/comments/CommentHideModal.vue'
    
    export default {
        components: {
            starsReiting,
            comment_hide_modal,
            comment_show_modal
        },
        props: [
            'table_info',
            'feedbacks_tab_name'
        ],
        data(){
            return {
                is_coment_model: false,
                is_user_feedback_delite_model: false,
                feedback_delete_cause: '',
                quick_feedback: [],

                user_feedback_id: 0,

                danger_color: '',

                MIX_SHOP_URL: process.env.MIX_SHOP_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            }
        },
        mounted(){
            // console.log(table_info)
            if(this.table_info.feedback.deleted_reason != null){
                this.danger_color = 'row_deanger'
            }
            else{
                this.danger_color = ''
            }
        },
        methods: {
            reset(){
                this.$emit('restart')
            },
            del_feedback(id){
                if(confirm('Are you sure, you want delite this comment from page content?')){
                    axios
                    .delete('/product_feedback/del_feedback/'+id, {
                        _method: 'delete'
                    })
                    .then(Response => {
                        this.is_user_comment_delite_model = false
                        this.$emit('restart')
                    })
                    .catch(error => console.log(error))
                }
            },
            go_to_product_page(url_title){
                window.open(this.MIX_APP_SSH + this.MIX_SHOP_URL + '/product/' + url_title)
            },
            get_action_feedback(feedback_id, action){
                this.quick_feedback = []

                axios
                .get("/product_feedback/get_actyve_feedback/"+feedback_id)
                .then(response => {
                    if(action == 'show'){
                        this.$refs.show_comment_modal.show_modal(response.data)
                    }
                    else if(action == 'hide'){
                        this.$refs.hide_comment_modal.show_modal('product_feedback/hide_feedback/', response.data)
                    }
                })
                .catch(
                    error => console.log(error)
                );
            },
            show_hide_comment(id){
                this.$refs.hide_comment_modal.show_modal('product_feedback/hide_feedback', id)
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