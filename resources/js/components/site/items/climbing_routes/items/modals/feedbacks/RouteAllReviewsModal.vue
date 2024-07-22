<template>
    <stack-modal
            :show="is_show_modal"
            title="Route Feedbacks"
            @close="close_route_review_modal(route_id)"
            :modal-class="{ [ModalClass]: true }"
            :saveButton="{ visible: true }"
            :cancelButton="{
                title: 'Close',
                btnClass: { 'btn btn-primary': true },
            }"
        >
            <div class="language-vue">
                <!-- <div class="container">
                    <div class="row"> -->
                        <div class="row justify-content-center" v-show="is_loading">
                            <div class="col-md-4">
                                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                            </div>
                        </div>

                        <ul v-show="!is_loading">
                            <li v-for="review in reviews" :key="review.id" v-if="review.user != null" class="comment_board">
                                <div class="row feedback_row">
                                    <div class="col-xs-10 col-md-10">
                                        <div class="row">
                                            <h3 class="comentator_name"><strong>{{review.user.name}} {{review.user.surname}}</strong> </h3>
                                        </div>
                                        <div class="row">
                                            <starsReiting 
                                                :reviews_count_prop = 1
                                                :reviews_stars_prop = review.stars
                                                :rewiew_count_text_prop = false
                                            />
                                        </div>
                                        <div class="row">
                                            <p v-if="review.ascent_style != null">{{review.ascent_style}}</p>
                                        </div>
                                        <div class="row" v-if="review.text != null">
                                            <p>{{review.text}}</p>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <h1 v-if="reviews == []">No feedbacks</h1>
                        </ul>
                    <!-- </div>
                </div> -->
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <button
                        class="btn btn-success"
                        @click="back_to_route_madal()"
                    >
                        {{ $t('guide.route.back_to_route_modal') }}
                    </button>
            
                    <button
                        class="btn btn-primary float-right"
                        @click="close_route_review_modal()"
                    >
                        {{ $t('guide.route.close_modal') }}
                    </button>
                </div>
            </div>
        </stack-modal>
</template>

<script>
import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
// import VueRecaptcha from 'vue-recaptcha'; //https://www.npmjs.com/package/vue-recaptcha

import starsReiting  from '../../../../../../global_components/StarReitingShowComponent.vue'

export default {
    components: { 
        // VueRecaptcha,
        StackModal,
        starsReiting,
    },
    props: [
        // "sector",
    ],
    data: function () {
        return {
            is_show_modal: false,

            route_id: 0,

            reviews: [],
            ModalClass: '',

            is_loading: false,
        };
    },
    mounted() {
        //
    },
    methods: {
        show_route_all_review_modal(route_id){
            // this.is_show_modal = true;
            this.route_id = route_id
            this.get_all_route_reviews()
        },
        close_route_review_modal(){
            this.is_show_modal = false;
        },

        back_to_route_madal(){
            this.close_route_review_modal()
            this.$emit('back_route_modal', this.route_id)
        },

        get_all_route_reviews() {
            this.is_loading = true

            axios
            .get("/route_review/get_all_route_reviews/" + this.route_id)
            .then((response) => {
                this.reviews = response.data
                this.is_show_modal = true;
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
.complaint_icon{
    float: right;
    cursor: pointer;
    font-size: 130%;
}
.comentator_name{
    margin: 0px;
    /* margin-left: 18%; */
    float: left;
    color: #000;
}
.comment_board{
    border: solid;
    border: 0, 1, 0;
    border-width: 2px 0 0 2px;
    border-radius: 3px 0 0 5px;

    margin-bottom: 22px;
}

.language-vue{
    width: 95%;

    position: absolute;
    top: 0;
    bottom: 0;
    overflow-y: scroll;
    overflow-x: hidden;
}
.modal-body{
    height: 6dvb;
}
.feedback_row{
    margin-left: 15px;
}
</style>