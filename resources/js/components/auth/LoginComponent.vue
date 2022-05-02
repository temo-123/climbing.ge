<template>
  <div class="login mt-5">
    <div class="card">
      <div class="card-header">
        Login
      </div>
      <div class="row mt-2">
        <div class="col-md-4 text-center">
          <button type="button" class="btn btn-danger">
            <i class="fa fa-google-plus" aria-hidden="true"></i>
          </button>
        </div>
        <div class="col-md-4 text-center">
          <button type="button" class="btn btn-primary">
            <i class="fa fa-facebook" aria-hidden="true"></i>
          </button>
        </div>
        <div class="col-md-4 text-center">
          <button type="button" class="btn btn-info">
            <i class="fa fa-twitter" aria-hidden="true"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <div class="alert alert-danger" role="alert" v-if="this.errors.message">
          {{ this.errors.message }}
        </div>
        <form>
          <div class="form-group">
            <label for="email">Email address</label>
            <input
              type="email"
              class="form-control"
              id="email"
              v-model="email"
              placeholder="Enter email"
              autocomplete="on"
            />
            <div class="alert alert-danger" role="alert" v-if="email_errors">
              {{ email_errors }}
            </div>
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input
              type="password"
              class="form-control"
              id="password"
              v-model="password"
              placeholder="Password"
              autocomplete="on"
            />
            <div class="invalid-feedback" v-if="errors.password">
              {{ errors.password[0] }}
            </div>
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
        errors: [],
        email_errors: null
      };
    },
    mounted() {
    },
    // created() {
    //     this.csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    // },
    methods: {
      login(){
        axios
          .get('/sanctum/csrf-cookie')
          .then(response => {
            axios
              .post('login', {
                email: this.email, 
                password: this.password
              })
              .then(res => {
                this.email_errors = null
                this.errors = []
                localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
                this.$router.push({ path: "/" });

              })
              .catch(err => {
                this.email_errors = null
                this.errors = []

                if (err.response.data) {
                  this.errors = err.response.data
                }
                
                if(err.response.data.errors.email[0]){
                  this.email_errors = err.response.data.errors.email[0];
                }
              })
          }); 
      }
    }
  };
</script>