<template>
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row navbar-fixed-top top_menu">
            
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 display-none-720px" style="z-index: 1001;">
                    <div class="site_title">
                        <!-- <router-link style="font-size: 1.5em;" :to="{name: 'index', params: {lang: this.$i18n.locale}}" exact class="site_title">
                            <img :src="'../../../images/site_img/site_logo/header logo.png'" alt="climbing.ge" style="height: 6em;">
                        </router-link> -->
                        <!-- <router-link style="font-size: 1.5em;" :to="{name: 'index'}" exact class="site_title">{{ $t('site name')}}</router-link> -->
                        <router-link style="font-size: 1.5em;" :to="{name: 'index'}" exact class="site_title">Climbing Guid</router-link>
                    </div>
                </div>

                <nav class="navbar navbar-default float-top" role="navigation">

                    <div class="container-fluid">
                        <!-- <router-link style="font-size: 1.5em;" :to="{name: 'index'}" exact class="site_title">{{ $t('site name')}}</router-link> -->

                        <button type="button" class="navbar-toggle" data-toggle="collapse" role="menu" data-target="#bs-example-navbar-collapse-1">
                            <i class="fa fa-angle-down"></i>
                        </button>
                        
                        <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown">
                            <i class="fa fa-language" aria-hidden="true"></i>
                        </button>
                        
                        <button type="button" class="navbar-toggle margin_right">
                            <a class="margin_right admin_menu_for_desctop" data-toggle="dropdown">
                                <!-- <i class="fa fa-search"  aria-hidden="true"></i> -->
                                search
                            </a>
                        </button>

                        <button type="button" class="navbar-toggle margin_right">
                            <a style="margin-top: -5%; color: #333333;">
                                <i class="fa fa-user" ></i>
                            </a>
                        </button>
                        
                        <ul class="dropdown-menu shadows" role="menu" >
                            <li><a  @click="localization('us')">usa</a></li>
                            <li><a  @click="localization('ka')">geo</a></li>
                            <li><a  @click="localization('ru')">rus</a></li>
                        </ul>

                        <button type="button" class="navbar-toggle margin_right">
                            <a style="margin-top: -5%; color: #333333;">
                                <i class="fa fa-heartbeat" aria-hidden="true"></i>
                            </a>
                        </button>
                    </div>

                    <div class="collapse navbar-collapse mobile_nav_menu" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav pull-right top_menu_buttons">
                            <li class="dropdown">
                                <a data-toggle="dropdown"> 
                                    {{ $t('menu climbing') }}
                                </a>

                                <ul class="dropdown-menu " role="menu" style="background: #1994b1; position: absolute;">
                                    <li><router-link style="font-size: 1.5em;" :to="{name: 'indoors', params: {lang: this.$i18n.locale}}" exact>  <span> {{ $t('menu indoor climbing') }}  </span> </router-link></li>
                                    <li><router-link style="font-size: 1.5em;" :to="{name: 'outdoors', params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('menu outdoor climbing') }} </span> </router-link></li>
                                </ul>
                            </li>

                            <li><router-link :to="{name: 'mountaineerings', params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('menu mountaineering') }}      </span> </router-link></li>
                            <li><router-link :to="{name: 'ices', params: {lang: this.$i18n.locale}}" exact>            <span> {{ $t('menu ice climbing') }}        </span> </router-link></li>
                            <li><router-link :to="{name: 'others', params: {lang: this.$i18n.locale}}" exact>          <span> {{ $t('menu other') }}               </span> </router-link></li>
                            <li><router-link :to="{name: 'about_us', params: {lang: this.$i18n.locale}}" exact>        <span> {{ $t('menu about us') }}            </span> </router-link></li>
                            
                            <li class="dropdown">
                                <a class="margin_right admin_menu_for_desctop" data-toggle="dropdown">
                                    <i class="fa fa-search"  aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu shadows menu_margin_left_850" role="menu" style="width: 1000%;">
                                    <li>
                                        <form action="/search" method="POST" role="search" class="navbar-form"  @submit.prevent="search">
                                            <div class="form-group" style="display:inline;">
                                                <div class="input-group" style="display:table;">
                                                <span class="input-group"> 
                                                    <input class="form-control" name="request" v-model="search_query" placeholder="Search Here" autocomplete="off" autofocus="autofocus" type="text">
                                                    <button type="submit" class="input-group-addon"> 
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </span>
                                                </div>
                                            </div>
                                        </form>
                                    </li>
                                </ul>
                            </li>

                            <li class="dropdown display-none-720px">
                                <a data-toggle="dropdown">
                                    <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                </a>

                                <ul class="dropdown-menu shadows" role="menu">
                                    <li v-if="activ_grade == 'UIAA'">
                                        <a style="width: 50%; margin-left: 25%;"  @click=" activ_grade = 'yds', grade_charts('yds')">
                                            YDS
                                        </a>
                                    </li>
                                    <li v-if="activ_grade == 'yds'">
                                        <a style="width: 50%; margin-left: 25%;" @click="activ_grade = 'UIAA', grade_charts('UIAA')">
                                            UIAA
                                        </a>
                                    </li>
                                </ul>
                            </li>

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
    import search from './SearchComponent'
    import localeSwitcher from '../../global_components/LocaleChangeComponent.vue'

    export default {
        data: function () {
            return {
                search_query: null,
                // i18n.locale: '',

                // activ_grade: localStorage.getItem('grade')

                get activ_grade() {
                    return localStorage.getItem('grade') || 'yds';
                },
                set activ_grade(value) {
                    localStorage.setItem('grade', value);
                },
            };
        },
        components: {
            search,
            localeSwitcher,
        },
        mounted() {
            // if(localStorage.getItem('lang') != 'en'){
            //     this.i18n.locale = localStorage.getItem('lang')
            // } else{this.i18n.locale = ''}
                // console.log("🚀 ~ file: NavbarComponent.vue ~ line 180 ~ return_data ~ g")
        },
        // watch: {
        //     $route(to) {
        //         if(localStorage.getItem('lang') != 'en'){
        //             this.i18n.locale = localStorage.getItem('lang')
        //         } else{this.i18n.locale = ''}
        //     }
        // },

        // watch: {
        //     '$route' (to, from) {
        //     }
        // },
        methods: {
            search(){
                this.$router.push({name: 'search_articles', query: { 'query': this.search_query} });
                this.search_query = ''
            },

            grade_charts(grade){
                localStorage.setItem('grade', grade)

                this.return_data(grade)
                // this.$router.push(to)
            },
            return_data(g){
                this.$emit('grade', g)
            }
        }
    }
</script>
