<template>
    <stack-modal
            :show="is_show_route_modal"
            title="Route Details"
            @close="is_show_route_modal = false"
            :modal-class="{ [ModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{
                title: 'Close',
                btnClass: { 'btn btn-primary': true },
            }"
        >
            <div class="model-body">
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <div class="container" v-show="!is_loading">
                    <div class="row">
                        <div class="modal-section basic-info">
                            <h2 class="section-title">{{ $t("guide.route.route detals") }}</h2>

                            <p class="route-detail"><strong>{{ $t("guide.route.name") }}</strong> - {{ route.name }}</p>

                            <p class="route-detail" v-if="route.height">
                                <strong>{{ $t("guide.route.height") }}</strong> - {{ route.height }}
                            </p>

                            <p v-if="route.category == 'tred'"><strong>{{ $t("guide.route.bolts") }}</strong> - Tred climbing</p>
                            <p v-else-if="route.category == 'top'"><strong>{{ $t("guide.route.bolts") }}</strong> - Top rope</p>
                            <p v-else-if="route.category == 'boulder'"></p>
                            <p v-else-if="route.category == 'sport climbing'"><strong>{{ $t("guide.route.bolts") }}</strong> - {{ route.bolts }}</p>
                            <!-- <p v-else><strong>{{ $t("guide.route.bolts") }}</strong> - {{ route.bolts }}</p> -->

                            <p class="route-detail"><strong>{{ $t("guide.route.grade fr") }}</strong> -
                                <span v-if="route.or_grade != null">
                                    {{ route.grade }} / {{ route.or_grade }}
                                </span>
                                <span v-else>
                                    {{ route.grade }}
                                </span>
                            </p>

                            <p class="route-detail">
                                <span v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'"><strong>{{ $t("guide.route.grade uiaa") }}</strong></span>
                                <span v-if="activ_grade == 'YDS' || activ_grade == 'yds'"><strong>{{ $t("guide.route.grade yds") }}</strong></span>
                                    -

                                <span v-if="route.or_grade != null">
                                    {{ lead_grade_chart(route.grade) }} /
                                    {{ lead_grade_chart(route.or_grade) }}
                                </span>
                                <span v-else>
                                    {{ lead_grade_chart(route.grade) }}
                                </span>
                            </p>
                        </div>

                        <div class="modal-section additional-info" v-if="route.author || route.creation_data || route.first_ascent">
                            <h3 class="section-title">Additional Information</h3>

                            <p class="route-detail" v-if="route.author">
                                <strong>{{ $t("guide.route.author") }}</strong> - {{ route.author }}
                            </p>

                            <p class="route-detail" v-if="route.creation_data">
                                <strong>{{ $t("guide.route.creating_data") }}</strong> - {{ route.creation_data }}
                            </p>

                            <p class="route-detail" v-if="route.first_ascent">
                                <strong>{{ $t("guide.route.first_ascent") }}</strong> - {{ route.first_ascent }}
                            </p>
                        </div>

                        <div class="modal-section technical-details" v-if="route.anchor_type || route.bolts_type">
                            <h3 class="section-title">Technical Details</h3>

                            <p class="route-detail" v-if="route.anchor_type">
                                <strong>{{ $t("guide.route.anchor_type") }}</strong> - {{ route.anchor_type }}
                            </p>

                            <div class="row">
                                <div class="col-md-6" v-if="route.bolts_type == 'glued'">
                                    <p class="route-detail">
                                        <strong>Route have a glued-in bolts</strong>
                                        <img
                                            class="climbing_bolt_image"
                                            :src="'../../../../images/svg/glued bolt.png'"
                                            alt="Glued-in bolt"
                                            title="Glued-in bolt"
                                        />
                                    </p>
                                </div>
                                <div class="col-md-6" v-if="route.bolts_type == 'hangerr'">
                                    <p class="route-detail">
                                    <strong>Route have a hangerr bolts</strong>
                                        <img
                                            class="climbing_bolt_image"
                                            :src="'../../../../images/svg/hangerr bolt.svg'"
                                            alt="Hangerr bolt"
                                            title="Hangerr bolt"
                                        />
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="modal-section description" v-if="route.text != null">
                            <h3 class="section-title">Description</h3>
                            <div class="description-content">
                                <span v-html="route.text"></span>
                            </div>
                        </div>

                        <div class="modal-section rating" v-if="route.reviews_count > 0">
                            <starsReiting
                                :reviews_count_prop = route.reviews_count
                                :reviews_stars_prop = route.reviews_stars
                            />
                        </div>

                    </div>
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer d-flex flex-column flex-md-row justify-content-between align-items-center">
                    <button
                        class="btn btn-success mb-2 mb-md-0"
                        @click="create_route_review_modal(route.id)"
                        v-if="user.length != 0"
                    >
                        {{ $t('guide.route.make_review') }}
                    </button>
                    
                    <div v-else class="alert alert-info cursor_pointer mb-2 mb-md-0 p-2" role="alert" @click="goTo('/login')" style="border-radius: 5px;">
                        <div class="col-md-12">
                            <p class="mb-0">{{ $t('guide.route.pleas_login') }}</p>
                        </div>
                    </div>

                    <button
                        class="btn btn-primary pool-right"
                        @click="show_route_all_review_modal(route.id)"
                        v-if="route.reviews_count > 0"
                    >
                        {{ $t('guide.route.show_feedbacks') }}
                    </button>
                </div>
            </div>
        </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
