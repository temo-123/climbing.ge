<template>
    <span>
        <div class="row" v-if="sectors_and_images.local_images.length > 0">
            <div
                v-for="spot_image in sectors_and_images.local_images"
                :key="spot_image"

                :class="'sector_images sector_images_' + sectors_and_images.local_images.length"
            >
                <openImg
                    :img="'/public/images/sector_local_img/' + spot_image.image"
                    :img_alt="spot_image.title"
                />
            </div>
        </div>
        
        <div class="row" v-if="sectors_and_images.sectors.length > 0">
            <div v-for="sector in sectors_and_images.sectors" :key="sector" >
                <sector :sector="sector"/>
            </div>
        </div>
    </span>
</template>

<script>
import route_modal from "./modals/RouteModalComponent.vue";
import mtp_modal from "./modals/MTPModalComponent.vue";

import openImg from "../../ImageOpenComponent.vue";

import sector from "./SectorComponent.vue"
export default {
    components: {
        openImg,
        route_modal,
        mtp_modal,
        sector,
    },
    props: [
        "sectors_and_images",
    ],
    data: function () {
        return {
            // climbing_sector: [],
        };
    },
    mounted() {
        // this.get_spot_rocks_images();
    },
    methods: {
        // show_route_modal(route_id) {
        //     this.$refs.open_route_modal.show_route_modal(route_id)
        //     // console.log("🚀 ~ file: SectorComponent.vue:456 ~ show_route_model ~ this.$refs.route_modal", this.$refs.open_route_modal.show_route_modal)
        // },
        // show_mtp_madel(mtp_id) {
        //     this.$refs.open_mtp_modal.show_mtp_modal(mtp_id)
        // },
    }
}
</script>

<style scoped>
    .sector_images {
        float: left;
        margin: 0.25%;
    }
    .sector_images_1 {
        width: 99%;
    }
    .sector_images_2 {
        width: 49%;
    }
    .sector_images_3 {
        width: 32.6%;
    }
    .sector_images_4 {
        width: 24.1%;
    }
    .sector_images_5 {
        width: 19.5%;
    }
    .sector_images_6 {
        width: 16%;
    }
    .sector_images_7 {
        width: 14, 0%;
    }
    .sector_images_8 {
        width: 12%;
    }
    .sector_images_9 {
        width: 10.5%;
    }
</style>