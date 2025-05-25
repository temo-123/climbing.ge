<template>
    <stack-modal
            :show="is_show_modal"
            title="Edit review"
            @close="close_modal(route_id)"
            :modal-class="{ [ModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{
                title: 'Close',
                btnClass: { 'btn btn-primary': true },
            }"
        >
            <div class="model-body">
                <div class="container">  
                    <div class="row justify-content-center" v-show="is_loading">
                        <div class="col-md-4">
                            <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                        </div>
                    </div>

                    <div class="row">
                        <form method="POST" id="route_review_form" v-on:submit.prevent="edit_route_review" v-show="!is_loading" style="width: 100%;">
                            Did you climb this route?
                            <input type="checkbox" v-model="data.climbed" name="scales" placeholder="Did you climb this route?" title="Did you climb this route?">

                            <div>
                                <span v-if="data.climbed">Insert climb data</span>
                                <input type="datetime-local" class="form-control" v-model="data.climbed_data" name="climbed_data" placeholder="Climbed data" title="Climbed data" v-if="data.climbed">
                            </div>

                            <div>
                                <span>How did you like this route?</span>
                                <span v-if="data.stars > 0"></span>

                                <starReitingInsert :actyve_stars_prop=data.stars @get_stars="update_stars"/>
                            </div>

                            <div>
                                <span>What do you think about this route?</span>
                                <textarea id="feadback" name="feadback" class="form-control" placeholder="Make feadback" v-model="data.text" rows="4"></textarea>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <div class="form-group">
                        <button type="submit" form="route_review_form" class="btn btn-success float-right">Save</button>
                    </div>
                </div>
            </div>
        </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css

import starReitingInsert from '../../../../global_components/StarReitingInsertComponent.vue'

export default {
    components: { 
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
            },

            ModalClass: [],

            is_loading: false,

            MIX_GOOGLE_CAPTCHA_SITE_KEY: process.env.MIX_GOOGLE_CAPTCHA_SITE_KEY,
        };
    },
    mounted() {
        //
    },
    methods: {
        show_modal(route_id){
            this.is_show_modal = true;
            this.route_id = route_id
            this.get_editing_review()
        },

        close_modal(route_id){
            this.is_show_modal = false;
            this.clear_data()
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

        get_editing_review(){
            this.is_loading = true
            axios
                .get("/route_review/get_actyve_review/" + this.route_id)
                .then((response) => {
                    this.data = {
                        stars: response.data.stars,
                        text: response.data.text,
                        climbed_data: response.data.climbed_data,
                    }
                })
                .catch((error) => {
                    // 
                })
                .finally(() => this.is_loading = false);
        },

        edit_route_review() {
            this.is_loading = true
            axios
                .post("/route_review/edit_route_review/" + this.route_id, this.data)
                .then((response) => {
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