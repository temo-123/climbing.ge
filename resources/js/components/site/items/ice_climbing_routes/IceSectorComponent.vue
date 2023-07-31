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
                            <iceRouteTab v-for="route in ice_sector.routes" :key="route.id" :ice_route_prop="route"/>
                        </tbody>
                    </table>
                </div>
            </span>
        </div>

    </div>
</template>

<script>
import StackModal from "@innologica/vue-stackable-modal"; //https://innologica.github.io/vue-stackable-modal/#sample-css

import iceRouteTab from "./items/IceRoutesTabComponent.vue"
import openImg from "../ImageOpenComponent.vue";

export default {
    mixins: [
        // site_data,
        // grade_chart,
    ],
    components: {
        StackModal,
        iceRouteTab,
        openImg,
    },
    props: ["article_id"],
    data: function () {
        return {
            // show_ice_route_modal: false,
            // modalClass: [],

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

        // $route(to, from) {
        //     this.update();
        //     // this.get_spot_rocks_images();
        // },

        // '$route' (to, from) {
        //     this.update();
        // }
    },
    methods: {
        update(id){
            this.id = this.article_id
            this.get_ice_sectors();
        },
        
        get_ice_sectors() {
            this.ice_sectors = []
            axios
                .get("/ice_sectors/get_article_sectors/" + this.id)
                .then((response) => {
                    this.ice_sectors = response.data;
                    // console.log("🚀 ~ file: IceSectorComponent.vue:100 ~ .then ~ this.ice_sectors:", this.ice_sectors[0].sector.name)
                    
                })
                .catch((error) => {});
        },
    },
};
</script>
