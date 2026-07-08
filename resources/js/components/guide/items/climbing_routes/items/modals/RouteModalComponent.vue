<template>
        <StackModal
            v-model="is_show_route_modal"
            :title="$t('guide.route.route_details_title')"
            :modal-class="{ [modalClass]: true }"

            :saveButton="{ visible: false }"
            :cancelButton="{ visible: false }"

            @close="close_route_modal"
        >
            <div class="model-body">
                <div class="row justify-content-center" v-show="is_loading">
                    <div class="col-md-4">
                        <img :src="'/images/site_img/loading.gif'" alt="loading">
                    </div>
                </div>

                <div class="container" v-show="!is_loading">
                    <div class="row">
                        <div class="modal-section basic-info">
                            <h2 class="section-title">{{ $t("guide.route.route_details") }}</h2>

                            <p class="route-detail"><strong>{{ $t("common.name") }}</strong> - {{ route.name || 'N/A' }}</p>

                            <p class="route-detail" v-if="route.height">
                                <strong>{{ $t("common.height") }}</strong> - {{ route.height }}
                            </p>

                            <p v-if="route.category"><strong>{{ $t("common.category") }}</strong> - {{ getCategoryText(route.category) }}</p>

                            <p class="route-detail" v-if="route.bolts"><strong>{{ $t("guide.route.bolts") }}</strong> - {{ route.bolts }}</p>

                            <p class="route-detail"><strong>{{ $t("guide.route.grade_fr") }}</strong> -
                                <span v-if="route.or_grade != null">
                                    {{ route.grade }} / {{ route.or_grade }}
                                </span>
                                <span v-else>
                                    {{ route.grade || 'N/A' }}
                                </span>
                            </p>

                            <p class="route-detail">
                                <span v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'"><strong>{{ $t("guide.route.grade_uiaa") }}</strong></span>
                                <span v-if="activ_grade == 'YDS' || activ_grade == 'yds'"><strong>{{ $t("guide.route.grade_yds") }}</strong></span>
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
                            <h3 class="section-title">{{ $t('guide.route.additional_info') }}</h3>

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
                            <h3 class="section-title">{{ $t('guide.route.technical_details') }}</h3>

                            <p class="route-detail" v-if="route.anchor_type">
                                <strong>{{ $t("guide.route.anchor_type") }}</strong> - {{ route.anchor_type }}
                            </p>

                            <div class="row">
                                <div class="col-md-6" v-if="route.bolts_type == 'glued'">
                                    <p class="route-detail">
                                        <strong>{{ $t('guide.route.glued_bolts') }}</strong>
                                        <img
                                            class="climbing_bolt_image"
                                            :src="'/images/svg/glued bolt.png'"
                                            :alt="$t('guide.route.glued_bolts')"
                                            :title="$t('guide.route.glued_bolts')"
                                        />
                                    </p>
                                </div>
                                <div class="col-md-6" v-if="route.bolts_type == 'hangerr'">
                                    <p class="route-detail">
                                        <strong>{{ $t('guide.route.hangerr_bolts') }}</strong>
                                        <img
                                            class="climbing_bolt_image"
                                            :src="'/images/svg/hangerr bolt.svg'"
                                            :alt="$t('guide.route.hangerr_bolts')"
                                            :title="$t('guide.route.hangerr_bolts')"
                                        />
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="modal-section description" v-if="routeDescription">
                            <h3 class="section-title">{{ $t('common.description') }}</h3>
                            <div class="description-content">
                                <span v-html="routeDescription"></span>
                            </div>
                        </div>

                        <div class="modal-section rating" v-if="route.reviews_count && route.reviews_count > 0">
                            <starsReiting
                                :reviews_count_prop="route.reviews_count"
                                :reviews_stars_prop="route.reviews_stars"
                            />
                        </div>

                        <div class="flex-row mt-3 w-100">
                            <button
                                class="btn btn-success"
                                @click="create_route_review_modal(route.id)"
                                v-if="user && user.id"
                            >
                                {{ $t('guide.route.make_review') }}
                            </button>

                            <div v-else class="alert alert-info mb-0 p-2" role="alert" @click="open_login_then_reopen" style="border-radius: 5px; cursor: pointer;">
                                <p class="mb-0">{{ $t('guide.route.please_login') }}</p>
                            </div>

                            <button
                                class="btn btn-primary float-right"
                                @click="show_route_all_review_modal(route.id)"
                                v-if="route.reviews_count && route.reviews_count > 0"
                            >
                                {{ $t('guide.route.show_feedbacks') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </StackModal>
</template>

<script>
import grade_chart from '../../../../../../mixins/grade_chart_mixin.js'
import starsReiting from '../../../../../global_components/StarReitingShowComponent.vue'

export default {
    mixins: [
        grade_chart,
    ],
    components: {
        starsReiting,
    },
    props: [],
    computed: {
        routeDescription() {
            if (this.$i18n.locale === 'ka' && this.route.text_ka) {
                return this.route.text_ka;
            }
            return this.route.text_us || null;
        },
    },
    data() {
        return {
            is_show_route_modal: false,
            is_loading: false,
            route: {},
            user: [],
            modalClass: '',
            _current_route_id: null,

            activ_grade: {
                get() {
                    return localStorage.getItem('grade') || 'yds';
                },
                set(value) {
                    localStorage.setItem('grade', value);
                }
            },
        };
    },
    methods: {
getCategoryText(category) {
            const categories = {
                'tred': this.$t('guide.route.tred'),
                'top': this.$t('guide.route.top_rope'),
                'boulder': this.$t('guide.route.boulder'),
                'sport climbing': this.$t('guide.route.sport_climbing')
            };
            return categories[category] || category;
        },

        lead_grade_chart(grade_fr) {
            return this.lead(grade_fr) || '';
        },

        create_route_review_modal(route_id) {
            this.is_show_route_modal = false;
            this.$emit('show_route_review_modal', route_id);
        },

        show_route_all_review_modal(route_id) {
            this.is_show_route_modal = false;
            this.$emit('show_route_all_review_modal', route_id);
        },

        open_login_then_reopen() {
            const route_id = this._current_route_id;
            this.is_show_route_modal = false;
            this.$bus.$emit('open-login-modal', () => {
                this.show_route_modal(route_id);
            });
        },

        show_route_modal(id) {
            this._current_route_id = id;
            this.clear_data();
            this.auth_user();
            this.is_loading = true;
            this.is_show_route_modal = false;
            this.get_route_data(id);
        },

        close_route_modal() {
            this.is_show_route_modal = false;
            this.is_loading = false;
            this.clear_data();
        },

        clear_data() {
            this.route = {};
            this.user = [];
        },

        get_route_data(id) {
            this.route = {};
            this.is_loading = true;

            axios.get("/get_route/get_route_for_modal/" + id)
                .then((response) => {
                    this.route = response.data || {};
                })
                .catch((error) => {
                    console.error('Error loading route data:', error);
                    this.route = {};
                })
                .finally(() => {
                    this.is_loading = false;
                    this.is_show_route_modal = true; // Show modal after load
                });
        },

        auth_user() {
            axios.get('/auth_user/')
                .then(response => {
                    this.user = response.data || [];
                })
                .catch(error => {
                    console.error('Error loading user data:', error);
                    this.user = [];
                });
        }
    }
}
</script>

<style scoped>
.card{
    width:380px;
    border:none;
    height:280px;
}

.climbing_bolt_image {
    height: 50px;
}

@media (max-width: 756px) {
    .climbing_bolt_image {
        height: 35px;
    }
}

.modal-section {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

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

.cursor_pointer {
    cursor: pointer;
}

.pool-right {
    margin-left: auto;
}
</style>
