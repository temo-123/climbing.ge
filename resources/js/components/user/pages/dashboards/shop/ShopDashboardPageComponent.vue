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
                                        
                                        <label for="1" >Workers tasks</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="2" :value="2" v-model="tab_num">
                                        
                                        <label for="2" >Orders (beta)</label>
                                    </div>
                                    <div class="col" >
                                        <input type="radio" id="3" :value="3" v-model="tab_num">
                                        
                                        <label for="3" >Workers</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 1">
                            <workers_tasks_panel />
                        </div>

                        <div class="row" v-if="tab_num == 2">
                            <order_list />
                        </div>

                        <div class="row" v-if="tab_num == 3">
                            <!-- <worker_list /> -->
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'

    import order_list from '../../../items/dashboards/OrderDiogramComponent.vue'

    import workers_tasks_panel from './assets/WorkerTaskControlComponent.vue'
    // import worker_list from './WorkerListComponent.vue'

    export default {
        components: {
            breadcrumb,

            order_list,
            workers_tasks_panel,
            // worker_list,
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