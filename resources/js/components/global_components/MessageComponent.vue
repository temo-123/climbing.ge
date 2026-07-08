<template>
    <div class="container">
        <h2 class="block_title">{{ $t('global.message.title') }}</h2>
        <div class="bar"><i class="fa fa-envelope" aria-hidden="true"></i></div>
        <h3> <span v-html="safeMessage"></span> </h3>
        
        <div class="alert alert-danger" role="alert" v-if="fatal_error">
            <h2 class="text-center">{{ $t('global.message.fatal_error_contact_support') }}</h2>
        </div>

        <form method="POST" @submit.prevent id="js_form" v-if="!is_mail_sending && !fatal_error">
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
                                <input type="text" class="form-control" v-model="num"  name="num"  id="num" :placeholder="$t('common.phone_number')">
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
                    <div v-if="captcha_error" class="alert alert-warning mb-2">
                        <i class="fa fa-exclamation-triangle"></i>
                        {{ $t('shop.product.feedback.recaptcha_error') }}
                    </div>
                    <FormCapchaComponent
                        :buttonTextProp="$t('global.message.send_message_btn')"
                        @recaptcha-verified="send_message"
                        @expired="onCaptchaExpired"
                    />
                </div>
            </div>
        </form>

        <div class="row" v-else-if="is_mail_sending && !fatal_error">
            <div class="text-center loader_margin">
                <img :src="'/images/site_img/loading.gif'" class="img-responsive center-block" alt="loading">
                <p>{{ $t('user.notifications.please_wait') }}</p>
            </div>
        </div>
    </div>
</template>

<script>
    import FormCapchaComponent from './FormCapchaComponent.vue'
    
    export default {
        components: { 
            FormCapchaComponent
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



                recaptcha_token: null,
                is_verify_isset: false,
                captcha_error: false,

                is_mail_sending: false,

                error: [],
                fatal_error: false,

                isLoading: false,
                isSuccess: false,
            }
        },
        computed: {
            safeMessage() {
                const raw = this.$siteData?.data?.message || '';
                const doc = new DOMParser().parseFromString(raw, 'text/html');
                doc.querySelectorAll('script, iframe, object, embed, form, input, link[rel="import"]').forEach(el => el.remove());
                doc.querySelectorAll('*').forEach(el => {
                    [...el.attributes].forEach(attr => {
                        if (/^on/i.test(attr.name) || (attr.name === 'href' && /^javascript:/i.test(attr.value))) {
                            el.removeAttribute(attr.name);
                        }
                    });
                });
                return doc.body.innerHTML;
            }
        },
        mounted() {
            // this.form_title
        },
        methods: {
            send_message(token) {
                if (!token) {
                    this.captcha_error = true;
                    return;
                }
                this.recaptcha_token = token
                this.captcha_error = false
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
                    recaptcha_token: this.recaptcha_token,
                })
                .then(Response => {
                    alert(this.$t('global.message.thank_you_for_message', { name: this.name }))
                    this.clear_form()
                })
                .catch(error =>{
                    if (error.response?.status === 422) {
                        if (error.response.data?.message?.toLowerCase().includes('recaptcha')) {
                            this.captcha_error = true;
                        } else {
                            this.error = error.response.data.errors || error.response.data.error || {}
                        }
                        this.isLoading = false
                    }
                    else if (error.response?.status === 500) {
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

            onCaptchaExpired() {
                this.recaptcha_token = null;
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