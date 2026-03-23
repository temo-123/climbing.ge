import { createRouter, createWebHistory } from "vue-router";

function load(component) {
    return () => import(`../components/films/pages/${component}.vue`)
}

import NotFound from '../components/errors/404Component.vue'

const routes = [
{ path: `/:locale${getLocaleRegex()}?`, name: 'studia', component: load('PortalPageComponent') },
    
    { path: '/film/:url_title', name: 'film', component: load('FilmPageComponent') },

    { path: '/about_us', name: 'about_films', component: load('AboutUsComponent'),},

    { path: '/search_films', name: 'search_films', component: load('SerchPageComponent') },

    { path: "/:pathMatch(.*)*", name: 'NotFound', component: NotFound }
];


function getLocaleRegex() {
    let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE
    
    return `(${reg})`;
}

const router = createRouter({
    routes,
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

export default router;

