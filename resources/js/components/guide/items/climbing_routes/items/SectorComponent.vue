<template>
    <div class="row">
        <div class="row">
            <div class="col-md-10">
                <h2 :id="'sector-' + sector.sector.id" style="font-size: 160%">
                    {{ $t("guide.article.title.sector name") }} -
                    <strong>{{ sector.sector.name }}</strong>
                </h2>
            </div>
            <div class="col-md-2">
                <a
                    @click="show_sector_canvas_modal()"
                    data-toggle="modal"
                    data-target="#squarespaceModal_route_info_"

                    class="show_sector_canvas_modal_link"
                >
                    <i class="fa fa-list-alt show_sector_canvas_modal_icon" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <img
                    v-if="sector.sector.in_shade_after_10"
                    class="sun_svg"
                    :src="'/images/svg/in shade after 10.00 am.svg'"
                    alt="In shade after 10.00 am"
                    title="In shade after 10.00 am"
                />
                <img
                    v-if="sector.sector.in_shade_after_15"
                    class="sun_svg"
                    :src="'/images/svg/in shade after 15.00 pm.svg'"
                    alt="In shade after 15.00 pm"
                    title="In shade after 15.00 pm"
                />
                <img
                    v-if="sector.sector.in_the_shade_befornoon"
                    class="sun_svg"
                    :src="'/images/svg/in shade befor noon.svg'"
                    alt="In shade befor noon"
                    title="In shade befor noon"
                />
                <img
                    v-if="sector.sector.in_the_shade_afternoon"
                    class="sun_svg"
                    :src="'/images/svg/in shade in the afternoon.svg'"
                    alt="In shade in the afternoon"
                    title="In shade in the afternoon"
                />
                <img
                    v-if="sector.sector.all_day_in_shade"
                    class="sun_svg"
                    :src="'/images/svg/in the shade whole day.svg'"
                    alt="In the shade whole day"
                    title="In the shade whole day"
                />
                <img
                    v-if="sector.sector.all_day_in_sun"
                    class="sun_svg"
                    :src="'/images/svg/in the sun the whole day.svg'"
                    alt="In the sun the whole day"
                    title="In the sun the whole day"
                />
            </div>
            <div class="col-md-6">
                <img
                    v-if="sector.sector.roof"
                    class="relief_svg"
                    :src="'/images/svg/roof.svg'"
                    alt="Roof"
                    title="Roof"
                />
                <img
                    v-if="sector.sector.overhang"
                    class="relief_svg"
                    :src="'/images/svg/overhang.svg'"
                    alt="Overhang"
                    title="Overhang"
                />
                <img
                    v-if="sector.sector.vertical"
                    class="relief_svg"
                    :src="'/images/svg/vertical.svg'"
                    alt="Vertical"
                    title="Vertical"
                />
                <img
                    v-if="sector.sector.slabby"
                    class="relief_svg"
                    :src="'/images/svg/slabby.svg'"
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
                    :src="'/images/svg/for family.svg'"
                    alt="This sector recomendidi family climbing. It`s seifty for kids"
                    title="This sector recomendidi family climbing. It`s seifty for kids"
                />
                <img
                    v-if="sector.sector.for_kids"
                    class="sun_svg"
                    :src="'/images/svg/for kids.svg'"
                    alt="This sector recomendidi for kids. It`a seifty for kids and poseble to kid climbing"
                    title="This sector recomendidi for kids. It`a seifty for kids and poseble to kid climbing"
                />
            </div>
            <div class="col-md-6" v-if="sector.sector.wolking_time">
                <span class="sector_walking_time"> ~ {{ sector.sector.wolking_time }}.</span>
                <img
                    class="relief_svg"
                    :src="'/images/svg/walking.svg'"
                    alt="Wolging time from parking to climbing sector."
                    title="Wolging time from parking to climbing sector."
                />
            </div>
        </div>

        <span v-html="sector.sector.text"></span>

        <!-- <div
            v-for="image in sector.sector_imgs"
            :key="image.id"
            :class="'sector_images sector_images_' + sector.sector_imgs.length"
        >
            <route_json
                :sector_image_id="image.id"
                :image_src="'/public/images/sector_img/' + image.image"
            />
        </div> -->

        <openImg
            v-for="image in sector.sector_imgs"
            :key="image.id"
            :img="'/public/images/sector_img/' + image.image"
            :img_alt="image.image"
            :img_class="
                'sector_images sector_images_' + sector.sector_imgs.length
            "
        />

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



                <tr 
                    v-for="route in sector.sport_routes" 
                    :key="route.id" 
                    :data-route-id="route.id"
                    :class="isRouteActive(route.id) ? 'route-active' : ''"
                >
                    <td>{{ route.num }}</td>
                    <td @click="show_route_modal(route.id)">{{ route.name }}</td>
                    <td>{{ route.height }}</td>

                    <td v-if="route.category == 'tred'">Tred</td>
                    <td v-else-if="route.category == 'top'">Top Rope</td>
                    <td v-else-if="route.category == 'sport climbing'">
                        <span v-if="route.bolts">
                            {{ route.bolts }}
                        </span>
                        <span v-else>
                            ?
                        </span>
                    </td>
                    <td v-else >?</td>

                    <td v-if="route.or_grade != null">
                        {{ route.grade }} / {{ route.or_grade }}
                    </td>
                    <td v-else>{{ route.grade }}</td>

                    <td class="display-none-720px" v-if="route.or_grade != null">
                        {{ lead_grade_chart(route.grade) }} /
                        {{ lead_grade_chart(route.or_grade) }}
                    </td>
                    <td class="display-none-720px" v-else>{{ lead_grade_chart(route.grade) }}</td>

                    <td @click="show_route_modal(route.id)">
                        <a style="margin-top: -5%; font-size: 150%; cursor: pointer;"
                            ><i class="fa fa-arrow-right" aria-hidden="true"></i
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



                <tr 
                    v-for="route in sector.boulder_route" 
                    :key="route.id" 
                    :data-route-id="route.id"
                    :class="isRouteActive(route.id) ? 'route-active' : ''"
                >
                    <td>{{ route.num }}</td>
                    <td @click="show_route_modal(route.id)">
                        {{ route.name }}
                    </td>
                    <td>{{ route.height }}</td>

                    <td v-if="route.or_grade != null">
                        {{ boulder_grade_chart(route.grade) }} /
                        {{ boulder_grade_chart(route.or_grade) }}
                    </td>
                    <td v-else>
                        {{ boulder_grade_chart(route.grade) }}
                    </td>

                    <td class="display-none-720px" v-if="route.or_grade != null">
                        {{ route.grade }} / {{ route.or_grade }}
                    </td>
                    <td class="display-none-720px" v-else>{{ route.grade }}</td>

                    <td @click="show_route_modal(route.id)">
                        <a style="margin-top: -5%; font-size: 150%; cursor: pointer;"
                            ><i class="fa fa-arrow-right" aria-hidden="true"></i
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
                    style="margin-top: -5%; font-size: 150%; cursor: pointer;"
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
                        :data-route-id="pitch.pitch_id"
                        :class="isRouteActive(pitch.pitch_id) ? 'route-active' : ''"
                    >
                        <td>{{ pitch.num }}</td>
                        <td>{{ pitch.name }}</td>
                        <td>{{ pitch.height }}</td>
                        <!-- <td>{{ pitch.bolts }}</td> -->

                        <td v-if="pitch.category == 'tred'">Tred</td>
                        <td v-else-if="pitch.category == 'sport climbing'">
                            <!-- {{ pitch.bolts }} -->
                            <span v-if="pitch.bolts">
                                {{ pitch.bolts }}
                            </span>
                            <span v-else>
                                ?
                            </span>
                        </td>
                        <td v-else>?</td>

                        <td v-if="pitch.or_grade != null">
                            {{ pitch.grade }} /
                            {{ pitch.or_grade }}
                        </td>
                        <td v-else>{{ pitch.grade }}</td>

                        <td class="display-none-720px" v-if="pitch.or_grade != null">
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
            @show_route_review_modal="show_route_review_modal"
            @show_route_all_review_modal="show_route_all_review_modal"
        />

        <create_route_review_modal 
            ref="open_route_review_modal"
            @back_route_modal="show_route_modal"
        />

        <route_all_reviews_modal
            ref="show_route_all_review_modal"
            @back_route_modal="show_route_modal"
        />

        <mtp_modal
            ref="open_mtp_modal"
        />

        <sector_canvas_modal
            ref="sector_canvas_modal"
            :sector="sector"
            @show_route_modal="show_route_modal"
        />

    </div>
