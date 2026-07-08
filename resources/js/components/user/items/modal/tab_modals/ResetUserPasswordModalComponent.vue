<template>
    <StackModal
        v-model="is_open"
        :title="modalTitle"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: $t('common.close'), btnClass: { 'btn btn-secondary': true } }"
        @close="close()"
    >
        <div class="form-group">
            <label>{{ $t('admin.users.new_password_label') }}</label>
            <input
                type="password"
                class="form-control"
                v-model="password"
                :placeholder="$t('admin.users.enter_new_password_placeholder')"
                autocomplete="new-password"
            />
        </div>
        <div class="form-group mt-2">
            <label>{{ $t('admin.users.confirm_password_label') }}</label>
            <input
                type="password"
                class="form-control"
                v-model="password_confirm"
                :placeholder="$t('admin.users.confirm_new_password_placeholder')"
                autocomplete="new-password"
            />
        </div>
        <div v-if="error" class="alert alert-danger mt-2">{{ error }}</div>
        <div class="mt-3">
            <button
                class="btn btn-primary"
                :disabled="saving"
                @click="save()"
            >
                {{ saving ? $t('admin.users.saving_ellipsis') : $t('admin.users.set_password_btn') }}
            </button>
        </div>
    </StackModal>
</template>

<script>
export default {
    emits: [],
    data() {
        return {
            is_open: false,
            saving: false,
            user_id: null,
            user_name: '',
            password: '',
            password_confirm: '',
            error: '',
        };
    },
    computed: {
        modalTitle() {
            return this.user_name ? this.$t('admin.users.reset_password_prefix', { name: this.user_name }) : this.$t('admin.users.reset_password_title');
        }
    },
    methods: {
        show_modal(user_id, user_name = '') {
            this.user_id = user_id;
            this.user_name = user_name;
            this.password = '';
            this.password_confirm = '';
            this.error = '';
            this.is_open = true;
        },
        close() {
            this.is_open = false;
        },
        save() {
            this.error = '';
            if (!this.password) {
                this.error = this.$t('admin.users.password_required');
                return;
            }
            if (this.password.length < 6) {
                this.error = this.$t('admin.users.password_min_length');
                return;
            }
            if (this.password !== this.password_confirm) {
                this.error = this.$t('admin.users.passwords_do_not_match');
                return;
            }
            this.saving = true;
            axios.post('/set_user/reset_password/' + this.user_id, { password: this.password })
                .then(response => {
                    alert(response.data.message);
                    this.close();
                })
                .catch(error => {
                    this.error = error.response?.data?.message || error.response?.data?.error || this.$t('admin.users.error_resetting_password');
                })
                .finally(() => this.saving = false);
        }
    }
}
</script>
