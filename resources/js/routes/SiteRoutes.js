import index_component from '../components/site/pages/IndexPageComponent.vue'
import about_us from '../components/site/pages/AboutUsComponent.vue'

import news_page from '../components/site/pages/NewsPageComponent.vue'
import event_page from '../components/site/pages/EventPageComponent.vue'
import security_page from '../components/site/pages/SecurityPageComponent.vue'
import partner_page from '../components/site/pages/PartnerPageComponent.vue'

import outdoor_list from '../components/site/pages/OutdoorListComponent.vue'
import outdoor_page from '../components/site/pages/OutdoorPageComponent.vue'

import indoor_list from '../components/site/pages/IndoorListComponent.vue'
import indoor_page from '../components/site/pages/IndoorPageComponent.vue'

import mountaineering_list from '../components/site/pages/MountaineeringListComponent.vue'
import mountaineering_page from '../components/site/pages/MountaineeringPageComponent.vue'

import ice_list from '../components/site/pages/IceListComponent.vue'
import ice_page from '../components/site/pages/IcePageComponent.vue'

import other_list from '../components/site/pages/OtherListComponent.vue'
import other_page from '../components/site/pages/OtherPageComponent.vue'

import serch_page from '../components/site/pages/SerchPageComponent.vue'

import NotFound from '../components/errors/404Component.vue'
export default{
    routes: [
        { path: '/', name: 'index', component: index_component },
        { path: '/about_us', name: 'about_us', component: about_us,},

        { path: '/news/:url_title', name: 'news', component: news_page,},
        { path: '/event/:url_title', name: 'event', component: event_page,},
        { path: '/security/:url_title', name: 'security', component: security_page,},
        { path: '/partner/:url_title', name: 'partner', component: partner_page,},

        { path: '/ice', name: 'ices', component: ice_list,},
        { path: '/ice/:url_title', name: 'ice', component: ice_page,},

        { path: '/other', name: 'others', component: other_list,},
        { path: '/other/:url_title', name: 'other', component: other_page,},

        { path: '/mountaineering', name: 'mountaineerings', component: mountaineering_list,},
        { path: '/mountaineering/:url_title', name: 'mountaineering', component: mountaineering_page,},

        { path: '/indoor', name: 'indoors', component: indoor_list,},
        { path: '/indoor/:url_title', name: 'indoor', component: indoor_page,},
        
        { path: '/outdoor', name: 'outdoors', component: outdoor_list,},
        { path: '/outdoor/:url_title', name: 'outdoor', component: outdoor_page,},

        { path: '/search_articles', name: 'search_articles', component: serch_page,},
        
        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
};