import grade_chart  from '../../../../../../mixins/grade_chart_mixin.js'
import starsReiting  from '../../../../../global_components/StarReitingShowComponent.vue'

export default {
    mixins: [
        grade_chart,
    ],
    components: {
        StackModal,
        starsReiting,
    },
    props: [
        // "sector",
    ],
    data: function () {
        return {
            is_show_route_modal: false,

            is_loading: false,

            route: [],
            user: [],
            ModalClass: '',

            get activ_grade() {
                return localStorage.getItem('grade') || 'yds';
            },
            set activ_grade(value) {
                localStorage.setItem('grade', value);
            },
        };
    },
    mounted() {
        // this.get_spot_rocks_images();
    },
    methods: {
        goTo(page = '/'){
            this.is_show_route_modal = false
            window.open(process.env.MIX_APP_SSH + 'user.' + process.env.MIX_SITE_URL + page);
        },

        lead_grade_chart(grade_fr) {
            return this.lead(grade_fr)
        },

        create_route_review_modal(route_id){
            this.is_show_route_modal = false;    

            this.$emit('show_route_review_modal', route_id)
        },

        show_route_all_review_modal(route_id){
            this.is_show_route_modal = false;    

            this.$emit('show_route_all_review_modal', route_id)
        },

        show_route_modal(id) {
            this.is_show_route_modal = true;

            this.clear_data()

            this.auth_user()
            this.get_route_data(id)
        },

        clear_data(){
            this.route = []
            this.user = []
        },

        get_route_data(id){
            this.route = [];
            this.is_loading = true

            axios
            .get("../../api/route/get_route_for_modal/" + id)
            .then((response) => {
                this.route = response.data;

                if(this.route.reviews_count > 0){
                    // this.$refs.stars_reiting_modal.colculate_stars(this.route.reviews_stars, this.route.reviews_count)
                }
            })
            .catch((error) => {
                //
            })
            .finally(() => {
                this.is_loading = false
            });
        },

        auth_user(){
            axios
            .get('/auth_user/')
            .then(response => {
                this.user = response.data
            })
            .catch()
        }
    }
}
</script>

<style>
.card{
    width:380px;
    border:none;
    height:280px;
}

.climbing_bolt_image{
    height: 50px;
}

@media (max-width: 756px) {
    .climbing_bolt_image {
        height: 35px;
    }
}

/* Friendly modal styles */

.section-title {
    font-weight: 600;
    font-size: 1.8rem;
    color: #495057;
    margin-bottom: 1rem;
    border-bottom: 2px solid #007bff;
    padding-bottom: 0.5rem;
}

.route-detail {
    margin-bottom: 0.5rem;
    font-size: 1.6rem;
    line-height: 1.7;
    color: #343a40;
}

.description-content {
    font-size: 1.5rem;
    line-height: 1.8;
    color: #495057;
}

.rating {
    text-align: center;
}

/* Improve button styles */
.btn {
    border-radius: 5px;
    font-weight: 500;
}

.btn-success {
    background-color: #28a745;
    border-color: #28a745;
}

.btn-primary {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
