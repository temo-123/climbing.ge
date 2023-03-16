<template>
    <stack-modal
            :show="is_login_model"
            title="Login"
            @close="close_login_model()"
            :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >
        <pre>
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

        BASE_URL_SSH: process.env.BASE_URL_SSH,
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
          .get(this.BASE_URL_SSH + '/sanctum/csrf-cookie')
          .then(response => {
            this.login_action()
          }); 
      },

      login_action(){
          axios
            .post(this.BASE_URL_SSH + '/login', {
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

</style>