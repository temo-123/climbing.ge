<template>
  <StackModal
    v-model="is_show_modal"
    :title="modalTitle"
    size="lg"
    :saveButton="{ visible: false }"
    :cancelButton="{ visible: true, title: $t('global.close'), btnClass: { 'btn btn-secondary': true } }"
    @close="close_modal"
  >
    <!-- Social login buttons -->
    <div class="social-login-buttons mb-3">
      <div class="left_social_icon">
        <button type="button" class="btn btn-danger left_social_icon_button" @click="social_login('google')">
          <i class="fa fa-google" aria-hidden="true"></i> Google
        </button>
      </div>
    </div>

    <!-- Login form -->
    <form method="POST" id="login_form" @submit.prevent="login" class="login-form">
      <div class="form-group mb-3">
        <input
          type="email"
          class="form-control"
          id="email"
          v-model="email"
          placeholder="Enter email"
          autocomplete="on"
          required
        />
      </div>
      
      <div class="form-group mb-3">
        <input
          type="password"
          class="form-control"
          id="password"
          v-model="password"
          placeholder="Password"
          autocomplete="on"
        />
      </div>
    </form>

    <!-- Submit button -->
    <div class="text-center mt-4">
      <button
        type="submit"
        class="btn btn-primary"
        form="login_form"
      >
        {{ $t('global.login') }}
      </button>
    </div>
  </StackModal>
</template>

<script>
export default {
  data() {
    return {
      email: null,
      password: null,
      errors: [],
      email_errors: null,
      remember_me: null,
      is_show_modal: false,
      loading: false
    };
  },
  computed: {
    modalTitle() {
      return this.$t('global.login') || 'Login';
    }
  },
  methods: {
    social_login(service) {
      window.location.href = `/api/login/${service}`;
    },
    login() {
      axios
        .get('/sanctum/csrf-cookie')
        .then(response => {
          this.login_action()
        }); 
    },
    login_action() {
      axios
        .post(process.env.MIX_APP_SSH + process.env.MIX_SITE_URL + '/login', {
          email: this.email, 
          password: this.password
        })
        .then((res) => {
          if (this.email_errors) {
            this.email_errors = null
            this.errors = []
          }

          localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
          this.close_modal()
        })
    },
    close_modal() {
      this.is_show_modal = false
      this.email = null
      this.password = null
      this.clear_errors()
    },
    show_modal() {
      this.loading = true
      this.clear_errors()
      // Auto-open, no ID needed for login
      this.loading = false
      this.is_show_modal = true
    },
    clear_errors() {
      this.errors = []
      this.email_errors = null
    }
  },
  emits: ['close']
}
</script>

<style>
.left_social_icon{
  width: 49.5%;
  float: left;
  margin-top: 0%;
}
.left_social_icon_button{
  float: left;
  margin: 15%;
}

.right_social_icon{
  width: 49%;
  float: right;
  margin-top: -10%;
}
.right_social_icon_button{
  float: right;
  margin: 15%;
}
</style>
