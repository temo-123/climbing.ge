<template>
  <div class="login mt-5">
    <div class="card">
      <div class="card-header">
        <h1>Login</h1>
      </div>
      <div class="row justify-content-center" v-show="is_loading">
          <div class="col-md-4">
              <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
          </div>
      </div>
      <div class="row mt-2" v-show="!is_loading">
          <div class="col-12 text-center">
            <button type="button" class="btn btn-danger" @click="social_login('google')">
              <i class="fa fa-google" aria-hidden="true"></i> Login with Google
            </button>
          </div>
          <!-- <div class="col-6 text-center">
            <button type="button" class="btn btn-primary" @click="social_login('facebook')">
              <i class="fa fa-facebook" aria-hidden="true"></i>
            </button>
          </div> -->
      </div>
      <div class="card-body" v-show="!is_loading">
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
            Invalid credentials
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
  import JSEncrypt from 'jsencrypt'

  export default {
    name: "Login",
    data: function() {
      return {
        email: null,
        password: null,
        remember: false,

        is_loading: false,
        
        error: [],
        auth_error: '',

        MIX_USER_PAGE_URL: process.env.MIX_USER_PAGE_URL,
        MIX_APP_SSH: process.env.MIX_APP_SSH,
        MIX_BASE_URL_SSH: process.env.MIX_BASE_URL_SSH,
        
        // RSA Public Key for password encryption
        publicKey: `-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuydxIbJIeJhsmra7QKZa
eXo12J/17Q5Yah+K0hFP7BWvvnDZ1D3IZ0GvIh2PhZLvBc/wzXMK3gyH3qUCuqmh
rG9+4pg2OMFtD9fbNhFP2cIEL+B4qIFVh589JLBs6uduHHloofKElIzBlN7sxHfF
R1fNag6AbqDJfB/aXW0XpK8oABDblGO44/m64Kh6OpWvolxEfC+Mnhs+SXIdj3rn
R39id5+axL6sdWnXpW5uMRqy633JuKiGamvVkEk+BzzWqMMVoGLvKRJR67w52DG9
jfcB6GWPL237h6UE9vcCGfIdHOk9l3nErU5N9s8Q1taebwsMDgLe2FrOtM+FmkfH
2wIDAQAB
-----END PUBLIC KEY-----`
      };
    },
    mounted() {
      document.querySelector('body').style.marginLeft = '0';
      document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
    },
    methods: {
      social_login(service){
        this.is_loading = true
        
        axios.get('/login/' + service)
        .then(response => {
          console.log(response.data)
          if(response.data.url){
            window.location.href = response.data.url
          }
        }).catch(err => {
          console.log(err.data)
        })
        .finally(() => this.is_loading = false);
      },

      login(){
        this.is_loading = true
        axios
          .get('../../sanctum/csrf-cookie')
          .then(response => {
            this.login_action()
          }).catch(error => {
            alert(error)
            window.location.reload()
          })
          .finally(() => this.is_loading = false);
      },

      login_action(){
          this.error = []
          this.auth_error = ''
          this.is_loading = true

          // Encrypt password before sending
          const encrypt = new JSEncrypt()
          encrypt.setPublicKey(this.publicKey)
          const encryptedPassword = encrypt.encrypt(this.password)

          if (!encryptedPassword) {
            this.auth_error = 'Encryption failed'
            this.is_loading = false
            return
          }

          axios
            .post(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/api/login', {
              email: this.email, 
              password: encryptedPassword, // Send encrypted password
              remember: this.remember
            })
            .then((response) => {
              localStorage.setItem('auth_token', response.data.token)
              localStorage.setItem('x_xsrf_token', response.config.headers['X-XSRF-TOKEN'])
              this.$router.push({ name: "home" });
            })
            .catch((error) => {
              if(error.response && error.response.status === 422) {
                if(error.response.data.message == 'auth.failed'){
                  this.auth_error = 'Email or password is not corect'
                }
                else{
                  this.error = error.response.data.errors
                }
              } else if (error.response && error.response.data && error.response.data.message) {
                this.auth_error = error.response.data.message
              }
            })
            .finally(() => this.is_loading = false);
      }
    }
  };
</script>
