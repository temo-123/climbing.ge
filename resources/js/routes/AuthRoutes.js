import login from '../components/auth/LoginComponent.vue'
import register from '../components/auth/RegisterComponent.vue'
export default{
    routes: [
        { path: '/login', name: 'login', component: login },
        { path: '/register', name: 'register', component: register,},
    ],
    mode: 'history',
};