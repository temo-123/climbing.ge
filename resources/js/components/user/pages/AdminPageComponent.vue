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
                                        
                                        <label for="1" >Tasks</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="2" :value="2" v-model="tab_num">
                                        
                                        <label for="2" >Orders (beta)</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="3" :value="3" v-model="tab_num">
                                        
                                        <label for="3" >Workers</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="4" :value="4" v-model="tab_num">
                                        
                                        <label for="4" >Content</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="5" :value="5" v-model="tab_num">
                                        
                                        <label for="5" >User mails</label>
                                        <!-- <label for="5" >Notifications and Mails</label> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 1">
                            <tasks_panel />
                        </div>

                        <div class="row" v-if="tab_num == 2">
                            <order_list />
                        </div>

                        <div class="row" v-if="tab_num == 3">
                            <worker_list />
                        </div>

                        <div class="row" v-if="tab_num == 4">
                            <data_counts_list />
                        </div>
                        
                        <div class="row" v-if="tab_num == 5">

                            <div class="col-md-12" >
                                <div class="card">
                                    <div class="card-body">
                                        <button class="btn btn-primary" @click="open_notifay_modal()">Make mail notification for user</button>
                                    </div>
                                </div>
                            </div>

                            <admin_notifications_list />
                        </div>


                    </div>

                    <user_notifications_modal ref="user_notifications"/>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../items/BreadcrumbComponent.vue'

    import user_notifications_modal from './../items/modal/notifications/UserMailNotificationModelComponent.vue'

    import admin_notifications_list from '../items/notificatione/AdminNotificationsListComponent.vue'
    import order_list from '../items/dashboards/OrderDiogramComponent.vue'
    import data_counts_list from '../items/dashboards/SiteDataCountListComponent.vue'

    import tasks_panel from './worker/tasks/TasksControlBoardComponent.vue'
    import worker_list from './worker/WorkerListComponent.vue'

    export default {
        components: {
            breadcrumb,

            user_notifications_modal,

            admin_notifications_list,
            order_list,
            data_counts_list,
            tasks_panel,
            worker_list,
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