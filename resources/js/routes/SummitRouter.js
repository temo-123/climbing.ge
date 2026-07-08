import { createRouter, createWebHistory, createMemoryHistory } from 'vue-router';
import { h } from 'vue';
import { RouterView } from 'vue-router';

import NotFound from '../components/errors/404Component.vue';

function load(component) {
    return () => import(`../components/summit/pages/${component}.vue`)
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
                return h(RouterView);
            }
        },
        children: [
            { path: '', name: 'index', component: load('IndexPage'), meta: { title: 'Site Index' } },
            { path: 'about_us', name: 'about_us', component: load('AboutUsPage'), meta: { title: 'About Us' } },
            { path: 'terms_of_use', name: 'summit_terms', component: load('TermsOfUsePage'), meta: { title: 'Terms of Use' } },
            { path: 'make_ascent/:id', name: 'make_ascent', component: load('MakeSummitAscentPage'), meta: { title: 'Add Summit' } },

            { path: 'summit/:url_title', name: 'summit', component: load('pages/SummitPage'), meta: { title: 'Summit Detail' } },

            { path: 'summits/list', name: 'summit_list', component: load('lists/SummitListPage'), meta: { title: 'Summit List' } },

            { path: 'summits/map', name: 'summit_map', component: load('SummitMapPage'), meta: { title: 'Summit Map' } },

            { path: 'search', name: 'search_summits', component: load('SearchPageComponent'), meta: { title: 'Search Summits' } },

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

