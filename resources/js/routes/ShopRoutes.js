// import MainWrapper from './components/shop/MainWrapperComponent.vue'
import Catalog from '../components/shop/pages/CatalogComponent.vue'
import ItemPage from '../components/shop/pages/ProductPageComponent.vue'
import AboutUs from '../components/shop/pages/AboutUsComponent.vue'
import ServicesCatalog from '../components/shop/pages/ServicesCatalogComponent.vue'
import ServicePage from '../components/shop/pages/ServicePageComponent.vue'
import Search from '../components/shop/pages/SearchPageComponent.vue'

import NotFound from '../components/errors/404Component.vue'
export default{
    routes: [
        { path: '/', name: 'catalog', component: Catalog },
        { path: '/product/:url_title', name: 'product', component: ItemPage,},
        
        { path: '/services', name: 'services', component: ServicesCatalog,},
        { path: '/service/:url_title', name: 'service', component: ServicePage,},

        { path: '/about_store', name: 'about_store', component: AboutUs,},

        { path: '/search_products', name: 'search_products', component: Search,},
        
        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
};