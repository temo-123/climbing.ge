<template>
<StackModal
            v-model="is_show_mtp_modal"
            :title="$t('guide.route.mtp_title')"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{ visible: false }"
            :cancelButton="{ visible: false,}"
            @close="close_mtp_modal"
        >

            <div class="model-body">
                <div class="container">
                    <!-- Loading State -->
                    <div v-if="loading" class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="sr-only">{{ $t('guide.route.loading_mtp_details') }}</span>
                        </div>
                        <p class="mt-2 text-muted">{{ $t('guide.route.loading_route_details') }}</p>
                    </div>

                    <!-- Content State -->
                    <div v-else>
                        <div class="modal-section overview">
                            <h2 class="section-title">{{ $t('guide.route.mtp_details') }}</h2>
                            <div class="overview-details">
                                <p class="route-detail">{{ $t("guide.route.name") }} - {{ (mtp_detals && mtp_detals.mtp && mtp_detals.mtp.name) ? mtp_detals.mtp.name : $t('guide.route.route_name_not_available') }}</p>
                                <p class="route-detail" v-if="mtp_detals && mtp_detals.mtp && mtp_detals.mtp.height">{{ $t("guide.route.height") }} - {{ mtp_detals.mtp.height }}</p>
                            </div>

                            <div class="mt-3" v-if="mtp_detals.reviews_count > 0">
                                <starsReiting
                                    :reviews_count_prop="mtp_detals.reviews_count"
                                    :reviews_stars_prop="mtp_detals.reviews_stars"
                                />
                            </div>
                        </div>

                        <div class="modal-section pitches">
                            <h3 class="section-title">{{ $t('guide.route.pitches') }}</h3>
                            
                            <!-- No pitches message -->
                            <div v-if="!mtp_detals || !mtp_detals.mtp_pitchs || mtp_detals.mtp_pitchs.length === 0" class="text-center py-3 text-muted">
                                <i class="fas fa-info-circle fa-2x mb-2"></i>
                                <p>{{ $t('guide.route.no_pitch_info') }}</p>
                            </div>

                            <div v-else class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>N</th>
                                            <th>{{ $t("guide.route.name") }}</th>
                                            <th>{{ $t("guide.route.height") }}</th>
                                            <th>{{ $t("guide.route.bolts") }}</th>
                                            <th>{{ $t("guide.route.grade fr") }}</th>
                                            <th class="display-none-720px">
                                                {{ $t("guide.route.grade yds") }}
                                            </th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr
                                            v-for="pitch in (mtp_detals && mtp_detals.mtp_pitchs ? mtp_detals.mtp_pitchs : [])"
                                                    :key="pitch.pitch_id || pitch.num || pitch.name || index"
                                        >

                                            <td>{{ pitch && pitch.num !== undefined ? pitch.num : '-' }}</td>
                                            <td>{{ pitch && pitch.name ? pitch.name : 'Loading...' }}</td>
                                            <td>{{ pitch && pitch.height ? pitch.height : '-' }}</td>

                                            <td v-if="pitch && pitch.category == 'tred'">Tred climbing</td>
                                            <td v-else-if="pitch && pitch.category == 'sport climbing'">
                                                <span v-if="pitch && pitch.bolts">
                                                    {{ pitch.bolts }}
                                                </span>
                                                <span v-else>
                                                    ?
                                                </span>
                                            </td>
                                            <td v-else>?</td>

                                            <td v-if="pitch && pitch.or_grade != null">
                                                {{ pitch.grade || '-' }} /
                                                {{ pitch.or_grade }}
                                            </td>
                                            <td v-else>{{ pitch && pitch.grade ? pitch.grade : '-' }}</td>

                                            <td v-if="pitch && pitch.or_grade != null">
                                                {{ lead_grade_chart(pitch.grade || '') }} /
                                                {{ lead_grade_chart(pitch.or_grade) }}
                                            </td>
                                            <td v-else>
                                                {{ lead_grade_chart(pitch && pitch.grade ? pitch.grade : '') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="flex-row mt-3 w-100">
                            <button
                                class="btn btn-success"
                                @click="open_review_modal"
                            >
                                {{ $t('guide.route.make_review') }}
                            </button>
                            <button
                                class="btn btn-primary float-right"
                                v-if="mtp_detals.reviews_count > 0"
                                @click="open_all_reviews_modal"
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
    mixins: [grade_chart],
    components: { starsReiting },
    props: [
        // "sector",
    ],

    data: function () {
        return {
            is_show_mtp_modal: false,
            mtp_detals: {},
            modalClass: '',
            loading: false,
        };
    },
    mounted() {
        // this.get_spot_rocks_images();
    },
    methods: {
        lead_grade_chart(grade_fr) {
            return this.lead(grade_fr)
        },

        boulder_grade_chart(grade_fr) {
            return this.boulder(grade_fr)
        },

        show_mtp_modal(id) {
            this.loading = true;
            this.mtp_detals = {};
            this.is_show_mtp_modal = false;
            axios
                .get("/get_mtp/get_mtp_for_modal/" + id)
                .then((response) => {
                    this.mtp_detals = response.data;
                    this.loading = false;
                    this.is_show_mtp_modal = true;
                })
                .catch((error) => {
                    this.mtp_detals = {};
                    this.loading = false;
                });
        },
        close_mtp_modal() {
            this.is_show_mtp_modal = false;
            this.loading = false;
            this.mtp_detals = {};
        },
        open_review_modal() {
            this.is_show_mtp_modal = false;
            this.$emit('show_mtp_review_modal', this.mtp_detals.mtp.id);
        },
        open_all_reviews_modal() {
            this.is_show_mtp_modal = false;
            this.$emit('show_mtp_all_review_modal', this.mtp_detals.mtp.id);
        },
    },
};
</script>

<style>
/* Friendly modal styles */
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
</style>
