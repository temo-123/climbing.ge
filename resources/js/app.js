import Vue from "vue";
require("./bootstrap");

/*
 *   Using pakets
 */
// import Carousel3d from "vue-carousel-3d";
// import CKEditor from '@ckeditor/ckeditor5-vue2'; //dont working for updating
import CKEditor from "ckeditor4-vue";
import Router from "vue-router";
import VueMeta from "vue-meta"; // https://www.epiloge.com/how-to-add-dynamic-meta-tags-to-your-vuejs-app-for-google-seo-0fa058
import VueExpandableImage from "vue-expandable-image";
import Vuex from "vuex";
import axios from "axios";
import i18n from "./services/localization/i18n";
import VueSocialSharing from "vue-social-sharing";
import plugin from "@serializedowen/vue-img-watermark";
import VueGlide from "vue-glide-js";
import "vue-glide-js/dist/vue-glide.css";
import VueGtag from "vue-gtag";
import { abilitiesPlugin } from "@casl/vue";
import ability from "./services/ability/ability";

// import FileManager from 'laravel-file-manager'

// Vue.use(Vuex);

// create Vuex store, if you don't have it
// const store = new Vuex.Store();

// Vue.use(FileManager, {store});

Vue.use(abilitiesPlugin, ability());
Vue.use(VueGlide);
Vue.use(plugin);
Vue.use(VueSocialSharing);
Vue.use(i18n);
Vue.use(Vuex);
Vue.use(axios);
Vue.use(VueExpandableImage);
Vue.use(VueMeta);
// Vue.use(Carousel3d);
Vue.use(CKEditor);
Vue.use(Router);

/*
 *   My components
 */

import leftmenu from "./components/user/items/navbars/LeftMenuComponent.vue";
import goTo from "./components/global_components/GoToComponrnt.vue";
import store from "./store";
import site_img from "./components/site/items/ImageComponent.vue";
import shop_img from "./components/shop/items/ImageComponent.vue";
import forum_img from "./components/forum/items/ImageComponent.vue";

Vue.component("site-img", site_img);
Vue.component("shop-img", shop_img);
Vue.component("forum-img", forum_img);
Vue.component("left-menu", leftmenu);
Vue.component("goTo", goTo);

import MainWrapper from "./components/shop/MainWrapperComponent.vue";
import Index from "./components/site/IndexComponent.vue";
import Home from "./components/user/HomeComponent.vue";
import Films from "./components/films/StudiaComponent.vue";
import Forum from "./components/forum/ForumComponent.vue";

import shop_routes from "./routes/ShopRoutes";
import site_routes from "./routes/SiteRoutes";
import user_routes from "./routes/UserRoutes";
import films_routes from "./routes/FilmsRoutes";
import forum_routes from "./routes/ForumRoutes";

Vue.component(
    "main-wrapper-component",
    require("./components/shop/MainWrapperComponent.vue").default
);
Vue.component(
    "index-component",
    require("./components/site/IndexComponent.vue").default
);
Vue.component(
    "home-component",
    require("./components/user/HomeComponent.vue").default
);
Vue.component(
    "studia-component",
    require("./components/films/StudiaComponent.vue").default
);
Vue.component(
    "forum-component",
    require("./components/forum/ForumComponent.vue").default
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
} else if (window.location.hostname == process.env.MIX_FILMS_URL) {
    homeComponent = Films;
    serviceRoutes = films_routes;
    analytic_id = process.env.MIX_FILMS_ANALITICS_ID;
    // axios.defaults.baseURL = process.env.MIX_FILMS_URL
} else if (window.location.hostname == process.env.MIX_FORUM_URL) {
    homeComponent = Forum;
    serviceRoutes = forum_routes;
    analytic_id = process.env.MIX_FORUM_ANALITICS_ID;
    // axios.defaults.baseURL = process.env.MIX_FORUM_URL
} else {
    window.location.href = "/404";
}

Vue.use(VueGtag, {
    config: { id: analytic_id },
});

Vue.config.productionTip = false;
Vue.prototype.$siteData = [];
Vue.prototype.$globalSiteData = [];

if(
    window.location.hostname == 'climbing.ge' &&
    window.location.hostname == 'shop.climbing.ge' &&
    window.location.hostname == 'user.climbing.ge' &&
    window.location.hostname == 'films.climbing.ge' &&
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
    i18n,
    store,
    // ability,

    // option,
    components: {
        homeComponent,
    },
    router: serviceRoutes,

    mounted() {
        this.get_site_data();
        // this.get_auth_user_data()
    },
    methods: {
        get_site_data() {
            axios
            .get("../../../../api/siteData/get_site_locale_data/"+localStorage.getItem('lang'))
            .then((response) => (
                Vue.prototype.$siteData = response.data.locale_data,
                Vue.prototype.$globalSiteData = response.data.global_data
            ));

        },
    },
    watch: {
        $route(to, from) {

            window.scrollTo(0, 0);

            let locale = localStorage.getItem("lang")
            this.get_site_data();

            if (window.location.hostname == process.env.MIX_SITE_URL || window.location.hostname == process.env.MIX_SHOP_URL) {
                if(locale != 'en'){
                    localStorage.setItem('lang', locale)
                    this.$i18n.locale = locale;

                    const to = this.$router.resolve({params: {locale}})
                    this.$router.push(to.location)
                }
                else if(locale == 'en'){
                    localStorage.setItem('lang', locale)
                    this.$i18n.locale = locale;

                    let activ_path_without_locale = this.$router.history.pending.path.split("/").splice(2).join("/")

                    this.get_site_data();

                    this.$router.push({ path: '/' + activ_path_without_locale })
                }
            }
            
        },
    },
});
