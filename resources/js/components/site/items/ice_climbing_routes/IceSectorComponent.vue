<template>
    <div class="container" id="sectors">

        <div v-if="ice_sectors.length > 0">
            <span v-for="ice_sector in ice_sectors" :key="ice_sector.sector.id">
                <span>
                    <h2 style="font-size: 160%">
                        {{ $t("guide.article.title.sector name") }} -
                        <strong>{{ ice_sector.sector.name }}</strong>
                    </h2>
                </span>

                <div
                    :class="'sector_images sector_images_' + ice_sector.images.length"
                    v-for="image in ice_sector.images"
                    :key="image.id"
                >
                    <openImg
                        :img="'/public/images/ice_sector_img/' + image.image"
                        :img_alt="image.title"
                    />
                </div>

                <div>
                    <table
                        class="table table-hover"
                        id="dev-table"
                        v-if="ice_sector.routes.length > 0"
                    >
                        <thead>
                            <tr>
                                <td>N</td>
                                <td>{{ $t("guide.route.name") }}</td>
                                <td>{{ $t("guide.route.height") }}</td>
                                <td>{{ $t("guide.route.bolts") }}</td>
                                <td>{{ $t("guide.route.grade") }}</td>
                                <td>Info</td>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="ice_route in ice_sector.routes" :key="ice_route.id">
                                <td>{{ ice_route.num }}</td>
                                <td>{{ ice_route.name }}</td>

                                <td v-if="ice_route.height">{{ ice_route.height }}</td>
                                <td v-else>?</td>

                                <td v-if="ice_route.category == 'dry'">
                                    <span v-if="ice_route.bolts">{{ ice_route.bolts }}</span>
                                    <span v-else>?</span>
                                </td>
                                <td v-else-if="ice_route.category == 'ice'">{{ $t("guide.route.screws") }}</td>

                                <td>{{ ice_route.grade }}</td>
                                
                                <td @click="show_route_modal(ice_route.id)">
                                    <a style="margin-top: -5%; font-size: 120%">
                                        <i class="fa fa-info" aria-hidden="true"></i>
                                    </a>
                                </td>
                            </tr>
                            <!-- <iceRouteTab v-for="route in ice_sector.routes" :key="route.id" :ice_route_prop="route"/> -->
                        </tbody>
                    </table>
                </div>
            </span>
        </div>


        <iceRouteModal
            ref="ice_sector_modal"
        />
    </div>
</template>

<script>
import StackModal from "@innologica/vue-stackable-modal"; //https://innologica.github.io/vue-stackable-modal/#sample-css

import openImg from "../ImageOpenComponent.vue";

import iceRouteModal from "./items/modals/IceRouteModalComponent.vue";

export default {
    mixins: [
        // 
    ],
    components: {
        StackModal,
        openImg,
        iceRouteModal
    },
    props: ["article_id"],
    data: function () {
        return {
            ice_sectors: [],

            id: this.article_id
        };
    },
    mounted() {
        this.get_ice_sectors();
    },

    watch: {
        article_id: function(){
            this.update();
        },
    },
    methods: {
        update(id){
            this.id = this.article_id
            this.get_ice_sectors();
        },

        show_route_modal(ice_route_id){
            this.$refs.ice_sector_modal.show_ice_route_modal(ice_route_id)
        },
        
        get_ice_sectors() {
            this.ice_sectors = []
            axios
                .get("/ice_sectors/get_article_sectors/" + this.id)
                .then((response) => {
                    this.ice_sectors = response.data;
                })
                .catch((error) => {});
        },
    },
};
</script>

<style>
@media (max-width: 767px) {
    table {
        display: block;
        overflow-x: scroll;
    }
}
@media (max-width: 567px) {
    table {
        white-space: nowrap;
    }
}
</style>
