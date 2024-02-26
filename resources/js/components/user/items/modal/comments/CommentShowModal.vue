<template>
    <stack-modal
                :show="is_coment_model"
                title="Show comment"
                @close="close_modal()"
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


                <span v-if="quick_comment.deleted_reason != null">
                    <div class="alert alert-danger" role="alert">
                        This comment was deleted!!!
                        <strong>Reason for deletion </strong>
                        We have calculated that your comment is - {{ quick_comment.deleted_reason }}
                    </div>
                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <!-- <button
                        type="button"
                        :class="{'btn btn-primary': true}"
                        @click="edit_coment(editing_image.id)"
                    >
                    Save
                    </button> -->
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
            'table_info',
            'comments_tab_name'
        ],
        data(){
            return {
                is_coment_model: false,
                quick_comment: [],
            }
        },
        mounted(){
            // console.log(table_info)
        },
        methods: {
            show_modal(comment){
                this.quick_comment = comment
                this.is_coment_model = true
            },
            close_modal(){
                this.is_coment_model = false
            },
        }
    }
</script>
