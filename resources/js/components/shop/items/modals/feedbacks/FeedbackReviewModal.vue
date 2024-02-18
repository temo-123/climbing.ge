<template>
    <stack-modal
            :show="is_show_modal"
            title="Product feedack"
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
                        <!-- <h2>{{ $t("guide.route.route_rewiev") }}</h2> -->
                         
                        <div class="row justify-content-center" v-show="is_loading">
                            <div class="col-md-4">
                                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                            </div>
                        </div>

                    <div>

                        <div class="col-md-12">
                            <form @submit.prevent="add_feedback" id="feedback_form" class="contact-form" method="POST" enctype="multipart/form-data">
                                                
                                <!-- <div class="row">
                                    <div class="col-md-12">
                                        <h2 id='feedbacks'>{{ $t('guide.article.title.feedbacks')}}</h2>
                                    </div>
                                </div> -->

                                <div v-if="user.length == 0">
                                    <div class="row" >
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input type="text" name="name" v-model="name" class="form-control textarea" placeholder="Name"><br>
                                                <div class="alert alert-danger" role="alert" v-if="errors.name">
                                                    Name is validation
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <input type="text" name="surname" v-model="surname" class="form-control textarea" placeholder="Surname"><br>
                                                <div class="alert alert-danger" role="alert" v-if="errors.surname">
                                                    Surname is validation
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="email" name="email" v-model="email" class="form-control textarea" placeholder="E_mail"><br>
                                                <div class="alert alert-danger" role="alert" v-if="errors.email">
                                                    Email is validation
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
                                

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <textarea rows="6" name="text" v-model="text" id="text" maxlength="500" placeholder="Your feedback (Write feedbacks only in English, no more than 500 characters!)" class="form-control textarea"></textarea>
                                            <div class="alert alert-danger" role="alert" v-if="errors.text">
                                                feedback text is validation
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            Do you have this product?
                                            <input type="checkbox" v-model="data.climbed" name="scales" placeholder="Did you climb this route?" title="Did you climb this route?">
                                        </div>
                                    </div>
                                </div>

                                <!-- <div>
                                    <span v-if="data.climbed">Insert climb data</span>
                                    <input type="datetime-local" class="form-control" v-model="data.climbed_data" name="climbed_data" placeholder="Climbed data" title="Climbed data" v-if="data.climbed">
                                </div> -->

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <span>How did you like this route?</span>
                                            <span v-if="data.stars > 0"></span>

                                            <starReitingInsert @get_stars="update_stars"/>
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
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">

                    <!-- <div class="col-md-6">
                        <div class="row"> -->
                            <div class="col-xs-6 col-md-6" v-if="is_verify_isset == false">
                                <button class="btn btn-primary" disabled>Add feedback</button>
                            </div>
                            <div class="col-xs-6 col-md-6" v-else>
                                <button type="sumit" form="feedback_form" class="btn btn-primary" >Add feedback</button>
                            </div>
                            <!-- <div class="col-xs-6 col-md-6">
                                <button @click="get_feedbacks" class="btn btn-success pull-right" v-if="!is_refresh">Refresh ({{refresh_id}})</button>
                                <span class="badge badge-primare mb-1 pull-right" v-if="is_refresh">Updating...</span>
                            </div> -->
                        <!-- </div>
                    </div> -->

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

            data: {
                stars: 0,
                text: '',
                climbed_data: '',
            },
            // hover: false,

            is_loading: false,


            user: [],
            errors: [],

            is_verify_isset: false,
            is_mail_sending: false,

            MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
        };
    },
    mounted() {
        this.get_user_info()
    },
    methods: {
        show_modal(id){
            this.is_show_modal = true;
            this.product_id = id
        },
        close_model(){
            this.is_show_modal = false;
            
            this.clear_data()
        },

        update_stars(stars){
            this.data.stars = stars
        },

        clear_data(){
            this.data = {
                stars: 0,
                text: '',
                climbed_data: '',
            },
            
            this.is_verify_isset = false
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

        add_feedback() {
            axios
            .put('/product_feedback/create_feedback/' + this.id, {
                name: this.name,
                is_verify_isset: this.is_verify_isset,
                surname: this.surname,
                email: this.email,
                text: this.text,
                product_id: this.id
            })
            .then(response => {
                // this.get_feedbacks()
            })
            .catch(error => {
                if (error.response.status == 422) {
                    this.errors = error.response.data.errors
                }
            })
        },
    }
}
</script>
