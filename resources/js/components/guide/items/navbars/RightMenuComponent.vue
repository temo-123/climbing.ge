<template>
    <div class="col-sm-3 col-md-3 offset-1 display-biger-then-768px right_fixed_menu" :class="{ 'cg-has-side-scene': sideScene, 'cg-has-mountain-top': sideScene === 'summit' }">

        <h3 class="navbar_title display-biger-then-768px">{{ $t('guide.article_right_nabar.menu_title') }}</h3>

        <!-- Quick at-a-glance counts, computed from the same sectors payload
             already fetched for the submenu below — no extra request. Was
             requested to fill out an otherwise sparse-looking menu with
             something genuinely useful rather than just decorative spacing. -->
        <div v-if="hasSectors && quickStats.sectorCount > 0" class="quick-stats display-biger-then-768px">
            <div class="quick-stats-item">
                <span class="quick-stats-num">{{ quickStats.sectorCount }}</span>
                <span class="quick-stats-label">{{ $t('guide.article_right_nabar.quick_stats_sectors') }}</span>
            </div>
            <div class="quick-stats-item">
                <span class="quick-stats-num">{{ quickStats.routeCount }}</span>
                <span class="quick-stats-label">{{ $t('guide.article_right_nabar.quick_stats_routes') }}</span>
            </div>
            <div class="quick-stats-item" v-if="quickStats.mtpCount > 0">
                <span class="quick-stats-num">{{ quickStats.mtpCount }}</span>
                <span class="quick-stats-label">{{ $t('guide.article_right_nabar.quick_stats_mtps') }}</span>
            </div>
        </div>

        <nav class="fading-side-menu display-biger-then-768px ">
            <ul class="list-unstyled">

                <li>
                    <a @click.prevent="scrollToSection('description')">
                        <span class="text-primary cursor_pointer">{{ $t('common.description') }}</span>
                    </a>
                </li>


                <!-- sectors: outdoor areas and ice climbing pages -->
                <li v-if="hasSectors">
                    <a @click.prevent="scrollToSection('routes')">
                        <span class="text-primary cursor_pointer">{{ $t('guide.article_right_nabar.sectors') }}</span>
                    </a>



                    <ul v-if="sectors && sectors.length > 0" class="submenu">
                        <template v-for="(sector, sectorIndex) in sectors" :key="sectorIndex">
                            <!-- Handle direct sector objects (no local images) -->
                            <li v-if="sector && sector.sector && sector.sector.id">
                                <a @click.prevent="scrollToSection('sector-' + sector.sector.id)">
                                    <span class="text-primary cursor_pointer">{{ sector.sector.name || 'Unnamed Sector' }}</span>
                                </a>
                            </li>

                            <!-- Sectors sharing one local-approach image — grouped under that
                                 image's own title, same grouping the "Local approach photo"
                                 page itself uses, instead of flattening them in among every
                                 other sector as if they were unrelated. Reported September
                                 2026: "need make grouped sectors like a sector local image
                                 grouped ... show name of sector local images title." -->
                            <li v-else-if="sector && sector.sectors && sector.sectors.length > 0" class="submenu-group">
                                <a v-if="groupImageId(sector)" @click.prevent="scrollToSection('local-image-' + groupImageId(sector))">
                                    <span class="text-primary cursor_pointer submenu-group-title">{{ groupTitle(sector) }}</span>
                                </a>
                                <span v-else class="submenu-group-title">{{ groupTitle(sector) }}</span>
                                <ul class="submenu submenu-nested">
                                    <li v-for="nestedSector in sector.sectors" :key="nestedSector.sector.id">
                                        <a v-if="nestedSector && nestedSector.sector && nestedSector.sector.id" @click.prevent="scrollToSection('sector-' + nestedSector.sector.id)">
                                            <span class="text-primary cursor_pointer">{{ nestedSector.sector.name || 'Unnamed Sector' }}</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </template>
                    </ul>
                    <div v-else-if="sectors && sectors.length === 0" class="text-muted" style="padding-left: 20px;">
                        <small>No sectors found</small>
                    </div>
                    <div v-else class="text-muted" style="padding-left: 20px;">
                        <small>Loading sectors...</small>
                    </div>
                </li>

                <!-- live camera (only when the article has one) -->
                <li v-if="hasCamera">
                    <a @click.prevent="scrollToSection('live-camera')">
                        <span class="text-primary cursor_pointer"><i class="fa fa-video-camera" aria-hidden="true"></i> {{ $t('guide.article_right_nabar.live_camera') }}</span>
                    </a>
                </li>

                <li>
                    <a @click.prevent="scrollToSection('gallery')">
                        <span class="text-primary cursor_pointer">{{ $t('guide.article_right_nabar.gallery') }}</span>
                    </a>
                </li>

                <li>
                    <a @click.prevent="scrollToSection('comments')">
                        <span class="text-primary cursor_pointer">{{ $t('guide.article_right_nabar.comments') }}</span>
                    </a>
                </li>

                <li>
                    <a @click.prevent="scrollToSection('other')">
                        <span class="text-primary">{{ $t('guide.article_right_nabar.similar_articles') }}</span>
                    </a>
                </li>

            </ul>
        </nav>

        <!-- <div class="row local_bisnes" v-if="this.$globalSiteData.data.ad">
            <div class="col-sm-12 col-md-10">
                <span v-html="this.$globalSiteData.data.ad"></span>
            </div>
        </div> -->

        <!-- <h3 class="navbar_title display-biger-then-768px" v-if="local_businesses.length != 0">{{ $t('guide.article_right_nabar.recomended_services') }}</h3>

        <div class="row local_bisnes display-biger-then-768px" v-if="local_businesses.length != 0">
            <div class="col-sm-10 col-md-10" v-for="bisnes in local_businesses" :kay="bisnes.global_data.id">
                <div class="thumbnail">
                    <router-link v-if="bisnes.image.length != 0" style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                        <img :src="'../../../images/suport_local_bisnes_img/' + bisnes.image" :alt="bisnes.local_data.title">
                    </router-link>
                    <router-link v-else style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                        <img :src="'/../public/images/site_img/image.png'" :alt="bisnes.local_data.title">
                    </router-link>
                    <div class="caption">
                        <router-link style="font-size: 1.5em;" :to="'../local_bisnes/' + bisnes.global_data.url_title" exact>
                            <h3>{{ bisnes.local_data.title }}</h3>
                        </router-link>
                    </div>
                    <div class="caption">
                        <span v-html="bisnes.local_data.short_description"></span>
                    </div>
                </div>
            </div>
        </div> -->

        <!-- optional extra block under the menu -->
        <slot name="below" />

        <!-- climbing scene on the side of this card (the card edge is the "wall") -->
        <img
            v-if="sideScene === 'bolter'"
            class="cg-card-bolter"
            src="/images/scenes/bolter-solo.svg"
            alt="" aria-hidden="true" loading="lazy" decoding="async"
        />
        <menu-side-scene
            v-else-if="sideScene && cardSize.h && motion.enabled"
            :variant="sideScene"
            :width="cardSize.w"
            :height="cardSize.h"
        />
    </div>
