<template>
    <div class="login mt-5">
        <div class="card">
            <div class="card-header">
                <button type="button" class="btn btn-primary btn-sm float-end" @click="go_back()">
                    <i class="fa fa-arrow-left" aria-hidden="true"></i> {{ $t('user.delete_account.back_btn') }}
                </button>
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
                        <label>{{ $t('user.delete_account.content_title') }}</label>
                        <div v-for="item in keepable" :key="item" class="form-check">
                            <input type="checkbox" class="form-check-input" :id="'delete_' + item" :value="item" v-model="delete_content" />
                            <label class="form-check-label" :for="'delete_' + item">{{ $t('user.delete_account.delete_' + item) }}</label>
                        </div>
                        <small class="form-text text-muted">{{ $t('user.delete_account.content_hint') }}</small>
                    </div>

                    <div class="form-group">
                        <label for="password">{{ $t('user.delete_account.password_label') }} <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" v-model="password" autocomplete="current-password" required />
                        <small class="form-text text-muted">
                            {{ $t('user.delete_account.password_hint') }}
                            <router-link :to="{ name: 'forget_pass' }">{{ $t('user.delete_account.forgot_password') }}</router-link>
                        </small>
                    </div>

                    <div class="form-group">
                        <label for="confirm_text">{{ $t('user.delete_account.confirm_label') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="confirm_text" v-model="confirm_text" placeholder="DELETE" autocomplete="off" required />
                    </div>

                    <div class="alert alert-danger mt-2" role="alert" v-if="error.length">
                        {{ error }}
                    </div>

                    <button type="submit" form="delete_account" class="btn btn-danger mt-2" :disabled="is_deleting || !password || confirm_text !== 'DELETE'">
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
                confirm_text: '',
                // AccountDeletionService::KEEPABLE — checked = delete, unchecked = keep anonymized
                keepable: ['ascents', 'reviews', 'product_feedbacks', 'comments'],
                delete_content: ['ascents', 'reviews', 'product_feedbacks', 'comments'],
                error: '',
                is_deleting: false,
                deleted: false,
            };
        },
        mounted() {
            // This page has no left menu — drop the sidebar margin left over from dashboard pages
            document.body.classList.remove('sidebar-open')
        },
        methods: {
            go_back() {
                if (window.history.length > 1) this.$router.back()
                else this.$router.push({ name: this.is_logged_in ? 'home' : 'login' })
            },
            on_submit() {
                this.is_deleting = true
                this.error = ''
                axios
                .post('account/delete', {
                    confirm: this.confirm_text,
                    password: this.password,
                    keep: this.keepable.filter(item => !this.delete_content.includes(item)),
                })
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
