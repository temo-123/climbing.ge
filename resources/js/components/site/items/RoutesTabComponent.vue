<template>
    <div class="container" id="sectors">
        <!-- 
            This block for spot image. image of all sectors image
         -->
        <div class="row" v-if="spot_images.length > 0">
            <div
                :class="'sector_images sector_images_' + spot_images.length"
                v-for="spot_image in spot_images"
                :key="spot_image"
            >
                <openImg
                    :img="'../../images/spot_rocks_img/' + spot_image.image"
                    :img_alt="spot_image.title"
                />
            </div>
        </div>

        <!-- 
            This is sector local images block. Images only for spot sectors part.
         -->
        <!-- <div class="row" v-if="spot_images.length > 0">
            <div
                :class="'sector_images sector_images_' + spot_images.length"
                v-for="spot_image in spot_images"
                :key="spot_image"
            >
                <openImg
                    :img="'/images/spot_rocks_img/' + spot_image.image"
                    :img_alt="spot_image.title"
                />
            </div>
        </div> -->

        <div class="row" v-for="area in climbing_area" :key="area">
            <!-- <div class="row"> -->
            <!-- <expandable-image 
                    :class="'spot_rocks_image'"
                    style=""
                    :src="'http://climbing.loc/images/sector_img/2021-08-07-17-08-32.jpg'"
                    :alt="'test'"
                ></expandable-image> -->

            <!-- </div> -->
            <span>
                <h2 style="font-size: 160%">
                    {{ $t("sector name") }} -
                    <strong>{{ area.sectors.name }}</strong>
                </h2>
            </span>
            <div class="row">
                <div class="col-md-6">
                    <img
                        v-if="area.sectors.in_shade_after_10"
                        class="sun_svg"
                        :src="'../images/svg/in shade after 10.00 am.svg'"
                        alt="In shade after 10.00 am"
                        title="In shade after 10.00 am"
                    />
                    <img
                        v-if="area.sectors.in_shade_after_15"
                        class="sun_svg"
                        :src="'../images/svg/in shade after 15.00 pm.svg'"
                        alt="In shade after 15.00 pm"
                        title="In shade after 15.00 pm"
                    />
                    <img
                        v-if="area.sectors.in_the_shade_befornoon"
                        class="sun_svg"
                        :src="'../images/svg/in shade befor noon.svg'"
                        alt="In shade befor noon"
                        title="In shade befor noon"
                    />
                    <img
                        v-if="area.sectors.in_the_shade_afternoon"
                        class="sun_svg"
                        :src="'../images/svg/in shade in the afternoon.svg'"
                        alt="In shade in the afternoon"
                        title="In shade in the afternoon"
                    />
                    <img
                        v-if="area.sectors.all_day_in_shade"
                        class="sun_svg"
                        :src="'../images/svg/in the shade whole day.svg'"
                        alt="In the shade whole day"
                        title="In the shade whole day"
                    />
                    <img
                        v-if="area.sectors.all_day_in_sun"
                        class="sun_svg"
                        :src="'../images/svg/in the sun the whole day.svg'"
                        alt="In the sun the whole day"
                        title="In the sun the whole day"
                    />
                </div>
                <div class="col-md-6">
                    <img
                        v-if="area.sectors.roof"
                        class="relief_svg"
                        :src="'../images/svg/roof.svg'"
                        alt="Roof"
                        title="Roof"
                    />
                    <img
                        v-if="area.sectors.overhang"
                        class="relief_svg"
                        :src="'../images/svg/overhang.svg'"
                        alt="Overhang"
                        title="Overhang"
                    />
                    <img
                        v-if="area.sectors.vertical"
                        class="relief_svg"
                        :src="'../images/svg/vertical.svg'"
                        alt="Vertical"
                        title="Vertical"
                    />
                    <img
                        v-if="area.sectors.slabby"
                        class="relief_svg"
                        :src="'../images/svg/slabby.svg'"
                        alt="Slabby"
                        title="Slabby"
                    />
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <img
                        v-if="area.sectors.for_family"
                        class="sun_svg"
                        :src="'../images/svg/for family.svg'"
                        alt="In this sector recomendidi family climbing. It`s seifty for kids"
                        title="In this sector recomendidi family climbing. It`s seifty for kids"
                    />
                    <img
                        v-if="area.sectors.for_kids"
                        class="sun_svg"
                        :src="'../images/svg/for kids.svg'"
                        alt="In this sector recomendidi for kids. It`a seifty for kids and poseble to kid climbing"
                        title="In this sector recomendidi for kids. It`a seifty for kids and poseble to kid climbing"
                    />
                </div>
                <div class="col-md-6">
                    <img
                        v-if="area.sectors.wolking_time"
                        class="relief_svg"
                        :src="'../images/svg/walking.svg'"
                        alt="Wolging time from car stoping to climbin sector."
                        title="Wolging time from car stoping to climbin sector."
                    />
                    <span> 30+ min. </span>
                </div>
            </div>

            <span v-html="area.sectors.text"></span>

            <!-- <expandable-image v-for="image in area.sectors.sector_img" :key="image.id"
                :class="'sector_images sector_images_'+area.sectors.sector_img.length"
                style=""
                :src="'../images/sector_img/'+image.image"
                :alt="image.image"

                v-watermark='config = option'
            ></expandable-image> -->

            <openImg
                v-for="image in area.sectors.sector_img"
                :key="image.id"
                :img="'../images/sector_img/' + image.image"
                :img_alt="image.image"
                :img_class="
                    'sector_images sector_images_' +
                    area.sectors.sector_img.length
                "
            />

            <table
                class="table col-md-12 table table-hover"
                v-if="area.sport_routes.length > 0"
            >
                <tbody>
                    <tr>
                        <td>N</td>
                        <td>{{ $t("route_tab name") }}</td>
                        <td>{{ $t("route_tab height") }}</td>
                        <td>{{ $t("route_tab bolts") }}</td>
                        <td>{{ $t("route_tab grade fr") }}</td>
                        <td>{{ $t("route_tab grade yds") }}</td>
                        <td>Info</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr v-for="route in area.sport_routes" :key="route.id">
                        <td>{{ route.num }}</td>
                        <td @click="show_route_model()">{{ route.name }}</td>
                        <td>{{ route.height }}</td>
                        <td>{{ route.bolts }}</td>

                        <td v-if="route.or_grade != NULL">
                            {{ route.grade }} / {{ route.or_grade }}
                        </td>
                        <td v-else>{{ route.grade }}</td>

                        <td v-if="route.or_grade != NULL">
                            {{ lead_grade_chart(route.grade) }} /
                            {{ lead_grade_chart(route.or_grade) }}
                        </td>
                        <td v-else>{{ lead_grade_chart(route.grade) }}</td>

                        <td @click="show_route_model(route.id)">
                            <a style="margin-top: -5%; font-size: 120%"
                                ><i class="fa fa-info" aria-hidden="true"></i
                            ></a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table
                class="table col-md-12 table table-hover"
                v-if="area.boulder_route.length > 0"
            >
                <tbody>
                    <tr>
                        <td>N</td>
                        <td>{{ $t("route_tab name") }}</td>
                        <td>{{ $t("route_tab height") }}</td>
                        <td>{{ $t("route_tab grade fr") }}</td>
                        <td>{{ $t("route_tab grade yds") }}</td>
                        <td>Info</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr v-for="route in area.boulder_route" :key="route.id">
                        <td>{{ route.num }}</td>
                        <td @click="show_route_model(route.id)">
                            {{ route.name }}
                        </td>
                        <td>{{ route.height }}</td>

                        <td v-if="route.or_grade != NULL">
                            {{ boulder_grade_chart(route.grade) }} /
                            {{ boulder_grade_chart(route.or_grade) }}
                        </td>
                        <td v-else>
                            {{ boulder_grade_chart(route.grade) }}
                        </td>

                        <td v-if="route.or_grade != NULL">
                            {{ route.grade }} / {{ route.or_grade }}
                        </td>
                        <td v-else>{{ route.grade }}</td>

                        <td @click="show_route_model(route.id)">
                            <a style="margin-top: -5%; font-size: 120%"
                                ><i class="fa fa-info" aria-hidden="true"></i
                            ></a>
                        </td>
                    </tr>
                </tbody>
            </table>

            <span
                :if="area.mtps.length > 0"
                v-for="mtp in area.mtps"
                :key="mtp.id"
            >
                <div class="col-md-8">
                    <span class="mtp_name"
                        ><h3>
                            {{ $t("mtp name") }} -
                            <strong>{{ mtp.mtp_name }}</strong>
                        </h3></span
                    >
                </div>

                <div class="col-md-4">
                    <a
                        @click="show_mtp_madel(mtp.mtp_id)"
                        data-toggle="modal"
                        data-target="#squarespaceModal_mtp_info_"
                    >
                        <h4><strong>More information</strong></h4>
                    </a>
                </div>

                <table class="table col-md-12 table table-hover">
                    <tbody>
                        <tr>
                            <td>N</td>
                            <td>{{ $t("route_tab name") }}</td>
                            <td>{{ $t("route_tab height") }}</td>
                            <td>{{ $t("route_tab bolts") }}</td>
                            <td>{{ $t("route_tab grade fr") }}</td>
                            <td class="display-none-720px">
                                {{ $t("route_tab grade yds") }}
                            </td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr
                            v-for="pitch in mtp.mtp_pitchs"
                            :key="pitch.pitch_id"
                        >
                            <td>{{ pitch.pitch_num }}</td>
                            <td>{{ pitch.pitch_name }}</td>
                            <td>{{ pitch.pitch_height }}</td>
                            <td>{{ pitch.pitch_bolts }}</td>

                            <td v-if="pitch.pitch_or_grade != NULL">
                                {{ pitch.pitch_grade_fr }} /
                                {{ pitch.pitch_or_grade }}
                            </td>
                            <td v-else>{{ pitch.pitch_grade_fr }}</td>

                            <td v-if="pitch.pitch_or_grade != NULL">
                                {{ lead_grade_chart(pitch.pitch_grade_fr) }} /
                                {{ lead_grade_chart(pitch.pitch_or_grade) }}
                            </td>
                            <td v-else>
                                {{ lead_grade_chart(pitch.pitch_grade_fr) }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </span>
        </div>

        <stack-modal
            :show="show_route_modal"
            title="Route detals"
            @close="show_route_modal = false"
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
                        <h2>Routes detals</h2>
                        <span
                            v-for="route in route_detals"
                            :key="route.id"
                            class="routes_detals"
                        >
                            <p class="route_detal">Name - {{ route.name }}</p>
                            <p class="route_detal">
                                Height - {{ route.height }}
                            </p>
                            <p>Bolts - {{ route.bolts }}</p>

                            <p class="route_detal" v-if="route.bolter">
                                Bolter - {{ route.bolter }}
                            </p>
                            <p class="route_detal" v-if="route.first_ascent">
                                First ascent - {{ route.first_ascent }}
                            </p>
                        </span>
                    </div>
                    <!-- <div class="row" v-if="this.route_posts.length > 0">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 @click="route_post_list = !route_post_list">
                                    User posts and rewies
                                </h2>
                            </div>
                            <div class="col-md-6">
                                <p
                                    style="float: right"
                                    v-if="route_post_list"
                                    @click="route_post_list = !route_post_list"
                                >
                                    Close
                                </p>
                                <p
                                    style="float: right"
                                    v-if="!route_post_list"
                                    @click="route_post_list = !route_post_list"
                                >
                                    Open
                                </p>
                            </div>
                        </div>
                        <div v-show="route_post_list">
                            <div class="row">
                                <div
                                    class="cardbox shadow-lg bg-white"
                                    v-for="post in route_posts"
                                    :key="post.id"
                                >
                                    <div class="cardbox-heading">
                                        <div class="media m-0">
                                            <div class="d-flex mr-3">
                                                <img
                                                    class="img-fluid rounded-circle"
                                                    v-if="post['user'].image"
                                                    :src="'/public/images/user_img/user_demo_img.gif'"
                                                    :alt="
                                                        post['user'].name +
                                                        ' ' +
                                                        post['user'].surname
                                                    "
                                                />
                                                <img
                                                    class="img-fluid rounded-circle"
                                                    v-else
                                                    :src="'/public/images/site_img/user_demo_img.gif'"
                                                    :alt="
                                                        post['user'].name +
                                                        ' ' +
                                                        post['user'].surname
                                                    "
                                                />
                                            </div>
                                            <div class="media-body">
                                                <div class="row m-0">
                                                    <p class="m-0">
                                                        {{
                                                            post["user"].name +
                                                            " " +
                                                            post["user"].surname
                                                        }}
                                                    </p>
                                                </div>
                                                <div class="row m-0">
                                                    <small
                                                        ><span
                                                            v-if="
                                                                post['post']
                                                                    .created_at
                                                            "
                                                            >{{
                                                                post["post"]
                                                                    .created_at
                                                            }}</span
                                                        ></small
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="cardbox-item container"
                                        v-if="post['post'].text"
                                    >
                                        <p
                                            style="
                                                background-color: #eee;
                                                text-align: center;
                                                font-size: 1.5em;
                                            "
                                        >
                                            {{ post["post"].text }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                For more posts visit forum site
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <!-- footer -->
                </div>
            </div>
        </stack-modal>

        <stack-modal
            :show="show_mtp_modal"
            title="Molty pitch"
            @close="show_mtp_modal = false"
            :modal-class="{ [modalClass]: true }"
            :saveButton="{
                visible: true,
                title: 'Save',
                btnClass: { 'btn btn-primary': true },
            }"
            :cancelButton="{
                visible: false,
                title: 'Close',
                btnClass: { 'btn btn-danger': true },
            }"
        >
            <div class="model-body">
                <div class="container">
                    <div class="row">
                        <h2>Multy-pitch detals</h2>
                        <span
                            v-for="mtp in mtp_detals"
                            :key="mtp.id"
                            class="routes_detals"
                        >
                            <p class="route_detal">Name - {{ mtp.name }}</p>
                            <p class="route_detal">Height - {{ mtp.height }}</p>
                        </span>
                    </div>
                    <!-- <div class="row" v-if="this.mtp_posts.length > 0">
                        <div class="row">
                            <div class="col-md-6">
                                <h2 @click="mtp_post_list = !mtp_post_list">
                                    User posts and rewies
                                </h2>
                            </div>
                            <div class="col-md-6">
                                <p
                                    style="float: right"
                                    v-if="mtp_post_list"
                                    @click="mtp_post_list = !mtp_post_list"
                                >
                                    Close
                                </p>
                                <p
                                    style="float: right"
                                    v-if="!mtp_post_list"
                                    @click="mtp_post_list = !mtp_post_list"
                                >
                                    Open
                                </p>
                            </div>
                        </div>
                        <div v-show="mtp_post_list">
                            <div class="row">
                                <div
                                    class="cardbox shadow-lg bg-white"
                                    v-for="mtp_post in mtp_posts"
                                    :key="mtp_post.id"
                                >
                                    <div class="cardbox-heading">
                                        <div class="media m-0">
                                            <div class="d-flex mr-3">
                                                <img
                                                    class="img-fluid rounded-circle"
                                                    v-if="
                                                        mtp_post['user'].image
                                                    "
                                                    :src="'/public/images/user_img/user_demo_img.gif'"
                                                    :alt="
                                                        mtp_post['user'].name +
                                                        ' ' +
                                                        mtp_post['user'].surname
                                                    "
                                                />
                                                <img
                                                    class="img-fluid rounded-circle"
                                                    v-else
                                                    :src="'/public/images/site_img/user_demo_img.gif'"
                                                    :alt="
                                                        mtp_post['user'].name +
                                                        ' ' +
                                                        mtp_post['user'].surname
                                                    "
                                                />
                                            </div>
                                            <div class="media-body">
                                                <div class="row m-0">
                                                    <p class="m-0">
                                                        {{
                                                            mtp_post["user"]
                                                                .name +
                                                            " " +
                                                            mtp_post["user"]
                                                                .surname
                                                        }}
                                                    </p>
                                                </div>
                                                <div class="row m-0">
                                                    <small
                                                        ><span
                                                            v-if="
                                                                mtp_post['post']
                                                                    .created_at
                                                            "
                                                            >{{
                                                                mtp_post["post"]
                                                                    .created_at
                                                            }}</span
                                                        ></small
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div
                                        class="cardbox-item container"
                                        v-if="mtp_post['post'].text"
                                    >
                                        <p
                                            style="
                                                background-color: #eee;
                                                text-align: center;
                                                font-size: 1.5em;
                                            "
                                        >
                                            {{ mtp_post["post"].text }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                For more posts visit forum site
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <div slot="modal-footer">
                <div class="modal-footer">
                    <!-- footer -->
                </div>
            </div>
        </stack-modal>
    </div>
</template>

<script>
// import VueExpandableImage from 'vue-expandable-image' //https://github.com/TahaSh/vue-expandable-image
// import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
import StackModal from "@innologica/vue-stackable-modal"; //https://innologica.github.io/vue-stackable-modal/#sample-css
// import StackModal from '../../../src/components/StackModal'

// import  routeQuanDiogram  from './RoutesQuantityComponent.vue'

import openImg from "./ImageOpenComponent.vue";

export default {
    components: {
        // SlickItem,
        // SlickList,
        StackModal,
        // routeQuanDiogram,
        // VueExpandableImage,

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
        // this.get_spot_rocks_images();
    },

    watch: {
        $route(to, from) {
            this.get_outdoor_routes();
        },
    },
    methods: {
        update(id){
            this.id = id
            this.get_outdoor_routes();
        },

        lead_grade_chart(grade_fr) {
            var grad = "";
            if (localStorage.getItem("grade") == "yds") {
                if (grade_fr == "4") grad = "5.6";
                else if (grade_fr == "5a" || grade_fr == "5a+") grad = "5.7";
                else if (grade_fr == "5b" || grade_fr == "5b+") grad = "5.8";
                else if (grade_fr == "5c" || grade_fr == "5c+") grad = "5.9";
                else if (grade_fr == "6a") grad = "5.10a";
                else if (grade_fr == "6a+") grad = "5.10b";
                else if (grade_fr == "6b") grad = "5.10c";
                else if (grade_fr == "6b+") grad = "5.10d";
                else if (grade_fr == "6c") grad = "5.11a/5.11b";
                else if (grade_fr == "6c+") grad = "5.11c";
                else if (grade_fr == "7a") grad = "5.11d";
                else if (grade_fr == "7a+") grad = "5.12a";
                else if (grade_fr == "7b") grad = "5.12b";
                else if (grade_fr == "7b+") grad = "5.12c";
                else if (grade_fr == "7c") grad = "5.12d";
                else if (grade_fr == "7c+") grad = "5.13a";
                else if (grade_fr == "8a") grad = "5.13b";
                else if (grade_fr == "8a+") grad = "5.13c";
                else if (grade_fr == "8b") grad = "5.13d";
                else if (grade_fr == "8b+") grad = "5.14a";
                else if (grade_fr == "8c") grad = "5.14b";
                else if (grade_fr == "8c+") grad = "5.14c";
                else if (grade_fr == "9a") grad = "5.14d";
                else if (grade_fr == "9a+") grad = "5.15a";
                else if (grade_fr == "9b") grad = "5.15b";
                else if (grade_fr == "9b+") grad = "5.15c";
                else if (grade_fr == "9c") grad = "5.15d";
                else if (grade_fr == "9c+") grad = "5.16a";
                else if (grade_fr == "Project") grad = "Project";
                else grad = "?";
            } else if (localStorage.getItem("grade") == "UIAA") {
                // console.log(localStorage.getItem("grade"))
                if (grade_fr == "4") grad = "IV";
                else if (grade_fr == "5a" || grade_fr == "5a+") grad = "V+";
                else if (grade_fr == "5b" || grade_fr == "5b+") grad = "VI-";
                else if (grade_fr == "5c" || grade_fr == "5c+") grad = "VI";
                else if (grade_fr == "6a") grad = "VI+";
                else if (grade_fr == "6a+") grad = "VII-";
                else if (grade_fr == "6b") grad = "VII";
                else if (grade_fr == "6b+") grad = "VII+";
                else if (grade_fr == "6c") grad = "VII+/VIII-";
                else if (grade_fr == "6c+") grad = "VIII-";
                else if (grade_fr == "7a") grad = "VIII";
                else if (grade_fr == "7a+") grad = "VIII+";
                else if (grade_fr == "7b") grad = "VIII+/IX-";
                else if (grade_fr == "7b+") grad = "IX-";
                else if (grade_fr == "7c") grad = "IX";
                else if (grade_fr == "7c+") grad = "IX+";
                else if (grade_fr == "8a") grad = "IX+/X-";
                else if (grade_fr == "8a+") grad = "X-";
                else if (grade_fr == "8b") grad = "X";
                else if (grade_fr == "8b+") grad = "X+";
                else if (grade_fr == "8c") grad = "XI-";
                else if (grade_fr == "8c+") grad = "XI";
                else if (grade_fr == "9a") grad = "XI+";
                else if (grade_fr == "9a+") grad = "XII-";
                else if (grade_fr == "9b") grad = "XII";
                else if (grade_fr == "9b+") grad = "XII+";
                else if (grade_fr == "9c") grad = "XIII";
                else if (grade_fr == "Project") grad = "Project";
                else grad = "?";
            } else {
                grad = "Error";
            }
            return grad;
        },
        boulder_grade_chart(grade_fr) {
            var grade = "";
            if (localStorage.getItem("grade") == "yds") {
                if (grade_fr == "VB") grade = "4-";
                else if (grade_fr == "V0-") grade = "4";
                else if (grade_fr == "V0") grade = "4+";
                else if (grade_fr == "V0+") grade = "5a";
                else if (grade_fr == "V1") grade = "5b";
                else if (grade_fr == "V2") grade = "5c";
                else if (grade_fr == "V3") grade = "6a";
                else if (grade_fr == "V4") grade = "6b";
                else if (grade_fr == "V5") grade = "6c+";
                else if (grade_fr == "V6") grade = "7a";
                else if (grade_fr == "V7") grade = "7a+";
                else if (grade_fr == "V8") grade = "7b";
                else if (grade_fr == "V9") grade = "7c";
                else if (grade_fr == "V10") grade = "7c+";
                else if (grade_fr == "V11") grade = "8a";
                else if (grade_fr == "V12") grade = "8a+";
                else if (grade_fr == "V13") grade = "8b";
                else if (grade_fr == "V14") grade = "8b+";
                else if (grade_fr == "V15") grade = "8c";
                else if (grade_fr == "V16") grade = "8c+";
                else if (grade_fr == "V17") grade = "9a";
                else if (grade_fr == "Project") grad = "Project";
                else grade = "?";
            } else if (localStorage.getItem("grade") == "UIAA") {
                if (grade_fr == "VB") grade = "4-";
                else if (grade_fr == "V0-") grade = "VI+";
                else if (grade_fr == "V0") grade = "VII-";
                else if (grade_fr == "V0+") grade = "VII";
                else if (grade_fr == "V1") grade = "VII+";
                else if (grade_fr == "V2") grade = "VII+/VII-";
                else if (grade_fr == "V3") grade = "VIII-";
                else if (grade_fr == "V4") grade = "VIII";
                else if (grade_fr == "V5") grade = "VIII/VIII+";
                else if (grade_fr == "V6") grade = "VIII+";
                else if (grade_fr == "V7") grade = "IX-";
                else if (grade_fr == "V8") grade = "IX";
                else if (grade_fr == "V9") grade = "IX/IX+";
                else if (grade_fr == "V10") grade = "IX+";
                else if (grade_fr == "V11") grade = "X-";
                else if (grade_fr == "V12") grade = "X";
                else if (grade_fr == "V13") grade = "X+";
                else if (grade_fr == "V14") grade = "XI-";
                else if (grade_fr == "V15") grade = "XI";
                else if (grade_fr == "V16") grade = "XI+";
                else if (grade_fr == "V17") grade = "XII-";
                else if (grade_fr == "Project") grad = "Project";
                else grade = "?";
            } else {
                grad = "Error";
            }
            return grade;
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

                    this.get_spot_rocks_images();
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
