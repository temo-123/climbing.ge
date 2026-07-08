import { createApp, reactive } from "vue";
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
window.$bus = app.config.globalProperties.$bus;

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

import CanvasJsonDataShow from "./components/global_components/CanvasJsonDataShowComponent.vue";
app.component("canvas-json-show", CanvasJsonDataShow);

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

if (window.location.hostname == process.env.MIX_SITE_URL) {
    app.component("index-component", Index);
    app.component("site-img", guide_img);

    homeComponent = Index;
    serviceRoutes = site_routes.options ? site_routes.options.routes : site_routes;
    axios.defaults.baseURL = window.location.origin + '/api';
}
else if (window.location.hostname == process.env.MIX_SHOP_URL) {
    app.component("main-wrapper-component", MainWrapper);
    app.component("shop-img", shop_img);
    app.component("site-img", shop_img);

    homeComponent = MainWrapper;
    serviceRoutes = shop_routes.options ? shop_routes.options.routes : shop_routes;
    axios.defaults.baseURL = window.location.origin + '/api';
}
else if (window.location.hostname == process.env.MIX_SUMMIT_URL) {
    app.component("summit-component", Summit);

    homeComponent = Summit;
    serviceRoutes = summit_routes.options ? summit_routes.options.routes : summit_routes;
    axios.defaults.baseURL = window.location.origin + '/api';
}
else if (window.location.hostname == process.env.MIX_FILMS_URL) {
    app.component("studia-component", Films);

    homeComponent = Films;
    serviceRoutes = films_routes.options ? films_routes.options.routes : films_routes;
    axios.defaults.baseURL = window.location.origin + '/api';
}
else if (window.location.hostname == process.env.MIX_BLOG_URL) {
    app.component("blog-component", Blog);

    homeComponent = Blog;
    serviceRoutes = blog_routes.options ? blog_routes.options.routes : blog_routes;
    axios.defaults.baseURL = window.location.origin + '/api';
}
else if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {

    app.component("left-menu", leftmenu);
    app.component("validator_alerts_component", validator_alerts_component);
    app.component("GlobalInfoFormBlock", GlobalInfoFormBlock);
    app.component("site-img", guide_img);

    app.component('home-component', Home);

    homeComponent = Home;
    serviceRoutes = user_routes.options ? user_routes.options.routes : user_routes;
    axios.defaults.baseURL = window.location.origin + '/api';
}
else {
    homeComponent = Error;
    serviceRoutes = error_routes.options ? error_routes.options.routes : error_routes;
}

// The GA tag ID is read from a server-rendered <meta> tag (set from config('services.analytics.*'),
// itself backed by MIX_*_ANALITICS_ID in .env) rather than process.env.MIX_*_ANALITICS_ID directly.
// Those MIX_ vars get baked into this bundle at `npm run build` time, so updating .env on the server
// without rebuilding assets would otherwise never take effect — reading it from the DOM at runtime
// means changing .env and reloading the page is enough, no rebuild/redeploy required.
const analytic_id = document.querySelector('meta[name="ga-tag-id"]')?.content;

import { createGtag } from "vue-gtag";
if (analytic_id) {
    app.use(createGtag({ tagId: analytic_id }));
}

app.config.productionTip = false;
app.config.globalProperties.$siteData = reactive({ data: [], loaded: false });
app.config.globalProperties.$globalSiteData = reactive({ data: [], loaded: false });

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
window.router = router;

import { getCurrentLocale } from './services/routerUtils.js';

let isFirstNavigation = true;

router.beforeEach((to, from, next) => {
    if (window.__imageOpen) {
        window.__imageOpen = false;
        window.dispatchEvent(new CustomEvent('imageclose'));
        return next(false);
    }

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

    const i18nLocale = locale === 'us' ? 'en' : locale;
    if (i18n.global.locale.value !== i18nLocale) {
        i18n.global.locale.value = i18nLocale;
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

        let token = localStorage.getItem('auth_token') || localStorage.getItem('x_xsrf_token');

        // No local token — try to recover auth via the shared Sanctum session cookie.
        // This handles the case where the user logged in on another subdomain
        // (climbing.ge, shop.climbing.ge, etc.) whose localStorage we can't access.
        // _tokenRecovery flag tells the 401 interceptor not to handle this error.
        if (!token && !userPublicRoutes.includes(to.name)) {
            authVerified = false;
            try {
                const raw = await axios.get('token', { _tokenRecovery: true });
                const newToken = String(raw.data).trim();
                if (newToken) {
                    localStorage.setItem('auth_token', newToken);
                    token = newToken;
                }
            } catch {
                // No valid session cookie either — fall through to login redirect
            }
        }

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

// CSRF/auth-token attachment and global error-toast handling (401/419/422/403,
// plus 500/429/etc.) now live in bootstrap.js's axios interceptors, which reach
// this app instance via window.$bus / window.router (see assignments above).

app.use(router);

app.mount("#app");

// Auto load site data immediately after app mount
{
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
}

window._ = require('lodash');

try {
    require('bootstrap');
} catch (e) {}
