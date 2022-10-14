<template>
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row navbar-fixed-top top_menu">
            <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3" style="z-index: 1001;">
                <div class="site_title">
                    <!-- <a :href="MIX_APP_SSH + MIX_SITE_URL" class="site_title">{{__ ('site name')}}</a> -->
                    <router-link style="font-size: 1.5em;" :to="{name: 'studia'}" exact class="site_title">
                        <!-- {{ $t('site name')}} -->
                        Climbing Films
                    </router-link>
                </div>
            </div>
            
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <nav class="navbar navbar-default float-top" role="navigation">
                    <div class="container-fluid">

                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                            <i class="fa fa-angle-down"></i>
                        </button>
                        
                        <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown">
                            <i class="fa fa-language" aria-hidden="true"></i>
                        </button>
                        
                        <button type="button" class="navbar-toggle margin_right">
                            <a style="margin-top: -5%; color: #333333;" href="3">
                                <i class="fa fa-user" ></i>
                            </a>
                        </button>
                        
                        <ul class="dropdown-menu shadows" role="menu" >
                            <li><a  @click="localization('us')"><img style="width: 50%; margin-left: 25%;" :src="'images/site_img/leng/en.png'" alt="us"></a></li>
                            <li><a  @click="localization('ka')"><img style="width: 50%; margin-left: 25%;" :src="'images/site_img/leng/ka.webp'" alt="ka"></a></li>
                            <li><a  @click="localization('ru')"><img style="width: 50%; margin-left: 25%;" :src="'images/site_img/leng/ru.png'" alt="ru"></a></li>
                        </ul>
                    </div>
                    <div class="collapse navbar-collapse mobile_nav_menu" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav pull-right top_menu_buttons">

                            <li class="dropdown display-none-720px serch_block">
                                <!-- <form class="navbar-form navbar-left" role="search"> -->
                                <form action="/search" method="POST" role="search" class="navbar-form"  @submit.prevent="search">
                                    <div class="form-group">
                                        <input type="text" class="form-control" placeholder="Search" v-model="search_query" >
                                    </div>
                                    <button type="submit" class="btn btn-primary" style='color:#FFFFFF;'>
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                    </button>
                                </form>
                            </li>
                
                            <li><router-link :to="{name: 'about_films',}" exact>        <span> {{ $t('menu about us') }}            </span> </router-link></li>

                            <localeSwitcher />
                            
                            <goTo :go_to_page="'/'" />
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>  
</template>

<script>
    // import search from './SearchComponent'
    import localeSwitcher from '../../global_components/LocaleChangeComponent.vue'

    export default {
        data: function () {
            return {
                search_query: '',
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        components: {
            localeSwitcher
        },
        mounted() {
        },
        methods: {
            search(){
                this.$router.push({name: 'search_films', query: { 'query': this.search_query} });
                this.search_query = ''
            },
            // localization(leng){
            //     window.location.href = leng;
            // },
            // goTo(page){
            //     window.location.href = this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page ;
            // },
            
        }
    }
</script>
<style scoped>
    .form-control {
        border-color: #aaa !important;
        height: 34px !important;
    }
    .serch_block{
        margin-top: 1em !important;
    }
</style>