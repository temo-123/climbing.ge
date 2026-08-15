<template>
  <div class="verify text-center mt-5">
    <div v-if="status === 'loading'">
      <h2>Verifying your email...</h2>
      <img :src="'/images/site_img/loading.gif'" alt="loading">
    </div>

    <div v-else-if="status === 'success'" class="card mx-auto" style="max-width: 480px;">
      <div class="card-body">
        <i class="fa fa-check-circle text-success" style="font-size: 3em;"></i>
        <h3 class="mt-3">Your email is verified!</h3>
        <p class="text-muted">All set — your account is fully active now.</p>
        <router-link v-if="isLoggedInHere" :to="{ name: 'home' }" class="btn btn-success mt-2">Go to dashboard</router-link>
        <router-link v-else :to="{ name: 'login' }" class="btn btn-success mt-2">Log in to continue</router-link>
      </div>
    </div>

    <div v-else-if="status === 'already'" class="card mx-auto" style="max-width: 480px;">
      <div class="card-body">
        <i class="fa fa-info-circle text-info" style="font-size: 3em;"></i>
        <h3 class="mt-3">Already verified</h3>
        <p class="text-muted">This email was already confirmed — nothing more to do here.</p>
        <router-link v-if="isLoggedInHere" :to="{ name: 'home' }" class="btn btn-primary mt-2">Go to dashboard</router-link>
        <router-link v-else :to="{ name: 'login' }" class="btn btn-primary mt-2">Log in to continue</router-link>
      </div>
    </div>

    <div v-else-if="status === 'error'" class="card mx-auto" style="max-width: 480px;">
      <div class="card-body">
        <i class="fa fa-times-circle text-danger" style="font-size: 3em;"></i>
        <h3 class="mt-3">Verification failed</h3>
        <p class="text-muted">{{ errorMessage }}</p>
        <button class="btn btn-warning mt-2" @click="resend" :disabled="resending">
          {{ resending ? 'Sending...' : 'Resend verification email' }}
        </button>
        <router-link :to="{ name: 'login' }" class="btn btn-secondary mt-2 ml-2">Back to login</router-link>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      status: 'loading',
      errorMessage: 'The verification link is invalid or has expired.',
      resending: false,
      isLoggedInHere: !!(localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token')),
    };
  },
  mounted() {
    document.querySelector('body').style.marginLeft = '0';
    const navbar = document.querySelector('.admin_page_header_navbar');
    if (navbar) navbar.style.marginLeft = '0';

    this.verify();
  },
  methods: {
    verify() {
      axios.get('email/verify/' + this.$route.params.user_id + '/' + this.$route.params.hash)
        .then(response => {
          if (response.data.message === 'Email already verified') {
            this.status = 'already';
          } else {
            this.status = 'success';
          }
          // Same device that's already signed in (e.g. verifying right after
          // registration) — take them straight in instead of showing a login prompt.
          if (this.isLoggedInHere) {
            this.$router.push({ name: 'home' });
          }
        })
        .catch(error => {
          this.status = 'error';
          if (error.response && error.response.data && error.response.data.message) {
            this.errorMessage = error.response.data.message;
          }
        });
    },
    resend() {
      this.resending = true;
      axios.get('email/resend')
        .then(() => {
          alert('Verification email sent! Please check your inbox.');
        })
        .catch(() => {
          alert('Could not send verification email. Please log in and try again.');
        })
        .finally(() => {
          this.resending = false;
        });
    },
  },
};
</script>
