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
                    component: load("pages/NewsPageComponent"),
                },
                {
                    path: "tech_tip/:url_title",
                    name: "tech_tip",
                    component: load("pages/TechTipPageComponent"),
                },
                {
                    path: "partner/:url_title",
                    name: "partner",
                    component: load("pages/PartnerPageComponent"),
                },
                {
                    path: "local_bisnes/:url_title",
                    name: "local_bisnes",
                    component: load("pages/LocalBisnesPageComponent"),
                },
                {
                    path: "events",
                    name: "events",
                    component: load("lists/EventsListPageComponent"),
                },
                {
                    path: "event/:url_title",
                    name: "event",
                    component: load("pages/EventPageComponent"),
                },
                {
                    path: "ice",
                    name: "ices",
                    component: load("lists/IceListComponent"),
                },
                {
                    path: "ice/:url_title",
                    name: "ice",
                    component: load("pages/IcePageComponent"),
                },
                {
                    path: "spot_projects",
                    name: "spot_projects",
                    component: load("lists/ProjectsList"),
                },
                {
                    path: "spot_project/:url_title",
                    name: "project",
                    component: load("pages/ProjectPage"),
                },
                {
                    path: "special_article/:url_title",
                    name: "special_article",
                    component: load("pages/SpecialAericlePage"),
                },

                {
                    path: "other",
                    name: "others",
                    component: load("lists/OtherListComponent"),
                },
                {
                    path: "other/:url_title",
                    name: "other",
                    component: load("pages/OtherPageComponent"),
                },

                {
                    path: "mountaineering",
                    name: "mountaineerings",
                    component: load("lists/MountaineeringListComponent"),
                },
                {
                    path: "mountaineering/:url_title",
                    name: "mountaineering",
                    component: load("pages/MountaineeringPageComponent"),
                },

                {
                    path: "indoor",
                    name: "indoors",
                    component: load("lists/IndoorListComponent"),
                },
                {
                    path: "indoor/:url_title",
                    name: "indoor",
                    component: load("pages/IndoorPageComponent"),
                },

                {
                    path: "outdoor",
                    name: "outdoors",
                    component: load("lists/OutdoorListComponent"),
                },
                {
                    path: "outdoor/:url_title",
                    name: "outdoor",
                    component: load("pages/OutdoorPageComponent"),
                },

                {
                    path: "search_articles",
                    name: "search_articles",
                    component: load("SerchPageComponent"),
                },

                {
                    path: "unfollow/:id",
                    name: "unfollow",
                    component: load("pages/varification_pages/UnfollowComponent"),
                },

                {
                    path: "/confirm_comment_email/:comment_id/:email",
                    name: "confirm_comment_email",
                    component: load("pages/varification_pages/CommentEmailVerificationePage"),
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
