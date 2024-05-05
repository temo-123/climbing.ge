import Vue from "vue";

import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
// import './bootstrap.js';
// import '../sass/app.scss';

/*
 *   Using pakets
 */
import CKEditor from "ckeditor4-vue";
import Router from "vue-router";
import VueMeta from "vue-meta";
import Vuex from "vuex";
import axios from "axios";
import i18n from "./services/localization/i18n";
import VueSocialSharing from "vue-social-sharing";
import VueGlide from "vue-glide-js";
// import "vue-glide-js/dist/vue-glide.css";
import VueGtag from "vue-gtag";
import { abilitiesPlugin } from "@casl/vue";
import ability from "./services/ability/ability";
import { ContentLoader } from 'vue-content-loader'

import { createHead } from '@unhead/vue' // https://unhead.unjs.io/setup/vue/installation
import { UnheadPlugin } from '@unhead/vue/vue2'

const head = createHead()
Vue.use(UnheadPlugin)

Vue.use(ContentLoader);
Vue.use(abilitiesPlugin, ability());
Vue.use(VueGlide);
Vue.use(VueSocialSharing);
Vue.use(i18n);
Vue.use(Vuex);
Vue.use(axios);
Vue.use(VueMeta);
Vue.use(CKEditor);
Vue.use(Router);


Vue.use(VueMeta, {
    // optional pluginOptions
    refreshOnceOnNavigation: true
})

/*
 *  Mixins
 */

import { editor_config } from './mixins/editor/editor_config_mixin.js'
import { going } from './mixins/easy_navigation_mixin.js'
import  site_data  from './mixins/site_data_mixin.js'

Vue.mixin(site_data); 
Vue.mixin(editor_config); 
Vue.mixin(going); 


/*
 *   My components
 */
import goTo from "./components/global_components/GoToComponrnt.vue";
Vue.component("goTo", goTo);

import MainWrapper from "./components/shop/MainWrapper.vue";
import Index from "./components/site/IndexComponent.vue";
import Home from "./components/user/HomeComponent.vue";

import shop_routes from "./routes/ShopRoutes";
import site_routes from "./routes/SiteRoutes";
import user_routes from "./routes/UserRoutes";

Vue.component(
    "main-wrapper-component",
    require("./components/shop/MainWrapper.vue").default
);
Vue.component(
    "index-component",
    require("./components/site/IndexComponent.vue").default
);
Vue.component(
    "home-component",
    require("./components/user/HomeComponent.vue").default
);

var serviceRoutes = [];
var homeComponent = [];
var analytic_id = "";

if (window.location.hostname == process.env.MIX_SITE_URL) {
    homeComponent = Index;
    serviceRoutes = site_routes;
    analytic_id = process.env.MIX_CLIMBING_GUIDBOOK_ANALITICS_ID;
    axios.defaults.baseURL = process.env.MIX_APP_SSH + process.env.MIX_SITE_URL + '/api'
} else if (window.location.hostname == process.env.MIX_SHOP_URL) {
    homeComponent = MainWrapper;
    serviceRoutes = shop_routes;
    analytic_id = process.env.MIX_SHOP_ANALITICS_ID;
    axios.defaults.baseURL = process.env.MIX_APP_SSH + process.env.MIX_SHOP_URL + '/api'
} else if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {
    homeComponent = Home;
    serviceRoutes = user_routes;
    analytic_id = process.env.MIX_USER_ANALITICS_ID;
    axios.defaults.baseURL = process.env.MIX_APP_SSH + process.env.MIX_USER_PAGE_URL + '/api'
}
else {
    homeComponent = Error;
    serviceRoutes = error_routes;
    analytic_id = process.env.MIX_CLIMBING_GUIDBOOK_ANALITICS_ID;
}

Vue.use(VueGtag, {
    config: { id: analytic_id },
});

Vue.config.productionTip = false;
Vue.prototype.$siteData = [];
Vue.prototype.$globalSiteData = [];

Vue.prototype.$going = going
Vue.prototype.$editor_config = editor_config
Vue.prototype.$get_site_data = site_data

if(
    window.location.hostname == 'climbing.ge' ||
    window.location.hostname == 'shop.climbing.ge' ||
    window.location.hostname == 'user.climbing.ge' ||
    window.location.hostname == 'films.climbing.ge' ||
    window.location.hostname == 'forum.climbing.ge'
){
    Vue.config.devtools = false
    Vue.config.debug = false
    Vue.config.select = false
}
else{
    Vue.config.devtools = true
}

const app = new Vue({
    el: "#app",
    unhead: head,
    i18n,
    store,
    // ability,

    // option,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    components: {
        homeComponent,
    },
    router: serviceRoutes,

    mounted() {
        this.get_site_data();
    },
    methods: {
        get_site_data() {
            axios
            .get("/siteData/get_site_locale_data/"+localStorage.getItem('lang'))
            .then((response) => (
                Vue.prototype.$siteData = response.data.locale_data,
                Vue.prototype.$globalSiteData = response.data.global_data
            ));
        },
    },
    watch: {
        $route(to, from) {
            window.scrollTo(0, 0);

            if (window.location.hostname == process.env.MIX_SITE_URL || window.location.hostname == process.env.MIX_SHOP_URL) {

                const firstSegment = location.pathname.split('/')[1];

                let suported_locales = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE.split("|")
                suported_locales.forEach(s_locale => {
                    if(s_locale == firstSegment && s_locale != this.$route.params.locale){
                        localStorage.setItem('lang', s_locale)
                    }
                });

                let locale = localStorage.getItem("lang")
                localStorage.setItem('lang', locale)
                this.$i18n.locale = locale;

                if(locale == 'en'){
                    // let activ_path_without_locale = this.$router.history.pending.path.split("/").splice(2).join("/")
                    // this.$router.push({ path: '/' + activ_path_without_locale })
                }
                else{
                    const to = this.$router.resolve({params: {locale}})
                    this.$router.push(to.location)
                }
                
            }
        },
    },
});
