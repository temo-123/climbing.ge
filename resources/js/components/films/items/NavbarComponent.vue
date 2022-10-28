<template>
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row navbar-fixed-top top_menu">
            
            <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 navbar_title">
                <div class="site_title">
                    <router-link style="font-size: 1.5em;" :to="{name: 'studia'}" exact class="site_title">
                        Climbing Films
                    </router-link>
                </div>
            </div>
            
                <nav class="navbar navbar-default float-top" role="navigation">
                    <div class="container-fluid">

                        <button type="button" class="navbar-toggle collapsed" @click="open_navbar()">
                            <i class="fa fa-angle-down"></i>
                        </button>

                        <div class="dropdown">
                            <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown" id="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                            <ul class="dropdown-menu shadows mobail_dropdown" aria-labelledby="dropdown">
                                <form action="/search" id="searchForm" method="POST" role="search" @submit.prevent="search" class="monail_navbar_form">
                                    <input class="form-control" name="request" v-model="search_query" placeholder="Search films" autocomplete="off" autofocus="autofocus" type="text">
                                </form>
                                <button type="submit" form="searchForm" class="monail_navbar_form_button"> 
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
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
                        
                        <button type="button" class="navbar-toggle margin_right" @click="goTo()">
                            <i class="fa fa-user" ></i>
                        </button>
                    </div>

                    <!-- <div class="collapse navbar-collapse mobile_nav_menu" id="bs-example-navbar-collapse-1"> -->
                    <div :class='[navbar_class]'>
                        <ul class="nav navbar-nav pull-right top_menu_buttons">

                            <li class="display-none-720px serch_block" style="margin-top: 2.8%;">
                                <form action="/search" method="POST" role="search" class="navbar-form"  @submit.prevent="search">
                                    <div class="form-group">
                                        <input type="text" id="searchForm" class="form-control" placeholder="Search films" v-model="search_query" >
                                    </div>
                                    <button type="submit" form="searchForm"  class="btn btn-primary" :style='"color: #FFFFFF;"'>
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
</template>

<script>
    import countryFlag from 'vue-country-flag' // https://www.npmjs.com/package/vue-country-flag
    // import search from './SearchComponent'
    import localeSwitcher from '../../global_components/LocaleChangeComponent.vue'

    export default {
        data: function () {
            return {
                search_query: '',
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,

                navbar_general_class: 'collapse navbar-collapse mobile_nav_menu',
                navbar_class: 'collapse navbar-collapse mobile_nav_menu',
                navbar_open_class: 'collapse navbar-collapse mobile_nav_menu show',
                
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
        },
        watch: {
            '$route' (to, from) {
                this.navbar_class = this.navbar_general_class
            }
        },
        methods: {
            search(){
                this.$router.push({name: 'search_films', query: { 'query': this.search_query} });
                this.search_query = ''
            },
            // localization(leng){
            //     window.location.href = leng;
            // },
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