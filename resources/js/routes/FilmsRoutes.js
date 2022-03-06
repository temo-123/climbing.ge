import studia_component from '../components/films/pages/PortalPageComponent.vue'

import NotFound from '../components/errors/404Component.vue'
export default{
    routes: [
        { path: '/studia', name: 'studia', component: studia_component },

        { path: '*', name: 'NotFound', component: NotFound }
    ],
    mode: 'history',
};