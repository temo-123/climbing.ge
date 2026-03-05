<template>
    <div>
        <button @click="show_modal()" class="btn btn-success" >{{ $t('shop.product.message.title') }}</button>

        <stack-modal
                :show="is_product_order_message_form_model"
                title="Please select a reason for deleting the feedback"
                @close="is_product_order_message_form_model=false"
                :saveButton="{ visible: true, title: 'Save', btnClass: { 'btn btn-primary': true } }"
                :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
            >
            <pre class="language-vue">
                <div class="justify-content-center" v-if="is_loader">
                    <div class="col-md-4">
                        <img :src="'../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>
                
                <div v-if="!is_loader">
                    <h1>{{ $t('shop.product.message.form_title') }}</h1>

                    <div :class="'alert alert-warning'" role="alert">
                        <div class="col-md-12">
                            <p>{{ $t('shop.product.message.form_alert') }}</p>
                        </div>
                    </div>

                    <form v-on:submit.prevent="send_message" id="send_order_message">
                        <!-- <label class="control-label" for="textarea">Message</label> -->
                        <p>{{ $t('shop.product.message.form_number_area') }}</p>
                        <input type="text" name="name" v-model="form_data.number"  class="form-control"> 

                        <p>{{ $t('shop.product.message.form_text_area') }}</p>
                        <textarea v-model="form_data.text" class="form-control" id="textarea" name="textarea" rows="10" minlength="50" placeholder="Write Your Requirements" required></textarea>

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
            </pre>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <!-- <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="send_order_message"
                        v-if="is_verify_isset == false"
                        disabled
                    >
                    Send email
                    </button>  -->
                    <button
                        type="submit"
                        :class="{'btn btn-primary': true}"
                        form="send_order_message"
                    >
                    {{ $t('shop.product.message.send') }}
                    </button> 
                </div>
            </div>
        </stack-modal>
    </div>
</template>
<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha
    export default {
        components: {
            StackModal,
            VueRecaptcha,
        },
        props: [
            "product_id_prop",
        ],
        data() {
            return {
                is_verify_isset: false,
                is_loader: false,
                is_product_order_message_form_model: false,

                loadRecaptchaScript: false,

                form_data: {
                    text: '',
                    number: '',
                },

                MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
            }
        },
        watch: {
            // product_id: function(){
                // this.get_feedbacks()
            // },
        },
        mounted() {
            // this.get_user_info()
        },
        methods: {
            onCaptchaVerified() {
                this.is_verify_isset = true
            },
            onCaptchaExpired(){
                this.is_verify_isset = false
            },

            show_modal(feedback_id){
                this.complaint_feedback_id = feedback_id
                this.is_product_order_message_form_model = true
            },

            close_modal(){
                this.is_product_order_message_form_model = false
                this.form_data = {
                    text: '',
                    number: ''
                }
            },

            send_message(){
                this.is_loader = true
                axios
                .post('/order/castam_prodaction_message/'+this.product_id_prop,{
                    form_data: this.form_data
                })
                .then(response => {
                    alert(response.data)
                    this.close_modal()
                })
                .catch()
                .finally(() => this.is_loader = false);
            },
        }
    }
</script>