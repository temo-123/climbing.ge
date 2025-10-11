<template>
    <span>
        <div class="row" v-if="sectors_and_images.local_images.length > 0">
            <div
                v-for="spot_image in sectors_and_images.local_images"
                :key="spot_image"

                :class="'sector_images sector_images_' + sectors_and_images.local_images.length"
            >
                <h3>{{spot_image.title}}</h3>
                
                <openImg
                    :img="'/public/images/sector_local_img/' + spot_image.image"
                    :img_alt="spot_image.title"
                    :img_class="'sector_local_images'"
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
/* Friendly styles for SectorsAndAreaLocalImageComponrnt */

.sector_images {
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    margin-bottom: 1.5rem;
    padding: 0.5rem;
    background-color: #f8f9fa;
    transition: transform 0.2s ease-in-out;
}

.sector_images:hover {
    transform: scale(1.05);
}

.sector_local_images {
    width: 100%;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

h3 {
    font-size: 1.4rem;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 0.75rem;
}
</style>
