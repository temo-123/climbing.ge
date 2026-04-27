<template>
    <div class="row">
        <!-- <div class="col-sm-3"> -->
            <left-menu />
        <!-- </div> -->
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
                                        <input type="radio" id="1" :value="1" v-model="tab_num">
                                        
                                        <label for="1" >Team members tasks</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="4" :value="4" v-model="tab_num">
                                        
                                        <label for="4" >Content</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="5" :value="5" v-model="tab_num">

                                        <label for="5" >User mails</label>
                                    </div>
                                    <div class="col">
                                        <input type="radio" id="6" :value="6" v-model="tab_num">
                                        <label for="6">Donations</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 1">
                            <tam_members_tasks_panel />
                        </div>

                        <div class="row" v-if="tab_num == 4">
                            <data_counts_list />
                        </div>
                        
                        <div class="row" v-if="tab_num == 5">

                            <div class="col-md-4 mb-3">
                                <mail_notification_widget />
                            </div>

                            <div class="col-md-8">
                                <div class="card">
                                    <div class="card-body">
                                        <button class="btn btn-primary" @click="open_notifay_modal()">Make mail notification for user</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <admin_notifications_list />
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 6">
                            <donation_list />
                        </div>
                    </div>

                    <user_notifications_modal ref="user_notifications"/>

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

    import tam_members_tasks_panel from './assets/TeamMembersTaskControlComponent.vue'
    import mail_notification_widget from '../../../items/dashboards/MailNotificationWidget.vue'
    import donation_list from '../../donations/DonationListPageComponent.vue'

    export default {
        components: {
            breadcrumb,

            user_notifications_modal,

            admin_notifications_list,
            data_counts_list,
            tam_members_tasks_panel,
            mail_notification_widget,
            donation_list,
        },
        data(){
            return{
                tab_num: 1,
            }
        },
        mounted(){
            // 
        },
        methods: {
            open_notifay_modal(){
                this.$refs.user_notifications.show_modal()
            }
        }
    }
</script>

<style scoped>

</style>