import VueRouter from 'vue-router'

import NotFound from '../components/errors/404Component.vue'

import i18n from "../services/localization/i18n";

function load(component) {
    return () => import(`../components/shop/pages/${component}.vue`)
}

function getLocaleRegex() {
    let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE
    
    return `(${reg})`;
}

const router = new VueRouter({
    routes: [
        {
            path: `/:locale${getLocaleRegex()}?`,
            // path: `/:locale(ka|ru|/)`,
            component: {
                render: (h) => h("router-view"),
            },
            beforeEnter: (to, from, next) => {
                let storage_locale = localStorage.getItem("lang")

                if(storage_locale){
                    to.params.locale = storage_locale;
                }
                else{
                    to.params.locale = 'en';
                }

                const locale = to.params.locale;

                localStorage.setItem("lang", locale);

                const supported_locales = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE.split("|");

                if (!supported_locales.includes(locale)) {
                    return next("/");
                }

                if (i18n.locale !== locale) {
                    i18n.locale = locale;
                }

                return next();
            },
            children: [
                { path: '/', name: 'index', component: load('MeinPage') },

                { path: '/climbing_wall_colculator', name: 'wall_price_colculator', component: load('pages/wall_colculator/ClimbingWallPriceColculatePage') },

                { path: '/products', name: 'catalog', component: load('lists/ProductsCatalog') },
                { path: '/product/:url_title', name: 'product', component: load('pages/ProductPage'),},
                
                { path: '/services', name: 'services', component: load('lists/ServicesCatalog'),},
                { path: '/service/:url_title', name: 'service', component: load('pages/ServicePage'),},
                
                { path: '/tours', name: 'tours', component: load('lists/ToursCatalog'),},
                { path: '/tour/:url_title', name: 'tour', component: load('pages/TourPage'),},

                { path: '/about_us', name: 'about_store', component: load('AboutUs'),},
                { path: '/search_products', name: 'search_products', component: load('SearchPage'),},

                { path: "confirm_comment_email/:comment_id/:email", name: "confirm_comment_email", component: load("CommentEmailVerificationePage"),},
                
                { path: '*', name: 'NotFound', component: NotFound }
            ]
        }
    ],
    mode: 'history',
});

router.beforeEach((to, from, next) => {
    to.params.locale = localStorage.getItem("lang");

    next();
});


export default router