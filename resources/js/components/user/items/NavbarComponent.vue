<template>
    <div class="header navbar-expand-lg navbar-right bg-light">
        <div class="row fixed-top top_menu">
            <div class="col-xs-12 col-sm-10 col-md-9 col-lg-9">
                    <div class="col-xs-12 col-sm-2 col-md-3 col-lg-3 ">
                        <div class="site_title">
                            <router-link :to="{name: 'home'}" class="site_title">
                                Hi {{ this.user }}
                            </router-link>
                        </div>
                    </div>
                <nav class="navbar navbar-default" role="navigation">
                    <!-- <div class="float-right float-top"> -->
                        <div class="collapse navbar-collapse mobile_nav_menu" id="bs-example-navbar-collapse-1">
                            <!-- <ul class="nav navbar-nav float-right top_menu_buttons">
                                <li class="dropdown">
                                    <a href="#" data-toggle="dropdown"> 
                                        {{ $t('menu climbing') }}
                                    </a>

                                    <ul class="dropdown-menu " role="menu" style="background: #1994b1; position: absolute;">
                                        <li><router-link style="font-size: 1.5em;" :to="{name: 'indoors'}" exact>  <span> {{ $t('menu indoor climbing') }}  </span> </router-link></li>
                                        <li><router-link style="font-size: 1.5em;" :to="{name: 'outdoors'}" exact> <span> {{ $t('menu outdoor climbing') }} </span> </router-link></li>
                                    </ul>
                                </li>
                                <li><router-link :to="{name: 'about_us'}" exact>        <span> {{ $t('menu about us') }}            </span> </router-link></li>
                            </ul> -->
                        </div>
                    <!-- </div> -->
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data(){
            return{
                user: '',
            }
        },
        mounted(){
            this.get_user()
        },
        // watch: {
        //     '$route' (to, from) {
        //         this.user = '',
        //         this.get_user()
        //         window.scrollTo(0,0)
        //     }
        // },
        methods: {
            get_user(){
                axios
                .get('/api/auth_user')
                .then((response)=>{
                    this.user = response.data['name']
                })
                .catch(
                    this.user = 'Boss'
                );
            }
        },
    }
</script>

<style>

</style>