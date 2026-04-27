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
                    
                    <li><router-link :to="{name: 'catalog',  params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.products') }}  </span> </router-link></li>
                    <li><router-link :to="{name: 'sale_products',  params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.sale_products') }}  </span> </router-link></li>
                    <li><router-link :to="{name: 'wall_price_colculator',  params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.climbing wall') }}  </span> </router-link></li>
                    <li><router-link :to="{name: 'services',  params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.services') }} </span> </router-link></li>
                    <li><router-link :to="{name: 'tours',  params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.tours') }} </span> </router-link></li>
                    <li><router-link :to="{name: 'about_store',  params: {lang: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.about us') }} </span> </router-link></li>

                    <navbar-search site="shop" search_route="search_products" />

                    <localeSwitcher />

                    <li>
                        <a @click="go_to_service('guid')" class="cursor_pointer"> 
                            <span>
                                <i class="fa fa-map-o" aria-hidden="true"></i>
                            </span>
                        </a>
                    </li>

                    <li>
                        <a @click="go_to_service('summit')" class="cursor_pointer"> 
                            <span>
                                <i class="fa fa-street-view" aria-hidden="true"></i>
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
    // import search from './SearchComponent'
    import localeSwitcher from '../../../global_components/LocaleChangeComponent.vue'
    import go_to_service_mixin from '../../../../mixins/go_to_service_mixin.js'

    export default {
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
        components: {
            localeSwitcher,
        },
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

.search_window{
    width: 1500%;
    background-color: #dfdfdf;
    border-radius: 0 0 20px 20px;
}

</style>