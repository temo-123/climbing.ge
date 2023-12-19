<template>
    <nav class="navbar navbar-inverse navbar-fixed-top bg-dark">
        <div class="container-fluid">

            <!-- BRAND -->
            <div class="navbar-header">
                <!-- <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#alignment-example" aria-expanded="false" @click="open_navbar()"> -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" aria-expanded="false" @click="open_navbar()">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <span>
                    <router-link style="font-size: 1.5em;" :to="{name: 'index'}" exact class="site_title">{{ $t('shop.name') }}</router-link>
                </span>
                <!-- <a class="navbar-brand" href="#">Your Brand</a> -->
            </div>

            <!-- COLLAPSIBLE NAVBAR --> 
            <!-- <div class="collapse navbar-collapse" id="alignment-example"> -->
            <div :class='[navbar_class]' id="alignment-example">

                <!-- Links -->
                <ul class="nav navbar-nav navbar-right">
                    
                    <li><router-link :to="{name: 'catalog'}" exact> <span> {{ $t('shop.menu.products') }}  </span> </router-link></li>
                    <li><router-link :to="{name: 'wall_price_colculator'}" exact> <span> {{ $t('shop.menu.climbing wall') }}  </span> </router-link></li>
                    <li><router-link :to="{name: 'services'}" exact> <span> {{ $t('shop.menu.services') }} </span> </router-link></li>
                    <li><router-link :to="{name: 'tours'}" exact> <span> {{ $t('shop.menu.tours') }} </span> </router-link></li>
                    <li><router-link :to="{name: 'about_store'}" exact> <span> {{ $t('shop.menu.about us') }} </span> </router-link></li>

                    <li class="dropdown">
                        <a class="margin_right" data-toggle="dropdown">
                            <span>
                                <i class="fa fa-search"  aria-hidden="true"></i>
                            </span>
                        </a>
                        <ul class="dropdown-menu shadows " role="menu" style="width: 1000%;">
                            <li>
                                <form action="/search" id="searchForm" method="POST" role="search" @submit.prevent="search" class="monail_navbar_form">
                                    <input class="form-control" name="request" v-model="search_query" placeholder="Search product." autocomplete="off" autofocus="autofocus" type="text" lang="en">
                                </form>
                                <button type="submit" form="searchForm" class="monail_navbar_form_button"> 
                                    <span class="glyphicon glyphicon-search"></span>
                                </button>
                            </li>
                        </ul>
                    </li>

                    <localeSwitcher />

                    <goTo :go_to_page="'/'" />
                </ul>

                <!-- Search -->
                <!-- <form class="navbar-form navbar-right" role="search">
                    <div class="form-group">
                    <input type="text" class="form-control">
                    </div>
                    <button type="submit" class="btn btn-default">Search</button>
                </form> -->

            </div>

        </div>
    </nav>
</template>

<script>
    import countryFlag from 'vue-country-flag' // https://www.npmjs.com/package/vue-country-flag
    // import search from './SearchComponent'
    import localeSwitcher from '../../../global_components/LocaleChangeComponent.vue'

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
                this.$router.push({name: 'search_products', query: { 'query': this.search_query} });
                this.search_query = ''
            },
            // localization(leng){
            //     window.location.href = leng;
            // },
            goTo(page = ''){
                window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page) ;
            },

            currency(){

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


<style>
.navbar span{
    color: #fff;
}


</style>