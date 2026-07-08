<template>
    <StackModal
        v-model="is_show_modal"
        :title="$t('admin.comments.hide_content_title')"
        @close="close_modal"
        @save="submit"
        :saveButton="{ visible: true, title: $t('admin.comments.hide_btn') }"
        :cancelButton="{ title: $t('admin.comments.cancel_btn'), btnClass: { 'btn btn-secondary': true } }"
    >
        <div class="container">
            <div class="mb-3">
                <label class="form-label fw-semibold">{{ $t('admin.comments.reason_label') }}</label>
                <select class="form-select" v-model="hidden_reason">
                    <option value="">{{ $t('admin.comments.select_reason_placeholder') }}</option>
                    <option value="spam">{{ $t('admin.comments.reason_spam') }}</option>
                    <option value="aggressive_remark">{{ $t('admin.comments.reason_aggressive_remark') }}</option>
                    <option value="offensive">{{ $t('admin.comments.reason_offensive') }}</option>
                    <option value="other">{{ $t('admin.comments.reason_other') }}</option>
                </select>
            </div>
            <div class="mb-3" v-if="hidden_reason === 'other'">
                <label class="form-label fw-semibold">{{ $t('admin.comments.comment_label') }}</label>
                <textarea class="form-control" v-model="hidden_reason_text" rows="3" :placeholder="$t('admin.comments.describe_reason_placeholder')"></textarea>
            </div>
            <div v-if="error" class="alert alert-danger py-1">{{ error }}</div>
        </div>
    </StackModal>
</template>

<script>
export default {
    data() {
        return {
            is_show_modal: false,
            route: '',
            content_id: null,
            hidden_reason: '',
            hidden_reason_text: '',
            error: '',
        };
    },
    methods: {
        show_modal(route, data) {
            this.route = route;
            this.content_id = data.id;
            this.hidden_reason = '';
            this.hidden_reason_text = '';
            this.error = '';
            this.is_show_modal = true;
        },
        close_modal() {
            this.is_show_modal = false;
        },
        submit() {
            if (!this.hidden_reason) {
                this.error = this.$t('admin.comments.error_select_reason');
                return;
            }
            if (this.hidden_reason === 'other' && !this.hidden_reason_text.trim()) {
                this.error = this.$t('admin.comments.error_enter_other_comment');
                return;
            }
            axios.post(this.route + this.content_id, {
                hidden_reason: this.hidden_reason,
                hidden_reason_text: this.hidden_reason_text,
            })
            .then(() => {
                this.$emit('restart');
                this.close_modal();
            })
            .catch(e => console.log(e));
        },
    },
};
</script>