</template>

<script>
    import { defineAsyncComponent } from 'vue'
    import { motion } from '../../../../services/motion.js'
    // loaded only on pages that show a side scene
    const MenuSideScene = defineAsyncComponent(() => import('./MenuSideSceneComponent.vue'))

    export default {
        name: "article-right-navigation-menu",
        props: ['article_id'],
        components: { MenuSideScene },
        data(){
            return {
                sectors: [],
                // set once the sector request has finished (ok or failed): the
                // outdoor side scene depends on it, so it waits for the data
                sectorsLoaded: false,
                hasCamera: false,
                cardSize: { w: 0, h: 0 },
                // navbar animation switch: off = no side scene at all
                motion,
                wideScreen: window.innerWidth >= 1200,
            }
        },
        computed: {

            // pages that list climbing sectors in the menu (both use OutdoorComponent)
            hasSectors() { return ['outdoor', 'ice'].includes(this.$route.name) },
            // Flattens both entry shapes get_sector_and_routes returns
            // ({sector: {...}} standalone, or {local_images, sectors: [...]}
            // grouped) into one list of real sector objects, so counting
            // logic doesn't have to special-case the grouping twice.
            flatSectors() {
                const flat = [];
                (this.sectors || []).forEach(entry => {
                    if (entry && entry.sector) flat.push(entry);
                    else if (entry && entry.sectors) entry.sectors.forEach(s => flat.push(s));
                });
                return flat;
            },
            quickStats() {
                let routeCount = 0, mtpCount = 0;
                this.flatSectors.forEach(s => {
                    routeCount += (s.sport_routes ? s.sport_routes.length : 0)
                                + (s.boulder_routes ? s.boulder_routes.length : 0);
                    mtpCount   += (s.mtps ? s.mtps.length : 0);
                });
                return { sectorCount: this.flatSectors.length, routeCount, mtpCount };
            },
            // which climbing scene hangs on the side of this card (wide screens
            // only — it needs the gap between the article and the card):
            // outdoor with several sectors → lead climbing pair, one sector →
            // bolter; ice → lead ice climbing; indoor → gym lead; tech tips →
            // practising a lead fall; mountaineering → rope team on the summit
            sideScene() {
                if (!this.wideScreen) return null;
                switch (this.$route.name) {
                    case 'special_article': return 'bolter';
                    // wait for the sectors (they decide the menu height and the scene)
                    case 'outdoor':
                        if (!this.sectorsLoaded) return null;
                        return this.quickStats.sectorCount > 1 ? 'lead' : 'bolter';
                    case 'ice': return 'ice';
                    case 'tech_tip': return 'safety';
                    case 'other': return 'slackline';
                    case 'indoor': return 'indoor';
                    case 'mountaineering': return 'summit';
                    default: return null;
                }
            },
        },
        mounted() {
            this.get_sectors_for_article();
            // the side scene follows the card's size (sectors load, window resizes)
            if (window.ResizeObserver) {
                this.sizeObserver = new ResizeObserver(() => {
                    const r = this.$el.getBoundingClientRect();
                    this.cardSize = { w: Math.round(r.width), h: Math.round(r.height) };
                });
                this.sizeObserver.observe(this.$el);
            }
            this.onResize = () => { this.wideScreen = window.innerWidth >= 1200; };
            window.addEventListener('resize', this.onResize, { passive: true });
        },
        beforeUnmount() {
            if (this.sizeObserver) this.sizeObserver.disconnect();
            window.removeEventListener('resize', this.onResize);
        },
        watch: {
            '$route'() {
                this.get_sectors_for_article();
            },
            'article_id'(newVal, oldVal) {
                if (newVal !== oldVal) {
                    this.get_sectors_for_article();
                }
            }
        },
        methods: {
            // The backend groups sectors sharing one local-approach photo as
            // { local_images: [image], sectors: [...] } — the image's own
            // admin-set title names the group; fall back to a generic label
            // only for the rare case it was never filled in.
            groupTitle(group) {
                const image = group.local_images && group.local_images[0];
                return (image && image.title) ? image.title : this.$t('guide.article_right_nabar.local_image_group_fallback');
            },
            // SectorAndRoutesTabComponent.vue wraps each group in
            // id="local-image-{id}" so this title can jump straight to the
            // photo itself, the same way each sector name jumps to its own
            // section.
            groupImageId(group) {
                const image = group.local_images && group.local_images[0];
                return image ? image.id : null;
            },
            scrollToSection(sectionId) {
                const target = document.getElementById(sectionId);
                if (target) {
                    const y = target.getBoundingClientRect().top + window.pageYOffset - 80;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            },
            // does this article have a live camera? (shown as a menu item)
            check_camera() {
                this.hasCamera = false;
                if (!this.article_id || !this.hasSectors) return;
                axios.get('/get_live_camera/get_camera_for_article/' + this.article_id)
                    .then(r => { this.hasCamera = !!(r.data && r.data.id); })
                    .catch(() => {});
            },
            get_sectors_for_article() {
                this.check_camera();
                this.sectorsLoaded = false;
                if (!this.article_id) { this.sectors = []; return; }
                axios.get('/get_sector/get_sector_and_routes/' + this.article_id)
                    .then(response => {
                        this.sectors = Array.isArray(response.data) ? response.data : [];
                    })
                    .catch(() => { this.sectors = []; })
                    // the menu has its final height now → the side scene can start
                    .finally(() => { this.$nextTick(() => { this.sectorsLoaded = true; }); });
            },
        },
    };
</script> 

<style scoped>
    /* Article "on this page" card — sticky beside the article */
    .right_fixed_menu {
        position: sticky;
        top: calc(var(--cg-nav-h) + 16px);
        align-self: flex-start;
        max-height: calc(100vh - var(--cg-nav-h) - 32px);
        overflow-y: auto;
        padding: 1.25rem 1.25rem 1rem;
        background: #fff;
        border: 1px solid var(--cg-line);
        border-radius: var(--cg-r-lg);
        box-shadow: var(--cg-shadow-sm);
    }
    .caption h3 {
        margin: 0;
    }
    .navbar_title {
        margin: 0 0 1rem;
        font-size: 1.15rem;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: var(--cg-muted);
    }
    .quick-stats {
        display: flex;
        gap: 8px;
        margin-bottom: 1rem;
    }
    .quick-stats-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: .6rem .25rem;
        border-radius: var(--cg-r);
        background: var(--cg-accent-soft);
        text-align: center;
    }
    .quick-stats-num {
        font-family: var(--cg-font-display);
        font-size: 1.7rem;
        font-weight: 700;
        line-height: 1;
        color: var(--cg-accent-dark);
    }
    .quick-stats-label {
        margin-top: .2rem;
        font-size: .68rem;
        font-weight: 800;
        color: var(--cg-stone);
        text-transform: uppercase;
        letter-spacing: .06em;
    }
    .local_bisnes {
        margin-top: 1.5rem;
    }
    .fading-side-menu {
        margin-bottom: .5rem;
    }
    .fading-side-menu > ul > li > a {
        display: block;
        padding: .45rem .6rem;
        border-radius: .55rem;
        font-weight: 700;
    }
    .fading-side-menu > ul > li > a:hover {
        background: var(--cg-chalk);
    }
    .fading-side-menu .text-primary {
        color: var(--cg-ink-2) !important;
    }
    .fading-side-menu a:hover .text-primary {
        color: var(--cg-accent) !important;
    }
    .submenu {
        list-style: none;
        margin: .1rem 0 .4rem .9rem;
        padding-left: .75rem;
        border-left: 2px dashed var(--cg-line);
    }
    .submenu li {
        margin: 0;
    }
    .submenu li a {
        display: block;
        padding: .25rem .5rem;
        font-size: .9rem;
    }
    .submenu li a .text-primary {
        font-weight: 600;
        color: var(--cg-stone) !important;
    }
    .submenu-group-title {
        display: block;
        margin-top: .5rem;
        font-weight: 800;
        font-size: .8rem;
        letter-spacing: .04em;
        text-transform: uppercase;
        color: var(--cg-muted) !important;
    }
    .submenu-nested {
        margin-left: .4rem;
    }
</style>
