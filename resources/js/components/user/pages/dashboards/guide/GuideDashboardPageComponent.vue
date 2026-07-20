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
                                        <label for="gd_tab_1">{{ $t('admin.dashboards.content_tab') }}</label>
                                    </div>
                                    <div class="col">
                                        <input type="radio" id="gd_tab_3" :value="2" v-model="tab_num">
                                        <label for="gd_tab_3">{{ $t('admin.dashboards.donations_tab') }}</label>
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
                            <div class="col-md-12">
                                <donation_list />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import breadcrumb from '../../../items/BreadcrumbComponent.vue'
    import data_counts_list from '../../../items/dashboards/SiteDataCountListComponent.vue'
    import donation_list from './assets/DonationListComponent.vue'

    export default {
        components: {
            breadcrumb,
            data_counts_list,
            donation_list,
        },
        data() {
            return { tab_num: 1 }
        },
    }
</script>
