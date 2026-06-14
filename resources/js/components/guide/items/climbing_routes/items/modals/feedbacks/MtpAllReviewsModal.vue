<template>
<StackModal
    v-model="is_show_modal"
    :title="$t('guide.route.all_feedbacks_title')"
    @close="close_modal"
    :modal-class="{ [modalClass]: true }"
    :saveButton="{ visible: false }"
    :cancelButton="{
        title: $t('guide.route.close_modal'),
        btnClass: { 'btn btn-secondary': true },
    }"
>
    <div class="model-body">
        <div class="container">
            <div class="row justify-content-center" v-show="is_loading">
                <div class="col-md-4 text-center">
                    <div class="spinner-border text-primary" role="status"></div>
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
                            <h3 class="reviewer-name">{{ review.user.name || 'Anonymous' }} {{ review.user.surname || '' }}</h3>
                            <starsReiting
                                :reviews_count_prop="1"
                                :reviews_stars_prop="review.stars"
                                :rewiew_count_text_prop="false"
                            />
                        </div>
                        <div class="review-details">
                            <p v-if="review.text" class="review-text">{{ review.text }}</p>
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
    components: { starsReiting },
    data() {
        return {
            is_show_modal: false,
            mtp_id: 0,
            reviews: [],
            modalClass: '',
            is_loading: false,
        };
    },
    computed: {
        safeReviews() {
            return this.reviews.filter(r => r && r.user);
        }
    },
    methods: {
        show_mtp_all_review_modal(mtp_id) {
            this.mtp_id = mtp_id;
            this.is_loading = true;
            axios.get('/get_mtp_review/get_all_mtp_reviews/' + mtp_id)
                .then((response) => {
                    this.reviews = response.data || [];
                    this.is_show_modal = true;
                })
                .catch(() => { this.reviews = []; })
                .finally(() => { this.is_loading = false; });
        },

        close_modal() {
            this.is_show_modal = false;
            this.$emit('back_mtp_modal', this.mtp_id);
        },
    }
}
</script>

<style scoped>
.reviews-list { padding: 1rem 0; }
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
.review-text {
    font-style: italic;
    font-size: 1.1rem;
    color: #343a40;
    margin: 0;
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
