<template>
    <div id="sectors">
        <areaRouteToolbar :climbing_area="climbing_area" :tools="route_tools" />

        <!-- how the sectors are shown: Full (default) / Tiles / Table / Tabs
             (services/guide/sectorView.js) -->
        <div v-if="sector_count > 1" class="cg-sector-view-bar d-print-none">
            <viewSegmentSwitch
                :model-value="view_mode"
                :views="sector_views"
                :label="$t('guide.sector_view.label')"
                @update:model-value="set_sector_view"
            />
        </div>

        <div v-if="spot_images && spot_images.length > 0">
            <div
                :class="'sector_images sector_images_' + spot_images.length"
                v-for="spot_image in spot_images"
                :key="spot_image && spot_image.id ? spot_image.id : 'spot-img-' + Math.random()"
            >

                <!-- Photo has one or more sector drawings on it — show the same
                     hover-tooltip / click-to-scroll canvas overlay
                     sector_local_images already uses (SectorLocalImageCanvasComponent),
                     instead of a plain lightbox photo. Its `layouts` prop shape
                     ({id, json, sector_id, sector, canvas_width, canvas_height,
                     bg_*}) matches spot_rocks_image_jsons rows exactly. -->
                <SectorLocalImageCanvas
                    v-if="spot_image && spot_image.image && spot_image.jsons && spot_image.jsons.length > 0"
                    :image_src="spotImageSrc(spot_image)"
                    :layouts="spot_image.jsons"
                    :extra_drawing_url="spot_image.id ? ('/get_sector/get_spot_rocks_image_extra_drawing/get/' + spot_image.id) : null"
                />
                <openImg
                    v-else-if="spot_image && spot_image.image"
                    :img="'/public/images/spot_rocks_img/' + spot_image.image"
                    :img_alt="spot_image.title || $t('guide.spot_rock_image_alt')"
                    :gallery="spot_gallery"
                    :gallery_index="spot_gallery_index(spot_image)"
                />
            </div>
        </div>


        <!-- clear: the area photos above are floated -->
        <sectorTiles v-if="view_mode === 'tiles'" class="cg-sector-view" :climbing_area="climbing_area" />
        <sectorTable v-else-if="view_mode === 'table'" class="cg-sector-view" :climbing_area="climbing_area" />
        <sectorTabs v-else-if="view_mode === 'tabs'" class="cg-sector-view" :climbing_area="climbing_area" />

        <template v-else>
        <div
            v-for="area in climbing_area"
            :key="area && area.sector && area.sector.id ? area.sector.id : 'area-' + Math.random()"
            :id="area['local_images'] && area.local_images[0] ? 'local-image-' + area.local_images[0].id : null"
        >
            <template v-if="area['local_images']">
                <sector_and_local_area_images :sectors_and_images="area"/>
            </template>
            <template v-else>
                <sector :sector="area"/>
            </template>
        </div>
        </template>

    </div>
</template>

<script>
// import StackModal from "@innologica/vue-stackable-modal"; //https://innologica.github.io/vue-stackable-modal/#sample-css

import sector from "./items/SectorComponent.vue"
import sector_and_local_area_images from "./items/SectorsAndAreaLocalImageComponrnt.vue"
import SectorLocalImageCanvas from "./items/SectorLocalImageCanvasComponent.vue"

import openImg from "../ImageOpenComponent.vue";
import areaRouteToolbar from "./AreaRouteToolbarComponent.vue";
import { new_route_tools, flat_sectors, flat_routes } from '../../../../services/guide/routeFilters.js'
import { sector_view, set_sector_view } from '../../../../services/guide/sectorView.js'
import viewSegmentSwitch from '../ViewSegmentSwitchComponent.vue'
import sectorTiles from './views/SectorTilesComponent.vue'
import sectorTable from './views/SectorRoutesTableComponent.vue'
import sectorTabs from './views/SectorTabsComponent.vue'

// best style first, for the ✓ badge when a route was logged more than once
const STYLE_ORDER = ['onsight', 'flash', 'redpoint', 'toprope'];

import  grade_chart  from '../../../../mixins/grade_chart_mixin.js'

