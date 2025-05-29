<template>
    <span>
        <div class="footer_re_capcha">
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

        <div v-if="loading == false">
            <div class="form-group"  v-if="is_verify_isset == false">
                <button class="btn btn-success" disabled>Follow</button>
            </div>
            <div class="form-group"  v-else>
                <button class="btn btn-success" v-if="this.email != ''">Follow</button>
                <button class="btn btn-success" disabled v-else>Follow</button>
            </div>
        </div>
        <div v-if="loading == true">
            <h4  class="footer_title">Loading</h4>
        </div>
    </span>
</template>

<script>
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha
    export default {
        props:[
            // 'service',
        ],
        data: function () {
            return {
                MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
                is_verify_isset: false,
                loading: false,
            };
        },
        components: {
            VueRecaptcha
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
        }
    }
</script>

<style>
.footer_input, .footer_re_capcha{
    margin-bottom: 1.5%;
}
</style>