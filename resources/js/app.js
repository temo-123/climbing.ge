import Vue from "vue";
require("./bootstrap");

/*
 *   Using pakets
 */
import Carousel3d from "vue-carousel-3d";
import CKEditor from "ckeditor4-vue";
import Router from "vue-router";
import VueMeta from "vue-meta"; // https://www.epiloge.com/how-to-add-dynamic-meta-tags-to-your-vuejs-app-for-google-seo-0fa058
import VueExpandableImage from "vue-expandable-image";
import Vuex from "vuex";
import axios from "axios";
import VueAnalytics from "vue-analytics";
import i18n from "./i18n";
import VueSocialSharing from "vue-social-sharing";
import plugin from "@serializedowen/vue-img-watermark";
import VueGlide from "vue-glide-js";
import "vue-glide-js/dist/vue-glide.css";
// import VueGtag from "vue-gtag";
// import loader from "vue-ui-preloader"; // https://vue-preloader.netlify.app/ (global loader after update pagfe)
// import ContentLoader from 'vue-content-loader' //https://www.npmjs.com/package/vue-content-loader/v/0.2.1 (use 0.2.1 version for vue 2. local block content loader)
import { ContentLoader } from "vue-content-loader";
// import { BootstrapVue } from 'bootstrap-vue'

// Vue.use(BootstrapVue)
Vue.use(ContentLoader);
// Vue.use(loader);
Vue.use(VueGlide);
Vue.use(plugin);
Vue.use(VueSocialSharing);
Vue.use(i18n);
Vue.use(Vuex);
Vue.use(axios);
Vue.use(VueExpandableImage);
Vue.use(VueMeta);
Vue.use(Carousel3d);
Vue.use(CKEditor);
Vue.use(Router);

/*
 *   My components
 */

import leftmenu from "./components/user/items/LeftMenuComponent.vue";
import goTo from "./components/global_components/GoToComponrnt.vue";
import footerAd from "./components/global_components/ad/FooterAdBlockComponent.vue";
import rightAd from "./components/global_components/ad/RightAdBlockComponent.vue";
import store from "./store";
import site_img from "./components/site/items/ImageComponent.vue";
import shop_img from "./components/shop/items/ImageComponent.vue";
import forum_img from "./components/forum/items/ImageComponent.vue";

Vue.component("site-img", site_img);
Vue.component("shop-img", shop_img);
Vue.component("forum-img", forum_img);
Vue.component("left-menu", leftmenu);
Vue.component("goTo", goTo);
Vue.component("footerAd", footerAd);
Vue.component("rightAd", rightAd);

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
    analytic_id = "G-B7KZEJ6CLW";
} else if (window.location.hostname == process.env.MIX_SHOP_URL) {
    homeComponent = MainWrapper;
    serviceRoutes = shop_routes;
} else if (window.location.hostname == process.env.MIX_USER_PAGE_URL) {
    homeComponent = Home;
    serviceRoutes = user_routes;
} else if (window.location.hostname == process.env.MIX_FILMS_URL) {
    homeComponent = Films;
    serviceRoutes = films_routes;
} else if (window.location.hostname == process.env.MIX_FORUM_URL) {
    homeComponent = Forum;
    serviceRoutes = forum_routes;
} else {
    window.location.href = "/404";
}

Vue.use(VueAnalytics, {
    id: "G-B7KZEJ6CLW",
    // serviceRoutes,
    // autoTracking: {
    //   skipSamePath: true,
    //   screenview: true
    // }
});

// Vue.use(VueGtag, {
//   config: { id: "UA-1234567-1" }
// });

// Vue.runtimeCompiler = true;
Vue.config.productionTip = false;
Vue.prototype.$siteData = [];

const app = new Vue({
    el: "#app",
    i18n,
    store,
    // option,
    components: {
        homeComponent,
        // loader,
        // ContentLoader
    },
    router: serviceRoutes,

    mounted() {
        this.get_site_data();
        // i18n.messages.en
        console.log("🚀 ~ file: app.js ~ line 151 ~ mounted ~ i18n.messages.en", i18n.messages.en)
    },
    methods: {
        get_site_data() {
            axios
                .get("../api/siteData")
                .then((response) => (Vue.prototype.$siteData = response.data));
        },
    },
    watch: {
        $route(to, from) {
            this.get_site_data();
            window.scrollTo(0, 0);
        },
    },
});
