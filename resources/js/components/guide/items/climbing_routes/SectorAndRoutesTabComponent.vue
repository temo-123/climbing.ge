<template>
    <div class="container" id="sectors">

        <div v-if="spot_images.length > 0">
            <div
                :class="'sector_images sector_images_' + spot_images.length"
                v-for="spot_image in spot_images"
                :key="spot_image"
            >
                <openImg
                    :img="'/public/images/spot_rocks_img/' + spot_image.image"
                    :img_alt="spot_image.title"
                />
            </div>
        </div>

        <div v-for="area in climbing_area" :key="area">
            <!-- {{ area }} -->
            <span v-if="area['local_images']" >
                <sector_and_local_area_images :sectors_and_images="area"/>
            </span>
            <span v-else>
                <sector :sector="area"/>
            </span>
        </div>

    </div>
</template>

<script>
import StackModal from "@innologica/vue-stackable-modal"; //https://innologica.github.io/vue-stackable-modal/#sample-css

import sector from "./items/SectorComponent.vue"
import sector_and_local_area_images from "./items/SectorsAndAreaLocalImageComponrnt.vue"

import openImg from "../ImageOpenComponent.vue";

import  grade_chart  from '../../../../mixins/grade_chart_mixin.js'

export default {
    mixins: [
        // site_data,
        grade_chart,
    ],
    components: {
        StackModal,

        sector,
        sector_and_local_area_images,

        openImg,
    },
    props: [
        "article_id_prop"
    ],
    watch: {
        article_id_prop: function(){
            this.article_id_prop = this.id
            this.get_outdoor_routes();
            this.get_spot_rocks_images();
        },
    },
    data: function () {
        return {
            climbing_area: [],
            spot_images: [],

            show_route_modal: false,
            show_mtp_modal: false,
            modalClass: [],

            route_detals: [],

            mtp_detals: [],
            id: 0
        };
    },
    mounted() {
        // this.get_outdoor_routes();
        // this.get_spot_rocks_images();
    },
    methods: {
        update(id){
            this.id = id
            this.get_outdoor_routes();
            this.get_spot_rocks_images();
        },

        lead_grade_chart(grade_fr) {
            return this.boulder(grade_fr)
        },

        boulder_grade_chart(grade_fr) {
            return this.lead(grade_fr)
        },

        get_spot_rocks_images() {
            this.spot_images = []
            axios
                .get("../../api/get_spot_rocks_images/" + this.id)
                .then((response) => {
                    this.spot_images = response.data;
                })
                .catch((error) => {});
        },
        
        get_outdoor_routes() {
            this.climbing_area = []
            axios
                .get("../../api/sector/get_sector_and_routes/" + this.id)
                .then((response) => {
                    this.climbing_area = response.data;
                })
                .catch((error) => {});
        },


        show_route_model(id) {
            this.show_route_modal = true;
            this.route_detals = [];

            axios
                .get("../../api/route/" + id)
                .then((response) => {
                    this.route_detals = response.data;
                    // this.route_post_list = true;
                })
                .catch((error) => {});
        },
        show_mtp_madel(id) {
            this.show_mtp_modal = true;
            this.mtp_detals = [];

            axios
                .get("../../api/MTP/" + id)
                .then((response) => {
                    this.mtp_detals = response.data;
                    // this.mtp_post_list = true;
                })
                .catch((error) => {});
        },
    },
};
</script>

<style scoped>
#sectors {
    padding: 1rem;
    background-color: #f8f9fa;
    border-radius: 8px;
    margin: 1rem 0;
}

.sector_images {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.sector_images img {
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    max-width: 100%;
    height: auto;
}

.sector_images_1 {
    justify-content: center;
}

.sector_images_2 {
    justify-content: space-around;
}

.sector_images_3,
.sector_images_4 {
    justify-content: space-between;
}

/* Add spacing between areas */
#sectors > div {
    margin-bottom: 2rem;
}

/* Improve readability */
#sectors h1, #sectors h2, #sectors h3 {
    color: #495057;
    margin-bottom: 1rem;
}

#sectors p {
    line-height: 1.6;
    color: #343a40;
}
</style>
