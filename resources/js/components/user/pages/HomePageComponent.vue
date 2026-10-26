<template>
    <div class="cg-dashboard">

        <left-menu />

        <section class="cg-page-head cg-dashboard__hero cg-art-carabiners">
            <span class="cg-eyebrow">{{ $t('global.brand.user') }}</span>
            <h1>{{ $t('user.home.greeting', { name: this.user['name'] || '' }) }}</h1>
            <p>{{ $t('user.home.subtitle') }}</p>
            <div class="cg-dashboard__actions">
                <a class="btn btn-hold btn-sm" :href="guide_url('/outdoor')" target="_blank" rel="noopener"><i class="fa fa-map-o" aria-hidden="true"></i> {{ $t('user.overview.actions.find_crag') }}</a>
                <router-link class="btn btn-light btn-sm" :to="{ name: 'myRouteAscents' }"><i class="fa fa-book" aria-hidden="true"></i> {{ $t('user.overview.actions.logbook') }}</router-link>
                <a class="btn btn-light btn-sm" :href="guide_url('/partners')" target="_blank" rel="noopener"><i class="fa fa-handshake-o" aria-hidden="true"></i> {{ $t('user.overview.actions.partner') }}</a>
                <router-link class="btn btn-light btn-sm" :to="{ name: 'options' }"><i class="fa fa-sliders" aria-hidden="true"></i> {{ $t('user.overview.actions.preferences') }}</router-link>
            </div>
        </section>

        <admin_attention />

        <my_overview />

        <current-user-climber-card>
            <template #extra>
                <a href="#" class="user-follow-summary__all-users" @click.prevent="go_to_all_users()">{{ $t('global.follow.all_users_btn') }}</a>
            </template>
        </current-user-climber-card>

        <h2 class="cg-section-title cg-section-title--left cg-dashboard__title">{{ $t('user.home.quick_links') }}</h2>
        <div class="cg-quick-grid">
            <router-link v-for="link in quick_links" :key="link.route" :to="locale_prefix + link.route" class="cg-quick">
                <span class="cg-quick__icon"><i :class="'fa ' + link.icon" aria-hidden="true"></i></span>
                <span class="cg-quick__label">{{ $t(link.label) }}</span>
                <i class="fa fa-angle-right cg-quick__arrow" aria-hidden="true"></i>
            </router-link>
        </div>

        <!-- each widget with its own permission (mail inbox / database checks) -->
        <div class="row g-3 mb-2" v-if="$can('show', 'mail') || $can('show', 'database')">
            <div class="col-lg-6" v-if="$can('show', 'mail')">
                <mail_notification_widget compact />
                <router-link v-if="$can('show', 'user_notification')" :to="{ name: 'notificationAnalytics' }" class="btn btn-primary btn-sm w-100 mt-2">
                    {{ $t('user.home.view_mail_alerts_btn') }}
                </router-link>
            </div>
            <div class="col-lg-6" v-if="$can('show', 'database')">
                <entity_locale_errors summary />
            </div>
        </div>

        <h2 class="cg-section-title cg-section-title--left cg-dashboard__title">{{ $t('user.home.sites_index') }}</h2>
        <div class="cg-service-tiles">
            <a v-for="item in services" :key="item.key" class="cg-service-tile" :class="'cg-service-' + item.theme" @click="go_to_service(item.key)">
                <span class="cg-service-tile__icon"><i :class="'fa ' + item.icon" aria-hidden="true"></i></span>
                <span class="cg-service-tile__text">
                    <strong>{{ $t(item.label) }}</strong>
                    <small>{{ $t('global.switcher.' + item.theme + '_desc') }}</small>
                </span>
                <i class="fa fa-external-link cg-service-tile__go" aria-hidden="true"></i>
            </a>
        </div>

        <user_notifications_list />

    </div>
</template>

<script>
    import { get_auth_user } from '../../../services/authUser.js'
    import user_notifications_list from '../items/notificatione/UserNotificationsListComponent.vue'
    import mail_notification_widget from '../items/dashboards/MailNotificationWidget.vue'
    import entity_locale_errors from '../items/dashboards/EntityLocaleErrorsComponent.vue'
    import my_overview from '../items/dashboards/MyOverviewComponent.vue'
    import admin_attention from '../items/dashboards/AdminAttentionComponent.vue'

    import CurrentUserClimberCard from '../../global_components/CurrentUserClimberCardComponent.vue'
    import go_to_service_mixin from '../../../mixins/go_to_service_mixin.js'

    export default {
        mixins: [go_to_service_mixin],
        components: {
            user_notifications_list,
            mail_notification_widget,
            entity_locale_errors,
            my_overview,
            admin_attention,
            'current-user-climber-card': CurrentUserClimberCard,
        },
        data(){
            return{
                user: {},
                quick_links: [
                    { route: '/my_trainings', icon: 'fa-heartbeat', label: 'user.menu.my trainings' },
                    { route: '/my_comments_and_reviews', icon: 'fa-comments-o', label: 'user.menu.my comments' },
                    { route: '/favorite_products', icon: 'fa-shopping-bag', label: 'user.menu.favorite products' },
                    { route: '/interested_events', icon: 'fa-calendar', label: 'user.nav.favorites_interested_events' },
                    { route: '/options', icon: 'fa-cog', label: 'user.menu.options' },
                ],
                services: [
                    { key: 'guid', theme: 'guide', icon: 'fa-map-o', label: 'user.home.guidebook' },
                    { key: 'shop', theme: 'shop', icon: 'fa-shopping-bag', label: 'user.home.shop' },
                    { key: 'blog', theme: 'blog', icon: 'fa-newspaper-o', label: 'user.home.blog' },
                    { key: 'summit', theme: 'summit', icon: 'fa-flag', label: 'user.home.summits' },
                ],
            }
        },
        computed: {
            locale_prefix() {
                return this.$i18n.locale === 'ka' ? '/ka' : ''
            },
        },
        mounted(){
            this.get_user_data();
        },
        methods: {
            get_user_data(){
                get_auth_user()
                .then((response)=>{
                    this.user = response.data
                })
            },
            guide_url(path) {
                const prefix = this.$i18n.locale === 'ka' ? '/ka' : ''
                return (process.env.MIX_APP_SSH || 'http://') + process.env.MIX_SITE_URL + prefix + path
            },
            go_to_all_users(){
                const ssh = process.env.MIX_APP_SSH || 'http://'
                const siteHost = process.env.MIX_GUIDBOOK_URL || process.env.MIX_SITE_URL || ''
                window.open(ssh + siteHost + '/climbers')
            },
        }
    }
</script>

<style>
.cg-dashboard__actions { display: flex; flex-wrap: wrap; gap: .5rem; margin-top: .9rem; position: relative; z-index: 1; }
.user-follow-summary__all-users {
    font-size: .95rem;
    font-weight: 700;
    margin-left: auto;
}
</style>
