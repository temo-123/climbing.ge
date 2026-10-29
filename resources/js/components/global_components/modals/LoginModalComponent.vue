<template>
  <stack-modal
    :show="is_show_modal"
:title="mode === 'register' ? $t('auth.quick_register.title') : ($t('common.login') || 'Login')"
    @close="close_modal"
    :saveButton="{ visible: false }"
    :cancelButton="{ visible: false }"
    size="sm"
  >
    <div class="login-modal-body">

      <div v-if="is_loading" class="text-center py-4">
        <i class="fa fa-spinner fa-spin fa-2x" aria-hidden="true"></i>
        <p class="mt-2 text-muted">Logging in...</p>
      </div>

      <div v-else>
        <!-- log in / quick sign-up -->
        <div class="cg-segmented w-100 mb-3" role="tablist">
          <button type="button" role="tab" :class="{ active: mode === 'login' }" :aria-selected="mode === 'login'" @click="mode = 'login'">{{ $t('common.login') }}</button>
          <button type="button" role="tab" :class="{ active: mode === 'register' }" :aria-selected="mode === 'register'" @click="mode = 'register'">{{ $t('auth.quick_register.tab') }}</button>
        </div>
        <p v-if="reason" class="login-reason"><i class="fa fa-info-circle" aria-hidden="true"></i> {{ reason }}</p>

        <div class="social-row mb-3 d-flex gap-2 justify-content-center" v-if="social_available.google || social_available.facebook">
          <button type="button" class="btn btn-social btn-google-outline" @click="social_login('google')" v-if="social_available.google">
            <i class="fa fa-google" aria-hidden="true"></i> Google
          </button>
          <button type="button" class="btn btn-social btn-facebook-outline" @click="social_login('facebook')" v-if="social_available.facebook">
            <i class="fa fa-facebook" aria-hidden="true"></i> Facebook
          </button>
        </div>

        <div class="divider-text"><span>or</span></div>

        <!-- quick registration: only what's needed to start; the rest is asked in the welcome questions / profile -->
        <form v-if="mode === 'register'" @submit.prevent="register" class="mt-3" novalidate>
          <div class="row g-2">
            <div class="col-6">
              <input type="text" class="form-control" v-model="reg.name" :placeholder="$t('auth.quick_register.name')" autocomplete="given-name" maxlength="25" required />
            </div>
            <div class="col-6">
              <input type="text" class="form-control" v-model="reg.surname" :placeholder="$t('auth.quick_register.surname')" autocomplete="family-name" maxlength="55" required />
            </div>
          </div>
          <input type="email" class="form-control mt-2" v-model="reg.email" :placeholder="$t('auth.quick_register.email')" autocomplete="email" required />
          <div class="row g-2 mt-0">
            <div class="col-6">
              <input type="text" class="form-control" v-model="reg.country" :placeholder="$t('auth.quick_register.country')" autocomplete="country-name" maxlength="255" required />
            </div>
            <div class="col-6">
              <input type="text" class="form-control" v-model="reg.city" :placeholder="$t('auth.quick_register.city')" autocomplete="address-level2" maxlength="255" required />
            </div>
          </div>
          <input type="tel" class="form-control mt-2" v-model="reg.phone_number" :placeholder="$t('auth.quick_register.phone')" autocomplete="tel" maxlength="255" required />
          <div class="mt-2" style="position: relative;">
            <input :type="showPassword ? 'text' : 'password'" class="form-control" style="padding-right: 42px;" v-model="reg.password" :placeholder="$t('auth.quick_register.password')" autocomplete="new-password" minlength="8" required />
            <button type="button" class="pwd-toggle-btn" tabindex="-1" @click="showPassword = !showPassword" :title="showPassword ? 'Hide password' : 'Show password'">
              <i :class="showPassword ? 'fa fa-eye-slash' : 'fa fa-eye'"></i>
            </button>
          </div>
          <div class="pwd-meter" :class="'is-' + password_strength" aria-hidden="true"><span></span></div>
          <small class="text-muted">{{ $t('auth.quick_register.password_hint') }}</small>
          <input :type="showPassword ? 'text' : 'password'" class="form-control mt-2" v-model="reg.password_confirmation" :placeholder="$t('auth.quick_register.password_confirmation')" autocomplete="new-password" minlength="8" required />
          <small class="text-danger" v-if="password_mismatch">{{ $t('auth.quick_register.password_mismatch') }}</small>

          <label class="form-check mt-2 login-terms">
            <input type="checkbox" class="form-check-input" v-model="reg.accept" />
            <span class="form-check-label">
              {{ $t('auth.quick_register.accept_1') }}
              <a :href="legal_url('terms_of_use')" target="_blank" rel="noopener">{{ $t('global.footer.terms_of_use') }}</a>
              {{ $t('auth.quick_register.accept_2') }}
              <a :href="legal_url('privacy_policy')" target="_blank" rel="noopener">{{ $t('global.footer.privacy_policy') }}</a>
            </span>
          </label>

          <div class="alert alert-danger mt-2 mb-0 py-2" v-if="auth_error">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{ auth_error }}
          </div>

          <button type="submit" class="btn btn-hold w-100 mt-3" :disabled="!can_register">
            <i class="fa fa-user-plus" aria-hidden="true"></i> {{ $t('auth.quick_register.submit') }}
          </button>
        </form>

        <form v-else @submit.prevent="login" class="mt-3">
          <div class="form-group">
            <input
              type="email"
              class="form-control"
              v-model="email"
              placeholder="Email address"
              autocomplete="email"
              required
            />
          </div>
          <div class="form-group mt-2">
            <div style="position: relative;">
              <input
                :type="showPassword ? 'text' : 'password'"
                class="form-control"
                style="padding-right: 42px;"
                v-model="password"
                placeholder="Password"
                autocomplete="current-password"
                required
              />
              <button
                type="button"
                class="pwd-toggle-btn"
                tabindex="-1"
                @click="showPassword = !showPassword"
                :title="showPassword ? 'Hide password' : 'Show password'"
              >
                <i :class="showPassword ? 'fa fa-eye-slash' : 'fa fa-eye'"></i>
              </button>
            </div>
          </div>

          <div class="alert alert-danger mt-2 mb-0 py-2" v-if="auth_error">
            <i class="fa fa-exclamation-circle" aria-hidden="true"></i> {{ auth_error }}
          </div>

          <button type="submit" class="btn btn-primary w-100 mt-3">
            <i class="fa fa-sign-in" aria-hidden="true"></i> {{ $t('common.login') || 'Login' }}
          </button>
        </form>
      </div>

    </div>
  </stack-modal>
