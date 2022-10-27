<template>
    <div class="container">
    	    
        	<h2 class="block_title">{{ $t('title message') }}</h2>
            <div class="bar"><i class="fa fa-envelope" aria-hidden="true"></i></div>
            <h3> <span v-html="this.$siteData.message"></span> </h3>
            
            <form method="POST"  @submit.prevent="send_message"  id="js_form">
            <!-- <form method="POST" id="js_form"> -->
                <div class='row'>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" v-model="name" name="name"  id="name" placeholder="Name*">
                                    <div class="alert alert-danger" role="alert" v-if="errors.name">
                                        {{ errors.name[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" v-model="surname" name="surname"  id="surname" placeholder="Surname*">
                                    <div class="alert alert-danger" role="alert" v-if="errors.surname">
                                        {{ errors.surname[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="email" name="email" v-model="email" class="form-control textarea" placeholder="E_mail">
                                    <div class="alert alert-danger" role="alert" v-if="errors.email">
                                        {{ errors.email[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="text" class="form-control" v-model="num"  name="num"  id="num" placeholder="Phone number">
                                    <div class="alert alert-danger" role="alert" v-if="errors.num">
                                        {{ errors.num[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group form_left">
                                    <input type="text" class="form-control" v-model="country"  name="country"  id="country" placeholder="Yout country">
                                    <div class="alert alert-danger" role="alert" v-if="errors.country">
                                        {{ errors.country[0] }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <textarea rows="10" name="msg" id="msg" v-model="msg" placeholder="Your message" class="form-control textarea"></textarea>
                                    <div class="alert alert-danger" role="alert" v-if="errors.msg">
                                        {{ errors.msg[0] }}
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
                            <button type="submit" class="btn btn-default btn-send main-btn" disabled>Send</button>
                        </div>
                        <div class="form-group"  v-else>
                            <button type="submit" class="btn btn-default btn-send main-btn">Send</button>
                        </div>
                    </div>
                </div>
            </form>
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

                errors: [],

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

                axios
                .post('../api/message', {
                    name: this.name,
                    surname: this.surname,
                    email: this.email,
                    msg: this.msg,
                    num: this.num,
                    country: this.country,
                })
                .then(Response => {
                    alert("Tenk you for your message " + this.name + ".")
                    this.onCaptchaExpired()
                    this.errors = []
                    this.isLoading = false
                })
                .catch(error =>{
                    if (error.response.status == 422) {
                        this.errors = error.response.data.errors
                        this.isLoading = false
                    }
                })
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
