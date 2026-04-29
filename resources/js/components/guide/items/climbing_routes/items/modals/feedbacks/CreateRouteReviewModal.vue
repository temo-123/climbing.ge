<template>
<StackModal
    v-model="is_show_modal"
    :title="$t('guide.route.create_feedback_title')"
    @close="close_route_review_modal"
    :modal-class="{ [modalClass]: true }"
    :saveButton="success_message ? { visible: false } : { visible: true, title: $t('global.save') || 'Save' }"
    @save="submit_review"
    :cancelButton="{
        title: $t('guide.route.back_to_modal'),
        btnClass: { 'btn btn-warning': true },
    }"
>
    <div class="model-body">
        <div class="container">

            <!-- Loading -->
            <div class="row justify-content-center" v-show="is_loading">
                <div class="col-md-4 text-center py-3">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">{{ $t('global.loading') }}</p>
                </div>
            </div>

            <!-- Success message -->
            <div v-if="!is_loading && success_message" class="alert alert-success text-center mt-3">
                <i class="fa fa-check-circle fa-2x mb-2 d-block"></i>
                <strong>{{ success_message }}</strong>
            </div>

            <!-- Review form -->
            <form v-else-if="!is_loading" method="POST" id="route_review_form" @submit.prevent="submit_review">
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
                        <select class="form-control friendly-input" v-model="data.ascent_style" name="ascent_style">
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
                        <starReitingInsert @get_stars="update_stars" />
                    </div>
                </div>

                <div class="modal-section feedback">
                    <h3 class="section-title">{{ $t('guide.route.feedback') }}</h3>
                    <div class="form-group">
                        <textarea
                            id="feedback"
                            name="feedback"
                            class="form-control friendly-input"
                            :placeholder="$t('guide.route.feedback_placeholder')"
                            v-model="data.text"
                            rows="4"
                        ></textarea>
                    </div>
                </div>

                <div class="alert alert-danger mt-2" v-if="error_message">
                    <i class="fa fa-exclamation-circle"></i> {{ error_message }}
                </div>
            </form>

        </div>
    </div>
</StackModal>
</template>

<script>
import starReitingInsert from '../../../../../../global_components/StarReitingInsertComponent.vue'

export default {
    components: { starReitingInsert },
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
            success_message: '',
            error_message: '',
        };
    },
    mounted() {
        if (!window.grecaptcha) {
            const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY;
            if (key) {
                const script = document.createElement('script');
                script.src = `https://www.google.com/recaptcha/api.js?render=${key}`;
                document.head.appendChild(script);
            }
        }
    },
    methods: {
        async show_route_review_modal(route_id) {
            try {
                const authRes = await axios.get('/auth_user/');
                if (!authRes.data || !authRes.data.id) {
                    this.$bus.$emit('open-login-modal', () => this.show_route_review_modal(route_id));
                    return;
                }
            } catch {
                this.$bus.$emit('open-login-modal', () => this.show_route_review_modal(route_id));
                return;
            }

            this.route_id = route_id;
            this.clear_data();
            this.is_show_modal = true;
        },

        close_route_review_modal() {
            this.is_show_modal = false;
            this.$emit('back_route_modal', this.route_id);
        },

        clear_data() {
            this.success_message = '';
            this.error_message = '';
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

        async get_recaptcha_token() {
            const key = process.env.MIX_GOOGLE_CAPTCHA_V3_SITE_KEY;
            if (!key || !window.grecaptcha) return null;
            try {
                await new Promise(resolve => window.grecaptcha.ready(resolve));
                return await window.grecaptcha.execute(key, { action: 'route_review' });
            } catch {
                return null;
            }
        },

        async submit_review() {
            if (this.success_message) return;
            this.is_loading = true;
            this.error_message = '';

            const recaptcha_token = await this.get_recaptcha_token();

            axios.post('/set_route/set_route_review/create_route_review/' + this.route_id, {
                ...this.data,
                recaptcha_token,
            })
                .then((response) => {
                    this.success_message = response.data.message || 'Thank you for your feedback!';
                })
                .catch((error) => {
                    if (error.response?.data?.already_reviewed) {
                        alert(error.response.data.message || 'You already have a review for this route.');
                        this.close_route_review_modal();
                    } else {
                        this.error_message = error.response?.data?.message || 'Error submitting review. Please try again.';
                    }
                })
                .finally(() => {
                    this.is_loading = false;
                });
        },
    }
}
</script>

<style scoped>
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
    font-size: 1.1rem;
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
.form-group { margin-bottom: 1rem; }
</style>
