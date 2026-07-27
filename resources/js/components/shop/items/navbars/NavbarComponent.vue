<template>
    <nav class="navbar navbar-inverse navbar-fixed-top bg-dark shop-navbar">
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
                    
                    <li class="dropdown" :class="{ active: is_catalog_page }">
                        <a href="#" class="dropdown-toggle cursor_pointer" id="shopProductsDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span> {{ $t('shop.menu.products') }} </span>
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="shopProductsDropdown">
                            <li :class="{ active: is_all_products_active }"><router-link :to="{name: 'catalog', params: {locale: this.$i18n.locale}}" exact> {{ $t('shop.menu.all_products') }} </router-link></li>
                            <li v-if="sale_products_count > 0" :class="{ active: this.$route.name == 'sale_products' }"><router-link :to="{name: 'sale_products', params: {locale: this.$i18n.locale}}" exact> {{ $t('shop.menu.sale_products') }} </router-link></li>

                            <li v-if="product_categories.length > 0" class="divider"></li>
                            <li v-for="category in product_categories" :key="category.id" :class="{ active: is_category_active(category.id) }">
                                <router-link :to="{name: 'catalog', params: {locale: this.$i18n.locale}, query: {category_id: category.id}}"> {{ category.us_name }} </router-link>
                            </li>
                        </ul>
                    </li>
                    <li v-if="outlet_products_count > 0"><router-link :to="{name: 'outlet_products',  params: {locale: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.outlet_products') }}  </span> </router-link></li>
                    <li><router-link :to="{name: 'wall_price_colculator',  params: {locale: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.climbing wall') }}  </span> </router-link></li>
                    <li><router-link :to="{name: 'services',  params: {locale: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.services') }} </span> </router-link></li>
                    <li><router-link :to="{name: 'tours',  params: {locale: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.tours') }} </span> </router-link></li>
                    <li><router-link :to="{name: 'about_store',  params: {locale: this.$i18n.locale}}" exact> <span> {{ $t('shop.menu.about us') }} </span> </router-link></li>

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

                // Hidden until confirmed non-empty, so the nav never shows a
                // link to a page that would just render EmptyPageComponent.
                sale_products_count: 0,
                outlet_products_count: 0,
                product_categories: [],

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
        computed: {
            is_catalog_page() {
                return this.$route.name == 'catalog';
            },
            is_all_products_active() {
                return this.$route.name == 'catalog' && !this.$route.query.category_id;
            },
        },
        mounted() {
            this.check_sale_and_outlet_products();
            this.get_product_categories();
        },
        watch: {
            '$route' (to, from) {
                this.navbar_class = this.navbar_general_class
            }
        },
        methods: {
            check_sale_and_outlet_products(){
                const lang = localStorage.getItem('lang') || 'us';
                axios
                .get('/get_product/get_local_saled_products/' + lang)
                .then(response => { this.sale_products_count = (response.data || []).length; })
                .catch(() => {});
                axios
                .get('/get_product/get_outlet_products/' + lang)
                .then(response => { this.outlet_products_count = (response.data || []).length; })
                .catch(() => {});
            },
            get_product_categories(){
                axios
                .get('/get_product/get_product_category/get_all_product_category')
                .then(response => { this.product_categories = response.data || []; })
                .catch(() => {});
            },
            is_category_active(category_id){
                return this.$route.name == 'catalog' && Number(this.$route.query.category_id) === category_id;
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

.search_window{
    width: 1500%;
    background-color: #dfdfdf;
    border-radius: 0 0 20px 20px;
}

</style>