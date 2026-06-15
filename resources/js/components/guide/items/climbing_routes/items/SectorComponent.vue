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
                    v-if="sector.sector.has_route_drawings"
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
                <span
                    v-if="sector.sector.in_shade_after_10"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'shade10' }"
                    @mouseenter="activeTooltip = 'shade10'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'shade10' ? null : 'shade10'"
                >
                    <img class="sun_svg" :src="'/images/svg/in shade after 10.00 am.svg'" alt="In shade after 10:00 am" />
                    <span class="tip-text">In shade after 10:00 am</span>
                </span>
                <span
                    v-if="sector.sector.in_shade_after_15"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'shade15' }"
                    @mouseenter="activeTooltip = 'shade15'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'shade15' ? null : 'shade15'"
                >
                    <img class="sun_svg" :src="'/images/svg/in shade after 15.00 pm.svg'" alt="In shade after 15:00 pm" />
                    <span class="tip-text">In shade after 15:00 pm</span>
                </span>
                <span
                    v-if="sector.sector.in_the_shade_befornoon"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'shadeNoon' }"
                    @mouseenter="activeTooltip = 'shadeNoon'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'shadeNoon' ? null : 'shadeNoon'"
                >
                    <img class="sun_svg" :src="'/images/svg/in shade befor noon.svg'" alt="In shade before noon" />
                    <span class="tip-text">In shade before noon</span>
                </span>
                <span
                    v-if="sector.sector.in_the_shade_afternoon"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'shadeAfternoon' }"
                    @mouseenter="activeTooltip = 'shadeAfternoon'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'shadeAfternoon' ? null : 'shadeAfternoon'"
                >
                    <img class="sun_svg" :src="'/images/svg/in shade in the afternoon.svg'" alt="In shade in the afternoon" />
                    <span class="tip-text">In shade in the afternoon</span>
                </span>
                <span
                    v-if="sector.sector.all_day_in_shade"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'shadeAllDay' }"
                    @mouseenter="activeTooltip = 'shadeAllDay'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'shadeAllDay' ? null : 'shadeAllDay'"
                >
                    <img class="sun_svg" :src="'/images/svg/in the shade whole day.svg'" alt="In the shade whole day" />
                    <span class="tip-text">In the shade whole day</span>
                </span>
                <span
                    v-if="sector.sector.all_day_in_sun"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'sunAllDay' }"
                    @mouseenter="activeTooltip = 'sunAllDay'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'sunAllDay' ? null : 'sunAllDay'"
                >
                    <img class="sun_svg" :src="'/images/svg/in the sun the whole day.svg'" alt="In the sun the whole day" />
                    <span class="tip-text">In the sun the whole day</span>
                </span>
            </div>
            <div class="col-md-6">
                <span
                    v-if="sector.sector.roof"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'roof' }"
                    @mouseenter="activeTooltip = 'roof'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'roof' ? null : 'roof'"
                >
                    <img class="relief_svg" :src="'/images/svg/roof.svg'" alt="Roof" />
                    <span class="tip-text">Roof</span>
                </span>
                <span
                    v-if="sector.sector.overhang"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'overhang' }"
                    @mouseenter="activeTooltip = 'overhang'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'overhang' ? null : 'overhang'"
                >
                    <img class="relief_svg" :src="'/images/svg/overhang.svg'" alt="Overhang" />
                    <span class="tip-text">Overhang</span>
                </span>
                <span
                    v-if="sector.sector.vertical"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'vertical' }"
                    @mouseenter="activeTooltip = 'vertical'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'vertical' ? null : 'vertical'"
                >
                    <img class="relief_svg" :src="'/images/svg/vertical.svg'" alt="Vertical" />
                    <span class="tip-text">Vertical</span>
                </span>
                <span
                    v-if="sector.sector.slabby"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'slabby' }"
                    @mouseenter="activeTooltip = 'slabby'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'slabby' ? null : 'slabby'"
                >
                    <img class="relief_svg" :src="'/images/svg/slabby.svg'" alt="Slabby" />
                    <span class="tip-text">Slabby</span>
                </span>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <span
                    v-if="sector.sector.for_family"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'family' }"
                    @mouseenter="activeTooltip = 'family'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'family' ? null : 'family'"
                >
                    <img class="sun_svg" :src="'/images/svg/for family.svg'" alt="For family" />
                    <span class="tip-text">Recommended for family climbing. Safe for kids</span>
                </span>
                <span
                    v-if="sector.sector.for_kids"
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'kids' }"
                    @mouseenter="activeTooltip = 'kids'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'kids' ? null : 'kids'"
                >
                    <img class="sun_svg" :src="'/images/svg/for kids.svg'" alt="For kids" />
                    <span class="tip-text">Recommended for kids. Safe and possible for kids to climb</span>
                </span>
            </div>
            <div class="col-md-6" v-if="sector.sector.wolking_time">
                <span class="sector_walking_time"> ~ {{ sector.sector.wolking_time }}.</span>
                <span
                    class="icon-tip"
                    :class="{ 'tip-open': activeTooltip === 'walking' }"
                    @mouseenter="activeTooltip = 'walking'"
                    @mouseleave="activeTooltip = null"
                    @click.stop="activeTooltip = activeTooltip === 'walking' ? null : 'walking'"
                >
                    <img class="relief_svg" :src="'/images/svg/walking.svg'" alt="Walking time" />
                    <span class="tip-text">~ {{ sector.sector.wolking_time }} walk from parking to sector</span>
                </span>
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

                    <td class="route-seo-col" v-html="[route.text, route.author ? 'Author: ' + route.author : null, route.creation_data ? 'Created: ' + route.creation_data : null, route.first_ascent ? 'First ascent: ' + route.first_ascent : null, route.anchor_type ? 'Anchor type: ' + route.anchor_type : null, route.reviews_count ? 'Rating: ' + route.reviews_stars + '/5 (' + route.reviews_count + ' reviews)' : null].filter(Boolean).join(' | ')"></td>
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

                    <td class="route-seo-col" v-html="[route.text, route.author ? 'Author: ' + route.author : null, route.creation_data ? 'Created: ' + route.creation_data : null, route.first_ascent ? 'First ascent: ' + route.first_ascent : null, route.anchor_type ? 'Anchor type: ' + route.anchor_type : null, route.reviews_count ? 'Rating: ' + route.reviews_stars + '/5 (' + route.reviews_count + ' reviews)' : null].filter(Boolean).join(' | ')"></td>
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
                <starsReiting
                    v-if="mtp.reviews_count > 0"
                    :reviews_count_prop="mtp.reviews_count"
                    :reviews_stars_prop="mtp.reviews_stars"
                />
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
            @show_mtp_review_modal="show_mtp_review_modal"
            @show_mtp_all_review_modal="show_mtp_all_review_modal"
        />

        <create_mtp_review_modal
            ref="open_mtp_review_modal"
            @back_mtp_modal="show_mtp_madel"
        />

        <mtp_all_reviews_modal
            ref="show_mtp_all_review_modal"
            @back_mtp_modal="show_mtp_madel"
        />

        <sector_canvas_modal
            ref="sector_canvas_modal"
            :sector="sector"
            @show_route_modal="show_route_modal"
            @show_mtp_modal="show_mtp_madel"
            size="xl"
        />

    </div>
