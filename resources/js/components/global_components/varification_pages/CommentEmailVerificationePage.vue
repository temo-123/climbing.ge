<template>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="error-template">
                    <!-- <h1> Confirm your email </h1>
                    <div class="error-actions">
                        <p>{{$route.params.email}}</p>
                        <button @click="confirm_email()" class="btn btn-primary btn-lg">
                            Confirm
                        </button>
                    </div>
                    <h2>Climbing.ge</h2> -->


                    <h1> Confirm your email </h1>

                    <div class="row" v-if="is_loading">
                        <div class="col-md-4 text-center loader_margin">
                            <img :src="'../../../../../../public/images/site_img/loading.gif'" class="img-responsive center-block" alt="loading">
                            <p>Pless wait!</p>
                        </div>
                    </div>

                    <div class="row" v-else>
                        <p>Your email - {{$route.params.email}}</p>

                        <vue-recaptcha 
                            :sitekey="MIX_GOOGLE_CAPTCHA_SITE_KEY" 
                            :loadRecaptchaScript="true"
                            ref="recaptcha"
                            type="invisible"
                            @verify="onCaptchaVerified"
                            @expired="onCaptchaExpired"
                        >
                        </vue-recaptcha>
                        <button v-if="is_verify_isset == false" class="btn btn-primary btn-lg" disabled>Confirm</button>
                        <button v-else  @click="confirm_email()" class="btn btn-primary btn-lg">Confirm</button>
                    </div>

                    <div class="row">
                        <h2>Climbing.ge</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha
    export default {
        props: [
            // 'article',
        ],
        data: function () {
            return {
                is_verify_isset: false,
                is_loading: false,
                MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
            };
        },
        components: {
            VueRecaptcha
        },
        mounted() {
            // 
        },
        watch: {
            '$route' (to, from) {
                window.scrollTo(0,0)
            }
        },
        methods: {
            onCaptchaVerified() {
                this.is_verify_isset = true
            },
            onCaptchaExpired(){
                this.is_verify_isset = false
            },

            confirm_email(){
                axios
                .post('/guide_comment/confirm_email/'+this.$route.params.email)
                .then(response => {
                    alert('Your email is confirmed! This page will be closed!')
                    window.close();
                })
                .catch(error =>{
                })
                .finally(() => this.article_loading = false);
            },
        }
    }
</script>

<style scoped>

</style>