<template>
  <div class="login mt-5">
    <router-link :to="{name: 'login'}" exact class="btn btn-success" v-if="!is_loading">
      Login
    </router-link>

    <hr v-if="!is_loading">

    <div class="card">

      <div class="card-header">
        Register
      </div>

      <div class="card-body" v-if="!is_loading">

        <div class="alert alert-success" v-if="success_message">
          {{ success_message }}
        </div>

        <form id="register_form" v-on:submit.prevent="register">
          <div class="form-group">
            <label for="name">Name</label>
            <input type="text" class="form-control" id="name" v-model="name" placeholder="Enter name" required />
            <div class="invalid-feedback d-block" v-if="error.name">{{ error.name[0] }}</div>
          </div>
          <div class="form-group">
            <label for="surname">Surname</label>
            <input type="text" class="form-control" id="surname" v-model="surname" placeholder="Enter surname" required />
            <div class="invalid-feedback d-block" v-if="error.surname">{{ error.surname[0] }}</div>
          </div>
          <div class="form-group">
            <label for="email">Email address</label>
            <input type="email" class="form-control" id="email" v-model="email" placeholder="Enter email" required />
            <div class="invalid-feedback d-block" v-if="error.email">{{ error.email[0] }}</div>
          </div>
          <div class="form-group">
            <label for="country">Country</label>
            <input type="text" class="form-control" id="country" v-model="country" placeholder="Enter country" required />
            <div class="invalid-feedback d-block" v-if="error.country">{{ error.country[0] }}</div>
          </div>
          <div class="form-group">
            <label for="city">City</label>
            <input type="text" class="form-control" id="city" v-model="city" placeholder="Enter city" required />
            <div class="invalid-feedback d-block" v-if="error.city">{{ error.city[0] }}</div>
          </div>
          <div class="form-group">
            <label for="phone_number">Phone number</label>
            <input type="tel" class="form-control" id="phone_number" v-model="phone_number" placeholder="Enter phone number" required />
            <div class="invalid-feedback d-block" v-if="error.phone_number">{{ error.phone_number[0] }}</div>
          </div>
          <div class="form-group">
            <label for="password">Password *</label>
            <input type="password" class="form-control" id="password" v-model="password" placeholder="Password" minlength="8" required />
            <div class="invalid-feedback d-block" v-if="error.password">{{ error.password[0] }}</div>
          </div>
          <div class="form-group">
            <label for="password_confirmation">Confirm password *</label>
            <input type="password" class="form-control" id="password_confirmation" v-model="password_confirmation" placeholder="Confirm password" minlength="8" required />
          </div>
          <div class="form-group">
            <input type="checkbox" v-model="terms_of_use" name="terms_of_use" value="1">
            I agree with the <span class="cursor_pointer text-warning" @click="open_term_of_use_modal()">terms of use</span>
          </div>

          <div class="alert alert-warning" v-if="captcha_error">
            <i class="fa fa-exclamation-triangle"></i>
            reCAPTCHA failed to load. Please reload the page and try again.
          </div>

          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <button
                  type="submit"
                  class="btn btn-default btn-send main-btn"
                  form="register_form"
                  :disabled="!terms_of_use || captcha_error"
                >
                  Register
                </button>
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="row justify-content-center" v-else-if="is_loading">
        <div class="col-md-4">
          <img :src="'/images/site_img/loading.gif'" alt="loading">
          <p class="text-center">Please wait!</p>
        </div>
      </div>

    </div>

    <stack-modal
      :show="is_term_of_use_modal"
      title="Terms of use"
      @close="close_term_of_use_modal()"
      :modal-class="{ [ModalClass]: true }"
      :saveButton="{ visible: true }"
      :cancelButton="{
        title: 'Close',
        btnClass: { 'btn btn-primary': true },
      }"
    >
      <div class="model-body">
        <div class="container">
          <div class="row">
            <span v-html="this.$siteData.data.terms_of_use"></span>
          </div>
        </div>
      </div>
    </stack-modal>

  </div>
</template>

<script>
export default {
  name: 'Register page',
  data() {
    return {
      name: null,
      surname: null,
      email: null,
      phone_number: null,
      country: null,
      city: null,
      password: null,
      password_confirmation: null,

      is_loading: false,
      terms_of_use: false,
      error: {},
      success_message: null,
      captcha_error: false,
      is_term_of_use_modal: false,
    };
  },
  mounted() {
    document.querySelector('body').style.marginLeft = '0';
    document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    if (!window.grecaptcha) {
        const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY
        if (key) {
            const s = document.createElement('script')
            s.src = `https://www.google.com/recaptcha/api.js?render=${key}`
            document.head.appendChild(s)
        }
    }
  },
  methods: {
    open_term_of_use_modal() {
      this.is_term_of_use_modal = true;
    },
    close_term_of_use_modal() {
      this.is_term_of_use_modal = false;
    },
    async get_recaptcha_token(action = 'register') {
        const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY
        if (!key || !window.grecaptcha) return null
        try {
            await new Promise(resolve => window.grecaptcha.ready(resolve))
            return await window.grecaptcha.execute(key, { action })
        } catch { return null }
    },

    async register() {
      this.error = {};
      this.success_message = null;
      this.is_loading = true;

      const recaptcha_token = await this.get_recaptcha_token('register')
      if (!recaptcha_token) {
          this.captcha_error = true
          this.is_loading = false
          return
      }

      axios.get('/sanctum/csrf-cookie')
        .then(() => {
          axios.post('register', {
            name: this.name,
            surname: this.surname,
            email: this.email,
            phone_number: this.phone_number,
            country: this.country,
            city: this.city,
            password: this.password,
            password_confirmation: this.password_confirmation,
            recaptcha_token,
          })
          .then(res => {
            if (res.data.token) {
              localStorage.setItem('auth_token', res.data.token);
            }
            const xsrf = res.config.headers['X-XSRF-TOKEN'] || document.cookie.match(/XSRF-TOKEN=([^;]+)/)?.[1];
            localStorage.setItem('x_xsrf_token', xsrf || '1');

            this.success_message = res.data.message || 'Registration successful! Please check your email to verify your account.';
            this.$bus.$emit('logged-in');

            setTimeout(() => {
              this.$router.push({ name: 'home' });
            }, 2000);
          })
          .catch(error => {
            if (error.response?.status === 422) {
              if (error.response.data?.message?.toLowerCase().includes('recaptcha')) {
                this.captcha_error = true;
              } else {
                this.error = error.response.data.errors || {};
              }
            }
          })
          .finally(() => {
            this.is_loading = false;
          });
        })
        .catch(() => {
          this.is_loading = false;
        });
    },
  },
};
</script>
