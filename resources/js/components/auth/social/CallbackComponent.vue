<template>
    <div class="login mt-5">
      <div class="card">

        <div class="card-header" v-if="!error">
          <h1>Please wait...</h1>
        </div>

        <div class="card-body" v-if="!error">
            <p>Signing you in with {{ $route.params.provaider }}...</p>
        </div>

        <div class="card-header" v-if="error">
          <h1>Login Failed</h1>
        </div>

        <div class="card-body" v-if="error">
            <div class="alert alert-danger" role="alert">
                Something went wrong. Redirecting to login in {{ countdown }}s...
                (<span class="cursor_pointer" @click="go_to_login_page()">Go now</span>)
            </div>
        </div>

      </div>
    </div>
  </template>

  <script>
    export default {
      data: function() {
        return {
            error: false,
            countdown: 5,
        };
      },
      mounted() {
        this.social_login_callback()
      },
      beforeUnmount() {
        if (this._countdownTimer) clearInterval(this._countdownTimer)
      },
      methods: {
        social_login_callback(){
            if (this.$route.query.error) {
                this.show_error()
                return
            }

            this.error = false
            axios.get('login/' + this.$route.params.provaider + '/callback', {
                params: { code: this.$route.query.code }
            })
            .then(response => {
                if (response.data.status == 'login') {
                    localStorage.setItem('auth_token', response.data.token)
                    window.location.href = '/'
                } else if (response.data.status == 'registratione') {
                    window.location.href = '/create_password/' + encodeURIComponent(response.data.new_user_email)
                } else {
                    this.show_error()
                }
            }).catch(() => {
                this.show_error()
            })
        },
        show_error(){
            this.error = true
            this._countdownTimer = setInterval(() => {
                this.countdown--
                if (this.countdown <= 0) {
                    clearInterval(this._countdownTimer)
                    this.go_to_login_page()
                }
            }, 1000)
        },
        go_to_login_page(){
            this.$router.push({ name: "login" });
        }
      }
    };
  </script>