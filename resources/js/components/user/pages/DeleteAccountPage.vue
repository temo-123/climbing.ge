<template>
    <div class="login mt-5">
        <div class="card">
            <div class="card-header">
                <h1>{{ $t('user.delete_account.title') }}</h1>
            </div>

            <div class="card-body" v-if="deleted">
                <div class="alert alert-success" role="alert">
                    {{ $t('user.delete_account.success') }}
                </div>
            </div>

            <div class="card-body" v-else>
                <p>{{ $t('user.delete_account.intro') }}</p>

                <h5>{{ $t('user.delete_account.steps_title') }}</h5>
                <ol>
                    <li>{{ $t('user.delete_account.step_login') }}</li>
                    <li>{{ $t('user.delete_account.step_confirm') }}</li>
                    <li>{{ $t('user.delete_account.step_done') }}</li>
                </ol>

                <h5>{{ $t('user.delete_account.deleted_title') }}</h5>
                <p>{{ $t('user.delete_account.deleted_text') }}</p>

                <h5>{{ $t('user.delete_account.kept_title') }}</h5>
                <p>{{ $t('user.delete_account.kept_text') }}</p>

                <hr>

                <div v-if="!is_logged_in">
                    <router-link :to="{ name: 'login', query: { redirect: $route.fullPath } }" class="btn btn-primary">
                        {{ $t('user.delete_account.login_btn') }}
                    </router-link>
                </div>

                <form v-else id="delete_account" v-on:submit.prevent="on_submit">
                    <div class="alert alert-danger" role="alert">
                        {{ $t('user.delete_account.warning') }}
                    </div>

                    <div class="form-group">
                        <label for="password">{{ $t('user.delete_account.password_label') }}</label>
                        <input type="password" class="form-control" id="password" v-model="password" autocomplete="current-password" />
                        <small class="form-text text-muted">{{ $t('user.delete_account.password_hint') }}</small>
                    </div>

                    <div class="form-group mt-2">
                        <label for="confirm">{{ $t('user.delete_account.confirm_label') }}</label>
                        <input type="text" class="form-control" id="confirm" v-model="confirm" placeholder="DELETE" required />
                    </div>

                    <div class="alert alert-danger mt-2" role="alert" v-if="error.length">
                        {{ error }}
                    </div>

                    <button type="submit" form="delete_account" class="btn btn-danger mt-2" :disabled="is_deleting || confirm !== 'DELETE'">
                        {{ is_deleting ? $t('user.delete_account.deleting') : $t('user.delete_account.delete_btn') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'DeleteAccountPage',
        data: function () {
            return {
                is_logged_in: !!localStorage.getItem('auth_token'),
                password: '',
                confirm: '',
                error: '',
                is_deleting: false,
                deleted: false,
            };
        },
        methods: {
            on_submit() {
                this.is_deleting = true
                this.error = ''
                axios
                .post('account/delete', { confirm: this.confirm, password: this.password || null })
                .then(() => {
                    localStorage.removeItem('auth_token')
                    localStorage.removeItem('x_xsrf_token')
                    localStorage.removeItem('user_permissions')
                    this.is_logged_in = false
                    this.deleted = true
                })
                .catch((error) => {
                    const errors = error.response?.data?.errors
                    this.error = (errors && Object.values(errors)[0]?.[0])
                        || error.response?.data?.message
                        || this.$t('user.delete_account.error')
                })
                .finally(() => this.is_deleting = false)
            },
        },
    };
</script>
