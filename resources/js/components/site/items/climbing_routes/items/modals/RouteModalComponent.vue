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

                            <p class="route_detal" v-if="route.auther">
                                {{ $t("guide.route.auther") }} - {{ route.auther }}
                            </p>

                            <p class="route_detal" v-if="route.creating_data">
                                {{ $t("guide.route.creating data") }} - {{ route.creating_data }}
                            </p>

                            <p class="route_detal" v-if="route.first_ascent">
                                {{ $t("guide.route.first_ascent") }} - {{ route.first_ascent }}
                            </p>
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
    
export default {
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
        };
    },
    mounted() {
        // this.get_spot_rocks_images();
    },
    methods: {


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