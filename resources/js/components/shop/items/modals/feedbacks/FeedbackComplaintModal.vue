<template>
    <StackModal
                v-model="is_user_feedback_complaint_model"
                :title="$t('shop.product.feedback.complaint_modal_title')"
                @close="close_modal"
                :saveButton="{ visible: true, title: $t('shop.product.feedback.save'), btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: true, title: $t('shop.product.feedback.close'), btnClass: { 'btn btn-secondary': true } }"
            >
            <pre class="language-vue">
                <div class="row justify-content-center" v-if="complaint_loader">
                    <div class="col-md-4">
                        <img :src="'/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <span v-if="!complaint_loader">
                    <h1>{{ $t('shop.product.feedback.complaint_heading') }}</h1>
                    <p>{{ $t('shop.product.feedback.complaint_description') }}</p>

                    <form v-on:submit.prevent="make_complaint" id="make_complaint" class="form">
                        <input v-if="user.length == 0" type="email" name="complainter email" v-model="complainter_email" class="form-control textarea" :placeholder="$t('shop.product.feedback.complainter_email')">

                        <select class="form-control" v-model="selected_feedback_complaint" name="feedback delete cause" >
                            <option value="Hostile remarks">{{ $t('shop.product.feedback.reasons.hostile') }}</option>
                            <option value="Does not match the theme of the site">{{ $t('shop.product.feedback.reasons.off_topic') }}</option>
                            <option value="Spam">{{ $t('shop.product.feedback.reasons.spam') }}</option>
                            <option value="Sexual content">{{ $t('shop.product.feedback.reasons.sexual_content') }}</option>
                            <option value="Expression of anger">{{ $t('shop.product.feedback.reasons.anger') }}</option>
                            <option value="Conflict with other members of the site">{{ $t('shop.product.feedback.reasons.conflict') }}</option>
                            <option value="The language of the feedbacks does not match the requirements of the site">{{ $t('shop.product.feedback.reasons.language') }}</option>
                        </select>

                        <!-- <vue-recaptcha 
                            :sitekey="MIX_GOOGLE_CAPTCHA_SITE_KEY" 
                            :loadRecaptchaScript="true"
                            :recaptchaHost="recaptchaHost"
                            ref="recaptcha"
                            type="invisible"
                            @verify="onCaptchaVerified"
                            @expired="onCaptchaExpired"
                        >
                        </vue-recaptcha> -->
                    </form>
                </span>
            </pre>
        </StackModal>

</template>
<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    // import VueRecaptchaV2 from 'vue3-recaptcha-v2'; //https://www.npmjs.com/package/vue3-recaptcha-v2

    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
            // 'vue-recaptcha': VueRecaptchaV2,
        },
        props: [
            "product_id",
        ],
        data() {
            return {
                name: "",
                surname: "",
                email: "",
                text: "",
                
                ModalClass: "",

                show_feadbacks: false,

                is_verify_isset: false,
                is_complaint_verify_isset: false,

                feedbacks: [],
                is_refresh: false,
                refresh_id: 0,
                id: 0,
                loadRecaptchaScript: false,
                // loadComplaintRecaptchaScript: false,

                errors: [],
                user: {},
                user_id: 0,

                complaint_feedback_id: 0,
                selected_feedback_complaint: 'Hostile remarks',
                is_user_feedback_complaint_model: false,

                MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
                complainter_email: '',
                complaint_loader: false,

                id: this.product_id
            }
        },
        watch: {
            product_id: function(){
                this.id = this.product_id
                // this.get_feedbacks()
            },
        },
        mounted() {
            // this.get_feedbacks()
            this.get_user_info()
        },
        methods: {
            update(id){
                this.id = id
            },

            onCaptchaVerified() {
                this.is_verify_isset = true
            },
            onCaptchaExpired(){
                this.is_verify_isset = false
            },

            get_user_info() {
                this.user = []
                    
                this.name = '',
                this.surname = '',
                this.email = ''
                
                axios
                .get('/auth_user/')
                .then(response => {
                    this.user = response.data,
                    
                    this.name = this.user.name,
                    this.surname = this.user.surname,
                    this.email = this.user.email

                    this.complainter_email = this.user.email
                })
                .catch()
            },

            show_modal(feedback_id){
                this.complaint_feedback_id = feedback_id
                this.is_user_feedback_complaint_model = true
            },
            close_modal(){
                this.is_user_feedback_complaint_model = false
            },

            make_complaint(){
                this.complaint_loader = true
                axios
                .post('/set_product_feedback_by_gest/add_feedback_complaint/',{
                    feedback_id: this.complaint_feedback_id,
                    feedback_complaint: this.selected_feedback_complaint,
                    email: this.complainter_email,
                })
                .then(response => {
                    this.is_user_feedback_complaint_model = false
                    this.selected_feedback_complaint = 'Hostile remarks'
                    // alert(response.data);
                })
                .catch()
                .finally(() => this.complaint_loader = false);
            },
        }
    }
</script>