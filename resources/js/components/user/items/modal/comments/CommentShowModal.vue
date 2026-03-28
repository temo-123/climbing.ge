<template>
    <StackModal
        v-model="is_coment_model"
        title="Show comment"
        @close="close_modal()">
        <div>
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
        </div>
    </StackModal>
</template>

<script>
    import { SlickList, SlickItem } from 'vue-slicksort';
    export default {
        components: {},
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
