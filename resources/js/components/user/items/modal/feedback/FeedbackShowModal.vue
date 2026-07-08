<template>
    <StackModal
        v-model="is_show"
        :title="$t('admin.comments.feedback_details_title')"
        @close="close_modal()"
        :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-primary': true } }"
    >
        <div v-if="row_data">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>{{ $t('common.product') }}</th>
                        <td>{{ row_data.locale_product && row_data.locale_product.title }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('admin.comments.reviewer_label') }}</th>
                        <td>{{ row_data.feedback && row_data.feedback.name }} {{ row_data.feedback && row_data.feedback.surname }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('common.email') }}</th>
                        <td>{{ row_data.feedback && row_data.feedback.email }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('admin.comments.col_stars') }}</th>
                        <td>
                            <i
                                v-for="n in 5"
                                :key="n"
                                :class="n <= (row_data.feedback && row_data.feedback.stars) ? 'fa fa-star rating-color' : 'fa fa-star'"
                            ></i>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ $t('admin.comments.feedback_text_label') }}</th>
                        <td>{{ row_data.feedback && row_data.feedback.text }}</td>
                    </tr>
                </tbody>
            </table>
            <div v-if="row_data.feedback && row_data.feedback.published == 0"
                 class="alert mt-3"
                 :class="row_data.feedback.admin_hidden ? 'alert-danger' : 'alert-warning'">
                <strong v-if="row_data.feedback.admin_hidden"><i class="fa fa-ban"></i> {{ $t('admin.comments.hidden_by_admin') }}</strong>
                <strong v-else><i class="fa fa-eye-slash"></i> {{ $t('admin.comments.hidden_by_user') }}</strong>
                <div v-if="row_data.feedback.admin_hidden && row_data.feedback.hidden_reason" class="mt-1">
                    {{ $t('admin.comments.reason_prefix') }} <strong>{{ reasonLabel(row_data.feedback.hidden_reason) }}</strong>
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
            return {
                spam: this.$t('admin.comments.reason_spam'),
                aggressive_remark: this.$t('admin.comments.reason_aggressive_remark'),
                offensive: this.$t('admin.comments.reason_offensive'),
                other: this.$t('admin.comments.reason_other'),
            }[r] || r;
        },
    },
};
</script>

<style scoped>
.rating-color { color: #fbc634; }
</style>
