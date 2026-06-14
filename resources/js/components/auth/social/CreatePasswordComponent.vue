<template>
    <div class="login mt-5">
      <div class="card">
        <div class="card-header">
          <h1>Create Password</h1>
          <p>Please create a password so you can also log in with email in the future.</p>
        </div>

        <div class="card-body" v-if="!is_pass_updeating_loader && !success">
          <form id="reset_password" v-on:submit.prevent="on_submit">
            <div class="form-group">
              <label for="password">Password *</label>
              <input
                type="password"
                class="form-control"
                id="password"
                v-model="data.password"
                placeholder="Password (min 8 characters)"
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
              Create password
            </button>
          </form>
        </div>

        <div class="card-body" v-else-if="success">
          <div class="alert alert-success" role="alert">
            Password created successfully!
          </div>
          <router-link :to="{name: 'home'}" exact class="btn btn-primary mt-2">
            Continue to dashboard
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
      data: function() {
        return {
          data: {
            password: null,
            password_confirmation: null,
          },
          error: '',
          success: false,
          is_pass_updeating_loader: false,
        };
      },
      beforeRouteLeave(to, from, next) {
        if (this.success) {
          next()
        } else if (window.confirm('Are you sure you want to go back? You may not be able to log in to your account!')) {
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
          .post('login/social/create_password/' + this.$route.params.email, { data: this.data })
          .then((response) => {
            localStorage.setItem('auth_token', response.data.token)
            this.success = true
            setTimeout(() => { window.location.href = '/' }, 1500)
          })
          .catch((error) => {
            this.error = error.response?.data?.message || 'Something went wrong. Please try again.'
          })
          .finally(() => this.is_pass_updeating_loader = false)
        },
      }
    };
  </script>