</template>

<script>
import { when_idle } from '../../../services/idle.js'

let JSEncrypt = null;

async function loadJSEncrypt() {
    if (typeof JSEncrypt === 'function') return JSEncrypt;
    try {
        const module = await import('jsencrypt');
        JSEncrypt = module.default || module.JSEncrypt || module;
        if (typeof JSEncrypt === 'function') return JSEncrypt;
    } catch (e) {}
    return new Promise((resolve, reject) => {
        if (window.JSEncrypt) { JSEncrypt = window.JSEncrypt; resolve(JSEncrypt); return; }
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jsencrypt/3.5.4/jsencrypt.min.js';
        script.onload = () => { JSEncrypt = window.JSEncrypt; resolve(JSEncrypt); };
        script.onerror = () => reject(new Error('Failed to load JSEncrypt'));
        document.head.appendChild(script);
    });
}

const PUBLIC_KEY = `-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuydxIbJIeJhsmra7QKZa
eXo12J/17Q5Yah+K0hFP7BWvvnDZ1D3IZ0GvIh2PhZLvBc/wzXMK3gyH3qUCuqmh
rG9+4pg2OMFtD9fbNhFP2cIEL+B4qIFVh589JLBs6uduHHloofKElIzBlN7sxHfF
R1fNag6AbqDJfB/aXW0XpK8oABDblGO44/m64Kh6OpWvolxEfC+Mnhs+SXIdj3rn
R39id5+axL6sdWnXpW5uMRqy633JuKiGamvVkEk+BzzWqMMVoGLvKRJR67w52DG9
jfcB6GWPL237h6UE9vcCGfIdHOk9l3nErU5N9s8Q1taebwsMDgLe2FrOtM+FmkfH
2wIDAQAB
-----END PUBLIC KEY-----`;

