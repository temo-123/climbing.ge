<template>
    <div class="cg-verify-page">
        <div class="cg-verify-page__card">
            <i class="fa fa-envelope-o cg-verify-page__icon" aria-hidden="true"></i>

            <h1 class="cg-verify-page__title">{{ $t('user.verify_required.title') }}</h1>
            <p class="mb-2">{{ $t('user.verify_required.text') }}</p>
            <p v-if="email" class="cg-verify-page__email">{{ email }}</p>

            <ol class="cg-verify-page__steps">
                <li>{{ $t('user.verify_required.step_open') }}</li>
                <li>{{ $t('user.verify_required.step_click') }}</li>
                <li>{{ $t('user.verify_required.step_spam') }}</li>
            </ol>

            <div class="cg-verify-page__actions">
                <button type="button" class="btn btn-primary" :disabled="sending || cooldown > 0" @click="resend">
                    <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <i v-else class="fa fa-paper-plane" aria-hidden="true"></i>
                    {{ resend_label }}
                </button>
                <button type="button" class="btn btn-outline-success" :disabled="checking" @click="check_again">
                    <span v-if="checking" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <i v-else class="fa fa-refresh" aria-hidden="true"></i>
                    {{ $t('user.verify_required.check_btn') }}
                </button>
            </div>
            <p v-if="still_unverified" class="small text-danger mt-2 mb-0">{{ $t('user.verify_required.still_unverified') }}</p>

            <hr>

            <p class="small text-muted mb-2">{{ $t('user.verify_required.wrong_email') }}</p>
            <div class="cg-verify-page__links">
                <router-link :to="{ name: 'options', params: { locale: $locale_param() } }" class="btn btn-sm btn-light">
                    <i class="fa fa-pencil" aria-hidden="true"></i> {{ $t('global.email_verify.change_email') }}
                </router-link>
                <button type="button" class="btn btn-sm btn-light" @click="logout">
                    <i class="fa fa-sign-out" aria-hidden="true"></i> {{ $t('user.verify_required.logout') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import { get_auth_user, forget_auth_user } from '../../../services/authUser.js'

const COOLDOWN_S = 60

// user.climbing.ge for a logged-in user whose e-mail isn't verified: the
// route guard in app.js sends every panel route here (only Options, Delete
// account, the legal pages and the verify link stay reachable).
// docs/FUNCTIONS_DOCS/global/email_verification.md
export default {
    data() {
        return {
            email: '',
            sending: false,
            checking: false,
            still_unverified: false,
            cooldown: 0,
        }
    },
    computed: {
        resend_label() {
            if (this.cooldown > 0) return this.$t('global.email_verify.sent_wait', { s: this.cooldown })
            return this.sending ? this.$t('global.email_verify.sending') : this.$t('global.email_verify.resend_btn')
        },
    },
    mounted() {
        get_auth_user()
            .then(response => {
                this.email = response.data.email || ''
                if (response.data.email_verified_at) this.go_on()
            })
            .catch(() => {})
    },
    beforeUnmount() {
        clearInterval(this._timer)
    },
    methods: {
        resend() {
            if (this.sending || this.cooldown > 0) return
            this.sending = true
            axios.get('email/resend')
                .then(response => {
                    if (response.data?.message === 'Email already verified') return this.go_on()
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('global.email_verify.sent'), duration: 6000 })
                    this.cooldown = COOLDOWN_S
                    clearInterval(this._timer)
                    this._timer = setInterval(() => {
                        if (--this.cooldown <= 0) clearInterval(this._timer)
                    }, 1000)
                })
                .catch(() => {})   // 429 / 500 toast globally
                .finally(() => { this.sending = false })
        },
        // verified on another device (phone) — look again without reloading
        check_again() {
            this.checking = true
            this.still_unverified = false
            get_auth_user({ force: true })
                .then(response => {
                    if (response.data.email_verified_at) this.go_on()
                    else this.still_unverified = true
                })
                .catch(() => {})
                .finally(() => { this.checking = false })
        },
        go_on() {
            forget_auth_user()
            this.$bus.$emit('email-verified')
            this.$bus.$emit('toast', { type: 'success', message: this.$t('global.email_verify.already') })
            this.$router.push({ name: 'home', params: { locale: this.$locale_param() } })
        },
        logout() {
            axios.post('logout')
                .finally(() => {
                    localStorage.removeItem('x_xsrf_token')
                    localStorage.removeItem('user_permissions')
                    localStorage.removeItem('auth_token')
                    if (this.$ability) this.$ability.update([])
                    this.$bus.$emit('logged-out')
                    this.$router.push({ name: 'login' })
                })
        },
    },
}
</script>

<style scoped>
.cg-verify-page {
    display: flex;
    justify-content: center;
    padding: 32px 16px;
}
.cg-verify-page__card {
    width: 100%;
    max-width: 560px;
    padding: 32px 28px;
    text-align: center;
    background: var(--cg-surface, #fff);
    border: 1px solid var(--cg-line, #e5e5e5);
    border-radius: var(--cg-r-lg, 12px);
    box-shadow: var(--cg-shadow, 0 4px 24px rgba(0, 0, 0, .08));
}
.cg-verify-page__icon {
    font-size: 3.2rem;
    color: var(--cg-accent);
}
.cg-verify-page__title {
    margin: 12px 0 10px;
    font-family: var(--cg-font-display, inherit);
    font-size: 1.6rem;
    font-weight: 700;
    color: var(--cg-ink);
}
.cg-verify-page__email {
    font-weight: 600;
    word-break: break-all;
    margin-bottom: 16px;
}
.cg-verify-page__steps {
    text-align: left;
    max-width: 420px;
    margin: 0 auto 20px;
    padding-left: 20px;
    color: var(--cg-muted, #6c757d);
    font-size: .92rem;
}
.cg-verify-page__steps li + li { margin-top: 4px; }
.cg-verify-page__actions,
.cg-verify-page__links {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 8px;
}
@media (max-width: 575.98px) {
    .cg-verify-page__card { padding: 24px 16px; }
    .cg-verify-page__actions .btn { width: 100%; }
}
</style>
