<template>
    <stack-modal
            :show="is_show_mtp_modal"
            title="Molty pitch"
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
                    <div class="row">
                        <h2>{{ $t("guide.route.mtp detals") }}</h2>
                        <span
                            class="routes_detals"
                        >
                            <p class="route_detal">{{ $t("guide.route.name") }} - {{ mtp_detals.mtp.name }}</p>
                            <p class="route_detal" v-if="mtp_detals.mtp.height">{{ $t("guide.route.height") }} - {{ mtp_detals.mtp.height }}</p>
                        </span>
                    </div>

                    <table class="table col-md-12 table table-hover">
                        <tbody>
                            <tr>
                                <td>N</td>
                                <td>{{ $t("guide.route.name") }}</td>
                                <td>{{ $t("guide.route.height") }}</td>
                                <td>{{ $t("guide.route.bolts") }}</td>
                                <td>{{ $t("guide.route.grade fr") }}</td>
                                <td class="display-none-720px">
                                    {{ $t("guide.route.grade yds") }}
                                </td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr
                                v-for="pitch in mtp_detals.mtp_pitchs"
                                :key="pitch.pitch_id"
                            >
                                <td>{{ pitch.num }}</td>
                                <td>{{ pitch.name }}</td>
                                <td>{{ pitch.height }}</td>
                                <td>{{ pitch.bolts }}</td>

                                <td v-if="pitch.or_grade != NULL">
                                    {{ pitch.grade }} /
                                    {{ pitch.or_grade }}
                                </td>
                                <td v-else>{{ pitch.grade }}</td>

                                <td v-if="pitch.or_grade != NULL">
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
        // route_modal,
        // mtp_model,
    },
    props: [
        // "sector",
    ],
    data: function () {
        return {
            // climbing_sector: [],
            // spot_images: [],

            // show_route_modal: false,
            is_show_mtp_modal: false,
            // modalClass: [],

            // route_detals: [],

            mtp_detals: [],
            // id: this.article_id
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
                .get("../../api/mtp/get_mtp_for_modal/" + id)
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

</style>