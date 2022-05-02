import VueRouter from 'vue-router'

import forum_index from '../components/forum/pages/ForumListPageComponent.vue'
import about_us from '../components/forum/pages/AboutUsComponent.vue'

import login from '../components/auth/LoginComponent.vue'
import register from '../components/auth/RegisterComponent.vue'

import NotFound from '../components/errors/404Component.vue'
const router = new VueRouter({
    routes: [
        { path: '/', name: 'forum', component: forum_index },

        { path: '/about_us', name: 'about_forum', component: about_us,},

        { path: '/login', name: 'login', component: login },
        { path: '/register', name: 'register', component: register,},

        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
});

export default router