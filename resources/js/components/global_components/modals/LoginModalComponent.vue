<template>
  <stack-modal
    :show="is_show_modal"
    :title="$t('global.login') || 'Login'"
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
        <div class="social-row mb-3 d-flex gap-2 justify-content-center">
          <button type="button" class="btn btn-social btn-google-outline" @click="social_login('google')">
            <i class="fa fa-google" aria-hidden="true"></i> Google
          </button>
          <button type="button" class="btn btn-social btn-facebook-outline" @click="social_login('facebook')">
            <i class="fa fa-facebook" aria-hidden="true"></i> Facebook
          </button>
        </div>

        <div class="divider-text"><span>or</span></div>

        <form @submit.prevent="login" class="mt-3">
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

          <button type="submit" class="btn btn-primary btn-block mt-3">
            <i class="fa fa-sign-in" aria-hidden="true"></i> {{ $t('global.login') || 'Login' }}
          </button>
        </form>
      </div>

    </div>
  </stack-modal>
</template>

<script>
let JSEncrypt = null;

async function loadJSEncrypt() {
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
            email: '',
            password: '',
            showPassword: false,
            auth_error: '',
            after_login_callback: null,
        };
    },
    async created() {
        try { await loadJSEncrypt(); } catch (e) {}
        this.$bus.$on('open-login-modal', (callback) => {
            this.show_modal(callback || null)
        })
    },
    methods: {
        show_modal(callback = null) {
            this.after_login_callback = callback
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
        async login() {
            if (!this.email || !this.password) return
            this.auth_error = ''
            this.is_loading = true
            try {
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
                if (error.response && error.response.status === 422) {
                    this.auth_error = 'Email or password is incorrect.'
                } else if (error.response && error.response.data && error.response.data.message) {
                    this.auth_error = error.response.data.message
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
