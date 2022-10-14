<template>
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row navbar-fixed-top top_menu">
            <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3" style="z-index: 1001;">
                <div class="site_title">
                    <router-link style="font-size: 1.5em;" :to="{name: 'forum_index'}" exact class="site_title">Climbing.ge forum</router-link>
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
                            <li><router-link :to="{name: 'users_list'}" exact>        <span> users           </span> </router-link></li>
                            <li><router-link :to="{name: 'events_list'}" exact>        <span> events            </span> </router-link></li>
                            <li><router-link :to="{name: 'about_forum'}" exact>        <span> {{ $t('menu about us') }}            </span> </router-link></li>

                            <goTo :go_to_page="'/my_posts'" />

                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>  
</template>

<script>
    export default {
        data: function () {
            return {
                //
            };
        },
        components: {
            //
        },
        mounted() {
            //
        },
        methods: {
            // 
        }
    }
</script>
