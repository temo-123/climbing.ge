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
                                    <div class="col" v-if="$can('show', 'comment')">
                                        <input type="radio" id="gd_tab_4" :value="3" v-model="tab_num">
                                        <label for="gd_tab_4">{{ $t('admin.community.tab') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3" v-if="tab_num === 1">
                            <div class="col-md-12">
                                <admin_attention :blocks="['guide', 'moderation']" :title="$t('admin.overview.guide_title')" />
                                <data_counts_list />
                            </div>
                        </div>

                        <div class="row mt-3" v-if="tab_num === 2">
                            <div class="col-md-12">
                                <donation_list />
                            </div>
                        </div>

                        <div class="row mt-3" v-if="tab_num === 3">
                            <div class="col-md-12">
                                <community_moderation />
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
    import community_moderation from './assets/CommunityModerationComponent.vue'
    import admin_attention from '../../../items/dashboards/AdminAttentionComponent.vue'

    export default {
        components: {
            breadcrumb,
            data_counts_list,
            donation_list,
            community_moderation,
            admin_attention,
        },
        data() {
            return { tab_num: 1 }
        },
    }
</script>
