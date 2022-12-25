<template>
    <div :class='"col-xs-3 col-xs-offset-1 blog-sidebar display-none-720px "+[right_navbar_class]'>
        <h3 class="navbar_title">{{ $t('article navigation menu') }}</h3>
        <nav class="navbar fading-side-menu">
            <ul class="list-unstyled">

                <li>
                    <a href="#description">
                        <span class="text-primary">{{ $t('nav description') }}</span>
                    </a>
                </li>

                <li>
                    <a href="#sectors">
                        <span class="text-primary">{{ $t('nav sectors') }}</span>
                    </a>
                </li>

                <li>
                    <a href="#gallery">
                        <span class="text-primary">{{ $t('nav gallery') }}</span>
                    </a>
                </li>
            
                <li>
                    <a href="#comments">
                        <span class="text-primary">{{ $t('nav comments') }}</span>
                    </a>
                </li>
            
                <li>
                    <a href="#other">
                        <span class="text-primary">{{ $t('nav similar articles') }}</span>
                    </a>
                </li>
            
            </ul>
        </nav>

        <div class="row" v-if="local_bisnes.image && local_bisnes.local_data && local_bisnes.global_data">
            <div class="col-sm-10 col-md-10">
                <div class="thumbnail">
                    <router-link style="font-size: 1.5em;" :to="'../local_bisnes/' + local_bisnes.global_data.url_title" exact>
                        <img :src="'../../../images/suport_local_bisnes_img/' + local_bisnes.image.image" alt="...">
                    </router-link>
                    <div class="caption">
                        <router-link style="font-size: 1.5em;" :to="'../local_bisnes/' + local_bisnes.global_data.url_title" exact>
                            <h3>{{ local_bisnes.local_data.title }}</h3>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        name: "Article Right Navigation Menu",
        data(){
            return{
                right_navbar_class: '',
                local_bisnes: {
                    image: '',
                    global_data: '',
                    local_data: ''
                },
            }
        },
        mounted() {
            this.margin_bottom_position = document.body.offsetHeight - window.scrollY

            if(document.body.offsetHeight > 2000){
                if(this.margin_bottom_position <= 1900){
                    this.right_navbar_class = ''
                }
                else{
                    this.right_navbar_class = 'right_navigarion_menu_fixed_on_scrine'
                }
            }
            else{
                this.right_navbar_class = ''
            }

            this.get_local_bisnes_for_article()
        },
        watch: {
            '$route' (to, from) {
                this.get_local_bisnes_for_article()
            }
        },
        methods: {
            get_local_bisnes_for_article(){
                axios
                .get('../api/bisnes/get_local_bisnes_for_article/' + this.$route.params.url_title + '/' + localStorage.getItem('lang'), {
                })
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
                
                if(document.body.offsetHeight > 2000){
                    if(this.margin_bottom_position <= 1900){
                        this.right_navbar_class = ''
                    }
                    else{
                        this.right_navbar_class = 'right_navigarion_menu_fixed_on_scrine'
                    }
                }
                else{
                    this.right_navbar_class = ''
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
    }
    .right_navigarion_menu_fixed_on_top{
        /* position: fixed; */
        right: 0;
    }
    .right_navigarion_menu_fixed_on_bottom{
        /* position: fixed; */
        right: 0;
    }
    .caption h3{
        margin: 0;
    }
    .navbar_title{
        text-align: left;
    }
</style>
