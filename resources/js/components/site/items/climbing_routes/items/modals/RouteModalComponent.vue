<template>
    <stack-modal
            :show="is_show_route_modal"
            title="Route detals"
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
                        <h2>{{ $t("guide.route.route detals") }}</h2>
                        
                        <p class="route_detal">{{ $t("guide.route.name") }} - {{ route.name }}</p>

                        <p class="route_detal" v-if="route.height">
                            {{ $t("guide.route.height") }} - {{ route.height }}
                        </p>

                        <p v-if="route.category == 'tred'">{{ $t("guide.route.bolts") }} - Tred climbing</p>
                        <p v-else-if="route.category == 'top'">{{ $t("guide.route.bolts") }} - Top rope</p>
                        <p v-else-if="route.category == 'boulder'"></p>
                        <p v-else-if="route.category == 'sport climbing'">{{ $t("guide.route.bolts") }} - {{ route.bolts }}</p>
                        <!-- <p v-else>{{ $t("guide.route.bolts") }} - {{ route.bolts }}</p> -->

                        <p>{{ $t("guide.route.grade fr") }} - 
                            <span v-if="route.or_grade != NULL">
                                {{ route.grade }} / {{ route.or_grade }}
                            </span>
                            <span v-else>
                                {{ route.grade }}
                            </span>
                        </p>

                        <p>
                            <span v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'">{{ $t("guide.route.grade uiaa") }}</span>
                            <span v-if="activ_grade == 'YDS' || activ_grade == 'yds'">{{ $t("guide.route.grade yds") }}</span>
                                -
                            
                            <span v-if="route.or_grade != NULL">
                                {{ lead_grade_chart(route.grade) }} /
                                {{ lead_grade_chart(route.or_grade) }}
                            </span>
                            <span v-else>
                                {{ lead_grade_chart(route.grade) }}
                            </span>
                        </p>

                        <p class="route_detal" v-if="route.author">
                            {{ $t("guide.route.author") }} - {{ route.author }}
                        </p>

                        <p class="route_detal" v-if="route.anchor_type">
                            {{ $t("guide.route.anchor_type") }} - {{ route.anchor_type }}
                        </p>

                        <p class="route_detal" v-if="route.creation_data">
                            {{ $t("guide.route.creating_data") }} - {{ route.creation_data }}
                        </p>

                        <p class="route_detal" v-if="route.first_ascent">
                            {{ $t("guide.route.first_ascent") }} - {{ route.first_ascent }}
                        </p>

                        <div class="row">
                            <div class="col-md-6" v-if="route.bolts_type == 'glued'">
                                <p>
                                    Route have a glued-in bolts
                                    <img
                                        class="climbing_bolt_image"
                                        :src="'../../../../images/svg/glued bolt.png'"
                                        alt="Glued-in bolt"
                                        title="Glued-in bolt"
                                    />
                                </p>
                            </div>
                            <div class="col-md-6" v-if="route.bolts_type == 'hangerr'">
                                <p>
                                    Route have a hangerr bolts
                                    <img
                                        class="climbing_bolt_image"
                                        :src="'../../../../images/svg/hangerr bolt.svg'"
                                        alt="Hangerr bolt"
                                        title="Hangerr bolt"
                                    />
                                </p>
                            </div>
                        </div>
                        
                        <hr v-if="route.text != null">

                        <span v-html="route.text" v-if="route.text != null"></span>

                        <div class="row" v-if="route.reviews_count > 0">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="ratings">                                        
                                        <i class="fa fa-star rating-color" v-for="i in stars.whole_stars" :key="i"></i>
                                        <i class="fa fa-star-half-o rating-color" v-if="stars.part_stars != 0"></i>
                                        <i class="fa fa-star" v-for="i in stars.other_stars" :key="i"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h5 class="review-count">{{ route.reviews_count }} Reviews ({{ route.reviews_stars }} Stars)</h5>
                            </div>
                        </div>

                        <button
                            class="btn btn-success"
                            @click="route_review_modal(route.id)"
                        >
                            {{ $t('guide.route.make_review') }}
                        </button>
                        
                    </div>
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <!-- footer -->
                </div>
            </div>
        </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
import  grade_chart  from '../../../../../../mixins/grade_chart_mixin.js'

export default {
    mixins: [
        grade_chart,
    ],
    components: {
        StackModal,
    },
    props: [
        // "sector",
    ],
    data: function () {
        return {
            is_show_route_modal: false,

            is_loading: false,

            route: [],

            stars: {
                whole_stars: 0,
                part_stars: 0,
                other_stars: 0,
            },

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
        lead_grade_chart(grade_fr) {
            return this.lead(grade_fr)
        },

        route_review_modal(route_id){
            this.is_show_route_modal = false;

            this.$emit('show_route_review_modal', route_id)
        },

        colculate_stars(stars){
            if(stars % 1 == 0){
                this.stars.part_stars = 0 // get number after comma
                this.stars.whole_stars = Math.floor(stars) // get number befor comma
            }
            else{
                this.stars.part_stars = Number((stars+' ').split(".")[1].substr(0,1)); // get number after comma
                this.stars.whole_stars = Math.floor(stars) // get number befor comma
            }

            // colculate empty srats
            if(this.stars.part_stars > 0){
                this.stars.other_stars = 4 - this.stars.whole_stars
            }
            else{
                this.stars.other_stars = 5 - this.stars.whole_stars
            }
        },

        show_route_modal(id) {
            this.is_show_route_modal = true;
            this.route = [];
            this.is_loading = true

            axios
                .get("../../api/route/get_route_for_modal/" + id)
                .then((response) => {
                    this.route = response.data;

                    if(this.route.reviews_count > 0){
                        this.colculate_stars(this.route.reviews_stars)
                    }
                })
                .catch((error) => {})
                .finally(() => this.is_loading = false);
        },
    }
}
</script>

<style>
.height-100{
    height:100vh;
}

.card{
    width:380px;
    border:none;
    height:280px;
}

.ratings{
    margin-right:10px;
}

.ratings i{
    
    color:#cecece;
    font-size:32px;
}

.rating-color{
    color:#fbc634 !important;
}

.review-count{
    font-weight:400;
    margin-bottom:2px;
    font-size:24px !important;
}

.small-ratings i{
  color:#cecece;   
}

.review-stat{
    font-weight:300;
    font-size:18px;
    margin-bottom:2px;
}
.climbing_bolt_image{
    height: 50px;
    /* float: right; */
}
</style>