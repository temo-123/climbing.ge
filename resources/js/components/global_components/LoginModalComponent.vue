<template>
    <stack-modal
            :show="is_login_model"
            title="Login"
            @close="close_login_model()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre>
            <!-- <div class="row mt-2"> -->
                <div class="left_social_icon">
                  <button type="button" class="btn btn-danger left_social_icon_button" @click="social_login('google')">
                    <!-- Google -->
                    <i class="fa fa-google" aria-hidden="true"></i>
                  </button>
                </div>
                <div class="right_social_icon">
                  <button type="button" class="btn btn-primary right_social_icon_button" @click="social_login('facebook')">
                    <!-- Facebook -->
                    <i class="fa fa-facebook" aria-hidden="true"></i>
                  </button>
                </div>
            <!-- </div> -->
            <form method="POST" id="login_form" v-on:submit.prevent="login">
                <!-- <label for="email">Email address</label> -->
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    v-model="email"
                    placeholder="Enter email"
                    autocomplete="on"
                    required
                />
                
                <!-- <label for="password">Password</label> -->
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    v-model="password"
                    placeholder="Password"
                    autocomplete="on"
                />
            </form>
        </pre>
        <div slot="modal-footer">
            <div class="modal-footer">
                <button
                    type="submit"
                    :class="{'btn btn-primary': true}"
                    form="login_form"
                >
                {{ $t('global.login') }}
                </button>
            </div>
        </div>
    </stack-modal>
</template>

<script>
import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

export default {
    components: {
        StackModal,
        SlickItem,
        SlickList,
    },
    name: "Login modal",
    data: function() {
      return {
        email: null,
        password: null,
        errors: [],
        email_errors: null,
        remember_me: null,

        // BASE_URL_SSH: process.env.BASE_URL_SSH,
        // MIX_APP_SSH: process.env.MIX_APP_SSH,

        is_login_model: false
      };
    },
    mounted() {
    },
    methods: {
      // social_login(service){
      //   window.location.href = `api/login/${service}`
      // },
      

      login(){
        axios
          .get('/sanctum/csrf-cookie')
          .then(response => {
            this.login_action()
          }); 
      },

      login_action(){
          axios
            .post(process.env.MIX_APP_SSH + process.env.MIX_SITE_URL + '/login', {
              email: this.email, 
              password: this.password
            })
            .then((res) => {
              if (this.email_errors) {
                this.email_errors = null
                this.errors = []
              }

              localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
            //   this.$router.push({ path: "/" });
                this.close_login_model()
            })
      },
      close_login_model(){
        this.is_login_model = false
        this.email = null
        this.password = null
      }
    }
  };
</script>

<style>
.left_social_icon{
  width: 49.5%;
  float: left;
  margin-top: 0%;
}
.left_social_icon_button{
  float: left;
  margin: 15%;
}

.right_social_icon{
  width: 49%;
  float: right;
  margin-top: -10%;
}
.right_social_icon_button{
  float: right;
  margin: 15%;
}
</style>