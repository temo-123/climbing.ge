<template>
    <stack-modal
                :show="is_user_feedback_complaint_model"
                title="Please select a reason for deleting the feedback"
                @close="is_user_feedback_complaint_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <div class="row justify-content-center" v-if="complaint_loader">
                    <div class="col-md-4">
                        <img :src="'../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <span v-if="!complaint_loader">
                    <h1>You can file a complaint for this feedback</h1>
                    <p>Please select a reason for deleting the feedback!!!</p>
                    
                    <form v-on:submit.prevent="make_complaint" id="make_complaint" class="form">
                        <input v-if="user.length == 0" type="email" name="complainter email" v-model="complainter_email" class="form-control textarea" placeholder="Your email">

                        <select class="form-control" v-model="selected_feedback_complaint" name="feedback delete cause" > 
                            <option value="Hostile remarks">Hostile remarks</option>
                            <option value="Does not match the theme of the site">Does not match the theme of the site</option>
                            <option value="Spam">Spam</option>
                            <option value="Sexual content">Sexual content</option>
                            <option value="Expression of anger">Expression of anger</option>
                            <option value="Conflict with other members of the site">Conflict with other members of the site</option>
                            <option value="The language of the feedbacks does not match the requirements of the site">The language of the feedbacks does not match the requirements of the site</option>
                        </select>

                        <vue-recaptcha 
                            :sitekey="MIX_GOOGLE_CAPTCHA_SITE_KEY" 
                            :loadRecaptchaScript="true"
                            :recaptchaHost="recaptchaHost"
                            ref="recaptcha"
                            type="invisible"
                            @verify="onCaptchaVerified"
                            @expired="onCaptchaExpired"
                        >
                        </vue-recaptcha>
                    </form>
                </span>
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="make_complaint"
                        v-if="is_verify_isset == false"
                        disabled
                    >
                    Submit
                    </button> 
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="make_complaint"
                        v-else
                    >
                    Submit
                    </button> 
                </div>
            </div>
        </stack-modal>

</template>
<script>
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha

    export default {
        components: {
            StackModal,
            // SlickItem,
            // SlickList,
            VueRecaptcha,
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
                user: [],
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
                // alert('test')
                this.complaint_feedback_id = feedback_id
                this.is_user_feedback_complaint_model = true
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