<template>
    <span>
        <button type="bottom" class="btn btn-default btn-send main-btn pull-right" @click="show_modal">{{ $t('guide.sector.routes_list_button') }}</button>

        <stack-modal
            :show="is_show_modal"
            :title="$t('guide.sector.modal_title')"
            @close="is_show_modal=false"
            :saveButton="{ visible: false, title: 'Save', btnClass: { 'btn btn-primary': false } }"
            :cancelButton="{ visible: false, title: 'Close', btnClass: { 'btn btn-danger': true } }"
        >

            <pre class="language-vue">

                <select class="form-control route_statistic_selection" v-model="routesPerPage" @change="refreshData">
                    <option value="5">5 {{ $t('guide.sector.routes') }}</option>
                    <option value="10">10 {{ $t('guide.sector.routes') }}</option>
                    <option value="15">15 {{ $t('guide.sector.routes') }}</option>
                    <option value="25">25 {{ $t('guide.sector.routes') }}</option>
                    <option value="50">50 {{ $t('guide.sector.routes') }}</option>
                </select>
                
                <select class="form-control route_statistic_selection" v-model="minReviews" @change="refreshData">
                    <option value="1">{{ $t('guide.sector.all_reviews') }}</option>
                    <option value="2">2+ {{ $t('guide.sector.reviews') }}</option>
                    <option value="3">3+ {{ $t('guide.sector.reviews') }}</option>
                    <option value="5">5+ {{ $t('guide.sector.reviews') }}</option>
                    <option value="10">10+ {{ $t('guide.sector.reviews') }}</option>
                </select>




                <select class="form-control route_statistic_selection" v-model="routeType" @change="refreshData">
                    <option v-for="category in routeCategories" :key="category.value" :value="category.value">
                        {{ $t(category.label) }}
                    </option>
                </select>


                <!-- Sport Routes Table - Only show if there are routes or if currently loading -->
                <div v-if="hasRoutes || loading">

                    <h1 style="margin: -80px 0;">
                        {{ routeTypeTitle }}
                        <!-- <small class="badge badge-primary ml-2" v-if="routes.data.length > 0">
                            {{ routes.data.length }} {{ $t('guide.sector.routes') }}
                        </small> -->
                    </h1>
                    <table class="table table-hover" id="dev-table">
                        <thead>

                            <tr>
                                <th>#</th>
                                <th>{{ $t('guide.sector.route_name') }}</th>
                                <th>{{ $t("guide.route.grade fr") }}</th>
                                <th>{{ $t('guide.sector.reviev_count') }}</th>
                                <th>{{ $t('guide.sector.stars') }}</th>
                                <!-- <th>{{ $t('guide.sector.region') }}</th> -->
                                <th>{{ $t('guide.sector.spot_name') }}</th>
                                <th>{{ $t('guide.sector.sector') }}</th>
                                <th>{{ $t('guide.sector.countinue') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(route, index) in routes.data" :key="'route-' + index">
                                <td>{{ index + 1 }}</td>
                                <td>{{ route.name }}</td>

                                <td v-if="route.or_grade && route.or_grade !== ''">{{ route.grade }} / {{ route.or_grade }}</td> <td v-else>{{ route.grade }}</td>
                                
                                <td>{{ route.review_count }}</td>
                                <td>
                                    <starsReiting
                                        style='margin-top: -4em; margin-bottom: -5em;'
                                        :reviews_count_prop = route.review_count
                                        :reviews_stars_prop = route.stars
                                        :rewiew_count_text_prop = false
                                    />
                                </td>

                                <!-- <td>{{ route.region }}</td> -->
                                <td>{{ route.spot_name }}</td>
                                <td>{{ route.sector }}</td>

                                <td @click="viewRoute(route)">
                                    <a style="margin-top: -5%; font-size: 120%"
                                        ><i class="fa fa-info" aria-hidden="true"></i
                                    ></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                <!-- No data message when there are no routes for the selected type -->
                <div v-if="!hasRoutes && !loading" class="text-center py-5">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">{{ $t('guide.sector.no_popular_routes_any') }}</h4>
                    <p class="text-muted">{{ $t('guide.sector.be_first_to_review_any') }}</p>
                </div>


            </pre>
        </stack-modal>
    </span>
</template>

<script>
    import StackModal from '@innologica/vue-stackable-modal'  //https://innologica.github.io/vue-stackable-modal/#sample-css
    import starsReiting  from '../../../../../global_components/StarReitingShowComponent.vue'

    export default {
        components: {
            StackModal,

            starsReiting,
        },




        props: {
            routeCategories: {
                type: Array,
                default: () => [
                    { value: 'sport', label: 'guide.sector.sport_climbing' },
                    { value: 'boulder', label: 'guide.sector.bouldering' },
                    { value: 'dry', label: 'guide.sector.dry' },
                    { value: 'ice', label: 'guide.sector.ice' }
                ]
            },
            defaultRouteType: {
                type: String,
                default: 'sport'
            }
        },


        data(){
            return{
                routes: { data: [], pagination: {} },
                is_show_modal: false,
                loading: false,
                
                // Controls
                routesPerPage: 10,
                minReviews: 1,
                routeType: this.defaultRouteType, // Use prop for initial route type
            }
        },


        computed: {
            hasRoutes() {
                return this.routes.data && this.routes.data.length > 0;
            },
            routeTypeTitle() {
                const titles = {
                    'sport': this.$t('guide.sector.sport_climbing_routes_title'),
                    'boulder': this.$t('guide.sector.boulder_routes_title'),
                    'ice': this.$t('guide.sector.ice_climbing_routes_title') || 'Popular Ice Climbing Routes',
                    'dry': this.$t('guide.sector.dry_tooling_routes_title') || 'Popular Dry Tooling Routes'
                };
                return titles[this.routeType] || this.$t('guide.sector.sport_climbing_routes_title');
            }
        },
        mounted(){
            // 
        },




        methods: {
            async show_modal(){
                this.loading = true
                await this.loadRoutes(1)
                this.is_show_modal = true
            },
            
            async loadRoutes(page = 1) {
                try {
                    this.loading = true
                    const response = await axios.get(`/get_route/get_most_popular_routes/${this.routeType}/`, {
                        params: {
                            per_page: this.routesPerPage,
                            min_reviews: this.minReviews,
                            page: page
                        }
                    })
                    this.routes = response.data
                } catch (error) {
                    this.routes = { data: [], pagination: {} }
                } finally {
                    this.loading = false
                }
            },
            
            async refreshData() {
                // Refresh the routes list when controls change
                await this.loadRoutes(1)
            },
            


            viewRoute(route) {
                // Close current modal
                this.is_show_modal = false
                
                // console.log('Navigating from modal with route data:', route);
                
                // Navigate to outdoor article page with sector and route info
                if (route.article_url_title) {
                    // Build URL with query parameters for sector and route
                    const queryParams = new URLSearchParams();
                    if (route.sector_id) {
                        queryParams.set('sector', route.sector_id);
                    }
                    if (route.route_id) {
                        queryParams.set('route', route.route_id);
                    }
                    
                    const queryString = queryParams.toString();
                    const url = `outdoor/${route.article_url_title}${queryString ? '?' + queryString : ''}`;
                    
                    // console.log('Using Vue Router to navigate to:', url);
                    // Use Vue router for navigation
                    this.$router.push(url);
                } else if (route.article_id && route.sector_id) {
                    // Fallback if no url_title but have article_id
                    const queryParams = new URLSearchParams();
                    queryParams.set('sector', route.sector_id);
                    if (route.route_id) {
                        queryParams.set('route', route.route_id);
                    }
                    
                    const queryString = queryParams.toString();
                    const url = `outdoor/article/${route.article_id}${queryString ? '?' + queryString : ''}`;
                    
                    // console.log('Fallback navigation to:', url);
                    this.$router.push(url);
                } else {
                    // Last resort: try to find by article_id if available
                    // console.warn('Missing required navigation data:', route);
                    if (route.article_id) {
                        alert('Unable to navigate to this route - missing URL title');
                    } else {
                        // Fallback to route details if sector/article info not available
                        this.$router.push(`route/${route.route_id}`);
                    }
                }
            }
        }
    }
</script>

<style>
.language-vue{
    margin: 10px 0 0 0;
    width: 95%;

    position: absolute;
    top: 0;
    bottom: 0;
    overflow-y: scroll;
    overflow-x: hidden;
}
.modal-body{
    height: 600px;
}
th:nth-child(2n+1) {
  background: #d7b396;
}

.route_statistic_selection{
    margin: -10px 0;
    float: left;
}
</style>
