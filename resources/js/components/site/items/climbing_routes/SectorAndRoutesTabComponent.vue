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

// import { site_data } from './mixins/site_data_mixin.js'

// // Vue.mixin(site_data); 
// import mixin from './mixin.js'
// import { site_data } from './mixins/site_data_mixin.js'
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
        // $route(to, from) {
        //     this.get_outdoor_routes();
        //     this.get_spot_rocks_images();
        // },
        
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
        // get_route_posts(route_id) {
        //     this.route_posts = [];
        //     axios
        //         .get("../api/posts/get_route_posts/" + route_id)
        //         .then((response) => {
        //             this.route_posts = response.data;
        //         })
        //         .catch((error) => console.log(error));
        // },
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
        // get_mtp_posts(mtp_id) {
        //     this.mtp_posts = [];
        //     axios
        //         .get("../api/posts/get_mtp_posts/" + mtp_id)
        //         .then((response) => {
        //             this.mtp_posts = response.data;
        //         })
        //         .catch((error) => console.log(error));
        // },
    },
};
</script>
