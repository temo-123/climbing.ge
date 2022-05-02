<template>
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row navbar-fixed-top top_menu">
            
            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 display-none-720px" style="z-index: 1001;">
                    <div class="site_title">
                        <router-link style="font-size: 1.5em;" :to="{name: 'index'}" exact class="site_title">
                            <img :src="'images/site_img/site_logo/header logo.png'" alt="climbing.ge" style="height: 6em;">
                        </router-link>
                        <!-- <router-link style="font-size: 1.5em;" :to="{name: 'index'}" exact class="site_title">{{ $t('site name')}}</router-link> -->
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

                        <!-- <li class="dropdown">
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
                        </li> -->
                        
                        <button type="button" class="navbar-toggle margin_right">
                            <a style="margin-top: -5%; color: #333333;" href="3">
                                <i class="fa fa-user" ></i>
                            </a>
                        </button>
                        
                        <ul class="dropdown-menu shadows" role="menu" >
                            <li :if="!lang == us"><a  @click="localization('us')"><country-flag country='usa' size='big'/></a></li>
                            <li :if="!lang == ka"><a  @click="localization('ka')"><country-flag country='geo' size='big'/></a></li>
                            <li :if="!lang == ru"><a  @click="localization('ru')"><country-flag country='rus' size='big'/></a></li>
                        </ul>


                        <button type="button" class="navbar-toggle margin_right">
                            <a style="margin-top: -5%; color: #333333;" href="3">
                                <i class="fa fa-heartbeat" aria-hidden="true"></i>
                            </a>
                        </button>
                        
                        <ul class="dropdown-menu shadows" role="menu" >
                            <!-- <li :if="!lang == us"><a  @click="localization('us')"><country-flag country='usa' size='big'/></a></li>
                            <li :if="!lang == ka"><a  @click="localization('ka')"><country-flag country='geo' size='big'/></a></li>
                            <li :if="!lang == ru"><a  @click="localization('ru')"><country-flag country='rus' size='big'/></a></li> -->
                            <li>YDS</li>
                            <li>YDS 2</li>
                        </ul>
                    </div>

                    <div class="collapse navbar-collapse mobile_nav_menu" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav pull-right top_menu_buttons">
                            <li class="dropdown">
                                <a href="#" data-toggle="dropdown"> 
                                    {{ $t('menu climbing') }}
                                </a>

                                <ul class="dropdown-menu " role="menu" style="background: #1994b1; position: absolute;">
                                    <li><router-link style="font-size: 1.5em;" :to="{name: 'indoors'}" exact>  <span> {{ $t('menu indoor climbing') }}  </span> </router-link></li>
                                    <li><router-link style="font-size: 1.5em;" :to="{name: 'outdoors'}" exact> <span> {{ $t('menu outdoor climbing') }} </span> </router-link></li>
                                </ul>
                            </li>


                            <li><router-link :to="{name: 'mountaineerings'}" exact> <span> {{ $t('menu mountaineering') }}      </span> </router-link></li>
                            <li><router-link :to="{name: 'ices'}" exact>            <span> {{ $t('menu ice climbing') }}        </span> </router-link></li>
                            <li><router-link :to="{name: 'others'}" exact>          <span> {{ $t('menu other') }}               </span> </router-link></li>
                            <li><router-link :to="{name: 'about_us'}" exact>        <span> {{ $t('menu about us') }}            </span> </router-link></li>
                            
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

                            <locale />

                            <li class="dropdown display-none-720px">
                                <a href="#" data-toggle="dropdown">
                                    <i class="fa fa-heartbeat" aria-hidden="true"></i>
                                </a>

                                <ul class="dropdown-menu shadows" role="menu">
                                    <li :if="!lang == us"><a style="width: 50%; margin-left: 25%;"  @click="grade_charts('yds')"><li>YDS (USA)</li></a></li>
                                    <li :if="!lang == ka"><a style="width: 50%; margin-left: 25%;"  @click="grade_charts('german')"><li>German</li></a></li>
                                    <li :if="!lang == ka"><a style="width: 50%; margin-left: 25%;"  @click="grade_charts('UIAA')"><li>UIAA</li></a></li>
                                    <li :if="!lang == ka"><a style="width: 50%; margin-left: 25%;"  @click="grade_charts('UK')"><li>UK</li></a></li>
                                </ul>
                            </li>

                            <li class="display-none-720px">
                                <a style="margin-top: -5%; font-size: 120%;" @click="goTo('/')">
                                    <i class="fa fa-user-circle"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>  
</template>

<script>
    import search from './SearchComponent'
    import locale from '../../global_components/LocaleChangeComponent.vue'
    // import CountryFlag from 'vue-country-flag'

    export default {
        data: function () {
            return {
                // locale_lang: localStorage.getItem('lang'),
                // lang: localStorage.getItem('lang') || 'en',
                // search_query: null,
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        components: {
            search,
            // CountryFlag,
            locale,
        },
        mounted() {
            // this.$i18n.locale = this.lang
        },
        methods: {
            search(){
                this.$router.push({name: 'search_articles', query: { 'query': this.search_query} });
            },
            // localization(locale){
            //     this.lang = locale
            //     localStorage.setItem('lang', this.lang)
            //     this.$i18n.locale = locale;
            // },

            grade_charts(chart){
                localStorage.setItem('grade_charts', this.chart)
            },
            goTo(page){
                window.location.href = this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page ;
            },
            
        }
    }
</script>
