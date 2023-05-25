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
    props: ["article_id"],
    data: function () {
        return {
            climbing_area: [],
            spot_images: [],

            show_route_modal: false,
            show_mtp_modal: false,
            modalClass: [],

            route_detals: [],

            mtp_detals: [],
            id: this.article_id
        };
    },
    mounted() {
        this.get_outdoor_routes();
        this.get_spot_rocks_images();
        // this.get_spot_rocks_images();
    },

    watch: {
        $route(to, from) {
            this.get_outdoor_routes();
            this.get_spot_rocks_images();
        },
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

<style lang="scss">
    .fade {
        opacity: 1;
        background: #000000a3 !important;
    }
    body.modal-open {
        margin-right: 0;
    }
    .modal-dialog {
        margin-top: 15em;
    }
    @media screen and (min-width: 768px) {
        .modal-dialog {
            width: 75% !important;
        }
    }
    .modal-footer {
        text-align: left;
    }

    .route_detal {
        margin: 0 0 0px !important;
    }
    .mtp_name h3 {
        margin: 0 0 0px !important;
        text-align: left !important;
    }

    .spot_rocks_image {
        width: 100%;
    }

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
    // .sector_images_1 {
    //     width: 9.5%;
    // }

    /*
    *
    * ===========================================================
    *     HERO SECTION  https://bootsnipp.com/snippets/VgkqV
    * ===========================================================
    *
    */
    .hero {
        padding: 6.25rem 0px !important;
        margin: 0px !important;
    }
    .cardbox {
        border-radius: 3px;
        margin-bottom: 20px;
        padding: 0px !important;
        border: 1px;
        border-style: solid;
        border-color: rgb(186 186 186);
        border-radius: 1em;
    }

    /* ------------------------------- */
    /* Cardbox Heading
    ---------------------------------- */
    .cardbox .cardbox-heading {
        padding: 16px 16px 16px 0;
        margin: 0;
    }
    .cardbox .btn-flat.btn-flat-icon {
        border-radius: 50%;
        font-size: 24px;
        height: 32px;
        width: 32px;
        padding: 0;
        overflow: hidden;
        color: #fff !important;
        background: #b5b6b6;

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .cardbox .float-right .dropdown-menu {
        position: relative;
        left: 13px !important;
    }
    .cardbox .float-right a:hover {
        background: #f4f4f4 !important;
    }
    .cardbox .float-right a.dropdown-item {
        display: block;
        width: 100%;
        padding: 4px 0px 4px 10px;
        clear: both;
        font-weight: 400;
        font-family: "Abhaya Libre", serif;
        font-size: 14px !important;
        color: #848484;
        text-align: inherit;
        white-space: nowrap;
        background: 0 0;
        border: 0;
    }

    /* ------------------------------- */
    /* Media Section
    ---------------------------------- */
    .media {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-align: start;
        align-items: flex-start;
    }
    .d-flex {
        display: -ms-flexbox !important;
        display: flex !important;
    }
    .media .mr-3 {
        margin-right: 1rem !important;
    }
    .media img {
        width: 48px !important;
        height: 48px !important;
        padding: 2px;
        border: 2px solid #f4f4f4;
    }
    .media-body {
        -ms-flex: 1;
        flex: 1;
        padding: 0.4rem !important;
    }
    .media-body p {
        font-family: "Rokkitt", serif;
        font-weight: 500 !important;
        font-size: 14px;
        color: #88898a;

        float: left;
    }
    .media-body small span {
        font-family: "Rokkitt", serif;
        font-size: 12px;
        color: #aaa;
        margin-right: 10px;
    }

    /* ------------------------------- */
    /* Cardbox Item
    ---------------------------------- */
    .cardbox .cardbox-item {
        position: relative;
        display: block;
    }
    /* .cardbox .cardbox-item img{
    } */
    .img-responsive {
        display: block;
        max-width: 100%;
        height: auto;
    }
    .fw {
        width: 100% !important;
        height: auto;
    }

    /* ------------------------------- */
    /* Cardbox Base
    ---------------------------------- */
    .cardbox-base {
        border-bottom: 2px solid #f4f4f4;
    }
    .cardbox-base ul {
        /* margin: 10px 0px 10px 15px!important;  */
        padding: 10px !important;
        font-size: 0px;
        display: inline-block;
    }
    .cardbox-base li {
        list-style: none;
        margin: 0px 0px 0px -8px !important;
        padding: 0px 0px 0px 0px !important;
        display: inline-block;
    }

    .cardbox-base li a {
        margin: 0px !important;
        padding: 0px !important;
    }
    .cardbox-base li a i {
        position: relative;
        top: 4px;
        font-size: 26px;
        color: #8d8d8d;
        margin-right: 15px;
    }
    .cardbox-base li a i :hover {
        color: #00c4cf;
        cursor: pointer;
    }
    .cardbox-base li a span {
        font-family: "Rokkitt", serif;
        font-size: 14px;
        color: #8d8d8d;
        /* margin-left: 20px; */
        position: relative;
        /* top: 5px;  */
    }
    .cardbox-base li a em {
        font-family: "Rokkitt", serif;
        font-size: 14px;
        color: #8d8d8d;
        position: relative;
        top: 3px;
    }
    .cardbox-base li a img {
        width: 25px;
        height: 25px;
        margin: 0px !important;
        border: 2px solid #fff;
    }

    /* ------------------------------- */
    /* Cardbox Comments
    ---------------------------------- */
    .cardbox-comments {
        padding: 10px 0 0 0 !important;
        /* font-size: 0px;	 */
        text-align: center;
        display: inline-block;
    }
    .cardbox-comments .comment-avatar img {
        margin-top: 1px;
        margin-right: 10px;
        position: relative;
        display: inline-block;
        text-align: center;
        width: 40px;
        height: 40px;
        /* float: left; */
    }
    .cardbox-comments .comment-body {
        overflow: auto;
    }
    .post_comment_porm {
        position: relative;
        right: -45px;
        top: -40px;
        margin-bottom: -34px;
        border: 4px solid #f4f4f4;
        width: 100%;
        overflow: hidden;
        margin-right: 68px;
    }
    .post_comment_porm input[type="text"] {
        background-color: #fff;
        line-height: 10px;
        padding: 10px 60px 8px 10px;
        border: none;
        border-radius: 4px;
        width: 350px;
        font-family: "Rokkitt", serif;
        font-size: 14px;
        color: #8d8d8d;
        height: inherit;
        font-weight: 700;
    }
    .post_comment_porm button {
        position: absolute;
        right: 0;
        top: 0px;
        border: none;
        background-color: transparent;
        color: #bbbbbb;
        padding: 15px 25px;
        cursor: pointer;

        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        text-align: center;
    }
    .post_comment_porm button i {
        font-size: 20px;
        line-height: 30px;
        display: block;
    }
    .m-0 {
        margin: 0% !important;
    }

    /* ------------------------------- */
    /* Author
    ---------------------------------- */
    .author a {
        font-family: "Rokkitt", serif;
        font-size: 16px;
        color: #00c4cf;
    }
    .author p {
        font-family: "Rokkitt", serif;
        font-size: 16px;
        color: #8d8d8d;
    }
</style>
