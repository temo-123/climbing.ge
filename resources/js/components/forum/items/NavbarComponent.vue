<template>
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row navbar-fixed-top top_menu">
            <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3" style="z-index: 1001;">
                <div class="site_title">
                    <router-link style="font-size: 1.5em;" :to="{name: 'index'}" exact class="site_title">{{ $t('site name')}}</router-link>
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
                    </div>
                    <div class="collapse navbar-collapse mobile_nav_menu" id="bs-example-navbar-collapse-1">
                        <ul class="nav navbar-nav pull-right top_menu_buttons">
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
    // import search from './SearchComponent'

    export default {
        data: function () {
            return {
                search_query: '',
                MIX_SITE_URL: process.env.MIX_SITE_URL,
                MIX_APP_SSH: process.env.MIX_APP_SSH,
            };
        },
        components: {
            // search
        },
        mounted() {
        },
        methods: {
            // search(){
            //     this.$router.push({name: 'search_articles', query: { 'query': this.search_query} });
            // },
            // localization(leng){
            //     window.location.href = leng;
            // },
            // goTo(page){
            //     window.location.href = this.MIX_APP_SSH + 'user.' + this.MIX_SITE_URL + page ;
            // },
            
        }
    }
</script>
