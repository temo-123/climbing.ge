import { createApp } from "vue";
import "./bootstrap";

import { Ckeditor } from '@ckeditor/ckeditor5-vue';
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
import { createPinia } from "pinia";
import axios from "axios";
import i18n from "./services/localization/i18n";

import VueGlide from "vue-glide-js";
import "vue-glide-js/dist/vue-glide.css";
import { createGtag } from "vue-gtag";
import { createHead } from '@unhead/vue'

const head = createHead()

const app = createApp({
    setup() {
        // Vue 3 setup function
    }
});

import { abilityDefaults } from "./services/ability/ability.js"

app.config.globalProperties.$ability = abilityDefaults
app.config.globalProperties.$can = (action, subject) => abilityDefaults.can(action, subject)

app.use(VueGlide);
app.component('Ckeditor', Ckeditor.component);

app.config.globalProperties.$ClassicEditor = ClassicEditor;

app.use(i18n);
app.use(createPinia());
app.use(head);

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

import leftmenu from "./components/user/items/navbars/LeftMenuComponent.vue";
import goToAdminPage from "./components/global_components/GoToComponrnt.vue";
import guide_img from "./components/guide/items/ImageComponent.vue";
import shop_img from "./components/shop/items/ImageComponent.vue";

app.component("site-img", guide_img);
app.component("shop-img", shop_img);
app.component("left-menu", leftmenu);
app.component("goToAdminPage", goToAdminPage);

import MainWrapper from "./components/shop/MainWrapper.vue";
import Index from "./components/guide/IndexComponent.vue";
import Summit from "./components/summit/SummitMainComponent.vue";
import Home from "./components/user/HomeComponent.vue";
import Films from "./components/films/StudiaComponent.vue";
import Blog from "./components/blog/BlogMainComponent.vue";
import Error from "./components/errors/global_errors/error.vue";

import shop_routes from "./routes/ShopRoutes";
import summit_routes from "./routes/SummitRouter";
import site_routes from "./routes/SiteRoutes";
import user_routes from "./routes/UserRoutes";
import films_routes from "./routes/FilmsRoutes";
import blog_routes from "./routes/BlogRoutes";
import error_routes from "./routes/ErrorRoutes";

app.component("main-wrapper-component", MainWrapper);
app.component("summit-component", Summit);
app.component("index-component", Index);
app.component("studia-component", Films);
app.component("blog-component", Blog);

var serviceRoutes = [];
var homeComponent = [];
let analytic_id = "";

if (window.location.hostname == process.env.MIX_SITE_URL) {
    homeComponent = Index;
    serviceRoutes = site_routes.options ? site_routes.options.routes : site_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let siteUrl = (process.env.MIX_SITE_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = siteUrl ? baseUrl + '/' + siteUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_CLIMBING_GUIDBOOK_ANALITICS_ID;
} 

else if (window.location.hostname == process.env.MIX_SHOP_URL) {
    homeComponent = MainWrapper;
    serviceRoutes = shop_routes.options ? shop_routes.options.routes : shop_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let shopUrl = (process.env.MIX_SHOP_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = shopUrl ? baseUrl + '/' + shopUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_SHOP_ANALITICS_ID;
} 

else if (window.location.hostname == process.env.MIX_SUMMIT_URL) {
    homeComponent = Summit;
    serviceRoutes = summit_routes.options ? summit_routes.options.routes : summit_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let userUrl = (process.env.MIX_SUMMIT_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = userUrl ? baseUrl + '/' + userUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_SUMMIT_ANALITICS_ID;
} 

else if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {
    homeComponent = Home;
    serviceRoutes = user_routes.options ? user_routes.options.routes : user_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let userUrl = (process.env.MIX_USER_PAGE_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = userUrl ? baseUrl + '/' + userUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_USER_ANALITICS_ID;
} 

else if (window.location.hostname == process.env.MIX_FILMS_URL) {
    homeComponent = Films;
    serviceRoutes = films_routes.options ? films_routes.options.routes : films_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let filmsUrl = (process.env.MIX_FILMS_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = filmsUrl ? baseUrl + '/' + filmsUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_FILMS_ANALITICS_ID;
} 

else if (window.location.hostname == process.env.MIX_BLOG_URL) {
    homeComponent = Blog;
    serviceRoutes = blog_routes.options ? blog_routes.options.routes : blog_routes;
    let baseUrl = process.env.MIX_APP_SSH.replace(/\/$/, '');
    let blogUrl = (process.env.MIX_BLOG_URL || '').replace(/^\/|\/$/g, '');
    axios.defaults.baseURL = blogUrl ? baseUrl + '/' + blogUrl + '/api' : baseUrl + '/api';

    analytic_id = process.env.MIX_blog_ANALITICS_ID;
} 

else {
    homeComponent = Error;
    serviceRoutes = error_routes.options ? error_routes.options.routes : error_routes;
    // analytic_id = process.env.MIX_CLIMBING_GUIDBOOK_ANALITICS_ID;
}

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
    window.location.hostname == 'blog.climbing.ge'
){
    app.config.devtools = false
    app.config.debug = false
}
else{
    app.config.devtools = true
    app.config.debug = true
}

app.component('home-component', homeComponent);

import { createRouter, createWebHistory } from 'vue-router';

const router = createRouter({
    history: createWebHistory(),
    routes: serviceRoutes,
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

import { getCurrentLocale } from './services/routerUtils.js';

router.beforeEach((to, from, next) => {
    window.scrollTo(0, 0);

    let locale = localStorage.getItem('lang') || 'us';

    // If URL has /ka prefix
    if (to.path.startsWith('/ka')) {
        locale = 'ka';
    } else if (to.path === '/') {
        // Root - use stored or default
    } else {
        // No ka prefix, default us
        locale = 'us';
    }

    localStorage.setItem('lang', locale);
    
    if (i18n.locale !== locale) {
        i18n.locale = locale;
    }

    // Force /ka prefix if locale = 'ka'
    if (locale === 'ka' && !to.path.startsWith('/ka') && to.path !== '/') {
        return next('/ka' + to.path);
    }

    next();
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
            // Check if user is banned
            if (err.response.data && err.response.data.is_banned === true) {
                // Show ban alert
                if (err.response.data.alert) {
                    alert(err.response.data.alert.title + '\n\n' + err.response.data.alert.message);
                } else {
                    alert('Your account has been banned.');
                }
                
                // Clear local storage and close page
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

// Use router
app.use(router);

// Mount the app
app.mount("#app");

// Global lodash
window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {}
