<template>
    <div class="col-sm-3 col-md-3 col-xs-offset-1 display-biger-then-768px right_fixed_menu">

        <h3 class="navbar_title display-biger-then-768px">{{ $t('guide.article_right_nabar.menu_title') }}</h3>

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
                        <template v-for="sector in sectors">
                            <!-- Handle direct sector objects (no local images) -->
                            <li v-if="sector && sector.sector && sector.sector.id" :key="sector.sector.id">
                                <a @click.prevent="scrollToSection('sector-' + sector.sector.id)">
                                    <span class="text-primary cursor_pointer">{{ sector.sector.name || 'Unnamed Sector' }}</span>
                                </a>
                            </li>
                            
                            <!-- Handle sectors wrapped in local_images structure -->
                            <li v-else-if="sector && sector.sectors && sector.sectors.length > 0" v-for="nestedSector in sector.sectors" :key="nestedSector.sector.id">
                                <a v-if="nestedSector && nestedSector.sector && nestedSector.sector.id" @click.prevent="scrollToSection('sector-' + nestedSector.sector.id)">
                                    <span class="text-primary cursor_pointer">{{ nestedSector.sector.name || 'Unnamed Sector' }}</span>
                                </a>
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
</style>
