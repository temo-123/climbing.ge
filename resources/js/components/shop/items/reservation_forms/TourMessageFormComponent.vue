<template>
    <div class="content">
        <div class="container">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 mb30 text-center">
                    <h2>{{ $t('shop.tour.message.title') }}</h2>
                </div>
            </div>
            <!-- {{ new Date().toISOString().split('T')[0] }} -->
            <div class="row">

                <div class="col-xl-12 col-lg-12 col-md-12 mb30">
                    <div class="tour.message-booking-form" v-if="!is_loading">

                        <div v-if="
                            user.length != 0 &&
                            (user.name == null || user.surname == null || user.country == null || user.city == null || user.phone_number == null || user.email == null)
                        ">
                            <div :class="'alert alert-danger cursor_pointer'" @click="goTo('/options')" role="alert">
                                <div class="row">
                                    <div class="col-md-12">
                                        <strong>{{ $t('shop.tour.message.warning') }}</strong>
                                        <p>{{ $t('shop.tour.message.enter_full_information') }}</p>
                                        <p>{{ $t('shop.tour.message.befor_enter_full_information') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <form v-else @submit.prevent="create_reservation" id="reservation_form" class="contact-form" method="POST" enctype="multipart/form-data">

                            <div class="row">
                                <div class="col-xl-6 col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="check_in">{{ $t('shop.tour.message.check_in') }}</label>
                                        <div class="select">
                                            <input v-model="form_data.check_in" id="check_in" name="check_in" type="datetime-local" :min="new Date().toISOString().split('T')[0] + 'T00:00'" placeholder="Check in" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xl-6 col-lg-6 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label required" for="select">{{ $t('shop.tour.message.persons') }}</label>
                                        <div class="select">
                                            <input v-model="form_data.persons" id="persons" name="persons" type="number" placeholder="Persons number" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div v-if="user.length == 0">
                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="name">{{ $t('shop.tour.message.name') }}</label>
                                            <input v-model="form_data.name" id="name" type="text" placeholder="Name" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="email">{{ $t('shop.tour.message.email') }}</label>
                                            <input v-model="form_data.email" id="email" type="text" placeholder="Your email" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="phone"> {{ $t('shop.tour.message.phone') }}</label>
                                            <input v-model="form_data.phone" id="phone" type="text" placeholder="Your phone" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="country">{{ $t('shop.tour.message.country') }}</label>
                                            <input v-model="form_data.country" id="country" type="text" placeholder="Your country" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-xl-4 col-lg-4 col-md-12">
                                        <div class="form-group">
                                            <label class="control-label" for="city">{{ $t('shop.tour.message.city') }}</label>
                                            <input v-model="form_data.city" id="city" type="text" placeholder="Your city" class="form-control" required>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="textarea">{{ $t('shop.tour.message.message') }}</label>
                                        <textarea  v-model="form_data.text" class="form-control" id="textarea" name="textarea" rows="4" placeholder="Write Your Requirements"></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <!-- <vue-recaptcha 
                                            :sitekey="MIX_GOOGLE_CAPTCHA_SITE_KEY" 
                                            :loadRecaptchaScript="true"
                                            ref="recaptcha"
                                            type="invisible"
                                            @verify="onCaptchaVerified"
                                            @expired="onCaptchaExpired"
                                        >
                                        </vue-recaptcha> -->
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <div v-if="captcha_error" class="alert alert-warning mb-2">
                                            <i class="fa fa-exclamation-triangle"></i>
                                            reCAPTCHA failed to load. Please reload the page and try again.
                                        </div>
                                        <button v-if="captcha_error" type="button" class="btn btn-secondary" disabled>{{ $t('shop.tour.message.send') }}</button>
                                        <button v-else type="submit" class="btn btn-success">{{ $t('shop.tour.message.send') }}</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>

                    <div class="row" v-else-if="is_loading">
                        <div class="col-md-4 text-center loader_margin">
                            <img :src="'/images/site_img/loading.gif'" class="img-responsive center-block" alt="loading">
                            <p>Pless wait!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    // import VueRecaptchaV2 from 'vue3-recaptcha-v2'; //https://www.npmjs.com/package/vue3-recaptcha-v2
    export default {
        components: { 
            // 'vue-recaptcha': VueRecaptchaV2,
        },
        data () {
            return {
                form_data: {
                    name: '',
                    email: '',
                    phone: '',
                    country: '',
                    city: '',
                    check_in: '',
                    persons: '',
                    text: ''
                },
                user: {},

                is_loading: false,

                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,

                MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,

                is_verify_isset: true,
                captcha_error: false,

                // Flag to prevent duplicate submissions
                is_submitting: false,
            }
        },
        watch: {
            '$route' (to, from) {
                //this.clear_product_data()
            }
        },
        props: [
            'tour_id_prop',
        ],
        mounted() {
            this.get_user_info()
            if (!window.grecaptcha) {
                const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY
                if (key) {
                    const s = document.createElement('script')
                    s.src = `https://www.google.com/recaptcha/api.js?render=${key}`
                    document.head.appendChild(s)
                }
            }
        },
        methods: {
            goTo(page = '/'){
                window.open(process.env.MIX_APP_SSH + 'user.' + process.env.MIX_SITE_URL + page) ;
            },
            async get_recaptcha_token(action = 'reservation') {
                const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY
                if (!key || !window.grecaptcha) return null
                try {
                    await new Promise(resolve => window.grecaptcha.ready(resolve))
                    return await window.grecaptcha.execute(key, { action })
                } catch { return null }
            },
            async create_reservation(){
                // Prevent duplicate submissions
                if (this.is_submitting) {
                    return;
                }

                this.is_submitting = true;
                this.is_loading = true;
                const recaptcha_token = await this.get_recaptcha_token('reservation')
                if (!recaptcha_token) {
                    this.captcha_error = true;
                    this.is_loading = false;
                    this.is_submitting = false;
                    return;
                }

                axios
                .post('/set_user_reservation/create_reservation/'+this.tour_id_prop, {
                    form_data: this.form_data,
                    recaptcha_token,
                })
                .then(response => {
                    // Reset form data
                    this.form_data = {
                        name: '',
                        email: '',
                        phone: '',
                        country: '',
                        city: '',
                        check_in: '',
                        persons: '',
                        text: ''
                    };

                    this.onCaptchaExpired();

                    alert(response.data);
                })
                .catch(error =>{
                    if (error.response?.status === 422 && error.response?.data?.message?.toLowerCase().includes('recaptcha')) {
                        this.captcha_error = true;
                    } else if (error.response && error.response.data && error.response.data.message) {
                        alert(error.response.data.message);
                    } else {
                        alert('An error occurred while creating the reservation.');
                    }
                })
                .finally( () => {
                    this.is_loading = false;
                    this.is_submitting = false;
                });
            },

            onCaptchaVerified() {
                this.is_verify_isset = true
            },
            onCaptchaExpired(){
                this.is_verify_isset = false
            },

            get_user_info() {
                this.user = []
                this.is_loading = true

                axios
                .get('/auth_user/')
                .then(response => {
                    this.user = response.data,
                    
                    this.form_data.name = this.user.name + ' ' + this.user.surname,
                    // this.form_data.surname = this.user.surname,
                    this.form_data.country = this.user.country
                    this.form_data.city = this.user.city
                    this.form_data.phone = this.user.phone_number
                    this.form_data.email = this.user.email
                })
                .catch()
                .finally( () => this.is_loading = false)
            },
        }
    }
</script>
