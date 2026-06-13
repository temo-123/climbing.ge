import { createRouter, createWebHistory } from "vue-router";
import { h } from "vue";
import { RouterView } from "vue-router";

function load(component) {
    return () => import(`../components/films/pages/${component}.vue`)
}

import NotFound from '../components/errors/404Component.vue'

function getLocaleRegex() {
    let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE
    
    return `(${reg})`;
}

const routes = [
{
    path: `/:locale${getLocaleRegex()}?`,
    component: {
        render() {
            return h(RouterView);
        }
    },
    children: [
        { path: '', name: 'studia', component: load('PortalPageComponent') },
        { path: 'film/:url_title', name: 'film', component: load('FilmPageComponent') },
        { path: 'about_us', name: 'about_films', component: load('AboutUsComponent') },
        { path: 'terms_of_use', name: 'films_terms', component: load('TermsOfUsePage') },
        { path: 'search_films', name: 'search_films', component: load('SerchPageComponent') },
        { path: "404",    name: "not-found",    component: NotFound, meta: { title: 'Not Found' } },
        { path: "403",    name: "forbidden",    component: NotFound, meta: { title: 'Forbidden' } },
        { path: "500",    name: "server-error", component: NotFound, meta: { title: 'Server Error' } },
    ]
},
    { path: '/:pathMatch(.*)*', component: NotFound, beforeEnter: () => { window.location.replace('/404'); return false; } },
];

const router = createRouter({
    routes,
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

export default router;
