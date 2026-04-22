<template>
    <div class="login mt-5">
      <div class="card">
        <div class="card-header">
          <h1>Reset Password</h1>
        </div>

        <div class="card-body" v-if="!is_pass_updeating_loader && !success">
          <form id="reset_password" v-on:submit.prevent="on_submit">
            <div class="form-group">
              <label for="password">New Password *</label>
              <input
                type="password"
                class="form-control"
                id="password"
                v-model="data.password"
                placeholder="Password"
                minlength="8"
                required
              />
            </div>
            <div class="form-group">
              <label for="password_confirmation">Confirm Password *</label>
              <input
                type="password"
                class="form-control"
                id="password_confirmation"
                v-model="data.password_confirmation"
                placeholder="Confirm password"
                minlength="8"
                required
              />
            </div>
            <div class="alert alert-danger mt-2" role="alert" v-if="error">
              {{ error }}
            </div>

            <button type="submit" form="reset_password" class="btn btn-warning">
              Save new Password
            </button>
          </form>
        </div>

        <div class="card-body" v-else-if="success">
          <div class="alert alert-success" role="alert">
            Your password has been saved successfully.
          </div>
          <router-link :to="{name: 'login'}" exact class="btn btn-primary mt-2">
            Go to Login
          </router-link>
        </div>

        <div class="card-body text-center" v-else-if="is_pass_updeating_loader">
          <p>Please wait...</p>
        </div>

      </div>
    </div>
  </template>

  <script>
    export default {
      name: "Reset password page",
      data: function() {
        return {
          data: {
            password: null,
            password_confirmation: null,
            token: this.$route.params.token,
            id: this.$route.params.user_id,
          },
          error: '',
          success: false,
          is_pass_updeating_loader: false,
        };
      },
      beforeRouteLeave(to, from, next) {
        if (this.success) {
          next()
        } else if (window.confirm('Are you sure you want to go back?')) {
          next()
        } else {
          next(false)
        }
      },
      methods: {
        on_submit(){
          this.is_pass_updeating_loader = true
          this.error = ''
          axios
          .post('password/reset_password', { data: this.data })
          .then(() => {
            this.success = true
          })
          .catch((error) => {
            this.error = error.response?.data?.message || 'Something went wrong. Please try again.'
          })
          .finally(() => this.is_pass_updeating_loader = false)
        },
      }
    };
  </script>
