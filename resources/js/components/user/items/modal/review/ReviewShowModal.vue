<template>
    <StackModal
        v-model="is_show"
        :title="$t('admin.comments.review_details_title')"
        @close="close_modal()"
        :cancelButton="{ title: $t('common.close'), btnClass: { 'btn btn-primary': true } }"
    >
        <div v-if="row_data">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th>{{ $t('admin.comments.col_climbing_route') }}</th>
                        <td>{{ row_data.route && row_data.route.name }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('admin.comments.reviewer_label') }}</th>
                        <td>{{ row_data.user && row_data.user.name }} {{ row_data.user && row_data.user.surname }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('common.email') }}</th>
                        <td>{{ row_data.user && row_data.user.email }}</td>
                    </tr>
                    <tr>
                        <th>{{ $t('admin.comments.col_stars') }}</th>
                        <td>
                            <i
                                v-for="n in 5"
                                :key="n"
                                :class="n <= (row_data.review && row_data.review.stars) ? 'fa fa-star rating-color' : 'fa fa-star'"
                            ></i>
                        </td>
                    </tr>
                    <tr>
                        <th>{{ $t('admin.comments.review_text_label') }}</th>
                        <td>{{ row_data.review && row_data.review.text }}</td>
                    </tr>
                    <tr v-if="row_data.review && row_data.review.climbed">
                        <th>{{ $t('admin.comments.climbed_date_label') }}</th>
                        <td>{{ row_data.review.climbed_data }}</td>
                    </tr>
                </tbody>
            </table>
            <div v-if="row_data.review && row_data.review.published == 0"
                 class="alert mt-3"
                 :class="row_data.review.admin_hidden ? 'alert-danger' : 'alert-warning'">
                <strong v-if="row_data.review.admin_hidden"><i class="fa fa-ban"></i> {{ $t('admin.comments.hidden_by_admin') }}</strong>
                <strong v-else><i class="fa fa-eye-slash"></i> {{ $t('admin.comments.hidden_by_user') }}</strong>
                <div v-if="row_data.review.admin_hidden && row_data.review.hidden_reason" class="mt-1">
                    {{ $t('admin.comments.reason_prefix') }} <strong>{{ reasonLabel(row_data.review.hidden_reason) }}</strong>
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
