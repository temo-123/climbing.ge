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
                                        <input type="radio" id="dash_tab_1" :value="1" v-model="tab_num">
                                        <label for="dash_tab_1">Order Statistics</label>
                                    </div>
                                    <div class="col">
                                        <input type="radio" id="dash_tab_2" :value="2" v-model="tab_num">
                                        <label for="dash_tab_2">Worker Tasks</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3" v-if="tab_num === 1">
                            <order_diagram />
                        </div>

                        <div class="row mt-3" v-if="tab_num === 2">
                            <worker_tasks global-category="shop" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'
    import order_diagram from '../../../items/dashboards/OrderDiogramComponent.vue'
    import worker_tasks from './assets/WorkerTaskControlComponent.vue'

    export default {
        components: { breadcrumb, order_diagram, worker_tasks },
        data() {
            return { tab_num: 1 }
        }
    }
</script>
