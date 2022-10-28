<template>
        <div class="header navbar-expand-lg navbar-right bg-light">
            <div class="row navbar-fixed-top top_menu">

                <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 navbar_title">
                    <div class="site_title">
                        <router-link style="font-size: 1.5em;" :to="{name: 'catalog'}" exact class="site_title">Climbing Shop</router-link>
                    </div>
                </div>
                <!-- <div class="col-xs-12 col-sm-10 col-md-9 col-lg-9"> -->
                    <nav class="navbar navbar-default float-top" role="navigation">
                        <div class="container-fluid">
                            
                            <!-- <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <i class="fa fa-angle-down"></i>
                            </button> -->
                            
                            <button type="button" class="navbar-toggle collapsed" @click="open_navbar()">
                                <i class="fa fa-angle-down"></i>
                            </button>

                            <button type="button" class="navbar-toggle margin_right" @click="goTo('/cart')">
                                <i class="fa fa-shopping-cart"></i>
                            </button>  

                            <button type="button" class="navbar-toggle margin_right" @click="goTo('/favorite_products')">
                                <i class="fa fa-heart" aria-hidden="true"></i>  
                            </button>   
                            
                            <div class="dropdown">
                                <!-- <button type="button" class="navbar-toggle margin_right">
                                    <i class="fa fa-usd" aria-hidden="true"></i>
                                </button>   -->
                                <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown" id="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-usd" aria-hidden="true"></i>
                                </button>

                                <ul class="dropdown-menu shadows mobail_dropdown" role="menu" aria-labelledby="dropdown">
                                    <li>
                                        <a class="mobail_dropdawn_text" @click="currency('USD')"><i class="fa fa-usd" aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a class="mobail_dropdawn_text" @click="currency('EUR')"><i class="fa fa-eur" aria-hidden="true"></i></a>
                                    </li>
                                    <li>
                                        <a class="mobail_dropdawn_text" @click="currency('GEL')"><!-- <font-awesome-icon icon="fa-solid fa-lari-sign" /> -->lari</a>
                                    </li>
                                </ul>
                            </div>
                            
                            <!-- <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown">
                                <i class="fa fa-language" aria-hidden="true"></i>
                            </button>
                            
                            <button type="button" class="navbar-toggle margin_right">
                                <a style="margin-top: -5%; color: #333333;" href="#">
                                    <i class="fa fa-user" ></i>
                                </a>
                            </button>
                            
                            <ul class="dropdown-menu shadows" role="menu" >
                                <li><a href="#"><img style="width: 20%;  margin-left: 40%;" :src="'images/site_img/leng/en.png'" alt=""></a></li>
                                <li><a href="#"><img style="width: 20%;  margin-left: 40%;" :src="'images/site_img/leng/ka.webp'" alt=""></a></li>
                                <li><a href="#"><img style="width: 20%;  margin-left: 40%;" :src="'images/site_img/leng/ru.png'" alt=""></a></li>
                            </ul> -->

                            <div class="dropdown">
                                <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown" id="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </button>
                                <ul class="dropdown-menu shadows mobail_dropdown" aria-labelledby="dropdown">
                                    <form action="/search" id="searchForm" method="POST" role="search" @submit.prevent="search" class="monail_navbar_form">
                                        <input class="form-control" name="request" v-model="search_query" placeholder="Search Here" autocomplete="off" autofocus="autofocus" type="text" lang="en">
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
                                
                                <!-- <li><span> {{ $n(100, 'currency') }} </span> </li>
                                <li><span> {{ $n(100, 'currency') }} </span> </li> -->

                                <li><router-link :to="{name: 'catalog'}" exact> <span> {{ $t('shop menu products') }}  </span> </router-link></li>
                                
                                <!-- <li><a href="#">services</a></li> -->
                                <!-- <li><a id='actyve' href="#">about us</a></li> -->
                                <!-- <li><router-link :to="{name: 'services'}"> services </router-link></li> -->
                                <li><router-link :to="{name: 'services'}" exact> <span> {{ $t('shop menu services') }} </span> </router-link></li>
                                <li><router-link :to="{name: 'about_store'}" exact> <span> {{ $t('shop menu about us') }} </span> </router-link></li>

                                <li class="display-none-720px">
                                    <a style="margin-top: -5%;" @click="goTo('/cart/my_cart')">
                                        <i class="fa fa-shopping-cart"></i>
                                    </a>
                                </li>

                                <li class="display-none-720px">
                                    <a style="margin-top: -5%;" @click="goTo('/favorite_products')">
                                        <i class="fa fa-heart" aria-hidden="true"></i>
                                    </a>
                                </li>

                                <li class="dropdown">
                                    <a class="margin_right admin_menu_for_desctop" data-toggle="dropdown">
                                        <i class="fa fa-search"  aria-hidden="true"></i>
                                    </a>
                                    <!-- <ul class="dropdown-menu shadows menu_margin_left_850" role="menu" style="width: 1000%;">
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
                                    </ul> -->


                                    <ul class="dropdown-menu shadows menu_margin_left_850" role="menu" style="width: 1000%;">
                                        <li>
                                            <form action="/search" id="searchForm" method="POST" role="search" @submit.prevent="search" class="monail_navbar_form">
                                                <input class="form-control" name="request" v-model="search_query" placeholder="Search Here" autocomplete="off" autofocus="autofocus" type="text" lang="en">
                                            </form>
                                            <button type="submit" form="searchForm" class="monail_navbar_form_button"> 
                                                <span class="glyphicon glyphicon-search"></span>
                                            </button>
                                        </li>
                                    </ul>
                                </li>

                                <li class="dropdown display-none-720px">

                                    <a data-toggle="dropdown">
                                        <i class="fa fa-usd" aria-hidden="true"></i>
                                    </a>

                                    <ul class="dropdown-menu shadows" role="menu">
                                        <li><a @click="currency('USD')"><i class="fa fa-usd" aria-hidden="true"></i></a></li>
                                        <li><a @click="currency('EUR')"><i class="fa fa-eur" aria-hidden="true"></i></a></li>
                                        <li><a @click="currency('GEL')"><!-- <font-awesome-icon icon="fa-solid fa-lari-sign" /> -->lari</a></li>
                                    </ul>
                                </li>

                                <!-- <li class="dropdown display-none-720px">

                                    <a data-toggle="dropdown">
                                        <i class="fa fa-language" aria-hidden="true"></i>
                                    </a>

                                    <ul class="dropdown-menu shadows" role="menu">
                                        <li><a  @click="localization('us')"><img style="width: 50%; margin-left: 25%;" :src="'images/site_img/leng/en.png'" alt="us"></a></li>
                                        <li><a  @click="localization('ka')"><img style="width: 50%; margin-left: 25%;" :src="'images/site_img/leng/ka.webp'" alt="ka"></a></li>
                                        <li><a  @click="localization('ru')"><img style="width: 50%; margin-left: 25%;" :src="'images/site_img/leng/ru.png'" alt="ru"></a></li>
                                    </ul>
                                </li> -->

                                <localeSwitcher />
                                
                                <goTo :go_to_page="'/productsList'" />
                                
                            </ul>
                        </div>
                    </nav>
                <!-- </div> -->
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