<template>
    <div class="login mt-5">
      <div class="card">
        <div class="card-header">
          <h1>Reset Password</h1>
        </div>
        <div class="card-body" v-if="!is_pass_updeating_loader">
          <form id="reset_password" v-on:submit.prevent="on_submit">
            
            <div class="form-group">
              <label for="password">Password *</label>
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
              <label for="password_confirmation">Confirm password *</label>
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
  
            <button type="submit" form="reset_password" class="btn btn-warning">
              Save new Password
            </button>
          </form>
        </div>

        <div class="row justify-content-center" v-else-if="is_pass_updeating_loader">
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
          data: {
            password: null,
            password_confirmation: null,

            token: this.$route.params.token,
            id: this.$route.params.user_id,
          },

          error: '',
  
          MIX_USER_PAGE_URL: process.env.MIX_USER_PAGE_URL,
          MIX_APP_SSH: process.env.MIX_APP_SSH,
          MIX_BASE_URL_SSH: process.env.MIX_BASE_URL_SSH,

          is_pass_updeating_loader: false,
          is_back_action: false
        };
      },
      mounted() {
      },
      beforeRouteLeave (to, from, next) {
          if(this.is_back_action){
              next()
          }
          else if (window.confirm('Are you sure, you want go back?')) {
              next()
          } 
          else {
              next(false)
          }
      },
      methods: {
        on_submit(){
          this.is_pass_updeating_loader = true
          axios
          .post('../../api/password/reset_password', { data: this.data })
          .then(response => {
            alert('New password saved successfully! You while returned on login page.')
            this.is_back_action = true
            this.$router.push({ name: "login" }); 
          })
          .catch((error) => {
            if(error.response.status == 422){
              this.error = error.response.data.message
            }
            else{
              this.error = error.response.data.message
            }
          })
          .finally(() => this.is_pass_updeating_loader = false)
        },
      }
    };
  </script>