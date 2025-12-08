<template>
    <div :class='"col-sm-3 col-md-3 col-xs-offset-1 display-biger-then-768px right_fixed_menu "+[right_navbar_class]'>

        <h3 class="navbar_title display-biger-then-768px">{{ $t('guide.article_right_nabar.menu_title') }}</h3>

        <nav class="fading-side-menu display-biger-then-768px ">
            <ul class="list-unstyled">

                <li>
                    <a @click.prevent="scrollToSection('description')">
                        <span class="text-primary cursor_pointer">{{ $t('guide.article_right_nabar.description') }}</span>
                    </a>
                </li>

                <li v-if="this.$route.name == 'outdoor'">
                    <a @click.prevent="scrollToSection('routes')">
                        <span class="text-primary cursor_pointer">{{ $t('guide.article_right_nabar.sectors') }}</span>
                    </a>
                    <ul v-if="sectors.length > 0" class="submenu">
                        <li v-for="sector in sectors" :key="sector.sector.id">
                            <a @click.prevent="scrollToSection('sector-' + sector.sector.id)">
                                <span class="text-primary cursor_pointer">{{ sector.sector.name }}</span>
                            </a>
                        </li>
                    </ul>
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

        <h3 class="navbar_title display-biger-then-768px" v-if="local_businesses.length != 0">{{ $t('guide.article_right_nabar.recomended_services') }}</h3>

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
        </div>

    </div>
</template>

<script>
    export default {
        name: "article-right-navigation-menu",
        props: [
            'article_id'
        ],
        data(){
            return{
                right_navbar_class: '',
                local_businesses: [],
                sectors: [],
                margin_bottom_position: 0,
                document_body_offsetHeight: document.body.offsetHeight,
                window_scrollY: window.scrollY,
            }
        },
        mounted() {
            this.get_local_bisnes_for_article()
            this.get_sectors_for_article()
            this.handleScroll()
        },
        watch: {
            '$route' (to, from) {
                this.get_local_bisnes_for_article()
                this.get_sectors_for_article()
            },
            'article_id' (newVal, oldVal) {
                if (newVal !== oldVal) {
                    this.get_sectors_for_article()
                }
            }
        },
        methods: {
            scrollToSection(sectionId) {
                const targetElement = document.getElementById(sectionId);
                if (targetElement) {
                    const yOffset = -80; // adjust this value to control the offset from the top
                    const y = targetElement.getBoundingClientRect().top + window.pageYOffset + yOffset;
                    window.scrollTo({ top: y, behavior: 'smooth' });
                }
            },

            get_local_bisnes_for_article(){
                axios
                .get('/get_bisnes/get_local_bisnes_for_article/' + this.$route.params.url_title + '/' + localStorage.getItem('lang'))
                .then(response => {
                    this.local_businesses = response.data
                })
                .catch(error =>{
                })
            },

            get_sectors_for_article(){
                if (this.article_id) {
                    axios
                    .get('/get_sector/get_sector_and_routes/' + this.article_id)
                    .then(response => {
                        this.sectors = response.data
                    })
                    .catch(error =>{
                    })
                }
            },

            handleScroll (event) {
                this.margin_bottom_position = document.body.offsetHeight - window.scrollY
                
                const menu = document.querySelector('.right_fixed_menu');
                const footer = document.querySelector('.footer');
                const footer__graphic = document.querySelector('.footer__graphic');

                const menuBottom = menu.getBoundingClientRect().bottom;
                const footerTop = footer.getBoundingClientRect().top;
                const footer__graphic_top = footer__graphic.offsetHeight;

                if(this.margin_bottom_position > window.scrollY){
                    this.right_navbar_class = ''
                }
                else if(footerTop - footer__graphic_top - 50 < menuBottom){                    
                    this.right_navbar_class = 'right_navigarion_menu_fixed_on_scrine_bottom'
                }
                else{
                    this.right_navbar_class = 'right_navigarion_menu_fixed_on_scrine'
                }
            }
        },
        created () {
            window.addEventListener('scroll', this.handleScroll);
        },
        destroyed () {
            window.removeEventListener('scroll', this.handleScroll);
        },
    };
</script> 

<style scoped>
    .right_navigarion_menu_fixed_on_scrine{
        position: fixed;
        right: 0;
        margin-top: -13%;
    }
    .right_navigarion_menu_fixed_on_scrine_bottom{
        position: absolute;
        right: 0;
        bottom: 570px;
    }
    .caption h3{
        margin: 0;
    }
    .navbar_title{
        font-size: 20px;
        text-align: left;
        margin: 0 0 8% 0;
    }
    .local_bisnes{
        margin-top: 8%;
    }
    .fading-side-menu{
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
