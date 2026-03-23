import { createRouter, createWebHistory, createMemoryHistory } from "vue-router";
import { h } from "vue";
import { RouterView } from "vue-router";

function load(component) {
    return () => import(`../components/summit/pages/${component}.vue`)
}

function getLocaleRegex() {
    let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE
    
    return `(${reg})`;
}

const routes = [
    {
        // path: `/:locale(en|)?`,
        path: `/:locale${getLocaleRegex()}?`,
        component: {
            render() {
                return h(RouterView);
            }
        },
        children: [
          { path: "", name: "index", component: load("IndexPage"), meta: { title: 'Site Index' } },
          { path: "/about_us", name: "about_us", component: load("AboutUsComponent"), meta: { title: 'About Us' } },
          { path: "/summit/:url_title", name: "summit.detail", component: load("pages/SummitPage"), meta: { title: 'Summit assents fool list' } },
          { path: "/summits/list", name: "summits.list", component: load("lists/SummitsList"), meta: { title: 'All summits list' } },
        ]
      }
]

const router = createRouter({
    routes,
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

export default router;

