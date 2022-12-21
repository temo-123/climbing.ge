import VueRouter from 'vue-router'

import NotFound from '../components/errors/404Component.vue'

import i18n from '../i18n'

function load(component) {
    return () => import(`../components/site/pages/${component}.vue`)
}

function getLocaleRegex() {
    let reg = ''
    // SUPPORTED_LOCALES.forEach((locale, index) => {
        // reg = `${reg}${locale.code}${index !== SUPPORTED_LOCALES.length - 1 ? '|' : ''}`
        // reg = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE.split(',') - 1 ? '|' : '/'
        reg = 'ka|ru|/'
    // })
    return `(${reg})`
}

const router = new VueRouter({
    routes: [
        { path: "/en", redirect: `/` },
        { 
            path: `/:locale${getLocaleRegex()}?`, 
            // path: `/:locale`,
            component: { 
                render: h => h('router-view')
            },
            beforeEnter:(to, from, next) => {
                to.params.locale = localStorage.getItem('lang',)
                const locale = to.params.locale

                localStorage.setItem('lang', locale)
                
                const supported_locales = process.env.MIX_VUE_APP_I18N_SUPORTED_LOCALE.split(',')

                if (!supported_locales.includes(locale)) {
                    return next('/')
                }
                
                if (i18n.locale !== locale) {
                    i18n.locale = locale
                }

                return next()
            },
            children: [
                { path: '', name: 'index', component: load('IndexPageComponent') },
                { path: 'about_us', name: 'about_us', component: load('AboutUsComponent') },

                { path: 'news/:url_title', name: 'news', component: load('NewsPageComponent') },
                { path: 'event/:url_title', name: 'event', component: load('EventPageComponent') },
                { path: 'tech_tip/:url_title', name: 'tech_tip', component: load('TechTipPageComponent') },
                { path: 'partner/:url_title', name: 'partner', component: load('PartnerPageComponent') },
                { path: 'local_bisnes/:url_title', name: 'local_bisnes', component: load('LocalBisnesPageComponent') },

                { path: 'ice', name: 'ices', component: load('IceListComponent') },
                { path: 'ice/:url_title', name: 'ice', component: load('IcePageComponent') },

                { path: 'other', name: 'others', component: load('OtherListComponent') },
                { path: 'other/:url_title', name: 'other', component: load('OtherPageComponent') },

                { path: 'mountaineering', name: 'mountaineerings', component: load('MountaineeringListComponent') },
                { path: 'mountaineering/:url_title', name: 'mountaineering', component: load('MountaineeringPageComponent') },

                { path: 'indoor', name: 'indoors', component: load('IndoorListComponent') },
                { path: 'indoor/:url_title', name: 'indoor', component: load('IndoorPageComponent') },

                { path: 'outdoor', name: 'outdoors', component: load('OutdoorListComponent') },
                { path: 'outdoor/:url_title', name: 'outdoor', component: load('OutdoorPageComponent'), meta: { title: "FOO" } },

                { path: 'search_articles', name: 'search_articles', component: load('SerchPageComponent') },
            
                { path: '*', name: 'NotFound', component: NotFound }
            ]
        }
    ],
    mode: 'history',
});

router.beforeEach((to, from, next) => {
    if (!to.params.locale) {
        i18n.locale = process.env.MIX_VUE_APP_I18N_LOCALE
        localStorage.setItem('lang', process.env.MIX_VUE_APP_I18N_LOCALE)
    }

    // console.log(to.params.locale, getLocaleRegex(), i18n.lang);

    // axios
    // .get('/api/auth_user')
    // .then((response)=>{
    //     // 
    // })
    // .catch(function (error) {
    //     // 
    // });
    
    next()
});


export default router