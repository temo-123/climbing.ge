import { createRouter, createWebHistory } from "vue-router";

function load(component) {
    return () => import(`../components/errors/global_errors/pages/${component}.vue`)
}

const routes = [
    { path: '/', name: 'host_error', component: load('host_error_component') },
];



const router = createRouter({
    routes,
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

export default router;

