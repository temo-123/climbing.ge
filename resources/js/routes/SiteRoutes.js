import { createRouter, createWebHistory, createMemoryHistory } from "vue-router";
import { h } from "vue";
import { RouterView } from "vue-router";

import NotFound from "../components/errors/404Component.vue";

import i18n from "../services/localization/i18n";

import UnfollowComponent from "../components/global_components/varification_pages/UnfollowPage.vue";
import CommentEmailVerificationePage from "../components/global_components/varification_pages/CommentEmailVerificationePage.vue";

import { isRouteLoading } from '../store/routeLoader'

function load(component) {
    return () => import(`../components/guide/pages/${component}.vue`);
}

function getLocaleRegex() {
    let reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE
    
    return `(${reg})`;
}

const routes = [
    {
        path: `/:locale${getLocaleRegex()}?`,
        component: {
            render() {
                return h(RouterView);
            }
        },
        children: [
            { path: '', name: "index", component: load("IndexPageComponent"), meta: { title: 'Site Index' } },
            { path: "about_us", name: "about_us", component: load("AboutUsComponent"), meta: { title: 'About Us' } },

            { path: "tech_tip/:url_title", name: "tech_tip", component: load("pages/TechTipPageComponent"), meta: { title: 'Tech Tip' } },
            { path: "partner/:url_title", name: "partner", component: load("pages/PartnerPageComponent"), meta: { title: 'Partner' } },
            { path: "local_bisnes/:url_title", name: "local_bisnes", component: load("pages/LocalBisnesPageComponent"), meta: { title: 'Local Business' } },
                        
            { path: "news/:url_title", name: "news", component: load("pages/NewsPageComponent"), meta: { title: 'News' } },
            { path: "special_article/:url_title", name: "special_article", component: load("pages/SpecialAericlePage"), meta: { title: 'Special Article' } },

            { path: "events", name: "events", component: load("lists/EventsListPageComponent"), meta: { title: 'Events' } },
            { path: "event/:url_title", name: "event", component: load("pages/EventPageComponent"), meta: { title: 'Event' } },

            { path: "ice", name: "ices", component: load("lists/IceListComponent"), meta: { title: 'Ice' } },
            { path: "ice/:url_title", name: "ice", component: load("pages/IcePageComponent"), meta: { title: 'Ice' } },

            { path: "spot_projects", name: "spot_projects", component: load("lists/ProjectsList"), meta: { title: 'Spot Projects' } },
            { path: "spot_project/:url_title", name: "project", component: load("pages/ProjectPage"), meta: { title: 'Project' } },

            { path: "other", name: "others", component: load("lists/OtherListComponent"), meta: { title: 'Others' } },
            { path: "other/:url_title", name: "other", component: load("pages/OtherPageComponent"), meta: { title: 'Other' } },

            { path: "mountaineering", name: "mountaineerings", component: load("lists/MountaineeringListComponent"), meta: { title: 'Mountaineering' } },
            { path: "mountaineering/:url_title", name: "mountaineering", component: load("pages/MountaineeringPageComponent"), meta: { title: 'Mountaineering' } },

            { path: "indoor", name: "indoors", component: load("lists/IndoorListComponent"), meta: { title: 'Indoor' } },
            { path: "indoor/:url_title", name: "indoor", component: load("pages/IndoorPageComponent"), meta: { title: 'Indoor' } },

            { path: "outdoor", name: "outdoors", component: load("lists/OutdoorListComponent"), meta: { title: 'Outdoor' } },
            { path: "outdoor/:url_title", name: "outdoor", component: load("pages/OutdoorPageComponent"), meta: { title: 'Outdoor' } },

            { path: "search_articles", name: "search_articles", component: load("SerchPageComponent"), meta: { title: 'Search Articles' } },

            { path: "unfollow/:id", name: "unfollow", component: UnfollowComponent, meta: { title: 'Unfollow' } },
            { path: "confirm_comment_email/:comment_id/:email", name: "confirm_comment_email", component: CommentEmailVerificationePage, meta: { title: 'Confirm Comment Email' } },

            { path: "*", name: "NotFound", component: NotFound, meta: { title: 'Not Found' } },
        ],
    },
];

const router = createRouter({
    routes,
    history: createWebHistory(),
    scrollBehavior(to, from, savedPosition) {
        return { top: 0 };
    }
});

export default router;

