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
            <div class="model-body">
                <div class="container">
                    <div class="row">
                        <div class="row justify-content-center" v-show="is_loading">
                            <div class="col-md-4 friendly-loading">
                                <img :src="'../../../../../../public/images/site_img/loading.gif'" alt="loading">
                            </div>
                        </div>

                        <div v-show="!is_loading">
                            <div v-if="reviews.length === 0" class="no-reviews">
                                <h2 class="no-reviews-title">{{ $t('guide.route.no_feedbacks') }}</h2>
                            </div>
                            <div v-else class="reviews-list">
                                <div v-for="review in reviews" :key="review.id" v-if="review.user != null" class="review-item">
                                    <div class="review-header">
                                        <h3 class="reviewer-name">{{review.user.name}} {{review.user.surname}}</h3>
                                        <starsReiting
                                            :reviews_count_prop = 1
                                            :reviews_stars_prop = review.stars
                                            :rewiew_count_text_prop = false
                                        />
                                    </div>
                                    <div class="review-details">
                                        <p v-if="review.ascent_style != null" class="ascent-style">{{review.ascent_style}}</p>
                                        <p v-if="review.text != null" class="review-text">{{review.text}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer d-flex justify-content-between">
                    <button
                        class="btn btn-danger float-left"
                        @click="close_route_review_modal()"
                    >
                        {{ $t('guide.route.close_modal') }}
                    </button>

                    <button
                        class="btn btn-warning float-right"
                        @click="back_to_route_madal()"
                    >
                        {{ $t('guide.route.back_to_modal') }}
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

.model-body {
    /* max-height: 60vh; */
    overflow-y: auto;
    overflow-x: hidden;
}

.friendly-loading img {
    max-width: 100%;
}

.reviews-list {
    padding: 1rem 0;
}

.review-item {
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    padding: 1rem;
    margin-bottom: 1rem;
}

.review-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.5rem;
}

.reviewer-name {
    font-size: 1.4rem;
    font-weight: 600;
    color: #495057;
    margin: 0;
}

.review-details p {
    margin-bottom: 0.5rem;
    font-size: 1.1rem;
    line-height: 1.4;
    color: #343a40;
}

.ascent-style {
    font-weight: 500;
    color: #007bff;
}

.review-text {
    font-style: italic;
}

.no-reviews {
    text-align: center;
    padding: 2rem;
}

.no-reviews-title {
    font-size: 1.5rem;
    color: #6c757d;
}

.modal-footer {
    padding: 1rem;
}

</style>
