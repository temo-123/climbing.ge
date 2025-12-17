<template>
    <stack-modal
            :show="is_show_modal"
            title="Create route feedback"
            @close="close_route_review_modal(route_id)"
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
                        <!-- <h2 class="main-title">{{ $t("guide.route.route_rewiev") }}</h2> -->

                        <div class="row justify-content-center" v-show="is_loading">
                            <div class="col-md-4 friendly-loading">
                                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                            </div>
                        </div>

                        <form method="POST" id="route_review_form" v-on:submit.prevent="add_route_review" v-show="!is_loading">
                            <div class="modal-section climbing-status">
                                <h3 class="section-title">Climbing Status</h3>
                                <div class="form-group">
                                    <label class="form-label">Did you climb this route?</label>
                                    <input type="checkbox" v-model="data.climbed" name="scales" class="form-check-input">
                                </div>
                            </div>

                            <div class="modal-section climb-details" v-if="data.climbed">
                                <h3 class="section-title">Climb Details</h3>
                                <div class="form-group">
                                    <label class="form-label">Insert climb date</label>
                                    <input type="date" class="form-control friendly-input" v-model="data.climbed_data" name="climbed_data">
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Route ascent style</label>
                                    <select class="form-control friendly-input" v-model="data.ascent_style" name="ascent_style" required>
                                        <option value="" disabled>Select ascent style</option>
                                        <option value="onsite">Onsite</option>
                                        <option value="flash">Flash</option>
                                        <option value="second_go">Second go</option>
                                        <option value="redpoint">Redpoint</option>
                                        <option value="top_rope">Top rope</option>
                                    </select>
                                </div>
                            </div>

                            <div class="modal-section rating">
                                <h3 class="section-title">Rating</h3>
                                <div class="form-group">
                                    <label class="form-label">How did you like this route?</label>
                                    <starReitingInsert @get_stars="update_stars"/>
                                </div>
                            </div>

                            <div class="modal-section feedback">
                                <h3 class="section-title">Feedback</h3>
                                <div class="form-group">
                                    <label class="form-label">What do you think about this route?</label>
                                    <textarea id="feedback" name="feedback" class="form-control friendly-input" placeholder="Share your thoughts about the route..." v-model="data.text" rows="4"></textarea>
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
            <div slot="modal-footer">
                <div class="modal-footer d-flex justify-content-between">
                    <button
                        class="btn btn-warning float-left"
                        @click="close_route_review_modal(route_id)"
                    >
                        {{ $t('guide.route.back_to_modal') }}
                    </button>

                    <!-- <div class="form-group"  v-if="is_verify_isset == false"> -->
                        <button v-if="is_verify_isset == false" type="submit" class="btn btn-success float-right" disabled>{{ $t("global.form.save") }}</button>
                    <!-- </div>
                    <div class="form-group"  v-else> -->
                        <button v-else type="submit" form="route_review_form" class="btn btn-success float-right">{{ $t("global.form.save") }}</button>
                    <!-- </div> -->
                </div>
            </div>
        </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha

import starReitingInsert from '../../../../../../global_components/StarReitingInsertComponent.vue'

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

            route_id: 0,

            data: {
                stars: 0,
                text: '',
                climbed_data: '',
                ascent_style: ''
            },
            ModalClass: '',

            is_loading: false,

            is_verify_isset: false,
            is_mail_sending: false,

            MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
        };
    },
    mounted() {
        //
    },
    methods: {
        show_route_review_modal(route_id){
            this.is_show_modal = true;
            this.route_id = route_id
        },
        close_route_review_modal(route_id){
            this.is_show_modal = false;
            this.back_to_route_madal(route_id)
            this.clear_data()
        },

        back_to_route_madal(route_id){
            this.$emit('back_route_modal', route_id)
        },

        clear_data(){
            this.data = {
                stars: 0,
                text: '',
                climbed_data: '',
            },
            
            this.is_verify_isset = false
        },

        update_stars(stars){
            this.data.stars = stars
        },

        onCaptchaVerified() {
            this.is_verify_isset = true
        },
        onCaptchaExpired(){
            this.is_verify_isset = false
        },

        add_route_review() {
            this.is_loading = true
            axios
                .post("/set_route_review/create_route_review/" + this.route_id, 
                    this.data
                )
                .then((response) => {
                    alert(response.data)
                    this.close_route_review_modal(this.route_id)
                })
                .catch((error) => {
                    // 
                })
                .finally(() => this.is_loading = false);
        },
    }
}
</script>

<style>
/* Friendly modal styles */

.main-title {
    font-weight: 600;
    font-size: 1.8rem;
    color: #495057;
    margin-bottom: 1.5rem;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
}

.section-title {
    font-weight: 600;
    font-size: 1.4rem;
    color: #495057;
    margin-bottom: 1rem;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
}

.form-label {
    font-weight: 500;
    font-size: 1.2rem;
    margin-bottom: 0.5rem;
    display: block;
}

.friendly-input {
    font-size: 1.1rem;
    padding: 0.5rem;
    border-radius: 5px;
    border: 1px solid #ced4da;
}



</style>
