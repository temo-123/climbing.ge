<template>
    <div class="sector-block">
        <div class="sector-header">
            <div class="sector-header-title">
                <!-- <h2 :id="'sector-' + sector.sector.id"> -->
                <h2 :id="'sector-' + sector.sector.id">
                    {{ $t("guide.article.title.sector name") }} —
                    <strong>{{ sector.sector.name }}</strong>
                </h2>
            </div>
            <div v-if="sector.has_route_drawings" class="sector-header-action">
                <a
                    @click="show_sector_canvas_modal()"
                    class="show_sector_canvas_modal_link"
                >
                    <i class="fa fa-list-alt show_sector_canvas_modal_icon" aria-hidden="true"></i>
                </a>
            </div>
        </div>
        <div class="row">
            <!-- Left: sun / shade icons + walking time -->
            <div class="col-md-6">
                <div class="icon-col icons-left">
                    <sector-icon-tip v-if="sector.sector.in_shade_after_10"      src="/images/svg/in shade after 10.00 am.svg"    :title="$t('guide.sector.shade after 10.00 am')"    :desc="$t('guide.sector.shade after 10.00 am_desc')" />
                    <sector-icon-tip v-if="sector.sector.in_shade_after_15"      src="/images/svg/in shade after 15.00 pm.svg"    :title="$t('guide.sector.shade after 15.00 pm')"    :desc="$t('guide.sector.shade after 15.00 pm_desc')" />
                    <sector-icon-tip v-if="sector.sector.in_the_shade_befornoon" src="/images/svg/in shade befor noon.svg"        :title="$t('guide.sector.shade befor noon')"         :desc="$t('guide.sector.shade befor noon_desc')" />
                    <sector-icon-tip v-if="sector.sector.in_the_shade_afternoon" src="/images/svg/in shade in the afternoon.svg"  :title="$t('guide.sector.shade in the afternoon')"  :desc="$t('guide.sector.shade in the afternoon_desc')" />
                    <sector-icon-tip v-if="sector.sector.all_day_in_shade"       src="/images/svg/in the shade whole day.svg"     :title="$t('guide.sector.shade whole day')"          :desc="$t('guide.sector.shade whole day_desc')" />
                    <sector-icon-tip v-if="sector.sector.all_day_in_sun"         src="/images/svg/in the sun the whole day.svg"   :title="$t('guide.sector.sun the whole day')"        :desc="$t('guide.sector.sun the whole day_desc')" />
                </div>
                <div v-if="sector.sector.wolking_time">
                    <div class="walking-card">
                        <img class="walking-icon" src="/images/svg/walking.svg" :alt="$t('guide.sector.walking_label')" />
                        <div class="walking-info">
                            <strong class="walking-time">~ {{ sector.sector.wolking_time }}</strong>
                            <small class="walking-desc">{{ $t('guide.sector.walking') }}</small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right: relief icons + family/kids below -->
            <div class="col-md-6">
                <div class="icon-col icons-right">
                    <sector-icon-tip v-if="sector.sector.roof"     src="/images/svg/roof.svg"     img-class="relief_svg" :title="$t('guide.sector.roof')"     :desc="$t('guide.sector.roof_desc')" />
                    <sector-icon-tip v-if="sector.sector.overhang" src="/images/svg/overhang.svg" img-class="relief_svg" :title="$t('guide.sector.overhang')" :desc="$t('guide.sector.overhang_desc')" />
                    <sector-icon-tip v-if="sector.sector.vertical" src="/images/svg/vertical.svg" img-class="relief_svg" :title="$t('guide.sector.vertical')" :desc="$t('guide.sector.vertical_desc')" />
                    <sector-icon-tip v-if="sector.sector.slabby"   src="/images/svg/slabby.svg"   img-class="relief_svg" :title="$t('guide.sector.slabby')"   :desc="$t('guide.sector.slabby_desc')" />
                </div>
                <div class="icon-col icons-right" style="margin-top: 8px;">
                    <sector-icon-tip v-if="sector.sector.for_family" src="/images/svg/for family.svg" :title="$t('guide.sector.for_family_label')" :desc="$t('guide.sector.for_family')" />
                    <sector-icon-tip v-if="sector.sector.for_kids"   src="/images/svg/for kids.svg"   :title="$t('guide.sector.for_kids_label')"   :desc="$t('guide.sector.for_kids')" />
                </div>
            </div>
        </div>

        <div class="col-12 sector-text" v-html="sector.sector.text"></div>

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

        <div class="col-12 sector-images-wrap" v-if="sector.sector_imgs.length > 0">
            <openImg
                v-for="image in sector.sector_imgs"
                :key="image.id"
                :img="'/public/images/sector_img/' + image.image"
                :img_alt="image.image"
                :img_class="'sector_images sector_images_' + sector.sector_imgs.length"
            />
        </div>

        <div class="col-12 table-responsive" v-if="sector.sport_routes.length > 0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>N</th>
                    <th>{{ $t("common.name") }}</th>
                    <th>{{ $t("common.height") }}</th>
                    <th>{{ $t("guide.route.bolts") }}</th>
                    <th>{{ $t("guide.route.grade fr") }}</th>
                    <th class="display-none-720px" v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'">{{ $t("guide.route.grade uiaa") }}</th>
                    <th class="display-none-720px" v-if="activ_grade == 'YDS' || activ_grade == 'yds'">{{ $t("guide.route.grade yds") }}</th>
                    <th class="col-info">Info</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="route in sector.sport_routes"
                    :key="route.id"
                    :data-route-id="route.id"
                    :class="isRouteActive(route.id) ? 'route-active' : ''"
                >
                    <td>{{ route.num }}</td>
                    <td class="route-name-cell" @click="show_route_modal(route.id)">{{ route.name }}</td>
                    <td>{{ route.height }}</td>

                    <td v-if="route.category == 'tred'">Tred</td>
                    <td v-else-if="route.category == 'top'">Top Rope</td>
                    <td v-else-if="route.category == 'sport climbing'">{{ route.bolts || '?' }}</td>
                    <td v-else>?</td>

                    <td v-if="route.or_grade != null">{{ route.grade }} / {{ route.or_grade }}</td>
                    <td v-else>{{ route.grade }}</td>

                    <td class="display-none-720px" v-if="route.or_grade != null">
                        {{ lead_grade_chart(route.grade) }} / {{ lead_grade_chart(route.or_grade) }}
                    </td>
                    <td class="display-none-720px" v-else>{{ lead_grade_chart(route.grade) }}</td>

                    <td class="col-info" @click="show_route_modal(route.id)">
                        <i class="fa fa-arrow-right route-arrow" aria-hidden="true"></i>
                    </td>

                    <td class="route-seo-col" v-html="[
                        route.text, 
                        route.author ? 'Author: ' + route.author : null, 
                        route.creation_data ? 'Created: ' + route.creation_data : null, 
                        route.first_ascent ? 'First ascent: ' + route.first_ascent : null, 
                        route.anchor_type ? 'Anchor type: ' + route.anchor_type : null, 
                        route.bolts_type ? 'Bolt type: ' + route.bolts_type : null, 
                        route.reviews_count ? 'Rating: ' + route.reviews_stars + '/5 (' + route.reviews_count + ' reviews)' : null
                        ].filter(Boolean).join(' | ')"></td>
                </tr>
            </tbody>
        </table>
        </div>

        <div class="col-12 table-responsive" v-if="sector.boulder_route.length > 0">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>N</th>
                    <th>{{ $t("common.name") }}</th>
                    <th>{{ $t("common.height") }}</th>
                    <th>{{ $t("guide.route.grade fr") }}</th>
                    <th class="display-none-720px">{{ $t("guide.route.grade yds") }}</th>
                    <th class="col-info">Info</th>
                </tr>
            </thead>
            <tbody>
                <tr
                    v-for="route in sector.boulder_route"
                    :key="route.id"
                    :data-route-id="route.id"
                    :class="isRouteActive(route.id) ? 'route-active' : ''"
                >
                    <td>{{ route.num }}</td>
                    <td class="route-name-cell" @click="show_route_modal(route.id)">{{ route.name }}</td>
                    <td>{{ route.height }}</td>

                    <td v-if="route.or_grade != null">
                        {{ boulder_grade_chart(route.grade) }} / {{ boulder_grade_chart(route.or_grade) }}
                    </td>
                    <td v-else>{{ boulder_grade_chart(route.grade) }}</td>

                    <td class="display-none-720px" v-if="route.or_grade != null">{{ route.grade }} / {{ route.or_grade }}</td>
                    <td class="display-none-720px" v-else>{{ route.grade }}</td>

                    <td class="col-info" @click="show_route_modal(route.id)">
                        <i class="fa fa-arrow-right route-arrow" aria-hidden="true"></i>
                    </td>

                    <td class="route-seo-col" v-html="[
                        route.text,
                        route.author ? 'Author: ' + route.author : null,
                        route.creation_data ? 'Created: ' + route.creation_data : null,
                        route.first_ascent ? 'First ascent: ' + route.first_ascent : null,
                        route.anchor_type ? 'Anchor type: ' + route.anchor_type : null, 
                        route.bolts_type ? 'Bolt type: ' + route.bolts_type : null, 
                        route.reviews_count ? 'Rating: ' + route.reviews_stars + '/5 (' + route.reviews_count + ' reviews)' : null
                    ].filter(Boolean).join(' | ')"></td>
                </tr>
            </tbody>
        </table>
        </div>

        <div
            v-for="mtp in sector.mtps"
            :key="mtp.id"
            class="col-12 mtp-block"
        >
            <div class="row align-items-center">
                <div class="col-12 col-md-8">
                    <h3 class="mtp-name">
                        {{ $t("guide.route.mtp name") }} —
                        <strong>{{ mtp.mtp_name }}</strong>
                    </h3>
                    <starsReiting
                        v-if="mtp.reviews_count > 0"
                        :reviews_count_prop="mtp.reviews_count"
                        :reviews_stars_prop="mtp.reviews_stars"
                    />
                </div>
                <div class="col-12 col-md-4 d-flex align-items-center">
                    <a
                        @click="show_mtp_madel(mtp.mtp_id)"
                        data-toggle="modal"
                        data-target="#squarespaceModal_mtp_info_"
                        class="mtp-more-link"
                    >
                        More information <i class="fa fa-info-circle" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>N</th>
                        <th>{{ $t("common.name") }}</th>
                        <th>{{ $t("common.height") }}</th>
                        <th>{{ $t("guide.route.bolts") }}</th>
                        <th>{{ $t("guide.route.grade fr") }}</th>
                        <th class="display-none-720px" v-if="activ_grade == 'UIAA' || activ_grade == 'uiaa'">{{ $t("guide.route.grade uiaa") }}</th>
                        <th class="display-none-720px" v-if="activ_grade == 'YDS' || activ_grade == 'yds'">{{ $t("guide.route.grade yds") }}</th>
                    </tr>
                </thead>
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

                        <td v-if="pitch.category == 'tred'">Tred</td>
                        <td v-else-if="pitch.category == 'sport climbing'">{{ pitch.bolts || '?' }}</td>
                        <td v-else>?</td>

                        <td v-if="pitch.or_grade != null">{{ pitch.grade }} / {{ pitch.or_grade }}</td>
                        <td v-else>{{ pitch.grade }}</td>

                        <td class="display-none-720px" v-if="pitch.or_grade != null">
                            {{ lead_grade_chart(pitch.grade) }} / {{ lead_grade_chart(pitch.or_grade) }}
                        </td>
                        <td class="display-none-720px" v-else>{{ lead_grade_chart(pitch.grade) }}</td>
                    </tr>
                </tbody>
            </table>
            </div>
        </div>

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
import SectorIconTip from './SectorIconTip.vue';

