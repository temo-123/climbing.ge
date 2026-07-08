<template>
    <StackModal
            v-model="is_show_modal"
            :title="$t('admin.comments.edit_review_title')"
            @close="close_modal(route_id)"
            :modal-class="{ [ModalClass]: true }"
            :saveButton="{ visible: true }"
            @save="edit_route_review"
            :cancelButton="{
                title: $t('common.close'),
                btnClass: { 'btn btn-primary': true },
            }"
        >
            <div class="model-body">
                <div class="container">
                    <div class="row justify-content-center" v-show="is_loading">
                        <div class="col-md-4">
                            <img :src="'/images/site_img/loading.gif'" alt="loading">
                        </div>
                    </div>

                    <div class="row">
                        <form method="POST" id="route_review_form" v-on:submit.prevent="edit_route_review" v-show="!is_loading" style="width: 100%;">
                            {{ $t('admin.comments.did_you_climb_route_label') }}
                            <input type="checkbox" v-model="data.climbed" name="scales" :placeholder="$t('admin.comments.did_you_climb_route_label')" :title="$t('admin.comments.did_you_climb_route_label')">

                            <div>
                                <span v-if="data.climbed">{{ $t('admin.comments.insert_climb_date_label') }}</span>
                                <input type="datetime-local" class="form-control" v-model="data.climbed_data" name="climbed_data" :placeholder="$t('admin.comments.climbed_date_placeholder')" :title="$t('admin.comments.climbed_date_placeholder')" v-if="data.climbed">
                            </div>

                            <div>
                                <span>{{ $t('admin.comments.how_did_you_like_route_label') }}</span>
                                <span v-if="data.stars > 0"></span>

                                <starReitingInsert :actyve_stars_prop=data.stars @get_stars="update_stars"/>
                            </div>

                            <div>
                                <span>{{ $t('admin.comments.what_do_you_think_route_label') }}</span>
                                <textarea id="feadback" name="feadback" class="form-control" :placeholder="$t('admin.comments.make_feedback_placeholder')" v-model="data.text" rows="4"></textarea>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </StackModal>
</template>

<script>
import starReitingInsert from '../../../../global_components/StarReitingInsertComponent.vue'

export default {
    components: { 
        starReitingInsert,
    },
    props: [
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
                .get("/set_route/set_route_review/get_actyve_review/" + this.route_id)
                .then((response) => {
                    this.data = {
                        stars: response.data.stars,
                        text: response.data.text,
                        climbed_data: response.data.climbed_data,
                    }
                })
                .catch((error) => {
                })
                .finally(() => this.is_loading = false);
        },

        edit_route_review() {
            this.is_loading = true
            axios
                .post("/set_route/set_route_review/edit_route_review/" + this.route_id, this.data)
                .then((response) => {
                    this.$emit('restart');
                    this.close_modal();
                })
                .catch((error) => {
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
