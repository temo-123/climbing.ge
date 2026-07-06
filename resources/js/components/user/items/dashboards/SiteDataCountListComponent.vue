<template>
    <div class="site-data-dashboard">

        <div class="dashboard-toolbar">
            <div>
                <h1 class="dashboard-toolbar__title">Guide overview</h1>
                <small v-if="last_updated" class="text-muted">Updated {{ last_updated }}</small>
            </div>
            <button class="btn btn-outline-primary btn-sm" @click="get_site_counts" :disabled="loading">
                <i class="fa fa-refresh" :class="{ 'fa-spin': loading }"></i> Refresh
            </button>
        </div>

        <div v-if="loading && !loaded_once">
            <skeleton-loader v-for="n in 5" :key="n" />
        </div>

        <template v-else>

            <dashboard_section title="Articles" icon="fa-window-maximize">
                <div class="row row-cols-1 row-cols-sm-3 g-3 mb-3">
                    <div class="col">
                        <stat_card icon="fa-window-maximize" color="primary"
                            :value="counts['global_articles_count']" label="Total articles" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-language" color="primary"
                            :value="counts['us_articles_count']" label="English articles" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-language" color="primary"
                            :value="counts['ka_articles_count']" label="Georgian articles" :loading="loading" />
                    </div>
                </div>

                <div class="alert alert-danger" role="alert" v-if="counts['us_article_errors']">
                    <i class="fa fa-bug" aria-hidden="true"></i>
                    EN article errors — {{ counts['us_article_errors'] }}
                    <div class="float-end">
                        <button class="btn btn-warning btn-sm me-1" @click="show_errors('us')">
                            <i class="fa fa-eye"></i> View
                        </button>
                        <button class="btn btn-danger btn-sm" @click="fix_article_bug()">
                            <i class="fa fa-trash"></i> Fix (delete)
                        </button>
                    </div>
                </div>

                <div class="alert alert-danger" role="alert" v-if="counts['ka_article_errors']">
                    <i class="fa fa-bug" aria-hidden="true"></i>
                    KA article errors — {{ counts['ka_article_errors'] }}
                    <div class="float-end">
                        <button class="btn btn-warning btn-sm me-1" @click="show_errors('ka')">
                            <i class="fa fa-eye"></i> View
                        </button>
                        <button class="btn btn-danger btn-sm" @click="fix_article_bug()">
                            <i class="fa fa-trash"></i> Fix (delete)
                        </button>
                    </div>
                </div>

                <!-- Error articles modal -->
                <div v-if="error_modal_open" class="error-modal-backdrop" @click.self="error_modal_open = false">
                    <div class="error-modal">
                        <div class="error-modal-header">
                            <strong>Orphaned {{ error_modal_locale.toUpperCase() }} articles ({{ error_articles.length }})</strong>
                            <button class="btn btn-sm btn-light" @click="error_modal_open = false"><i class="fa fa-times"></i></button>
                        </div>
                        <div class="error-modal-body">
                            <div v-if="error_modal_loading" class="text-center py-3">
                                <i class="fa fa-spinner fa-spin fa-2x"></i>
                            </div>
                            <table v-else class="table table-condensed table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Locale</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="a in error_articles" :key="a.id">
                                        <td class="text-muted small">{{ a.id }}</td>
                                        <td>{{ a.title }}</td>
                                        <td><span class="badge bg-secondary">{{ a.locale }}</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section title="Content categories" icon="fa-th-large">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 mb-3">
                    <div class="col">
                        <stat_card icon="fa-building" color="primary" :to="{ name: 'articlelist', params: { article_category: 'indoor' } }"
                            :value="counts['indoor_gyms']" label="Indoor gyms" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-snowflake-o" color="primary" :to="{ name: 'articlelist', params: { article_category: 'ice' } }"
                            :value="counts['ice_climbing']" label="Ice climbing" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-bicycle" color="primary" :to="{ name: 'articlelist', params: { article_category: 'other' } }"
                            :value="counts['other_antyvity']" label="Other activities" :loading="loading" />
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-newspaper-o" color="primary" :to="{ name: 'articlelist', params: { article_category: 'news' } }"
                            :value="counts['news']" label="News" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-shield" color="primary" :to="{ name: 'articlelist', params: { article_category: 'tech_tip' } }"
                            :value="counts['techtip']" label="Techtip" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section title="Shop / Products" icon="fa-shopping-bag">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-shopping-bag" color="light" :to="{ name: 'productsList' }"
                            :value="counts['products']" label="Products" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-shopping-basket" color="light" :to="{ name: 'productsList' }"
                            :value="counts['product_categories']" label="Product categories" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section title="Outdoor climbing" icon="fa-tree">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-window-maximize" color="primary" :to="{ name: 'articlelist', params: { article_category: 'outdoor' } }"
                            :value="counts['outdoor_climbing']" label="Outdoor climbing" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-map-o" color="primary" :to="{ name: 'articlelist', params: { article_category: 'outdoor' } }"
                            :value="counts['region']" label="Spot regions" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section title="Mountaineering" icon="fa-area-chart">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-area-chart" color="primary" :to="{ name: 'articlelist', params: { article_category: 'mount_route' } }"
                            :value="counts['mountaineering_route']" label="Mountaineering climbing routes" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-area-chart" color="primary" :to="{ name: 'articlelist', params: { article_category: 'mount_route' } }"
                            :value="counts['mount_masives']" label="Mount massifs" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section title="Events & competitions" icon="fa-calendar">
                <div class="row row-cols-2 row-cols-lg-4 g-3">
                    <div class="col">
                        <stat_card icon="fa-calendar-check-o" color="success" :to="{ name: 'eventList' }"
                            :value="counts['active_events_count']" label="Active events" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-calendar-minus-o" color="success" :to="{ name: 'eventList' }"
                            :value="counts['completed_events_count']" label="Completed events" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-calendar-check-o" color="success" :to="{ name: 'eventList' }"
                            :value="counts['active_comprtitions_count']" label="Active competitions" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-calendar-minus-o" color="success" :to="{ name: 'eventList' }"
                            :value="counts['completed_comprtitions_count']" label="Completed competitions" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section title="Climbing spots" icon="fa-map-marker">
                <div class="row row-cols-2 row-cols-lg-4 g-3 mb-3">
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['sectors_count']" label="Sectors" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['routes_count']" label="Routes" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['mtp_count']" label="Multi-pitch" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['mtp_pitch_count']" label="Multi-pitch pitches" :loading="loading" />
                    </div>
                </div>
                <div class="row row-cols-2 row-cols-lg-5 g-3">
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['sport_climbing_routes_count']" label="Sport climbing" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['top_rope_routes_count']" label="Top rope" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['bouldering_routes_count']" label="Boulder" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['tred_routes_count']" label="Trad climbing" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-heartbeat" color="sectors" :to="{ name: 'routeAndSectorList' }"
                            :value="counts['aid_routes_count']" label="Aid" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section title="Gallery & images" icon="fa-picture-o">
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-picture-o" color="success" :to="{ name: 'multimedia' }"
                            :value="counts['gallery_images']" label="Gallery images" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-picture-o" color="success" :to="{ name: 'multimedia' }"
                            :value="counts['index_header_images']" label="Index header slider images" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section title="Comments & complaints" icon="fa-comment-o">
                <div class="row row-cols-1 row-cols-sm-3 g-3">
                    <div class="col">
                        <stat_card icon="fa-gavel" color="danger" :to="{ name: 'commentsAndReviewsList' }"
                            :value="counts['article_comment_complaint_count']" label="Comment complaints" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-comment-o" color="dark" :to="{ name: 'commentsAndReviewsList' }"
                            :value="counts['article_comments_count']" label="Article comments" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-comment-o" color="dark" :to="{ name: 'commentsAndReviewsList' }"
                            :value="counts['product_comments_count']" label="Product comments" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

            <dashboard_section title="Users & access" icon="fa-users">
                <div class="row row-cols-1 row-cols-sm-2 row-cols-lg-3 g-3 mb-3">
                    <div class="col">
                        <stat_card icon="fa-user-circle-o" color="success" :to="{ name: 'usersList' }"
                            :value="counts['users']" label="Users" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-user-plus" color="success" :to="{ name: 'usersList' }"
                            :value="counts['following_users']" label="Following relationships" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-key" color="success" :to="{ name: 'usersList' }"
                            :value="counts['roles']" label="Roles" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-key" color="success" :to="{ name: 'usersList' }"
                            :value="counts['permissions']" label="Permissions" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-google" color="success" :to="{ name: 'usersList' }"
                            :value="counts['google_accounts_count']" label="Google accounts" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-facebook" color="success" :to="{ name: 'usersList' }"
                            :value="counts['facebook_accounts_count']" label="Facebook accounts" :loading="loading" />
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-sm-2 g-3">
                    <div class="col">
                        <stat_card icon="fa-user-circle-o" color="success" :to="{ name: 'usersList' }"
                            :value="counts['guid_follovers']" label="Guide followers" :loading="loading" />
                    </div>
                    <div class="col">
                        <stat_card icon="fa-user-circle-o" color="success" :to="{ name: 'usersList' }"
                            :value="counts['shop_follovers']" label="Shop followers" :loading="loading" />
                    </div>
                </div>
            </dashboard_section>

        </template>
    </div>
