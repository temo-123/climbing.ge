<template>
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row navbar-fixed-top top_menu">
            <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3" style="z-index: 1001;">
                <div class="site_title">
                    <!-- <a :href="MIX_APP_SSH + MIX_SITE_URL" class="site_title">{{__ ('site name')}}</a> -->
                    <router-link style="font-size: 1.5em;" :to="{name: 'index'}" exact class="site_title">{{__ ('site name')}}</router-link>
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
                            <li class="dropdown">
                                <!-- <a href="#" id='actyve' data-toggle="dropdown">  -->
                                <a href="#" data-toggle="dropdown"> 
                                {{__ ('menu climbing') }}
                               </a>

                                <ul class="dropdown-menu " role="menu" style="background: #1994b1; position: absolute;">
                                    <!-- <li><a style="font-size: 1.5em;" href="#">{{__ ('menu indoor climbing') }}</a></li> -->
                                    <li><router-link style="font-size: 1.5em;" :to="{name: 'indoors'}" exact>  <span> {{__ ('menu indoor climbing') }}  </span> </router-link></li>
                                    <li><router-link style="font-size: 1.5em;" :to="{name: 'outdoors'}" exact> <span> {{__ ('menu outdoor climbing') }} </span> </router-link></li>
                                    <!-- <li><a style="font-size: 1.5em;" href="#">{{__ ('menu outdoor climbing') }}</a></li> -->
                                </ul>
                            </li>


                            <li><router-link :to="{name: 'mountaineerings'}" exact> <span> {{__ ('menu mountaineering') }}      </span> </router-link></li>
                            <li><router-link :to="{name: 'ices'}" exact>            <span> {{__ ('menu ice climbing') }}        </span> </router-link></li>
                            <li><router-link :to="{name: 'others'}" exact>          <span> {{__ ('menu other') }}               </span> </router-link></li>
                            <li><router-link :to="{name: 'about_us'}" exact>        <span> {{__ ('menu about us') }}            </span> </router-link></li>
                            <!-- <li><a href="#">{{__ ('menu mountaineering') }}</a></li>
                            <li><a href="#">{{__ ('menu ice climbing') }}</a></li>
                            <li><a href="#">{{__ ('menu other') }}</a></li>
                            <li><a href="#">{{__ ('menu about us') }}</a></li> -->


                            <!-- <li class="dropdown">
                                <a href="#" class="margin_right admin_menu_for_desctop" data-toggle="dropdown">
                                    <i class="fa fa-search" aria-hidden="true"></i>
                                </a>
                                <ul class="dropdown-menu shadows menu_margin_left_850" role="menu" style="width: 1000%;">
                                    <li>
                                        <form action="/search" method="POST" role="search" class="navbar-form">
                                            <div class="form-group" style="display:inline;">
                                                <div class="input-group" style="display:table;">
                                                <span class="input-group"> 
                                                    <input class="form-control" name="request" placeholder="Search Here" autocomplete="off" autofocus="autofocus" type="text">
                                                    <button type="submit" class="input-group-addon"> 
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </span>
                                                </div>
                                            </div>
                                        </form>
                                    </li>
                                    <search />
                                </ul>
                            </li> -->
                            
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
                                <a href="#" data-toggle="dropdown">
                                    <i class="fa fa-language" aria-hidden="true"></i>
                                </a>

                                <ul class="dropdown-menu shadows" role="menu">
                                    <li><a  @click="localization('us')"><img style="width: 50%; margin-left: 25%;" :src="'images/site_img/leng/en.png'" alt="us"></a></li>
                                    <li><a  @click="localization('ka')"><img style="width: 50%; margin-left: 25%;" :src="'images/site_img/leng/ka.webp'" alt="ka"></a></li>
                                    <li><a  @click="localization('ru')"><img style="width: 50%; margin-left: 25%;" :src="'images/site_img/leng/ru.png'" alt="ru"></a></li>
                                </ul>
                            </li>
                            
                            <!-- @if (Route::has('login'))
                                @auth -->
                                <!-- <li class="display-none-720px">
                                    <a style="margin-top: -5%; font-size: 120%;" href="">
                                        <i class="fa fa-user-circle"></i>
                                    </a>
                                </li> -->
                                <li class="display-none-720px">
                                    <a style="margin-top: -5%; font-size: 120%;" @click="goTo('/')">
                                        <i class="fa fa-user-circle"></i>
                                    </a>
                                </li>
                                <!-- @endauth
                            @endif
                            @if (Auth::guest())
                                <li class="display-none-720px">
                                    <a style="margin-top: -5%; font-size: 120%;" href="{{route('user_index')}}">
                                        <i class="fa fa-user-circle"></i>
                                    </a>
                                </li>
                            @endif -->
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>  
</template>

<script>
    import search from './SearchComponent'

    export default {
        data: function () {
            return {
                search_query: '',
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        components: {
            search
        },
        mounted() {
        },
        methods: {
            search(){
                this.$router.push({name: 'search_articles', query: { 'query': this.search_query} });
            },
            localization(leng){
                window.location.href = leng;
            },
            goTo(page){
                window.location.href = this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page ;
            },
            
        }
    }
</script>