</template>

<script>
import route_modal from "./modals/RouteModalComponent.vue";
import create_route_review_modal from "./modals/feedbacks/CreateRouteReviewModal.vue";
import route_all_reviews_modal from "./modals/feedbacks/RouteAllReviewsModal.vue";
import mtp_modal from "./modals/MTPModalComponent.vue";
import sector_canvas_modal from "./modals/SectorCanvasModalComponent.vue";

import openImg from "../../ImageOpenComponent.vue";
import grade_chart  from '../../../../../mixins/grade_chart_mixin.js'

import route_json from './canvas/RouteLinesComponent.vue'

export default {
    mixins: [
        grade_chart,
    ],
    components: {
        openImg,
        route_modal,
        mtp_modal,
        create_route_review_modal,
        route_all_reviews_modal,
        route_json,
        sector_canvas_modal
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
            return this.lead(grade_fr)
        },

        boulder_grade_chart(grade_fr) {
            return this.boulder(grade_fr)
        },



        isRouteActive(routeId) {
            // Check if the current route ID matches the route parameter from URL
            const urlParams = new URLSearchParams(window.location.search);
            const routeParam = urlParams.get('route');
            const sectorParam = urlParams.get('sector');
            
            // Only highlight if both sector and route match
            const currentSectorId = this.sector.sector.id.toString();
            
            return routeParam && sectorParam && 
                   routeParam === routeId.toString() && 
                   sectorParam === currentSectorId;
        },

        show_route_all_review_modal(route_id){
            this.$refs.show_route_all_review_modal.show_route_all_review_modal(route_id)
        },
        show_route_review_modal(route_id){
            this.$refs.open_route_review_modal.show_route_review_modal(route_id)
        },
        show_route_modal(route_id) {
            this.$refs.open_route_modal.show_route_modal(route_id)
        },
        show_mtp_madel(mtp_id) {
            this.$refs.open_mtp_modal.show_mtp_modal(mtp_id)
        },
        show_sector_canvas_modal() {
            this.$refs.sector_canvas_modal.show_modal(this.sector)
        },
    }
}
</script>

