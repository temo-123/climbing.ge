<template>
        <div class="header navbar-expand-lg navbar-right bg-light">
            <div class="row navbar-fixed-top top_menu">
                <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3" style="z-index: 1001;">
                    <div class="site_title">
                        <!-- <a :href="MIX_APP_SSH + 'shop.' + MIX_SITE_URL+'/catalog'" class="site_title">name</a> -->
                        <!-- <router-link :class="'site_title'" :to="{name: 'home'}"> name </router-link> -->
                        <router-link style="font-size: 1.5em;" :to="{name: 'catalog'}" exact class="site_title">{{ $t('shop name')}}</router-link>
                    </div>
                </div>
                <div class="col-xs-12 col-sm-10 col-md-9 col-lg-9">
                    <nav class="navbar navbar-default  float-right float-top" role="navigation">
                        <div class="container-fluid">
                            
                            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <i class="fa fa-angle-down"></i>
                            </button>
                            
                            <button type="button" class="navbar-toggle margin_right">
                                <a style="margin-top: -5%; color: #333333;" href="#">
                                    <i class="fa fa-shopping-cart"></i>
                                </a>
                            </button>                 
                            
                            <button type="button" class="navbar-toggle margin_right" data-toggle="dropdown">
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
                            </ul>
                        </div>
                        <div class="collapse navbar-collapse mobile_nav_menu" id="bs-example-navbar-collapse-1">
                            <ul class="nav navbar-nav pull-right top_menu_buttons">
                                
                                <!-- <li><span> {{ $n(100, 'currency') }} </span> </li>
                                <li><span> {{ $n(100, 'currency') }} </span> </li> -->

                                <li><router-link :to="{name: 'catalog'}" exact> <span> products </span> </router-link></li>
                                
                                <!-- <li><a href="#">services</a></li> -->
                                <!-- <li><a id='actyve' href="#">about us</a></li> -->
                                <!-- <li><router-link :to="{name: 'services'}"> services </router-link></li> -->
                                <li><router-link :to="{name: 'services'}" exact> <span> services </span> </router-link></li>
                                <li><router-link :to="{name: 'about_store'}" exact> <span> about us </span> </router-link></li>

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

                                <locale />
                                
                                <goTo :go_to_page="'/productsList'" />
                                
                            </ul>
                        </div>
                    </nav>
                </div>
            </div>
        </div>
</template>

<script>
    import locale from '../../global_components/LocaleChangeComponent.vue'
    export default {
        data: function () {
            return {
                search_query: '',
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
                // MIX_SHOP_URL: process.env.MIX_SHOP_URL
            };
        },
        components: {
            locale,
        },
        mounted() {
        },
        methods: {
            search(){
                this.$router.push({name: 'search_products', query: { 'query': this.search_query} });
            },
            localization(leng){
                window.location.href = leng;
            },
            goTo(page){
                window.open(this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page)
            },
            currency(cur){
                localStorage.setItem('currency', cur)
            },
        }
    }
</script>
