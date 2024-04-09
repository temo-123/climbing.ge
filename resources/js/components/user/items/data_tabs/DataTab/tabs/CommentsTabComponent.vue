<template>
    <tr :class="danger_color">
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>

        <td @click="get_comment(table_info.comment.id, 'show')" class="cursor_zoom_in">{{table_info.comment.id}}</td>
        <td>|</td>

        <td v-if="comments_tab_name == 'Guidebook comments'" >{{table_info.comment.published}}</td>
        <td v-if="comments_tab_name == 'Guidebook comments'" >|</td>

        <td @click="go_to_article_page(table_info.global_article.url_title, table_info.global_article.category)" class="cursor_pointer">{{table_info.global_article.url_title}}</td>
        <td v-if="comments_tab_name == 'Guidebook comments'" >|</td>

        <td v-if="comments_tab_name == 'Guidebook comments'" @click="get_comment(table_info.comment.id, 'show')" :style="'cursor: zoom-in'">{{table_info.comment.name}} {{ table_info.comment.surname }}</td>
        <td v-if="comments_tab_name == 'Guidebook comments'">|</td>

        <td v-if="comments_tab_name == 'Guidebook comments'">{{table_info.comment.email}}</td>
        <td v-if="comments_tab_name == 'Guidebook comments'">|</td>

        <td v-if="comments_tab_name == 'Guidebook comments'">
            <button type="submit" class="btn btn-danger" @click="del_comment(table_info.comment.id)">Delete</button>
        </td>

        <td v-if="comments_tab_name == 'Guidebook comments'">|</td>
        <td v-if="comments_tab_name == 'Guidebook comments'">
            <button type="submit" class="btn btn-warning" @click="get_comment(table_info.comment.id, 'hide')">Hide</button>
        </td>

        <comment_show_modal ref="show_comment_modal" @restart="reset"/>
        <comment_hide_modal ref="hide_comment_modal" @restart="reset"/>
    </tr>
</template>

<script>
    import comment_show_modal from '../../../modal/comments/CommentShowModal.vue'
    import comment_hide_modal from '../../../modal/comments/CommentHideModal.vue'
    export default {
        components: {
            comment_show_modal,
            comment_hide_modal,
        },
        props: [
            'table_info',
            'comments_tab_name'
        ],
        data(){
            return {
                is_coment_model: false,
                is_user_comment_delite_model: false,
                comment_delete_cause: '',
                quick_comment: [],

                user_comment_id: 0,

                danger_color: '',

                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            }
        },
        mounted(){
            if(this.table_info.comment.deleted_reason != null){
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
            del_comment(id){
                if(confirm('Are you sure, you want delite this comment from page content?')){
                    axios
                    .delete('/guide_comment/del_comment/'+id, {
                        _method: 'delete'
                    })
                    .then(Response => {
                        this.is_user_comment_delite_model = false
                        this.reset()
                    })
                    .catch(error => console.log(error))
                }
            },
            go_to_article_page(url_title, category){
                window.open(this.MIX_APP_SSH + this.MIX_SITE_URL + '/' + category + '/' + url_title)
            },
            get_comment(comment_id, action){
                this.quick_comment = []

                axios
                .get("/guide_comment/get_actyve_comment/"+comment_id)
                .then(response => {
                    if(action == 'show'){
                        this.$refs.show_comment_modal.show_modal(response.data)
                    }
                    else if(action == 'hide'){
                        this.$refs.hide_comment_modal.show_modal('guide_comment/hide_comment/', response.data)
                    }
                })
                .catch(
                    error => console.log(error)
                );
            },
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