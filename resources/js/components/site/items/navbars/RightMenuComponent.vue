<template>
    <div :class='"col-xs-3 col-xs-offset-1 right_fixed_menu display-none-720px "+[right_navbar_class]'>

        <h3 class="navbar_title">{{ $t('guide.article_right_nabar.menu_title') }}</h3>

        <nav class="fading-side-menu">
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

        <div class="row local_bisnes" v-if="this.$globalSiteData.ad">
            <div class="col-sm-12 col-md-12">
                <span v-html="this.$globalSiteData.ad"></span>
            </div>
        </div>

        <div class="row local_bisnes" v-if="local_bisnes.image && local_bisnes.local_data && local_bisnes.global_data">
            <div class="col-sm-10 col-md-10">
                <div class="thumbnail">
                    <router-link style="font-size: 1.5em;" :to="'../local_bisnes/' + local_bisnes.global_data.url_title" exact>
                        <img :src="'../../../images/suport_local_bisnes_img/' + local_bisnes.image.image" :alt="local_bisnes.local_data.title">
                    </router-link>
                    <div class="caption">
                        <router-link style="font-size: 1.5em;" :to="'../local_bisnes/' + local_bisnes.global_data.url_title" exact>
                            <h3>{{ local_bisnes.local_data.title }}</h3>
                        </router-link>
                    </div>
                    <div class="caption">
                        <span v-html="local_bisnes.local_data.short_description"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        // name: "Article Right Navigation Menu",
        data(){
            return{
                right_navbar_class: '',
                local_bisnes: {
                    image: '',
                    global_data: '',
                    local_data: ''
                },
                margin_bottom_position: 0,
                document_body_offsetHeight: document.body.offsetHeight,
                window_scrollY: window.scrollY,
            }
        },
        mounted() {
            this.get_local_bisnes_for_article()
            this.handleScroll()
        },
        watch: {
            '$route' (to, from) {
                this.get_local_bisnes_for_article()
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
                .get('/bisnes/get_local_bisnes_for_article/' + this.$route.params.url_title + '/' + localStorage.getItem('lang'))
                .then(response => {
                    this.local_bisnes.image = response.data.image
                    this.local_bisnes.local_data = response.data.local_data
                    this.local_bisnes.global_data = response.data.global_data
                })
                .catch(error =>{
                })
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
        margin-top: -10%;
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
        text-align: left;
        margin: 0 0 8% 0;
    }
    .local_bisnes{
        margin-top: 8%;
    }
</style>
