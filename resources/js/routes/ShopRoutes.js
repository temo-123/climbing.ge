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
                { path: '', name: 'catalog', component: load('CatalogComponent') },
                { path: 'product/:url_title', name: 'product', component: load('ProductPageComponent'),},
                
                { path: 'services', name: 'services', component: load('ServicesCatalogComponent'),},
                { path: 'service/:url_title', name: 'service', component: load('ServicePageComponent'),},

                { path: 'about_us', name: 'about_store', component: load('AboutUsComponent'),},
                { path: 'search_products', name: 'search_products', component: load('SearchPageComponent'),},
                
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