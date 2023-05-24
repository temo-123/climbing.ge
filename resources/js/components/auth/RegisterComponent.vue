<template>
  <div class="login mt-5">
    <router-link :to="{name: 'login'}" exact class="btn btn-success" v-if="!is_loading">
      Login
    </router-link>

    <hr v-if="!is_loading">

    <div class="card">

      <div class="card-header">
        Register
      </div>

      <div class="card-body" v-if="!is_loading">
        <form id="register_form" v-on:submit.prevent="register">
          <div class="form-group">
            <label for="email">Name</label>
            <input
              type="text"
              class="form-control"
              id="name"
              v-model="name"
              placeholder="Enter name"
              required
            />
          </div>
          <div class="form-group">
            <label for="email">surname</label>
            <input
              type="text"
              class="form-control"
              id="surname"
              v-model="surname"
              placeholder="Enter surname"
              required
            />
          </div>
          <div class="form-group">
            <label for="email">Email address</label>
            <input
              type="email"
              class="form-control"
              id="email"
              v-model="email"
              placeholder="Enter email"
              required
            />
            <div class="alert alert-danger" role="alert" v-if="error.email">
              User with this email address already exists!
            </div>
          </div>
          <div class="form-group">
            <label for="country">country</label>
            <input
              type="country"
              class="form-control"
              id="country"
              v-model="country"
              placeholder="Enter country"
              required
            />
          </div>
          <div class="form-group">
            <label for="city">city</label>
            <input
              type="city"
              class="form-control"
              id="city"
              v-model="city"
              placeholder="Enter city"
              required
            />
          </div>
          <div class="form-group">
            <label for="phone_number">phone_number</label>
            <input
              type="phone_number"
              class="form-control"
              id="phone_number"
              v-model="phone_number"
              placeholder="Enter phone_number"
              required
            />
          </div>
          <div class="form-group">
            <label for="password">Password *</label>
            <input
              type="password"
              class="form-control"
              id="password"
              v-model="password"
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
              v-model="password_confirmation"
              placeholder="Confirm password"
              minlength="8"
              required
            />
          </div>
          <div class="alert alert-danger" role="alert" v-if="error.password">
            Confirming faild!
          </div>
          <div class="form-group">
            <input type="checkbox" v-model="terms_of_use" name="One time code" value="One time code">
            I agree with the <span class="cursor_pointer text-warning" @click="open_term_of_use_modal()">terms of use</span>
          </div>
          <div class="row">
              <div class="col-md-12">
                  <div class="form-group form_left">
                      <vue-recaptcha 
                          :sitekey="MIX_GOOGLE_CAPTCHA_SITE_KEY" 
                          :loadRecaptchaScript="true"
                          ref="recaptcha"
                          type="invisible"
                          @verify="onCaptchaVerified"
                          @expired="onCaptchaExpired"
                      >
                      </vue-recaptcha>
                  </div>
              </div>
          </div>
          
          <div class="row">
            <div class="col-md-12">
                <div class="form-group"  v-if="is_verify_isset != false && terms_of_use != false">
                  <button
                          type="submit"
                          class="btn btn-default btn-send main-btn"
                          form="register_form"
                      >
                      Save
                  </button>
                </div>
                <div class="form-group"  v-else>
                  <button
                          type="submit"
                          class="btn btn-default btn-send main-btn"
                          form="register_form"
                          disabled
                      >
                      Save
                  </button>
                </div>
            </div>
          </div>

        </form>
      </div>

      <div class="row justify-content-center" v-else-if="is_loading">
          <div class="col-md-4">
              <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
              <p class="text-center">Pless wait!</p>
          </div>
      </div>

    </div>

    <stack-modal
        :show="is_term_of_use_modal"
        title="Terms of use"
        @close="close_term_of_use_modal()"
        :modal-class="{ [ModalClass]: true }"
        :saveButton="{ visible: true }"
        :cancelButton="{
            title: 'Close',
            btnClass: { 'btn btn-primary': true },
        }"
    >
        <div class="model-body">
            <div class="container">
                <div class="row">
                    <span v-html="this.$siteData.terms_of_use"></span>
                </div>
            </div>
        </div>
        <div slot="modal-footer">
            <div class="modal-footer">
                <!-- footer -->
            </div>
        </div>
    </stack-modal>

  </div>
</template>

<script>
import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha
import StackModal from "@innologica/vue-stackable-modal"; //https://innologica.github.io/vue-stackable-modal/#sample-css
export default {
    components: { 
        VueRecaptcha,
        StackModal,
    },
  name: "Register page",
  data: function() {
    return {
      name: null,
      surname: null,
      email: null,
      phone_number: null,
      country: null,
      city: null,
      password: null,
      password_confirmation: null,

      is_loading: false,

      is_verify_isset: false,
      terms_of_use: false,

      error: [],

      MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,

      is_term_of_use_modal: false,
    };
  },
  mounted() {
  },
  methods: {

    onCaptchaVerified() {
        this.is_verify_isset = true
    },
    onCaptchaExpired(){
        this.is_verify_isset = false
    },
    open_term_of_use_modal(){
      this.is_term_of_use_modal = true
    },
    close_term_of_use_modal(){
      this.is_term_of_use_modal = false
    },
    register(){
      this.error = []
      this.is_loading = true
      axios
        .get('/sanctum/csrf-cookie')
        .then(response => {
          axios
            .post(process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/register',{
              name: this.name,
              surname: this.surname,
              email: this.email,
              phone_number: this.phone_number,
              country: this.country,
              city: this.city,
              password: this.password,
              password_confirmation: this.password_confirmation
            })
            .then(res=>{
              localStorage.setItem('x_xsrf_token', res.config.headers['X-XSRF-TOKEN'])
              this.$router.push({ path: "/" });
            })
            .catch((error) => {
              if(error.response.status === 422) {
                this.error = error.response.data.errors
              }
            })
            .finally(() => this.is_loading = false);
      })
    }
  }
};
</script>