export default {
    mixins: [
        // site_data,
        grade_chart,
    ],
    components: {
        sector,
        sector_and_local_area_images,
        SectorLocalImageCanvas,

        openImg,
        areaRouteToolbar,
        viewSegmentSwitch,
        sectorTiles,
        sectorTable,
        sectorTabs,
    },
    // one reactive filter/ticks object for every SectorComponent on the page
    provide() {
        return { route_tools: this.route_tools };
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
    computed: {
        sector_count() {
            return flat_sectors(this.climbing_area).length;
        },
        sector_views() {
            return [
                { mode: 'full',  icon: 'fa-th-list',     label: this.$t('guide.sector_view.full') },
                { mode: 'tiles', icon: 'fa-th-large',    label: this.$t('guide.sector_view.tiles') },
                { mode: 'table', icon: 'fa-table',       label: this.$t('guide.sector_view.table') },
                { mode: 'tabs',  icon: 'fa-folder-o',    label: this.$t('guide.sector_view.tabs') },
            ];
        },
        // a single sector has nothing to switch between
        view_mode() {
            return this.sector_count > 1 ? this.sector_view.mode : 'full';
        },
        // The whole spot-rock photo set as one browsable lightbox gallery.
        spot_gallery() {
            return (this.spot_images || [])
                .filter(spot_image => spot_image && spot_image.image)
                .map(spot_image => ({
                    src: '/public/images/spot_rocks_img/' + spot_image.image,
                    alt: spot_image.title || this.$t('guide.spot_rock_image_alt'),
                }));
        },
    },

    data: function () {
        return {
            climbing_area: [],
            spot_images: [],
            sector_view,

            show_route_modal: false,
            show_mtp_modal: false,
            modalClass: [],

            route_detals: [],

            mtp_detals: [],
            id: 0,

            route_tools: new_route_tools(),
        };
    },
    mounted() {
        // this.get_outdoor_routes();
        // this.get_spot_rocks_images();
    },
    methods: {
        set_sector_view,
        spot_gallery_index(spot_image) {
            if (!spot_image || !spot_image.image) return 0;
            const src = '/public/images/spot_rocks_img/' + spot_image.image;
            const idx = this.spot_gallery.findIndex(item => item.src === src);
            return idx >= 0 ? idx : 0;
        },

        // Same has_original branching every other drawing viewer uses — the
        // clean origin_img backup once a drawing exists, so strokes aren't
        // rendered on top of a composite that already has them baked in.
        spotImageSrc(spot_image) {
            const v = spot_image.updated_at ? '?v=' + encodeURIComponent(spot_image.updated_at) : '';
            return (spot_image.has_original
                ? '/public/images/spot_rocks_img/origin_img/' + spot_image.image
                : '/public/images/spot_rocks_img/' + spot_image.image) + v;
        },

        update(id){
            this.id = id
            this.get_outdoor_routes();
            this.get_spot_rocks_images();
            this.get_my_ticks();
        },

        // logged-in climbers see ✓ on routes they've logged + area progress
        get_my_ticks() {
            if (!localStorage.getItem('auth_token')) return;
            axios
                .get('/set_route_ascent/get_my_route_ascents')
                .then((response) => {
                    const ticked = {};
                    (response.data || []).forEach(a => {
                        const prev = ticked[a.route_id];
                        if (!prev || STYLE_ORDER.indexOf(a.ascent_style) < STYLE_ORDER.indexOf(prev)) ticked[a.route_id] = a.ascent_style;
                    });
                    this.route_tools.ticked = ticked;
                    this.route_tools.logged_in = true;
                })
                .catch(() => {});
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
                .get("/get_sector/get_spot_rocks_images/" + this.id)
                .then((response) => {
                    this.spot_images = response.data;
                })
                .catch((error) => {});
        },
        
        get_outdoor_routes() {
            this.climbing_area = []
            axios
                .get("/get_sector/get_sector_and_routes/" + this.id)
                .then((response) => {
                    this.climbing_area = response.data;
                    // counts for the page (mobile scene banner)
                    const sectors = flat_sectors(response.data);
                    this.$emit('loaded', { sectors: sectors.length, routes: flat_routes(response.data).length });
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
.cg-sector-view-bar {
    display: flex;
    margin: 0 0 1rem;
}
.cg-sector-view { clear: both; }
.cg-sector-view-bar :deep(.cg-view-switch) { flex: 0 1 auto; max-width: 100%; overflow-x: auto; }
/* phones: icons only (the label + 4 words don't fit) */
@media (max-width: 575.98px) {
    .cg-sector-view-bar :deep(.cg-view-switch__label),
    .cg-sector-view-bar :deep(.cg-view-switch__btn span) { display: none; }
    .cg-sector-view-bar :deep(.cg-view-switch) { width: 100%; }
    .cg-sector-view-bar :deep(.cg-view-switch__btn) { flex: 1; justify-content: center; padding: .45rem .5rem; font-size: .95rem; }
}

#sectors {
    padding: 1rem;
    background-color: var(--cg-line);
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
    color: var(--cg-stone);
    margin-bottom: 1rem;
}

#sectors p {
    line-height: 1.6;
    color: var(--cg-ink-2);
}

@media (max-width: 576px) {
    #sectors {
        padding: 0.6rem;
    }
}
</style>
