import { createRouter, createWebHistory, createMemoryHistory } from "vue-router";
import { h } from "vue";
import { RouterView } from "vue-router";

import NotFound from '../components/errors/404Component.vue'

import i18n from "../services/localization/i18n";

import UnfollowComponent from "../components/global_components/varification_pages/UnfollowPage.vue";
import CommentEmailVerificationePage from "../components/global_components/varification_pages/CommentEmailVerificationePage.vue";

function load(component) {
    return () => import(`../components/shop/pages/${component}.vue`)
}

function getLocaleRegex() {
    let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE
    
    return `(${reg})`;
}

const routes = [
{
        path: `/:locale${getLocaleRegex()}?`,
        component: {
            render() {
                // return h("router-view");
                return h(RouterView);
            }
        },
        children: [
            { path: '', name: 'index', component: load('MeinPage'), meta: { title: 'Shop Index' } },

            { path: '/climbing_wall_colculator', name: 'wall_price_colculator', component: load('pages/wall_colculator/ClimbingWallPriceColculatePage'), meta: { title: 'Wall Price Calculator' } },

            { path: '/products', name: 'catalog', component: load('lists/ProductsCatalog'), meta: { title: 'Products Catalog' } },
            { path: '/sale_products', name: 'sale_products', component: load('lists/SaleProductsPage'), meta: { title: 'Sale Products' } },
            { path: 'product/:url_title', name: 'product', component: load('pages/ProductPage'), meta: { title: 'Product' },},
                        
            { path: '/services', name: 'services', component: load('lists/ServicesCatalog'), meta: { title: 'Services' }, },
            { path: 'service/:url_title', name: 'service', component: load('pages/ServicePage'), meta: { title: 'Service' },},
                        
            { path: '/tours', name: 'tours', component: load('lists/ToursCatalog'), meta: { title: 'Tours' }, },
            { path: 'tour/:url_title', name: 'tour', component: load('pages/TourPage'), meta: { title: 'Tour' },},

            { path: '/about_us', name: 'about_store', component: load('AboutUs'), meta: { title: 'About Store' }, },
            { path: '/search_products', name: 'search_products', component: load('SearchPage'), meta: { title: 'Search Products' }, },

            { path: "unfollow/:id", name: "unfollow", component: UnfollowComponent, meta: { title: 'Unfollow' } },
            { path: "confirm_comment_email/:comment_id/:email", name: "confirm_comment_email", component: CommentEmailVerificationePage, meta: { title: 'Confirm Comment Email' } },

            { path: "*", name: 'NotFound', component: NotFound, meta: { title: 'Not Found' } }
        ]
    }
];

const router = createRouter({
    routes,
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

export default router;

