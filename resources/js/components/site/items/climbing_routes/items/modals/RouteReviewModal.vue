<template>
    <stack-modal
            :show="is_show_modal"
            title="Route detals"
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
                        <h2>{{ $t("guide.route.route_rewiev") }}</h2>
                         
                        <div class="row justify-content-center" v-show="is_loading">
                            <div class="col-md-4">
                                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                            </div>
                        </div>

                        <form method="POST" id="route_review_form" v-on:submit.prevent="add_route_review" v-show="!is_loading">
                            <div>
                                Did you climb this route?
                                <input type="checkbox" v-model="data.climbed" name="scales" placeholder="Did you climb this route?" title="Did you climb this route?">
                            </div>

                            <div v-if="data.climbed">
                                <span>Insert climb data</span>
                                <input type="date" class="form-control" v-model="data.climbed_data" name="climbed_data" placeholder="Climbed data" title="Climbed data">
                            </div>

                            <div v-if="data.climbed">
                                <span >Route ascent style</span>
                                <select class="form-control" v-model="data.ascent_style" name="comment delete cause" required> 
                                    <option value="" disabled>Select ascent style</option>
                                    <option value="onsite">Onsite</option>
                                    <option value="flesh">Flesh</option>
                                    <option value="second_go">Second go</option>
                                    <option value="redpoint">Redpoint</option>
                                    <option value="top_rope">Top rope</option>
                                </select>
                            </div>

                            <div>
                                <span>How did you like this route?</span>
                                <span v-if="data.stars > 0"></span>

                                <starReitingInsert @get_stars="update_stars"/>
                            </div>

                            <div>
                                <span>What do you think about this route?</span>
                                <textarea id="feadback" name="feadback" class="form-control" placeholder="Make feadback" v-model="data.text" rows="4"></textarea>
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
                <div class="modal-footer">
                    <div class="form-group"  v-if="is_verify_isset == false">
                        <button type="submit" class="btn btn-success float-right" disabled>{{ $t("global.message.save") }}</button>
                    </div>
                    <div class="form-group"  v-else>
                        <button type="submit" form="route_review_form" class="btn btn-success float-right">{{ $t("global.message.save") }}</button>
                    </div>
                </div>
            </div>
        </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha

import starReitingInsert from '../../../../../global_components/StarReitingInsertComponent.vue'

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
                .post("/route_review/create_route_review/" + this.route_id, this.data)
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
.rating-header {
    margin-top: -10px;
    margin-bottom: 10px;
}
</style>