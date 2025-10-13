import VueRouter from "vue-router";

import NotFound from "../components/errors/404Component.vue";

import i18n from "../services/localization/i18n";

function load(component) {
    return () => import(`../components/blog/pages/${component}.vue`);
}

function getLocaleRegex() {
    let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE
    
    return `(${reg})`;
}

const router = new VueRouter({
    routes: [
        {
            path: `/:locale${getLocaleRegex()}?`,
            component: {
                render: (h) => h("router-view"),
            },
            beforeEnter: (to, from, next) => {
                let storage_locale = localStorage.getItem("lang")

                if(storage_locale){
                    to.params.locale = storage_locale;
                }
                else{
                    to.params.locale = 'en';
                }

                const locale = to.params.locale;

                localStorage.setItem("lang", locale);

                const supported_locales = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE.split("|");

                if (!supported_locales.includes(locale)) {
                    return next("/");
                }

                if (i18n.locale !== locale) {
                    i18n.locale = locale;
                }

                return next();
            },
            children: [
                { path: "", name: "index", component: load("IndexPage"),},
                { path: "about_us", name: "about_us", component: load("AboutUsPage"),},

                { path: "post/:url_title", name: "post", component: load("pages/PostPage"),},
                { path: "news/:url_title", name: "news", component: load("pages/NewsPage"),},

                { path: "*", name: "NotFound", component: NotFound },
            ],
        },
    ],
    mode: "history",
});

router.beforeEach((to, from, next) => {
    to.params.locale = localStorage.getItem("lang");

    next();
});

export default router;
