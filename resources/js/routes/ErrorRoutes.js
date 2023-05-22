import VueRouter from 'vue-router'

function load(component) {
    return () => import(`../components/errors/global_errors/pages/${component}.vue`)
}

const router = new VueRouter({
    routes: [
        { path: '/', name: 'host_error', component: load('host_error_component') },
    ],
    mode: 'history',
});

export default router