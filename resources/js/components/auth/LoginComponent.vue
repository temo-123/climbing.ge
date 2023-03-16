<template>
  <div class="login mt-5">
    <div class="card">
      <div class="card-header">
        <h1>Login</h1>
      </div>
      <div class="row mt-2">
          <div class="col-6 text-center">
            <button type="button" class="btn btn-danger" @click="social_login('google')">
              <!-- Google -->
              <i class="fa fa-google" aria-hidden="true"></i>
            </button>
          </div>
          <div class="col-6 text-center">
            <button type="button" class="btn btn-primary" @click="social_login('facebook')">
              <!-- Facebook -->
              <i class="fa fa-facebook" aria-hidden="true"></i>
            </button>
          </div>
      </div>
      <div class="card-body">
        <form id="login_form" v-on:submit.prevent="login">
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
            <div class="alert alert-danger" role="alert" v-if="error.email">
              Plees insert Email (Email required)
            </div>
          </div>

          <div class="form-group">
            <label for="password">Password</label>
              (
                <router-link :to="{name: 'forget_pass'}">
                  Return password
                </router-link>
              )
            <input
              type="password"
              class="form-control"
              id="password"
              v-model="password"
              placeholder="Password"
              autocomplete="on"
              required
            />
            <div class="alert alert-danger" role="alert" v-if="error.password">
              Plees insert password (password required)
            </div>
          </div>
          <div class="form-group">
            <input type="checkbox" v-model="remember" name="One time code" value="One time code">
            Remember me
          </div>
          <div class="alert alert-danger" role="alert" v-if="auth_error">
            Password or email is not correct
          </div>
          <button type="button" @click.prevent="login" class="btn btn-primary">
            Login
          </button>
        </form>
        <hr>
        <router-link :to="{name: 'register'}" exact class="btn btn-success mt-2">
          Register
        </router-link>
      </div>
    </div>
  </div>
</template>

<script>
  export default {
    name: "Login",
    data: function() {
      return {
        email: null,
        password: null,
        remember: false,
        
        error: [],
        auth_error: '',

        MIX_USER_PAGE_URL: process.env.MIX_USER_PAGE_URL,
        MIX_APP_SSH: process.env.MIX_APP_SSH,
        MIX_BASE_URL_SSH: process.env.MIX_BASE_URL_SSH
      };
    },
    mounted() {
    },
    // created() {
    //     this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    // },
    methods: {
      social_login(service){
        // window.location.href = this.MIX_APP_SSH + this.MIX_USER_PAGE_URL + '/api/login/' + service

        axios.get('../../../api/login/' + service)
        .then(response => {
          console.log(response.data)
          if(response.data.url){
            window.location.href = response.data.url
          }
        }).catch(err => {
          console.log(err.data)
        })
      },

      login(){
        axios
          .get('/sanctum/csrf-cookie')
          .then(response => {
            this.login_action()
          }).catch(err => {
            window.location.reload()
          }); 
      },

      login_action(){
          this.error = []
          this.auth_error = ''
          axios
            .post(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/login', {
              email: this.email, 
              password: this.password,
              remember: this.remember
            })
            .then((response) => {
              localStorage.setItem('x_xsrf_token', response.config.headers['X-XSRF-TOKEN'])

              this.$router.push({ name: "home" });
            })
            .catch((error) => {
              if(error.response.status === 422) {
                if(error.response.data.message == 'auth.failed'){
                  this.auth_error = 'Email or password is not corect'
                }
                else{
                  this.error = error.response.data.errors
                }
              }
            })
      }
    }
  };
</script>