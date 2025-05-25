<template>
    <stack-modal
            :show="is_coment_model"
            title="Show comment"
            @close="close_model()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre class="language-vue">
            <div class="container" v-show="is_loading">
                <div class="row justify-content-center">
                    <div class="col-md-4">
                        <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>
            </div>

            <span v-show="!is_loading">
                <h1>Show Comment</h1>

                
                <!-- <span v-if="comments_tab_name == 'Comments'"> -->
                    Comentator - {{ quick_comment.name }} {{ quick_comment.suenmae }}
                    Email - {{ quick_comment.email }}
                    <!-- Data to comment - {{ quick_comment.created_at }} -->
                <!-- </span> -->

                {{ quick_comment.text }}

                <span v-if="quick_comment.deleted_reason != null">
                    <div class="alert alert-danger" role="alert">
                        This comment was deleted!!!
                        <strong>Reason for deletion </strong>
                        We have calculated that your comment is - {{ quick_comment.deleted_reason }}
                    </div>
                </span>
                {{ quick_comment.deleted_reason }}
                <span v-if="quick_comment.deleted_reason == null">
                    <h1>You can file a complaint for this comment</h1>
                    <p>Please select a reason for deleting the comment!!!</p>
                    
                    <form v-on:submit.prevent="hide_comment" id="comment_hide_form" class="form">
                        <select class="form-control" v-model="selected_comment_complaint" name="comment delete cause" > 
                            <option value="Hostile remarks">Hostile remarks</option>
                            <option value="Does not match the theme of the site">Does not match the theme of the site</option>
                            <option value="Spam">Spam</option>
                            <option value="Sexual content">Sexual content</option>
                            <option value="Expression of anger">Expression of anger</option>
                            <option value="Conflict with other members of the site">Conflict with other members of the site</option>
                            <option value="The language of the comments does not match the requirements of the site">The language of the comments does not match the requirements of the site</option>
                        </select>
                    </form>
                </span>
            </span>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer"  v-if="quick_comment.deleted_reason == null">
                <button
                    type="submit"
                    :class="{'btn btn-primary': true}"
                    form="comment_hide_form"
                >
                Hide
                </button>
            </div>
        </div>
    </stack-modal>
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
            // 
        ],
        data(){
            return {
                is_coment_model: false,
                is_loading: false,
                route: '',
                quick_comment: [],
                selected_comment_complaint: ''
            }
        },
        mounted(){
            // 
        },
        methods: {
            hide_comment(){
                if(confirm('Are you sure, you want hide this comment it?')){
                    let sending_data = {
                        "complaint": this.selected_comment_complaint,
                        "email": this.quick_comment.email,
                        "comment_id": this.quick_comment.id
                    }
                    this.is_loading = true
                    axios
                    .post(this.route + this.quick_comment.id, {
                        _method: 'post',
                        data: sending_data,
                    })
                    .then(Response => {
                        this.$emit('restart')
                        this.close_model()
                    })
                    .catch(error => console.log(error))
                    .finally(() => this.is_loading = false)
                }
            },
            show_modal(route, data){
                this.is_coment_model = true
                this.route = route
                this.quick_comment = data
            },
            close_model(){
                this.is_coment_model = false
                this.selected_comment_complaint = ''
            },
        }
    }
</script>
