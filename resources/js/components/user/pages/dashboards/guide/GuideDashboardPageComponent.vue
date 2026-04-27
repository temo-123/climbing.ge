<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="tabs">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col">
                                        <input type="radio" id="gd_tab_1" :value="1" v-model="tab_num">
                                        <label for="gd_tab_1">Content</label>
                                    </div>
                                    <div class="col">
                                        <input type="radio" id="gd_tab_2" :value="2" v-model="tab_num">
                                        <label for="gd_tab_2">User mails</label>
                                    </div>
                                    <div class="col">
                                        <input type="radio" id="gd_tab_3" :value="3" v-model="tab_num">
                                        <label for="gd_tab_3">Donations</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3" v-if="tab_num === 1">
                            <div class="col-md-12">
                                <data_counts_list />
                            </div>
                        </div>

                        <div class="row mt-3" v-if="tab_num === 2">
                            <div class="col-md-4 mb-3">
                                <mail_notification_widget />
                            </div>
                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <button class="btn btn-primary" @click="open_notify_modal()">Make mail notification for user</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <admin_notifications_list />
                            </div>
                        </div>

                        <div class="row mt-3" v-if="tab_num === 3">
                            <div class="col-md-12">
                                <donation_list />
                            </div>
                        </div>
                    </div>

                    <user_notifications_modal ref="user_notifications" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'
    import user_notifications_modal from '../../../items/modal/notifications/UserMailNotificationModelComponent.vue'
    import admin_notifications_list from '../../../items/notificatione/AdminNotificationsListComponent.vue'
    import data_counts_list from '../../../items/dashboards/SiteDataCountListComponent.vue'
    import mail_notification_widget from '../../../items/dashboards/MailNotificationWidget.vue'
    import donation_list from './assets/DonationListComponent.vue'

    export default {
        components: {
            breadcrumb,
            user_notifications_modal,
            admin_notifications_list,
            data_counts_list,
            mail_notification_widget,
            donation_list,
        },
        data() {
            return { tab_num: 1 }
        },
        methods: {
            open_notify_modal() {
                this.$refs.user_notifications.show_modal()
            }
        }
    }
</script>
