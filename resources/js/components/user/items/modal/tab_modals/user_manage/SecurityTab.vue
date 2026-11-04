<template>
    <div class="row g-4">
        <!-- Ban -->
        <div v-if="$can('create_ban', 'user')" class="col-lg-6">
            <div class="border rounded p-3 h-100">
                <h6 class="fw-semibold"><i class="fa fa-ban"></i> {{ $t('admin.tour.user_ban_title') }}</h6>

                <div v-if="ban_loading" class="text-muted py-2">
                    <span class="spinner-border spinner-border-sm text-primary" role="status"></span>
                </div>

                <template v-else>
                    <div v-if="!ban_role_exists" class="alert alert-warning py-2 small">{{ $t('admin.tour.ban_role_missing_warning') }}</div>

                    <div class="alert py-2" :class="is_banned ? 'alert-danger' : 'alert-success'">
                        {{ $t('admin.tour.user_currently_prefix') }}
                        <strong>{{ is_banned ? $t('admin.tour.banned_label') : $t('admin.tour.active_label') }}</strong>.
                    </div>

                    <template v-if="!is_banned">
                        <label class="form-label">{{ $t('admin.tour.ban_duration_label') }}</label>
                        <select class="form-select" v-model="ban_duration">
                            <option value="" disabled>{{ $t('admin.tour.select_duration_placeholder') }}</option>
                            <option value="0.5 year">{{ $t('admin.tour.half_year_option') }}</option>
                            <option value="1 year">{{ $t('admin.tour.one_year_option') }}</option>
                            <option value="2 year">{{ $t('admin.tour.two_year_option') }}</option>
                            <option value="permanent">{{ $t('admin.tour.permanent_option') }}</option>
                        </select>
                        <button type="button" class="btn btn-warning mt-3" :disabled="!ban_duration || ban_saving" @click="create_ban">
                            <i class="fa fa-ban"></i> {{ ban_saving ? $t('admin.tour.banning_ellipsis') : $t('admin.tour.ban_user_btn') }}
                        </button>
                    </template>
                    <button v-else type="button" class="btn btn-success" :disabled="ban_saving" @click="remove_ban">
                        <i class="fa fa-check"></i> {{ ban_saving ? $t('admin.tour.unbanning_ellipsis') : $t('admin.tour.unban_user_btn') }}
                    </button>
                </template>
            </div>
        </div>

        <!-- Password reset -->
        <div v-if="$can('edit', 'user')" class="col-lg-6">
            <form class="border rounded p-3 h-100" @submit.prevent="reset_password">
                <h6 class="fw-semibold"><i class="fa fa-key"></i> {{ $t('admin.users.reset_password_title') }}</h6>

                <label class="form-label">{{ $t('admin.users.new_password_label') }}</label>
                <input
                    type="password"
                    class="form-control"
                    v-model="password"
                    :placeholder="$t('admin.users.enter_new_password_placeholder')"
                    autocomplete="new-password"
                >
                <label class="form-label mt-2">{{ $t('admin.users.confirm_password_label') }}</label>
                <input
                    type="password"
                    class="form-control"
                    v-model="password_confirm"
                    :placeholder="$t('admin.users.confirm_new_password_placeholder')"
                    autocomplete="new-password"
                >
                <div v-if="password_error" class="alert alert-danger py-2 mt-2 mb-0">{{ password_error }}</div>
                <small class="form-text text-muted d-block mt-2">{{ $t('admin.users.reset_password_logout_hint') }}</small>

                <button type="submit" class="btn btn-primary mt-3" :disabled="password_saving">
                    {{ password_saving ? $t('admin.users.saving_ellipsis') : $t('admin.users.set_password_btn') }}
                </button>
            </form>
        </div>
    </div>
</template>

<script>
export default {
    emits: ['saved'],
    props: {
        user_id: { type: [Number, String], required: true },
    },
    data() {
        return {
            ban_loading: false,
            ban_saving: false,
            ban_role_exists: true,
            is_banned: false,
            ban_duration: '',

            password: '',
            password_confirm: '',
            password_error: '',
            password_saving: false,
        }
    },
    mounted() {
        if (this.$can('create_ban', 'user')) this.load_ban_status()
    },
    methods: {
        toast(type, message) {
            this.$bus.$emit('toast', { type, title: this.$t('admin.users.toast_title'), message })
        },
        load_ban_status() {
            this.ban_loading = true
            axios.get('/set_user/get_ban_status/' + this.user_id)
                .then(response => {
                    this.is_banned = response.data.is_banned
                    this.ban_role_exists = response.data.ban_role_exists !== false
                })
                .catch(error => console.log(error))
                .finally(() => this.ban_loading = false)
        },
        create_ban() {
            this.ban_saving = true
            axios.post('/set_user/create_ban/' + this.user_id, { duration: this.ban_duration })
                .then(response => {
                    this.is_banned = true
                    this.ban_duration = ''
                    this.toast('success', response.data.message)
                    this.$emit('saved')
                })
                .catch(error => this.toast('danger', error.response?.data?.message || this.$t('admin.users.role_saved_error_message')))
                .finally(() => this.ban_saving = false)
        },
        remove_ban() {
            this.ban_saving = true
            axios.delete('/set_user/remove_ban/' + this.user_id)
                .then(response => {
                    this.is_banned = false
                    this.toast('success', response.data.message)
                    this.$emit('saved')
                })
                .catch(error => this.toast('danger', error.response?.data?.message || this.$t('admin.users.role_saved_error_message')))
                .finally(() => this.ban_saving = false)
        },
        reset_password() {
            this.password_error = ''
            if (!this.password) {
                this.password_error = this.$t('admin.users.password_required')
                return
            }
            if (this.password.length < 6) {
                this.password_error = this.$t('admin.users.password_min_length')
                return
            }
            if (this.password !== this.password_confirm) {
                this.password_error = this.$t('admin.users.passwords_do_not_match')
                return
            }
            this.password_saving = true
            axios.post('/set_user/reset_password/' + this.user_id, { password: this.password })
                .then(response => {
                    this.password = ''
                    this.password_confirm = ''
                    this.toast('success', response.data.message)
                })
                .catch(error => {
                    this.password_error = error.response?.data?.message || error.response?.data?.error || this.$t('admin.users.error_resetting_password')
                })
                .finally(() => this.password_saving = false)
        },
    },
}
</script>
