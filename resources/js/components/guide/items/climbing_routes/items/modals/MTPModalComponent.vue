<template>
    <stack-modal
            :show="is_show_mtp_modal"
            title="Multi Pitch Details"
            @close="is_show_mtp_modal = false"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{
                visible: true,
                title: 'Save',
                btnClass: { 'btn btn-primary': true },
            }"
            :cancelButton="{
                visible: false,
                title: 'Close',
                btnClass: { 'btn btn-danger': true },
            }"
        >
            <div class="model-body">
                <div class="container">
                    <div class="modal-section overview">
                        <h2 class="section-title">{{ $t("guide.route.mtp detals") }}</h2>
                        <div class="overview-details">
                            <p class="route-detail">{{ $t("guide.route.name") }} - {{ mtp_detals.mtp.name }}</p>
                            <p class="route-detail" v-if="mtp_detals.mtp.height">{{ $t("guide.route.height") }} - {{ mtp_detals.mtp.height }}</p>
                        </div>
                    </div>

                    <div class="modal-section pitches">
                        <h3 class="section-title">Pitches</h3>
                        <div class="table-responsive">
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
                                        v-for="pitch in mtp_detals.mtp_pitchs"
                                        :key="pitch.pitch_id"
                                    >
                                        <td>{{ pitch.num }}</td>
                                        <td>{{ pitch.name }}</td>
                                        <td>{{ pitch.height }}</td>

                                        <td v-if="pitch.category == 'tred'">Tred climbing</td>
                                        <td v-else-if="pitch.category == 'sport climbing'">
                                            <span v-if="pitch.bolts">
                                                {{ pitch.bolts }}
                                            </span>
                                            <span v-else>
                                                ?
                                            </span>
                                        </td>
                                        <td v-else>?</td>

                                        <td v-if="pitch.or_grade != null">
                                            {{ pitch.grade }} /
                                            {{ pitch.or_grade }}
                                        </td>
                                        <td v-else>{{ pitch.grade }}</td>

                                        <td v-if="pitch.or_grade != null">
                                            {{ lead_grade_chart(pitch.grade) }} /
                                            {{ lead_grade_chart(pitch.or_grade) }}
                                        </td>
                                        <td v-else>
                                            {{ lead_grade_chart(pitch.grade) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
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
            is_show_mtp_modal: false,
            mtp_detals: [],
            ModalClass: '',
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
            this.is_show_mtp_modal = true;
            this.mtp_detals = [];

            axios
                .get("/get_mtp/get_mtp_for_modal/" + id)
                .then((response) => {
                    this.mtp_detals = response.data;
                    // this.mtp_post_list = true;
                })
                .catch((error) => {});
        },
    }
}
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
