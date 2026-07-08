<template>
<StackModal
            v-model="is_show_modal"
            :title="$t('shop.product.feedback.modal_title')"
            size="lg"
            :modal-class="{ [ModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{
                title: $t('common.close'),
                btnClass: { 'btn btn-primary': true },
            }"
            @close="close_modal()"
        >
            <div class="model-body">
                <div class="container">
                    <div class="row">

                        <div class="row justify-content-center" v-show="is_loading">
                            <div class="col-md-4 friendly-loading">
                                <img :src="'/images/site_img/loading.gif'" alt="loading">
                            </div>
                        </div>

                        <div class="col-md-12" v-show="!is_loading">
                            <form @submit.prevent="add_feedback" id="feedback_form" class="contact-form" method="POST" enctype="multipart/form-data">

                                <div v-if="user.length == 0">
                                    <div class="modal-section">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">{{ $t('common.name') }}</label>
                                                    <input type="text" name="name" v-model="data.name" class="form-control friendly-input" :placeholder="$t('common.name')" required><br>
                                                    <div class="alert alert-danger" role="alert" v-if="errors.name">
                                                        {{ $t('shop.product.feedback.name_required') }}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="form-label">{{ $t('shop.product.feedback.surname') }}</label>
                                                    <input type="text" name="surname" v-model="data.surname" class="form-control friendly-input" :placeholder="$t('shop.product.feedback.surname')" required><br>
                                                    <div class="alert alert-danger" role="alert" v-if="errors.surname">
                                                        {{ $t('shop.product.feedback.surname_required') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">{{ $t('common.email') }}</label>
                                                    <input type="email" name="email" v-model="data.email" class="form-control friendly-input" :placeholder="$t('common.email')" required><br>
                                                    <div class="alert alert-danger" role="alert" v-if="errors.email">
                                                        {{ $t('shop.product.feedback.email_required') }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="alert alert-danger" role="alert" v-if="errors.name">
                                        {{ $t('shop.product.feedback.name_required') }}
                                    </div>
                                    <div class="alert alert-danger" role="alert" v-if="errors.surname">
                                        {{ $t('shop.product.feedback.surname_required') }}
                                    </div>
                                    <div class="alert alert-danger" role="alert" v-if="errors.email">
                                        {{ $t('shop.product.feedback.email_required') }}
                                    </div>
                                    <div class="alert alert-danger" role="alert" v-if="errors.email || errors.surname || errors.name">
                                        {{ $t('shop.product.feedback.autofill_warning') }}
                                    </div>
                                </div>


                                <div class="modal-section">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ $t('shop.product.feedback.your_feedback') }}</label>
                                                <textarea rows="6" name="text" v-model="data.text" id="text" maxlength="500" :placeholder="$t('shop.product.feedback.feedback_placeholder')" class="form-control friendly-input" required></textarea>
                                                <div class="alert alert-danger" role="alert" v-if="errors.text">
                                                    {{ $t('shop.product.feedback.feedback_required') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="modal-section">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ $t('shop.product.feedback.have_product') }}</label>
                                                <input type="checkbox" v-model="data.have_product" name="scales">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-section">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">{{ $t('shop.product.feedback.rate_product') }}</label>
                                                <span v-if="data.stars > 0"></span>

                                                <starReitingInsert @get_stars="update_stars"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- <vue-recaptcha 
                                    :sitekey="MIX_GOOGLE_CAPTCHA_SITE_KEY" 
                                    :loadRecaptchaScript="true"
                                    ref="recaptcha"
                                    type="invisible"
                                    @verify="onCaptchaVerified"
                                    @expired="onCaptchaExpired"
                                >
                                </vue-recaptcha> -->
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <!-- Custom footer moved to content area - StackModal handles buttons -->
            <div v-show="!is_loading" class="text-center pt-4 mt-4 border-t border-gray-200">
                <div v-if="captcha_error" class="alert alert-warning mb-2">
                    <i class="fa fa-exclamation-triangle"></i>
                    {{ $t('shop.product.feedback.recaptcha_error') }}
                </div>
                <button v-if="captcha_error" class="btn btn-secondary" disabled>{{ $t('shop.product.feedback.submit') }}</button>
                <button v-else type="submit" form="feedback_form" class="btn btn-primary">{{ $t('shop.product.feedback.submit') }}</button>
            </div>
        </StackModal>
</template>

<script>



import starReitingInsert from '../../../../global_components/StarReitingInsertComponent.vue'

export default {
    components: { 
        starReitingInsert, // StackModal global
    },
    emits: ['restart'],
    props: [
        // "sector",
    ],
    data: function () {
        return {
            is_show_modal: false,

            product_id: 0,

            ModalClass: "",

            data: {
                stars: 0,
                text: '',
                have_product: '',

                name:'',
                surname:'',
                email: '',

                is_verify_isset: false,
            },
            is_verify_isset: true,
            captcha_error: false,

            is_loading: false,

            user: {},
            errors: [],

            MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
        };
    },
    mounted() {
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
        show_modal(id){
            this.is_show_modal = true;
            this.captcha_error = false;
            this.clear_data()

            this.product_id = id
            
            this.get_user_info()
        },
        close_modal(){
            this.is_show_modal = false;
            
            this.is_loading = false
            
            this.clear_data()
        },

        update_stars(stars){
            this.data.stars = stars
        },

        clear_data(){
            this.user = []

            this.data = {
                stars: 0,
                text: '',
                have_product: '',

                name:'',
                surname:'',
                email: '',
                is_verify_isset: false
            },

            this.data.is_verify_isset = false
        },

        onCaptchaVerified() {
            this.is_verify_isset = true
        },
        onCaptchaExpired(){
            this.is_verify_isset = false
        },

        get_user_info() {
            // this.is_loading = true

            axios
            .get('/auth_user/')
            .then(response => {
                this.user = response.data,
                
                this.data.name = this.user.name,
                this.data.surname = this.user.surname,
                this.data.email = this.user.email
            })
            .catch()
            .finally(() => this.is_loading == false);
        },

        async get_recaptcha_token(action = 'feedback') {
            const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY
            if (!key || !window.grecaptcha) return null
            try {
                await new Promise(resolve => window.grecaptcha.ready(resolve))
                return await window.grecaptcha.execute(key, { action })
            } catch { return null }
        },

        async add_feedback() {
            this.data.product_id = this.product_id

            this.is_loading = true
            const recaptcha_token = await this.get_recaptcha_token('feedback')
            if (!recaptcha_token) {
                this.captcha_error = true;
                this.is_loading = false;
                return;
            }

            axios
            .post('/set_product_feedback_by_gest/create_feedback/' + this.product_id, {
                data: this.data,
                recaptcha_token,
            })
            .then(response => {
                this.close_modal()
                this.$emit('restart')
                alert(response.data)
            })
            .catch(error => {
                this.is_loading = false
                if (error.response?.status === 422) {
                    if (error.response.data?.message?.toLowerCase().includes('recaptcha')) {
                        this.captcha_error = true;
                    } else {
                        this.errors = error.response.data.errors
                    }
                }
            })
            .finally(() => this.is_loading = false);
        },
    }
}
</script>
