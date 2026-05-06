import { createApp } from "vue";
import "./bootstrap";
import { createHead } from "@unhead/vue/client";

const app = createApp({});
const head = createHead();
app.use(head);

import { createPinia } from "pinia";
app.use(createPinia());

import i18n from "./services/localization/i18n";
app.use(i18n);

import big_editor from './components/user/items/form/parts/editor/BigEditorComponent.vue'
app.component("big_editor", big_editor);

import small_editor from './components/user/items/form/parts/editor/SmallEditorComponent.vue'
app.component("small_editor", small_editor);

import mini_editor from './components/user/items/form/parts/editor/MiniEditorComponent.vue'
app.component("mini_editor", mini_editor);

import { abilityDefaults, updateAbility } from "./services/ability/ability.js"
import { abilitiesPlugin } from '@casl/vue'
app.use(abilitiesPlugin, abilityDefaults, { useGlobalProperties: true })

import { useAuthStore } from "./store/auth.js"

import { going } from './mixins/easy_navigation_mixin.js';
app.config.globalProperties.$going = going;
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

import SkeletonLoader from "./components/global_components/SkeletonLoaderComponent.vue"
app.component('skeleton-loader', SkeletonLoader)

import ToastNotification from "./components/global_components/ToastNotificationComponent.vue"
app.component('toast-notification', ToastNotification)

import goToAdminPage from "./components/global_components/GoToComponrnt.vue";
app.component("goToAdminPage", goToAdminPage);

import NavBadges from "./components/global_components/NavBadgesComponent.vue";
app.component("nav-badges", NavBadges);

import NavbarSearch from "./components/global_components/NavbarSearchComponent.vue";
app.component("navbar-search", NavbarSearch);

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
    app.component("site-img", shop_img);

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
    app.component("site-img", guide_img);

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
app.config.globalProperties.$siteData = { data: [], loaded: false };
app.config.globalProperties.$globalSiteData = { data: [], loaded: false };

