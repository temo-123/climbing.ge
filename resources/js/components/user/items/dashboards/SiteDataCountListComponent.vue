<template>
    <div class="site-data-dashboard">

        <div class="dashboard-toolbar">
            <div>
                <h1 class="dashboard-toolbar__title">{{ $t('admin.dashboards.guide_overview_title') }}</h1>
                <small v-if="last_updated" class="text-muted">{{ $t('admin.dashboards.updated_prefix') }} {{ last_updated }}</small>
            </div>
            <button class="btn btn-outline-primary btn-sm" @click="get_site_counts" :disabled="loading">
                <i class="fa fa-refresh" :class="{ 'fa-spin': loading }"></i> {{ $t('common.refresh') }}
            </button>
        </div>

        <integrity_issues_alert />

        <admin_notifications_list />

        <div v-if="loading && !loaded_once">
            <skeleton-loader v-for="n in 5" :key="n" />
        </div>

        <template v-else>

            <dashboard_section :title="$t('admin.dashboards.articles_section')" icon="fa-window-maximize">
                <div class="row row-cols-1 row-cols-sm-3 g-3 mb-3">
                    <div class="col">
                        <stat_card icon="fa-window-maximize" color="primary"
                            :value="counts['global_articles_count']" :label="$t('admin.dashboards.total_articles_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-language" color="primary"
                            :value="counts['us_articles_count']" :label="$t('admin.dashboards.english_articles_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-language" color="primary"
                            :value="counts['ka_articles_count']" :label="$t('admin.dashboards.georgian_articles_label')" :loading="loading" />
                    </div>
                </div>

                <entity_locale_errors :tables="['articles']" />
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.content_categories_section')" icon="fa-th-large">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 mb-3">
                    <div class="col">
                        <stat_card icon="fa-building" color="primary" :to="{ name: 'articlelist', params: { article_category: 'indoor' } }"
                            :value="counts['indoor_gyms']" :label="$t('admin.dashboards.indoor_gyms_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-snowflake-o" color="primary" :to="{ name: 'articlelist', params: { article_category: 'ice' } }"
                            :value="counts['ice_climbing']" :label="$t('admin.dashboards.ice_climbing_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-bicycle" color="primary" :to="{ name: 'articlelist', params: { article_category: 'other' } }"
                            :value="counts['other_antyvity']" :label="$t('admin.dashboards.other_activities_label')" :loading="loading" />
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-newspaper-o" color="primary" :to="{ name: 'articlelist', params: { article_category: 'news' } }"
                            :value="counts['news']" :label="$t('admin.dashboards.news_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-shield" color="primary" :to="{ name: 'articlelist', params: { article_category: 'tech_tip' } }"
                            :value="counts['techtip']" :label="$t('admin.dashboards.techtip_label')" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.shop_products_section')" icon="fa-shopping-bag">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-shopping-bag" color="light" :to="{ name: 'productsList' }"
                            :value="counts['products']" :label="$t('admin.dashboards.products_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-shopping-basket" color="light" :to="{ name: 'productsList' }"
                            :value="counts['product_categories']" :label="$t('admin.dashboards.product_categories_label')" :loading="loading" />
                    </div>
                </div>

                <entity_locale_errors :tables="['products']" />
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.services_section')" icon="fa-concierge-bell">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-concierge-bell" color="light" :to="{ name: 'servicesList' }"
                            :value="counts['services']" :label="$t('admin.dashboards.services_label')" :loading="loading" />
                    </div>
                </div>

                <entity_locale_errors :tables="['services']" />
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.tours_section')" icon="fa-bus">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-bus" color="light" :to="{ name: 'allToursList' }"
                            :value="counts['tours']" :label="$t('admin.dashboards.tours_label')" :loading="loading" />
                    </div>
                </div>

                <entity_locale_errors :tables="['tours']" />
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.films_section')" icon="fa-film">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-film" color="light" :to="{ name: 'filmsList' }"
                            :value="counts['films']" :label="$t('admin.dashboards.films_label')" :loading="loading" />
                    </div>
                </div>

                <entity_locale_errors :tables="['films']" />
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.blog_section')" icon="fa-rss">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-rss" color="light" :to="{ name: 'posts' }"
                            :value="counts['blog_posts']" :label="$t('admin.dashboards.blog_posts_label')" :loading="loading" />
                    </div>
                </div>

                <entity_locale_errors :tables="['posts']" />
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.outdoor_climbing_section')" icon="fa-tree">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-window-maximize" color="primary" :to="{ name: 'articlelist', params: { article_category: 'outdoor' } }"
                            :value="counts['outdoor_climbing']" :label="$t('admin.dashboards.outdoor_climbing_section')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-map-o" color="primary" :to="{ name: 'articlelist', params: { article_category: 'outdoor' } }"
                            :value="counts['region']" :label="$t('admin.dashboards.spot_regions_label')" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.mountaineering_section')" icon="fa-area-chart">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-area-chart" color="primary" :to="{ name: 'articlelist', params: { article_category: 'mount_route' } }"
                            :value="counts['mountaineering_route']" :label="$t('admin.dashboards.mountaineering_routes_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-area-chart" color="primary" :to="{ name: 'articlelist', params: { article_category: 'mount_route' } }"
                            :value="counts['mount_masives']" :label="$t('admin.dashboards.mount_massifs_label')" :loading="loading" />
                    </div>
                </div>

                <entity_locale_errors :tables="['mounts']" />
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.events_competitions_section')" icon="fa-calendar">
                <div class="row row-cols-2 row-cols-lg-4 g-3">
                    <div class="col">
                        <stat_card icon="fa-calendar-check-o" color="success" :to="{ name: 'eventList' }"
                            :value="counts['active_events_count']" :label="$t('admin.dashboards.active_events_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-calendar-minus-o" color="success" :to="{ name: 'eventList' }"
                            :value="counts['completed_events_count']" :label="$t('admin.dashboards.completed_events_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-calendar-check-o" color="success" :to="{ name: 'eventList' }"
                            :value="counts['active_comprtitions_count']" :label="$t('admin.dashboards.active_competitions_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-calendar-minus-o" color="success" :to="{ name: 'eventList' }"
                            :value="counts['completed_comprtitions_count']" :label="$t('admin.dashboards.completed_competitions_label')" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.climbing_spots_section')" icon="fa-map-marker">
                <div class="row row-cols-2 row-cols-lg-4 g-3 mb-3">
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['sectors_count']" :label="$t('common.sectors')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['routes_count']" :label="$t('admin.dashboards.routes_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['mtp_count']" :label="$t('admin.dashboards.multi_pitch_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['mtp_pitch_count']" :label="$t('admin.dashboards.multi_pitch_pitches_label')" :loading="loading" />
                    </div>
                </div>
                <div class="row row-cols-2 row-cols-lg-5 g-3">
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['sport_climbing_routes_count']" :label="$t('admin.dashboards.sport_climbing_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['top_rope_routes_count']" :label="$t('admin.dashboards.top_rope_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['bouldering_routes_count']" :label="$t('admin.dashboards.boulder_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['tred_routes_count']" :label="$t('admin.dashboards.trad_climbing_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['aid_routes_count']" :label="$t('admin.dashboards.aid_label')" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.gallery_images_section')" icon="fa-picture-o">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-picture-o" color="success" :to="{ name: 'multimedia' }"
                            :value="counts['gallery_images']" :label="$t('admin.dashboards.gallery_images_section')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-picture-o" color="success" :to="{ name: 'multimedia' }"
                            :value="counts['index_header_images']" :label="$t('admin.dashboards.index_header_slider_images_label')" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.comments_complaints_section')" icon="fa-comment-o">
                <div class="row row-cols-1 row-cols-sm-3 g-3">
                    <div class="col">
                        <stat_card icon="fa-gavel" color="danger" :to="{ name: 'commentsAndReviewsList' }"
                            :value="counts['article_comment_complaint_count']" :label="$t('admin.dashboards.comment_complaints_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-comment-o" color="dark" :to="{ name: 'commentsAndReviewsList' }"
                            :value="counts['article_comments_count']" :label="$t('admin.dashboards.article_comments_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-comment-o" color="dark" :to="{ name: 'commentsAndReviewsList' }"
                            :value="counts['product_comments_count']" :label="$t('admin.dashboards.product_comments_label')" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section :title="$t('admin.dashboards.users_access_section')" icon="fa-users">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 mb-3">
                    <div class="col">
                        <stat_card icon="fa-user-circle-o" color="success" :to="{ name: 'usersList' }"
                            :value="counts['users']" :label="$t('admin.dashboards.users_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-user-plus" color="success" :to="{ name: 'usersList' }"
                            :value="counts['following_users']" :label="$t('admin.dashboards.following_relationships_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-key" color="success" :to="{ name: 'usersList' }"
                            :value="counts['roles']" :label="$t('admin.dashboards.roles_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-key" color="success" :to="{ name: 'usersList' }"
                            :value="counts['permissions']" :label="$t('admin.dashboards.permissions_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-google" color="success" :to="{ name: 'usersList' }"
                            :value="counts['google_accounts_count']" :label="$t('admin.dashboards.google_accounts_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-facebook" color="success" :to="{ name: 'usersList' }"
                            :value="counts['facebook_accounts_count']" :label="$t('admin.dashboards.facebook_accounts_label')" :loading="loading" />
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-user-circle-o" color="success" :to="{ name: 'usersList' }"
                            :value="counts['guid_follovers']" :label="$t('admin.dashboards.guide_followers_label')" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-user-circle-o" color="success" :to="{ name: 'usersList' }"
                            :value="counts['shop_follovers']" :label="$t('admin.dashboards.shop_followers_label')" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

        </template>
    </div>
</template>

<script>
import dashboard_section from './DashboardSectionComponent.vue'
import stat_card from './StatCardComponent.vue'
import entity_locale_errors from './EntityLocaleErrorsComponent.vue'
import integrity_issues_alert from './IntegrityIssuesAlertComponent.vue'
import admin_notifications_list from '../notificatione/AdminNotificationsListComponent.vue'

export default {
    components: { dashboard_section, stat_card, entity_locale_errors, integrity_issues_alert, admin_notifications_list },
    data() {
        return {
            counts: {},
            loading: false,
            loaded_once: false,
            last_updated: '',
        }
    },
    mounted() {
        this.get_site_counts()
    },
    methods: {
        get_site_counts() {
            this.loading = true
            axios
                .get('set_site_data/site_data_counts')
                .then(response => {
                    this.counts = response.data
                    this.last_updated = new Date().toLocaleTimeString()
                })
                .catch(() => {
                    this.$bus.$emit('toast', {
                        type: 'danger',
                        title: this.$t('admin.dashboards.dashboard_toast_title'),
                        message: this.$t('admin.dashboards.failed_load_dashboard_data'),
                    })
                })
                .finally(() => {
                    this.loading = false
                    this.loaded_once = true
                })
        },
    }
}
</script>

<style scoped>
.dashboard-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: .5rem;
    margin-bottom: 1.25rem;
}

.dashboard-toolbar__title {
    font-size: 1.5rem;
    font-weight: 700;
    margin: 0;
}
</style>
