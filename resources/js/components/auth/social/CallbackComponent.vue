<template>
    <div class="login cg-auth">
      <auth-aside />
      <div class="cg-auth__main">
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
    </div>
  </template>

  <script>
  import AuthAside from '../AuthAsideComponent.vue'
    export default {
    components: { AuthAside },
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
                // state carries the page the user started from (validated again on the server)
                params: { code: this.$route.query.code, state: this.$route.query.state }
            })
            .then(response => {
                const return_url = response.data.return_url || ''
                if (response.data.status == 'login') {
                    localStorage.setItem('auth_token', response.data.token)
                    window.location.href = return_url || '/'
                } else if (response.data.status == 'registratione') {
                    // one-time key for create_password + where to go afterwards (this tab only)
                    try {
                        sessionStorage.setItem('cg_social_setup', JSON.stringify({
                            email: response.data.new_user_email,
                            key: response.data.setup_key,
                            return_url,
                        }))
                    } catch (e) { /* private mode: create_password will ask to sign in again */ }
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