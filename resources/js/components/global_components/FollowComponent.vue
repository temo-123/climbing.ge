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
                    <input type="email" v-model="email" name="email" class="form-control footer_input" :placeholder="$t('shop.product.feedback.complainter_email')">

                    <div class="footer_re_capcha">
                        <div v-if="captcha_error" class="alert alert-warning mb-2">
                            <i class="fa fa-exclamation-triangle"></i>
                            {{ $t('shop.product.feedback.recaptcha_error') }}
                        </div>
                        <div v-if="feedback_message" :class="['alert mb-2', feedback_type === 'success' ? 'alert-success' : 'alert-danger']">
                            {{ feedback_message }}
                        </div>
                        <FormCapchaComponent
                            :buttonTextProp="$t('global.footer.follow_btn')"
                            recaptchaAction="follow"
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
                feedback_message: '',
                feedback_type: 'success',
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
                this.feedback_message = ''
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
                else if (window.location.hostname == process.env.MIX_BLOG_URL) {
                    this.service = 'blog'
                }
                else if (window.location.hostname == process.env.MIX_SUMMIT_URL) {
                    this.service = 'summit'
                }
                else if (window.location.hostname == process.env.MIX_FILMS_URL) {
                    this.service = 'films'
                }

                this.loading = true
                axios
                .post('/set_follow/' + this.service, {
                    email: this.email,
                    recaptcha_token: this.recaptcha_token,
                    _method: 'post'
                })
                .then(Response => {
                    this.feedback_type = 'success'
                    this.feedback_message = Response.data?.message || Response.data
                    this.email = ''
                })
                .catch(error =>{
                    if (error.response?.status === 422) {
                        if (error.response.data?.message?.toLowerCase().includes('recaptcha')) {
                            this.captcha_error = true;
                        } else {
                            this.feedback_type = 'danger'
                            this.feedback_message = error.response.data?.message
                                || Object.values(error.response.data?.errors || {}).flat()[0]
                                || this.$t('global.errors.generic_status', { status: 422, statusText: error.response.statusText || '' })
                        }
                        this.loading = false
                    }
                    else if (error.response?.status === 419){
                        this.feedback_type = 'danger'
                        this.feedback_message = this.$t('global.footer.csrf_mismatch_error')
                    }
                    else {
                        this.feedback_type = 'danger'
                        this.feedback_message = this.$t('global.errors.generic_status', {
                            status: error.response?.status || '—',
                            statusText: error.response?.statusText || error.message || '',
                        })
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