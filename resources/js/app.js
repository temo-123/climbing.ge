import { createApp } from "vue";
import "./bootstrap";

const app = createApp({
    setup() {
        // Vue 3 setup function
    }
});

import axios from "axios";
app.use(axios);

import { createPinia } from "pinia";
app.use(createPinia());

import i18n from "./services/localization/i18n";
app.use(i18n);

import { abilityDefaults } from "./services/ability/ability.js"
app.config.globalProperties.$ability = abilityDefaults
app.config.globalProperties.$can = (action, subject) => abilityDefaults.can(action, subject)

import { going } from './mixins/easy_navigation_mixin.js';
app.config.globalProperties.$going = going;

import { editor_config } from './mixins/editor/editor_config_mixin.js';
app.config.globalProperties.$editor_config = editor_config;
app.config.globalProperties.$bus = {
    callbacks: {},
    $emit(event, payload) {
        (this.callbacks[event] || []).forEach(callback => callback(payload));
    },
    $on(event, callback) {
        (this.callbacks[event] = this.callbacks[event] || []).push(callback);
        // Return unsubscribe
        return () => this.$off(event, callback);
    },
    $off(event, callback) {
        const callbacks = this.callbacks[event];
        if (callbacks) {
        const index = callbacks.indexOf(callback);
        if (index > -1) callbacks.splice(index, 1);
        }
    }
};

import StackModal from "./components/global_components/modals/StackModal.vue"
app.component('StackModal', StackModal)

import goToAdminPage from "./components/global_components/GoToComponrnt.vue";
app.component("goToAdminPage", goToAdminPage);

import leftmenu from "./components/user/items/navbars/LeftMenuComponent.vue";
import validator_alerts_component from "./components/user/items/form/validator_alerts_component.vue";
import GlobalInfoFormBlock from "./components/user/items/form/parts/GlobalInfoFormBlockComponent.vue";

import guide_img from "./components/guide/items/ImageComponent.vue";
import shop_img from "./components/shop/items/ImageComponent.vue";
import MainWrapper from "./components/shop/MainWrapper.vue";
import Index from "./components/guide/IndexComponent.vue";
import Summit from "./components/summit/SummitMainComponent.vue";
import Films from "./components/films/StudiaComponent.vue";
import Blog from "./components/blog/BlogMainComponent.vue";
import Home from "./components/user/HomeComponent.vue";
import Error from "./components/errors/global_errors/error.vue";

import shop_routes from "./routes/ShopRoutes";
import summit_routes from "./routes/SummitRouter";
import site_routes from "./routes/SiteRoutes";
import user_routes from "./routes/UserRoutes";
import films_routes from "./routes/FilmsRoutes";
import blog_routes from "./routes/BlogRoutes";
import error_routes from "./routes/ErrorRoutes";

var serviceRoutes = [];
var homeComponent = [];
let analytic_id = "";

