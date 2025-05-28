<template>
    <div class="container">
        <h2 class="block_title">{{ $t('global.message.title') }}</h2>
        <div class="bar"><i class="fa fa-envelope" aria-hidden="true"></i></div>
        <h3> <span v-html="this.$siteData.message"></span> </h3>
        
        <div class="alert alert-danger" role="alert" v-if="fatal_error">
            <h2 class="text-center">Fatal error! Please contact support!</h2>
        </div>

        <form method="POST"  @submit.prevent="send_message"  id="js_form" v-if="!is_mail_sending && !fatal_error">
        <!-- <form method="POST" id="js_form"> -->
            <div class='row'>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" v-model="name" name="name"  id="name" :placeholder="$t('global.message.form.name')">
                                <div class="alert alert-danger" role="alert" v-if="error.name">
                                    {{ error.name[0] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" v-model="surname" name="surname"  id="surname" :placeholder="$t('global.message.form.surname')">
                                <div class="alert alert-danger" role="alert" v-if="error.surname">
                                    {{ error.surname[0] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="email" name="email" v-model="email" class="form-control textarea" :placeholder="$t('global.message.form.email')">
                                <div class="alert alert-danger" role="alert" v-if="error.email">
                                    {{ error.email[0] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" class="form-control" v-model="num"  name="num"  id="num" :placeholder="$t('global.message.form.number')">
                                <div class="alert alert-danger" role="alert" v-if="error.num">
                                    {{ error.num[0] }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form_left">
                                <input type="text" class="form-control" v-model="country"  name="country"  id="country" :placeholder="$t('global.message.form.country')">
                                <div class="alert alert-danger" role="alert" v-if="error.country">
                                    {{ error.country[0] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <textarea rows="10" name="msg" id="msg" v-model="msg" :placeholder="$t('global.message.form.message text')" class="form-control textarea"></textarea>
                                <div class="alert alert-danger" role="alert" v-if="error.msg">
                                    {{ error.msg[0] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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
                    <div class="form-group"  v-if="is_verify_isset == false">
                        <button type="submit" class="btn btn-default btn-send main-btn" disabled>{{ $t("global.message.send") }}</button>
                    </div>
                    <div class="form-group"  v-else>
                        <button type="submit" class="btn btn-default btn-send main-btn">{{ $t("global.message.send") }}</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="row" v-else-if="is_mail_sending && !fatal_error">
            <div class="text-center loader_margin">
                <img :src="'../../../../../../public/images/site_img/loading.gif'" class="img-responsive center-block" alt="loading">
                <p>Pless wait!</p>
            </div>
        </div>
    </div>
</template>

<script>
    // import VueLoadingButton from 'vue-loading-button'
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha
    export default {
        components: { 
            VueRecaptcha,
            // VueLoadingButton,

        },
        props: [
            // 'form_title'
        ],
        data() {
            return {
                name: "",
                surname: "",
                email: "",
                num: "",
                country: "",
                msg: "",

                data: {
                    name: "",
                    surname: "",
                    email: "",
                    num: "",
                    country: "",
                    msg: "",
                },

                is_verify_isset: false,

                is_mail_sending: false,

                error: [],
                fatal_error: false,

                // MIX_APP_NAME: process.env.MIX_APP_NAME,
                MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
                // MIX_GOOGLE_CAPTCHA_SECRET_KEY: process.env.MIX_GOOGLE_CAPTCHA_SECRET_KEY,

                isLoading: false,
                isSuccess: false,
            }
        },
        mounted() {
            // this.form_title
        },
        methods: {
            send_message() {
                this.isLoading = true,
                this.isSuccess = false,

                this.is_mail_sending = true
                this.fatal_error = false

                axios
                .post('/message', {
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    msg: this.msg,
                    num: this.num,
                    country: this.country,
                })
                .then(Response => {
                    alert("Tenk you for your message " + this.name + ".")
                    this.clear_form()
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.error = error.response.data.error
                        this.isLoading = false
                    }
                    else if (error.response.status == 500) {
                        this.fatal_error = true
                    }
                })
                .finally(() => this.is_mail_sending = false)
            },

            clear_form(){
                this.onCaptchaExpired()
                this.error = []
                this.isLoading = false,

                this.name = ''
                this.surname = ''
                this.email = ''
                this.msg = ''
                this.num = ''
                this.country = ''
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
<style scoped>
.loader_margin {
    margin-left: 40%;
    margin-right: 40%;
}
</style>