import openImg from "../../ImageOpenComponent.vue";
import grade_chart  from '../../../../../mixins/grade_chart_mixin.js'

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
        sector_canvas_modal,
        starsReiting,
        SectorIconTip,
    },
    props: [
        "sector",
    ],

    data: function () {
        return {
            get activ_grade() {
                return localStorage.getItem('grade') || 'yds';
            },
            set activ_grade(value) {
                localStorage.setItem('grade', value);
            },
        };
    },
    mounted() {},

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
.sector_walking_time {
    float: right;
    font-size: 1.5em;
}

.spot_rocks_image {
    width: 100%;
}

/* Sector images — float grid, responsive */
.sector-images-wrap { overflow: hidden; }

.sector_images {
    float: left;
    margin: 0.45%;
    border-radius: 6px;
}
.sector_images_1  { width: 99%  !important; }
.sector_images_2  { width: 49%  !important; }
.sector_images_3  { width: 32%  !important; }
.sector_images_4  { width: 24%  !important; }
.sector_images_5  { width: 19%  !important; }
.sector_images_6  { width: 16%  !important; }
.sector_images_7  { width: 14%  !important; }
.sector_images_8  { width: 12%  !important; }
.sector_images_9  { width: 10%  !important; }
.sector_images_10 { width:  9%  !important; }

