import VueRouter from 'vue-router'
import Vue from 'vue'

import forum_index from '../components/forum/pages/ForumListPageComponent.vue'
import about_us from '../components/forum/pages/AboutUsComponent.vue'

import login from '../components/auth/LoginComponent.vue'
import register from '../components/auth/RegisterComponent.vue'

import NotFound from '../components/errors/404Component.vue'
const router = new VueRouter({
    routes: [
        { path: '/', name: 'forum_index', component: forum_index },

        { path: '/about_us', name: 'about_forum', component: about_us,},

        { path: '/login', name: 'login', component: login },
        { path: '/register', name: 'register', component: register,},

        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
});


Vue.prototype.$siteData = [];
router.beforeEach((to, from, next)=>{
    axios
    .get('/api/auth_user')
    .then((response)=>{
        Vue.prototype.$authUser = response.data
        localStorage.setItem('user', 'are login')
    })
    .catch(function (error) {
        if (error.request.status === 401) {
            if (localStorage.getItem("user") !== null) {
                localStorage.removeItem('user')
                location.reload();
            }
        }
    });

    next()
})

export default router