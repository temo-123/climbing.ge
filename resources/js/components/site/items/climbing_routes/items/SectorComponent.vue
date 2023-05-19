<template>
    <div class="row">
        <span>
            <h2 style="font-size: 160%">
                {{ $t("guide.article.title.sector name") }} -
                <strong>{{ sector.sector.name }}</strong>
            </h2>
        </span>
        <div class="row">
            <div class="col-md-6">
                <img
                    v-if="sector.sector.in_shade_after_10"
                    class="sun_svg"
                    :src="'../images/svg/in shade after 10.00 am.svg'"
                    alt="In shade after 10.00 am"
                    title="In shade after 10.00 am"
                />
                <img
                    v-if="sector.sector.in_shade_after_15"
                    class="sun_svg"
                    :src="'../images/svg/in shade after 15.00 pm.svg'"
                    alt="In shade after 15.00 pm"
                    title="In shade after 15.00 pm"
                />
                <img
                    v-if="sector.sector.in_the_shade_befornoon"
                    class="sun_svg"
                    :src="'../images/svg/in shade befor noon.svg'"
                    alt="In shade befor noon"
                    title="In shade befor noon"
                />
                <img
                    v-if="sector.sector.in_the_shade_afternoon"
                    class="sun_svg"
                    :src="'../images/svg/in shade in the afternoon.svg'"
                    alt="In shade in the afternoon"
                    title="In shade in the afternoon"
                />
                <img
                    v-if="sector.sector.all_day_in_shade"
                    class="sun_svg"
                    :src="'../images/svg/in the shade whole day.svg'"
                    alt="In the shade whole day"
                    title="In the shade whole day"
                />
                <img
                    v-if="sector.sector.all_day_in_sun"
                    class="sun_svg"
                    :src="'../images/svg/in the sun the whole day.svg'"
                    alt="In the sun the whole day"
                    title="In the sun the whole day"
                />
            </div>
            <div class="col-md-6">
                <img
                    v-if="sector.sector.roof"
                    class="relief_svg"
                    :src="'../images/svg/roof.svg'"
                    alt="Roof"
                    title="Roof"
                />
                <img
                    v-if="sector.sector.overhang"
                    class="relief_svg"
                    :src="'../images/svg/overhang.svg'"
                    alt="Overhang"
                    title="Overhang"
                />
                <img
                    v-if="sector.sector.vertical"
                    class="relief_svg"
                    :src="'../images/svg/vertical.svg'"
                    alt="Vertical"
                    title="Vertical"
                />
                <img
                    v-if="sector.sector.slabby"
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
                    v-if="sector.sector.for_family"
                    class="sun_svg"
                    :src="'../images/svg/for family.svg'"
                    alt="This sector recomendidi family climbing. It`s seifty for kids"
                    title="This sector recomendidi family climbing. It`s seifty for kids"
                />
                <img
                    v-if="sector.sector.for_kids"
                    class="sun_svg"
                    :src="'../images/svg/for kids.svg'"
                    alt="This sector recomendidi for kids. It`a seifty for kids and poseble to kid climbing"
                    title="This sector recomendidi for kids. It`a seifty for kids and poseble to kid climbing"
                />
            </div>
            <div class="col-md-6" v-if="sector.sector.wolking_time">
                <span class="sector_walking_time"> ~ {{ sector.sector.wolking_time }}.</span>
                <img
                    class="relief_svg"
                    :src="'../images/svg/walking.svg'"
                    alt="Wolging time from car stoping to climbin sector."
                    title="Wolging time from car stoping to climbin sector."
                />
            </div>
        </div>

        <span v-html="sector.sector.text"></span>

        <openImg
            v-for="image in sector.sector_imgs"
            :key="image.id"
            :img="'/public/images/sector_img/' + image.image"
            :img_alt="image.image"
            :img_class="
                'sector_images sector_images_' +
                sector.sector_imgs.length
            "
        />
        <!-- {{ sector.sector_imgs[0] }} -->

        <table
            class="table col-md-12 table table-hover"
            v-if="sector.sport_routes.length > 0"
        >
            <tbody>
                <tr>
                    <td>N</td>
                    <td>{{ $t("guide.route.name") }}</td>
                    <td>{{ $t("guide.route.height") }}</td>
                    <td>{{ $t("guide.route.bolts") }}</td>
                    <td>{{ $t("guide.route.grade fr") }}</td>
                    <!-- <td>{{ $t("guide.route.grade yds") }}</td> -->
                    <td class="display-none-720px" v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'">{{ $t("guide.route.grade uiaa") }}</td>
                    <td class="display-none-720px" v-if="activ_grade == 'YDS' || activ_grade == 'yds'">{{ $t("guide.route.grade yds") }}</td>
                    <td>Info</td>
                </tr>
            </tbody>
            <tbody>
                <tr v-for="route in sector.sport_routes" :key="route.id">
                    <td>{{ route.num }}</td>
                    <td @click="show_route_modal()">{{ route.name }}</td>
                    <td>{{ route.height }}</td>
                    <td>{{ route.bolts }}</td>

                    <td v-if="route.or_grade != NULL">
                        {{ route.grade }} / {{ route.or_grade }}
                    </td>
                    <td v-else>{{ route.grade }}</td>

                    <td class="display-none-720px" v-if="route.or_grade != NULL">
                        {{ lead_grade_chart(route.grade) }} /
                        {{ lead_grade_chart(route.or_grade) }}
                    </td>
                    <td class="display-none-720px" v-else>{{ lead_grade_chart(route.grade) }}</td>

                    <td @click="show_route_modal(route.id)">
                        <a style="margin-top: -5%; font-size: 120%"
                            ><i class="fa fa-info" aria-hidden="true"></i
                        ></a>
                    </td>
                </tr>
            </tbody>
        </table>

        <table
            class="table col-md-12 table table-hover"
            v-if="sector.boulder_route.length > 0"
        >
            <tbody>
                <tr>
                    <td>N</td>
                    <td>{{ $t("guide.route.name") }}</td>
                    <td>{{ $t("guide.route.height") }}</td>
                    <td class="display-none-720px">{{ $t("guide.route.grade fr") }}</td>
                    <td class="display-none-720px">{{ $t("guide.route.grade yds") }}</td>
                    <td>Info</td>
                </tr>
            </tbody>
            <tbody>
                <tr v-for="route in sector.boulder_route" :key="route.id">
                    <td>{{ route.num }}</td>
                    <td @click="show_route_modal(route.id)">
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

                    <td class="display-none-720px" v-if="route.or_grade != NULL">
                        {{ route.grade }} / {{ route.or_grade }}
                    </td>
                    <td class="display-none-720px" v-else>{{ route.grade }}</td>

                    <td @click="show_route_modal(route.id)">
                        <a style="margin-top: -5%; font-size: 120%"
                            ><i class="fa fa-info" aria-hidden="true"></i
                        ></a>
                    </td>
                </tr>
            </tbody>
        </table>

        <span
            :if="sector.mtps.length > 0"
            v-for="mtp in sector.mtps"
            :key="mtp.id"
        >
            <div class="col-md-8">
                <span class="mtp_name"
                    ><h3>
                        {{ $t("guide.route.mtp name") }} -
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
                        <td>{{ $t("guide.route.name") }}</td>
                        <td>{{ $t("guide.route.height") }}</td>
                        <td>{{ $t("guide.route.bolts") }}</td>
                        <td>{{ $t("guide.route.grade fr") }}</td>
                        
                        <!-- <td >
                            {{ $t("guide.route.grade yds") }}
                        </td> -->

                        <td class="display-none-720px" v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'">{{ $t("guide.route.grade uiaa") }}</td>
                        <td class="display-none-720px" v-if="activ_grade == 'YDS' || activ_grade == 'yds'">{{ $t("guide.route.grade yds") }}</td>
                    </tr>
                </tbody>
                <tbody>
                    <tr
                        v-for="pitch in mtp.mtp_pitchs"
                        :key="pitch.pitch_id"
                    >
                        <td>{{ pitch.num }}</td>
                        <td>{{ pitch.name }}</td>
                        <td>{{ pitch.height }}</td>
                        <td>{{ pitch.bolts }}</td>

                        <td v-if="pitch.or_grade != NULL">
                            {{ pitch.grade }} /
                            {{ pitch.or_grade }}
                        </td>
                        <td v-else>{{ pitch.grade }}</td>

                        <td class="display-none-720px" v-if="pitch.or_grade != NULL">
                            {{ lead_grade_chart(pitch.grade) }} /
                            {{ lead_grade_chart(pitch.or_grade) }}
                        </td>
                        <td class="display-none-720px" v-else>
                            {{ lead_grade_chart(pitch.grade) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </span>

        <route_modal 
            ref="open_route_modal"
        />
        <mtp_modal 
            ref="open_mtp_modal"
        />

    </div>
</template>

<script>
import route_modal from "./modals/RouteModalComponent.vue";
import mtp_modal from "./modals/MTPModalComponent.vue";

import openImg from "../../ImageOpenComponent.vue";
export default {
    components: {
        openImg,
        route_modal,
        mtp_modal,
    },
    props: [
        "sector",
    ],
    data: function () {
        return {
            // climbing_sector: [],

            get activ_grade() {
                return localStorage.getItem('grade') || 'yds';
            },
            set activ_grade(value) {
                localStorage.setItem('grade', value);
            },
        };
    },
    mounted() {
        // this.get_spot_rocks_images();
    },
    methods: {
        lead_grade_chart(grade_fr) {
            var grad = "";
            if (localStorage.getItem("grade") == "yds" || localStorage.getItem("grade") == "YDS") {
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
            } else if (localStorage.getItem("grade") == "UIAA" || localStorage.getItem("grade") == "uiaa") {
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
            if (localStorage.getItem("grade") == "yds" || localStorage.getItem("grade") == "YDS") {
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
            } else if (localStorage.getItem("grade") == "UIAA" || localStorage.getItem("grade") == "uiaa") {
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

        show_route_modal(route_id) {
            this.$refs.open_route_modal.show_route_modal(route_id)
            // console.log("🚀 ~ file: SectorComponent.vue:456 ~ show_route_model ~ this.$refs.route_modal", this.$refs.open_route_modal.show_route_modal)
        },
        show_mtp_madel(mtp_id) {
            this.$refs.open_mtp_modal.show_mtp_modal(mtp_id)
        },
    }
}
</script>

<style>
.sector_walking_time{
    float: right;
    font-size: 1.5em;
}
</style>