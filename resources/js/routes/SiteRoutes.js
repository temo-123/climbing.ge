import VueRouter from "vue-router";

import NotFound from "../components/errors/404Component.vue";

import i18n from "../services/localization/i18n";

function load(component) {
    return () => import(`../components/site/pages/${component}.vue`);
}

function getLocaleRegex() {
    let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE
    
    return `(${reg})`;
}

const router = new VueRouter({
    routes: [
        // { path: "/en", redirect: `/` },
        {
            path: `/:locale${getLocaleRegex()}?`,
            // path: `/:locale(ka|ru|/)`,
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
                {
                    path: "",
                    name: "index",
                    component: load("IndexPageComponent"),
                },
                {
                    path: "about_us",
                    name: "about_us",
                    component: load("AboutUsComponent"),
                },

                {
                    path: "news/:url_title",
                    name: "news",
                    component: load("NewsPageComponent"),
                },
                {
                    path: "tech_tip/:url_title",
                    name: "tech_tip",
                    component: load("TechTipPageComponent"),
                },
                {
                    path: "partner/:url_title",
                    name: "partner",
                    component: load("PartnerPageComponent"),
                },
                {
                    path: "local_bisnes/:url_title",
                    name: "local_bisnes",
                    component: load("LocalBisnesPageComponent"),
                },
                {
                    path: "events",
                    name: "events",
                    component: load("EventsListPageComponent"),
                },
                {
                    path: "event/:url_title",
                    name: "event",
                    component: load("EventPageComponent"),
                },
                {
                    path: "ice",
                    name: "ices",
                    component: load("IceListComponent"),
                },
                {
                    path: "ice/:url_title",
                    name: "ice",
                    component: load("IcePageComponent"),
                },

                {
                    path: "other",
                    name: "others",
                    component: load("OtherListComponent"),
                },
                {
                    path: "other/:url_title",
                    name: "other",
                    component: load("OtherPageComponent"),
                },

                {
                    path: "mountaineering",
                    name: "mountaineerings",
                    component: load("MountaineeringListComponent"),
                },
                {
                    path: "mountaineering/:url_title",
                    name: "mountaineering",
                    component: load("MountaineeringPageComponent"),
                },

                {
                    path: "indoor",
                    name: "indoors",
                    component: load("IndoorListComponent"),
                },
                {
                    path: "indoor/:url_title",
                    name: "indoor",
                    component: load("IndoorPageComponent"),
                },

                {
                    path: "outdoor",
                    name: "outdoors",
                    component: load("OutdoorListComponent"),
                },
                {
                    path: "outdoor/:url_title",
                    name: "outdoor",
                    component: load("OutdoorPageComponent"),
                },

                {
                    path: "search_articles",
                    name: "search_articles",
                    component: load("SerchPageComponent"),
                },

                {
                    path: "unfollow/:id",
                    name: "unfollow",
                    component: load("UnfollowComponent"),
                },

                {
                    path: "/confirm_comment_email/:comment_id/:email",
                    name: "confirm_comment_email",
                    component: load("CommentEmailVerificationePage"),
                },

                { path: "*", name: "NotFound", component: NotFound },
            ],
        },
    ],
    mode: "history",
});

router.beforeEach((to, from, next) => {
    // if (!to.params.locale) {
    //     i18n.locale = process.env.MIX_VUE_APP_I18N_LOCALE;
    //     localStorage.setItem("lang", process.env.MIX_VUE_APP_I18N_LOCALE);
    // }

    // this.$router.resolve({params: {locale}})
    // console.log("🚀 ~ file: SiteRoutes.js:161 ~ router.beforeEach ~  this.$router.resolve:",  resolve)

    to.params.locale = localStorage.getItem("lang");

    next();
});

export default router;
