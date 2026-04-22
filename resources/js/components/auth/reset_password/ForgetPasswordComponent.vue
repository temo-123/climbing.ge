<template>
    <div class="login mt-5">
      <div class="card">
        <div class="card-header">
          <h1>Reset Password</h1>
        </div>

        <div class="card-body" v-if="!is_mail_sending && !mail_sent">
          <form id="reset_password" v-on:submit.prevent="on_submit">
            <div class="form-group">
              <label for="email">Email address</label>
              <input
                type="email"
                class="form-control"
                id="email"
                v-model="email"
                placeholder="Enter email"
                autocomplete="on"
                required
              />
              <div class="alert alert-danger mt-2" role="alert" v-if="error.length">
                {{ error }}
              </div>
            </div>

            <button type="submit" form="reset_password" class="btn btn-warning">
              Send password reset email
            </button>
          </form>
          <hr>
          <router-link :to="{name: 'login'}" exact class="btn btn-primary mt-2">
            Login
          </router-link>
          <router-link :to="{name: 'register'}" exact class="btn btn-success mt-2">
            Register
          </router-link>
        </div>

        <div class="card-body" v-else-if="mail_sent">
          <div class="alert alert-success" role="alert">
            A password reset link has been sent to <strong>{{ email }}</strong>. Please check your inbox.
          </div>
          <router-link :to="{name: 'login'}" exact class="btn btn-primary mt-2">
            Back to Login
          </router-link>
        </div>

        <div class="card-body text-center" v-else-if="is_mail_sending">
          <p>Sending...</p>
        </div>

      </div>
    </div>
  </template>

  <script>
    export default {
      name: "Reset password page",
      data: function() {
        return {
          email: null,
          error: '',
          mail_sent: false,
          is_mail_sending: false
        };
      },
      methods: {
        on_submit(){
          this.is_mail_sending = true
          this.error = ''
          axios
          .post('password/send_forget_mail', { email: this.email })
          .then(() => {
            this.mail_sent = true
          })
          .catch((error) => {
            this.error = error.response?.data?.message || 'Something went wrong. Please try again.'
          })
          .finally(() => this.is_mail_sending = false)
        },
      }
    };
  </script>
