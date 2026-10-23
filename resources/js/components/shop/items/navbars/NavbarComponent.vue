<template>
    <header class="cg-nav shop-navbar" :class="{ 'is-open': is_open }">
        <div class="cg-nav__inner">

            <cg-brand service="shop" :to="{ name: 'index' }" />

            <button type="button" class="cg-nav__toggle" :aria-expanded="is_open" :aria-label="$t('global.menu_toggle')" @click="open_navbar()">
                <span></span><span></span><span></span>
            </button>

            <nav class="cg-nav__menu" id="alignment-example">
                <ul class="cg-nav__links">
                    <li class="dropdown" :class="{ active: is_catalog_page }">
                        <a class="dropdown-toggle" id="shopProductsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span>{{ $t('shop.menu.products') }}</span>
                        </a>
                        <ul class="dropdown-menu cg-nav__mega" aria-labelledby="shopProductsDropdown">
                            <li><router-link :class="{ active: is_all_products_active }" :to="{name: 'catalog', params: {locale: this.$i18n.locale}}" exact><i class="fa fa-th-large fa-fw" aria-hidden="true"></i> {{ $t('shop.menu.all_products') }}</router-link></li>
                            <li v-if="sale_products_count > 0"><router-link :class="{ active: this.$route.name == 'sale_products' }" :to="{name: 'sale_products', params: {locale: this.$i18n.locale}}" exact><i class="fa fa-tag fa-fw" aria-hidden="true"></i> {{ $t('shop.menu.sale_products') }}</router-link></li>

                            <li v-if="product_categories.length > 0"><hr class="dropdown-divider"></li>
                            <li v-for="category in product_categories" :key="category.id">
                                <router-link :class="{ active: is_category_active(category.id) }" :to="{name: 'catalog', params: {locale: this.$i18n.locale}, query: {category_id: category.id}}">{{ category.us_name }}</router-link>
                            </li>
                        </ul>
                    </li>
                    <li v-if="outlet_products_count > 0"><router-link :to="{name: 'outlet_products', params: {locale: this.$i18n.locale}}" exact><span>{{ $t('shop.menu.outlet_products') }}</span></router-link></li>
                    <li><router-link :to="{name: 'wall_price_colculator', params: {locale: this.$i18n.locale}}" exact><span>{{ $t('shop.menu.climbing wall') }}</span></router-link></li>
                    <li><router-link :to="{name: 'services', params: {locale: this.$i18n.locale}}" exact><span>{{ $t('shop.menu.services') }}</span></router-link></li>
                    <li><router-link :to="{name: 'tours', params: {locale: this.$i18n.locale}}" exact><span>{{ $t('shop.menu.tours') }}</span></router-link></li>
                    <li><router-link :to="{name: 'about_store', params: {locale: this.$i18n.locale}}" exact><span>{{ $t('shop.menu.about us') }}</span></router-link></li>
                </ul>

                <ul class="cg-nav__tools">
                    <navbar-search site="shop" search_route="search_products" />
                    <localeSwitcher />
                    <services-switcher current="shop" />
                    <nav-badges />
                </ul>
            </nav>

        </div>
    </header>
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

                is_open: false,

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
                this.is_open = false
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
                this.is_open = !this.is_open
            }
        }
    }
</script>
