<template>
<StackModal 
    v-model="is_show_modal"
    :title="$t('guide.route.all_feedbacks_title')"
    @close="close_route_review_modal"
    :modal-class="{ [modalClass]: true }"
    :saveButton="{ visible: false }"
    :cancelButton="{
        title: $t('common.close'),
        btnClass: { 'btn btn-secondary': true },
    }"
>
    <div class="model-body">
        <div class="container">
            <div class="row">
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">{{ $t('global.loading') }}</p>
                    </div>
                </div>

                <div v-show="!is_loading">
                    <div v-if="safeReviews.length === 0" class="no-reviews">
                        <h2 class="no-reviews-title">{{ $t('guide.route.no_feedbacks') }}</h2>
                        <p class="text-muted">{{ $t('guide.route.no_feedbacks_message') }}</p>
                    </div>
                    <div v-else class="reviews-list">
                        <div v-for="(review, index) in safeReviews" :key="review.id || index" class="review-item">
                            <div class="review-header">
                                <h3 class="reviewer-name">
                                    <router-link v-if="review.user.id" :to="{ name: 'climberProfile', params: { id: review.user.id } }">
                                        {{ review.user.name || 'Anonymous' }} {{ review.user.surname || '' }}
                                    </router-link>
                                    <span v-else>{{ review.user.name || 'Anonymous' }} {{ review.user.surname || '' }}</span>
                                </h3>
                                <starsReiting
                                    :reviews_count_prop="1"
                                    :reviews_stars_prop="review.stars"
                                    :rewiew_count_text_prop="false"
                                />
                            </div>
                            <div class="review-details">
                                <p v-if="review.ascent_style" class="ascent-style">{{ review.ascent_style }}</p>
                                <p v-if="review.text" class="review-text">{{ review.text }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</StackModal>
</template>

<script>
import starsReiting from '../../../../../../global_components/StarReitingShowComponent.vue'

export default {
    components: { 
        starsReiting,
    },
    props: [],
    data() {
        return {
            is_show_modal: false,
            route_id: 0,
            reviews: [],
            modalClass: '',
            is_loading: false,
        };
    },
    computed: {
        safeReviews() {
            return this.reviews.filter(review => review && review.user);
        }
    },
    methods: {
        show_route_all_review_modal(route_id) {
            this.route_id = route_id;
            this.is_loading = true;
            this.get_all_route_reviews();
        },
        close_route_review_modal() {
            this.is_show_modal = false;
            this.$emit('back_route_modal', this.route_id);
        },

        get_all_route_reviews() {
            axios.get("/get_route/get_route_review/get_all_route_reviews/" + this.route_id)
                .then((response) => {
                    this.reviews = response.data || [];
                    this.is_show_modal = true;
                })
                .catch((error) => {
                    console.error('Error loading reviews:', error);
                    this.reviews = [];
                })
                .finally(() => {
                    this.is_loading = false;
                });
        }
    }
}
</script>

<style scoped>
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
    flex-wrap: wrap;
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
    font-style: italic;
}

.review-text {
    font-style: italic;
}

.no-reviews {
    text-align: center;
    padding: 2rem;
    color: #6c757d;
}

.no-reviews-title {
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
}
</style>
