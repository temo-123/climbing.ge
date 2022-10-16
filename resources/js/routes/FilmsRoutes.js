import VueRouter from 'vue-router'

function load(component) {
    return () => import(`../components/films/pages/${component}.vue`)
}

import NotFound from '../components/errors/404Component.vue'
const router = new VueRouter({
    routes: [
        { path: '/', name: 'studia', component: load('PortalPageComponent') },
        
        { path: '/film/:url_title', name: 'film', component: load('FilmPageComponent') },

        { path: '/about_us', name: 'about_films', component: load('AboutUsComponent'),},

        { path: 'search_films', name: 'search_films', component: load('SerchPageComponent') },

        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
});

export default router