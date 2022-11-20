<template>
    <tr :class="danger_color">
        <td :style='"text-align: center;"'>
            <input type="checkbox">
        </td>
        <td>|</td>
        <td>{{table_info.id}}</td>

        <td>|</td>
        <td @click="quick_wiev_action(table_info.id)" :style="'cursor: zoom-in'"><!-- {{table_info.article_id}} --> (show comment)</td>

        <!-- <td v-if="comments_tab_name == 'Comments'">|</td> -->
        <!-- <td v-if="comments_tab_name == 'Comments' && table_info.user_id == null">Gest</td>
        <td v-if="comments_tab_name == 'Comments' && table_info.user_id != null">{{table_info.user_id}}</td> -->
        
        <td>|</td>
        <!-- <td v-if="comments_tab_name == 'My comments'">
            <button type="submit" class="btn btn-danger" @click="del_my_comment(table_info.id)">Delete</button>
        </td> -->
        <td>
            <button type="submit" class="btn btn-danger" @click="del_my_comment(table_info.id)">Totaly delete</button>
        </td>

        <td v-if="comments_tab_name == 'Comments'">|</td>

        <td v-if="comments_tab_name == 'Comments'">
            <!-- <button v-if="table_info.comment_deleted_reason != null" type="submit" class="btn btn-danger" @click="user_coment_del_modal(table_info.id)" disabled>Delete</button> -->
            <button type="submit" class="btn btn-warning" @click="user_coment_del_modal(table_info.id)">Delete from content</button>
        </td>

        <stack-modal
                :show="is_coment_model"
                title="Show coment"
                @close="is_coment_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <h1>Show Comment</h1>

                <span v-if="comments_tab_name == 'Comments'">
                    Comentator - {{ quick_comment.name }} {{ quick_comment.suenmae }}
                    Email - {{ quick_comment.email }}
                    Data to comment - {{ quick_comment.created_at }}
                </span>

                {{ quick_comment.text }}


                <span v-if="table_info.comment_deleted_reason != null">
                    <div class="alert alert-danger" role="alert">
                        This comment was deleted!!!
                        <strong>Reason for deletion </strong>
                        We have calculated that your comment is - {{ table_info.comment_deleted_reason }}
                    </div>
                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer" v-if="comments_tab_name == 'My comments' && table_info.comment_deleted_reason == null">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_coment(editing_image.id)"
                    >
                    Save
                    </button>
                </div>
            </div>
        </stack-modal>

        <stack-modal
                :show="is_user_comment_delite_model"
                title="Please select a reason for deleting the comment"
                @close="is_user_comment_delite_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <span v-if="comments_tab_name == 'Comments'">
                    Comentator - {{ quick_comment.name }} {{ quick_comment.suenmae }}
                    Email - {{ quick_comment.email }}
                    Data to comment - {{ quick_comment.created_at }}
                </span>

                {{ quick_comment.text }}

                <p :style="'font-size: 200%;'">Please select a reason for deleting the comment!!!</p>
                
                <select class="form-control" v-model="comment_delete_cause" name="comment delete cause" > 
                    <option value="Hostile remarks">Hostile remarks</option>
                    <option value="Does not match the theme of the site">Does not match the theme of the site</option>
                    <option value="Spam">Spam</option>
                    <option value="Sexual content">Sexual content</option>
                    <option value="Expression of anger">Expression of anger</option>
                    <option value="Conflict with other members of the site">Conflict with other members of the site</option>
                    <option value="The language of the comments does not match the requirements of the site">The language of the comments does not match the requirements of the site</option>
                </select> 
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="del_user_comment(comment_delete_cause, quick_comment.id)"
                    >
                    Delete this comment
                    </button>
                </div>
            </div>
        </stack-modal>
    </tr>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    export default {
        components: {
            StackModal,
            SlickItem,
            SlickList,
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

                danger_color: ''
            }
        },
        mounted(){
            // console.log(table_info)
            if(this.table_info.deleted_reason != null){
                this.danger_color = 'row_deanger'
            }
            else{
                this.danger_color = ''
            }
        },
        methods: {
            del_my_comment(id){
                if(confirm('Are you sure, you want delite it?')){
                    axios
                    .post('../../api/del_my_comment/'+id, {
                        _method: 'DELETE'
                    })
                    .then(Response => {
                    })
                    .catch(error => console.log(error))
                }
            },
            del_user_comment(cause, id){
                if(confirm('Are you sure, you want delite this comment from page content?')){
                    axios
                    .post('../../api/del_user_comment/'+id, {
                        cause,
                        _method: 'post'
                    })
                    .then(Response => {
                        this.is_user_comment_delite_model = false
                    })
                    .catch(error => console.log(error))
                }
            },
            quick_wiev_action(comment_id){
                this.is_coment_model = true
                this.get_action_comment(comment_id)
            },
            user_coment_del_modal(comment_id){
                this.is_user_comment_delite_model = true
                this.get_action_comment(comment_id)
            },

            get_action_comment(comment_id){
                this.quick_comment = []

                axios
                .get("../api/get_quick_comment/"+comment_id)
                .then(response => {
                    this.quick_comment = response.data
                })
                .catch(
                    error => console.log(error)
                );

            },
            edit_coment(comment_id){
                alert('Edit comment ( article ID - '+comment_id+')')
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