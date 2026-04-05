import { createRouter, createWebHistory } from "vue-router"; import { h } from "vue"; import { RouterView } from "vue-router";

function getLocaleRegex() { let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE; return `(${reg})`; } function load(component) {
    return () => import(`../components/errors/global_errors/pages/${component}.vue`)
}

const routes = [{ path: `/:locale${getLocaleRegex()}?`, component: { render() { return h(RouterView); } }, children: [
{ path: '', name: 'host_error', component: load('host_error_component') },
] } ];



const router = createRouter({
    routes,
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

export default router;

