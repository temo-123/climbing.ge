<template>
    <stack-modal
            :show="is_show_modal"
            title="Product Feedback"
            @close="close_model()"
            :modal-class="{ [ModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{
                title: 'Close',
                btnClass: { 'btn btn-primary': true },
            }"
        >
            <div class="model-body">
                <div class="container">
                    <div class="row">

                        <div class="row justify-content-center" v-show="is_loading">
                            <div class="col-md-4 friendly-loading">
                                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                            </div>
                        </div>

                        <div class="col-md-12" v-show="!is_loading">
                            <form @submit.prevent="add_feedback" id="feedback_form" class="contact-form" method="POST" enctype="multipart/form-data">

                                <div v-if="user.length == 0">
                                    <div class="modal-section">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-label">Name</label>
                                                    <input type="text" name="name" v-model="data.name" class="form-control friendly-input" placeholder="Name" required><br>
                                                    <div class="alert alert-danger" role="alert" v-if="errors.name">
                                                        Name is validation
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="form-label">Surname</label>
                                                    <input type="text" name="surname" v-model="data.surname" class="form-control friendly-input" placeholder="Surname" required><br>
                                                    <div class="alert alert-danger" role="alert" v-if="errors.surname">
                                                        Surname is validation
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Email</label>
                                                    <input type="email" name="email" v-model="data.email" class="form-control friendly-input" placeholder="E_mail" required><br>
                                                    <div class="alert alert-danger" role="alert" v-if="errors.email">
                                                        Email is validation
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div v-else>
                                    <div class="alert alert-danger" role="alert" v-if="errors.name">
                                        Name is validation
                                    </div>
                                    <div class="alert alert-danger" role="alert" v-if="errors.surname">
                                        Surname is validation
                                    </div>
                                    <div class="alert alert-danger" role="alert" v-if="errors.email">
                                        Email is validation
                                    </div>
                                    <div class="alert alert-danger" role="alert" v-if="errors.email || errors.surname || errors.name">
                                        If you use automatically data pres reload page and try again!
                                    </div>
                                </div>
                                

                                <div class="modal-section">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Your Feedback</label>
                                                <textarea rows="6" name="text" v-model="data.text" id="text" maxlength="500" placeholder="Your feedback (Write feedbacks only in English, no more than 500 characters!)" class="form-control friendly-input" required></textarea>
                                                <div class="alert alert-danger" role="alert" v-if="errors.text">
                                                    feedback text is validation
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            
                                <div class="modal-section">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">Do you have this product?</label>
                                                <input type="checkbox" v-model="data.have_product" name="scales" placeholder="Did you climb this route?" title="Did you climb this route?">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal-section">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label class="form-label">How did you like this product?</label>
                                                <span v-if="data.stars > 0"></span>

                                                <starReitingInsert @get_stars="update_stars"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <vue-recaptcha 
                                    :sitekey="MIX_GOOGLE_CAPTCHA_SITE_KEY" 
                                    :loadRecaptchaScript="true"
                                    ref="recaptcha"
                                    type="invisible"
                                    @verify="onCaptchaVerified"
                                    @expired="onCaptchaExpired"
                                >
                                </vue-recaptcha>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <span v-show="!is_loading">
                        <div class="col-xs-6 col-md-6" v-if="is_verify_isset == false">
                            <button class="btn btn-primary" disabled>Add feedback</button>
                        </div>
                        <div class="col-xs-6 col-md-6" v-else>
                            <button type="sumit" form="feedback_form" class="btn btn-primary" >Add feedback</button>
                        </div>
                    </span>
                </div>
            </div>
        </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha

import starReitingInsert from '../../../../global_components/StarReitingInsertComponent.vue'

export default {
    components: { 
        VueRecaptcha,
        StackModal,
        starReitingInsert,
    },
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
            is_verify_isset: false,

            is_loading: false,

            user: [],
            errors: [],

            MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
        };
    },
    mounted() {
        //
    },
    methods: {
        show_modal(id){
            this.is_show_modal = true;
            // this.is_loading = true
            this.clear_data()

            this.product_id = id
            
            this.get_user_info()
        },
        close_model(){
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

        add_feedback() {
            this.data.product_id = this.product_id
            this.data.is_verify_isset = this.is_verify_isset

            this.is_loading = true

            axios
            .post('/product_feedback/create_feedback/' + this.product_id, {
                data: this.data,
            })
            .then(response => {
                this.close_model()

                this.$emit('restart')

                alert(response.data)
            })
            .catch(error => {
                this.is_loading == false
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors
                }
            })
            .finally(() => this.is_loading == false);
        },
    }
}
</script>