<style>
@media (max-width: 767px) {
    /* table {
        display: block;
        overflow-x: scroll;
    } */
}
@media (max-width: 567px) {
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
.sector_walking_time{
    float: right;
    font-size: 1.5em;
}

.spot_rocks_image {
    width: 100%;
}

.sector_images {
    float: left;
    margin: 0.45%;
}
.sector_images_1 {
    width: 99% !important;
}
.sector_images_2 {
    width: 49% !important;
}
.sector_images_3 {
    width: 32% !important;
}
.sector_images_4 {
    width: 24% !important;
}
.sector_images_5 {
    width: 19% !important;
}
.sector_images_6 {
    width: 16% !important;
}
.sector_images_7 {
    width: 14% !important;
}
.sector_images_8 {
    width: 12% !important;
}
.sector_images_9 {
    width: 10% !important;
}
.sector_images_10 {
    width: 9% !important;
}
</style>

<style scoped>
/* Friendly styles for SectorComponent */

h2 {
    font-size: 1.8rem;
    font-weight: 600;
    color: #343a40;
    margin-bottom: 1rem;
}

.sun_svg, .relief_svg {
    height: 40px;
    margin: 0.25rem;
    filter: drop-shadow(0 1px 1px rgba(0,0,0,0.1));
}

.sector_walking_time {
    float: right;
    font-size: 1.5em;
    color: #495057;
    font-weight: 500;
}

.sector_images {
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    transition: transform 0.2s ease-in-out;
}

.sector_images:hover {
    transform: scale(1.05);
}

.show_sector_canvas_modal_icon {
    transition: color 0.3s ease;

    font-size: 3em;
    color: #000;
}
.show_sector_canvas_modal_icon:hover {
    color: #007bff;
    cursor: pointer;
}


.show_sector_canvas_modal_link {
    float: right;
}







/* Active route highlighting - no layout shift */
.route-active {
    background-color: #f0f8ff !important;
    transition: background-color 0.2s ease !important;
}

.route-active td {
    background-color: inherit !important;
    color: #495057 !important;
}

.route-active:hover {
    background-color: #e6f3ff !important;
}

/* Table hover effects */
.table-hover tbody tr:hover {
    background-color: rgba(23, 162, 184, 0.05) !important;
    transition: background-color 0.2s ease !important;
}
</style>
