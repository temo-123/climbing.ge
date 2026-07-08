<template>
    <div class="row f-links container">
        <div class="flick">
            <h4  class="footer_title">{{ $t("global.footer.follow") }}</h4>
        </div>
        <div class="form-group row">

            <div class="col-md-10" v-if="loading">
                <img :src="'/images/site_img/loading.gif'" alt="loading">
            </div>

            <div class="col-md-10" v-if="!loading">
                <form name="contact-form" method="POST" id="global_form" ref="myForm" @submit.prevent enctyp="multipart/form-data">
                    <input type="text" v-model="email" name="email" class="form-control footer_input" :placeholder="$t('shop.product.feedback.complainter_email')">

                    <div class="footer_re_capcha">
                        <div v-if="captcha_error" class="alert alert-warning mb-2">
                            <i class="fa fa-exclamation-triangle"></i>
                            {{ $t('shop.product.feedback.recaptcha_error') }}
                        </div>
                        <FormCapchaComponent
                            :buttonTextProp="$t('global.footer.follow_btn')"
                            @recaptcha-verified="follow"
                            @expired="onCaptchaExpired"
                        />
                    </div>
                    <div v-if="loading == true">
                        <h4  class="footer_title">{{ $t('global.loading') }}</h4>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import FormCapchaComponent from './FormCapchaComponent.vue';

    export default {
        props:[
            // 'service',
        ],
        data: function () {
            return {
                service: 0,
                email: '',

                MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
                recaptcha_token: null,
                loading: false,
                captcha_error: false,
            };
        },
    components: {
            FormCapchaComponent
        },
        provide() {
            return {
                //
            }
        },
        mounted() {
            
        },
        methods: {
            follow(token){
                if (!token) {
                    this.captcha_error = true;
                    return;
                }
                this.captcha_error = false
                this.recaptcha_token = token
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
                .post('/set_follow/' + this.service, {
                    email: this.email,
                    recaptcha_token: this.recaptcha_token,
                    _method: 'post'
                })
                .then(Response => {
                    alert(Response.data)
                    this.email = ''
                })
                .catch(error =>{
                    if (error.response?.status === 422) {
                        if (error.response.data?.message?.toLowerCase().includes('recaptcha')) {
                            this.captcha_error = true;
                        } else {
                            this.errors = error.response.data.errors
                        }
                        this.loading = false
                    }
                    else if (error.response?.status === 419){
                        alert(this.$t('global.footer.csrf_mismatch_error'))
                    }
                })
                .finally(() => (this.loading = false));

            },
            onCaptchaExpired(){
                this.recaptcha_token = null
            },
        }
    }
</script>

<style>
.footer_input, .footer_re_capcha{
    margin-bottom: 1.5%;
}
</style>