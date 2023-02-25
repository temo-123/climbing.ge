<template>
    <div class="login mt-5">
      <div class="card">

        <div class="card-header" v-if="!error">
          <h1>Pless wait!</h1>
        </div>

        <div class="card-body" v-if="!error">
            <p>you will be logged whith your {{ this.$route.params.provaider }}</p>
        </div>


        <div class="card-header" v-if="error">
          <h1>Error</h1>
        </div>

        <div class="card-body" v-if="error">
            <div class="alert alert-danger" role="alert">
                Somesing happened please go to login page and try again. (<span class="cursor_pointer" @click="go_to_login_page()">Click for go to login page</span>)
            </div>
        </div>

      </div>
    </div>
  </template>
  
  <script>
    export default {
      data: function() {
        return {
            error: false
        };
      },
      mounted() {
        this.social_login_callback()
      },
      beforeRouteLeave (to, from, next) {
        //
      },
      methods: {
        social_login_callback(){
            this.error = false
            axios.get('../../../api/login/' + this.$route.params.provaider + '/callback',{
                params: {code: this.$route.query.code}
            })
            .then(response => {

                localStorage.setItem('x_xsrf_token', response.config.headers['X-XSRF-TOKEN'])

                const token = localStorage.getItem('x_xsrf_token')
                console.log("🚀 ~ file: CallbackComponent.vue:55 ~ social_login_callback ~ token:", token)

                if (token) {  
                    if(response.data.status == 'login'){
                        window.location.href = '/'
                    }
                    else if(response.data.status == 'registratione'){
                        window.location.href = '/create_password/' + response.data.new_user_email
                    }
                }
                else{
                    alert("Token error!")
                }
            }).catch(err => {
                this.error = true
            })
        },
        go_to_login_page(){
            this.$router.push({ name: "login" });
        }
      }
    };
  </script>