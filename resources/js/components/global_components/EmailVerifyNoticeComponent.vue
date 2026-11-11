<template>
    <!-- Bar for a logged-in user whose e-mail isn't verified (every subdomain) -->
    <div v-if="show_bar" class="cg-verify-bar" role="status">
        <i class="fa fa-envelope-o cg-verify-bar__icon" aria-hidden="true"></i>
        <div class="cg-verify-bar__text">
            <strong>{{ $t('global.email_verify.bar_title') }}</strong>
            <span class="d-none d-md-inline"> {{ $t('global.email_verify.bar_text') }}</span>
        </div>
        <button type="button" class="btn btn-sm btn-light cg-verify-bar__btn" :disabled="sending || cooldown > 0" @click="resend">
            <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            {{ resend_label }}
        </button>
        <button type="button" class="cg-verify-bar__close" :aria-label="$t('common.close')" @click="hide_bar">
            <i class="fa fa-times" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Opened when an action is refused with 403 { code: 'email_not_verified' } -->
    <StackModal v-model="is_modal" :title="$t('global.email_verify.modal_title')" size="md" @close="is_modal = false">
        <div class="text-center py-2">
            <i class="fa fa-envelope-o cg-verify-modal__icon" aria-hidden="true"></i>
            <p class="mt-3 mb-2">{{ $t('global.email_verify.modal_text') }}</p>
            <p v-if="email" class="fw-semibold text-break mb-3">{{ email }}</p>
            <p class="small text-muted mb-0">{{ $t('global.email_verify.modal_hint') }}</p>
        </div>
        <template #footer>
            <div class="modal-footer">
                <a v-if="options_url" :href="options_url" class="btn btn-light me-auto">
                    {{ $t('global.email_verify.change_email') }}
                </a>
                <button type="button" class="btn btn-primary" :disabled="sending || cooldown > 0" @click="resend">
                    <span v-if="sending" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    <i v-else class="fa fa-paper-plane" aria-hidden="true"></i>
                    {{ resend_label }}
                </button>
            </div>
        </template>
    </StackModal>
</template>

<script>
import StackModal from './modals/StackModal.vue'
import { get_auth_user, forget_auth_user } from '../../services/authUser.js'

const HIDE_KEY = 'cg_verify_bar_hidden'
const COOLDOWN_S = 60

// Routes where the bar would only be in the way (the verify pages themselves)
const NO_BAR_ROUTES = ['verify', 'verify_required']

export default {
    name: 'EmailVerifyNotice',
    components: { StackModal },
    data() {
        return {
            unverified: false,
            email: '',
            checked_token: null,
            bar_hidden: false,
            is_modal: false,
            sending: false,
            cooldown: 0,
            unsubs: [],
        }
    },
    computed: {
        show_bar() {
            return this.unverified && !this.bar_hidden && !NO_BAR_ROUTES.includes(this.$route?.name)
        },
        resend_label() {
            if (this.cooldown > 0) return this.$t('global.email_verify.sent_wait', { s: this.cooldown })
            return this.sending ? this.$t('global.email_verify.sending') : this.$t('global.email_verify.resend_btn')
        },
        // the e-mail can be changed on user.climbing.ge/options
        options_url() {
            const host = process.env.MIX_USER_PAGE_URL
            if (!host) return null
            return (process.env.MIX_APP_SSH || '//') + host + '/options'
        },
    },
    watch: {
        // the user subdomain logs in with a page (no bus event) — re-check when the token changes
        '$route.fullPath'() { this.check() },
    },
    mounted() {
        try { this.bar_hidden = sessionStorage.getItem(HIDE_KEY) === '1' } catch (e) {}

        this.unsubs = [
            this.$bus.$on('logged-in', () => this.check(true)),
            this.$bus.$on('permissions-loaded', () => this.check(true)),
            this.$bus.$on('email-verified', () => { this.unverified = false; this.is_modal = false }),
            this.$bus.$on('email-not-verified', (payload) => this.open_modal(payload)),
        ]
        this.check()
    },
    beforeUnmount() {
        this.unsubs.forEach(off => off && off())
        clearInterval(this._timer)
    },
    methods: {
        check(force = false) {
            const token = localStorage.getItem('auth_token') || ''
            if (!token) { this.unverified = false; this.checked_token = ''; return }
            if (!force && token === this.checked_token) return
            this.checked_token = token

            get_auth_user({ force })
                .then(response => {
                    const user = response.data || {}
                    this.email = user.email || ''
                    this.unverified = !!user.id && !user.email_verified_at
                })
                .catch(() => { this.unverified = false })
        },
        open_modal(payload = {}) {
            if (payload.email) this.email = payload.email
            this.unverified = true
            this.is_modal = true
        },
        hide_bar() {
            this.bar_hidden = true
            try { sessionStorage.setItem(HIDE_KEY, '1') } catch (e) {}
        },
        resend() {
            if (this.sending || this.cooldown > 0) return
            this.sending = true
            axios.get('email/resend')
                .then(response => {
                    if (response.data?.message === 'Email already verified') {
                        forget_auth_user()
                        this.unverified = false
                        this.is_modal = false
                        this.$bus.$emit('toast', { type: 'success', message: this.$t('global.email_verify.already') })
                        return
                    }
                    this.$bus.$emit('toast', { type: 'success', message: this.$t('global.email_verify.sent'), duration: 6000 })
                    this.start_cooldown()
                })
                .catch(error => {
                    // 429 / 500 already toast from the global axios handler
                    if (!error.response) {
                        this.$bus.$emit('toast', { type: 'danger', message: this.$t('global.email_verify.send_error') })
                    }
                })
                .finally(() => { this.sending = false })
        },
        start_cooldown() {
            this.cooldown = COOLDOWN_S
            clearInterval(this._timer)
            this._timer = setInterval(() => {
                this.cooldown--
                if (this.cooldown <= 0) clearInterval(this._timer)
            }, 1000)
        },
    },
}
</script>

<style scoped>
.cg-verify-bar {
    position: fixed;
    left: 50%;
    bottom: 16px;
    transform: translateX(-50%);
    z-index: 1040;
    display: flex;
    align-items: center;
    gap: 10px;
    width: min(760px, calc(100% - 32px));
    padding: 10px 12px 10px 16px;
    border-radius: var(--cg-r-lg, 12px);
    background: var(--cg-ink, #1f2a30);
    color: #fff;
    box-shadow: var(--cg-shadow-lg, 0 8px 24px rgba(0, 0, 0, .25));
    font-size: .92rem;
}
.cg-verify-bar__icon {
    font-size: 1.25rem;
    color: var(--cg-accent);
    flex: none;
}
.cg-verify-bar__text {
    flex: 1 1 auto;
    min-width: 0;
    line-height: 1.3;
}
.cg-verify-bar__btn {
    flex: none;
    white-space: nowrap;
}
.cg-verify-bar__close {
    flex: none;
    border: 0;
    background: transparent;
    color: rgba(255, 255, 255, .7);
    padding: 4px 6px;
    line-height: 1;
}
.cg-verify-bar__close:hover { color: #fff; }
.cg-verify-modal__icon {
    font-size: 2.6rem;
    color: var(--cg-accent);
}
@media (max-width: 575.98px) {
    .cg-verify-bar { bottom: 10px; font-size: .85rem; }
}
</style>