app.mixin({
    methods: {
        $get_site_data() {
            return this.get_site_data;
        },
        get_site_data(force = false) {
            if (!force && this.$siteData.loaded && this.$globalSiteData.loaded) {
                console.log('Site data already loaded, skipping fetch.');
                return;
            }
            
            const lang = localStorage.getItem('lang') || 'us';
            axios
                .get("get_site_data/get_site_locale_data_for_site/" + lang)
                .then((response) => {
                    this.$siteData.data = response.data.locale_data;
                    this.$siteData.loaded = true;
                    this.$globalSiteData.data = response.data.global_data;
                    this.$globalSiteData.loaded = true;
                })
                .catch((error) => {
                    console.error('Failed to load site data:', error);
                });
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

let isFirstNavigation = true;

router.beforeEach((to, from, next) => {
    if (isFirstNavigation) {
        isFirstNavigation = false;
    } else {
        isRouteLoading.value = true;
    }
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

    if (to.name === 'banned') {
        const token = localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token');
        if (token) return next({ name: 'home' });
        if (sessionStorage.getItem('banned_redirect')) {
            sessionStorage.removeItem('banned_redirect');
            return next();
        }
        return next({ name: 'login' });
    }

    next();
});

router.afterEach(() => {
    setTimeout(() => {
        isRouteLoading.value = false;
    }, 300);
});

// ── User subdomain: auth + permission guard ───────────────────────────────────
// The UserRoutes.js beforeEach guards never execute because app.js extracts
// only the routes array and creates a new router. All real guard logic lives here.
if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {
    const userPublicRoutes = [
        'login', 'register', 'forget_pass', 'reset_pass',
        'callback', 'verify', 'create_pass',
    ];

    let authVerified = false;

    document.addEventListener('visibilitychange', () => {
        if (!document.hidden) authVerified = false;
    });

    router.beforeEach(async (to, from, next) => {
        // Banned page access is fully handled by the locale guard above
        if (to.name === 'banned') return next();

        const token = localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token');

        if (!token) {
            authVerified = false;
            if (userPublicRoutes.includes(to.name)) return next();
            return next({ name: 'login', query: { redirect: to.fullPath } });
        }

        // Logged in — bounce away from public/auth pages
        if (userPublicRoutes.includes(to.name)) {
            return next({ name: 'home' });
        }

        // First protected navigation: verify token + load CASL rules
        if (!authVerified) {
            try {
                const response = await axios.get('auth_user');
                updateAbility(response.data.casl_permissions || []);
                authVerified = true;
            } catch (e) {
                if (e.response?.data?.is_banned === true) {
                    sessionStorage.setItem('banned_redirect', '1');
                    return next({ name: 'banned' });
                }
                localStorage.removeItem('auth_token');
                localStorage.removeItem('x_xsrf_token');
                localStorage.removeItem('user_permissions');
                authVerified = false;
                return next({ name: 'login', query: { redirect: to.fullPath } });
            }
        }

        // Block routes the user lacks permission for
        const requiredPermissions = to.meta.permissions;
        if (requiredPermissions?.length) {
            const hasPermission = requiredPermissions.some(([action, subject]) =>
                abilityDefaults.can(action, subject)
            );
            if (!hasPermission) return next({ name: 'home' });
        }

        next();
    });
}
// ─────────────────────────────────────────────────────────────────────────────

function getCsrfCookie() {
    const match = document.cookie.match(/(?:^|; )XSRF-TOKEN=([^;]*)/);
    return match ? decodeURIComponent(match[1]) : null;
}

// Shared state to prevent duplicate auth redirects and serialise CSRF refreshes
let csrfRefreshPromise = null;
let isHandlingAuthError = false;

window.axios.interceptors.request.use(config => {
    // Read XSRF-TOKEN cookie fresh on every request so stale tokens never cause 419s
    const xsrf = getCsrfCookie();
    if (xsrf) {
        config.headers['X-XSRF-TOKEN'] = xsrf;
    }
    const token = localStorage.getItem('auth_token');
    if (token) {
        config.headers['Authorization'] = 'Bearer ' + token;
    }
    return config;
});

window.axios.interceptors.response.use(response => response, async err => {
    if (err && err.response) {
        const status = err.response.status;
        const originalRequest = err.config;

        if (status === 419 && !originalRequest._csrfRetry) {
            // Refresh the CSRF cookie once, then retry the original request.
            // All concurrent 419s share the same refresh promise so we only hit /csrf-cookie once.
            originalRequest._csrfRetry = true;
            if (!csrfRefreshPromise) {
                csrfRefreshPromise = axios.get(window.location.origin + '/sanctum/csrf-cookie').finally(() => {
                    csrfRefreshPromise = null;
                });
            }
            try {
                await csrfRefreshPromise;
                const xsrf = getCsrfCookie();
                if (xsrf) originalRequest.headers['X-XSRF-TOKEN'] = xsrf;
                return axios(originalRequest);
            } catch (e) {
                // CSRF refresh failed — fall through to auth error handling
            }
        }

        if (status === 401 || status === 419) {
            if (!isHandlingAuthError) {
                isHandlingAuthError = true;
                localStorage.removeItem('x_xsrf_token');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_permissions');
                if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {
                    router.push({ name: 'login' });
                }
                setTimeout(() => { isHandlingAuthError = false; }, 3000);
            }
            return Promise.reject(err);
        }
        else if (status === 422) {
            return Promise.reject(err);
        }
        else if (status === 403) {
            if (err.response.data && err.response.data.is_banned === true) {
                localStorage.removeItem('x_xsrf_token');
                localStorage.removeItem('auth_token');
                localStorage.removeItem('user_permissions');
                sessionStorage.setItem('banned_redirect', '1');
                if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {
                    router.push({ name: 'banned' });
                } else {
                    window.location.href = (process.env.MIX_APP_SSH || '') + '/banned';
                }
                return Promise.reject(err);
            }
            else {
                app.config.globalProperties.$bus.$emit('toast', {
                    type: 'warning',
                    message: "You don't have permission to perform this action.",
                });
                return Promise.reject(err);
            }
        }
        else {
            app.config.globalProperties.$bus.$emit('toast', {
                type: 'danger',
                message: "Error " + err.response.status + ': ' + err.response.statusText,
            });
            return Promise.reject(err);
        }
    }
    return Promise.reject(err);
})

app.use(router);

app.mount("#app");

// Auto load site data after app mount - direct global call to avoid multiple
setTimeout(() => {
    const lang = localStorage.getItem('lang') || 'us';
    if (!app.config.globalProperties.$siteData.loaded) {
        axios
            .get("get_site_data/get_site_locale_data_for_site/" + lang)
            .then((response) => {
                app.config.globalProperties.$siteData.data = response.data.locale_data;
                app.config.globalProperties.$siteData.loaded = true;
                app.config.globalProperties.$globalSiteData.data = response.data.global_data;
                app.config.globalProperties.$globalSiteData.loaded = true;
            })
            .catch((error) => {
                console.error('Failed to load site data:', error);
            });
    }
}, 500);

window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {}
