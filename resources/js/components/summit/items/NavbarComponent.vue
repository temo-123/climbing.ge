<template>
    <nav class="navbar navbar-inverse navbar-fixed-top bg-dark">
        <div class="container-fluid">

            <!-- BRAND -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" aria-expanded="false" @click="open_navbar()">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <span>
                    <router-link style="font-size: 1.5em;" :to="'/'" class="site_title">{{ $t('summit.name') || 'Summit Climbing' }}</router-link>
                </span>
            </div>

            <!-- COLLAPSIBLE NAVBAR --> 
            <div :class='[navbar_class]' id="alignment-example">

                <!-- Links -->
                <ul class="nav navbar-nav navbar-right">
                    
                    <li><router-link :to="'/summits/list'"> <span> {{ $t('summit.menu.list') }} </span></router-link></li>
                    <li><router-link :to="'/summits/map'"> <span> {{ $t('summit.menu.map') }} </span></router-link></li>
                    <li><router-link :to="'/about_us'"> <span> {{ $t('summit.menu.about') }} </span></router-link></li>

                    <navbar-search site="summit" search_route="search_summits" />

                    <locale-switcher />

                    <li>
                        <a @click="go_to_service('guid')" class="cursor_pointer"> 
                            <span>
                                <i class="fa fa-map-o" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a @click="go_to_service('shop')" class="cursor_pointer"> 
                            <span>
                                <i class="fa fa-shopping-bag" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>


                    <li>
                        <a @click="go_to_service('blog')" class="cursor_pointer"> 
                            <span>
                                <i class="fa fa-newspaper-o" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>

                    <nav-badges />
                </ul>

            </div>

        </div>
    </nav>
</template>

<script>
import { RouterLink } from 'vue-router'
    import localeSwitcher from '../../global_components/LocaleChangeComponent.vue'
    import go_to_service_mixin from '../../../mixins/go_to_service_mixin.js'

    export default {
        components: {
            'router-link': RouterLink,
            localeSwitcher,
        },
        mixins: [
            go_to_service_mixin
        ],
        data: function () {
            return {
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
        // Duplicate components removed
        mounted() {
        },
        watch: {
            '$route' (to, from) {
                this.navbar_class = this.navbar_general_class
            }
        },
        methods: {
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

.navbar a {
    color: #fff;
    text-decoration: none;
}

.search_window{
    width: 1500%;
    background-color: #dfdfdf;
    border-radius: 0 0 20px 20px;
}

</style>
