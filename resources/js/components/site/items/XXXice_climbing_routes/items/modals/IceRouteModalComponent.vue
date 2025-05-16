<template>
    <stack-modal
            :show="is_show_route_modal"
            title="Route detals"
            @close="close_route_modal()"
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
                        <!-- <span
                            v-for="route in route"
                            :key="route.id"
                            class="routes_detals"
                        > -->
                            <p class="route_detal">{{ $t("guide.route.name") }} - {{ route.name }}</p>

                            <p class="route_detal" v-if="route.height">
                                {{ $t("guide.route.height") }} - {{ route.height }}
                            </p>

                            <p v-if="route.category == 'tred'">{{ $t("guide.route.bolts") }} - Tred climbing</p>
                            <p v-else-if="route.category == 'top'">{{ $t("guide.route.bolts") }} - Top rope</p>
                            <p v-else-if="route.category == 'boulder'"></p>
                            <p v-else-if="route.category == 'sport climbing'">{{ $t("guide.route.bolts") }} - {{ route.bolts }}</p>
                            
                            <p class="route_detal" v-if="route.author">
                                {{ $t("guide.route.author") }} - {{ route.author }}
                            </p>

                            <p class="route_detal" v-if="route.anchor_type">
                                {{ $t("guide.route.anchor") }} - {{ route.anchor_type }}
                            </p>

                            <p class="route_detal" v-if="route.creation_data">
                                {{ $t("guide.route.creating_data") }} - {{ route.creation_data }}
                            </p>

                            <p class="route_detal" v-if="route.first_ascent">
                                {{ $t("guide.route.first_ascent") }} - {{ route.first_ascent }}
                            </p>
                            
                            <hr v-if="route.text != null">

                            <span v-html="route.text" v-if="route.text != null"></span>
                        <!-- </span> -->
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
            ModalClass: [],
            route: [],
        };
    },
    mounted() {
        // this.show_route_modal();
    },
    methods: {
        show_ice_route_modal(id) {
            this.route = [];

            axios
                .get("/ice_routes/get_route_data_for_modal/" + id)
                .then((response) => {
                    this.route = response.data;
                    this.is_show_route_modal = true;
                })
                .catch((error) => {});
        },
        close_route_modal(){
            this.is_show_route_modal = false
        }
    }
}
</script>
