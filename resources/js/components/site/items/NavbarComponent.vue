<template>
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row navbar-fixed-top top_menu">
            
            <!-- <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12"> -->
                <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 navbar_title">
                    <div class="site_title">
                        <router-link style="font-size: 1.5em;" :to="{name: 'index'}" exact class="site_title">
                            Climbing Guid
                        </router-link>
                    </div>
                </div>

                <nav class="navbar navbar-default float-top" role="navigation">

                    <div class="container-fluid">
                        <!-- <button type="button" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" class="navbar-toggle collapsed">
                            <i class="fa fa-angle-down"></i> // this button whil work for fresh bootstrap 3.0.0
                        </button> -->

                        <button type="button" class="navbar-toggle collapsed" @click="open_navbar()">
                            <i class="fa fa-angle-down"></i>
                        </button>

                        <div class="dropdown">
                            <button type="button" class="navbar-toggle margin_right"  data-toggle="dropdown" id="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-heartbeat" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu shadows mobail_dropdown" role="menu" aria-labelledby="dropdown">
                                <li v-if="activ_grade == 'UIAA'" @click=" activ_grade = 'yds', grade_charts('yds')">
                                    <a class="mobail_dropdawn_text">YDS</a>
                                </li>
                                <li v-if="activ_grade == 'yds'" @click="activ_grade = 'UIAA', grade_charts('UIAA')">
                                    <a class="mobail_dropdawn_text">UIAA</a>
                                </li>
                            </ul>
                        </div>
                        
                        <div class="dropdown">
                            <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown" id="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-language" aria-hidden="true"></i>
                            </button>

                            <ul class="dropdown-menu shadows mobail_dropdown" aria-labelledby="dropdown">
                                <li v-if="activ_lang == 'ka' || activ_lang == 'ru'"><a class="mobail_flag" @click="activ_lang = 'en', localization('en')"><countryFlag country='usa' size='big'/></a></li>
                                <li v-if="activ_lang == 'en' || activ_lang == 'ru'"><a class="mobail_flag" @click="activ_lang = 'ka', localization('ka')"><countryFlag country='geo' size='big'/></a></li>
                                <li v-if="activ_lang == 'ka' || activ_lang == 'en'"><a class="mobail_flag" @click="activ_lang = 'ru', localization('ru')"><countryFlag country='rus' size='big'/></a></li>
                            </ul>
                        </div>
                        
                        <div class="dropdown">
                            <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown" id="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                            
                            <ul class="dropdown-menu shadows mobail_dropdown" aria-labelledby="dropdown">
                                <form action="/search" id="searchForm" method="POST" role="search" @submit.prevent="search" class="monail_navbar_form">
                                    <input class="form-control" name="request" v-model="search_query" placeholder="Search climbing spot here." autocomplete="off" autofocus="autofocus" type="text" lang="en">
                                </form>
                                <button type="submit" form="searchForm" class="monail_navbar_form_button"> 
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </ul>
                        </div>
                        
                        <button type="button" class="navbar-toggle margin_right" @click="goTo()">
                            <i class="fa fa-user" ></i>
                        </button>
                    </div>
                    
                    <!-- <div class="collapse navbar-collapse mobile_nav_menu"  id="bs-example-navbar-collapse-1"> // this class whil work for fresh bootstrap 3.0.0 -->
                    <div :class='[navbar_class]'>
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
                            <li><router-link :to="{name: 'events', params: {lang: this.$i18n.locale}}" exact>          <span> {{ $t('menu events') }}              </span> </router-link></li>
                            <li><router-link :to="{name: 'others', params: {lang: this.$i18n.locale}}" exact>          <span> {{ $t('menu other') }}               </span> </router-link></li>
                            <li><router-link :to="{name: 'about_us', params: {lang: this.$i18n.locale}}" exact>        <span> {{ $t('menu about us') }}            </span> </router-link></li>
                            
                            <li class="dropdown">
                                <a class="margin_right admin_menu_for_desctop" data-toggle="dropdown">
                                    <i class="fa fa-search"  aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu shadows menu_margin_left_850" role="menu" style="width: 1000%;">
                                    <li>
                                        <form action="/search" id="searchForm" method="POST" role="search" @submit.prevent="search" class="monail_navbar_form">
                                            <input class="form-control" name="request" v-model="search_query" placeholder="Search climbing spot here." autocomplete="off" autofocus="autofocus" type="text" lang="en">
                                        </form>
                                        <button type="submit" form="searchForm" class="monail_navbar_form_button"> 
                                            <span class="glyphicon glyphicon-search"></span>
                                        </button>
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
            <!-- </div> -->
        </div>
    </div>  
</template>

<script>
    import countryFlag from 'vue-country-flag' // https://www.npmjs.com/package/vue-country-flag
    import localeSwitcher from '../../global_components/LocaleChangeComponent.vue'

    export default {
        data: function () {
            return {
                search_query: null,
                show: false,
                navbar_general_class: 'collapse navbar-collapse mobile_nav_menu',
                navbar_class: 'collapse navbar-collapse mobile_nav_menu',
                navbar_open_class: 'collapse navbar-collapse mobile_nav_menu show',

                get activ_grade() {
                    return localStorage.getItem('grade') || 'yds';
                },
                set activ_grade(value) {
                    localStorage.setItem('grade', value);
                },

                
                get activ_lang() {
                    return localStorage.getItem('lang') || 'en';
                },
                set activ_lang(value) {
                    localStorage.setItem('lang', value);
                },
            };
        },
        components: {
            localeSwitcher,
            countryFlag,
        },
        mounted() {
            this.navbar_class = this.navbar_general_class
        },
        watch: {
        //     $route(to) {
        //         if(localStorage.getItem('lang') != 'en'){
        //             this.i18n.locale = localStorage.getItem('lang')
        //         } else{this.i18n.locale = ''}
        //     }
        //     '$route' (to, from) {
        //     }
            '$route' (to, from) {
                this.navbar_class = this.navbar_general_class
            }
        },

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
            },
            goTo(page = ''){
                window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page) ;
            },
            open_navbar(){
                if(this.navbar_class == 'collapse navbar-collapse mobile_nav_menu'){
                    this.navbar_class = this.navbar_open_class
                }
                else if(this.navbar_class == 'collapse navbar-collapse mobile_nav_menu show'){
                    this.navbar_class = this.navbar_general_class
                }
                else{
                    this.navbar_class = this.navbar_general_class
                }
            }
        }
    }
</script>