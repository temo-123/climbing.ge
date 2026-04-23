<template>
    <div class="row">
        <left-menu />
        <div class="col-sm-12">
            <div class="row">
                <div class="col-md-12">
                    <breadcrumb />
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <div class="tabs">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col">
                                        <input type="radio" id="sp_tab_1" :value="1" v-model="tab_num">
                                        <label for="sp_tab_1">Production Tasks</label>
                                    </div>
                                    <div class="col">
                                        <input type="radio" id="sp_tab_2" :value="2" v-model="tab_num">
                                        <label for="sp_tab_2">Delivery Tasks</label>
                                    </div>
                                    <div class="col">
                                        <input type="radio" id="sp_tab_3" :value="3" v-model="tab_num">
                                        <label for="sp_tab_3">All Shop Tasks</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3" v-if="tab_num === 1">
                            <task_panel ref="prod_panel" global-category="shop" filter-category="production" />
                        </div>
                        <div class="row mt-3" v-if="tab_num === 2">
                            <task_panel ref="del_panel" global-category="shop" filter-category="delivery" />
                        </div>
                        <div class="row mt-3" v-if="tab_num === 3">
                            <task_panel ref="all_panel" global-category="shop" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'
    import task_panel from './assets/WorkerTaskControlComponent.vue'

    export default {
        components: { breadcrumb, task_panel },
        data() {
            return { tab_num: 1 }
        }
    }
</script>
