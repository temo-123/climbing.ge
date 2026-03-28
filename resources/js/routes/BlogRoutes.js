import { createRouter, createWebHistory, createMemoryHistory } from "vue-router";
import { h } from "vue";
import { RouterView } from "vue-router";

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
        path: `/:locale${getLocaleRegex()}?`,
        component: {
            render() {
                // return h("router-view");
                return h(RouterView);
            }
        },
        children: [
            { path: "", name: "index", component: load("IndexPage"), meta: { title: 'climbing.ge blog' }},
{ path: "about_us", name: "about_us", component: load("AboutUsPage"), meta: { title: 'About This Blog' } },

            { path: "post/:url_title", name: "post", component: load("pages/PostPage"), meta: { title: 'Post' }},
            { path: "news/:url_title", name: "news", component: load("pages/NewsPage"), meta: { title: 'News' }},

            { path: "*", name: "NotFound", component: NotFound,  meta: { title: 'Not Found' }},
        ],
    },
];



const router = createRouter({
    routes,
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

export default router;