if (window.location.hostname == process.env.MIX_SITE_URL) {
    app.component("index-component", Index);
    app.component("site-img", guide_img);

    homeComponent = Index;
    serviceRoutes = site_routes.options ? site_routes.options.routes : site_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let siteUrl = (process.env.MIX_SITE_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = siteUrl ? baseUrl + '/' + siteUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_CLIMBING_GUIDBOOK_ANALITICS_ID;
} 
else if (window.location.hostname == process.env.MIX_SHOP_URL) {
    app.component("main-wrapper-component", MainWrapper);
    app.component("shop-img", shop_img);

    homeComponent = MainWrapper;
    serviceRoutes = shop_routes.options ? shop_routes.options.routes : shop_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let shopUrl = (process.env.MIX_SHOP_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = shopUrl ? baseUrl + '/' + shopUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_SHOP_ANALITICS_ID;
} 
else if (window.location.hostname == process.env.MIX_SUMMIT_URL) {
    app.component("summit-component", Summit);

    homeComponent = Summit;
    serviceRoutes = summit_routes.options ? summit_routes.options.routes : summit_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let userUrl = (process.env.MIX_SUMMIT_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = userUrl ? baseUrl + '/' + userUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_SUMMIT_ANALITICS_ID;
} 
else if (window.location.hostname == process.env.MIX_FILMS_URL) {
    app.component("studia-component", Films);

    homeComponent = Films;
    serviceRoutes = films_routes.options ? films_routes.options.routes : films_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let filmsUrl = (process.env.MIX_FILMS_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = filmsUrl ? baseUrl + '/' + filmsUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_FILMS_ANALITICS_ID;
} 
else if (window.location.hostname == process.env.MIX_BLOG_URL) {
    app.component("blog-component", Blog);

    homeComponent = Blog;
    serviceRoutes = blog_routes.options ? blog_routes.options.routes : blog_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let blogUrl = (process.env.MIX_BLOG_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = blogUrl ? baseUrl + '/' + blogUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_BLOG_ANALITICS_ID;
} 
else if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {

    app.component("left-menu", leftmenu);
    app.component("validator_alerts_component", validator_alerts_component);
    app.component("GlobalInfoFormBlock", GlobalInfoFormBlock);

    app.component('home-component', Home);

    homeComponent = Home;
    serviceRoutes = user_routes.options ? user_routes.options.routes : user_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let userUrl = (process.env.MIX_USER_PAGE_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = userUrl ? baseUrl + '/' + userUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_USER_ANALITICS_ID;
} 
else {
    homeComponent = Error;
    serviceRoutes = error_routes.options ? error_routes.options.routes : error_routes;
    analytic_id = process.env.MIX_ERROR_PAGE_ANALITICS_ID;
}

import { createGtag } from "vue-gtag";
createGtag({
    gtag: {
        id: analytic_id
    }
});

app.config.productionTip = false;
app.config.globalProperties.$siteData = { data: [] };
app.config.globalProperties.$globalSiteData = { data: [] };

app.mixin({
    methods: {
        $get_site_data() {
            return this.get_site_data;
        },
        get_site_data() {
            axios
                .get("/get_site_data/get_site_locale_data_for_site/" + localStorage.getItem('lang') || 'en')
                .then((response) => (
                    this.$siteData.data = response.data.locale_data,
                    this.$globalSiteData.data = response.data.global_data
                ));
        }
    },
    data() {
        return {
            $siteData: app.config.globalProperties.$siteData,
            $globalSiteData: app.config.globalProperties.$globalSiteData
        }
    }
});

if(
    window.location.hostname == 'climbing.ge' ||
    window.location.hostname == 'shop.climbing.ge' ||
    window.location.hostname == 'user.climbing.ge' ||
    window.location.hostname == 'films.climbing.ge' ||
    window.location.hostname == 'summit.climbing.ge' ||
    window.location.hostname == 'blog.climbing.ge'
){
    app.config.devtools = false
    app.config.debug = false
}
else{
    app.config.devtools = true
    app.config.debug = true
}

import { createRouter, createWebHistory } from 'vue-router';
import { isRouteLoading } from './store/routeLoader.js';

const router = createRouter({
    history: createWebHistory(),
    routes: serviceRoutes,
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

import { getCurrentLocale } from './services/routerUtils.js';

router.beforeEach((to, from, next) => {
    isRouteLoading.value = true;
    window.scrollTo(0, 0);

    let locale = localStorage.getItem('lang') || 'us';

    if (to.path.startsWith('/ka')) {
        locale = 'ka';
    } else if (to.path === '/') {
    } else {
        locale = 'us';
    }

    localStorage.setItem('lang', locale);
    
    if (i18n.locale !== locale) {
        i18n.locale = locale;
    }

    if (locale === 'ka' && !to.path.startsWith('/ka') && to.path !== '/') {
        return next('/ka' + to.path);
    }

    next();
});

router.afterEach(() => {
    setTimeout(() => {
        isRouteLoading.value = false;
    }, 300);
});

window.axios.interceptors.response.use({}, err => {
    if(err && err.response){
        if(err.response.status === 401 || err.response.status === 419){
            const token = localStorage.getItem('x_xsrf_token')
            if(token){
                localStorage.removeItem('x_xsrf_token')
            }

            if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {
                router.push({name: "login"})
            }

            return Promise.reject(err)
        }
        else if(err.response.status === 422){
            return Promise.reject(err)
        }
        else if(err.response.status === 403){
            if (err.response.data && err.response.data.is_banned === true) {
                if (err.response.data.alert) {
                    alert(err.response.data.alert.title + '\n\n' + err.response.data.alert.message);
                } else {
                    alert('Your account has been banned.');
                }
                
                localStorage.removeItem('x_xsrf_token');
                localStorage.removeItem('user');
                window.close();
                return Promise.reject(err);
            }
            else{
                alert("You don't have permission to perform this action.")
                return Promise.reject(err)
            }
        }
        else{
            alert("Error -> {" + err + "}")
            return Promise.reject(err)
        }
    }
})

app.use(router);

app.mount("#app");

window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {}