/* On mobile: max 2 images per row */
@media (max-width: 576px) {
    .sector_images_1,
    .sector_images_2  { width: 99%  !important; }
    .sector_images_3,
    .sector_images_4,
    .sector_images_5,
    .sector_images_6,
    .sector_images_7,
    .sector_images_8,
    .sector_images_9,
    .sector_images_10 { width: 49%  !important; }
}
</style>

<style scoped>
/* Friendly styles for SectorComponent */

.sector-block {
    width: 100%;
    padding: 0;
}

/* Zero out column gutters so sector content aligns with page text */
/* .sector-block .row.mx-0 > [class*="col"] { */
.sector-block .mx-0 > [class*="col"] {
    padding-left: 0;
    padding-right: 0;
}

/* h2 {
    font-size: 1.6rem;
    font-weight: 700;
    color: #343a40;
    margin-bottom: 0.75rem;
    line-height: 1.3;
} */

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


/* Flex row instead of floats — floated elements with no clearfix collapsed this
   row's height on narrow screens, letting the icon-rows below render on top of
   (and swallow clicks meant for) the modal-open button. */
.sector-header {
    display: flex;
    width: 100%;
    align-items: flex-start;
    gap: 8px;
}
.sector-header-title {
    /* flex-basis:0 (not auto) so this always claims the full remaining row
       width via flex-grow, regardless of the h2 text's own intrinsic/content
       size — with a break-word content size counted in, "auto" basis could
       resolve to a near-zero width instead of actually filling the row. */
    flex: 1 1 0;
    min-width: 0;
    overflow-wrap: break-word;
    word-break: break-word;
}
.sector-header-action {
    flex: 0 0 auto;
    margin-left: auto;
}