</template>

<script>
import dashboard_section from './DashboardSectionComponent.vue'
import stat_card from './StatCardComponent.vue'

export default {
    components: { dashboard_section, stat_card },
    data() {
        return {
            counts: {},
            loading: false,
            loaded_once: false,
            last_updated: '',
            error_modal_open: false,
            error_modal_locale: '',
            error_modal_loading: false,
            error_articles: [],
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
                        title: 'Dashboard',
                        message: 'Failed to load dashboard data.',
                    })
                })
                .finally(() => {
                    this.loading = false
                    this.loaded_once = true
                })
        },

        show_errors(locale) {
            this.error_modal_locale = locale
            this.error_modal_open = true
            this.error_modal_loading = true
            this.error_articles = []
            axios.get('set_site_data/article_errors/' + locale)
                .then(r => { this.error_articles = r.data })
                .catch(() => {})
                .finally(() => { this.error_modal_loading = false })
        },

        fix_article_bug() {
            if (window.confirm('This action will delete all conflicting items! Are you sure?')) {
                axios
                    .get('set_site_data/fix_article_bugs')
                    .then(response => {
                        this.$bus.$emit('toast', {
                            type: 'success',
                            title: 'Article bugs fixed',
                            message: `Deleted ${response.data.us_deleted} EN and ${response.data.ka_deleted} KA orphaned articles.`,
                        })
                        this.get_site_counts()
                    })
                    .catch(() => {
                        this.$bus.$emit('toast', {
                            type: 'danger',
                            title: 'Dashboard',
                            message: 'Failed to fix article bugs.',
                        })
                    })
            }
        }
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

.error-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,.55);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}
.error-modal {
    background: #fff;
    border-radius: 6px;
    width: 90vw;
    max-width: 560px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 8px 32px rgba(0,0,0,.25);
}
.error-modal-header {
    padding: 14px 18px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.error-modal-body {
    overflow-y: auto;
    padding: 0;
}
</style>
