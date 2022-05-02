import VueRouter from 'vue-router'

import Catalog from '../components/shop/pages/CatalogComponent.vue'
import AboutUs from '../components/shop/pages/AboutUsComponent.vue'
import ServicesCatalog from '../components/shop/pages/ServicesCatalogComponent.vue'
import ServicePage from '../components/shop/pages/ServicePageComponent.vue'
import Search from '../components/shop/pages/SearchPageComponent.vue'

import login from '../components/auth/LoginComponent.vue'
import register from '../components/auth/RegisterComponent.vue'

import NotFound from '../components/errors/404Component.vue'
const router = new VueRouter({
    routes: [
        { path: '/', name: 'catalog', component: Catalog },
        // { path: '/product/:url_title', name: 'product', component: ItemPage,},
        
        { path: '/services', name: 'services', component: ServicesCatalog,},
        { path: '/service/:url_title', name: 'service', component: ServicePage,},

        { path: '/about_us', name: 'about_store', component: AboutUs,},

        { path: '/search_products', name: 'search_products', component: Search,},
        
        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
});

export default router