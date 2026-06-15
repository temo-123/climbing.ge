<template>
    <StackModal
        v-model="is_show_modal"
        title="Hide content"
        @close="close_modal"
        @save="submit"
        :saveButton="{ visible: true, title: 'Hide' }"
        :cancelButton="{ title: 'Cancel', btnClass: { 'btn btn-secondary': true } }"
    >
        <div class="container">
            <div class="mb-3">
                <label class="form-label fw-semibold">Reason</label>
                <select class="form-select" v-model="hidden_reason">
                    <option value="">— Select reason —</option>
                    <option value="spam">Spam</option>
                    <option value="aggressive_remark">Aggressive remark</option>
                    <option value="offensive">Offensive content</option>
                    <option value="other">Other</option>
                </select>
            </div>
            <div class="mb-3" v-if="hidden_reason === 'other'">
                <label class="form-label fw-semibold">Comment</label>
                <textarea class="form-control" v-model="hidden_reason_text" rows="3" placeholder="Describe the reason..."></textarea>
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
                this.error = 'Please select a reason.';
                return;
            }
            if (this.hidden_reason === 'other' && !this.hidden_reason_text.trim()) {
                this.error = 'Please enter a comment for "Other".';
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
