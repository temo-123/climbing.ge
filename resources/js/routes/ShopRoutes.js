import VueRouter from 'vue-router'

// import Catalog from '../components/shop/pages/CatalogComponent.vue'
// import ItemPage from '../components/shop/pages/ProductPageComponent.vue'
// import AboutUs from '../components/shop/pages/AboutUsComponent.vue'
// import ServicesCatalog from '../components/shop/pages/ServicesCatalogComponent.vue'
// import ServicePage from '../components/shop/pages/ServicePageComponent.vue'
// import Search from '../components/shop/pages/SearchPageComponent.vue'


function load(component) {
    return () => import(`../components/shop/pages/${component}.vue`)
}

import NotFound from '../components/errors/404Component.vue'
const router = new VueRouter({
    routes: [
        { path: '/', name: 'catalog', component: load('CatalogComponent') },
        { path: '/product/:url_title', name: 'product', component: load('ProductPageComponent'),},
        
        { path: '/services', name: 'services', component: load('ServicesCatalogComponent'),},
        { path: '/service/:url_title', name: 'service', component: load('ServicePageComponent'),},

        { path: '/about_us', name: 'about_store', component: load('AboutUsComponent'),},
        { path: '/search_products', name: 'search_products', component: load('SearchPageComponent'),},
        
        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
});

export default router