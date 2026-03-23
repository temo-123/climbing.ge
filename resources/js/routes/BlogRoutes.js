import { createRouter, createWebHistory } from "vue-router";
import { h } from "vue";

import NotFound from "../components/errors/404Component.vue";

import i18n from "../services/localization/i18n";

function load(component) {
    return () => import(`../components/blog/pages/${component}.vue`);
}

function getLocaleRegex() {
    let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE
    
    return `(${reg})`;
}

const routes = [
    {
        path: '/',
        component: {
            render() {
                return h("router-view");
            }
        },
        children: [
            { path: "", name: "index", component: load("IndexPage"),},
            { path: "about_us", name: "about_us", component: load("AboutUsPage"),},

            { path: "post/:url_title", name: "post", component: load("pages/PostPage"),},
            { path: "news/:url_title", name: "news", component: load("pages/NewsPage"),},

            { path: ":pathMatch(.*)*", name: "NotFound", component: NotFound },
        ],
    },
];

export default routes;