</template>

<script>
import route_modal from "./modals/RouteModalComponent.vue";
import create_route_review_modal from "./modals/feedbacks/CreateRouteReviewModal.vue";
import route_all_reviews_modal from "./modals/feedbacks/RouteAllReviewsModal.vue";
import mtp_modal from "./modals/MTPModalComponent.vue";
import create_mtp_review_modal from "./modals/feedbacks/CreateMtpReviewModal.vue";
import mtp_all_reviews_modal from "./modals/feedbacks/MtpAllReviewsModal.vue";
import sector_canvas_modal from "./modals/SectorCanvasModalComponent.vue";
import starsReiting from '../../../../global_components/StarReitingShowComponent.vue';

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
        create_mtp_review_modal,
        mtp_all_reviews_modal,
        route_json,
        sector_canvas_modal,
        starsReiting,
    },
    props: [
        "sector",
    ],

    data: function () {
        return {
            activeTooltip: null,

            get activ_grade() {
                return localStorage.getItem('grade') || 'yds';
            },
            set activ_grade(value) {
                localStorage.setItem('grade', value);
            },
        };
    },
    mounted() {
        document.addEventListener('click', this.closeTooltip);
    },
    beforeUnmount() {
        document.removeEventListener('click', this.closeTooltip);
    },

    methods: {
        closeTooltip() {
            this.activeTooltip = null;
        },

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
        show_mtp_review_modal(mtp_id) {
            this.$refs.open_mtp_review_modal.show_mtp_review_modal(mtp_id)
        },
        show_mtp_all_review_modal(mtp_id) {
            this.$refs.show_mtp_all_review_modal.show_mtp_all_review_modal(mtp_id)
        },
        show_sector_canvas_modal() {
            this.$refs.sector_canvas_modal.show_sector_modal(this.sector)
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







.icon-tip {
    position: relative;
    display: inline-block;
    cursor: pointer;
}

.tip-text {
    display: none;
    position: absolute;
    bottom: 115%;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(30, 30, 30, 0.88);
    color: #fff;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 0.78rem;
    white-space: nowrap;
    z-index: 300;
    pointer-events: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.tip-text::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    border: 5px solid transparent;
    border-top-color: rgba(30, 30, 30, 0.88);
}

.icon-tip:hover .tip-text,
.icon-tip.tip-open .tip-text {
    display: block;
}

/* SEO column — in DOM for Google, hidden from users */
.route-seo-col {
    display: none;
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
