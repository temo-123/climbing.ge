<template>
    <StackModal
        v-model="is_open"
        :title="modalTitle"
        :saveButton="{ visible: false }"
        :cancelButton="{ visible: true, title: 'Close', btnClass: { 'btn btn-secondary': true } }"
        @close="close()"
    >
        <div class="form-group">
            <label>New Password</label>
            <input
                type="password"
                class="form-control"
                v-model="password"
                placeholder="Enter new password"
                autocomplete="new-password"
            />
        </div>
        <div class="form-group mt-2">
            <label>Confirm Password</label>
            <input
                type="password"
                class="form-control"
                v-model="password_confirm"
                placeholder="Confirm new password"
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
                {{ saving ? 'Saving...' : 'Set Password' }}
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
            return this.user_name ? `Reset Password: ${this.user_name}` : 'Reset Password';
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
                this.error = 'Password is required.';
                return;
            }
            if (this.password.length < 6) {
                this.error = 'Password must be at least 6 characters.';
                return;
            }
            if (this.password !== this.password_confirm) {
                this.error = 'Passwords do not match.';
                return;
            }
            this.saving = true;
            axios.post('/set_user/reset_password/' + this.user_id, { password: this.password })
                .then(response => {
                    alert(response.data.message);
                    this.close();
                })
                .catch(error => {
                    this.error = error.response?.data?.message || error.response?.data?.error || 'Error resetting password.';
                })
                .finally(() => this.saving = false);
        }
    }
}
</script>