.show_sector_canvas_modal_link {
    display: inline-block;
}







.icon-col {
    display: flex;
    flex-wrap: wrap;
    align-items: flex-start;
    gap: 8px;
    padding: 4px 0;
}
.icon-col.icons-left  { justify-content: flex-start; }
.icon-col.icons-right { justify-content: flex-end; }

.walking-card {
    display: inline-flex;
    align-items: center;
    gap: 12px;
    background: rgba(0, 0, 0, 0.05);
    border-radius: 10px;
    padding: 10px 16px;
    margin: 4px 0;
}

.walking-icon {
    height: 40px;
    filter: drop-shadow(0 1px 1px rgba(0,0,0,0.1));
}

.walking-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.walking-time {
    font-size: 1.4rem;
    font-weight: 700;
    color: #2c3e50;
    line-height: 1.2;
}

.walking-desc {
    font-size: 1rem;
    color: #666;
    line-height: 1.3;
}

/* Info column — narrow, centered arrow */
.col-info {
    width: 1%;
    white-space: nowrap;
    text-align: center;
}

/* Route name — clickable */
.route-name-cell {
    cursor: pointer;
}
.route-name-cell:hover {
    color: #007bff;
}

/* Arrow icon in info column */
.route-arrow {
    font-size: 1.2rem;
    color: #6c757d;
    cursor: pointer;
}
.route-arrow:hover {
    color: #007bff;
}

/* SEO column — in DOM for Google, hidden from users */
.route-seo-col {
    display: none;
}

/* Rich-text sector description */
.sector-text {
    word-break: break-word;
    overflow-wrap: break-word;
    margin-top: 0.5rem;
    margin-bottom: 0.5rem;
}

/* MTP section */
.mtp-block {
    margin-top: 1.5rem;
    padding-top: 1rem;
    border-top: 1px solid #dee2e6;
}

.mtp-name {
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.mtp-more-link {
    display: inline-block;
    cursor: pointer;
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    font-size: 1rem;
    padding: 6px 14px;
    border: 1px solid #007bff;
    border-radius: 6px;
    transition: background 0.2s, color 0.2s;
}
.mtp-more-link:hover {
    background: #007bff;
    color: #fff;
    text-decoration: none;
}

/* Hide grade conversion columns on narrow screens */
@media (max-width: 720px) {
    .display-none-720px {
        display: none !important;
    }
}

/* Mobile */
@media (max-width: 767px) {
    /* h2 {
        font-size: 1.2rem;
    } */
    /* Stack title above the modal-open button instead of sharing a row —
       squeezing the title into a narrow flex column pushed long words past
       the box edge. */
    .sector-header {
        flex-direction: column;
    }
    .sector-header-action {
        align-self: flex-end;
    }
    .icon-col.icons-left,
    .icon-col.icons-right {
        justify-content: center;
    }
    .walking-card {
        width: 100%;
        justify-content: center;
    }
    .mtp-more-link {
        margin-top: 0.5rem;
        width: 100%;
        text-align: center;
    }
    .show_sector_canvas_modal_icon {
        font-size: 2em;
    }
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
