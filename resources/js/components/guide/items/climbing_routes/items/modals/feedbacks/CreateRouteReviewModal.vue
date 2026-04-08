<template>
<StackModal 
    v-model="is_show_modal"
    :title="$t('guide.route.create_feedback_title')"
    @close="close_route_review_modal"
    :modal-class="{ [modalClass]: true }"
    :saveButton="{ visible: true }"
    :cancelButton="{
        title: $t('guide.route.back_to_modal'),
        btnClass: { 'btn btn-warning': true },
    }"
>
    <div class="model-body">
        <div class="container">
            <div class="row">
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4 friendly-loading">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                        <p class="mt-2 text-muted">{{ $t('global.loading') }}</p>
                    </div>
                </div>

                <form method="POST" id="route_review_form" v-on:submit.prevent="add_route_review" v-show="!is_loading">
                    <div class="modal-section climbing-status">
                        <h3 class="section-title">{{ $t('guide.route.climbing_status') }}</h3>
                        <div class="form-group">
                            <label class="form-label">{{ $t('guide.route.did_you_climb') }}</label>
                            <input type="checkbox" v-model="data.climbed" class="form-check-input">
                        </div>
                    </div>

                    <div class="modal-section climb-details" v-if="data.climbed">
                        <h3 class="section-title">{{ $t('guide.route.climb_details') }}</h3>
                        <div class="form-group">
                            <label class="form-label">{{ $t('guide.route.climb_date') }}</label>
                            <input type="date" class="form-control friendly-input" v-model="data.climbed_data" name="climbed_data">
                        </div>
                        <div class="form-group">
                            <label class="form-label">{{ $t('guide.route.ascent_style') }}</label>
                            <select class="form-control friendly-input" v-model="data.ascent_style" name="ascent_style" required>
                                <option value="" disabled>{{ $t('guide.route.select_ascent_style') }}</option>
                                <option value="onsite">{{ $t('guide.route.onsite') }}</option>
                                <option value="flash">{{ $t('guide.route.flash') }}</option>
                                <option value="second_go">{{ $t('guide.route.second_go') }}</option>
                                <option value="redpoint">{{ $t('guide.route.redpoint') }}</option>
                                <option value="top_rope">{{ $t('guide.route.top_rope') }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-section rating">
                        <h3 class="section-title">{{ $t('guide.route.rating_label') }}</h3>
                        <div class="form-group">
                            <label class="form-label">{{ $t('guide.route.rating_label') }}</label>
                            <starReitingInsert @get_stars="update_stars" />
                        </div>
                    </div>

                    <div class="modal-section feedback">
                        <h3 class="section-title">{{ $t('guide.route.feedback') }}</h3>
                        <div class="form-group">
                            <label class="form-label">{{ $t('guide.route.feedback') }}</label>
                            <textarea id="feedback" name="feedback" class="form-control friendly-input" :placeholder="$t('guide.route.feedback_placeholder')" v-model="data.text" rows="4"></textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</StackModal>
</template>

<script>
import starReitingInsert from '../../../../../../global_components/StarReitingInsertComponent.vue'

export default {
    components: { 
        starReitingInsert,
    },
    props: [],
    data() {
        return {
            is_show_modal: false,
            route_id: 0,
            data: {
                stars: 0,
                text: '',
                climbed: false,
                climbed_data: '',
                ascent_style: ''
            },
            modalClass: '',
            is_loading: false,
        };
    },
    methods: {
        show_route_review_modal(route_id) {
            this.route_id = route_id;
            this.clear_data();
            this.is_show_modal = true;
        },
        close_route_review_modal() {
            this.is_show_modal = false;
            this.$emit('back_route_modal', this.route_id);
        },

        clear_data() {
            this.data = {
                stars: 0,
                text: '',
                climbed: false,
                climbed_data: '',
                ascent_style: ''
            };
        },

        update_stars(stars) {
            this.data.stars = stars;
        },

        add_route_review() {
            this.is_loading = true;
            axios.post("/set_route/set_route_review/create_route_review/" + this.route_id, this.data)
                .then((response) => {
                    alert(response.data.message || 'Success');
                    this.close_route_review_modal();
                })
                .catch((error) => {
                    console.error('Review submit error:', error);
                    alert(error.response?.data?.message || 'Error submitting review');
                })
                .finally(() => {
                    this.is_loading = false;
                });
        },
    }
}
</script>

<style scoped>
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

.modal-section {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.form-group {
    margin-bottom: 1rem;
}

.form-check-input {
    margin-right: 0.5rem;
}
</style>

