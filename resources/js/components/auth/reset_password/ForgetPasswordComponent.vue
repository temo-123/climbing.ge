<template>
    <div class="login mt-5">
      <div class="card">
        <div class="card-header">
          <h1>Reset Password</h1>
        </div>
        <div class="card-body" v-if="!is_mail_sending">
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
              <div class="alert alert-danger" role="alert" v-if="error.length">
                {{ error }}
              </div>
            </div>
  
            <button type="submit" form="reset_password" class="btn btn-warning">
              Send mail for reset Password
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

        <div class="row justify-content-center" v-else-if="is_mail_sending">
          <div class="col-md-4">
              <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
              <p>Pless wait!</p>
          </div>
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
  
          MIX_USER_PAGE_URL: process.env.MIX_USER_PAGE_URL,
          MIX_APP_SSH: process.env.MIX_APP_SSH,
          MIX_BASE_URL_SSH: process.env.MIX_BASE_URL_SSH,

          is_mail_sending: false
        };
      },
      mounted() {
      },
      methods: {
        on_submit(){
          this.is_mail_sending = true
          axios
          .post('../../api/password/send_forget_mail', { email: this.email })
          .then(response => {
            alert('We send message on inserting email -> "' + this.email + '", Please check this email for finishing password resetting. You while returned on login page.')
            this.$router.push({ path: "login" }); 
          })
          .catch((error) => {
            if(error.response.status == 422){
              this.error = error.response.data.message
            }
            else{
              this.error = error.response.data.message
            }
          })
          .finally(() => this.is_mail_sending = false)
        },
      }
    };
  </script>