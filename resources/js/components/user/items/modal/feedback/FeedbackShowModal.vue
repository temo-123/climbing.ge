<template>
    <StackModal
        v-model="is_show"
        title="Feedback details"
        @close="close_modal()"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }"
    >
        <div v-if="row_data">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Product</th>
                        <td>{{ row_data.locale_product && row_data.locale_product.title }}</td>
                    </tr>
                    <tr>
                        <th>Reviewer</th>
                        <td>{{ row_data.feedback && row_data.feedback.name }} {{ row_data.feedback && row_data.feedback.surname }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ row_data.feedback && row_data.feedback.email }}</td>
                    </tr>
                    <tr>
                        <th>Stars</th>
                        <td>
                            <i
                                v-for="n in 5"
                                :key="n"
                                :class="n <= (row_data.feedback && row_data.feedback.stars) ? 'fa fa-star rating-color' : 'fa fa-star'"
                            ></i>
                        </td>
                    </tr>
                    <tr>
                        <th>Feedback text</th>
                        <td>{{ row_data.feedback && row_data.feedback.text }}</td>
                    </tr>
                </tbody>
            </table>
            <div v-if="row_data.feedback && row_data.feedback.published == 0"
                 class="alert mt-3"
                 :class="row_data.feedback.admin_hidden ? 'alert-danger' : 'alert-warning'">
                <strong v-if="row_data.feedback.admin_hidden"><i class="fa fa-ban"></i> Hidden by admin</strong>
                <strong v-else><i class="fa fa-eye-slash"></i> Hidden by user</strong>
                <div v-if="row_data.feedback.admin_hidden && row_data.feedback.hidden_reason" class="mt-1">
                    Reason: <strong>{{ reasonLabel(row_data.feedback.hidden_reason) }}</strong>
                    <div v-if="row_data.feedback.hidden_reason_text" class="text-muted mt-1">{{ row_data.feedback.hidden_reason_text }}</div>
                </div>
            </div>
        </div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_show: false,
            row_data: null,
        };
    },
    methods: {
        show_modal(rowData) {
            this.row_data = rowData;
            this.is_show = true;
        },
        close_modal() {
            this.is_show = false;
            this.row_data = null;
        },
        reasonLabel(r) {
            return { spam: 'Spam', aggressive_remark: 'Aggressive remark', offensive: 'Offensive content', other: 'Other' }[r] || r;
        },
    },
};
</script>

<style scoped>
.rating-color { color: #fbc634; }
</style>
