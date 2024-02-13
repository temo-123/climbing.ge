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
                                    <div class="col" >
                                        <input type="radio" id="1" :value="1" v-model="tab_num">
                                        
                                        <label for="1" >Tasks</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row" v-if="tab_num == 1">

                            <div class="col-md-12">
                                <div class="alert alert-danger" role="alert">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <strong>Danger!</strong> task 1.
                                        </div>
                                        <div class="col-md-12">
                                            <button class="btn btn-primary float-right" @click="open_add_task_model">Update status</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                    </div>

                </div>
            </div>
        </div>

        <taskStatusModal ref="add_task_modal" @restart="update"/>
    </div>
</template>

<script>
    // import Editor from '../items/canvas/EditorComponent.vue'
    // import { SlickList, SlickItem } from 'vue-slicksort'; //https://github.com/Jexordexan/vue-slicksort
    // import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import breadcrumb from '../items/BreadcrumbComponent.vue'

    import user_notifications_list from '../items/UserNotificationsListComponent.vue'
    import order_list from '../items/dashboards/OrderDiogramComponent.vue'
    import data_counts_list from '../items/dashboards/SiteDataCountListComponent.vue'

    import taskStatusModal from '../items/modal/task/task/TaskStatusModalComponent.vue'

    export default {
        components: {
            // StackModal,
            // SlickItem,
            // SlickList,
            breadcrumb,

            user_notifications_list,
            order_list,
            data_counts_list,

            taskStatusModal
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
            open_add_task_model(){
                this.$refs.add_task_modal.open_task_status_modal()
            },          
        }
    }
</script>

<style scoped>

</style>