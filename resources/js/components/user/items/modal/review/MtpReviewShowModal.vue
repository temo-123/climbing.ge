<template>
    <StackModal
        v-model="is_show"
        title="MTP Review details"
        @close="close_modal()"
        :cancelButton="{ title: 'Close', btnClass: { 'btn btn-primary': true } }"
    >
        <div v-if="row_data">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>Multi-Pitch</th>
                        <td>{{ row_data.mtp && row_data.mtp.name }}</td>
                    </tr>
                    <tr>
                        <th>Reviewer</th>
                        <td>{{ row_data.user && row_data.user.name }} {{ row_data.user && row_data.user.surname }}</td>
                    </tr>
                    <tr>
                        <th>Email</th>
                        <td>{{ row_data.user && row_data.user.email }}</td>
                    </tr>
                    <tr>
                        <th>Stars</th>
                        <td>
                            <i
                                v-for="n in 5"
                                :key="n"
                                :class="n <= (row_data.review && row_data.review.stars) ? 'fa fa-star rating-color' : 'fa fa-star'"
                            ></i>
                        </td>
                    </tr>
                    <tr>
                        <th>Review text</th>
                        <td>{{ row_data.review && row_data.review.text }}</td>
                    </tr>
                </tbody>
            </table>
            <div v-if="row_data.review && row_data.review.published == 0"
                 class="alert mt-3"
                 :class="row_data.review.admin_hidden ? 'alert-danger' : 'alert-warning'">
                <strong v-if="row_data.review.admin_hidden"><i class="fa fa-ban"></i> Hidden by admin</strong>
                <strong v-else><i class="fa fa-eye-slash"></i> Hidden by user</strong>
                <div v-if="row_data.review.admin_hidden && row_data.review.hidden_reason" class="mt-1">
                    Reason: <strong>{{ reasonLabel(row_data.review.hidden_reason) }}</strong>
                    <div v-if="row_data.review.hidden_reason_text" class="text-muted mt-1">{{ row_data.review.hidden_reason_text }}</div>
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
