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

            <div class="row justify-content-center" v-if="user.id">
                <div class="col-md-12">
                    <div class="user-follow-summary">
                        <climber-card :user="my_card" @open="open_my_profile_modal" class="user-follow-summary__card" />

                        <div class="user-follow-summary__counts">
                            <span><strong>{{ follow_counts.followers_count }}</strong> {{ $t('global.follow.followers_label') }}</span>
                            <span><strong>{{ follow_counts.following_count }}</strong> {{ $t('global.follow.following_label') }}</span>
                            <a href="#" class="user-follow-summary__all-users" @click.prevent="go_to_all_users()">{{ $t('global.follow.all_users_btn') }}</a>
                        </div>
                    </div>

                    <climber-profile-modal ref="profile_modal" />
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
    import ClimberCard from '../../global_components/ClimberCardComponent.vue'
    import ClimberProfileModal from '../../guide/items/climber/ClimberProfileModalComponent.vue'
    import go_to_service_mixin from '../../../mixins/go_to_service_mixin.js'

    export default {
        mixins: [go_to_service_mixin],
        components: {
            user_notifications_list,
            mail_notification_widget,
            entity_locale_errors,
            servicesListComponent,
            'climber-card': ClimberCard,
            'climber-profile-modal': ClimberProfileModal,
        },
        data(){
            return{
                user: {},
                follow_counts: {
                    followers_count: 0,
                    following_count: 0,
                },
            }
        },
        computed: {
            my_card(){
                return {
                    id: this.user.id,
                    name: this.user.name,
                    surname: this.user.surname,
                    image: this.user.image,
                    followers_count: this.follow_counts.followers_count,
                };
            },
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
                    this.get_follow_counts()
                })
            },
            get_follow_counts(){
                axios
                .get('get_climber_profile/' + this.user.id)
                .then((response)=>{
                    this.follow_counts.followers_count = response.data.followers_count
                    this.follow_counts.following_count = response.data.following_count
                })
                .catch(() => {})
            },
            go_to_all_users(){
                const ssh = process.env.MIX_APP_SSH || 'http://'
                const siteHost = process.env.MIX_GUIDBOOK_URL || process.env.MIX_SITE_URL || ''
                window.open(ssh + siteHost + '/climbers')
            },
            open_my_profile_modal(userId){
                this.$refs.profile_modal.show_modal(userId)
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
.user-follow-summary {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 8px 4px 16px;
}
.user-follow-summary__card {
    flex: 0 0 auto;
}
.user-follow-summary__counts {
    display: flex;
    align-items: center;
    gap: 16px;
    flex: 1 1 auto;
}
.user-follow-summary__all-users {
    font-size: .95rem;
    margin-left: auto;
}
</style>