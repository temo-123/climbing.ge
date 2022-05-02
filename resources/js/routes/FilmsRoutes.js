import VueRouter from 'vue-router'

import studia_component from '../components/films/pages/PortalPageComponent.vue'
import about_us from '../components/films/pages/AboutUsComponent.vue'

import login from '../components/auth/LoginComponent.vue'
import register from '../components/auth/RegisterComponent.vue'

import NotFound from '../components/errors/404Component.vue'
const router = new VueRouter({
    routes: [
        { path: '/', name: 'studia', component: studia_component },
        
        // { path: '/film/{url_title}', name: 'film', component: film_page },

        { path: '/about_us', name: 'about_films', component: about_us,},

        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
});

export default router