<template>
    <StackModal
        v-model="is_coment_model"
        title="Show comment"
        @close="close_modal()">
        <div>
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Name</th>
                        <td>{{ quick_comment.name }} {{ quick_comment.surname }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ quick_comment.email }}</td>
                    </tr>
                    <tr>
                        <th>Date</th>
                        <td>{{ quick_comment.created_at }}</td>
                    </tr>
                    <tr>
                        <th>Text</th>
                        <td>{{ quick_comment.text }}</td>
                    </tr>
                </tbody>
            </table>
            <div v-if="quick_comment.published == 0"
                 class="alert mt-3"
                 :class="quick_comment.admin_hidden ? 'alert-danger' : 'alert-warning'">
                <strong v-if="quick_comment.admin_hidden"><i class="fa fa-ban"></i> Hidden by admin</strong>
                <strong v-else><i class="fa fa-eye-slash"></i> Hidden by user</strong>
                <div v-if="quick_comment.admin_hidden && quick_comment.hidden_reason" class="mt-1">
                    Reason: <strong>{{ reasonLabel(quick_comment.hidden_reason) }}</strong>
                    <div v-if="quick_comment.hidden_reason_text" class="text-muted mt-1">{{ quick_comment.hidden_reason_text }}</div>
                </div>
            </div>
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
            reasonLabel(r) {
                return { spam: 'Spam', aggressive_remark: 'Aggressive remark', offensive: 'Offensive content', other: 'Other' }[r] || r;
            },
        }
    }
</script>
