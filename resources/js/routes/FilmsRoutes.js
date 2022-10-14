import VueRouter from 'vue-router'

// import studia_component from '../components/films/pages/PortalPageComponent.vue'
// import film_page from '../components/films/pages/FilmPageComponent.vue'
// import about_us from '../components/films/pages/AboutUsComponent.vue'

function load(component) {
    return () => import(`../components/films/pages/${component}.vue`)
}

import NotFound from '../components/errors/404Component.vue'
const router = new VueRouter({
    routes: [
        // { path: '/', name: 'studia', component: studia_component },
        
        // { path: '/film/:url_title', name: 'film', component: film_page },

        // { path: '/about_us', name: 'about_films', component: about_us,},
        { path: '/', name: 'studia', component: load('PortalPageComponent') },
        
        { path: '/film/:url_title', name: 'film', component: load('FilmPageComponent') },

        { path: '/about_us', name: 'about_films', component: load('AboutUsComponent'),},

        { path: 'search_films', name: 'search_films', component: load('SerchPageComponent') },

        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
});

export default router