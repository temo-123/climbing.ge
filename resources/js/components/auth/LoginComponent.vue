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
// Dynamic import for JSEncrypt with CDN fallback for production environment
let JSEncrypt = null;

async function loadJSEncrypt() {
    // Try to load from npm package first (works in most cases)
    try {
        const module = await import('jsencrypt');
        JSEncrypt = module.default || module.JSEncrypt || module;
        if (typeof JSEncrypt === 'function') {
            return JSEncrypt;
        }
    } catch (e) {
        console.warn('JSEncrypt npm package not available, falling back to CDN');
    }
    
    // Fallback: Load from CDN for production environments
    return new Promise((resolve, reject) => {
        // Check if already loaded
        if (window.JSEncrypt) {
            JSEncrypt = window.JSEncrypt;
            resolve(JSEncrypt);
            return;
        }
        
        const script = document.createElement('script');
        script.src = 'https://cdnjs.cloudflare.com/ajax/libs/jsencrypt/3.5.4/jsencrypt.min.js';
        script.onload = () => {
            JSEncrypt = window.JSEncrypt;
            resolve(JSEncrypt);
        };
        script.onerror = () => {
            console.error('Failed to load JSEncrypt from CDN');
            reject(new Error('Failed to load JSEncrypt'));
        };
        document.head.appendChild(script);
    });
}

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
        JSEncryptLoaded: false,

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
    async mounted() {
      document.querySelector('body').style.marginLeft = '0';
      document.querySelector('.admin_page_header_navbar').style.marginLeft = '0';
      
      // Preload JSEncrypt to ensure it's available when needed
      try {
          await loadJSEncrypt();
          this.JSEncryptLoaded = true;
      } catch (e) {
          console.error('Failed to load JSEncrypt:', e);
          this.auth_error = 'Encryption library failed to load. Please refresh the page.';
      }
    },
    methods: {
      getJSEncrypt() {
          if (!this.JSEncryptLoaded || !JSEncrypt) {
              throw new Error('JSEncrypt not loaded');
          }
          return JSEncrypt;
      },
      
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

      async login(){
        this.is_loading = true
        axios
          .get(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/sanctum/csrf-cookie', {
            headers: {
              'Accept': 'application/json'
            }
          })
          .then(response => {
            this.login_action()
          }).catch(error => {
            console.error('CSRF cookie error:', error);
            this.auth_error = 'Failed to initialize login. Please try again.'
            this.is_loading = false
          });
      },

      async login_action(){
          this.error = []
          this.auth_error = ''
          this.is_loading = true

          try {
              // Ensure JSEncrypt is loaded
              if (!this.JSEncryptLoaded || !JSEncrypt) {
                  await loadJSEncrypt();
                  this.JSEncryptLoaded = true;
              }
              
              // Encrypt password before sending
              const EncryptClass = this.getJSEncrypt();
              const encrypt = new EncryptClass();
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
                }, {
                  headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                  }
                })
                .then((response) => {
                  localStorage.setItem('auth_token', response.data.token)
                  localStorage.setItem('x_xsrf_token', response.config.headers['X-XSRF-TOKEN'])
                  
                  // Fetch permissions immediately after successful login
                  return axios
                    .get(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/get_user/get_auth_user_permissions/', {
                      headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + response.data.token
                      }
                    })
                    .then((permResponse) => {
                      // Store permissions in localStorage for persistence
                      localStorage.setItem('user_permissions', JSON.stringify(permResponse.data))
                      
                      // Update CASL ability if it's available globally
                      if (this.$ability) {
                        this.$ability.update(permResponse.data)
                      }
                      
                      // Emit global event so all components can update
                      this.$root.$emit('permissions-loaded', permResponse.data)
                      
                      // Navigate to home
                      this.$router.push({ name: "home" });
                    })
                })
                .catch((error) => {
                  console.log('Login error:', error.response || error);
                  if(error.response && error.response.status === 422) {
                    if(error.response.data.message == 'auth.failed'){
                      this.auth_error = 'Email or password is not corect'
                    }
                    else{
                      this.error = error.response.data.errors
                    }
                  } else if (error.response && error.response.data && error.response.data.message) {
                    this.auth_error = error.response.data.message
                  } else if (error.response && error.response.status === 302) {
                    // Handle redirect - try direct API call
                    this.auth_error = 'Redirect detected - please check credentials'
                  }
                })
                .finally(() => this.is_loading = false);
          } catch (e) {
              console.error('Login encryption error:', e);
              this.auth_error = 'Encryption error. Please refresh and try again.';
              this.is_loading = false;
          }
      }
    }
  };
</script>
