<template>
    <div class="row">
        
        <left-menu />

        <div class="col-sm-12">

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="alert alert-success" role="alert">
                        <h1>{{ $t('user.home.greeting', { name: this.user['name'] }) }}</h1>
                    </div>
                </div>
            </div>

            <!-- <servicesListComponent /> -->

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <current-user-climber-card>
                        <template #extra>
                            <a href="#" class="user-follow-summary__all-users" @click.prevent="go_to_all_users()">{{ $t('global.follow.all_users_btn') }}</a>
                        </template>
                    </current-user-climber-card>
                </div>
            </div>

            <div class="row justify-content-center" v-if="$can('show', 'mail')">
                <div class="col-md-6 mb-3">
                    <mail_notification_widget compact />
                    <router-link :to="{ name: 'notificationAnalytics' }" class="btn btn-primary btn-sm w-100 mt-2">
                        {{ $t('user.home.view_mail_alerts_btn') }}
                    </router-link>
                </div>
                <div class="col-md-6 mb-3">
                    <entity_locale_errors summary />
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">{{ $t('user.home.sites_index') }}</div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6 col-md-6 col-lg-3 text-center">
                                    <button class="btn btn_service_guid" @click="go_to_service('guid')">{{ $t('user.home.guidebook') }}</button>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-3 text-center">
                                    <button class="btn btn_service_shop" @click="go_to_service('shop')">{{ $t('user.home.shop') }}</button>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-3 text-center">
                                    <button class="btn btn_service_blog" @click="go_to_service('blog')">{{ $t('user.home.blog') }}</button>
                                </div>
                                <div class="col-sm-6 col-md-6 col-lg-3 text-center">
                                    <button class="btn btn_service_summit" @click="go_to_service('summit')">{{ $t('user.home.summits') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <user_notifications_list />

        </div>
    </div>
</template>

<script>
    import user_notifications_list from '../items/notificatione/UserNotificationsListComponent.vue'
    import mail_notification_widget from '../items/dashboards/MailNotificationWidget.vue'
    import entity_locale_errors from '../items/dashboards/EntityLocaleErrorsComponent.vue'

    import servicesListComponent from '../../global_components/ServicesListComponent.vue'
    import CurrentUserClimberCard from '../../global_components/CurrentUserClimberCardComponent.vue'
    import go_to_service_mixin from '../../../mixins/go_to_service_mixin.js'

    export default {
        mixins: [go_to_service_mixin],
        components: {
            user_notifications_list,
            mail_notification_widget,
            entity_locale_errors,
            servicesListComponent,
            'current-user-climber-card': CurrentUserClimberCard,
        },
        data(){
            return{
                user: {},
            }
        },
        mounted(){
            this.get_user_data();
        },
        methods: {
            get_user_data(){
                axios
                .get('/auth_user')
                .then((response)=>{
                    this.user = response.data
                })
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
.btn_service_guid, .btn_service_shop, .btn_service_blog, .btn_service_summit{
    margin: 2%;
}
.btn_service_guid{
    background-color: #279fbb;
}
.btn_service_shop{
    background-color: #27bb7d;
}
.btn_service_blog{
    background-color: #777777;
}
.btn_service_summit{
    background-color: #ff6b6b;
}
.user-follow-summary__all-users {
    font-size: .95rem;
    margin-left: auto;
}
</style>