export default {
    emits: ['logged-in', 'close'],
    data() {
        return {
            is_show_modal: false,
            is_loading: false,
            mode: 'login',
            reason: '',
            reg: { name: '', surname: '', email: '', country: '', city: '', phone_number: '', password: '', password_confirmation: '', accept: false },
            email: '',
            password: '',
            showPassword: false,
            auth_error: '',
            after_login_callback: null,

            social_available: {
                google: false,
                facebook: false,
            },
        };
    },
    created() {
        // encryption lib + reCAPTCHA are only needed to submit: fetch them
        // when the browser is idle (or right away when the modal opens)
        when_idle(() => this.load_assets())
        axios.get('login/status')
            .then(response => {
                this.social_available = response.data
            })
            .catch(() => {});
        this.$bus.$on('open-login-modal', (callback) => {
            this.show_modal(callback || null)
        })
        // payload: { callback, reason } — e.g. the partner board. Opens on the
        // Log in tab like every other entry point; Sign up is one click away.
        this.$bus.$on('open-register-modal', (payload) => {
            const p = payload || {}
            this.show_modal(p.callback || null, 'login', p.reason || '')
        })
    },
    computed: {
        password_strength() {
            const p = this.reg.password || ''
            if (p.length < 8) return 'weak'
            const kinds = [/[a-z]/, /[A-Z]/, /\d/, /[^\w]/].filter(r => r.test(p)).length
            return kinds >= 3 && p.length >= 10 ? 'strong' : 'ok'
        },
        // shown once the confirmation is typed, not while it's still empty
        password_mismatch() {
            return this.reg.password_confirmation !== '' && this.reg.password_confirmation !== this.reg.password
        },
        can_register() {
            const r = this.reg
            return r.name.trim() && r.surname.trim() && /\S+@\S+\.\S+/.test(r.email)
                && r.country.trim() && r.city.trim() && r.phone_number.trim()
                && r.password.length >= 8 && r.password_confirmation === r.password && r.accept
        },
    },
    methods: {
        legal_url(page) {
            const prefix = this.$i18n && this.$i18n.locale === 'ka' ? '/ka' : ''
            return (process.env.MIX_APP_SSH || 'http://') + process.env.MIX_SITE_URL + prefix + '/' + page
        },
        async register() {
            if (!this.can_register) return
            this.auth_error = ''
            this.is_loading = true
            try {
                const recaptcha_token = await this.get_recaptcha_token('register')
                if (process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY && !recaptcha_token) {
                    this.auth_error = 'reCAPTCHA verification failed. Please refresh and try again.'
                    return
                }
                await axios.get(window.location.origin + '/sanctum/csrf-cookie')
                const res = await axios.post('register', {
                    name: this.reg.name.trim(),
                    surname: this.reg.surname.trim(),
                    email: this.reg.email.trim(),
                    country: this.reg.country.trim(),
                    city: this.reg.city.trim(),
                    phone_number: this.reg.phone_number.trim(),
                    password: this.reg.password,
                    password_confirmation: this.reg.password_confirmation,
                    lang: localStorage.getItem('lang') || 'us',
                    recaptcha_token,
                })
                if (res.data && res.data.token) localStorage.setItem('auth_token', res.data.token)
                const xsrf = res.config.headers['X-XSRF-TOKEN'] || (document.cookie.match(/XSRF-TOKEN=([^;]+)/) || [])[1]
                localStorage.setItem('x_xsrf_token', xsrf || '1')
                // the welcome questions (photo, interests, defaults) open right after sign-up on this site
                try { sessionStorage.setItem('cg_just_registered', '1') } catch (e) {}
                this.$bus.$emit('toast', { type: 'success', message: this.$t('auth.quick_register.done'), duration: 6000 })
                this.$emit('logged-in')
                this.$bus.$emit('logged-in')
                this.$bus.$emit('registered')
                if (this.after_login_callback) this.after_login_callback()
                this.reg = { name: '', surname: '', email: '', country: '', city: '', phone_number: '', password: '', password_confirmation: '', accept: false }
                this.close_modal()
            } catch (error) {
                const data = error.response && error.response.data
                const first = data && data.errors && Object.values(data.errors)[0]
                this.auth_error = (first && first[0]) || (data && data.message) || this.$t('auth.quick_register.error')
            } finally {
                this.is_loading = false
            }
        },
        load_assets() {
            loadJSEncrypt().catch(() => {})
            if (!window.grecaptcha && !document.querySelector('script[src*="recaptcha/api.js"]')) {
                const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY
                if (key) {
                    const s = document.createElement('script')
                    s.src = `https://www.google.com/recaptcha/api.js?render=${key}`
                    document.head.appendChild(s)
                }
            }
        },
        show_modal(callback = null, mode = 'login', reason = '') {
            this.load_assets()
            this.after_login_callback = callback
            this.mode = mode
            this.reason = reason
            const token = localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token')
            if (token) {
                if (callback) callback()
                return
            }
            this.auth_error = ''
            this.email = ''
            this.password = ''
            this.showPassword = false
            this.is_show_modal = true
        },
        close_modal() {
            this.is_show_modal = false
            this.auth_error = ''
            this.$emit('close')
            this.$bus.$emit('login-modal-closed')
        },
        social_login(service) {
            this.is_loading = true
            this.auth_error = ''
            axios.get('login/' + service)
                .then(response => {
                    if (response.data && response.data.url) {
                        window.location.href = response.data.url
                    } else {
                        this.auth_error = 'Could not initiate social login. Please try again.'
                        this.is_loading = false
                    }
                })
                .catch(() => {
                    this.auth_error = 'Social login failed. Please try again.'
                    this.is_loading = false
                })
        },
        async get_recaptcha_token(action = 'login') {
            const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY
            if (!key || !window.grecaptcha) return null
            try {
                // grecaptcha.ready() never calls back if the script was blocked
                // (ad-blockers/privacy extensions) or never finished loading.
                // Racing against a timeout guarantees this always resolves
                // instead of hanging the login button forever.
                const ready = new Promise(resolve => window.grecaptcha.ready(resolve))
                const timeout = new Promise((_, reject) => setTimeout(() => reject(new Error('reCAPTCHA timed out')), 8000))
                await Promise.race([ready, timeout])
                return await window.grecaptcha.execute(key, { action })
            } catch (e) {
                console.error('reCAPTCHA error:', e)
                return null
            }
        },
        async login() {
            if (!this.email || !this.password) return
            this.auth_error = ''
            this.is_loading = true
            try {
                // The backend rejects every login with "reCAPTCHA verification
                // failed" whenever GOOGLE_CAPTCHA_V3_SECRET_KEY is configured and
                // no valid recaptcha_token is sent — this modal never sent one.
                const recaptcha_token = await this.get_recaptcha_token('login')
                if (!recaptcha_token) {
                    this.auth_error = 'reCAPTCHA verification failed. Please refresh and try again.'
                    this.is_loading = false
                    return
                }

                if (!JSEncrypt) await loadJSEncrypt()
                const encryptor = new JSEncrypt()
                encryptor.setPublicKey(PUBLIC_KEY)
                const encryptedPassword = encryptor.encrypt(this.password)
                if (!encryptedPassword) {
                    this.auth_error = 'Encryption failed. Please refresh and try again.'
                    this.is_loading = false
                    return
                }
                await axios.get(window.location.origin + '/sanctum/csrf-cookie')
                const response = await axios.post('login', {
                    email: this.email,
                    password: encryptedPassword,
                    remember: false,
                    recaptcha_token,
                })
                localStorage.setItem('x_xsrf_token', response.config.headers['X-XSRF-TOKEN'])
                if (response.data && response.data.token) {
                    localStorage.setItem('auth_token', response.data.token)
                }
                // Reload permissions
                try {
                    const permRes = await axios.get('get_user/get_auth_user_permissions/')
                    if (this.$ability) this.$ability.update(permRes.data)
                    this.$bus.$emit('permissions-loaded', permRes.data)
                } catch (e) {}

                this.$emit('logged-in')
                this.$bus.$emit('logged-in')
                if (this.after_login_callback) this.after_login_callback()
                this.close_modal()
            } catch (error) {
                const data = error.response && error.response.data
                if (data && data.message === 'auth.failed') {
                    this.auth_error = 'Email or password is incorrect.'
                } else if (data && data.message) {
                    // Surfaces recaptcha failures, validation errors, etc.
                    // instead of always masking every 422 as bad credentials.
                    this.auth_error = data.message
                } else if (error.response && error.response.status === 422) {
                    this.auth_error = 'Email or password is incorrect.'
                } else {
                    this.auth_error = 'Login failed. Please try again.'
                }
            } finally {
                this.is_loading = false
            }
        },
    },
}
</script>

<style scoped>
.login-reason { margin: 0 0 .75rem; padding: .5rem .7rem; border-radius: var(--cg-r); background: var(--cg-accent-soft); color: var(--cg-accent-dark); font-weight: 700; font-size: .88rem; }
.login-terms { display: flex; gap: .5rem; font-size: .85rem; }
.login-terms .form-check-input { flex: 0 0 auto; margin-top: .2rem; }
.pwd-meter { height: 4px; margin: .4rem 0 .2rem; border-radius: 4px; background: var(--cg-chalk-2); overflow: hidden; }
.pwd-meter span { display: block; height: 100%; width: 15%; background: #c43c3c; transition: width .2s, background .2s; }
.pwd-meter.is-ok span { width: 60%; background: #e0a800; }
.pwd-meter.is-strong span { width: 100%; background: #2fa36b; }
</style>
