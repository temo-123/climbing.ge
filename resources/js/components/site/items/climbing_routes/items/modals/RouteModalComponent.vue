<template>
    <stack-modal
            :show="is_show_route_modal"
            title="Route detals"
            @close="is_show_route_modal = false"
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
                        <h2>{{ $t("guide.route.route detals") }}</h2>
                        <span
                            v-for="route in route_detals"
                            :key="route.id"
                            class="routes_detals"
                        >
                            <p class="route_detal">{{ $t("guide.route.name") }} - {{ route.name }}</p>

                            <p class="route_detal">
                                {{ $t("guide.route.height") }} - {{ route.height }}
                            </p>

                            <p>{{ $t("guide.route.bolts") }} - {{ route.bolts }}</p>

                            <p>{{ $t("guide.route.grade fr") }} - 
                                <span v-if="route.or_grade != NULL">
                                    {{ route.grade }} / {{ route.or_grade }}
                                </span>
                                <span v-else>
                                    {{ route.grade }}
                                </span>
                            </p>

                            <p>
                                <span v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'">{{ $t("guide.route.grade uiaa") }}</span>
                                <span v-if="activ_grade == 'YDS' || activ_grade == 'yds'">{{ $t("guide.route.grade yds") }}</span>
                                    -
                                
                                <span v-if="route.or_grade != NULL">
                                    {{ lead_grade_chart(route.grade) }} /
                                    {{ lead_grade_chart(route.or_grade) }}
                                </span>
                                <span v-else>
                                    {{ lead_grade_chart(route.grade) }}
                                </span>
                            </p>

                            <p class="route_detal" v-if="route.author">
                                {{ $t("guide.route.author") }} - {{ route.author }}
                            </p>

                            <p class="route_detal" v-if="route.creation_data">
                                {{ $t("guide.route.creating_data") }} - {{ route.creation_data }}
                            </p>

                            <p class="route_detal" v-if="route.first_ascent">
                                {{ $t("guide.route.first_ascent") }} - {{ route.first_ascent }}
                            </p>
                            
                            <hr v-if="route.text != null">

                            <span v-html="route.text"></span>
                        </span>
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
            is_show_route_modal: false,

            route_detals: [],

            get activ_grade() {
                return localStorage.getItem('grade') || 'yds';
            },
            set activ_grade(value) {
                localStorage.setItem('grade', value);
            },
        };
    },
    mounted() {
        // this.get_spot_rocks_images();
    },
    methods: {

        lead_grade_chart(grade_fr) {
            return this.lead(grade_fr)
        },

        show_route_modal(id) {
            this.is_show_route_modal = true;
            this.route_detals = [];

            axios
                .get("../../api/route/get_route_for_modal/" + id)
                .then((response) => {
                    this.route_detals = response.data;
                    // this.route_post_list = true;
                })
                .catch((error) => {});
        },
    }
}
</script>

<style>

</style>