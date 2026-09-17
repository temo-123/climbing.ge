<template>
    <div class="col-sm-3 col-md-3 col-xs-offset-1 display-biger-then-768px right_fixed_menu">

        <h3 class="navbar_title display-biger-then-768px">{{ $t('guide.article_right_nabar.menu_title') }}</h3>

        <!-- Quick at-a-glance counts, computed from the same sectors payload
             already fetched for the submenu below — no extra request. Was
             requested to fill out an otherwise sparse-looking menu with
             something genuinely useful rather than just decorative spacing. -->
        <div v-if="this.$route.name == 'outdoor' && quickStats.sectorCount > 0" class="quick-stats display-biger-then-768px">
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


                <li v-if="this.$route.name == 'outdoor'">
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

    </div>
</template>

<script>
    export default {
        name: "article-right-navigation-menu",
        props: ['article_id'],
        data(){
            return {
                sectors: [],
            }
        },
        computed: {
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
        },
        mounted() {
            this.get_sectors_for_article();
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
            get_sectors_for_article() {
                if (!this.article_id) { this.sectors = []; return; }
                axios.get('/get_sector/get_sector_and_routes/' + this.article_id)
                    .then(response => {
                        this.sectors = Array.isArray(response.data) ? response.data : [];
                    })
                    .catch(() => { this.sectors = []; });
            },
        },
    };
</script> 

<style scoped>
    .right_fixed_menu {
        position: sticky;
        top: 80px;
        align-self: flex-start;
        max-height: calc(100vh - 90px);
        overflow-y: auto;
        padding-left: 1.5rem;
    }
    .caption h3 {
        margin: 0;
    }
    .navbar_title {
        font-size: 20px;
        text-align: left;
        margin: 0 0 8% 0;
    }
    .quick-stats {
        display: flex;
        gap: 8px;
        margin-bottom: 6%;
        padding: 10px 4px;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
    }
    .quick-stats-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .quick-stats-num {
        font-size: 1.4em;
        font-weight: 700;
        line-height: 1.1;
        color: #333;
    }
    .quick-stats-label {
        font-size: 0.72em;
        color: #888;
        text-transform: uppercase;
        letter-spacing: 0.03em;
    }
    .local_bisnes {
        margin-top: 8%;
    }
    .fading-side-menu {
        margin-bottom: 4%;
    }
    .submenu {
        list-style: none;
        padding-left: 20px;
        margin: 0;
    }
    .submenu li {
        margin: 5px 0;
    }
    .submenu-group-title {
        display: block;
        font-weight: 600;
        color: #555;
        font-size: 0.92em;
        margin-top: 10px;
    }
    .submenu-nested {
        padding-left: 14px;
        margin-top: 2px;
    }
</style>
