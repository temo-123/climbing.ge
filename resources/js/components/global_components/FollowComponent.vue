<template>
    <div class="row f-links container">
        <div class="flick">
            <h4  class="footer_title">Follow us</h4>
        </div>
        <div class="form-group row">
            <div class="col-md-10">
                <form name="contact-form" method="POST" id="global_form" ref="myForm" @submit.prevent="follow" enctyp="multipart/form-data">
                    <input type="text" v-model="email" name="email" class="form-control footer_input"> 

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
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha
    export default {
        props:[
            // 'service',
        ],
        data: function () {
            return {
                service: 0,
                email: '',

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
            follow(){
                if(window.location.hostname == process.env.MIX_SITE_URL){
                    this.service = 'guid'
                }
                else if (window.location.hostname == process.env.MIX_SHOP_URL) {
                    this.service = 'shop'
                }
                else if (window.location.hostname == process.env.FILMS_URL) {
                    this.service = 'cinema'
                }

                this.loading = true
                axios
                .post('../../../../api/follow/' + this.service, {
                    email: this.email,

                    _method: 'post'
                })
                .then(Response => {
                    alert(Response.data)
                    this.email = ''
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                        this.isLoading = false
                    }
                })
                .finally(() => (this.loading = false));

            },
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